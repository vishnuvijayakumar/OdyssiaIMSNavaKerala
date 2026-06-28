<?php 

$page_title = 'Pagination Details1';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
 page_require_level(2);

if(isset($_POST['page'])){ 
    // Include pagination library file 
    include_once 'Pagination.class.php'; 
     
    // Set some useful configuration 
    $baseURL = 'getData.php'; 
    $offset = !empty($_POST['page'])?$_POST['page']:0; 
    $limit = 5; 
     
    // Set conditions for search 
    $whereSQL = ''; 
    if(!empty($_POST['keywords'])){ 
        $whereSQL = " WHERE (name LIKE '%".$_POST['keywords']."%' OR username LIKE '%".$_POST['keywords']."%' OR id LIKE '%".$_POST['keywords']."%') "; 
    } 
    //if(isset($_POST['filterBy']) && $_POST['filterBy'] != ''){ 
        //$whereSQL .= (strpos($whereSQL, 'WHERE') !== false)?" AND ":" WHERE "; 
       // $whereSQL .= " status = ".$_POST['filterBy']; 
   // } 
     
    // Count of all records 
    $query   = $db->query("SELECT COUNT(*) as rowNum FROM users ".$whereSQL); 
    $result  = $query->fetch_assoc(); 
    $rowCount= $result['rowNum']; 
     
    // Initialize pagination class 
    $pagConfig = array( 
        'baseURL' => $baseURL, 
        'totalRows' => $rowCount, 
        'perPage' => $limit, 
        'currentPage' => $offset, 
        'contentDiv' => 'dataContainer', 
        'link_func' => 'searchFilter' 
    ); 
    $pagination =  new Pagination($pagConfig); 
 
    // Fetch records based on the offset and limit 
    $query = $db->query("SELECT * FROM users $whereSQL ORDER BY id DESC LIMIT $offset,$limit"); 
?> 
    <!-- Data list container --> 
    <table class="table table-striped"> 
    <thead> 
        <tr> 
            <th scope="col">#</th> 
            <th scope="col">id</th> 
            <th scope="col">Name</th> 
            <th scope="col">Username</th> 
            <th scope="col">Status</th> 
        </tr> 
    </thead> 
    <tbody> 
        <?php 
        if($query->num_rows > 0){ 
            while($row = $query->fetch_assoc()){ 
                $offset++ 
        ?> 
            <tr> 
                <th scope="row"><?php echo $offset; ?></th> 
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
<?php 
} 
?>