<?php
#################
# Configuration #
#################

// additional search fields, as used in the table header, are transfered as GET-variables
// here we have to map them to the right field in the database
// $filter_fields = array(
//   'sSearch_0' => 'OXTITLE',
//   'sSearch_1' => '', // Producer Article number
//   'sSearch_2' => 'OXARTNUM',
//   'sSearch_3' => 'OXEAN'
//   );

$sTable = "view_oxarticles_manufacturers";
$sIndexColumn = "OXID";
$aColumns = array('OXID', 'OXTITLE','manufacturer_OXTITLE', 'vendor_OXTITLE', 'OXMPN', 'OXARTNUM', 'OXEAN', 'OXDISTEAN', 'OXPRICE', 'OXTPRICE', 'OXBPRICE', 'OXINSERT', 'OXACTIVE'); // OXACTIVE has to be the last

########### configuration end ##################
require('../inc/includes.inc.php');

$catHandler = new CategoryReader();

// debug mode
if(isset($_GET['debug'])) $debug = true;
else $debug = false;

if($debug) {
  var_dump(setlocale(LC_ALL, 0));
  $timer = new Timer();
  var_dump($_GET);
}

if($debug) $timer->start("query_creation");
// will a LEFT JOIN query be used? then we have to prepend some columns with the table name
if($_GET['only_empty_categories']=="true") $will_join = true;
else $will_join = false;
// var_dump($will_join);

/*
 * Paging
 */
$sLimit = "";
if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )  {
  $sLimit = "LIMIT ".$db->validate( $_GET['iDisplayStart'] ).", ".($db->validate( $_GET['iDisplayLength'] ));
}


/*
 * Ordering
 */
if ( isset( $_GET['iSortCol_0'] ) ) {
  $sOrder = "ORDER BY  ";
  for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ) {
    if($_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true") {
      if($will_join) $sOrder.=$sTable.".";
      $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) +1 ]." ".$db->validate( $_GET['sSortDir_'.$i] ) .", ";
    }
  }

  $sOrder = substr_replace( $sOrder, "", -2 );
  if ( $sOrder == "ORDER BY" ) $sOrder = "";
}


/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
$sWhere = "";
if ( $_GET['sSearch'] != "" ) {
  $sWhere = "WHERE (";
  for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
    if($will_join) $sWhere.=$sTable.".";
    $sWhere .= $aColumns[$i]." LIKE '%".$db->validate( $_GET['sSearch'] )."%' OR ";
  }
  $sWhere = substr_replace( $sWhere, "", -3 );
  $sWhere .= ')';
}

/* Individual column filtering */
for ( $i=0 ; $i<count($aColumns) ; $i++ ) {
  if ( isset($_GET['bSearchable_'.$i]) AND $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ) {
    if ( $sWhere == "" ) $sWhere = "WHERE ";
    else $sWhere .= " AND ";
    if($will_join) $table_prefix = $sTable.".";
    else $table_prefix = "";
    $sWhere .= "(";
    foreach(explode(" ", $_GET['sSearch_'.$i]) as $word) {
      $sWhere .= $table_prefix.$aColumns[$i+1]." LIKE '%".$db->validate($word)."%' AND ";
    }
    $sWhere = substr($sWhere, 0, -4);
    $sWhere .= ")";
  }
}

$sJoin = "";
$sGroup = "";
// filter products that have no category assigned yet
if($_GET['only_empty_categories'] == "true") {
  $sJoin = "LEFT JOIN oxobject2category ON {$sTable}.OXID = oxobject2category.OXOBJECTID";
  $sGroup = "GROUP BY {$sTable}.OXID";
  if(strlen($sWhere)==0) $sWhere = "WHERE oxobject2category.OXOBJECTID IS NULL";
  else {
    $sWhere = "WHERE oxobject2category.OXOBJECTID IS NULL AND (".substr($sWhere, 6).")";
  }
}
// filter products that are marked as inactive
if($_GET['hide_inactive_articles'] == "true") {
  if(strlen($sWhere)==0) $sWhere = "WHERE {$sTable}.OXACTIVE = 1";
  else {
    $sWhere =  "WHERE {$sTable}.OXACTIVE = 1 AND (".substr($sWhere, 6).")";
  }
}

