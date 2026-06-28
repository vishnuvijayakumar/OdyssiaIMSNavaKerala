<?php
$page_title = 'Stock Audit Report';
$results = '';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php

  if(isset($_GET['print'])){
    //$req_dates = array('start-date','end-date');
   // validate_fields($req_dates);

    //if(empty($errors)):
  
      $results      = join_stockaudit_barcode_report();

	if(isset($_GET['excel'])) {

        $results1      = join_stockaudit_barcode_report_export();

        $tasks = array();
        while( $rows = mysqli_fetch_assoc($results1) ) {
        $tasks[] = $rows;
        }

        $filename = "IMS_StockAuditReport_export".date('Ymd') . ".xls";
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            ExportFile($tasks);
        //print_r($tasks);
        //echo "yes";die();

      }


   // else:
     // $session->msg("d", $errors);
     // redirect('datewise_report.php', false);
    //endif;

  } else {
    $session->msg("d", "Content is Null");
    redirect('stock_Audit_report.php.php', false);
  }
?>
<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Stock Audit Report Print</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
   
</head>
<body>
  <?php if($results): ?>
    <div class="page-break">
    <div class="row">
     <div class="col-md-12">
     <div class="panel panel-default">
       <div class="panel-heading text-center">
           <h4 class="text-center">Inventory Management System - Stock Audit Report</h4>
       </div>
       <div class="panel-body text-center">
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
    </div>
  <?php
    else:
        $session->msg("d", "Sorry no Audits has been found. ");
        redirect('stock_Audit_report.php', false);
     endif;
  ?>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>
