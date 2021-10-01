<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Bericht als je succesvol een nieuw boek aangemaakt hebt
$msg = 'Created Successfully!';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $author = isset($_POST['author']) ? $_POST['author'] : '';
    $isbn13 = isset($_POST['isbn13']) ? $_POST['isbn13'] : '';
    $format = isset($_POST['format']) ? $_POST['format'] : '';
    $publisher = isset($_POST['publisher']) ? $_POST['publisher'] : '';
    $pages = isset($_POST['pages']) ? $_POST['pages'] : '';
    $dimensions = isset($_POST['dimensions']) ? $_POST['dimensions'] : '';
    $overview = isset($_POST['overview']) ? $_POST['overview'] : '';
    $created = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO contacts VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$id, $title, $author, $isbn13, $format, $publisher, $pages, $dimensions, $overview]);
}
?>

<?=template_header('Create')?>

<div class="content update">
	<h2>Create Contact</h2>
    <form action="create.php" method="post">
        <label for="id">ID</label>
        <label for="title">title</label>
        <input type="text" name="id" placeholder="26" value="auto" id="id">
        <input type="text" name="title" placeholder="title" id="title">
        <label for="author">author</label>
        <label for="isbn13">isbn13</label>
        <input type="text" name="author" placeholder="auther" id="author">
        <input type="text" name="isbn13" placeholder="isbn13" id="isbn13">
        <label for="format">Format</label>
        <label for="publisher">Publisher</label>
        <input type="text" name="format" placeholder="format" id="format">
        <input type="text" name="publisher" placeholder="publisher" id="publisher">
        <label for="pages">pages</label>
        <label for="publisher">dimensions</label>
        <input type="text" name="pages" placeholder="pages" id="pages">
        <input type="text" name="dimensions" placeholder="dimensions" id="dimensions">
        <label for="publisher">overview</label>
        <label for="created">Created</label>
        <input type="text" name="overview" placeholder="overview" id="overview">
        <input type="datetime-local" name="created" value="<?=date('Y-m-d\TH:i')?>" id="created">
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>