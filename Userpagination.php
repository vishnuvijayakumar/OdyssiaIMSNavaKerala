<?php
  $page_title = 'Pagination Details';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $Stocks = join_stock_table();
    $userids1 = current_user();
	$userid1 = $userids1['id'];

    // Include pagination library file 
include_once 'Pagination.class.php'; 
 
 
// Set some useful configuration 
$baseURL = 'getData.php'; 
$limit = 10; 
 
// Count of all records 
$query   = $db->query("SELECT COUNT(*) as rowNum FROM users"); 
$result  = $query->fetch_assoc(); 
$rowCount= $result['rowNum']; 
 
// Initialize pagination class 
$pagConfig = array( 
    'baseURL' => $baseURL, 
    'totalRows' => $rowCount, 
    'perPage' => $limit, 
    'contentDiv' => 'dataContainer', 
    'link_func' => 'searchFilter' 
); 
$pagination =  new Pagination($pagConfig); 
 
// Fetch records based on the limit 
$query = $db->query("SELECT * FROM users ORDER BY id DESC LIMIT $limit"); 

?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <div class="pull-right">
       
    
<div class="form-group col-md-6">
            <input type="text" class="form-control" id="keywords" placeholder="Type keywords..." onkeyup="searchFilter();">
        </div>
        <div class="form-group col-md-4">
            <a href="Stock_Add.php" class="btn btn-primary">Add New</a>
        </div>
           
         </div>
        </div>
        <div class="datalist-wrapper">
    <!-- Loading overlay -->
    <div class="loading-overlay" style="display:none;"><div class="overlay-content">Loading...</div></div>
    
    <!-- Data list container -->
    <div id="dataContainer">
        <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Username</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if($query->num_rows > 0){ $i=0; 
                while($row = $query->fetch_assoc()){ $i++; 
            ?>
                <tr>
                    <th scope="row"><?php echo $i; ?></th>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["name"]; ?></td>
                    <td><?php echo $row["username"]; ?></td>
                    
                    <td><?php echo ($row["status"] == 1)?'Active':'Inactive'; ?></td>
                </tr>
            <?php 
                } 
            }else{ 
                echo '<tr><td colspan="6">No records found...</td></tr>'; 
            } 
            ?>
        </tbody>
        </table>
        
        <!-- Display pagination links -->
        <?php echo $pagination->createLinks(); ?>
    </div>
</div>
      </div>
    </div>
  </div>
  
  <?php include_once('layouts/footer.php'); ?>
  
