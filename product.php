<?php
include 'functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 4;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$stmt = $pdo->prepare('SELECT products.id, products.name, category.category as category, products.category as cat, products.stocks, 
        products.unitprice, products.brand, products.code, products.image, products.description, 
        users.username as userid 
        FROM products INNER JOIN category ON products.category = category.id 
        INNER JOIN users ON products.userid = users.id 
        WHERE ( products.name LIKE "%'.$search.'%" || products.brand LIKE "%'.$search.'%" 
        || category.category LIKE "%'.$search.'%" || products.code LIKE "%'.$search.'%"
        || users.username LIKE "%'.$search.'%" || products.description LIKE "%'.$search.'%") 
        ORDER BY products.id DESC LIMIT :current_page, :record_per_page'); 
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of products, this is so we can determine whether there should be a next and previous button
$num_products = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
?>

    <table>
        <thead>
            <tr>
                <td>#</td>
                <td>Product Name</td>
                <td>Category</td>
                <td>Stocks</td>
                <td>Unit Price</td>
                <td>Brand</td>
                <td>Product Code</td>
                <td>Product Image</td>
                <td>Description</td>
                <td>Encoder</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; foreach ($products as $products): ?>
            <tr>
                <td><?=$i++?></td>
                <td><?=$products['name']?></td>
                <td><?=$products['category']?></td>
                <td><?=$products['stocks']?></td>
                <td><?=$products['unitprice']?></td>
                <td><?=$products['brand']?></td>
                <td><?=$products['code']?></td>
        
                <td><img src='<?='images/'.$products['image']?>'></img></td>

                <td><?=$products['description']?></td>
                <td><?=$products['userid']?></td>
                <td class="actions">
                    <a href="update.php?id=<?=$products['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="delete.php?id=<?=$products['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_products): ?>
		<a href="read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>