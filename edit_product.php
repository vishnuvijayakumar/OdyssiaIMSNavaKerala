<?php
$passid=0;
  $page_title = 'Edit Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
if(isset($_GET['ProductId'])) {
  $passid=$_GET['ProductId'];
} 

if(isset($_POST['ProductId'])) {
  $passid=$_POST['ProductId'];
} 
//echo $passid;die();
$all_categories = find_all('categorydetails');
$all_subcategories = find_all('subcategorydetails');
$product = find_by_id_new('productdetails',$passid,'ProductId');
//print_r($product);

if(!(isset($product['ProductId']))){
  //echo $passid;  print_r($product);die("nothing");
  $session->msg("d","Missing product id.");
  redirect('product.php');
}//die();
?>
<?php
 if(isset($_POST['product'])){
    $req_fields = array('product-code','product-title','product-categorie','product-subcategory');
    validate_fields($req_fields);

   if(empty($errors)){
       $p_code   = remove_junk($db->escape($_POST['product-code']));
       $p_name  = remove_junk($db->escape($_POST['product-title']));
       $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
       $p_subcat   = remove_junk($db->escape($_POST['product-subcategory']));
       //$p_sale  = remove_junk($db->escape($_POST['saleing-price']));
       //if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       //  $media_id = '0';
      // } else {
      //   $media_id = remove_junk($db->escape($_POST['product-photo']));
       //}
       
       $query   = "UPDATE productdetails SET";
       $query  .=" Itemcode='{$p_code}', ItemName ='{$p_name}', CategoryId ='{$p_cat}',SubCategoryId = '{$p_subcat}'";
       $query  .=" WHERE ProductId ='{$product['ProductId']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Product updated ");
                 redirect('product.php', false);
               } else {
                 $session->msg('d',' Sorry failed to updated!');
                 redirect('edit_product.php?ProductId='.$product['ProductId'], false);
               }

   } else{
       $session->msg("d", $errors);
       redirect('edit_product.php?ProductId='.$product['ProductId'], false);
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
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-7">
           <form method="post" action="edit_product.php?ProductId=<?php echo (int)$product['ProductId']; ?>">
              <div class="form-group">
                <div class="row">
                <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="hidden" class="form-control" name="ProductId" value="<?php echo remove_junk((int)$product['ProductId']);?>">
                  <input type="hidden" class="form-control" name="product" value="1">
                  <input type="text" class="form-control" name="product-code" value="<?php echo remove_junk($product['Itemcode']);?>">
               </div>
               </div>
               <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['ItemName']);?>">
                </div>
               </div>
               </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                    <option value=""> Select a Category</option>
                   <?php  foreach ($all_categories as $cat): ?>
                     <option value="<?php echo (int)$cat['CategoryId']; ?>" <?php if((int)$product['CategoryId'] === (int)$cat['CategoryId']): echo "selected"; endif; ?> >
                       <?php echo remove_junk($cat['CategoryName']); ?></option>
                   <?php endforeach; ?>
                 </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-subcategory">
                    <option value=""> Select a Sub Category</option>
                   <?php  foreach ($all_subcategories as $scat): ?>
                     <option value="<?php echo (int)$scat['SubCategoryId']; ?>" <?php if($product['SubCategoryId'] === $scat['SubCategoryId']): echo "selected"; endif; ?> >
                       <?php echo remove_junk($scat['SubCategoryName']); ?></option>
                   <?php endforeach; ?>
                 </select>
                  </div>
                </div>
                </div>
              <button type="submit" name="product" class="btn btn-danger">Update</button>
             
          </form>
         </div>
        </div>
      </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
