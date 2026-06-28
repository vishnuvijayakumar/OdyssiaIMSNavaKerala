<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $stocks = find_by_id_new('stockdetails',(int)$_GET['StockId'],'StockId');
  if(!$stocks){
    $session->msg("d","Missing Stock id.");
    redirect('Stock.php');
  }
?>
<?php
print_r($stocks);
if(isset($stocks['Barcode'])) {
  if($stocks['StockType']=='IN - First Entry' || $stocks['StockType']=='In - First Entry') {
    $stinentryupdate=update_inentrystockbarcode_qty($stocks['Quantity'],$stocks['Barcode']);
  } else if ($stocks['StockType']=='IN - Excess' || $stocks['StockType']=='In - Excess') {
    $stinupdate=update_instockbarcode_qty($stocks['Quantity'],$stocks['Barcode']);
  } else {
    $stoutupdate=update_outstockbarcode_qty($stocks['Quantity'],$stocks['Barcode']);
  }
  //echo "yes";die();
}//die();
  $delete_id = delete_by_id_new('stockdetails',(int)$stocks['StockId'],'StockId');
  if($delete_id){
      $session->msg("s","Stock Entry deleted.");
      redirect('Stock.php');
  } else {
      $session->msg("d","Stock Entry deletion failed.");
      redirect('Stock.php');
  }
?>
