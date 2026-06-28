<?php
$page_title = 'Plan / Chelan wise Report';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   $all_products = find_all('challandetails');

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">

      </div>
      <div class="panel-body">
      <form class="clearfix" method="post" action="Challanwise_report_process.php">
            <div class="form-group">
                <label class="form-label">Challan</label>
                <div class="input-group">
                <select class="form-control" id="itemcode" name="planno">
                      <option value="0">Select Challan</option>
                    <?php  foreach ($all_products as $prod): ?>
                      <option value="<?php echo $prod['ChallanId']; ?>" <?php if(isset($Stocks['ChallanId']) && ($Stocks['ChallanId']==$prod['ProductId'])){ echo "Selected";  }?>>
                        <?php echo $prod['ChallanName']  ?></option>
                    <?php endforeach; ?>
                    </select>
               </div>
            </div>
            <div class="form-group">
                 <button type="submit" name="submit" class="btn btn-primary">Generate Report</button>
            </div>
          </form>
      </div>

    </div>
  </div>

</div>
<?php include_once('layouts/footer.php'); ?>
