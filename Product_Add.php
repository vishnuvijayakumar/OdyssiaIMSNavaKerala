<?php


  $page_title = 'Add Product';
  require_once('includes/load.php');

  require_once $_SERVER['DOCUMENT_ROOT'].'/InventorySystem/barcode.php';


  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categorydetails');
  $all_sub_categories = find_all('subcategorydetails');
  $all_locations = find_all('locationdetails');
  $all_photo = find_all('media');
?>

<?php
if(isset($_POST['CatId'])){

$catid  = remove_junk($db->escape($_POST['CatId']));
$subcategorydetails1 = category_subcategory_table($catid);

header('Content-Type: application/json');
echo json_encode($subcategorydetails1);
exit;

}
?>

<?php
 if(isset($_POST['add_product'])){
   $req_fields = array('product-code','product-title','product-category','product-subcategory' );
   validate_fields($req_fields);
   if(empty($errors)){
    $p_code  = remove_junk($db->escape($_POST['product-code']));
     $p_name  = remove_junk($db->escape($_POST['product-title']));
     $p_cat   = remove_junk($db->escape($_POST['product-category']));
     $p_subcat = remove_junk($db->escape($_POST['product-subcategory']));
     $userid = current_user();
     $p_userid = $userid['id'];
     //echo $p_code,$p_name;die();
     //$p_qty   = remove_junk($db->escape($_POST['product-quantity']));
     //$p_loc  = remove_junk($db->escape($_POST['product-location']));

     //if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
    //   $media_id = '0';
     //} else {
     //  $media_id = remove_junk($db->escape($_POST['product-photo']));
     //}
     //$date    = make_date();

     $query  = "INSERT INTO productdetails (";
     $query .=" ItemCode,ItemName,CategoryId,SubCategoryId,id";
     $query .=") VALUES (";
     $query .=" '{$p_code}','{$p_name}', '{$p_cat}', '{$p_subcat}','{$p_userid}'";
     $query .=")";
     //$query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
     if($db->query($query)){
       $session->msg('s',"Product added ");
       redirect('Product_Add.php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('Product_Add.php',false);
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
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="Product_Add.php" class="clearfix">
              <div class="form-group">
              <div class="row">
                 <div class="col-md-6">
                    <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-code" placeholder="Item Code">
                     </div>
                 </div>
                 <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Item Name">
               </div>
              </div>
                </div>
            </div>
             
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select id="CatId" class="form-control" name="product-category" onChange="subcatselect()">
                      <option value="">Select Product Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo $cat['CategoryId']; ?>">
                        <?php echo $cat['CategoryName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select id="SubcatId" class="form-control" name="product-subcategory">
                      <option value="">Select Product Sub Category</option>
                    <?php  foreach ($all_sub_categories as $scat): ?>
                      <option value="<?php echo $scat['SubCategoryId']; ?>">
                        <?php echo $scat['SubCategoryName'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <!---<div class="form-group">
               <div class="row">
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity">
                  </div>
                 </div>
                 <div class="col-md-6">
                    <select class="form-control" name="product-location">
                      <option value="">Select Product Location</option>
                    <?php  //foreach ($all_locations as $loc): ?>
                      <option value="<?php //echo (int)$loc['LocationId'] ?>">
                        <?php// echo $loc['LocationName'] ?></option>
                    <?php //endforeach; ?>
                    </select>
                  </div>
               
               </div>-->
              </div>
              <button type="submit" name="add_product" class="btn btn-danger">Add product</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/JavaScript"> 
  function subcatselect() {
      var Catvalue=document.getElementById('CatId').value;
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
              $("#SubcatId").empty();
              $('#SubcatId').append('<option value="">Select Product Sub Category </option>');
              console.log(myObj);
              console.log(Object.keys(myObj).length);
              //you can now access data like this:
              //myObj.Address_1
              $.each(myObj, function (i, value) {
                $('#SubcatId').append('<option value=' + value.SubCategoryId + '>' + value.SubCategoryName + '</option>');
            });
        }
    }
  );
    
}
     </script>'


<?php include_once('layouts/footer.php'); ?>
