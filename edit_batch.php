<?php
  $page_title = 'Edit Batch';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  //Display all catgories.
  //if(isset($_GET['BatchId'])){
  $categorie = find_by_id_new('batchdetails',(int)$_GET['BatchId'],'BatchId');
  if(!$categorie){
    $session->msg("d","Missing Batch id.");
    redirect('Batch.php');
  }
 // }
?>

<?php
if(isset($_POST['edit_cat'])){
  $req_field = array('batch-name','batch-shortname','batch-date');
  validate_fields($req_field);
  $batch_name = remove_junk($db->escape($_POST['batch-name']));
  $batch_shortname = remove_junk($db->escape($_POST['batch-shortname']));
  $batch_date = remove_junk($db->escape($_POST['batch-date']));
  
  if(empty($errors)){
        $sql = "UPDATE batchdetails SET BatchName='{$batch_name}', BatchShortName='{$batch_shortname}', BatchDate='{$batch_date}'";
       $sql .= " WHERE BatchId='{$categorie['BatchId']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Batch");
       redirect('Batch.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
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
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editing <?php echo remove_junk(ucfirst($categorie['BatchName']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_batch.php?BatchId=<?php echo (int)$categorie['BatchId'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="batch-name" value="<?php echo remove_junk(ucfirst($categorie['BatchName']));?>">
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="batch-shortname" value="<?php echo remove_junk(ucfirst($categorie['BatchShortName']));?>">
           </div>
           <div class="form-group">
               <input type="text" class="form-control" name="batch-date" value="<?php echo remove_junk(ucfirst($categorie['BatchDate']));?>">
           </div>
           <button type="submit" name="edit_cat" class="btn btn-primary">Update Batch</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
