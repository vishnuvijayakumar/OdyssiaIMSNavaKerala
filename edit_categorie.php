<?php
  $page_title = 'Edit category';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  //Display all catgories.
  $categorie = find_by_id_new('categorydetails',(int)$_GET['CategoryId'],'CategoryId');
  if(!$categorie){
    $session->msg("d","Missing category id.");
    redirect('category.php');
  }
?>

<?php
if(isset($_POST['edit_cat'])){
  $req_field = array('categorie-name');
  validate_fields($req_field);
  $cat_name = remove_junk($db->escape($_POST['categorie-name']));
  
  if(empty($errors)){
        $sql = "UPDATE categorydetails SET CategoryName='{$cat_name}'";
       $sql .= " WHERE CategoryId='{$categorie['CategoryId']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Categorie");
       redirect('category.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('category.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('category.php',false);
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
           <span>Editing <?php echo remove_junk(ucfirst($categorie['CategoryName']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_categorie.php?CategoryId=<?php echo (int)$categorie['CategoryId'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="categorie-name" value="<?php echo remove_junk(ucfirst($categorie['CategoryName']));?>">
           </div>
           <button type="submit" name="edit_cat" class="btn btn-primary">Update categorie</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
