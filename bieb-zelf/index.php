<?php
include '../admin/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 3;

// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM contacts ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_contacts = $pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn();

// Dit is de code die ervoor zorgt dat je niet op de pagina kan komen zonder in te loggen
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../cms-login/index.html');
	exit;
}

?>

<?=template_header('Read')?>

<div class="content read">
	<h2>Bibliotheek Website</h2>
	<table>
        <thead>
            <tr>
                <td>#</td>
                <td>Title</td>
                <td>Author</td>
                <td>Isbn 13</td>
                <td>Format</td>
                <td>Publisher</td>
                <td>Pages</td>
                <td>Dimensions</td>
                <td>Overview</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?=$contact['id']?></td>
                <td><?=$contact['title']?></td>
                <td><?=$contact['author']?></td>
                <td><?=$contact['isbn13']?></td>
                <td><?=$contact['format']?></td>
                <td><?=$contact['publisher']?></td>
                <td><?=$contact['pages']?></td>
                <td><?=$contact['dimensions']?></td>
                <td><?=$contact['overview']?></td>
                <td class="actions">
                    <a href="lenen.php?id=<?=$contact['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="index.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_contacts): ?>
		<a href="index.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>