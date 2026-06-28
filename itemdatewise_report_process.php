<?php
$page_title = 'Datewise Stock Report';
$results = '';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php

  if(isset($_GET['start-date'])&& isset($_GET['end-date'])){
    //$req_dates = array('start-date','end-date');
   // validate_fields($req_dates);

    //if(empty($errors)):
      $start_date   = remove_junk($db->escape($_GET['start-date']));
      $end_date     = remove_junk($db->escape($_GET['end-date']));
      $product_name = remove_junk($db->escape($_GET['product_name']));
      $results      = find_stock_by_dates_product($start_date,$end_date,$product_name);

	if(isset($_GET['excel'])) {

        $tasks = array();
        while( $rows = mysqli_fetch_assoc($results) ) {
        $tasks[] = $rows;
        }

        $filename = "IMS_DateReport_export".date('Ymd') . ".xls";
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
    $session->msg("d", "Select dates");
    redirect('datewise_report.php', false);
  }
?>
<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Datewise Report Print</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
   
</head>
<body>
  <?php if($results): ?>
    <div class="page-break">
    <div class="row">
     <div class="col-md-12">
     <div class="panel panel-default">
       <div class="panel-heading text-center">
           <h4 class="text-center">Inventory Management System - Datewise Stock Report</h4>
           <span class="text-center"><strong><?php if(isset($start_date)){ echo $start_date;}?> TILL DATE <?php if(isset($end_date)){echo $end_date;}?> </strong></span>
       </div>
       <div class="panel-body text-center">
      <table class="table table-bordered ">
        <thead>
          <tr>
                <th class="text-center" style="width: 10%;">#</th>
                <th class="text-center" style="width: 50px;">Stock Date</th>
                <th class="text-center" style="width: 50px;">Item Code</th>
                <th class="text-center" style="width: 50px;"> Item Name </th>
                <th class="text-center" style="width: 10%;"> Main Category </th>
                <th class="text-center" style="width: 10%;"> Total Inward Stock </th>
                <th class="text-center" style="width: 10%;"> Inward Stock </th>
                <th class="text-center" style="width: 10%;"> Excess In Stock </th>
                <th class="text-center" style="width: 10%;"> Total Outward Stock </th>
                <th class="text-center" style="width: 10%;"> Outward Stock </th>
                <th class="text-center" style="width: 10%;"> Additional Outward Stock </th>
                <th class="text-center" style="width: 10%;"> Clicking Out </th>
                <th class="text-center" style="width: 100px;"> Other Sale Out </th>
                <th class="text-center" style="width: 100px;"> Plan No. Out </th>
                <th class="text-center" style="width: 100px;"> Chelan No. Out </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results as $stock): ?>
           <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td class="text-center"> <?php echo remove_junk($stock['StockDate']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Itemcode']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['ItemName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['CategoryName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Total Inward Stock']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Inward stock']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Excess In stock']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Total Outward Stock']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Outward Stock']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Additional Outward Stock']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Clicking Out']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Other Sale Out']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Plan No Out']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Chelan No Out']); ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <!--<tfoot>
         <tr class="text-right">
           <td colspan="4"></td>
           <td colspan="1">Grand Total</td>
           <td> $
           <?php //echo number_format(total_price($results)[0], 2);?>
          </td>
         </tr>
         <tr class="text-right">
           <td colspan="4"></td>
           <td colspan="1">Profit</td>
           <td> $<?php //echo number_format(total_price($results)[1], 2);?></td>
         </tr>
        </tfoot>-->
      </table>

       </div>
      </div>
      </div>
    </div>
    </div>
  <?php
    else:
        $session->msg("d", "Sorry no sales has been found. ");
        redirect('itemdatewise_report.php', false);
     endif;
  ?>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>
