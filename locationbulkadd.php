<?php
  $page_title = 'Product Locations';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_prodlocations = find_all('locationdetails')
?>
<?php
 for($i=111;$i<=300;$i++){
  
   $loc_name = "SYL ".$i;
   $userid = current_user();
     $p_userid = $userid['id'];
  
      $sql  = "INSERT INTO locationdetails (LocationName,id)";
      $sql .= " VALUES ('{$loc_name}','{$p_userid}')";
      if($db->query($sql)){
        echo "Successfully Added New Category".$loc_name;
        //redirect('product_location.php',false);
      } else {
        echo "Sorry Failed to insert.".$loc_name;
        //redirect('product_location.php',false);
      }
   
 }
?>