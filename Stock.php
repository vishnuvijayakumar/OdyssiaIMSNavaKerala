<?php
ini_set('memory_limit', '1024M');
  $page_title = 'Stock Details';
  require_once('includes/load.php');
  ini_set('memory_limit', '-1');
  // Checkin What level user has permission to view this page
   page_require_level(4);
  $Stocks = join_stock_table();
    $userids1 = current_user();
	$userid1 = $userids1['id'];

 include_once 'Pagination.class.php'; 
 
 
// Set some useful configuration 
$baseURL = 'getBarcodeData.php'; 
$limit = 10; 
 
// Count of all records 
$Stockscount = join_stock_table_count($whereSQL);
$result  = $Stockscount->fetch_assoc();  
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
$query = join_stock_table_default($limit); 

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
            if($query->num_rows > 0){ $i=0; 
                while($stock = $query->fetch_assoc()){ $i++; 
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
    </div>
</div>
      </div>
    </div>
  </div>
  
  <?php include_once('layouts/footer.php'); ?>
