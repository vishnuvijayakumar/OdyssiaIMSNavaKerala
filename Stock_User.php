<?php
  $page_title = 'Stock Details';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(4);
   $userid = current_user();
   $user_id= $userid['id'];
  $Stocks = join_stock_table_user($user_id);

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
           <a href="Stock_Add.php" class="btn btn-primary">Add New</a>
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered" id="stocktable">
            <thead>
              <tr>
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
                <!---<th class="text-center" style="width: 100px;"> Actions </th>---->
              </tr>
            </thead>
            <tbody>
              <?php foreach ($Stocks as $stock):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <!--<td>
                  <?php //if($product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                  <?php //else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php //echo $product['image']; ?>" alt="">
                <?php //endif; ?>
                </td>-->
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
                <!----<td class="text-center">
                  <div class="btn-group">
                    <a href="edit_stock.php?StockId=<?php //echo (int)$stock['StockId'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_stock.php?StockId=<?php //echo (int)$stock['StockId'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>---->
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>

        </div>
      </div>
    </div>
  </div>
  
  <?php include_once('layouts/footer.php'); ?>
  
