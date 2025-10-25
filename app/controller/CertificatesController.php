<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/certificates.php';

class CertificatesController
{
    public function certificate()
    {
        global $config;

        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $me = $_SESSION['user'] ?? null;

        if (!$me) {
            header('Location: index.php?page=login');
            exit;
        }

        $certificatesModel = new certificates($config);
        $scope = isset($_GET['scope']) ? (string)$_GET['scope'] : (($me['level'] === 'admin') ? 'all' : 'my');
        $showAll = ($me['level'] === 'admin' && $scope === 'all');
        if ($showAll) {
            $certificates = $certificatesModel->getAll();
        } else {
            $certificates = $certificatesModel->getByUserId((int)$me['id']);
        }

        // Set base URL for file paths
        if (!isset($baseUrl)) {
            $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
            if ($baseUrl === '/') $baseUrl = '';
        }

        $content = dirname(__DIR__) . '/views/pages/certificates/certificate.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function myCertificates()
    {
        global $config;

        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $me = $_SESSION['user'] ?? null;

        if (!$me) {
            header('Location: index.php?page=login');
            exit;
        }

        $certificatesModel = new certificates($config);
        $certificates = $certificatesModel->getByUserId($me['id']);

        // Set base URL for file paths
        if (!isset($baseUrl)) {
            $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
            if ($baseUrl === '/') $baseUrl = '';
        }

        $content = dirname(__DIR__) . '/views/pages/certificates/certificate.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function upload()
    {
        global $config;

        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $me = $_SESSION['user'] ?? null;

        if (!$me) {
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = mysqli_real_escape_string($config, $_POST['title'] ?? '');
            $description = mysqli_real_escape_string($config, $_POST['description'] ?? '');
            $issued_by = mysqli_real_escape_string($config, $_POST['issued_by'] ?? '');
            $issued_at = mysqli_real_escape_string($config, $_POST['issued_at'] ?? '');

            // Handle file upload
            $uploadDir = str_replace('/', DIRECTORY_SEPARATOR, dirname(__DIR__, 2) . '/public/uploads/certificates');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $uploadDir = $uploadDir . DIRECTORY_SEPARATOR;

            $fileName = '';
            if (isset($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] === UPLOAD_ERR_OK) {
                $fileExtension = pathinfo($_FILES['certificate_file']['name'], PATHINFO_EXTENSION);
                $fileName = time() . '_certificate_' . uniqid() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['certificate_file']['tmp_name'], $filePath)) {
                    // Debug: log file upload info
                    error_log("Certificate Upload - fileName: " . $fileName);
                    error_log("Certificate Upload - filePath: " . $filePath);
                    
                    $certificatesModel = new certificates($config);
                    $data = [
                        'user_id' => $me['id'],
                        'title' => $title,
                        'description' => $description,
                        'file_path' => 'uploads/certificates/' . $fileName,
                        'issued_by' => $issued_by,
                        'issued_at' => $issued_at
                    ];
                    
                    error_log("Certificate Upload - saved path: " . $data['file_path']);

                    if ($certificatesModel->create($data)) {
                        $_SESSION['success'] = 'Sertifikat berhasil diupload!';
                    } else {
                        $_SESSION['error'] = 'Gagal menyimpan sertifikat.';
                    }
                } else {
                    $_SESSION['error'] = 'Gagal mengupload file.';
                }
            } else {
                $_SESSION['error'] = 'File sertifikat wajib diupload.';
            }

            header('Location: index.php?page=certificates');
            exit;
        }
    }

    public function delete()
    {
        global $config;

        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $me = $_SESSION['user'] ?? null;

        if (!$me) {
            header('Location: index.php?page=login');
            exit;
        }

        // pastikan request POST (form JS mengirim POST)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=certificates');
            exit;
        }

        // ambil id dari POST, bukan GET
        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            $certificatesModel = new certificates($config);

            // Check if user owns this certificate or is admin
            $certificate = $certificatesModel->getById($id);
            if ($certificate && ($certificate['user_id'] == $me['id'] || $me['level'] === 'admin')) {
                // Delete file from server
                if ($certificate['file_path'] && file_exists(__DIR__ . '/../../public/' . $certificate['file_path'])) {
                    unlink(__DIR__ . '/../../public/' . $certificate['file_path']);
                }

                if ($certificatesModel->delete($id)) {
                    $_SESSION['success'] = 'Sertifikat berhasil dihapus!';
                } else {
                    $_SESSION['error'] = 'Gagal menghapus sertifikat.';
                }
            } else {
                $_SESSION['error'] = 'Anda tidak memiliki izin untuk menghapus sertifikat ini.';
            }
        }

        header('Location: index.php?page=certificates');
        exit;
    }
}
