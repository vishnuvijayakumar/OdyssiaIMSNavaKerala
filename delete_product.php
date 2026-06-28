<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $product = find_by_id_new('productdetails',(int)$_GET['id'],'ProductId');
  if(!$product){
    $session->msg("d","Missing Product id.");
    redirect('product.php');
  }
  
  $stockcheck = find_by_column("stockdetails",(int)$_GET['id'],"ProductId");

  if(isset($stockcheck['ProductId'])) {
    $session->msg("d","Unable to detele!... A dependend entry is existing in the database ");
    redirect('product.php');
  }
  
?>
<?php
  $delete_id = delete_by_id_new('productdetails',(int)$product['ProductId'],'ProductId');
  if($delete_id){
      $session->msg("s","Products deleted.");
      redirect('product.php');
  } else {
      $session->msg("d","Products deletion failed.");
      redirect('product.php');
  }
?>
