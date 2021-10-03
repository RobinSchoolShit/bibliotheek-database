<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        $id = isset($_POST['id']) ? $_POST['id'] : NULL;
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $author = isset($_POST['author']) ? $_POST['author'] : '';
        $isbn13 = isset($_POST['isbn13']) ? $_POST['isbn13'] : '';
        $format = isset($_POST['format']) ? $_POST['format'] : '';
        $publisher = isset($_POST['publisher']) ? $_POST['publisher'] : '';
        $pages = isset($_POST['pages']) ? $_POST['pages'] : '';
        $dimensions = isset($_POST['dimensions']) ? $_POST['dimensions'] : '';
        $overview = isset($_POST['overview']) ? $_POST['overview'] : '';
        $uitgeleend = isset($_POST['uitgeleend']) ? $_POST['uitgeleend'] : '';
        // Update de records
        $stmt = $pdo->prepare('UPDATE contacts SET title = ?, author = ?, isbn13 = ?, `format` = ?, publisher = ?, pages = ?, dimensions = ?, overview = ?, uitgeleend = ? WHERE id = ?');
        $stmt->execute([$title, $author, $isbn13, $format, $publisher, $pages, $dimensions, $overview, $uitgeleend, $id]);
        $msg = 'Updated Successfully!';
    }
    // Haal het contact uit de contactentabel
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}

// Dit is de code die ervoor zorgt dat je niet op de pagina kan komen zonder in te loggen
session_start();
// Als je niet bent ingelogd ga je terug naar index.html dus de inlog pagina
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../cms-login/index.html');
	exit;
}

?>

<?=template_header('Read')?>

<div class="content update">
	<h2>Update Contact #<?=$contact['id']?></h2>
    <form action="update.php?id=<?=$contact['id']?>" method="post">
        <label for="id">ID</label>
        <label for="name">Title</label>
        <input type="text" name="id" placeholder="1" value="<?=$contact['id']?>" id="id">
        <input type="text" name="title" placeholder="title" value="<?=$contact['title']?>" id="title">
        <label for="email">Author</label>
        <label for="phone">Isbn 13</label>
        <input type="text" name="author" placeholder="author" value="<?=$contact['author']?>" id="author">
        <input type="text" name="isbn13" placeholder="isbn13" value="<?=$contact['isbn13']?>" id="isbn13">
        <label for="title">Format</label>
        <label for="phone">publisher</label>
        <input type="text" name="format" placeholder="format" value="<?=$contact['format']?>" id="format">
        <input type="text" name="publisher" placeholder="publisher" value="<?=$contact['publisher']?>" id="publisher">
        <label for="title">pages</label>
        <label for="phone">dimensions</label>
        <input type="text" name="pages" placeholder="pages" value="<?=$contact['pages']?>" id="pages">
        <input type="text" name="dimensions" placeholder="dimensions" value="<?=$contact['dimensions']?>" id="dimensions">
        <label for="title">overview</label>
        <label for="title">Uitgeleend</label>
        <input type="text" name="overview" placeholder="overview" value="<?=$contact['overview']?>" id="overview">
        <input type="text" name="uitgeleend" placeholder="uitgeleend" value="<?=$contact['uitgeleend']?>" id="uitgeleend">
        <!-- Submit -->
        <input type="submit" value="Update">
    </form>
    <!-- Dit zorgt ervoor dat er een bericht wordt gestuurd dat het geupdate is -->
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>