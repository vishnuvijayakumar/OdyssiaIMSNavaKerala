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
    $all_uoms = join_subcategory_table($stockdetails['SubCategoryId']);
    if(!$stockdetails) {
      $session->msg('d',' Sorry Failed to Featch Details!');
       redirect('Stock.php', false);
    } 
  }
  ?>

<?php
 if(isset($_POST['ProdID'])){

    $prodidcat  = remove_junk($db->escape($_POST['ProdID']));
    $prodcatdetails = join_product_table_new($prodidcat);

    header('Content-Type: application/json');
    echo json_encode($prodcatdetails);
    exit;

  }

  if(isset($_POST['SubCatId'])){

    $subcatid  = remove_junk($db->escape($_POST['SubCatId']));
    $prodcatdetails = join_subcategory_table($subcatid);

    header('Content-Type: application/json');
    echo json_encode($prodcatdetails);
    exit;

  }

  ?>

<?php
 if(isset($_POST['update_stock'])){
   $req_fields = array('product-name','product-category','product-subcategory','product-quantity','UOM','stock-type','product-location','unit','planno' );
   validate_fields($req_fields);
   //echo $errors;die();
   if(empty($errors)){
     //$b_code=remove_junk($db->escape($_POST['barcode']));
    $p_code  = remove_junk($db->escape($_POST['product-name']));
     $p_cat   = remove_junk($db->escape($_POST['product-category']));
     $p_subcat = remove_junk($db->escape($_POST['product-subcategory']));
     //echo $p_code,$p_name;die();
     $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
	 //$p_qty = $stockdetails['Quantity'];
     $p_uom  = remove_junk($db->escape($_POST['UOM']));
     $p_loc  = remove_junk($db->escape($_POST['product-location']));
     $p_stock_type  = remove_junk($db->escape($_POST['stock-type']));;
     $p_unit  = remove_junk($db->escape($_POST['unit']));
     $p_planno  = remove_junk($db->escape($_POST['planno']));
     $p_stockid =  remove_junk($db->escape($_POST['stockid']));

     $query  = "UPDATE stockdetails SET";
     $query .=" ProductId=$p_code,CategoryId=$p_cat,SubCategoryId=$p_subcat,StockType='$p_stock_type',Quantity=$p_qty,UomId=$p_uom,LocationId=$p_loc,UnitId=$p_unit,PlanNo='$p_planno'";
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
            <span>Update Stock Details</span>
         </strong>
        </div>
        <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="edit_stock.php" class="clearfix">
              <div class="form-group">
              <div class="row">
                 <div class="col-md-6">
                    <select class="form-control" id="itemcode" name="product-name" onChange="enable()";>
                      <option value="">Select Product</option>
                    <?php  foreach ($all_products as $prod): ?>
                      <option value="<?php echo $prod['ProductId']; ?>" <?php if($stockdetails['ProductId']==$prod['ProductId']){ echo "Selected"; }?>>
                        <?php echo $prod['ItemName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                     </div>
                 <div class="col-md-6">
                 <select class="form-control" id="itemcat" name="product-category">
                      <option value="">Select Product Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo $cat['CategoryId']; ?>" <?php if($stockdetails['CategoryId']==$cat['CategoryId']){ echo "Selected"; }?> >
                        <?php echo $cat['CategoryName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                 </div>
                </div>
            </div>
             
              <div class="form-group">
                <div class="row">
                  <div class="col-md-4">
                  <select class="form-control" id="itemsubcat" name="product-subcategory">
                      <option value="">Select Product Sub Category</option>
                    <?php  foreach ($all_sub_categories as $scat): ?>
                      <option value="<?php echo $scat['SubCategoryId']; ?>" <?php if($stockdetails['SubCategoryId']==$scat['SubCategoryId']){ echo "Selected"; }?> >
                        <?php echo $scat['SubCategoryName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                  <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="text" class="form-control" name="product-quantity" value="<?php echo $stockdetails['Quantity']; ?>" placeholder="Product Quantity" readonly="true">
                  </div>
                  </div>
                  <div class="col-md-4">
                  <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-scissors"></i>
                     </span>
                     <select class="form-control" id="UomId" name="UOM">
                      <option value="">Select UOM</option>
                      <?php  foreach ($all_uoms as $uoms): ?>
                      <option value="<?php echo $uoms['UomId']; ?>" <?php if($stockdetails['UomId']==$uoms['UomId']){ echo "Selected"; }?> >
                        <?php echo $uoms['UomSubType'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                  <!--<select class="form-control" name="stock-type">
                      <option value="">Select Stock Type</option>
                      <option value="In - First Entry" <?php //if($stockdetails['StockType']=='In - First Entry'){ echo "Selected"; }?> >In - First Entry</option>
                      <option value="In - Excess" <?php //if($stockdetails['StockType']=='In - Excess'){ echo "Selected"; }?> >In - Excess</option>
                      <option value="Out - Plan No" <?php //if($stockdetails['StockType']=='Out - Plan No'){ echo "Selected"; }?> >Out - Plan No</option>
                      <option value="Out - Chelan No" <?php //if($stockdetails['StockType']=='Out - Chelan No'){ echo "Selected"; }?> >Out - Chelan No</option>
                    </select>--->
					<input type="text" class="form-control" name="stock-type" value="<?php echo $stockdetails['StockType']; ?>" placeholder="Product Quantity" readonly="true">
                  </div>
                  <div class="col-md-6">
                  <select class="form-control" name="product-location">
                      <option value="">Select Product Location</option>
                    <?php  foreach ($all_locations as $loc): ?>
                      <option value="<?php echo $loc['LocationId']; ?>" <?php if($stockdetails['LocationId']==$loc['LocationId']){ echo "Selected"; }?> >
                        <?php echo $loc['LocationName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                  <select class="form-control" name="unit">
                      <option value="">Select Unit</option>
                    <?php  foreach ($all_units as $uni): ?>
                      <option value="<?php echo $uni['UnitId']; ?>" <?php if($stockdetails['UnitId']==$uni['UnitId']){ echo "Selected"; }?> >
                        <?php echo $uni['UnitName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                  <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-book"></i>
                     </span>
                     <input type="text" class="form-control" name="planno" value="<?php echo $stockdetails['PlanNo']; ?>" placeholder="Chelan/Plan No">
                     <input type="hidden" class="form-control" name="stockid" value="<?php echo $stockdetails['StockId']; ?>" placeholder="StockId">
                  </div>
                  </div>
                </div>
              </div>

       
              </div>
              <button type="submit" name="update_stock" class="btn btn-danger">Update Stock</button>
          </form>
         </div>
        </div>
      </div>
    </div>

  </div>

  <script type="text/JavaScript"> 
    function enable() {
      var prodvalue=document.getElementById('itemcode').value;
      //console.log(prodvalue);

      $.ajax({
    url      : 'Stock_Add.php',
    data     : {'ProdID':prodvalue},
    dataType: 'json',
    type     : 'POST',
    success  : function(Result){
              //console.log(Result[0]);
              //var myObj = $.parseJSON(Result);
              var myObj =Result[0];
             // console.log(myObj);
              //you can now access data like this:
              //myObj.Address_1
              document.getElementById('itemcat').value=myObj.CategoryId;
              document.getElementById('itemsubcat').value=myObj.SubCategoryId;
              uomcheck();
        }
    }

  );
    
}

function uomcheck() {
      var subcatvalue=document.getElementById('itemsubcat').value;
      //console.log(prodvalue);

      $.ajax({
    url      : 'Stock_Add.php',
    data     : {'SubCatId':subcatvalue},
    dataType: 'json',
    type     : 'POST',
    success  : function(Result){
              //console.log(Result[0]);
              //var myObj = $.parseJSON(Result);
              var myObj =Result;
              $("#UomId").empty();
              $('#UomId').append('<option value="">Select UOM </option>');
              //console.log(myObj);
              //you can now access data like this:
              //myObj.Address_1
              $.each(myObj, function (i, value) {
                $('#UomId').append('<option value=' + value.UomId + '>' + value.UomSubType + '</option>');
            });
        }
    }
  );
    
}
     </script>'

<?php include_once('layouts/footer.php'); ?>
