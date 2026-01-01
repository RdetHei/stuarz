<?php
class PrintController {
    
    public function index() {
        global $config;
        
        $title = "Print Data - Stuarz";
        $description = "Print data dari semua tabel";
        
        $content = dirname(__DIR__) . '/views/pages/print/index.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    
    public function printTable() {
        global $config;
        $table = $_GET['table'] ?? '';
        
        if (empty($table)) {
            header('Location: index.php?page=print');
            exit;
        }
        
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            die('Invalid table name');
        }
        
        $data = $this->getTableData($config, $table);
        $columns = $this->getTableColumns($config, $table);
        $tableName = ucfirst(str_replace('_', ' ', $table));
        
        include dirname(__DIR__) . '/views/pages/print/print_table.php';
    }
    
    public function printAll() {
        header('HTTP/1.1 404 Not Found');
        echo 'Print All is no longer available.';
        exit;
    }
    
    private function getAllTables($db) {
        $tables = [];
        $sql = "SHOW TABLES";
        $result = mysqli_query($db, $sql);
        
        if ($result) {
            while ($row = mysqli_fetch_array($result)) {
                $tables[] = $row[0];
            }
            mysqli_free_result($result);
        }
        
        return $tables;
    }
    
    private function getTableColumns($db, $table) {
        $columns = [];
        $sql = "SHOW COLUMNS FROM `{$table}`";
        $result = mysqli_query($db, $sql);
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $columns[] = $row['Field'];
            }
            mysqli_free_result($result);
        }
        
        return $columns;
    }
    
    private function getTableData($db, $table) {
        $data = [];
        $sql = "SELECT * FROM `{$table}`";
        $result = mysqli_query($db, $sql);
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            mysqli_free_result($result);
        }
        
        return $data;
    }
}