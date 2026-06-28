<?php
  $page_title = 'All Batchs';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  
  $all_categories = find_all('batchdetails')
?>
<?php
 if(isset($_POST['add_cat'])){
   $req_field = array('batch-name','batch-date');
   validate_fields($req_field);
   $batch_name = remove_junk($db->escape($_POST['batch-name']));
   $batch_date = remove_junk($db->escape($_POST['batch-date']));
   $batch_shortname = remove_junk($db->escape($_POST['batch-shortname']));
   $userid = current_user();
     $p_userid = $userid['id'];
   if(empty($errors)){
      $sql  = "INSERT INTO batchdetails (BatchName,BatchShortName,BatchDate,id)";
      $sql .= " VALUES ('{$batch_name}','{$batch_shortname}','{$batch_date}','{$p_userid}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added New Batch");
        redirect('Batch.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('Batch.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('Batch.php',false);
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
            <span>Add New Batch</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="Batch.php">
            <div class="form-group">
                <input type="text" class="form-control" name="batch-name" placeholder="Batch Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="batch-shortname" placeholder="Batch Short Name">
            </div>
            <div class="form-group">
                <input type="text" class="datepicker form-control" name="batch-date" placeholder="Batch Date">
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
          <span>All Batchs</span>
       </strong>
      </div>
        <div class="panel-body">
          <table id="categorytable" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Batch Name</th>
                    <th>Batch Short Name</th>
                    <th>Batch Date</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_categories as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($cat['BatchName'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($cat['BatchShortName'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($cat['BatchDate'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_batch.php?BatchId=<?php echo (int)$cat['BatchId'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_batch.php?BatchId=<?php echo (int)$cat['BatchId'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
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
