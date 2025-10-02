<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/SubjectsModel.php';
class SubjectController {
    private $model;
    public function __construct() {
        global $config;
        $this->model = new SubjectsModel($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }
    public function index() {
        $subjects = $this->model->getAll();
    $content = dirname(__DIR__) . '/views/pages/subjects/index.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function create() {
    $content = dirname(__DIR__) . '/views/pages/subjects/form.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function store() {
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        if ($name === '' || $desc === '') {
            $_SESSION['flash'] = 'Nama dan deskripsi wajib diisi!';
            header('Location: index.php?page=subjects/create'); exit;
        }
        $ok = $this->model->create(['name'=>$name, 'description'=>$desc]);
        $_SESSION['flash'] = $ok ? 'Subject berhasil ditambah.' : 'Gagal menambah subject.';
        header('Location: index.php?page=subjects'); exit;
    }
    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        $subject = $this->model->getById($id);
    $content = dirname(__DIR__) . '/views/pages/subjects/form.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function update() {
        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        if ($name === '' || $desc === '') {
            $_SESSION['flash'] = 'Nama dan deskripsi wajib diisi!';
            header('Location: index.php?page=subjects/edit&id='.$id); exit;
        }
        $ok = $this->model->update($id, ['name'=>$name, 'description'=>$desc]);
        $_SESSION['flash'] = $ok ? 'Subject berhasil diupdate.' : 'Gagal update subject.';
        header('Location: index.php?page=subjects'); exit;
    }
    public function delete() {
        $id = intval($_POST['id'] ?? 0);
        $ok = $this->model->delete($id);
        $_SESSION['flash'] = $ok ? 'Subject dihapus.' : 'Gagal hapus subject.';
        header('Location: index.php?page=subjects'); exit;
    }
}
