<?php
  $page_title = 'Stock Audit Report';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
  $results = join_stockaudit_barcode_report();

  if(isset($_POST['Clear'])){
    $clearaudits = clear_audit();

    $session->msg('s',"Successfully deleted the audit details  ");
       redirect('stock_audit_report.php', false);
  }

?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <?php if(Count($results)>0) { ?>
          <div class="pull-left">
          <form method='POST' Action='#' onSubmit="return confirm('Do you want to clear the audit details?') ">
          <input type='hidden' name ='Clear' value='1'/>
          <button class="btn btn-danger" type="submit" >Clear Audit</button>
        </form>
        </div>
      <div class="pull-right">
           <a href="stock_Audit_report_process.php?print=1&excel=1" class="btn btn-primary">Export</a>
           <a href="stock_Audit_report_process.php?print=1" target="_blank" class="btn btn-primary">Print</a>
         </div>
         <?php } ?>
      <div class="panel-heading ">
         <h4 class="text-center">Stock Audit Report</h4>
        </div>
        <div class="panel-body">
          <table class="table table-bordered" id="stocktable">
            <thead>
              <tr>
              <th class="text-center" style="width: 10%;">#</th>
                <th class="text-center" style="width: 50px;">Barcode</th>
                <th class="text-center" style="width: 50px;"> Product Code</th>
                <th class="text-center" style="width: 50px;"> Product </th>
                <th class="text-center" style="width: 10%;"> Audit Quantity </th>
                <th class="text-center" style="width: 10%;"> System Quantity </th>
                <th class="text-center" style="width: 10%;"> User </th>
                <th class="text-center" style="width: 10%;"> Created Date </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $stock):?>
              <tr <?php if(remove_junk($stock['Audit_Quantity']) != remove_junk($stock['System_Quantity'])) { ?> style="background-color:yellow" <?php } ?>>
                <td class="text-center"><?php echo count_id();?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Fullbarcode']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Itemcode']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['ItemName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Audit_Quantity']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['System_Quantity']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['name']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Created_at']); ?></td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>

        </div>
      </div>
    </div>
  </div>

  <?php include_once('layouts/footer.php'); ?>