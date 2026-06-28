<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  $categorie = find_by_id_new('challandetails',(int)$_GET['ChallanId'],'ChallanId');
  if(!$categorie){
    $session->msg("d","Missing Batch id.");
    redirect('Batch.php');
  }
  
   $stockcheck = find_by_column("stockdetails",(int)$_GET['ChallanId'],"ChallanId");

  if(isset($stockcheck['ChallanId'])) {
    $session->msg("d","Unable to detele!... A dependend entry is existing in the database ");
    redirect('Challan.php');
  }
  
?>
<?php
  $delete_id = delete_by_id_new('challandetails',(int)$categorie['ChallanId'],'ChallanId');
  if($delete_id){
      $session->msg("s","Challan deleted.");
      redirect('Challan.php');
  } else {
      $session->msg("d","Challan deletion failed.");
      redirect('Challan.php');
  }
?>
