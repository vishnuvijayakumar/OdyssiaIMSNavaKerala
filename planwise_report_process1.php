<?php
$page_title = 'Plan/Chelan Wise Stock Report';
$results = '';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php

  if(isset($_GET['planno'])){
    //$req_dates = array('start-date','end-date');
   // validate_fields($req_dates);

    //if(empty($errors)):
      $planno = remove_junk($db->escape($_GET['planno']));
      
      $results = find_stock_by_plan($planno);
   // else:
     // $session->msg("d", $errors);
     // redirect('datewise_report.php', false);
    //endif;
    //print_r($results);die();

  } else {
    $session->msg("d", "Select Plan No or Chelan No");
    redirect('planwise_report_process.php', false);
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
           <h4 class="text-center">Inventory Management System - Stock Entry Report</h4>
           <span class="text-center"><strong> Plan No: <?php if(isset($planno)){echo $planno;}?> </strong></span>
       </div>
       <div class="panel-body text-center">
      <table class="table table-bordered ">
        <thead>
          <tr>
          <th class="text-center">#</th>
          <th class="text-center">Barcode</th>
          <th class="text-center"> Product </th>
          <!--<th class="text-center"> Category </th>
          <th class="text-center"> Sub Category </th>-->
          <th class="text-center"> Stock Type </th>
          <th class="text-center"> Quantity </th>
          <th class="text-center"> UOM </th>
          <th class="text-center"> Location </th>
          <th class="text-center"> Unit </th>
          <th class="text-center"> Plan No. </th>
          <th class="text-center"> Challan No. </th>
          <th class="text-center"> User </th>
          <!--<th class="text-center"> Entry Date </th>-->
          <th class="text-center"> Last Updated Date </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results as $stock): ?>
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
                <!--<td class="text-center"> <?php //echo remove_junk($stock['CategoryName']); ?></td>
                <td class="text-center"> <?php //echo remove_junk($stock['SubCategoryName']); ?></td>-->
                <td class="text-center"> <?php echo remove_junk($stock['StockType']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Quantity']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['UomSubType']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['LocationName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['UnitName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['PlanNo']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['ChallanName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Name']); ?></td>
                <!--<td class="text-center"> <?php //echo remove_junk($stock['Created_at']); ?></td>-->
                <td class="text-center"> <?php echo remove_junk($stock['Updated_at']); ?></td>
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
        redirect('planwise_report_process.php', false);
     endif;
  ?>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>
