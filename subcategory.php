<?php
  $page_title = 'All Sub Categories';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $joinsubcategory = join_category_table();
  $all_categories = find_all('categorydetails');
  $all_sub_categories = find_all('subcategorydetails');
  $all_uomtype = find_all_unique('uomdetails','UomType');
?>
<?php
 if(isset($_POST['add_sub_cat'])){  
   $req_field = array('categorie-id','sub-categorie-name','sub-categorie-uom');
   validate_fields($req_field);
   $sub_cat_name = remove_junk($db->escape($_POST['sub-categorie-name']));
   $cat_id = remove_junk($db->escape($_POST['categorie-id']));
   $sub_cat_uom = remove_junk($db->escape($_POST['sub-categorie-uom']));
   $userid = current_user();
    $p_userid = $userid['id'];
   if(empty($errors)){
      $sql  = "INSERT INTO subcategorydetails (CategoryId,SubCategoryName,UOM,id)";
      $sql .= " VALUES ('{$cat_id}','{$sub_cat_name}','{$sub_cat_uom}','{$p_userid}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added New Category");
        redirect('subcategory.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
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
  </div>
   <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Sub Category</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="subcategory.php">
            <div class="form-group">
                <select type="text" class="form-control" name="categorie-id" placeholder="Category Id">
                <option value="">Select Product Category</option>
                <?php foreach ($all_categories as $cat):?>
                    <option value="<?php echo remove_junk(ucfirst($cat['CategoryId'])); ?>"><?php echo remove_junk(ucfirst($cat['CategoryName'])); ?></option>
                <?php endforeach; ?>    
                </select>

                <input type="text" class="form-control" name="sub-categorie-name" placeholder="Sub Category Name">
                <select type="text" class="form-control" name="sub-categorie-uom" placeholder="Sub Category UOM">
                <option value="">Select UOM Type</option>
                <?php foreach ($all_uomtype as $ut):?>
                    <option value="<?php echo remove_junk(ucfirst($ut['UomType'])); ?>"><?php echo remove_junk(ucfirst($ut['UomType'])); ?></option>
                <?php endforeach; ?>    
                </select>
               <!-- <input type="text" class="form-control" name="sub-categorie-uom" placeholder="Sub Category UOM">-->
            </div>
            <button type="submit" name="add_sub_cat" class="btn btn-primary">Add Sub Category</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Sub Categories</span>
       </strong>
      </div>
        <div class="panel-body">
          <table id="subcategorytable" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
					<th>Categories</th>
                    <th>Sub Categories</th>
                    <th>UOM Type</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($joinsubcategory as $scat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
					<td><?php echo remove_junk(ucfirst($scat['CategoryName'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($scat['SubCategoryName'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($scat['UOM'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_subcategory.php?SubCategoryId=<?php echo (int)$scat['SubCategoryId'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_subcategory.php?SubCategoryId=<?php echo (int)$scat['SubCategoryId'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                          <span class="glyphicon glyphicon-trash"></span>
                        </a>
                      </div>
                    </td>

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div>
    </div>
    </div>
   </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
