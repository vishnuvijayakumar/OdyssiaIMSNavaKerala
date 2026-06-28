<?php
  $page_title = 'Edit Unit';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  //Display all catgories.
  $categorie = find_by_id_new('unitdetails',(int)$_GET['UnitId'],'UnitId');
  if(!$categorie){
    $session->msg("d","Missing unit id.");
    redirect('units.php');
  }
?>

<?php
if(isset($_POST['edit_unit'])){
  $req_field = array('unit-name');
  validate_fields($req_field);
  $unit_name = remove_junk($db->escape($_POST['unit-name']));
  $unit_location = remove_junk($db->escape($_POST['unit-location']));
  
  if(empty($errors)){
        $sql = "UPDATE unitdetails SET UnitName='{$unit_name}', UnitLocation='{$unit_location}'";
       $sql .= " WHERE UnitId='{$categorie['UnitId']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Unit");
       redirect('units.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('units.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('units.php',false);
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
           <span>Editing <?php echo remove_junk(ucfirst($categorie['UnitName']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_units.php?UnitId=<?php echo (int)$categorie['UnitId'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="unit-name" value="<?php echo remove_junk(ucfirst($categorie['UnitName']));?>">
           </div>
           <button type="submit" name="edit_unit" class="btn btn-primary">Update unit</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
