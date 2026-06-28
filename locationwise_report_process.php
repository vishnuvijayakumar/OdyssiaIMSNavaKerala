<?php
$page_title = 'Location Barcode List';
$results = '';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php

  if(isset($_GET['product_name'])){
    //$req_dates = array('start-date','end-date');
   // validate_fields($req_dates);

    //if(empty($errors)):
      if(isset($_GET['product_name']) && ($_GET['product_name']!=0)) {
        $product_name = remove_junk($db->escape($_GET['product_name']));
      } else {
        $product_name = 0;
      }
      $results = find_locationwise_product($product_name);
     
	  
	  if(isset($_GET['excel'])) {

        $tasks = array();
        while( $rows = mysqli_fetch_assoc($results) ) {
        $tasks[] = $rows;
        }

        $filename = "IMS_LocationBarcode_export_".date('Ymd') . ".xls";
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
    redirect('locationwise_report.php', false);
  }
?>
<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Location Barcodes Print</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
   
</head>
<body>
  <?php if($results): ?>
    <div class="page-break">
    <div class="row">
     <div class="col-md-12">
     <div class="panel panel-default">
       <div class="panel-heading text-center">
           <h4 class="text-center">Inventory Management System - Location Barcode List</h4>
           <span class="text-center"><strong> Overall Barcode for a Location </strong></span>
       </div>
       <div class="panel-body text-center">
      <table class="table table-bordered ">
        <thead>
          <tr>
                <th class="text-center" style="width: 10%;">#</th>
                <th class="text-center" style="width: 50px;">Location</th>
                <th class="text-center" style="width: 50px;">Item Code</th>
                <th class="text-center" style="width: 50px;"> Item Name </th>
                <th class="text-center" style="width: 10%;"> Barcode </th>
                <th class="text-center" style="width: 100px;"> Total Available Stock </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results as $stock): ?>
           <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td class="text-center"> <?php echo remove_junk($stock['LocationName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Itemcode']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['ItemName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Fullbarcode']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Quantity']); ?></td>
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
        $session->msg("d", "Sorry no barcodes has been found. ");
        redirect('locationwise_report.php', false);
     endif;
  ?>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>
