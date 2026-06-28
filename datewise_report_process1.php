<?php
  $page_title = 'Stock Details';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
  //$Stocks = join_stock_table();

  if(isset($_POST['submit'])){
    $req_dates = array('start-date','end-date');
    validate_fields($req_dates);

    if(empty($errors)):
      $start_date   = remove_junk($db->escape($_POST['start-date']));
      $end_date     = remove_junk($db->escape($_POST['end-date']));
      $results      = find_stock_by_dates($start_date,$end_date);
    else:
      $session->msg("d", $errors);
      redirect('datewise_report.php', false);
    endif;

  } else {
    $session->msg("d", "Select dates");
    redirect('datewise_report.php', false);
  }

?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <?php if(isset($_POST['start-date'])&&isset($_POST['end-date'])) { ?>
      <div class="pull-right">
           <a href="datewise_report_process.php?start-date=<?php echo $_POST['start-date']; ?>&end-date=<?php echo  $_POST['end-date']; ?>" target="_blank" class="btn btn-primary">Print</a>
         </div>
         <?php } ?>
      <div class="panel-heading ">
         <h4 class="text-center">Stock Entry Report</h4>
        </div>
        <div class="panel-body">
          <table class="table table-bordered" id="stocktable">
            <thead>
              <tr>
              <th class="text-center" style="width: 10%;">#</th>
                <th class="text-center" style="width: 50px;">Barcode</th>
                <th class="text-center" style="width: 50px;"> Product Code</th>
                <th class="text-center" style="width: 50px;"> Product </th>
                <th class="text-center" style="width: 10%;"> Category </th>
                <th class="text-center" style="width: 10%;"> Sub Category </th>
                <th class="text-center" style="width: 10%;"> Stock Type </th>
                <th class="text-center" style="width: 10%;"> Quantity </th>
                <th class="text-center" style="width: 10%;"> UOM </th>
                <th class="text-center" style="width: 10%;"> Location </th>
                <th class="text-center" style="width: 10%;"> Unit </th>
                <th class="text-center" style="width: 10%;"> Plan No. </th>
                <th class="text-center" style="width: 10%;"> Challan No. </th>
                <th class="text-center" style="width: 100px;"> Entry Date </th>
                <th class="text-center" style="width: 100px;"> Last Updated Date </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $stock):?>
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
                <td class="text-center"> <?php echo remove_junk($stock['Itemcode']); ?></td>
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
                <td class="text-center"> <?php echo remove_junk($stock['Updated_at']); ?></td>
                
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>

        </div>
      </div>
    </div>
  </div>
  
  <?php include_once('layouts/footer.php'); ?>
  
