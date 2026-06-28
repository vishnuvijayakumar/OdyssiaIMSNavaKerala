<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $categorie = find_by_id_new('subcategorydetails',(int)$_GET['SubCategoryId'],'SubCategoryId');
  if(!$categorie){
    $session->msg("d","Missing Categorie id.");
    redirect('subcategory.php');
  }
  
   $stockcheck = find_by_column("stockdetails",(int)$_GET['SubCategoryId'],"SubCategoryId");
  $prodcheck = find_by_column("productdetails",(int)$_GET['SubCategoryId'],"SubCategoryId");

  if(isset($stockcheck['SubCategoryId']) || isset($prodcheck['SubCategoryId'])) {
    $session->msg("d","Unable to detele!... A dependend entry is existing in the database ");
    redirect('subcategory.php');
  }
  
?>
<?php
  $delete_id = delete_by_id_new('subcategorydetails',(int)$categorie['SubCategoryId'],'SubCategoryId');
  if($delete_id){
      $session->msg("s","Categorie deleted.");
      redirect('subcategory.php');
  } else {
      $session->msg("d","Categorie deletion failed.");
      redirect('subcategory.php');
  }
?>
