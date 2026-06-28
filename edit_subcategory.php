<?php
  $page_title = 'Edit Sub Category';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  //Display all sub catgories.
  $categorie = find_by_id_new('subcategorydetails',(int)$_GET['SubCategoryId'],'SubCategoryId');
  if(!$categorie){
    $session->msg("d","Missing category id.");
    redirect('subcategory.php');
  }
?>

<?php

$all_categories = find_all('categorydetails');
$all_uomtype = find_all_unique('uomdetails','UomType');

if(isset($_POST['edit_sub_cat'])){
    $req_field = array('categorie-id','sub-categorie-name','sub-categorie-uom');
  validate_fields($req_field);
 
  $sub_cat_name = remove_junk($db->escape($_POST['sub-categorie-name']));
   $cat_id = remove_junk($db->escape($_POST['categorie-id']));
   $sub_cat_uom = remove_junk($db->escape($_POST['sub-categorie-uom']));
  
  if(empty($errors)){
        $sql = "UPDATE subcategorydetails SET CategoryId='{$cat_id}',SubCategoryName='{$sub_cat_name}',UOM='{$sub_cat_uom}'";
       $sql .= " WHERE SubCategoryId='{$categorie['SubCategoryId']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Categorie");
       redirect('subcategory.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('subcategory.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('subcategory.php',false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editing <?php echo remove_junk(ucfirst($categorie['SubCategoryName']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_subcategory.php?SubCategoryId=<?php echo (int)$categorie['SubCategoryId'];?>">
           <div class="form-group">
           <select type="text" class="form-control" name="categorie-id" placeholder="Category Id">
                <?php foreach ($all_categories as $cat):?>
                    <option value="<?php echo remove_junk(ucfirst($cat['CategoryId'])); ?>" <?php if($cat['CategoryId']==$categorie['CategoryId']){echo 'selected';  } ?>><?php echo remove_junk(ucfirst($cat['CategoryName'])); ?> </option>
                <?php endforeach; ?>    
                </select>

               <input type="text" class="form-control" name="sub-categorie-name" value="<?php echo remove_junk(ucfirst($categorie['SubCategoryName']));?>">
               <select type="text" class="form-control" name="sub-categorie-uom" placeholder="Sub Category UOM">
                <?php foreach ($all_uomtype as $ut):?>
                    <option value="<?php echo remove_junk(ucfirst($ut['UomType'])); ?>" <?php if($ut['UomType']==$categorie['UOM']){echo 'selected';  } ?>><?php echo remove_junk(ucfirst($ut['UomType'])); ?> </option>
                <?php endforeach; ?>    
                </select>
               <!--<input type="text" class="form-control" name="sub-categorie-uom" placeholder="Sub Category UOM" value="<?php //echo remove_junk(ucfirst($categorie['UOM']));?>">-->
           </div>
           <button type="submit" name="edit_sub_cat" class="btn btn-primary">Update Sub Category</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
