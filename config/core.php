<?php

// 1 page is default
$page = isset($_GET['page']) ? $_GET['page'] : 1;

//number of records per page
$records_per_page = 5;


// for MySql's LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

 ?>
