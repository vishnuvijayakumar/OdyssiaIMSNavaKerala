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
  
    if(isset($_POST['checkbarcode'])){

    $fbarcodefull  = remove_junk($db->escape($_POST['checkbarcode']));
    $barcodevaluescheck = fullbarcode_fetch_detailsnew($fbarcodefull);
    $p_batch=$barcodevaluescheck['BatchId'];
    $p_code=$barcodevaluescheck['ProductId'];
    $barcodecheck='Pass';
    
    $batchdetails= find_by_id_custom("batchdetails",$p_batch,"BatchId");
      if((!empty($batchdetails)) && isset($batchdetails['BatchDate'])) {
          
          $checkstockbatchstatus=checkbatchinstock($batchdetails['BatchDate'],$p_code);
          
          if(!empty($checkstockbatchstatus) && isset($checkstockbatchstatus['BatchShortName'])) {
              $barcodecheck='Fail';
          }
          
          //print_r($batchdetails);die();
      }

    header('Content-Type: application/json');
    echo json_encode($barcodecheck);
    exit;

  }

  if(isset($_POST['fullbarcodvalue'])){

    $fbarcodefull  = remove_junk($db->escape($_POST['fullbarcodvalue']));
    $barcodevaluescheck = fullbarcode_fetch_details($fbarcodefull);

    header('Content-Type: application/json');
    echo json_encode($barcodevaluescheck);
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
   $req_fields= array('planno','unit','stock-type');
   validate_fields($req_fields);

   //if(empty($errors)){
    if(empty($errors)&&isset($_POST['fullbarcode'])&&isset($_POST['product-qty'])&&isset($_POST['product-avl-qty'])) {
    $fbarcode=$_POST['fullbarcode'];
    $productqty = $_POST['product-qty'];
    $prodavlqty = $_POST['product-avl-qty'];
    $produomarray = $_POST['UOM'];
    $validation=0;
    $barcodeArray = [];
    //print_r($productqty);die();

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

      if(empty($produomarray[$key1])) {
        $validation=1;
        $errorsnew="Uom is missing on the ". ($key1+1) ." Entry";
        break; 
      }

      if (in_array($bar1, $barcodeArray)) {
        $validation=1;
        $errorsnew="Cannot use smae barcode on ". ($key1+1) ." Entry";
        break; 
      }
      array_push($barcodeArray,$bar1);

      if($productqty[$key1]>$prodavlqty[$key1]) {
        $validation=1;
        $errorsnew="Product quantity provided is grater than available quantity on ". ($key1+1) ." Entry";
        break;
      }

    }

    if($validation == 1) {
      $session->msg("d", $errorsnew);
      redirect('Stock_Bulk_Add.php',false);
    }

    foreach($fbarcode as $key => $bar) {
        $b_code=remove_junk($db->escape($bar));
      $stockentryreq=  check_barcode_entry($b_code);
        //print_r($stockentryreq);die();
      if($stockentryreq) {
        foreach($stockentryreq as $stvals) {
            $b_code=remove_junk($db->escape($stvals['Barcode']));
            $p_code  = remove_junk($db->escape($stvals['ProductId']));
            $p_cat   = remove_junk($db->escape($stvals['CategoryId']));
            $p_subcat = remove_junk($db->escape($stvals['SubCategoryId']));
            $p_qty   = remove_junk($db->escape($productqty[$key]));
            $p_uom  = remove_junk($db->escape($produomarray[$key]));
            $p_loc  = remove_junk($db->escape($stvals['LocationId']));
            $p_stock_type  = remove_junk($db->escape($_POST['stock-type']));
            $p_unit  = remove_junk($db->escape($_POST['unit']));
            $p_planno  = remove_junk($db->escape($_POST['planno']));
            $userid = current_user();
            $p_userid = $userid['id'];


            if(isset($prodavlqty[$key]) && (remove_junk($db->escape($prodavlqty[$key])<$p_qty))) {
              $errors="Requiired stock is not available on ".$b_code." barcode...!";
              $session->msg("d", $errors);
              redirect('Stock_Bulk_Add.php',false);
             }

             //echo "barcode".$b_code,"productcode".$p_code,"cate".$p_cat,"subcat".$p_subcat,"qty".$p_qty;
             //echo "uom".$p_uom,"loc".$p_loc,"stocktype".$p_stock_type,"unit".$p_unit,"planno".$p_planno,"user".$p_userid;;

             $stockdetailscheck = find_by_id_new('barcodedetails',$stvals['Barcode'],'Barcode');
             //print_r($stockdetailscheck);//die();
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
               redirect('Stock_Bulk_Add.php', false);
             }
        
           
        
           } else {
            $session->msg('d',' Sorry unable to find the '.$b_code.' Barcode!');
            redirect('Stock_Bulk_Add.php', false);
          }

        }
        //echo "yes";die();

      } else {
        $session->msg('d',' No product added to the given barcode '.$bar.' !');
        redirect('Stock_Bulk_Add.php', false);
      }


     } 

     if($successflag == 1) {
      $session->msg('s',"Stock Entry added ");
      redirect('Stock_Bulk_Add.php', false);
     }

 } else {
  $session->msg("d", $errors);
  redirect('Stock_Bulk_Add.php',false);
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
            <span>Stock Bulk Out</span>
         </strong>
        </div>
        <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="Stock_Bulk_Add.php" id="Bulk_Add" class="clearfix" onsubmit="return formValidations(event)">
            <div class="form-group container1">
                <div class="form-group">
              <div class="row">
                 <div class="col-md-3">
                    <input type="text" class="form-control" id="barcode1" name="fullbarcode[]" placeholder="Scan/Enter Barcode" required>
                     </div>
                 <div class="col-md-2">
                 <input type="number" class="form-control" id="pqty1" name="product-qty[]" placeholder="Product Quantity" onClick="checkbarcode(barcode1,pqty1,pavlqty1,UomId1)" step="any" required>
                 </div>
                 <div class="col-md-2">
                 <input type="text" class="form-control" id="pavlqty1" name="product-avl-qty[]" placeholder="Product Available Quantity" readonly="true" required>
                 </div>
                 <div class="col-md-2">
                     <select class="form-control" id="UomId1" name="UOM[]" required>
                      <option value="">Select UOM</option>
                      <?php  foreach ($all_uom as $uoms): ?>
                      <option value="<?php echo $uoms['UomId']; ?>" <?php if(isset($Stocks['UomId']) && ($Stocks['UomId']==$uoms['UomId'])){ echo "Selected";  }?>>
                        <?php echo $uoms['UomSubType'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                 <a href="#" class="btn btn-success add_form_field"><span class="glyphicon glyphicon-plus"></span></a>
                </div>
                </div>
            </div>
            <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="planno" placeholder="Plan No/Chelan No" required>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control unitcheck" name="unit" required>
                                <option value="">Select Unit</option>
                                    <?php  foreach ($all_units as $uni): ?>
                                <option value="<?php echo $uni['UnitId']; ?>">
                                    <?php echo $uni['UnitName'] ?></option>
                                    <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="stock-type" required>
                                <option value="">Select Stock Type</option>
                                <option value="Out - Plan No">Out - Plan No</option>
                                <option value="Out - Challan">Out - Challan No</option>
                                <option value="Out - Additional">Out - Additional</option>
                            </select>
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
barcvaluestest = $("input[name='fullbarcode[]']").map(function(){return $(this).val();}).get();
prodqtyarray = $("input[name='product-qty[]']").map(function(){return $(this).val();}).get();
prodavlqtyarray = $("input[name='product-avl-qty[]']").map(function(){return $(this).val();}).get();
//console.log(barcvaluestest);

if(checkIfDuplicateExists(barcvaluestest)) {
  alert("Duplicate entry with same barcodes found!! Cannot allow duplicate entry.");
  return false;
} 
for (let i = 0; i < prodqtyarray.length; i++) {
 if(Math.round(prodqtyarray[i]) > Math.round(prodavlqtyarray[i])) {
  alert("Product quantity provided is greater than the available quantity for "+barcvaluestest[i]);
  return false;
 }
}
$("#Bulk_Add_Button").disabled = true;
$("#Bulk_Add").submit();

}

function checkbarcode(barcodevalue,prodqty,prodavlqty,produom) {
    var bacodevalue1;
    var produom1;
    
    if (typeof barcodevalue.value != 'undefined' && barcodevalue.length != 2) {
       bacodevalue1=barcodevalue.value;
    } else {
       bacodevalue1=barcodevalue[0].value;
    }
    
    if (typeof produom.value != 'undefined' && produom.length != 2) {
       produom1=produom.value;
    } else {
       produom1=produom[0].value;
    }
    
    $.ajax({
    url      : 'Stock_Bulk_Add.php',
    data     : {'checkbarcode':bacodevalue1},
    dataType: 'json',
    type     : 'POST',
    success  : function(Result){
        var response= Result.trim();
       if(response=="Pass") {
           console.log(response);
         fetchvalues(barcodevalue,prodqty,prodavlqty,produom);  
        } else {
            console.log("no");
            alert("We have older batch barcode instock so kindly select that first...!");
        }
              
        }
    }

  );

}

function fetchvalues(barcodevalue,prodqty,prodavlqty,produom) {
    var bacodevalue1;
    
    if (typeof barcodevalue.value != 'undefined' && barcodevalue.length != 2) {
       bacodevalue1=barcodevalue.value;
    } else {
       bacodevalue1=barcodevalue[0].value;
    }

      $.ajax({
    url      : 'Stock_Bulk_Add.php',
    data     : {'fullbarcodvalue':bacodevalue1},
    dataType: 'json',
    type     : 'POST',
    success  : function(Result){
              var myObj =Result[0];
              prodavlqty.value=myObj.avlqty;
              uomcheck(produom,myObj.baseuom);
        }
    }

  );

    
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

function uomcheck(produom1,baseuom1) {
      if(produom1.length==2) {
        produom1=produom1[0];
      }
      console.log(produom1.id);

      $.ajax({
    url      : 'Stock_Bulk_Add.php',
    data     : {'BaseUom':baseuom1},
    dataType: 'json',
    type     : 'POST',
    success  : function(Result){
              //console.log(Result);
              //var myObj = $.parseJSON(Result);
              var myObj =Result;
              var obid=produom1.id;
              $("#"+obid).empty();
              $("#"+obid).append('<option value="">Select UOM </option>');
              console.log(myObj);
              //you can now access data like this:
              //myObj.Address_1
              $.each(myObj, function (i, value) {
                if(value.BaseUomFlag == 1) {
                  $("#"+obid).append('<option value=' + value.UomId + ' selected>' + value.UomSubType + '</option>');
                } else {
                $("#"+obid).append('<option value=' + value.UomId + ' >' + value.UomSubType + '</option>');
                }
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
