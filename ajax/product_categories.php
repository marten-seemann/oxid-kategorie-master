<?php
require('../inc/includes.inc.php');
require('../inc/lib/json_encode_oldphp/jsonwrapper.php'); // add json_encode for PHP version before PHP 5.2
$catHandler = new CategoryReader();

$ids = explode(",", $_GET['ids']);
$res = array();
foreach($ids as $id) {
  $prod = new Product($id);
  $res[] = array(
    "id" => $id,
    "categories" =>$prod->getCategories()
    );
}
echo json_encode($res);
?>
