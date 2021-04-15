<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        $id = isset($_POST['id']) ? $_POST['id'] : NULL;
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        $stocks = isset($_POST['stocks']) ? $_POST['stocks'] : '';
        $unitprice = isset($_POST['unitprice']) ? $_POST['unitprice'] : '';
        $brand = isset($_POST['brand']) ? $_POST['brand'] : '';
        $code = isset($_POST['code']) ? $_POST['code'] : '';
        $imagenew = $_POST['image'];
       
        if( $imagenew == '' || $imagenew == NULL){
            $image = $_POST['image2'];
        }else{
            $image = $_POST['image'];
        }


        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $created_at = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');
        $stmt = $pdo->prepare('UPDATE products SET id = ?, name = ?, category = ?, stocks = ?, unitprice = ?, brand = ?, code = ?, image = ? , description = ?, created_at = ? WHERE id = ?');
        $stmt->execute([$id, $name, $category, $stocks, $unitprice, $brand, $code, $image, $description, $created_at, $_GET['id']]);
        $msg = 'Updated Successfully!';


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
        
        if (file_exists($target_file)) {
          echo "Sorry, file already exists.";
          $uploadOk = 0;
        }
        
        if ($_FILES["image"]["size"] > 500000) {
          echo "Sorry, your file is too large.";
          $uploadOk = 0;
        }
        
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
          echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
        }
        
        if ($uploadOk == 0) {
          echo "Sorry, your file was not uploaded.";
        } else {
          if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded.";
          } else {
            echo "Sorry, there was an error uploading your file.";
          }
        }
     
       header('Location: read.php'); exit;

    }

    //$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt = $pdo->prepare('SELECT products.id, products.name, category.category, products.stocks, products.unitprice, products.brand, products.code, products.image, products.description, users.username as userid, category.id as categoryid FROM products INNER JOIN category ON products.category = category.id INNER JOIN users ON products.userid = users.id WHERE products.id=?'); 
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        exit('Contact doesn\'t exist with that ID!');
    }

} else {
    exit('No ID specified!');
}
?>

<script>
  function validateForm() {
    var name = document.forms["productform"]["name"].value;
    var category = document.forms["productform"]["category"].value;
    var brand = document.forms["productform"]["brand"].value;
    var code = document.forms["productform"]["code"].value;
    var description = document.forms["productform"]["description"].value;
    if (name == "") {
      alert("Product Name must be filled out");
      return false;
    }else if (category == "") {
      alert("Category required!");
      return false;
    }else if (brand == "") {
      alert("Brand required!");
      return false;
    }else if (code == "") {
      alert("Product Code required!");
      return false;
    }else if (description == "") {
      alert("Product Description required!");
      return false;
    }
  }
</script>

<?=template_header('Read')?>

<div class="content update">
	<h2>Update Product :  <?=$product['name']?></h2>
    <form name="productform" onsubmit="return validateForm()" action="update.php?id=<?=$product['id']?>" method="post">
        <input type="hidden" name="id" value="<?=$product['id']?>" id="id">

        <label for="name">Name</label>
        <label for="category">Category</label>        
        <input type="text" name="name" value="<?=$product['name']?>" id="name">
        <select name="category" id="category" required>
          <option value="<?=$product['categoryid']?>"><?=$product['category']?></option>
          <option value="1">Paracetamol</option>
          <option value="2">Loperamide</option>
          <option value="3">Mefenamic</option>
          <option value="4">Aspirin</option>
        </select>   
        <label for="stocks">stocks</label>
        <label for="unitprice">Unit Price</label>
        <input type="number" name="stocks" value="<?=$product['stocks']?>" id="stocks">
        <input type="number" step="0.01" name="unitprice" value="<?=$product['unitprice']?>" id="unitprice">
        <label for="brand">Brand</label>
        <label for="code">Product Code</label>
        <input type="text" name="brand" value="<?=$product['brand']?>" id="brand">
        <input type="text" name="code" value="<?=$product['code']?>" id="code">
        <label for="image">Product Image</label>
        <label for="description">Description</label>
        <input type="file" name="image" id="image">
        <input type="text" name="description" value="<?=$product['description']?>" id="description">
        
        <input style="display:none" type="datetime-local" name="created_at" value="<?=date('Y-m-d\TH:i', strtotime($product['created_at']))?>" id="created">
        <input type="submit" value="Update">
        <input type="hidden" name="image2" value="<?=$product['image']?>" id="image2">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>