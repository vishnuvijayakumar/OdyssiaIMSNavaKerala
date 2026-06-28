<?php


  $page_title = 'Add Stock';
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
  $all_uom = find_all('uomdetails');
  $all_barcodes = find_all('barcodedetails');

  $stockdet=0;
  $stockdet1=0;
  if(isset($_GET["stockdet"])) {
    $stockdet=$_GET["stockdet"];
  }

  if(isset($_GET['barcode'])){
    $Stocks = find_by_id_new('stockdetails',$_GET['barcode'],'Barcode');
    //print_r($Stocks);die();
  }

?>

<?php
  if(isset($_POST['search-barcode'])) {

    $stockdet=1;

    $req_fields = array('bar-code' );
   validate_fields($req_fields);
   if(empty($errors)){
    $bar_code  = remove_junk($db->escape($_POST['bar-code']));
    //echo $bar_code;die();
    //$stockdetails = find_by_id_new('barcodedetails',$bar_code,'Fullbarcode');
    $sql =$db->query("Select * from barcodedetails where Fullbarcode='".$bar_code."' LIMIT 1");
    $stockdetails = $db->fetch_assoc($sql);
    if(!$stockdetails) {
      $session->msg('d',' Sorry Invalid Barcode!');
       redirect('Stock_Add.php', false);
    } else {
      $stockdet1=1;
      $bar_code=$stockdetails['Barcode'];
      //echo $bar_code;
      $Stocks = find_by_id_new('stockdetails',$bar_code,'Barcode');
      //print_r($Stocks);die();
    }

    //echo $stockdet1;die();
      redirect('Stock_Add.php?stockdet='.$stockdet.'&barcode='.$bar_code.'&stockdet1='.$stockdet1, false);
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
 if(isset($_POST['add_stock'])){
   $req_fields = array('barcode','product-name','product-category','product-subcategory','product-quantity','UOM','stock-type','product-location' );
   validate_fields($req_fields);
   if(empty($errors)){
     $b_code=remove_junk($db->escape($_POST['barcode']));
    $p_code  = remove_junk($db->escape($_POST['product-name']));
     $p_cat   = remove_junk($db->escape($_POST['product-category']));
     $p_subcat = remove_junk($db->escape($_POST['product-subcategory']));
     $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
     $p_uom  = remove_junk($db->escape($_POST['UOM']));
     $p_loc  = remove_junk($db->escape($_POST['product-location']));
     $p_stock_type  = remove_junk($db->escape($_POST['stock-type']));
     $p_unit  = remove_junk($db->escape($_POST['unit']));
     $p_planno  = remove_junk($db->escape($_POST['planno']));
     $userid = current_user();
     $p_userid = $userid['id'];
     //echo $p_uom;die();

     $conversionvaluefind = find_by_column("uomdetails",$p_uom,"UomId");
     if($conversionvaluefind) {
       $p_qty_converted=$p_qty*$conversionvaluefind['ConversionValue'];
     } else {

      $session->msg("d", "Provided UOM is Invalid");
      redirect('Stock_Add.php',false);
     }
     //print_r($p_qty_converted);die();

     if(isset($_POST['product-quantity-limit']) && ($p_stock_type != "IN - First Entry"&&$p_stock_type!="IN - Excess") && (remove_junk($db->escape($_POST['product-quantity-limit'])<$p_qty_converted))) {
      $errors="Requiired stock is not available on this barcode...!";
      $session->msg("d", $errors);
      redirect('Stock_Add.php',false);
     }

     if($p_planno===''){
      $p_planno=NULL;
    }

     //echo $p_unit,$p_planno;die();

     //if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
    //   $media_id = '0';
     //} else {
     //  $media_id = remove_junk($db->escape($_POST['product-photo']));
     //}
     //$date    = make_date();
     $stockdetailscheck = find_by_id_new('barcodedetails',$b_code,'Barcode');
     //print_r($stockdetailscheck);//die();
     if(isset($stockdetailscheck['Barcode'])){

     $query  = "INSERT INTO stockdetails (";
     $query .=" Barcode,ProductId,CategoryId,SubCategoryId,StockType,Quantity,UomId,LocationId,UnitId,PlanNo,id";
     $query .=") VALUES (";
     $query .=" '{$b_code}','{$p_code}', '{$p_cat}', '{$p_subcat}', '{$p_stock_type}', '{$p_qty}','{$p_uom}', '{$p_loc}', '{$p_unit}', '{$p_planno}', '{$p_userid}'";
     $query .=")";
   
     if($db->query($query)){
       $session->msg('s',"Stock Entry added ");
       redirect('Stock_Add.php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('Stock.php', false);
     }

   

   } else {
    $session->msg('d',' Sorry unable to find the Barcode!');
    redirect('Stock.php', false);
  }

     } else {
      $session->msg("d", $errors);
      redirect('Stock_Add.php',false);
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
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-search"></span>
            <span>Search Stock Details</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
           <form method="post" action="Stock_Add.php" class="clearfix">
           <div class="form-group">
              <div class="row">
                 <div class="col-md-6">
                    <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-barcode"></i>
                  </span>
                  <input type="text" class="form-control" name="bar-code" placeholder="BarCode">
                     </div>
                 </div>
                 
                  <button type="submit" class="btn btn-danger" name="search-barcode" >Search</button>
               
                </div>
            </div>
           </form>
          </div>
          </div>
          </div>

          <?php if(isset($stockdet) && $stockdet==1) { 
            if($stockdet1==1) {
              $stockdetails = find_by_id_new('stockdetails',$barcode,'Barcode');
              //print_r($stockdetails);die();
            }
            
            ?>

           <div class="panel panel-default" >
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-plus"></span>
            <span>Add/Update Stock Details</span>
         </strong>
        </div>
        <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="Stock_Add.php" class="clearfix">
              <div class="form-group">
              <div class="row">
                 <div class="col-md-6">
                    <select class="form-control" id="itemcode" name="product-name" onChange="enable()";>
                      <option value="">Select Product</option>
                    <?php  foreach ($all_products as $prod): ?>
                      <option value="<?php echo $prod['ProductId']; ?>" <?php if(isset($Stocks['ProductId']) && ($Stocks['ProductId']==$prod['ProductId'])){ echo "Selected";  }?>>
                        <?php echo $prod['ItemName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                     </div>
                 <div class="col-md-6">
                 <select class="form-control" id="itemcat" name="product-category" onChange="subcatselect()" >
                      <option value="">Select Product Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo $cat['CategoryId']; ?>" <?php if(isset($Stocks['CategoryId']) && ($Stocks['CategoryId']==$cat['CategoryId'])){ echo "Selected";  }?>>
                        <?php echo $cat['CategoryName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                 </div>
                </div>
            </div>
            <?php  foreach ($all_barcodes as $abars): 
                if(isset($Stocks['Barcode'])&&$abars['Barcode']==$Stocks['Barcode']) { ?>
                  <span class="form-control"><b><?php echo "Available stock for this barcode is ". Round($abars['Quantity'],2)." ".$abars['UOM']."."; ?></b></span>
            <?php } endforeach; ?>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-4">
                  <select class="form-control" id="itemsubcat" name="product-subcategory" onChange="uomcheck();">
                      <option value="">Select Product Sub Category</option>
                    <?php  foreach ($all_sub_categories as $scat): ?>
                      <option value="<?php echo $scat['SubCategoryId']; ?>" <?php if(isset($Stocks['SubCategoryId']) && ($Stocks['SubCategoryId']==$scat['SubCategoryId'])){ echo "Selected";  }?>>
                        <?php echo $scat['SubCategoryName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                  <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="text" class="form-control" name="product-quantity" placeholder="Product Quantity">

                     <?php  foreach ($all_barcodes as $abars): 
                    if(isset($Stocks['Barcode'])&&$abars['Barcode']==$Stocks['Barcode']) { ?>
                     <input type="hidden" class="form-control" name="product-quantity-limit" value="<?php echo Round($abars['Quantity'],2); ?>" placeholder="Product Quantity">
                     <?php } endforeach; ?>
                     
                  </div>
                  </div>
                  <div class="col-md-4">
                  <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-scissors"></i>
                     </span>
                     <select class="form-control" id="UomId" name="UOM">
                      <option value="">Select UOM</option>
                      <?php  foreach ($all_uom as $uoms): ?>
                      <option value="<?php echo $uoms['UomId']; ?>" <?php if(isset($Stocks['UomId']) && ($Stocks['UomId']==$uoms['UomId'])){ echo "Selected";  }?>>
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
                  <select class="form-control" name="stock-type">
                      <option value="">Select Stock Type</option>
                      <option value="In - First Entry">In - First Entry</option>
                      <option value="In - Excess">In - Excess</option>
                      <option value="Out - Plan No">Out - Plan No</option>
                      <option value="Out - Chelan No">Out - Chelan No</option>
                      <option value="Out - Chelan No">Out - Additional</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                  <select class="form-control" name="product-location">
                      <option value="">Select Product Location</option>
                    <?php  foreach ($all_locations as $loc): ?>
                      <option value="<?php echo $loc['LocationId']; ?>" <?php if(isset($Stocks['LocationId']) && ($Stocks['LocationId']==$loc['LocationId'])){ echo "Selected";  }?>>
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
                      <option value="<?php echo $uni['UnitId']; ?>">
                        <?php echo $uni['UnitName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                  <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-book"></i>
                     </span>
                     <input type="text" class="form-control" name="planno" placeholder="Chelan/Plan No">
                     <input type="hidden" class="form-control" name="barcode" value="<?php if(isset($_GET['barcode']))echo $_GET['barcode'];?>" placeholder="barcode">
                  </div>
                  </div>
                </div>
              </div>

       
              </div>
              <button type="submit" name="add_stock" class="btn btn-danger">Add Stock</button>
          </form>
         </div>
        </div>
      </div>
    </div>
    <?php } ?>

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

function subcatselect() {
      var Catvalue=document.getElementById('itemcat').value;
      //console.log(prodvalue);

      $.ajax({
    url      : 'Product_Add.php',
    data     : {'CatId':Catvalue},
    dataType: 'json',
    type     : 'POST',
    success  : function(Result){
              //console.log(Result[0]);
              //var myObj = $.parseJSON(Result);
              var myObj =Result;
              $("#itemsubcat").empty();
              $('#itemsubcat').append('<option value="">Select Product Sub Category </option>');
              console.log(myObj);
              console.log(Object.keys(myObj).length);
              //you can now access data like this:
              //myObj.Address_1
              $.each(myObj, function (i, value) {
                $('#itemsubcat').append('<option value=' + value.SubCategoryId + '>' + value.SubCategoryName + '</option>');
            });
        }
    }
  );
    
}
     </script>'

<?php include_once('layouts/footer.php'); ?>
