
<?php
// start 
// Anees Ghazanfar 5/3/2023
// this function is used to store data in database


function add_to_database (&$data , $order_no){

	

foreach($data as $key => $row) {
	$req_date = date('Y-m-d', strtotime($row['req_date']));

    if($row['id'] == null) {
        $sql = "INSERT INTO ".TB_PREF."purch_plan_details (sorder_no, style_id, stk_type, stk_code, perpc, stk_extra, stk_total, filename, unique_name, req_date)
                VALUES (".db_escape($order_no).", ".db_escape($row['style_id']).", ".db_escape($row['stk_type']).", ".db_escape($row['stock_id']).", ".db_escape($row['perpc']).", ".db_escape($row['stk_extra']).", ".db_escape($row['stk_total']).", ".db_escape($row['filename']).", ".db_escape($row['unique_name']).", ".db_escape($req_date).")";
    }
	else {
		$sql = "UPDATE ".TB_PREF."purch_plan_details SET sorder_no = ".db_escape($order_no).", style_id = ".db_escape($row['style_id']).", stk_type = ".db_escape($row['stk_type']).", stk_code = ".db_escape($row['stock_id']).", perpc = ".db_escape($row['perpc']).", stk_extra = ".db_escape($row['stk_extra']).", stk_total = ".db_escape($row['stk_total']).", filename = ".db_escape($row['filename']).", unique_name = ".db_escape($row['unique_name']).", req_date = ".db_escape($row['req_date'])." WHERE id = ".db_escape($row['id']);
		
	}
    	db_query($sql, 'order Cannot be Added');

}



}


// get all value already in data base
function get_all_detail($order_no){
	$sql = "SELECT * FROM ".TB_PREF."purch_plan_details WHERE sorder_no = ".db_escape($order_no);
	return db_query($sql, "could not get all detail");
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


// end