<?php

include_once 'config/core.php';

include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);

//get search term
$search_term = isset($_GET['s']) ? $_GET['s'] : '';

//set page title
$page_title = "You searched for \"{$search_term}\"";
include_once "layout_header.php";

//search term and limit the mysql query
$stmt = $product->search($search_term, $from_record_num, $records_per_page);

//url for pagination
$page_url = "search.php?s={$search_term}&";

//count rows
$total_rows = $product->countAll_BySearch($search_term);

include_once "read_template.php";

include_once "layout_footer.php";

 ?>
