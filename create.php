<?php
include 'functions.php';
session_start();
$id = $_SESSION['id'];
$pdo = pdo_connect_mysql();
$msg = '';
$nameErr = '';


if (!empty($_POST)) {

  if ( !isset($_POST['name'], $_POST['category'], $_POST['stocks'], $_POST['unitprice'], $_POST['brand'], $_POST['code'], $_POST['description']) ) {
    // Could not get the data that should have been sent.
    exit('Please fill out all fields!');
  }
  
    $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $stocks = isset($_POST['stocks']) ? $_POST['stocks'] : '';
    $unitprice = isset($_POST['unitprice']) ? $_POST['unitprice'] : '';
    $brand =isset($_POST['brand']) ? $_POST['brand'] : '';
    $code =isset($_POST['brand']) ? $_POST['brand'] : '';
    $image = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $userid = isset($_SESSION['id']) ? $_SESSION['id'] : '';
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s');

        
    //!empty($_POST['category']) && !empty($_POST['brand']) && !empty($_POST['description']) ){
      $stmt = $pdo->prepare('INSERT INTO products VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? ,?,?)');
      $stmt->execute([$id, $name, $category, $stocks, $unitprice, $brand, $code, $image, $description, $userid, $created_at]);
      $msg = 'Created Successfully!';

      $target_dir = "images/";
      $target_file = $target_dir . basename($_FILES["image"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      
      // Check if image file is a actual image or fake image
      if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
          echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
        } else {
          echo "File is not an image.";
          $uploadOk = 0;
        }
      }
      
      // Check if file already exists
      if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
      }
      
      // Check file size
      if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
      }
      
      // Allow certain file formats
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
      }
      
      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
      // if everything is ok, try to upload file
      } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
          echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded.";
        } else {
          echo "Sorry, there was an error uploading your file.";
        }
      }
      
      header('Location: read.php'); exit;

}
?>

<?=template_header('Create')?>

<div class="content update">
	<h2>Add Product</h2>
    <form name="productform" action="create.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="auto" id="id">  
        <label for="name">Name</label>
        <label for="category">Category</label>
        <input type="text" name="name" id="name" required>
        <select name="category" id="category" required>
          <option value="1">Paracetamol</option>
          <option value="2">Loperamide</option>
          <option value="3">Mefenamic</option>
          <option value="4">Aspirin</option>
        </select>     
        <label for="stocks">stocks</label>
        <label for="unitprice">Unit Price</label>
        <input type="number" name="stocks" value="0" id="stocks" required>
        <input type="number" step="0.01" name="unitprice" value="0.00" id="unitprice" required>
        <label for="brand">Brand</label>
        <label for="code">Product Code</label>
        <input type="text" name="brand" id="brand" required>
        <input type="text" name="code" id="code" required>
        <label for="image">Product Image</label>
        <label for="decription">Description</label>
        <input type="file" name="image" id="image">
        <input type="text" name="description" id="description" required>
        <input style="display:none;" type="datetime-local" name="created_at" value="<?=date('Y-m-d\TH:i')?>" id="created_at">
        <input type="submit" value="Add">
      
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
