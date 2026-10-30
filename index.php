<?php
session_start();
require_once __DIR__ . '/config/db.php';

$search = trim($_GET['search'] ?? '');

// ดึงรายการการ์ดจากฐานข้อมูลตามคำค้นหา
if ($search !== '') {
    $sql   = "SELECT * FROM cards WHERE name_en LIKE ? OR name_th LIKE ? OR card_type LIKE ? ORDER BY card_id DESC";
    $stmt  = mysqli_prepare($conn, $sql);
    $param = "%{$search}%";
    mysqli_stmt_bind_param($stmt, 'sss', $param, $param, $param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $sql    = "SELECT * FROM cards ORDER BY card_id DESC";
    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riftbound Pocket TH</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="navbar">
        <h1>Riftbound Pocket TH</h1>
        <div>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <span>สวัสดี, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="logout.php" class="btn-sm danger">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn-sm">Admin Login</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
        <section class="search-section">
            <form method="GET" action="index.php">
                <input type="text" name="search" placeholder="ค้นหาชื่อการ์ด (TH/EN) หรือประเภท..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn">ค้นหา</button>
            </form>
        </section>

        <section class="card-grid">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($card = mysqli_fetch_assoc($result)): ?>
                    <div class="card-item">
                        <img src="<?= htmlspecialchars($card['image_url'] ?: 'assets/images/no-image.jpg') ?>" alt="<?= htmlspecialchars($card['name_en']) ?>">
                        <h3><?= htmlspecialchars($card['name_th']) ?></h3>
                        <p class="en-name"><?= htmlspecialchars($card['name_en']) ?></p>
                        <span class="badge"><?= htmlspecialchars($card['card_type']) ?> | Cost: <?= htmlspecialchars((string)$card['cost']) ?></span>
                        <div class="effect-box">
                            <strong>เอฟเฟกต์ (TH):</strong>
                            <p><?= nl2br(htmlspecialchars($card['effect_th'] ?? '')) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-data">ไม่พบข้อมูลการ์ด</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
