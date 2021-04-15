<?php
include 'functions.php';


?>
<?=template_header('Read');?>
<div class="content read">
	<a href="create.php" class="create-product">Add Products</a> 
    <input style="align:left;" type="text" onkeyup="myFunction()" name="search" id="search" placeholder="search...">
    

<span id="data"></span>