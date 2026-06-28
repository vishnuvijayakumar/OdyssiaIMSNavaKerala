<?php
  $page_title = 'Product Locations';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  
  $all_prodlocations = find_all('locationdetails')
?>
<?php
 if(isset($_POST['add_loc'])){
   $req_field = array('prodlocation-name');
   validate_fields($req_field);
   $loc_name = remove_junk($db->escape($_POST['prodlocation-name']));
   $userid = current_user();
     $p_userid = $userid['id'];
   if(empty($errors)){
      $sql  = "INSERT INTO locationdetails (LocationName,id)";
      $sql .= " VALUES ('{$loc_name}','{$p_userid}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added New Category");
        redirect('product_location.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('product_location.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('product_location.php',false);
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
            <span>Add New Product Location</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="product_location.php">
            <div class="form-group">
                <input type="text" class="form-control" name="prodlocation-name" placeholder="Location Name">
            </div>
            <button type="submit" name="add_loc" class="btn btn-primary">Add Location</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Product Locations</span>
       </strong>
      </div>
        <div class="panel-body">
          <table id="prodlocationtable" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Locations</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_prodlocations as $loc):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($loc['LocationName'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_product_location.php?LocationId=<?php echo (int)$loc['LocationId'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_product_location.php?LocationId=<?php echo (int)$loc['LocationId'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
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
