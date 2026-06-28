<?php
  $page_title = 'Plan / Chelan wise Stock Details';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
  //$Stocks = join_stock_table();

  if(isset($_POST['submit'])){
    $req_dates = array('planno');
    validate_fields($req_dates);

    if(empty($errors)):
      $planno = remove_junk($db->escape($_POST['planno']));
      
      $results = batch_reports($planno);
      //print_r($results);die();

    else:
      $session->msg("d", $errors);
      redirect('planwise_report.php', false);
    endif;

    //print_r($results);die();


  } else {
    $session->msg("d", "Select Plan No or Chelan No");
    redirect('planwise_report.php', false);
  }

?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <?php if(isset($_POST['planno'])) { ?>
      <div class="pull-right">
           <a href="planwise_report_process1.php?planno=<?php echo $_POST['planno']; ?>" target="_blank" class="btn btn-primary">Print</a>
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
                <th class="text-center" style="width: 50px;">Item Code</th>
                <th class="text-center" style="width: 50px;"> Item Name </th>
                <th class="text-center" style="width: 10%;"> Batch </th>
                <th class="text-center" style="width: 10%;"> Barcode Count </th>
               
              </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $stock):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Itemcode']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['ItemName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['BatchName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['barcodes']); ?></td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>

        </div>
      </div>
    </div>
  </div>
  
  <?php include_once('layouts/footer.php'); ?>
  
