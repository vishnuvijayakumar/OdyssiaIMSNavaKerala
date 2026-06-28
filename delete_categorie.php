<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $categorie = find_by_id_new('categorydetails',(int)$_GET['CategoryId'],'CategoryId');
  if(!$categorie){
    $session->msg("d","Missing Categorie id.");
    redirect('category.php');
  }
  
   $stockcheck = find_by_column("stockdetails",(int)$_GET['CategoryId'],"CategoryId");
  $prodcheck = find_by_column("productdetails",(int)$_GET['CategoryId'],"CategoryId");
  $subcatcheck = find_by_column("subcategorydetails",(int)$_GET['CategoryId'],"CategoryId");

  if(isset($stockcheck['CategoryId']) || isset($prodcheck['CategoryId']) || isset($subcatcheck['CategoryId'])) {
    $session->msg("d","Unable to detele!... A dependend entry is existing in the database ");
    redirect('category.php');
  }
  
?>
<?php
  $delete_id = delete_by_id_new('categorydetails',(int)$categorie['CategoryId'],'CategoryId');
  if($delete_id){
      $session->msg("s","Categorie deleted.");
      redirect('category.php');
  } else {
      $session->msg("d","Categorie deletion failed.");
      redirect('category.php');
  }
?>
