<?php
  $page_title = 'All Barcodes';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  //$all_barcodes = find_all('barcodedetails');
  $all_barcodes =join_barcodegen_table();
  $barcodeuique=0;
  
  //echo __DIR__; die();
?>
<?php

if(isset($_GET['Gen_barcode'])){  

    $regenbarcode = rtrim($_GET['Gen_barcode'], ',');
	
	$last_bar=find_by_id_custom('barcodedetails',$regenbarcode,'Barcode');
    //print_r($last_bar);die();
    if($last_bar) {
        $barcodeuique=$last_bar['Fullbarcode'];
    }
//echo $barcodeuique;die();
    $regenbarcode=$barcodeuique;
	
    //$barcodefile=getenv("HOMEDRIVE").getenv("HOMEPATH")."/Desktop"."/Generated_barcode.txt";
    $barcodefile='D:\IMSBarcode\barcodes.txt'; 
    file_put_contents($barcodefile, $regenbarcode);

    $session->msg("s", "Successfully Re-Generated...");
        redirect('Barcodegen.php',false);

}

 if(isset($_POST['add_barcode'])){  
   $req_field = array('barcode-type','barcode-count');
   validate_fields($req_field);
   $barcode_type = remove_junk($db->escape($_POST['barcode-type']));
   $barcode_count = remove_junk($db->escape($_POST['barcode-count']));
  
   if(empty($errors)){

    $content='';

    for($i=0;$i<(int)$barcode_count;$i++) {

        $k=$i+1;

    $last_top_id=find_by_id_max('barcodedetails','barcode');
	
    if($last_top_id['lastbarcode']=='') {
        $barcodeuique=1001;
    } else {
        $barcodeuique=$last_top_id['lastbarcode']+1;
    }

    $fullbarcode=$barcode_type.'_'.$barcodeuique;
      $sql  = "INSERT INTO barcodedetails (Barcode,BarcodeType,FullBarcode)";
      $sql .= " VALUES ('{$barcodeuique}','{$barcode_type}','{$fullbarcode}')";
      if($db->query($sql)){
        $content.=$fullbarcode."\r\n";
        $session->msg("s", "$k Barcodes Successfully Generated");
      } else {
        $session->msg("d", "$k Barcode Generated but Failed to Generate remaining.");
        redirect('Barcodegen.php',false);
      }

    }

    $content1 = trim($content, ",");
    //$barcodefile=getenv("HOMEDRIVE").getenv("HOMEPATH")."/Desktop"."/Generated_barcode.txt";
    $barcodefile='D:\IMSBarcode\barcodes.txt';
    file_put_contents($barcodefile, $content1);

    $session->msg("s", "Successfully Generated All Barcodes");
        redirect('Barcodegen.php',false);
 

   } else {
     $session->msg("d", $errors);
     redirect('Barcodegen.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>

  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
   <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Generate Barcodes</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="Barcodegen.php">
            <div class="form-group">
                <!--<select type="text" class="form-control" name="categorie-id" placeholder="Category Id">
                <option value="">Select Product Category</option>
                <?php //foreach ($all_categories as $cat):?>
                    <option value="<?php //echo remove_junk(ucfirst($cat['CategoryId'])); ?>"><?php //echo remove_junk(ucfirst($cat['CategoryName'])); ?></option>
                <?php //endforeach; ?>    
                </select> -->
                <select type="text" class="form-control" name="barcode-type" placeholder="Barcode Type">
                <option value="">Select Barcode Type</option>
                <option value="Rexin">Rexin</option>
                <option value="Comp">Components</option>
                <option value="Upper">Upper</option>
                <option value="Spair">Spairs</option>
                </select>

                <input type="text" class="form-control" name="barcode-count" placeholder="Needed Count of Barcode">
            </div>
            <button type="submit" name="add_barcode" class="btn btn-primary">Generate</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Barcodes</span>
       </strong>
      </div>
        <div class="panel-body">
          <table id="barcodetable" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Barcodes</th>
                    <th>Item Name</th>
                    <th>Quantity Available</th>
                    <th>UOM</th>
                    <th>Location</th>
                    <th class="text-center" style="width: 100px;">Re-Generate</th> 
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_barcodes as $bar):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($bar['Fullbarcode'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($bar['ItemName'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($bar['Quantity'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($bar['UOM'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($bar['LocationName'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="Barcodegen.php?Gen_barcode=<?php echo (int)$bar['Barcode'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Generate">
                          <span class="glyphicon glyphicon-print"></span>
                        </a>
                      </div>
                    </td> 

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div>
    </div>
    </div>
   </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
