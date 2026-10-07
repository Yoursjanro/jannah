<?php
require_once 'config.php';
require_login();
$customSeeds = [
 ['Singly Linked List - Treasure Hunt','Singly','Automatic treasure hunt demonstrating singly linked-list operations.','singly_linked_list.pdf'],
 ['Doubly Linked List - Music Playlist','Doubly','Functional music playlist demonstrating doubly linked-list operations.','doubly_linked_list.pdf'],
 ['Circular Linked List - Hot Potato','Circular','Automatic Hot Potato game demonstrating circular linked-list operations.','circular_linked_list.pdf']
];
foreach($customSeeds as $cs){ $q0=$pdo->prepare('SELECT id FROM activities WHERE title=? LIMIT 1'); $q0->execute([$cs[0]]); if(!$q0->fetch()){ $ins=$pdo->prepare('INSERT INTO activities(title,type,description,code,input_hint,reference_pdf) VALUES(?,?,?,?,?,?)'); $ins->execute([$cs[0],$cs[1],$cs[2],'','',''.$cs[3]]); } }
$q = trim($_GET['q'] ?? '');
$type = $_GET['type'] ?? '';
$sql = "SELECT * FROM activities WHERE 1";
$params = [];
if ($q !== '') { $sql .= " AND (title LIKE ? OR description LIKE ? OR type LIKE ?)"; $like="%$q%"; $params=[$like,$like,$like]; }
if (in_array($type,['Singly','Doubly','Circular'],true)) { $sql .= " AND type = ?"; $params[]=$type; }
$sql .= " ORDER BY FIELD(type,'Singly','Doubly','Circular'), title";
$stmt=$pdo->prepare($sql); $stmt->execute($params); $activities=$stmt->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Back</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'partials/nav.php'; ?>
<main class="container">
<div class="page-heading"><div><span class="eyebrow">Back</span><h1>Browse Activities</h1><p>Search and filter every activity by linked-list type.</p></div><?php if(is_admin()): ?><a class="btn primary" href="admin.php">Activity Manager</a><?php endif; ?></div>
<form class="searchbar" method="get"><input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search title, description, or type..."><select name="type"><option value="">All Types</option><?php foreach(['Singly','Doubly','Circular'] as $t): ?><option <?= $type===$t?'selected':'' ?>><?= $t ?></option><?php endforeach; ?></select><button class="btn primary">Search</button><a class="btn" href="activities.php">Reset</a></form>
<?php
$gameCards = [
 'Singly' => ['🏴‍☠️ Treasure Hunt','One-way automatic treasure hunt. Demonstrates HEAD → node → NULL traversal.','singly'],
 'Doubly' => ['🎵 Music Playlist','Real audio playlist with forward and backward navigation using a doubly linked list.','doubly'],
 'Circular' => ['🥔 Hot Potato','Automatic turn-based game where the last player connects back to the first.','circular']
];
if ($type !== '' && isset($gameCards[$type])):
  [$gameTitle,$gameDesc,$gameKey] = $gameCards[$type];
?>
<section class="catalog-group"><h2><?= htmlspecialchars($type) ?> Linked List Game</h2><div class="card-grid">
<article class="activity-card"><span class="tag <?= strtolower($type) ?>"><?= htmlspecialchars($type) ?></span><h3><?= htmlspecialchars($gameTitle) ?></h3><p><?= htmlspecialchars($gameDesc) ?></p><a class="btn primary" href="activity.php?game=<?= urlencode($gameKey) ?>">Open <?= htmlspecialchars($gameTitle) ?> →</a></article>
</div></section>
<?php elseif ($type === ''): ?>
<section class="catalog-group"><h2>🎮 Interactive Linked-List Games</h2><p class="legend">Choose a linked-list type to see only its game.</p><div class="card-grid">
<?php foreach($gameCards as $gameType=>$card): [$gameTitle,$gameDesc,$gameKey]=$card; ?><article class="activity-card"><span class="tag <?= strtolower($gameType) ?>"><?= htmlspecialchars($gameType) ?></span><h3><?= htmlspecialchars($gameTitle) ?></h3><p><?= htmlspecialchars($gameDesc) ?></p><a class="btn primary" href="activities.php?type=<?= urlencode($gameType) ?>">View <?= htmlspecialchars($gameType) ?> Game →</a></article><?php endforeach; ?>
</div></section>
<?php endif; ?>
<?php if (!$activities): ?><div class="empty">No activities found.</div><?php endif; ?>
<?php foreach(['Singly','Doubly','Circular'] as $group): $groupItems=array_filter($activities,fn($a)=>$a['type']===$group); if(!$groupItems) continue; ?>
<section class="catalog-group"><h2><?= $group ?> Linked List</h2><div class="card-grid">
<?php foreach($groupItems as $a): ?><article class="activity-card"><span class="tag <?= strtolower($a['type']) ?>"><?= htmlspecialchars($a['type']) ?></span><h3><?= htmlspecialchars($a['title']) ?></h3><p><?= htmlspecialchars($a['description']) ?></p><?php if(!empty($a['reference_pdf'])): ?><small class="pdf-label">📄 <?= htmlspecialchars($a['reference_pdf']) ?></small><?php endif; ?><a class="btn primary" href="activity.php?id=<?= $a['id'] ?>">Open Activity →</a></article><?php endforeach; ?>
</div></section>
<?php endforeach; ?>
</main><?php include 'partials/footer.php'; ?></body></html>