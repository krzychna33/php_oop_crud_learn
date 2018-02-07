<?php

$id = isset($_GET['id']) ? $_GET['id'] : die('Error: missing id.');

include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);

$product->id = $id;

$product->readOne();

$page_title = "Update Product";
include_once "layout_header.php";

echo "<div class='right-button-margin'>";
    echo "<a href='index.php' class='btn btn-default pull-right'>Read Products</a>";
echo "</div>";

if($_POST){

  $product->name = $_POST['name'];
  $product->price = $_POST['price'];
  $product->description = $_POST['description'];
  $product->category_id = $_POST['category_id'];

  if($product->update()){
    echo "<div class='alert alert-success alert-dismissable'>";
      echo "Product was updated.";
    echo "</div>";
  } else {
    echo "<div class='alert alert-danger alert-dismissable'>";
        echo "Unable to update product.";
    echo "</div>";
  }
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post">
    <table class='table table-hover table-responsive table-bordered'>

        <tr>
            <td>Name</td>
            <td><input type='text' name='name' value='<?php echo $product->name; ?>' class='form-control' /></td>
        </tr>

        <tr>
            <td>Price</td>
            <td><input type='text' name='price' value='<?php echo $product->price; ?>' class='form-control' /></td>
        </tr>

        <tr>
            <td>Description</td>
            <td><textarea name='description' class='form-control'><?php echo $product->description; ?></textarea></td>
        </tr>

        <tr>
            <td>Category</td>
            <td>
                <?php
                  $stmt = $category->read();

                  echo "<select class='form-control' name='category_id'";
                    echo "<option>Please select...</option>";

                    while($row_category = $stmt->fetch(PDO::FETCH_ASSOC)){
                      extract($row_category);

                      if($product->category_id == $id){
                        echo "<option value='id' selected>";
                      } else {
                        echo "<option value='$id'>";
                      }

                      echo "$name</option>";
                    }

                  echo "</select>";
                 ?>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">Update</button>
            </td>
        </tr>

    </table>
</form>

<?php



include_once "layout_footer.php";
?>
