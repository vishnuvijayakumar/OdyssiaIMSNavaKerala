<?php
  require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}
/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all_unique($table,$columnname) {
  global $db;
  if(tableExists($table))
  {
    return find_by_sql("SELECT DISTINCT $columnname FROM ".$db->escape($table));
  }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

/*--------------------------------------------------------------*/
/*  Function for Find data from table by Custom id
/*--------------------------------------------------------------*/
function find_by_id_custom($table,$id,$column)
{
  global $db;
  $id = (int)$id;
  
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE {$column}='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by dynamic id
/*--------------------------------------------------------------*/
function find_by_id_new($table,$id,$Idcolumn)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE {$db->escape($Idcolumn)}={$db->escape($id)} LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

/*--------------------------------------------------------------*/
/*  Function for Find data from table by dynamic id
/*--------------------------------------------------------------*/
function find_by_column($table,$value,$column)
{
  global $db;
  $value = (int)$value;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE {$db->escape($column)}={$db->escape($value)}");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}


/*--------------------------------------------------------------*/
/*  Function for Find data from table by dynamic string values
/*--------------------------------------------------------------*/
function find_by_anycolumn($table,$value,$column)
{
  global $db;
 
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE {$db->escape($column)}='{$value}'");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

/*--------------------------------------------------------------*/
/*  Function for Find data from table by max id
/*--------------------------------------------------------------*/
function find_by_id_max($table,$Idcolumn)
{
  global $db;
  //$id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT MAX($Idcolumn) as lastbarcode FROM {$db->escape($table)}");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}


/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by dynamic id
/*--------------------------------------------------------------*/
function delete_by_id_new($table,$id,$Idcolumn)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE $Idcolumn=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id_new($table,$column){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT($column) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Please login...');
            redirect('index.php', false);
      //if Group status Deactive
     elseif($login_level['group_status'] ??= '0'):
           $session->msg('d','This level user has been band!');
           redirect('home.php',false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "Sorry! you dont have permission to view the page.");
            redirect('home.php', false);
        endif;

     }
	
	   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and subcategorie
   /*--------------------------------------------------------------*/
  function join_category_table(){
     global $db;
     $sql  =" SELECT p.CategoryId,p.SubCategoryId,c.CategoryName,p.SubCategoryName,p.UOM";
    $sql  .=" FROM subcategorydetails p";
    $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = p.CategoryId";
    $sql  .=" ORDER BY p.SubCategoryId ASC";
    return find_by_sql($sql);
	
  }
	
   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
     global $db;
      $sql  =" SELECT distinct b.Barcode, p.ProductId,p.Itemcode,p.ItemName,p.ProductValue,p.CategoryId,p.SubCategoryId,c.CategoryName,m.SubCategoryName,
     IF(p.CategoryId!=6 ,SUM(IF(b.Quantity>0,b.Quantity,0)),IF(b.Quantity>0,b.Quantity,0)) as AvlQty,
     IF(p.CategoryId!=6,SUM(IF(b.Quantity>0,1,0)),IF(b.Quantity>0,1,0)) as barcodecount,b.UOM ,GROUP_CONCAT(l.LocationName) as Locations";
    $sql  .=" FROM productdetails p";
    $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = p.CategoryId";
    $sql  .=" LEFT JOIN subcategorydetails m ON m.SubCategoryId = p.SubCategoryId";
    $sql  .=" LEFT JOIN productreportview prv ON prv.ProductId = p.ProductId";
    $sql  .=" LEFT JOIN stockdetails s ON (s.ProductId = p.ProductId and s.StockType='IN - First Entry')";
    $sql  .=" LEFT JOIN barcodedetails b ON b.Barcode = s.Barcode";
    $sql  .=" LEFT JOIN locationdetails l on l.LocationId=s.LocationId";
    $sql  .=" GROUP BY p.ProductId";
    $sql  .=" ORDER BY p.ItemName ASC";
    return find_by_sql($sql);

   }
   
/*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
   function join_product_table_excel(){
    global $db;
    $sql  =" SELECT distinct p.ProductId,p.Itemcode,p.ItemName,p.ProductValue,p.CategoryId,p.SubCategoryId,c.CategoryName,m.SubCategoryName,
    IF(p.CategoryId!=6,SUM(IF(b.Quantity>0,b.Quantity,0)),IF(b.Quantity>0,b.Quantity,0)) as AvlQty,
     IF(p.CategoryId!=6,SUM(IF(b.Quantity>0,1,0)),IF(b.Quantity>0,1,0)) as barcodecount,b.UOM ,GROUP_CONCAT(l.LocationName) as Locations";
   $sql  .=" FROM productdetails p";
   $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = p.CategoryId";
   $sql  .=" LEFT JOIN subcategorydetails m ON m.SubCategoryId = p.SubCategoryId";
   $sql  .=" LEFT JOIN productreportview prv ON prv.ProductId = p.ProductId";
   $sql  .=" LEFT JOIN stockdetails s ON (s.ProductId = p.ProductId and s.StockType='IN - First Entry')";
   $sql  .=" LEFT JOIN barcodedetails b ON b.Barcode = s.Barcode";
   $sql  .=" LEFT JOIN locationdetails l on l.LocationId=s.LocationId";
   $sql  .=" GROUP BY p.ProductId";
   $sql  .=" ORDER BY p.ItemName ASC";
   return $db->query($sql);

  }

      /*--------------------------------------------------------------*/
   /* Function for Finding specific product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table_new($productid){
    global $db;
    $sql  =" SELECT p.ProductId,p.Itemcode,p.ItemName,p.CategoryId,p.SubCategoryId,c.CategoryName,m.SubCategoryName";
   $sql  .=" FROM productdetails p";
   $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = p.CategoryId";
   $sql  .=" LEFT JOIN subcategorydetails m ON m.SubCategoryId = p.SubCategoryId";
   $sql  .=" Where p.ProductId=".$productid;
   return find_by_sql($sql);

  }

        /*--------------------------------------------------------------*/
   /* Function for Finding specific sub category uom list
   /* JOIN with subcategorie  and uom database table
   /*--------------------------------------------------------------*/
   function join_subcategory_table($subcatid){
    global $db;
    $sql  =" SELECT s.SubCategoryId,s.SubCategoryName,s.UOM,u.UomId,u.UomSubType";
   $sql  .=" FROM subcategorydetails s";
   $sql  .=" LEFT JOIN uomdetails u ON u.UOMType = s.UOM";
   $sql  .=" Where s.SubCategoryId=".$subcatid;
   return find_by_sql($sql);

  }

         /*--------------------------------------------------------------*/
   /* Function for Finding specific sub category uom list
   /* JOIN with subcategorie  and uom database table
   /*--------------------------------------------------------------*/
   function category_subcategory_table($catid){
    global $db;
    $sql  =" SELECT * FROM subcategorydetails";
   $sql  .=" Where CategoryId=".$catid;
   return find_by_sql($sql);

  }
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
   $sql .= " GROUP BY s.product_id";
   $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date, p.name,p.sale_price,p.buy_price,";
  $sql .= "COUNT(s.product_id) AS total_records,";
  $sql .= "SUM(s.qty) AS total_sales,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY DATE(s.date),p.name";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function  dailySales($year,$month){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-{$month}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function  monthlySales($year){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y' ) = '{$year}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%c' ),s.product_id";
  $sql .= " ORDER BY date_format(s.date, '%c' ) ASC";
  return find_by_sql($sql);
}

   /*--------------------------------------------------------------*/
   /* Function for Finding all stock details
   /* JOIN with categorie,location,unit  and subcategory database table
   /*--------------------------------------------------------------*/
   function join_stock_table(){
    global $db;
   $sql   =" SELECT s.StockId,s.Barcode,s.ProductId,p.Itemcode,p.ItemName,s.CategoryId,s.SubCategoryId,c.CategoryName,m.SubCategoryName,s.StockType,s.Quantity,s.LocationId,l.LocationName,s.UnitId,u.UnitName,s.PlanNo,uom.UomId,uom.UomSubType,b.Fullbarcode,bt.BatchId,bt.BatchShortName,ch.ChallanId,ch.ChallanName,s.Created_at";
   $sql  .=" FROM stockdetails as s";
   $sql  .=" LEFT JOIN productdetails p ON p.ProductId= s.ProductId";
   $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = s.CategoryId";
   $sql  .=" LEFT JOIN subcategorydetails m ON m.SubCategoryId = s.SubCategoryId";
   $sql  .=" LEFT JOIN locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=" LEFT JOIN unitdetails u ON u.UnitId = s.UnitId";
   $sql  .=" LEFT JOIN uomdetails uom ON uom.UomId = s.UomId";
   $sql  .=" LEFT JOIN barcodedetails b ON b.Barcode = s.Barcode";
   $sql  .=" LEFT JOIN batchdetails bt ON bt.BatchId = s.BatchId";
   $sql  .=" LEFT JOIN challandetails ch ON ch.ChallanId = s.ChallanId";
   $sql  .=" ORDER BY s.StockId ASC";
   return find_by_sql($sql);

  }

  function join_stock_table_count($whereSQL){
    global $db;
   $sql   =" SELECT COUNT(*) as rowNum";
   $sql  .=" FROM stockdetails as s";
   $sql  .=" LEFT JOIN productdetails p ON p.ProductId= s.ProductId";
   $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = s.CategoryId";
   $sql  .=" LEFT JOIN subcategorydetails m ON m.SubCategoryId = s.SubCategoryId";
   $sql  .=" LEFT JOIN locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=" LEFT JOIN unitdetails u ON u.UnitId = s.UnitId";
   $sql  .=" LEFT JOIN uomdetails uom ON uom.UomId = s.UomId";
   $sql  .=" LEFT JOIN barcodedetails b ON b.Barcode = s.Barcode";
   $sql  .=$whereSQL;
   $sql  .=" ORDER BY s.StockId ASC";
   return $db->query($sql);

  }

  function join_stock_table_pagination($whereSQL,$offset,$limit){
    global $db;
   $sql   =" SELECT s.StockId,s.Barcode,s.ProductId,p.Itemcode,p.ItemName,s.CategoryId,s.SubCategoryId,c.CategoryName,m.SubCategoryName,s.StockType,s.Quantity,s.LocationId,l.LocationName,s.UnitId,u.UnitName,s.PlanNo,uom.UomId,uom.UomSubType,b.Fullbarcode,bt.BatchId,bt.BatchShortName,ch.ChallanId,ch.ChallanName,s.Created_at";
   $sql  .=" FROM stockdetails as s";
   $sql  .=" LEFT JOIN productdetails p ON p.ProductId= s.ProductId";
   $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = s.CategoryId";
   $sql  .=" LEFT JOIN subcategorydetails m ON m.SubCategoryId = s.SubCategoryId";
   $sql  .=" LEFT JOIN locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=" LEFT JOIN unitdetails u ON u.UnitId = s.UnitId";
   $sql  .=" LEFT JOIN uomdetails uom ON uom.UomId = s.UomId";
   $sql  .=" LEFT JOIN barcodedetails b ON b.Barcode = s.Barcode";
   $sql  .=" LEFT JOIN batchdetails bt ON bt.BatchId = s.BatchId";
   $sql  .=" LEFT JOIN challandetails ch ON ch.ChallanId = s.ChallanId";
   $sql  .=$whereSQL;
   $sql  .=" ORDER BY s.StockId ASC";
   $sql  .=" LIMIT $offset,$limit";
   return $db->query($sql);

  }

  function join_stock_table_default($limit){
    global $db;
   $sql   =" SELECT s.StockId,s.Barcode,s.ProductId,p.Itemcode,p.ItemName,s.CategoryId,s.SubCategoryId,c.CategoryName,m.SubCategoryName,s.StockType,s.Quantity,s.LocationId,l.LocationName,s.UnitId,u.UnitName,s.PlanNo,uom.UomId,uom.UomSubType,b.Fullbarcode,bt.BatchId,bt.BatchShortName,ch.ChallanId,ch.ChallanName,s.Created_at";
   $sql  .=" FROM stockdetails as s";
   $sql  .=" LEFT JOIN productdetails p ON p.ProductId= s.ProductId";
   $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = s.CategoryId";
   $sql  .=" LEFT JOIN subcategorydetails m ON m.SubCategoryId = s.SubCategoryId";
   $sql  .=" LEFT JOIN locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=" LEFT JOIN unitdetails u ON u.UnitId = s.UnitId";
   $sql  .=" LEFT JOIN uomdetails uom ON uom.UomId = s.UomId";
   $sql  .=" LEFT JOIN barcodedetails b ON b.Barcode = s.Barcode";
   $sql  .=" LEFT JOIN batchdetails bt ON bt.BatchId = s.BatchId";
   $sql  .=" LEFT JOIN challandetails ch ON ch.ChallanId = s.ChallanId";
   $sql  .=" ORDER BY s.StockId ASC";
   $sql  .=" LIMIT $limit";
   return $db->query($sql);

  }


     /*--------------------------------------------------------------*/
   /* Function for Finding all stock details
   /* JOIN with categorie,location,unit  and subcategory database table
   /*--------------------------------------------------------------*/
   function join_stock_table_user($userid){
    global $db;
   $sql   =" SELECT s.StockId,s.Barcode,s.ProductId,p.Itemcode,p.ItemName,s.CategoryId,s.SubCategoryId,c.CategoryName,m.SubCategoryName,s.StockType,s.Quantity,s.LocationId,l.LocationName,s.UnitId,u.UnitName,s.PlanNo,uom.UomId,uom.UomSubType,b.Fullbarcode,bt.BatchId,bt.BatchShortName,ch.ChallanId,ch.ChallanName,s.Created_at";
   $sql  .=" FROM stockdetails as s";
   $sql  .=" LEFT JOIN productdetails p ON p.ProductId= s.ProductId";
   $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = s.CategoryId";
   $sql  .=" LEFT JOIN subcategorydetails m ON m.SubCategoryId = s.SubCategoryId";
   $sql  .=" LEFT JOIN locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=" LEFT JOIN unitdetails u ON u.UnitId = s.UnitId";
   $sql  .=" LEFT JOIN uomdetails uom ON uom.UomId = s.UomId";
   $sql  .=" LEFT JOIN barcodedetails b ON b.Barcode = s.Barcode";
   $sql  .=" LEFT JOIN batchdetails bt ON bt.BatchId = s.BatchId";
   $sql  .=" LEFT JOIN challandetails ch ON ch.ChallanId = s.ChallanId";
   if($userid) {
    $sql  .=" WHERE s.id=".$userid;
   }
   $sql  .=" ORDER BY s.StockId ASC";
   return find_by_sql($sql);

  }

  /*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_stock_by_dates($start_date,$end_date){
  global $db;
  $start_date  = "'".date("Y-m-d", strtotime($start_date))."'";
  $end_date    = "'".date("Y-m-d", strtotime($end_date))."'";
  $sql   =" SELECT s.StockId,s.Barcode,s.ProductId,p.Itemcode,p.ItemName,s.CategoryId,s.SubCategoryId,c.CategoryName,m.SubCategoryName,s.StockType,s.Quantity,
  s.LocationId,l.LocationName,s.UnitId,u.UnitName,s.PlanNo,s.ChallanId,ch.ChallanName,uom.UomId,uom.UomSubType,b.Fullbarcode,s.Created_at,s.Updated_at,us.Name";
   $sql  .=" FROM stockdetails as s";
   $sql  .=" LEFT JOIN productdetails p ON p.ProductId= s.ProductId";
   $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = s.CategoryId";
   $sql  .=" LEFT JOIN subcategorydetails m ON m.SubCategoryId = s.SubCategoryId";
   $sql  .=" LEFT JOIN locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=" LEFT JOIN unitdetails u ON u.UnitId = s.UnitId";
   $sql  .=" LEFT JOIN uomdetails uom ON uom.UomId = s.UomId";
   $sql  .=" LEFT JOIN barcodedetails b ON b.Barcode = s.Barcode";
   $sql  .=" LEFT JOIN users us ON us.id = s.id";
   $sql  .=" LEFT JOIN challandetails ch on s.ChallanId=ch.ChallanId";
   $sql  .=" WHERE DATE(s.Created_at) BETWEEN ".$start_date." AND ".$end_date;
   $sql  .=" ORDER BY s.StockId ASC";
  return $db->query($sql);
}

  /*--------------------------------------------------------------*/
  
   /*-----------------------------------------New Features----------------------------------------------------*/
  
/* Function for Generate sales report by two dates & prodict
/*--------------------------------------------------------------*/
function find_stock_by_dates_product($start_date,$end_date,$product_name){
  global $db;
  $start_date  = "'".date("Y-m-d", strtotime($start_date))."'";
  $end_date    = "'".date("Y-m-d", strtotime($end_date))."'";
  $sql   =" SELECT * ";
   $sql  .=" FROM datewiseview as s";
   $sql  .=" WHERE DATE(s.StockDate) BETWEEN ".$start_date." AND ".$end_date;
   if($product_name!=0) {
    $sql  .=" AND s.ProductId=".$product_name;
    }
   $sql  .=" ORDER BY s.StockDate DESC";
   
   //echo $sql;die();
   
  return $db->query($sql);
}

  /*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates & prodict
/*--------------------------------------------------------------*/
function find_product_report($product_name){
  global $db;
  $sql   =" SELECT s.*, p.ProductValue ";
   $sql  .=" FROM productreportview as s";
   $sql  .=" LEFT JOIN productdetails p ON p.ProductId = s.ProductId";
   if($product_name!=0) {
    $sql  .=" WHERE s.ProductId=".$product_name;
    }
   $sql  .=" ORDER BY s.ItemName ASC";
  return $db->query($sql);

}

/*--------------------------------------------------------------*/
/* Function for Generate plan wise report by plan or chelan no
/*--------------------------------------------------------------*/
function find_stock_by_plan($planno){
  global $db;
  $planno  = "'".$planno."'";
  $sql   =" SELECT s.StockId,s.Barcode,s.ProductId,p.Itemcode,p.ItemName,s.CategoryId,s.SubCategoryId,c.CategoryName,m.SubCategoryName,s.StockType,s.Quantity,
  s.LocationId,l.LocationName,s.UnitId,u.UnitName,s.PlanNo,s.ChallanId,ch.ChallanName,uom.UomId,uom.UomSubType,b.Fullbarcode,s.Created_at,s.Updated_at,us.Name  ";
   $sql  .=" FROM stockdetails as s";
   $sql  .=" LEFT JOIN productdetails p ON p.ProductId= s.ProductId";
   $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = s.CategoryId";
   $sql  .=" LEFT JOIN categoryorderdetails co ON c.CategoryId = co.CategoryId";
   $sql  .=" LEFT JOIN subcategorydetails m ON m.SubCategoryId = s.SubCategoryId";
   $sql  .=" LEFT JOIN locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=" LEFT JOIN unitdetails u ON u.UnitId = s.UnitId";
   $sql  .=" LEFT JOIN uomdetails uom ON uom.UomId = s.UomId";
   $sql  .=" LEFT JOIN barcodedetails b ON b.Barcode = s.Barcode";
   $sql  .=" LEFT JOIN users us ON us.id = s.id";
   $sql  .=" LEFT JOIN challandetails ch on ch.ChallanId=s.ChallanId";
   $sql  .=" WHERE s.PlanNo =".$planno;
   $sql  .=" ORDER BY co.CategoryId DESC";

   //echo $sql;die();
  return $db->query($sql);
}

/*--------------------------------------------------------------*/
/* Function for Generate plan wise report by plan or chelan no
/*--------------------------------------------------------------*/
function find_stock_by_challan($planno){
  global $db;
  $planno  = "'".$planno."'";
  $sql   =" SELECT s.StockId,s.Barcode,s.ProductId,p.Itemcode,p.ItemName,s.CategoryId,s.SubCategoryId,c.CategoryName,m.SubCategoryName,s.StockType,s.Quantity,
  s.LocationId,l.LocationName,s.UnitId,u.UnitName,s.PlanNo,s.ChallanId,ch.ChallanName,uom.UomId,uom.UomSubType,b.Fullbarcode,s.Created_at,s.Updated_at,us.Name  ";
   $sql  .=" FROM stockdetails as s";
   $sql  .=" LEFT JOIN productdetails p ON p.ProductId= s.ProductId";
   $sql  .=" LEFT JOIN categorydetails c ON c.CategoryId = s.CategoryId";
   $sql  .=" LEFT JOIN subcategorydetails m ON m.SubCategoryId = s.SubCategoryId";
   $sql  .=" LEFT JOIN locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=" LEFT JOIN unitdetails u ON u.UnitId = s.UnitId";
   $sql  .=" LEFT JOIN uomdetails uom ON uom.UomId = s.UomId";
   $sql  .=" LEFT JOIN barcodedetails b ON b.Barcode = s.Barcode";
   $sql  .=" LEFT JOIN users us ON us.id = s.id";
   $sql  .=" LEFT JOIN challandetails ch on ch.ChallanId=s.ChallanId";
   $sql  .=" WHERE s.ChallanId =".$planno;
   $sql  .=" ORDER BY s.StockId ASC";

   //echo $sql;die();
  return $db->query($sql);
}



/*--------------------------------------------------------------*/
/* Function for checking product against barcode
/*--------------------------------------------------------------*/
function   check_barcode_entry($fullbarcode){
  global $db;
  $sql   =" SELECT *,a.Barcode as bbar from barcodedetails as a";
  $sql  .=" LEFT JOIN stockdetails as b on b.Barcode=a.Barcode";
  $sql  .=" WHERE a.fullbarcode='".$fullbarcode."' LIMIT 1";
  return $db->query($sql);
}

     /*--------------------------------------------------------------*/
   /* Function for Finding specific sub category uom list
   /* JOIN with subcategorie  and uom database table
   /*--------------------------------------------------------------*/
   function fullbarcode_fetch_details($fullbarcode){
    global $db;
    $sql   =" SELECT a.Fullbarcode,a.Quantity as avlqty,a.UOM as baseuom,b.* from barcodedetails as a";
   $sql  .=" LEFT JOIN stockdetails as b on b.Barcode=a.Barcode";
   $sql  .=" WHERE a.fullbarcode='".$fullbarcode."' LIMIT 1";
   return find_by_sql($sql);

  }

     /*--------------------------------------------------------------*/
   /* Function for Finding specific sub category uom list
   /* JOIN with subcategorie  and uom database table
   /*--------------------------------------------------------------*/
   function base_uom_check($baseuom){
    global $db;
    $sql  =" SELECT u.UomId,u.UomSubType,u.BaseUomFlag";
   $sql  .=" FROM uomdetails u";
   $sql  .=" Where u.UomType='".$baseuom."'";
   return find_by_sql($sql);

  }

       /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with barcode assigned and locations
   /*--------------------------------------------------------------*/
  function join_barcodegen_table(){
    global $db;
    $sql  =" SELECT b.Barcode,b.Fullbarcode,p.ItemName,b.Quantity,b.UOM,l.LocationName from  barcodedetails b";
   $sql  .="  left join stockdetails s ON s.Barcode=b.Barcode";
   $sql  .=" left join productdetails p ON p.ProductId=s.ProductId";
   $sql  .=" left join locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=" group by b.Barcode";
   $sql  .=" order by b.Barcode ASC";
   return find_by_sql($sql);

  }

  function join_barcodegen_table_count($whereSQL){
    global $db;
   $sql   =" SELECT COUNT(*) as rowNum from (";
   $sql  .=" SELECT b.Barcode as rowNum  from  barcodedetails b";
   $sql  .="  left join stockdetails s ON s.Barcode=b.Barcode";
   $sql  .=" left join productdetails p ON p.ProductId=s.ProductId";
   $sql  .=" left join locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=$whereSQL;
   $sql  .=" group by b.Barcode";
   $sql  .=" order by b.Barcode ASC) as ab";
   return $db->query($sql);

  }

  function join_barcodegen_table_default($limit){
    global $db;
    $sql  =" SELECT b.Barcode,b.Fullbarcode,p.ItemName,b.Quantity,b.UOM,l.LocationName from  barcodedetails b";
   $sql  .="  left join stockdetails s ON s.Barcode=b.Barcode";
   $sql  .=" left join productdetails p ON p.ProductId=s.ProductId";
   $sql  .=" left join locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=" group by b.Barcode";
   $sql  .=" order by b.Barcode ASC";
   $sql  .=" LIMIT $limit";
   return $db->query($sql);

  }

  function join_barcodegen_table_pagination($whereSQL,$offset,$limit){
    global $db;
    $sql  =" SELECT b.Barcode,b.Fullbarcode,p.ItemName,b.Quantity,b.UOM,l.LocationName from  barcodedetails b";
   $sql  .="  left join stockdetails s ON s.Barcode=b.Barcode";
   $sql  .=" left join productdetails p ON p.ProductId=s.ProductId";
   $sql  .=" left join locationdetails l ON l.LocationId = s.LocationId";
   $sql  .=$whereSQL;
   $sql  .=" group by b.Barcode";
   $sql  .=" order by b.Barcode ASC ";
   $sql  .=" LIMIT $offset,$limit";
   return $db->query($sql);

  }

         /*--------------------------------------------------------------*/
   /* Function for Finding all Audit stock with barcode wise
   /* JOIN with barcode,product and users
   /*--------------------------------------------------------------*/
   function join_stockaudit_barcode_report(){
    global $db;
    $sql  =" SELECT c.Id,c.Barcode,c.Quantity as Audit_Quantity,c.ProductId,c.userid,c.Created_at,b.Fullbarcode,b.Quantity as System_Quantity,p.ItemName,p.Itemcode,u.name";
    $sql  .=" FROM barcodedetails as b";
    $sql  .=" Left join stockdetails as s on b.Barcode = s.Barcode";
    $sql  .=" Left join productdetails as p on s.ProductId = p.ProductId";
    $sql  .=" Left join checkstockdetails as c ON (b.Barcode= c.Barcode and c.ProductId = p.ProductId)";
    $sql  .=" Left join users as u on c.userid = u.id";
    $sql  .=" WHERE p.ProductId IN (Select Distinct ProductId from checkstockdetails)";
	$sql  .=" group by b.Barcode";
    return find_by_sql($sql);

  }

  function join_stockaudit_barcode_report_export(){
    global $db;
    $sql  =" SELECT b.Fullbarcode,p.Itemcode,p.ItemName,c.Quantity as Audit_Quantity,b.Quantity as System_Quantity,u.name,c.Created_at";
    $sql  .=" FROM barcodedetails as b";
    $sql  .=" Left join stockdetails as s on b.Barcode = s.Barcode";
    $sql  .=" Left join productdetails as p on s.ProductId = p.ProductId";
    $sql  .=" Left join checkstockdetails as c ON (b.Barcode= c.Barcode and c.ProductId = p.ProductId)";
    $sql  .=" Left join users as u on c.userid = u.id";
    $sql  .=" WHERE p.ProductId IN (Select Distinct ProductId from checkstockdetails)";
	$sql  .=" group by b.Barcode";
    return $db->query($sql);

  }

           /*--------------------------------------------------------------*/
   /* Function for Finding all Audit stock based on products
   /* JOIN with barcode,product and users
   /*--------------------------------------------------------------*/
   function join_stockaudit_product_report(){
    global $db;
    $sql  =" SELECT c.Id,c.Barcode,c.ProductId,p.ItemName,p.Itemcode,SUM(c.Quantity) as Audit_Quantity,SUM(IF(b.Quantity>0,b.Quantity,0)) as System_Quantity";
    $sql  .=" FROM barcodedetails as b";
    $sql  .=" Left join stockdetails as s on b.Barcode = s.Barcode";
    $sql  .=" Left join productdetails as p on (s.ProductId = p.ProductId and s.StockType='IN - First Entry')";
    $sql  .=" Left join checkstockdetails as c ON (b.Barcode= c.Barcode and c.ProductId = p.ProductId)";
    $sql  .=" Left join users as u on c.userid = u.id";
    $sql  .=" WHERE p.ProductId IN (Select Distinct ProductId from checkstockdetails)";
    $sql  .=" group by p.ProductId";
    return find_by_sql($sql);

  }

  function join_stockaudit_product_report_export(){
    global $db;
    $sql  =" SELECT p.ItemName,p.Itemcode,SUM(c.Quantity) as Audit_Quantity,SUM(b.Quantity) as System_Quantity";
    $sql  .=" FROM barcodedetails as b";
    $sql  .=" Left join stockdetails as s on b.Barcode = s.Barcode";
    $sql  .=" Left join productdetails as p on (s.ProductId = p.ProductId and s.StockType='IN - First Entry')";
    $sql  .=" Left join checkstockdetails as c ON (b.Barcode= c.Barcode and c.ProductId = p.ProductId)";
    $sql  .=" Left join users as u on c.userid = u.id";
    $sql  .=" WHERE p.ProductId IN (Select Distinct ProductId from checkstockdetails)";
    $sql  .=" group by p.ProductId";
    return $db->query($sql);

  }

          /*--------------------------------------------------------------*/
   /* Function for Finding all Audit stock based on products
   /* JOIN with barcode,product and users
   /*--------------------------------------------------------------*/
   function clear_audit(){
    global $db;
    $sql  ="TRUNCATE TABLE checkstockdetails";
    return $db->query($sql);

  }

            /*--------------------------------------------------------------*/
   /* Function for Finding all product locations for each product
   /* JOIN with locations,product and stockdetails
   /*--------------------------------------------------------------*/
   function product_locations($productid){
    global $db;
    $sql  =" SELECT p.ProductId,p.Itemcode,p.ItemName,GROUP_CONCAT(DISTINCT l.LocationName) AS Locations";
    $sql  .=" FROM productdetails as p";
    $sql  .=" left join stockdetails as s on s.ProductId=p.ProductId";
    $sql  .=" left join locationdetails as l on l.LocationId = s.LocationId";
    $sql  .=" left join barcodedetails as b on b.Barcode = s.Barcode";
    if($productid!=0) {
      $sql  .=" where s.LocationId IS NOT NULL AND p.ProductId=".$productid." AND b.Quantity>0";
    } else {
      $sql  .=" where s.LocationId IS NOT NULL AND b.Quantity>0";
    }
    $sql  .=" group by p.ProductId";
    return $db->query($sql);

  }

          /*--------------------------------------------------------------*/
   /* Function for Finding all barcode in product wise
   /* JOIN with barcode,product and stock
   /*--------------------------------------------------------------*/
   function find_barcode_product($productname) {
    global $db;
    $sql  =" SELECT p.ProductId,b.Barcode,p.ItemName,p.Itemcode,b.Fullbarcode,b.Quantity,l.LocationName";
    $sql  .=" FROM barcodedetails as b";
    $sql  .=" Left join stockdetails as s on b.Barcode = s.Barcode";
    $sql  .=" Left join productdetails as p on s.ProductId = p.ProductId";
    $sql  .=" Left Join locationdetails as l on l.LocationId=s.LocationId";
    $sql  .=" WHERE b.Quantity>0 and s.StockType='IN - First Entry'";
    if($productname !=0) {
      $sql .=" and p.ProductId =".$productname;
    }
	
    return $db->query($sql);

  }


            /*--------------------------------------------------------------*/
   /* Function for Finding all barcode in product wise
   /* JOIN with barcode,product and stock
   /*--------------------------------------------------------------*/
   function find_batch_stock($productname) {
    global $db;
    $sql  =" SELECT p.ProductId,p.Itemcode,p.ItemName,bt.BatchShortName,sum(b.Quantity) as Quantity,GROUP_CONCAT(Distinct l.LocationName) as Locations";
    $sql  .=" FROM `stockdetails` s";
    $sql  .=" left join barcodedetails b on s.Barcode=b.Barcode";
    $sql  .=" left join productdetails p on s.ProductId=p.ProductId";
    $sql  .=" left join batchdetails bt on s.BatchId=bt.BatchId";
    $sql  .=" Left Join locationdetails as l on l.LocationId=s.LocationId";
    $sql  .=" WHERE b.Quantity>0 ";
    if($productname !=0) {
      $sql .=" and p.ProductId =".$productname;
    }
    $sql .=" group by p.ProductId,p.Itemcode,p.ItemName,bt.BatchName";
	
    return $db->query($sql);

  }

            /*--------------------------------------------------------------*/
   /* Function for Finding all barcode in product wise
   /* JOIN with barcode,product and stock
   /*--------------------------------------------------------------*/
   function find_locationwise_product($locationname) {
    global $db;
    $sql  =" SELECT p.ProductId,b.Barcode,p.ItemName,p.Itemcode,b.Fullbarcode,b.Quantity,l.LocationName";
    $sql  .=" FROM barcodedetails as b";
    $sql  .=" Left join stockdetails as s on b.Barcode = s.Barcode";
    $sql  .=" Left join productdetails as p on s.ProductId = p.ProductId";
    $sql  .=" Left Join locationdetails as l on l.LocationId=s.LocationId";
    $sql  .=" WHERE b.Quantity>0";
    if($locationname !=0) {
      $sql .=" and l.LocationId =".$locationname;
    }
	
    return $db->query($sql);

  }

  
  /*--------------------------------------------------------------*/
  /* Function for Update product quantity in Barcodedetails
  /*--------------------------------------------------------------*/
  function update_inentrystockbarcode_qty($qty,$barcode){
    global $db;
    $qty = (int) $qty;
    $id  = $barcode;
    $sql = "UPDATE barcodedetails SET quantity=0 WHERE barcode = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }

  function update_instockbarcode_qty($qty,$barcode){
    global $db;
    $qty = (int) $qty;
    $id  = $barcode;
    $sql = "UPDATE barcodedetails SET quantity=quantity -'{$qty}' WHERE barcode = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }

  function update_outstockbarcode_qty($qty,$barcode){
    global $db;
    $qty = (int) $qty;
    $id  = $barcode;
    $sql = "UPDATE barcodedetails SET quantity=quantity +'{$qty}' WHERE barcode = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  
   /*--------------------------------------------------------------*/
   /* Function for Finding specific batch id which is having data in
   /* JOIN with stocktable  and batchdate less than current batch date provided
   /*--------------------------------------------------------------*/
   function checkbatchinstock($batchdate,$ProdId){
    global $db;
    $sql  ="SELECT s.*,b.* FROM stockdetails as s";
    $sql  .=" inner join batchdetails b on s.BatchId= b.BatchId";
    $sql  .=" inner join barcodedetails bc on s.Barcode=bc.Barcode";
    $sql  .=" WHERE b.`BatchDate` < '".$batchdate."' and s.ProductId=".$ProdId." and bc.Quantity > 0";
    $sql1=$db->query($sql);
    if($result = $db->fetch_assoc($sql1))
            return $result;
          else
            return null;
    //return find_by_sql($sql);

  }
  
    /*--------------------------------------------------------------*/
   /* Function for Finding specific sub category uom list
   /* JOIN with subcategorie  and uom database table
   /*--------------------------------------------------------------*/
   function fullbarcode_fetch_detailsnew($fullbarcode){
    global $db;
    $sql   =" SELECT a.Fullbarcode,a.Quantity as avlqty,a.UOM as baseuom,b.* from barcodedetails as a";
   $sql  .=" LEFT JOIN stockdetails as b on b.Barcode=a.Barcode";
   $sql  .=" WHERE a.fullbarcode='".$fullbarcode."' LIMIT 1";
   $sql1=$db->query($sql);
    if($result = $db->fetch_assoc($sql1))
            return $result;
          else
            return null;
   //return find_by_sql($sql);

  }
  
  /*--------------------------------------------------------------*/
   /* Function for Finding batch level details with respect to items
   
   /*--------------------------------------------------------------*/
   function batch_reports($prodid){
    global $db;
    $sql   =" select p.Itemcode,p.ItemName,b.BatchId,b.BatchName,COUNT(s.Barcode) as barcodes from stockdetails s ";
   $sql  .=" join productdetails p on s.ProductId=p.ProductId";
   $sql  .=" join batchdetails b on s.BatchId=b.BatchId";
   $sql .=" where s.StockType='IN - First Entry' and p.ProductId=".$prodid;
   $sql .=" GROUP BY b.BatchId,p.Itemcode";
   
   //echo $sql;
   
   //$sql1=$db->query($sql);
   // if($result = $db->fetch_assoc($sql1))
   //         return $result;
    //      else
      //      return null;
   return $db->query($sql);;

  }


?>
