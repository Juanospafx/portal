<?php

$cat = SubcategoryData::getById($_GET["subcategory_id"]);
$cat->del();

Core::redir("index.php?view=subcategories");
?>