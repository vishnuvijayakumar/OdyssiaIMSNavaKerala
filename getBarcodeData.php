<?php 

$page_title = 'Pagination Details2';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
 page_require_level(2);
 $userids1 = current_user();
	$userid1 = $userids1['id'];

if(isset($_POST['page'])){ 
    // Include pagination library file 
    include_once 'Pagination.class.php'; 
     
    // Set some useful configuration 
    $baseURL = 'getBarcodeData.php'; 
    $offset = !empty($_POST['page'])?$_POST['page']:0; 
    $limit = 10; 
     
    // Set conditions for search 
    $whereSQL = ''; 
    if(!empty($_POST['keywords'])){ 
        $whereSQL = " WHERE (Fullbarcode LIKE '%".$_POST['keywords']."%' OR ItemName LIKE '%".$_POST['keywords']."%' 
        OR LocationName LIKE '%".$_POST['keywords']."%') "; 
    } 
    //if(isset($_POST['filterBy']) && $_POST['filterBy'] != ''){ 
        //$whereSQL .= (strpos($whereSQL, 'WHERE') !== false)?" AND ":" WHERE "; 
       // $whereSQL .= " status = ".$_POST['filterBy']; 
   // } 
     
    // Count of all records 
    //$query   = $db->query("SELECT COUNT(*) as rowNum FROM users ".$whereSQL); 
    $Stockscount = join_barcodegen_table_count($whereSQL);
    $result  = $Stockscount->fetch_assoc(); 
    $rowCount= $result['rowNum']; 
     
    // Initialize pagination class 
    $pagConfig = array( 
        'baseURL' => $baseURL, 
        'totalRows' => $rowCount, 
        'perPage' => $limit, 
        'currentPage' => $offset, 
        'contentDiv' => 'dataContainer', 
        'link_func' => 'searchFilter1' 
    ); 
    $pagination =  new Pagination($pagConfig); 
 
    // Fetch records based on the offset and limit 
    //$query = $db->query("SELECT * FROM users $whereSQL ORDER BY id DESC LIMIT $offset,$limit"); 
    $query = join_barcodegen_table_pagination($whereSQL,$offset,$limit);
?> 
    <!-- Data list container --> 
    <table class="table table-striped"> 
    <thead> 
        <tr>  
        <th scope="col" class="text-center" style="width: 50px;">#</th>
                    <th scope="col">Barcodes</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Quantity Available</th>
                    <th scope="col">UOM</th>
                    <th scope="col">Location</th>
                    <th scope="col" class="text-center" style="width: 100px;">Re-Generate</th> 
        </tr> 
    </thead> 
    <tbody> 
        <?php 
        if($query->num_rows > 0){ 
            while($bar = $query->fetch_assoc()){ 
                $offset++ 
        ?> 
            <tr> 
            <td scope="row" class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($bar['Fullbarcode'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($bar['ItemName'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($bar['Quantity'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($bar['UOM'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($bar['LocationName'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="Barcodegen.php?Gen_barcode=<?php echo (int)$bar['Barcode'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Generate">
                          <span class="glyphicon glyphicon-print"></span>
                        </a>
                      </div>
                    </td> 
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