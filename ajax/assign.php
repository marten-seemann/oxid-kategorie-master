<?php
require('../inc/includes.inc.php');
$catHandler = new CategoryReader();

$mode = $_POST['mode'];
$ret = array(); // the result to be returned
$prods = array();

$ids = explode(",",$_POST["ids"]);
if(count($ids) == 1 && $ids[0]=="") dieerror();
$new_cat = $_POST['new_cat'];

// return error if the category does not exist
if(!$catHandler->categoryExists($new_cat)) dieerror();


$delete_affected = 0; // counter. if mode is deleted, we delete here from how many of the products the specified category is removed
foreach($ids as $id) {
  $prod = new Product($id);
  if($mode == "redefine") {
    $prod->removeFromAllCategories();
    if($prod->setCategory($new_cat,true) != 1) dieerror();
  }
  else if($mode == "add") {
    if($prod->setCategory($new_cat,false) != 1) dieerror();
  }
  else if($mode == "main_category") {
    if($prod->setCategory($new_cat,true) != 1) dieerror();
  }
  else if($mode == "delete") {
    $delete_affected +=$prod->removeFromCategory($new_cat);
  }

  $prods[] = array(
    "id" => $id,
    "categories" => $prod->getCategories()
    );
}


if($mode == "delete" AND $delete_affected == 0) dieerror();

echo json_encode($prods);



function dieerror() {
  die(json_encode("false"));
}
?>