<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  $categorie = find_by_id_new('locationdetails',(int)$_GET['LocationId'],'LocationId');
  if(!$categorie){
    $session->msg("d","Missing Location id.");
    redirect('product_location.php');
  }
  
  $stockcheck = find_by_column("stockdetails",(int)$_GET['LocationId'],"LocationId");

  if(isset($stockcheck['LocationId'])) {
    $session->msg("d","Unable to detele!... A dependend entry is existing in the database ");
    redirect('product_location.php');
  }
  
?>
<?php
  $delete_id = delete_by_id_new('locationdetails',(int)$categorie['LocationId'],'LocationId');
  if($delete_id){
      $session->msg("s","Location deleted.");
      redirect('product_location.php');
  } else {
      $session->msg("d","Categorie deletion failed.");
      redirect('product_location.php');
  }
?>
