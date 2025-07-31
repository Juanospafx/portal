<?php
$s = UserData::getById($_GET["user_id"]);
$s->del();

Core::redir("index.php?view=user");

?>