<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  $categorie = find_by_id_new('batchdetails',(int)$_GET['BatchId'],'BatchId');
  if(!$categorie){
    $session->msg("d","Missing Batch id.");
    redirect('Batch.php');
  }
  
   $stockcheck = find_by_column("stockdetails",(int)$_GET['BatchId'],"BatchId");

  if(isset($stockcheck['CategoryId'])) {
    $session->msg("d","Unable to detele!... A dependend entry is existing in the database ");
    redirect('Batch.php');
  }
  
?>
<?php
  $delete_id = delete_by_id_new('batchdetails',(int)$categorie['BatchId'],'BatchId');
  if($delete_id){
      $session->msg("s","Batch deleted.");
      redirect('Batch.php');
  } else {
      $session->msg("d","Batch deletion failed.");
      redirect('Batch.php');
  }
?>
