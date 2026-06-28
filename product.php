<?php
  $page_title = 'All Products';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $products = join_product_table();
  //print_r($products);die();
  
  if(isset($_GET['excel'])) {

    $results=join_product_table_excel();

    $tasks = array();
    while( $rows = mysqli_fetch_assoc($results) ) {
    $tasks[] = $rows;
    }

    $filename = "IMS_Product_export_".date('Ymd') . ".xls";
        header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    ExportFile($tasks);
    //print_r($tasks);
    //echo "yes";die();

  }

?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <div class="pull-right">
		   <a href="product.php?excel=1" class="btn btn-primary">Export</a>
           <a href="Product_Add.php" class="btn btn-primary">Add New</a>
         </div>
        </div>
        <div class="panel-body">
          <table id="producttable" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
              <th class="text-center" style="width: 10%;">#</th>
                <th class="text-center" style="width: 50px;">Item Code</th>
                <th class="text-center" style="width: 50px;"> Item Name </th>
                <th class="text-center" style="width: 10%;"> Category </th>
                <th class="text-center" style="width: 10%;"> Sub Category </th>
                <th class="text-center" style="width: 10%;"> Available Quantity </th>
                <th class="text-center" style="width: 10%;"> Product Value (₹) </th>
                <th class="text-center" style="width: 10%;"> Total Value (₹) </th>
                <th class="text-center" style="width: 10%;"> No. of Barcode with Stock </th>
                <th class="text-center" style="width: 10%;"> UOM </th>
                <!---<th class="text-center" style="width: 10%;"> Locations </th>--->
                <th class="text-center" style="width: 100px;"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <!--<td>
                  <?php //if($product['media_id'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                  <?php //else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php //echo $product['image']; ?>" alt="">
                <?php //endif; ?>
                </td>-->
                <td class="text-center"> <?php echo remove_junk($product['Itemcode']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['ItemName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['CategoryName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['SubCategoryName']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['AvlQty']); ?></td>
                <td class="text-center"> ₹ <?php echo number_format((float)$product['ProductValue'], 2); ?></td>
                <td class="text-center"> ₹ <?php echo number_format((float)$product['ProductValue'] * (float)$product['AvlQty'], 2); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['barcodecount']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['UOM']); ?></td>
                <!---<td class="text-center"> <?php echo remove_junk($product['Locations']); ?></td>--->
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?ProductId=<?php echo (int)$product['ProductId'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$product['ProductId'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
