<?php
include '../admin/functions.php';
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
    <form action="../admin/update.php?id=<?=$contact['id']?>" method="post">
        <label for="title">Uitgeleend</label>
        <input type="text" name="uitgeleend" placeholder="Ja of Nee" value="<?=$contact['uitgeleend']?>" id="uitgeleend">
        <!-- Submit -->
        <input type="submit" value="Update">
    </form>
    <!-- Dit zorgt ervoor dat er een bericht wordt gestuurd dat het geupdate is -->
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>