<?php
  $page_title = 'Edit Product Location';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  //Display all catgories.
  $locations = find_by_id_new('locationdetails',(int)$_GET['LocationId'],'LocationId');
  if(!$locations){
    $session->msg("d","Missing category id.");
    redirect('product_location.php');
  }
?>

<?php
if(isset($_POST['edit_loc'])){
  $req_field = array('prodlocation-name');
  validate_fields($req_field);
  $loc_name = remove_junk($db->escape($_POST['prodlocation-name']));
  
  if(empty($errors)){
        $sql = "UPDATE locationdetails SET LocationName='{$loc_name}'";
       $sql .= " WHERE LocationId='{$locations['LocationId']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Location");
       redirect('product_location.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
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
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editing <?php echo remove_junk(ucfirst($locations['LocationName']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_product_location.php?LocationId=<?php echo (int)$locations['LocationId'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="prodlocation-name" value="<?php echo remove_junk(ucfirst($locations['LocationName']));?>">
           </div>
           <button type="submit" name="edit_loc" class="btn btn-primary">Update Location</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
