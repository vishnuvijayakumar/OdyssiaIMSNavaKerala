<?php
  $page_title = 'Stock Details';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $Stocks = join_stock_table();
    $userids1 = current_user();
	$userid1 = $userids1['id'];

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
         <div class="search-panel">
    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="text" class="form-control" id="keywords" placeholder="Type keywords..." onkeyup="searchFilter();">
        </div>
    </div>
</div>
           <a href="Stock_Add.php" class="btn btn-primary">Add New</a>
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered" id="stocktable">
            <thead>
              <tr>
              <th class="text-center" style="width: 10%;">#</th>
                <th class="text-center" style="width: 50px;">Barcode</th>
                <th class="text-center" style="width: 50px;"> Product </th>
                <th class="text-center" style="width: 10%;"> Category </th>
                <th class="text-center" style="width: 10%;"> Sub Category </th>
                <th class="text-center" style="width: 10%;"> Stock Type </th>
                <th class="text-center" style="width: 10%;"> Quantity </th>
                <th class="text-center" style="width: 10%;"> UOM </th>
                <th class="text-center" style="width: 10%;"> Location </th>
                <th class="text-center" style="width: 10%;"> Unit </th>
                <th class="text-center" style="width: 10%;"> Chelan/Plan No. </th>
		<th class="text-center" style="width: 10%;"> Entry Date </th>
				<?php if($userid1==3) { ?>
                <th class="text-center" style="width: 100px;"> Actions </th>
				<?php } else { ?>
          	<th class="text-center" style="width: 100px;"> Edit Location </th>
          	<?php } ?>
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
                <td class="text-center"> <?php echo remove_junk($stock['ItemName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['CategoryName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['SubCategoryName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['StockType']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Quantity']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['UomSubType']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['LocationName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['UnitName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['PlanNo']); ?></td>
		<td class="text-center"> <?php echo remove_junk($stock['Created_at']); ?></td>
				<?php if($userid1==3) { ?>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_stock.php?StockId=<?php //echo (int)$stock['StockId'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_stock.php?StockId=<?php //echo (int)$stock['StockId'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
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
             <?php endforeach; ?>
            </tbody>
          </tabel>

        </div>
      </div>
    </div>
  </div>
  
  <?php include_once('layouts/footer.php'); ?>
  
