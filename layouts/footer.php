
<style>

footer {
  text-align: center;
  padding: 3px;
  background-color: #ec1c23;
  color: white;
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  z-index: 99;
  line-height: 65px;
  height: 65px;
}
</style>
<footer>
  <p>© 2026 Odyssia. All Rights Reserved.<br>
  <a href="mailto:hege@example.com">hege@example.com</a></p>
</footer>

     </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
    <script type="text/javascript" src="libs/js/jquery.min.js"></script>

  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>-->
  <script type="text/javascript" src="libs/js/bootstrap.min.js"></script>


  <!--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>-->
  <script type="text/javascript" src="libs/js/bootstrap-datepicker.min.js"></script>

  <script type="text/javascript" src="libs/js/functions.js"></script>

  <!--<script src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
    <!--<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>-->
    <script type="text/javascript" src="libs/js/jquery.dataTables.min.js"></script>

    <!--<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>-->
    <script type="text/javascript" src="libs/js/dataTables.bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

    <script type="text/JavaScript"> 
var foo;    
$(document).ready(function() {
  
    $('#stocktable').DataTable();
    $('#barcodetable').DataTable();
    $('#producttable').DataTable();
    $('#unittable').DataTable();
    $('#prodlocationtable').DataTable();
    $('#subcategorytable').DataTable();
    $('#categorytable').DataTable();
    $('#usertable').DataTable();
    $('#usergrouptable').DataTable();

    $("#itemcode").select2();

    var max_fields = 100;
    var wrapper = $(".container1");
    var add_button = $(".add_form_field");
    var add_button1 = $(".add_form_field_In");
    $("#barcode1").focus();
    

    var x = 1;
    $(add_button).click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;
            //$(wrapper).append('<div><input type="text" name="mytext[]"/><a href="#" class="delete">Delete</a></div>'); //add input box
            $(wrapper).append('<div class="form-group"><div class="row"><div class="col-md-3"><input type="text" id="barcode'+x+'" class="form-control fbarcode" name="fullbarcode[]" placeholder="Scan/Enter Barcode" required></div><div class="col-md-2"><input type="number" id="pqty'+x+'" class="form-control" name="product-qty[]" placeholder="Product Quantity" onClick="checkbarcode(barcode'+x+',pqty'+x+',pavlqty'+x+',UomId'+x+')" step="any" required></div><div class="col-md-2" required><input type="text" id="pavlqty'+x+'" class="form-control" name="product-avl-qty[]" placeholder="Product Available Quantity" readonly="true"></div><div class="col-md-2"><select class="form-control uomcheck" id="UomId'+x+'" name="UOM[]" required><option value="">Select UOM</option></select></div><a href="#" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></a></div></div>');
            $("#barcode"+x).focus();
          } else {
            alert('You Reached the limits')
        }
    });

    $(add_button1).click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;
            //$(wrapper).append('<div><input type="text" name="mytext[]"/><a href="#" class="delete">Delete</a></div>'); //add input box
            $(wrapper).append('<div class="form-group"><div class="row"><div class="col-md-3"><input type="text" id="barcode'+x+'" class="form-control fbarcode" name="fullbarcode[]" placeholder="Scan/Enter Barcode" required></div><div class="col-md-2"><input type="number" id="pqty'+x+'" class="form-control" name="product-qty[]" placeholder="Product Quantity" step="any" required></div><a href="#" class="btn btn-danger delete"><span class="glyphicon glyphicon-remove"></span></a></div></div>');
            $("#barcode"+x).focus();
          } else {
            alert('You Reached the limits')
        }
    });

    $(wrapper).on("click", ".delete", function(e) {
        e.preventDefault();
        //var barcs=$(this).closest('.row').find('.fbarcode')[0]['value'];
        //console.log(barcs);
        //console.log(barcvalues);
        
        //const index = barcvalues.indexOf(barcs);
        //if (index > -1) { 
          //barcvalues.splice(index, 1); 
       // }
        //console.log(barcvalues); 
        $(this).parent('.row').remove();
        //x--;
    })

} );
  </script>

<script>
function searchFilter(page_num) {
    page_num = page_num?page_num:0;
    var keywords = $('#keywords').val();
    var filterBy = $('#filterBy').val();
    $.ajax({
        type: 'POST',
        url: 'getStockData.php',
        data:'page='+page_num+'&keywords='+keywords+'&filterBy='+filterBy,
        beforeSend: function () {
            $('.loading-overlay').show();
        },
        success: function (html) {
            $('#dataContainer').html(html);
            $('.loading-overlay').fadeOut("slow");
        }
    });
}
</script>

<script>
function searchFilter1(page_num) {
    page_num = page_num?page_num:0;
    var keywords = $('#keywords').val();
    var filterBy = $('#filterBy').val();
    $.ajax({
        type: 'POST',
        url: 'getBarcodeData.php',
        data:'page='+page_num+'&keywords='+keywords+'&filterBy='+filterBy,
        beforeSend: function () {
            $('.loading-overlay').show();
        },
        success: function (html) {
            $('#dataContainer').html(html);
            $('.loading-overlay').fadeOut("slow");
        }
    });
}
</script>

  </body>
</html>

<?php if(isset($db)) { $db->db_disconnect(); } ?>
