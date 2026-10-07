<?php
require_once 'config.php';
require_login();
$counts = ['Singly'=>0,'Doubly'=>0,'Circular'=>0];
$stmt = $pdo->query("SELECT type, COUNT(*) total FROM activities GROUP BY type");
foreach ($stmt as $row) $counts[$row['type']] = $row['total'];
$total = array_sum($counts);
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Data Structures Activity Lab</title><link rel="stylesheet" href="assets/style.css">
</head><body>
<?php include 'partials/nav.php'; ?>
<main class="hero">
<section class="hero-card"><span class="float-node n1">→</span><span class="float-node n2">↔</span><span class="float-node n3">∞</span>
<span class="eyebrow">XAMPP + PHP + MySQL + JavaScript</span>
<h1>Data Structures Activity Lab</h1>
<p class="lead">A Activity Management-based web application where visitors can browse linked-list activities, read explanations, view source code, run activities directly in the browser, and observe the result.</p>
<div class="actions"><a class="btn primary" href="activities.php">Browse Activities</a><?php if(is_admin()): ?><a class="btn" href="admin.php">Manage Activities</a><?php endif; ?></div>
</section>
<div class="section-label">Inside the Lab</div>
<section class="info-grid">
<div class="info-card"><h3>Group Members</h3><p>Member 1<br>Member 2<br>Member 3<br>Member 4</p><small>Edit these names in index.php.</small></div>
<div class="info-card"><h3>Project Purpose</h3><p>Demonstrate singly, doubly, and circular linked-list operations with an interactive browser runner.</p></div>
<div class="info-card"><h3>Activity Count</h3><p class="big-number"><?= $total ?></p><p>activities available</p></div>
</section>
<div class="section-label" style="margin-top:34px">Choose a Structure</div>
<section class="type-grid">
<?php foreach ($counts as $type=>$count): ?>
<a class="type-card" href="activities.php?type=<?= urlencode($type) ?>"><span><?= htmlspecialchars($type) ?></span><strong><?= $count ?></strong><small>activities</small></a>
<?php endforeach; ?>
</section>
</main>
<?php include 'partials/footer.php'; ?>
</body></html>