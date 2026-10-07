<?php
require_once 'config.php';
require_admin();
$error = '';
$flash = get_flash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $pdo->prepare('DELETE FROM activities WHERE id=?');
                $stmt->execute([$id]);
                flash($stmt->rowCount() ? 'Activity deleted successfully.' : 'Activity was not found.');
            }
            header('Location: admin.php'); exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $type = $_POST['type'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $code = trim($_POST['code'] ?? '');
        $hint = trim($_POST['input_hint'] ?? '');
        $reference = trim($_POST['reference_pdf'] ?? '');
        $allowedRefs = ['', 'singly_linked_list.pdf', 'doubly_linked_list.pdf', 'circular_linked_list.pdf'];

        if ($title === '' || !in_array($type, ['Singly','Doubly','Circular'], true) || $description === '' || $code === '') {
            $error = 'Please complete the title, type, description, and runner code.';
        } elseif (!in_array($reference, $allowedRefs, true)) {
            $error = 'Invalid reference PDF selected.';
        } elseif ($action === 'create') {
            $stmt = $pdo->prepare('INSERT INTO activities(title,type,description,code,input_hint,reference_pdf) VALUES(?,?,?,?,?,?)');
            $stmt->execute([$title,$type,$description,$code,$hint,$reference]);
            flash('Activity created successfully.'); header('Location: admin.php'); exit;
        } elseif ($action === 'update' && $id > 0) {
            $stmt = $pdo->prepare('UPDATE activities SET title=?,type=?,description=?,code=?,input_hint=?,reference_pdf=? WHERE id=?');
            $stmt->execute([$title,$type,$description,$code,$hint,$reference,$id]);
            flash('Activity updated successfully.'); header('Location: admin.php'); exit;
        } else {
            $error = 'Invalid activity action.';
        }
    } catch (PDOException $e) {
        $error = 'The activity could not be saved. Please check the form and database connection.';
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM activities WHERE id=?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    if (!$edit) { flash('Activity not found.', 'error'); header('Location: admin.php'); exit; }
}
$activities = $pdo->query("SELECT * FROM activities ORDER BY FIELD(type,'Singly','Doubly','Circular'),title")->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Activity Manager</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'partials/nav.php'; ?><main class="container">
<div class="page-heading"><div><span class="eyebrow">Admin Only</span><h1>Activity Manager</h1><p>Add, edit, and delete activities.</p></div><a class="btn" href="activities.php">View Activities</a></div>
<?php if($flash): ?><div class="notice"><?= e($flash['message']) ?></div><?php endif; ?>
<?php if($error): ?><div class="error-box"><?= e($error) ?></div><?php endif; ?>
<section class="panel form-panel"><h2><?= $edit?'Edit Activity':'Add Activity' ?></h2>
<form method="post" class="admin-form">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="<?= $edit?'update':'create' ?>"><input type="hidden" name="id" value="<?= e($edit['id']??'') ?>">
<label>Title<input required maxlength="150" name="title" value="<?= e($edit['title']??'') ?>"></label>
<label>Type<select required name="type"><?php foreach(['Singly','Doubly','Circular'] as $t): ?><option value="<?= e($t) ?>" <?= (($edit['type']??'')===$t)?'selected':'' ?>><?= e($t) ?></option><?php endforeach; ?></select></label>
<label>Description<textarea required name="description" rows="4"><?= e($edit['description']??'') ?></textarea></label>
<label>Input Hint<input name="input_hint" value="<?= e($edit['input_hint']??'') ?>"></label>
<label>Reference PDF<select name="reference_pdf"><option value="">No reference PDF</option><?php foreach(['singly_linked_list.pdf'=>'Singly Linked List PDF','doubly_linked_list.pdf'=>'Doubly Linked List PDF','circular_linked_list.pdf'=>'Circular Linked List PDF'] as $file=>$label): ?><option value="<?= e($file) ?>" <?= (($edit['reference_pdf']??'')===$file)?'selected':'' ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
<label>Runner JavaScript Code<textarea required name="code" rows="12"><?= e($edit['code']??'const values = input.values.map(Number);\nreturn values.join(" -> ");') ?></textarea></label>
<button class="btn primary"><?= $edit?'Save Changes':'Add Activity' ?></button><?php if($edit): ?><a class="btn" href="admin.php">Cancel</a><?php endif; ?>
</form></section>
<section class="panel"><h2>All Activities</h2><div class="table-wrap"><table><thead><tr><th>Title</th><th>Type</th><th>Actions</th></tr></thead><tbody>
<?php foreach($activities as $a): ?><tr><td><?= e($a['title']) ?></td><td><span class="tag <?= strtolower(e($a['type'])) ?>"><?= e($a['type']) ?></span></td><td class="actions-cell"><a href="activity.php?id=<?= (int)$a['id'] ?>">View</a><a href="admin.php?edit=<?= (int)$a['id'] ?>">Edit</a><form method="post" onsubmit="return confirm('Delete this activity?')"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$a['id'] ?>"><button class="link-danger">Delete</button></form></td></tr><?php endforeach; ?>
</tbody></table></div></section>
</main><?php include 'partials/footer.php'; ?></body></html>
