<?php
  $page_title = 'Product Barcode Details';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
  //$Stocks = join_stock_table();

  if(isset($_POST['submit'])){
    $req_dates = array('product-name');
    validate_fields($req_dates);

    if(empty($errors)):
      if(isset($_POST['product-name']) && ($_POST['product-name']!=0)) {
        $product_name = remove_junk($db->escape($_POST['product-name']));
      } else {
        $product_name =0;
      }
      $results = find_barcode_product($product_name);
      //print_r($results);die;
    else:
      $session->msg("d", $errors);
      redirect('productwise_Barcode.php', false);
    endif;

  } else {
    $session->msg("d", "Select dates");
    redirect('productwise_Barcode.php', false);
  }

?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <?php if(isset($_POST['product-name'])) { ?>
      <div class="pull-right">
		   <a href="productwise_barcode_process.php?excel=1&product_name=<?php echo  $product_name; ?>" class="btn btn-primary">Export</a>
           <a href="productwise_barcode_process.php?product_name=<?php echo  $product_name; ?>" target="_blank" class="btn btn-primary">Print</a>
         </div>
         <?php } ?>
      <div class="panel-heading ">
         <h4 class="text-center">Product Barcode List</h4>
        </div>
        <div class="panel-body">
          <table class="table table-bordered" id="stocktable">
            <thead>
              <tr>
                <th class="text-center" style="width: 10%;">#</th>
                <th class="text-center" style="width: 50px;">Item Code</th>
                <th class="text-center" style="width: 50px;"> Item Name </th>
                <th class="text-center" style="width: 10%;"> Barcode </th>
                <th class="text-center" style="width: 100px;"> Total Available Stock </th>
                <th class="text-center" style="width: 100px;"> Location </th>
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
                <td class="text-center"> <?php echo remove_junk($stock['Itemcode']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['ItemName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Fullbarcode']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['Quantity']); ?></td>
                <td class="text-center"> <?php echo remove_junk($stock['LocationName']); ?></td>
                
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>

        </div>
      </div>
    </div>
  </div>
  
  <?php include_once('layouts/footer.php'); ?>
  
