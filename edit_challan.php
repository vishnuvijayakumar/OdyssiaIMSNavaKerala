<?php
  $page_title = 'Edit Challan';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  //Display all catgories.
  //if(isset($_GET['BatchId'])){
  $categorie = find_by_id_new('challandetails',(int)$_GET['ChallanId'],'ChallanId');
  if(!$categorie){
    $session->msg("d","Missing Challan id.");
    redirect('Challan.php');
  }
 // }
?>

<?php
if(isset($_POST['edit_cat'])){
  $req_field = array('challan-name','reqqty');
  validate_fields($req_field);
  $Challan_name = remove_junk($db->escape($_POST['challan-name']));
  $Challan_qty = remove_junk($db->escape($_POST['reqqty']));
  
  if(empty($errors)){
        $sql = "UPDATE challandetails SET ChallanName='{$Challan_name}', RequiredQty='{$Challan_qty}'";
       $sql .= " WHERE ChallanId='{$categorie['ChallanId']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Challan");
       redirect('Challan.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
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
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editing <?php echo remove_junk(ucfirst($categorie['ChallanName']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_challan.php?ChallanId=<?php echo (int)$categorie['ChallanId'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="challan-name" value="<?php echo remove_junk(ucfirst($categorie['ChallanName']));?>">
           </div>
           <div class="form-group">
               <input type="number" class="form-control" name="reqqty" value="<?php echo remove_junk(ucfirst($categorie['RequiredQty']));?>">
           </div>
           <button type="submit" name="edit_cat" class="btn btn-primary">Update Challan</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
