<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $token    = $_POST['csrf'] ?? '';

    if (!hash_equals($_SESSION['csrf'], $token)) {
        $error = 'คำขอไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง';
    } elseif ($username === '' || $password === '') {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } else {
        $sql  = "SELECT user_id, username, password, role FROM users WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $uid, $uname, $hash, $role);
            $found = mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if ($found && password_verify($password, $hash)) {
                session_regenerate_id(true);
                $_SESSION['user_id']  = $uid;
                $_SESSION['username'] = $uname;
                $_SESSION['role']     = $role;
                $_SESSION['csrf']     = bin2hex(random_bytes(32));

                header('Location: index.php');
                exit;
            }
            $error = 'Username หรือ Password ไม่ถูกต้อง';
        } else {
            $error = 'เกิดข้อผิดพลาดของระบบ กรุณาลองใหม่ภายหลัง';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Riftbound Pocket TH</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="center-body">
    <div class="card-box">
        <h2>Riftbound Pocket TH</h2>
        <p class="en-name">Admin Login</p>
        <?php if ($error): ?>
            <div class="alert err"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['logged_out'])): ?>
            <div class="alert ok">ออกจากระบบเรียบร้อยแล้ว</div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn">Login</button>
            <a href="index.php" class="back-link">← กลับหน้าแรก</a>
        </form>
    </div>
</body>
</html>
