
<?php
// start 
// Anees Ghazanfar 5/3/2023
// this function is used to store item in itembase


function add_to_database (&$item_item , $order_no , $Comments , $maincat_id){

foreach($item as $key => $row) {
	$req_date = date('Y-m-d', strtotime($row['req_date']));

    if($row['id'] == null) {
        $sql = "INSERT INTO ".TB_PREF."purch_plan_details (sorder_no, style_id, maincat_id, stk_code, perpc, stk_extra, stk_total, ufilename, req_date)
                VALUES (".db_escape($order_no).", ".db_escape($row['style_id']).", ".db_escape($row['maincat_id']).", ".db_escape($row['stock_id']).", ".db_escape($row['perpc']).", ".db_escape($row['stk_extra']).", ".db_escape($row['stk_total']).", ".db_escape($row['ufilename']).", ".db_escape($req_date).")";
    }
	else {
		$sql = "UPDATE ".TB_PREF."purch_plan_details SET sorder_no = ".db_escape($order_no).", style_id = ".db_escape($row['style_id']).", maincat_id = ".db_escape($row['maincat_id']).", stk_code = ".db_escape($row['stock_id']).", perpc = ".db_escape($row['perpc']).", stk_extra = ".db_escape($row['stk_extra']).", stk_total = ".db_escape($row['stk_total']).", ufilename = ".db_escape($row['ufilename']).", req_date = ".db_escape($row['req_date'])." WHERE id = ".db_escape($row['id']);
		
	}
    	db_query($sql, 'order Cannot be Added');

}

if($maincat_id == 4)
	$Comments_variable = dyed_cmt;
else if($maincat_id == 3)
	$Comments_variable =greige_cmt;
else if($maincat_id == 2)
	$Comments_variable =yarn_cmt;
else if($maincat_id == 1)
	$Comments_variable =acs_cmt;



$sql = "UPDATE ".TB_PREF."purch_plan SET $Comments_variable = ".db_escape($Comments)." WHERE sorder_no = ".db_escape($order_no);
	    	db_query($sql, 'order Cannot be Added');



}

function get_plan_comments($order_no ,$maincat_id){
	if($maincat_id == 4)
		$Comments_variable = dyed_cmt;
	else if($maincat_id == 3)
		$Comments_variable =greige_cmt;
	else if($maincat_id == 2)
		$Comments_variable =yarn_cmt;
	else if($maincat_id == 1)
		$Comments_variable =acs_cmt;
	
	$sql = "SELECT $Comments_variable FROM ".TB_PREF."purch_plan WHERE sorder_no = ".db_escape($order_no);
	$result = db_query($sql, "could not get comments");
	$row = db_fetch_row($result);
	return $row[0];
}


// get all value already in item base
function get_all_detail($order_no){
	$sql = "SELECT * FROM ".TB_PREF."purch_plan_details WHERE sorder_no = ".db_escape($order_no);
	return db_query($sql, "could not get all detail");
}

function get_order_qty($order_no , $style_id){
$sql = "SELECT SUM(quantity) AS style_qty FROM ".TB_PREF."sales_order_details s,".TB_PREF."stock_master c WHERE s.order_no = ".db_escape($order_no)." AND c.style_id = ".db_escape($style_id)." AND s.stk_code=c.stock_id";
$result = db_query($sql, 'Could not retreive style quantity');
$row = db_fetch($result);
return $row[0];
}

// get Descripition of stock id from stock master
function get_description($stock_id){
	$sql = "SELECT description FROM ".TB_PREF."stock_master WHERE stock_id = ".db_escape($stock_id);
	$result = db_query($sql, "could not get description");
	$row = db_fetch_row($result);
	return $row[0];

}
//get unit of stock id from stock master

function get_unit($stock_id){
	$sql = "SELECT units FROM ".TB_PREF."stock_master WHERE stock_id = ".db_escape($stock_id);
	$result = db_query($sql, "could not get unit");
	$row = db_fetch_row($result);
	return $row[0];
}

function find_row($prefix, $numeric=true) {
	
	foreach($_POST as $postkey=>$postval ) {
		if (strpos($postkey, $prefix) === 0) {
			$id = substr($postkey, strlen($prefix));
			return (($numeric ? (int)$id : $id)-1);
		}
	}
	return $numeric ? -1 : null;
}

// end