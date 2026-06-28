<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  $categorie = find_by_id_new('unitdetails',(int)$_GET['UnitId'],'UnitId');
  if(!$categorie){
    $session->msg("d","Missing Unit id.");
    redirect('units.php');
  }
  
  $stockcheck = find_by_column("stockdetails",(int)$_GET['UnitId'],"UnitId");

  if(isset($stockcheck['UnitId'])) {
    $session->msg("d","Unable to detele!... A dependend entry is existing in the database ");
    redirect('units.php');
  }
  
?>
<?php
  $delete_id = delete_by_id_new('unitdetails',(int)$categorie['UnitId'],'UnitId');
  if($delete_id){
      $session->msg("s","Unit deleted.");
      redirect('units.php');
  } else {
      $session->msg("d","Unit deletion failed.");
      redirect('units.php');
  }
?>
