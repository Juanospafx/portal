<?php

$cat = PostData::getById($_GET["product_id"]);
$cat->del();

Core::redir("index.php?view=posts");
?>