// function to only show products that are in subcategories of a specified category
$cat_id = $_GET['show_only_products_in_cat'];
if(strlen($cat_id) > 0) {
  $filter_cats = true;
  $sub_cats = $catHandler->getAllSubCategoriesFromHere($cat_id);
  $where_add = "";
  for($i = 0; $i < count($sub_cats); $i++) {
    if($i > 0) $where_add .= " OR ";
    $where_add .= "oxobject2category.OXCATNID = '".$sub_cats[$i]."'";
  }
  $sJoin = "LEFT JOIN oxobject2category ON {$sTable}.OXID = oxobject2category.OXOBJECTID";
  $sGroup = "GROUP BY {$sTable}.OXID";
  if(strlen($sWhere) > 0) $sWhere = "WHERE (".substr($sWhere, 6).") AND ($where_add)";
  else $sWhere = "WHERE ($where_add)";
}
else $filter_cats = false;


if($debug) {
  $timer->stop("query_creation");
  $timer->start("query_execution");
}
// var_dump($)

/*
 * SQL queries
 * Get data to display
 */
$sQuery = "
  SELECT {$sTable}.".str_replace(" , ", " ", implode(", {$sTable}.", $aColumns))."
  FROM   $sTable
  $sJoin
  $sWhere
  $sGroup
  $sOrder
  $sLimit
";
if($debug) echo getFormattedSQL($sQuery);
$rResult = $db->query($sQuery) or die(mysql_error());
if($debug) $timer->stop("query_execution");

/* Data set length after filtering */
$sQuery = "
  SELECT COUNT(DISTINCT {$sTable}.OXID)
  FROM   $sTable
  $sJoin
  $sWhere
";
if($debug) echo getFormattedSQL($sQuery);

$rResultFilterTotal = $db->query( $sQuery) or die(mysql_error());
$aResultFilterTotal = $rResultFilterTotal->fetch_array();
$iFilteredTotal = $aResultFilterTotal[0];

/* Total data set length */
$sQuery = "
  SELECT COUNT(".$sIndexColumn.")
  FROM   $sTable
";
$rResultTotal = $db->query($sQuery) or die(mysql_error());
$aResultTotal = $rResultTotal->fetch_array();
$iTotal = $aResultTotal[0];

/*
 * Output
 */
$output = array(
  "sEcho" => intval($_GET['sEcho']),
  "iTotalRecords" => $iTotal,
  "iTotalDisplayRecords" => $iFilteredTotal,
  "aaData" => array()
);

while($data = $rResult->fetch_object()) {
  // var_dump($data);
  $id = $data->OXID;
  $prod = new Product($id);
  $cats = $prod->getCategories(); // first elemeent is the main category
  // $cats_name = array();
  // foreach($cats as $cat) {
  //   $cats_name[] = $catHandler->getCategoryName($cat);
  // }
  // $title = $data->OXTITLE." (".$data->OXID.")";
  $title = $data->OXTITLE;
  if(empty($title)) $title = $data->OXID;
  $row_class = "";
  if($data->OXACTIVE == 0) $row_class = "article_hidden";
  $row = array(
    "DT_RowId" => $id,
    "DT_RowClass" => $row_class,
    0 => trim($title),
    1 => $data->manufacturer_OXTITLE,
    2 => $data->vendor_OXTITLE,
    3 => $data->OXMPN, //manufacturer article number
    4 => $data->OXARTNUM,
    5 => $data->OXEAN,
    6 => $data->OXDISTEAN,
    7 => $lang->formatPrice($data->OXPRICE),
    8 => $lang->formatPrice($data->OXTPRICE),
    9 => $lang->formatPrice($data->OXBPRICE),
    10 => $data->OXINSERT,
    11 => implode(", ", $cats),
  );
  $output['aaData'][] = $row;
}


if($debug) {
  var_dump($timer->getAll());
  var_dump($output);
}
else echo json_encode($output);




?>
