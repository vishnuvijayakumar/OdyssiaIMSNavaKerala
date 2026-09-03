<?php
$page_title = 'Slow Moving Products';
require_once('includes/load.php');
page_require_level(3);
include_once 'Pagination.class.php';

$end_date = isset($_GET['end-date']) ? date('Y-m-d', strtotime($_GET['end-date'])) : date('Y-m-d');
$start_date = isset($_GET['start-date']) ? date('Y-m-d', strtotime($_GET['start-date'])) : date('Y-m-d', strtotime('-90 days'));
$limit = 20;
$offset = 0;
foreach (array_keys($_GET) as $query_key) {
  if (ctype_digit((string)$query_key)) {
    $offset = max(0, (int)$query_key);
    break;
  }
}
$products = array();
$total_rows = 0;

if ($start_date <= $end_date) {
  $products = find_slow_moving_products($start_date, $end_date, $offset, $limit);
  $count_result = $db->query('SELECT FOUND_ROWS() AS totalRows');
  $total_rows = (int)$db->fetch_assoc($count_result)['totalRows'];
}

$pagination = new Pagination(array(
  'baseURL' => 'slow_moving_products.php?start-date=' . urlencode($start_date) . '&end-date=' . urlencode($end_date),
  'totalRows' => $total_rows,
  'perPage' => $limit,
  'currentPage' => $offset
));
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-stats"></span>
          <span>Slow Moving Products</span>
        </strong>
      </div>
      <div class="panel-body">
        <form class="clearfix" method="get" action="slow_moving_products.php">
          <div class="form-group col-md-5">
            <label for="start-date">From</label>
            <input type="date" class="form-control" id="start-date" name="start-date" value="<?php echo $start_date; ?>" required>
          </div>
          <div class="form-group col-md-5">
            <label for="end-date">To</label>
            <input type="date" class="form-control" id="end-date" name="end-date" value="<?php echo $end_date; ?>" required>
          </div>
          <div class="form-group col-md-2">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary form-control">Generate Report</button>
          </div>
        </form>
        <?php if ($start_date > $end_date): ?>
          <div class="alert alert-danger">The start date must be before or equal to the end date.</div>
        <?php else: ?>
          <p>Products currently in stock, ordered by outward quantity from <?php echo $start_date; ?> to <?php echo $end_date; ?>.</p>
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th>Item Code</th>
                  <th>Item Name</th>
                  <th>Category</th>
                  <th class="text-right">Current Stock</th>
                  <th class="text-right">Outward Quantity</th>
                  <th>Last Movement</th>
                </tr>
              </thead>
              <tbody>
                <?php $row_number = 1; while ($product = $db->fetch_assoc($products)): ?>
                  <tr>
                    <td class="text-center"><?php echo $row_number++; ?></td>
                    <td><?php echo remove_junk($product['Itemcode']); ?></td>
                    <td><?php echo remove_junk($product['ItemName']); ?></td>
                    <td><?php echo remove_junk($product['CategoryName']); ?></td>
                    <td class="text-right"><?php echo number_format((float)$product['CurrentStock'], 2); ?></td>
                    <td class="text-right"><?php echo number_format((float)$product['OutwardQty'], 2); ?></td>
                    <td><?php echo $product['LastMovement'] ? remove_junk($product['LastMovement']) : 'No outward movement'; ?></td>
                  </tr>
                <?php endwhile; ?>
                <?php if ($row_number === 1): ?>
                  <tr><td colspan="7" class="text-center">No products found for this report.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <?php echo $pagination->createLinks(); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>