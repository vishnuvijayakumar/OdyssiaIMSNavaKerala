<?php


  $page_title = 'Update Stock';
  require_once('includes/load.php');

  require_once $_SERVER['DOCUMENT_ROOT'].'/InventorySystem/barcode.php';


  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_products = find_all('productdetails');
  $all_categories = find_all('categorydetails');
  $all_sub_categories = find_all('subcategorydetails');
  $all_locations = find_all('locationdetails');
  $all_units = find_all('unitdetails');
  $all_photo = find_all('media');

  $stockdet=0;
  $stockdet1=0;
  if(isset($_GET["stockdet"])) {
    $stockdet=$_GET["stockdet"];
  }
?>

<?php
  if(isset($_GET['StockId'])) {

   // $req_fields = array('bar-code' );
   //validate_fields($req_fields);
   //if(empty($errors)){
    $stockid  = remove_junk($db->escape($_GET['StockId']));
    //echo $bar_code;die();
    $stockdetails = find_by_id_new('stockdetails',$stockid,'StockId');
    //print_r($stockdetails);die();
    
    if(!$stockdetails) {
      $session->msg('d',' Sorry Failed to Featch Details!');
       redirect('Stock.php', false);
    } 
  }
  ?>

<?php
 if(isset($_POST['update_stock'])){
   $req_fields = array('product-location');
   validate_fields($req_fields);
   
   if(empty($errors)){
     $p_loc  = remove_junk($db->escape($_POST['product-location']));
     $p_stockid =  remove_junk($db->escape($_POST['stockid']));

     $query  = "UPDATE stockdetails SET";
     $query .=" LocationId=$p_loc";
     $query .=" WHERE StockId=".$p_stockid;
	 //echo $query;die();
	 
     if($db->query($query)){
       $session->msg('s',"Stock Entry Updated ");
       redirect('Stock.php', false);
     } else {
       $session->msg('d',' Sorry failed to Update!');
       redirect('Stock.php', false);
     }

     } else {
      $session->msg("d", $errors);
      redirect('Stock.php',false);
    }

 }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  <div class="col-md-8">
      

           <div class="panel panel-default" >
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-circle-arrow-up"></span>
            <span>Update Stock Location</span>
         </strong>
        </div>
        <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="edit_stock_location.php" class="clearfix">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                  <select class="form-control" name="product-location">
                      <option value="">Select Product Location</option>
                    <?php  foreach ($all_locations as $loc): ?>
                      <option value="<?php echo $loc['LocationId']; ?>" <?php if($stockdetails['LocationId']==$loc['LocationId']){ echo "Selected"; }?> >
                        <?php echo $loc['LocationName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                    <input type="hidden" class="form-control" name="stockid" value="<?php echo $stockdetails['StockId']; ?>" placeholder="StockId">
                  </div>
                </div>
              </div>
              <button type="submit" name="update_stock" class="btn btn-danger">Update</button>
          </form>
         </div>
        </div>
      </div>
    </div>

  </div>

<?php include_once('layouts/footer.php'); ?>
