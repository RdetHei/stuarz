<?php
require_once dirname(__DIR__) . '/config/config.php';
class ProfileController
{
    public function profile()
    {
        global $config;
        if (session_status() === PHP_SESSION_NONE) session_start();

        $targetId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
        $userId = $targetId ?: ($_SESSION['user_id'] ?? null);
        if (!$userId) {
            header("Location: index.php?page=login");
            exit;
        }

        $stmt = mysqli_prepare($config, "SELECT * FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        $level = $user['level'] ?? 'user';
        $tasksCompleted = 0;
        $attendanceCount = 0;
        $certCount = 0;
        $avgGradeStr = '-';

        if ($level === 'guru') {
            require_once dirname(__DIR__) . '/model/ClassModel.php';
            require_once dirname(__DIR__) . '/model/TasksCompletedModel.php';
            require_once dirname(__DIR__) . '/model/TaskSubmissionsModel.php';
            require_once dirname(__DIR__) . '/model/certificates.php';

            $cm = new ClassModel($config);
            $managed = $cm->getManagedClasses((int)$userId);
            $classIds = [];
            foreach ($managed as $row) { if (isset($row['id'])) $classIds[] = (int)$row['id']; }

            $stmt2 = mysqli_prepare($config, "SELECT COUNT(*) AS cnt FROM task_submissions ts JOIN tasks_completed t ON ts.task_id = t.id WHERE t.user_id = ? AND ts.grade IS NOT NULL");
            mysqli_stmt_bind_param($stmt2, 'i', $userId);
            mysqli_stmt_execute($stmt2);
            $res2 = mysqli_stmt_get_result($stmt2);
            if ($res2 && ($r = mysqli_fetch_assoc($res2))) { $tasksCompleted = (int)($r['cnt'] ?? 0); }
            mysqli_stmt_close($stmt2);

            if (!empty($classIds)) {
                $in = implode(',', array_map('intval', $classIds));
                $sqlAtt = "SELECT COUNT(*) AS cnt FROM attendance WHERE class_id IN (" . $in . ")";
                $resAtt = mysqli_query($config, $sqlAtt);
                if ($resAtt && ($ra = mysqli_fetch_assoc($resAtt))) { $attendanceCount = (int)($ra['cnt'] ?? 0); }
            }

            $certModel = new certificates($config);
            $certCount = $certModel->getCountByUserId((int)$userId);

            $avgGradeStr = '-';
        } elseif ($level === 'admin') {
            require_once dirname(__DIR__) . '/model/certificates.php';

            $stmtT = mysqli_prepare($config, "SELECT COUNT(*) AS cnt FROM task_submissions WHERE user_id = ? AND grade IS NOT NULL AND (status = 'graded' OR review_status = 'graded')");
            mysqli_stmt_bind_param($stmtT, 'i', $userId);
            mysqli_stmt_execute($stmtT);
            $resT = mysqli_stmt_get_result($stmtT);
            if ($resT && ($rt = mysqli_fetch_assoc($resT))) { $tasksCompleted = (int)($rt['cnt'] ?? 0); }
            mysqli_stmt_close($stmtT);

            $stmtA = mysqli_prepare($config, "SELECT COUNT(*) AS cnt FROM attendance WHERE user_id = ?");
            mysqli_stmt_bind_param($stmtA, 'i', $userId);
            mysqli_stmt_execute($stmtA);
            $resA = mysqli_stmt_get_result($stmtA);
            if ($resA && ($ra = mysqli_fetch_assoc($resA))) { $attendanceCount = (int)($ra['cnt'] ?? 0); }
            mysqli_stmt_close($stmtA);

            $certModel = new certificates($config);
            $certCount = $certModel->getCountByUserId((int)$userId);

            $stmtG = mysqli_prepare($config, "SELECT ROUND(AVG(score),1) AS avg_score FROM grades WHERE user_id = ?");
            mysqli_stmt_bind_param($stmtG, 'i', $userId);
            mysqli_stmt_execute($stmtG);
            $resG = mysqli_stmt_get_result($stmtG);
            if ($resG && ($rg = mysqli_fetch_assoc($resG))) {
                $avg = $rg['avg_score'];
                if ($avg !== null && $avg !== '') $avgGradeStr = (string)$avg;
            }
            mysqli_stmt_close($stmtG);
        } else {
            $stmtT = mysqli_prepare($config, "SELECT COUNT(*) AS cnt FROM task_submissions WHERE user_id = ? AND grade IS NOT NULL AND (status = 'graded' OR review_status = 'graded')");
            mysqli_stmt_bind_param($stmtT, 'i', $userId);
            mysqli_stmt_execute($stmtT);
            $resT = mysqli_stmt_get_result($stmtT);
            if ($resT && ($rt = mysqli_fetch_assoc($resT))) { $tasksCompleted = (int)($rt['cnt'] ?? 0); }
            mysqli_stmt_close($stmtT);

            $stmtA = mysqli_prepare($config, "SELECT COUNT(*) AS cnt FROM attendance WHERE user_id = ?");
            mysqli_stmt_bind_param($stmtA, 'i', $userId);
            mysqli_stmt_execute($stmtA);
            $resA = mysqli_stmt_get_result($stmtA);
            if ($resA && ($ra = mysqli_fetch_assoc($resA))) { $attendanceCount = (int)($ra['cnt'] ?? 0); }
            mysqli_stmt_close($stmtA);

            require_once dirname(__DIR__) . '/model/certificates.php';
            $certModel = new certificates($config);
            $certCount = $certModel->getCountByUserId((int)$userId);

            $stmtG = mysqli_prepare($config, "SELECT ROUND(AVG(score),1) AS avg_score FROM grades WHERE user_id = ?");
            mysqli_stmt_bind_param($stmtG, 'i', $userId);
            mysqli_stmt_execute($stmtG);
            $resG = mysqli_stmt_get_result($stmtG);
            if ($resG && ($rg = mysqli_fetch_assoc($resG))) {
                $avg = $rg['avg_score'];
                if ($avg !== null && $avg !== '') $avgGradeStr = (string)$avg;
            }
            mysqli_stmt_close($stmtG);

            if ($avgGradeStr === '-') {
                $stmtTS = mysqli_prepare($config, "SELECT ROUND(AVG(grade),1) AS avg_score FROM task_submissions WHERE user_id = ? AND grade IS NOT NULL AND (status = 'graded' OR review_status = 'graded')");
                mysqli_stmt_bind_param($stmtTS, 'i', $userId);
                mysqli_stmt_execute($stmtTS);
                $resTS = mysqli_stmt_get_result($stmtTS);
                if ($resTS && ($rts = mysqli_fetch_assoc($resTS))) {
                    $avgts = $rts['avg_score'];
                    if ($avgts !== null && $avgts !== '') $avgGradeStr = (string)$avgts;
                }
                mysqli_stmt_close($stmtTS);
            }
        }

        if (is_array($user)) {
            $user['tasks_completed'] = $tasksCompleted;
            $user['attendance'] = $attendanceCount;
            $user['certificates'] = $certCount;
            $user['average_grade'] = $avgGradeStr;
        }

        if ($user && empty($targetId)) {
            $_SESSION['user'] = $user;
        }

        $title = "Profile - Stuarz";
        $description = "Your profile details";
        $content = dirname(__DIR__) . '/views/pages/users/profile.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function studentProfile()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) { header('Location: index.php?page=login'); exit; }
        global $config;
        $stmt = mysqli_prepare($config, "SELECT * FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        $content = dirname(__DIR__) . '/views/pages/student/profile.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function updateStudentProfile()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) { header('Location: index.php?page=login'); exit; }
        global $config;

        $bio = trim($_POST['bio'] ?? '');
        $allowedExt = ['jpg','jpeg','png'];
        $maxSize = 2 * 1024 * 1024;

        $avatarPath = null;
        if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $orig = $_FILES['avatar']['name'];
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt, true)) {
                $_SESSION['error'] = 'Avatar harus berupa JPG/PNG.';
                header('Location: index.php?page=student/profile'); exit;
            }
            if ($_FILES['avatar']['size'] > $maxSize) {
                $_SESSION['error'] = 'Ukuran avatar maksimal 2MB.';
                header('Location: index.php?page=student/profile'); exit;
            }
            $dir = 'public/uploads/avatars/';
            if (!file_exists($dir)) mkdir($dir, 0777, true);
            $safe = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($orig, PATHINFO_FILENAME));
            $fileName = $userId . '_' . time() . '_' . $safe . '.' . $ext;
            $dest = $dir . $fileName;
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                $_SESSION['error'] = 'Gagal mengunggah avatar.';
                header('Location: index.php?page=student/profile'); exit;
            }
            $avatarPath = $dest;
        }

        $fields = [];
        $params = '';
        $values = [];
        if ($bio !== '') { $fields[] = 'bio = ?'; $params .= 's'; $values[] = $bio; }
        if ($avatarPath !== null) { $fields[] = 'avatar = ?'; $params .= 's'; $values[] = $avatarPath; }

        if (!empty($fields)) {
            $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
            $stmt = mysqli_prepare($config, $sql);
            $params .= 'i'; $values[] = $userId;
            mysqli_stmt_bind_param($stmt, $params, ...$values);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        $_SESSION['success'] = 'Profil diperbarui.';
        header('Location: index.php?page=student/profile'); exit;
    }
}