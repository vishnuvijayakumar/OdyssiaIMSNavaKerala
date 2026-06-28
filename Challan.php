<?php
  $page_title = 'All Challans';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  
  $all_categories = find_all('challandetails')
?>
<?php
 if(isset($_POST['add_cat'])){
   $req_field = array('challan-name','reqqty');
   validate_fields($req_field);
   $Challan_name = remove_junk($db->escape($_POST['challan-name']));
   $Challan_qty = remove_junk($db->escape($_POST['reqqty']));
   $userid = current_user();
     $p_userid = $userid['id'];

     $dupentry=find_by_anycolumn("challandetails", $Challan_name,"ChallanName");
     //print_r($dupentry);echo $dupentry;die();
     if(isset($dupentry['ChallanId'])) {
         $session->msg("d", "Sorry Failed to insert because of duplicate entry for challan.");
        redirect('Challan.php',false);
     }
         
   if(empty($errors)){
      $sql  = "INSERT INTO challandetails (ChallanName,RequiredQty,id)";
      $sql .= " VALUES ('{$Challan_name}','{$Challan_qty}','{$p_userid}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added New Challan");
        redirect('Challan.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('Challan.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('Challan.php',false);
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
            <span>Add New Challan</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="Challan.php">
            <div class="form-group">
                <input type="text" class="form-control" name="challan-name" placeholder="Challan Name">
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="reqqty" placeholder="Required Quantity">
            </div>
            <button type="submit" name="add_cat" class="btn btn-primary">Add Batch</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Challans</span>
       </strong>
      </div>
        <div class="panel-body">
          <table id="categorytable" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Challan Name</th>
                    <th>Required Quantity</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_categories as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($cat['ChallanName'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($cat['RequiredQty'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_challan.php?ChallanId=<?php echo (int)$cat['ChallanId'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_challan.php?ChallanId=<?php echo (int)$cat['ChallanId'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
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
