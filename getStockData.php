<?php 

$page_title = 'Pagination Details1';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
 page_require_level(2);
 $userids1 = current_user();
	$userid1 = $userids1['id'];

if(isset($_POST['page'])){ 
    // Include pagination library file 
    include_once 'Pagination.class.php'; 
     
    // Set some useful configuration 
    $baseURL = 'getStockData.php'; 
    $offset = !empty($_POST['page'])?$_POST['page']:0; 
    $limit = 10; 
     
    // Set conditions for search 
    $whereSQL = ''; 
    if(!empty($_POST['keywords'])){ 
        $whereSQL = " WHERE (Fullbarcode LIKE '%".$_POST['keywords']."%' OR ItemName LIKE '%".$_POST['keywords']."%' OR StockType LIKE '%".$_POST['keywords']."%' 
        OR LocationName LIKE '%".$_POST['keywords']."%' OR PlanNo LIKE '%".$_POST['keywords']."%') "; 
    } 
    //if(isset($_POST['filterBy']) && $_POST['filterBy'] != ''){ 
        //$whereSQL .= (strpos($whereSQL, 'WHERE') !== false)?" AND ":" WHERE "; 
       // $whereSQL .= " status = ".$_POST['filterBy']; 
   // } 
     
    // Count of all records 
    //$query   = $db->query("SELECT COUNT(*) as rowNum FROM users ".$whereSQL); 
    $Stockscount = join_stock_table_count($whereSQL);
    $result  = $Stockscount->fetch_assoc(); 
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
    //$query = $db->query("SELECT * FROM users $whereSQL ORDER BY id DESC LIMIT $offset,$limit"); 
    $query = join_stock_table_pagination($whereSQL,$offset,$limit);
?> 
    <!-- Data list container --> 
    <table class="table table-striped"> 
    <thead> 
        <tr>  
            <th scope="col">#</th>
                <th scope="col" style="width: 50px;">Barcode</th>
                <th scope="col" style="width: 50px;">Batch</th>
                <th scope="col" style="width: 50px;"> Product </th>
                <th scope="col" style="width: 10%;"> Category </th>
                <th scope="col" style="width: 10%;"> Sub Category </th>
                <th scope="col" style="width: 10%;"> Stock Type </th>
                <th scope="col" style="width: 10%;"> Quantity </th>
                <th scope="col" style="width: 10%;"> UOM </th>
                <th scope="col" style="width: 10%;"> Location </th>
                <th scope="col" style="width: 10%;"> Unit </th>
                <th scope="col" style="width: 10%;"> Plan No. </th>
                <th scope="col" style="width: 10%;"> Challan No. </th>
                <th scope="col" style="width: 10%;"> Entry Date </th>
				<?php if($userid1==3) { ?>
                <th class="text-center" style="width: 100px;"> Actions </th>
				<?php } else { ?>
          	<th class="text-center" style="width: 100px;"> Edit Location </th>
          	<?php } ?>
        </tr> 
    </thead> 
    <tbody> 
        <?php 
        if($query->num_rows > 0){ 
            while($stock = $query->fetch_assoc()){ 
                $offset++ 
        ?> 
            <tr> 
                <th scope="row"><?php echo count_id();?></th> 
                <td class="text-center"> <?php echo remove_junk($stock['Fullbarcode']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['BatchShortName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['ItemName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['CategoryName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['SubCategoryName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['StockType']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Quantity']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['UomSubType']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['LocationName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['UnitName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['PlanNo']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['ChallanName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Created_at']); ?></td>
				<?php if($userid1==3) { ?>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_stock.php?StockId=<?php echo (int)$stock['StockId'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_stock.php?StockId=<?php echo (int)$stock['StockId'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
				<?php } else { ?>

          	<td class="text-center">
                  <div class="btn-group">
                    <a href="edit_stock_location.php?StockId=<?php echo (int)$stock['StockId'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                  </div>
                </td>
          
          <?php }?>
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