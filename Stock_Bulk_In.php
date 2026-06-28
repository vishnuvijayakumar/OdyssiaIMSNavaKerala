<?php


  $page_title = 'Add Stock';
  require_once('includes/load.php');

  require_once $_SERVER['DOCUMENT_ROOT'].'/InventorySystem/barcode.php';


  // Checkin What level user has permission to view this page
  page_require_level(4);
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
  $resultcheckbarcode=0;

  if(isset($_GET["stockdet"])) {
    $stockdet=$_GET["stockdet"];
  }

  if(isset($_GET['barcode'])){
    $Stocks = find_by_id_new('stockdetails',$_GET['barcode'],'Barcode');
    //print_r($Stocks);die();
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

  if(isset($_POST['fullbarcodvalue'])){

    $fbarcodefull  = remove_junk($db->escape($_POST['fullbarcodvalue']));
    $barcodevaluescheck = check_barcode_entry($fbarcodefull);

    if($barcodevaluescheck) {
      $resultcheckbarcode=1;
    }

    header('Content-Type: application/json');
    echo json_encode($resultcheckbarcode);
    exit;

  }

  if(isset($_POST['BaseUom'])){

    $buom  = remove_junk($db->escape($_POST['BaseUom'])); 

    $sql = $db->query("SELECT * FROM uomdetails WHERE UomSubType='{$db->escape($buom)}' LIMIT 1");
          $result = $db->fetch_assoc($sql);
    //$uomtypedetails=find_by_id_custom('uomdetails',$buom,'UomSubType');
   //print_r($result);die();
    $buomdetails = base_uom_check($result['UomType']);

    header('Content-Type: application/json');
    echo json_encode($buomdetails);
    exit;

  }

  ?>

<?php
 if(isset($_POST['add_stock'])){
  

  $successflag=0;
   //$req_fields = array('barcode','product-name','product-category','product-subcategory','product-quantity','UOM','stock-type','product-location' );
   $req_fields= array('product-name','product-category','product-subcategory','UOM','product-location');
   validate_fields($req_fields);
   $productname=$_POST['product-name'];
   $productcategory=$_POST['product-category'];
   $productsubcategory=$_POST['product-subcategory'];
   $productUom=$_POST['UOM'];
   $productlocation=$_POST['product-location'];
   
   if(empty($errors)){
    if(empty($errors)&&isset($_POST['fullbarcode'])&&isset($_POST['product-qty'])) {
      
    $fbarcode=$_POST['fullbarcode'];
    $productqty = $_POST['product-qty'];
    $validation=0;
    $barcodeArray = [];
    //print_r($fbarcode);die();

    foreach($fbarcode as $key1 => $bar1) {

      if(empty($bar1)) {
        $validation=1;
        $errorsnew="Barcode is missing on the ".($key1+1) ." Entry";
        break; 
      }

      if(empty($productqty[$key1])) {
        $validation=1;
        $errorsnew="Product quantity is missing on the ". ($key1+1) ." Entry";
        break; 
      }

      if (in_array($bar1, $barcodeArray)) {
        $validation=1;
        $errorsnew="Cannot use same barcode on ". ($key1+1) ." Entry";
        break; 
      }
      array_push($barcodeArray,$bar1);

      $barcodealready=check_barcode_entry($bar1);
      //print_r($barcodealready);die();
      foreach($barcodealready as $dupbarcode) {
        $b_codedup=remove_junk($db->escape($dupbarcode['Barcode']));
        $dupproductname=remove_junk($db->escape($dupbarcode['ProductId']));
        //echo $productname,"---",$dupproductname;die();
        //print_r();die();
        if($dupproductname!=NULL && !(empty($dupproductname))) {
        if($productname==$dupproductname) {
          if($productcategory==3) {
            $validation=1;
            $errorsnew="Cannot use already used barcode for rexin on Bulk In!!...";
            break; 
            } 
        } else {
          $validation=1;
          $errorsnew="Cannot use different product assigned barcode on Bulk In!!...";
          break; 
          
        }
      } 
        //echo $b_codedup,$dupproductname;
      }

    }
    //echo $validation;die();

    if($validation == 1) {
      $session->msg("d", $errorsnew);
      redirect('Stock_Bulk_In.php',false);
    }

    foreach($fbarcode as $key => $bar) {
        $b_code=remove_junk($db->escape($bar));
      $stockentryreq=  check_barcode_entry($b_code);
      //echo $bar;
        //print_r($stockentryreq);die();
      if($stockentryreq) {
        foreach($stockentryreq as $stvals) {
            $b_code=remove_junk($db->escape($stvals['bbar']));
            $p_code  = remove_junk($db->escape($productname));
            $p_cat   = remove_junk($db->escape($productcategory));
            $p_subcat = remove_junk($db->escape($productsubcategory));
            $p_qty   = remove_junk($db->escape($productqty[$key]));
            $p_uom  = remove_junk($db->escape($productUom));
            $p_loc  = remove_junk($db->escape($productlocation));
            $p_stock_type  = remove_junk($db->escape('In - First Entry'));
            $p_unit  = remove_junk($db->escape(1));
            $p_planno  = remove_junk($db->escape('None'));
            $userid = current_user();
            $p_userid = $userid['id'];

             //echo "barcode".$b_code,"productcode".$p_code,"cate".$p_cat,"subcat".$p_subcat,"qty".$p_qty;
             //echo "uom".$p_uom,"loc".$p_loc,"stocktype".$p_stock_type,"unit".$p_unit,"planno".$p_planno,"user".$p_userid;;

             $stockdetailscheck = find_by_id_new('barcodedetails',$stvals['bbar'],'Barcode');
             //echo $stvals['bbar'];
             //print_r($stockdetailscheck);die();
             if(isset($stockdetailscheck['Barcode'])){
        
             $query  = "INSERT INTO stockdetails (";
             $query .=" Barcode,ProductId,CategoryId,SubCategoryId,StockType,Quantity,UomId,LocationId,UnitId,PlanNo,id";
             $query .=") VALUES (";
             $query .=" '{$b_code}','{$p_code}', '{$p_cat}', '{$p_subcat}', '{$p_stock_type}', '{$p_qty}','{$p_uom}', '{$p_loc}', '{$p_unit}', '{$p_planno}', '{$p_userid}'";
             $query .=")";
           
             if($db->query($query)){
               $successflag=1;
             } else {
               $session->msg('d',' Sorry failed to added!');
               redirect('Stock_Bulk_In.php', false);
             }
        
           
        
           } else {
            $session->msg('d',' Sorry unable to find the '.$b_code.' Barcode!');
            redirect('Stock_Bulk_In.php', false);
          }

        }
        //echo "yes";die();

      } else {
        $session->msg('d',' No product added to the given barcode '.$bar.' !');
        redirect('Stock_Bulk_In.php', false);
      }


     } 

     if($successflag == 1) {
      $session->msg('s',"Stock Entry added ");
      redirect('Stock_Bulk_In.php', false);
     }

 } else {
  $session->msg("d", $errors);
  redirect('Stock_Bulk_In.php',false);
} 

} else {
  $session->msg("d", $errors);
  redirect('Stock_Bulk_In.php',false);
} 


 }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

           <div class="panel panel-default" >
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-plus"></span>
            <span>Stock Bulk In</span>
         </strong>
        </div>
        <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="Stock_Bulk_In.php" id="Bulk_Add" class="clearfix" onsubmit="return formValidations(event)">
            <div class="form-group container1">
                <div class="form-group">
              <div class="row">
                 <div class="col-md-3">
                    <input type="text" class="form-control" id="barcode1" name="fullbarcode[]" placeholder="Scan/Enter Barcode" required>
                     </div>
                 <div class="col-md-2">
                 <input type="number" class="form-control" id="pqty1" name="product-qty[]" placeholder="Product Quantity" step="any" required>
                 </div>
                 <a href="#" class="btn btn-success add_form_field_In"><span class="glyphicon glyphicon-plus"></span></a>
                </div>
                </div>
            </div>
            <div class="form-group">

            <div class="row">
            <div class="col-md-3">
                    <select class="form-control" id="itemcode" name="product-name" onChange="enable()"; required>
                      <option value="">Select Product</option>
                    <?php  foreach ($all_products as $prod): ?>
                      <option value="<?php echo $prod['ProductId']; ?>" <?php if(isset($Stocks['ProductId']) && ($Stocks['ProductId']==$prod['ProductId'])){ echo "Selected";  }?>>
                        <?php echo $prod['ItemName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                     </div>
                 <div class="col-md-3">
                 <select class="form-control" id="itemcat" name="product-category" onChange="subcatselect()" readonly="true" required>
                      <option value="">Select Product Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo $cat['CategoryId']; ?>" <?php if(isset($Stocks['CategoryId']) && ($Stocks['CategoryId']==$cat['CategoryId'])){ echo "Selected";  }?>>
                        <?php echo $cat['CategoryName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                 </div>
                 <div class="col-md-3">
                  <select class="form-control" id="itemsubcat" name="product-subcategory" onChange="uomcheck();" readonly="true" required>
                      <option value="">Select Product Sub Category</option>
                    <?php  foreach ($all_sub_categories as $scat): ?>
                      <option value="<?php echo $scat['SubCategoryId']; ?>" <?php if(isset($Stocks['SubCategoryId']) && ($Stocks['SubCategoryId']==$scat['SubCategoryId'])){ echo "Selected";  }?>>
                        <?php echo $scat['SubCategoryName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                        </div>
                    </div>

                <div class="form-group">
                    <div class="row">
                    <div class="col-md-3">
                     <select class="form-control" id="UomId" name="UOM" required>
                      <option value="">Select UOM</option>
                      <?php  foreach ($all_uom as $uoms): ?>
                      <option value="<?php echo $uoms['UomId']; ?>" <?php if(isset($Stocks['UomId']) && ($Stocks['UomId']==$uoms['UomId'])){ echo "Selected";  }?>>
                        <?php echo $uoms['UomSubType'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                  <select class="form-control" name="product-location" required>
                      <option value="">Select Product Location</option>
                    <?php  foreach ($all_locations as $loc): ?>
                      <option value="<?php echo $loc['LocationId']; ?>" <?php if(isset($Stocks['LocationId']) && ($Stocks['LocationId']==$loc['LocationId'])){ echo "Selected";  }?>>
                        <?php echo $loc['LocationName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                        
                        </div>
                    </div>
                </div>
            <?php  foreach ($all_barcodes as $abars): 
                if(isset($Stocks['Barcode'])&&$abars['Barcode']==$Stocks['Barcode']) { ?>
                  <span class="form-control"><b><?php echo "Available stock for this barcode is ". Round($abars['Quantity'],2)." ".$abars['UOM']."."; ?></b></span>
            <?php } endforeach; ?>
            
              <button type="submit" id="Bulk_Add_Button" name="add_stock" class="btn btn-danger" >Add Stock</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  

  </div>

  <script type="text/JavaScript"> 
var barcvalues = [];
var barcvaluestest = [];
var prodqtyarray = [];
var prodavlqtyarray = [];

function checkIfDuplicateExists(arr) {
    return new Set(arr).size !== arr.length
}

function formValidations() {
  var checkvalbarcode=0;
barcvaluestest = $("input[name='fullbarcode[]']").map(function(){return $(this).val();}).get();
prodqtyarray = $("input[name='product-qty[]']").map(function(){return $(this).val();}).get();
console.log(barcvaluestest);

if(checkIfDuplicateExists(barcvaluestest)) {
  alert("Duplicate entry with same barcodes found!! Cannot allow duplicate entry.");
  return false;
} 
$("#Bulk_Add_Button").disabled = true;
$("#Bulk_Add").submit();

}


    function enable() {
      var prodvalue=document.getElementById('itemcode').value;

      $.ajax({
    url      : 'Stock_Add.php',
    data     : {'ProdID':prodvalue},
    dataType: 'json',
    type     : 'POST',
    success  : function(Result){
              var myObj =Result[0];
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
              console.log(Result);
              //var myObj = $.parseJSON(Result);
              var myObj =Result;
              $("#itemsubcat").empty();
              $('#itemsubcat').append('<option value="">Select Product Sub Category </option>');
              //console.log(myObj);
              $.each(myObj, function (i, value) {
                $('#itemsubcat').append('<option value=' + value.SubCategoryId + '>' + value.SubCategoryName + '</option>');
            });
        }
    }
  );
    
}
     </script>'

<?php include_once('layouts/footer.php'); ?>
