<?php
$page_title = 'Products Barcodes';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   $all_products = find_all('productdetails');
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
          <form class="clearfix" method="post" action="productwise_barcode_process1.php">
            <div class="form-group">
                <label class="form-label">Product</label>
                <div class="input-group">
                <select class="form-control" id="itemcode" name="product-name">
                      <option value="0">Select Product</option>
                    <?php  foreach ($all_products as $prod): ?>
                      <option value="<?php echo $prod['ProductId']; ?>" <?php if(isset($Stocks['ProductId']) && ($Stocks['ProductId']==$prod['ProductId'])){ echo "Selected";  }?>>
                        <?php echo $prod['ItemName'] ?></option>
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
