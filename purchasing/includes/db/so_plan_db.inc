<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
//--------------------------------------------------------------------------------------
function add_sum($order_no, $stk_code, $maincat_id, $act_req, $plan_req, $req_date) {
	$sql = "INSERT INTO ".TB_PREF."purch_plan_summary (sorder_no, stk_code, maincat_id, act_req, plan_req, req_date)
		VALUES (" . db_escape($order_no) . ", " .
		db_escape($stk_code). ", " .
		db_escape($maincat_id). ", " .
		db_escape($act_req). ", ".
		db_escape($plan_req).", ".
		db_escape($req_date). ")";

	db_query($sql, 'The order plan could not be added');
}

//--------------------------------------------------------------------------------------
function update_sum($selected_id, $order_no, $stk_code, $maincat_id, $act_req, $plan_req, $req_date) {
	$sql = "UPDATE ".TB_PREF."purch_plan_summary SET
		sorder_no =" . db_escape($order_no). " ,
		stk_code =" . db_escape($stk_code). " ,
		maincat_id =" . db_escape($maincat_id). " ,
		act_req =" . db_escape($act_req). " ,
		plan_req =" .db_escape($plan_req). " ,
		req_date =" . db_escape($req_date). "
		WHERE ppsum_id = ".db_escape($selected_id);

	db_query($sql, 'The order plan could not be updated');
}

//--------------------------------------------------------------------------------------
function delete_sum($selected_id) {
	$sql="DELETE FROM ".TB_PREF."purch_plan_summary WHERE ppsum_id=".db_escape($selected_id);
	db_query($sql, 'could not delete order plan');
}

//--------------------------------------------------------------------------------------
function get_sums($show_inactive, $order_no) {
	$sql = "SELECT * FROM ".TB_PREF."purch_plan_summary WHERE sorder_no=".db_escape($order_no);
	if (!$show_inactive) $sql .= " AND !inactive";
 	$sql .= " ORDER BY ppsum_id";
 	return db_query($sql, 'could not get order plans');
 }

//--------------------------------------------------------------------------------------
function get_sum($selected_id) {
	$sql = "SELECT * FROM ".TB_PREF."purch_plan_summary WHERE ppsum_id=".db_escape($selected_id);
	$result = db_query($sql, 'could not get order plan');
	return db_fetch($result);
}

//--------------------------------------------------------------------------------------
function get_item_detail($stock_id){
	$sql = "SELECT description, units FROM ".TB_PREF."stock_master WHERE stock_id = ".db_escape($stock_id);
	$result = db_query($sql, "could not get description");
	return db_fetch($result);

}
//--------------------------------------------------------------------------------------
function maincat_plan_list($name, $selected_id=null, $submit_on_change=false) {
	$sql = "SELECT main_cat_id, CONCAT(main_cat_code,' | ',main_cat_name) as maincat_ref FROM ".TB_PREF."stock_main_cat WHERE main_cat_id BETWEEN 1 AND 5";
	$options = array('order' => 'main_cat_id', 'select_submit'=> $submit_on_change);
	return combo_input($name, $selected_id, $sql, 'main_cat_id', 'maincat_ref', $options);
}
function maincat_plan_list_cells($name, $selected_id=null, $submit_on_change=false) {
	echo "<td>";
	echo maincat_plan_list($name, $selected_id, $submit_on_change);
	echo "</td>\n";
}

//--------------------------------------------------------------------------------------
function item_plan_list($name, $selected_id=null, $submit_on_change=false, $maincat_id=null, $order_no=null) {
	$sql = "SELECT DISTINCT stock_id, CONCAT(stock_id,' | ',description) as item_ref FROM ".TB_PREF."stock_master s, ".TB_PREF."purch_plan_details p WHERE s.stock_id=p.stk_code AND p.maincat_id = ".db_escape($maincat_id)." AND p.sorder_no = ".db_escape($order_no);
	$options = array('order' => 'stock_id', 'select_submit'=> $submit_on_change);
	return combo_input($name, $selected_id, $sql, 'stock_id', 'item_ref', $options);
}
function item_plan_list_cells($name, $selected_id=null, $submit_on_change=false, $maincat_id=null, $order_no=null) {
	echo "<td>";
	echo item_plan_list($name, $selected_id, $submit_on_change, $maincat_id, $order_no);
	echo "</td>\n";
}

//--------------------------------------------------------------------------------------
function get_summary_qty($order_no , $stk_code){
$sql = "SELECT SUM(stk_total) AS stk_total FROM ".TB_PREF."purch_plan_details WHERE sorder_no = ".db_escape($order_no)." AND stk_code = ".db_escape($stk_code);
$result = db_query($sql, 'Could not retreive item quantity');
$row = db_fetch($result);
return $row[0];
}

//Sum end--------------------------------------------------------------------------------------
function add_to_database (&$item , $order_no , $Comments , $maincat_id){
foreach($item as $key => $row) {
	$req_date = date('y/m/d', strtotime($row['req_date']));
    if($row['pp_id'] == null ) {
    $sql = "INSERT INTO ".TB_PREF."purch_plan_details (sorder_no, style_id, maincat_id, stk_code, perpc,waste, stk_extra, stk_total, ufilename, req_date)
            VALUES (".db_escape($order_no).", ".db_escape($row['style_id']).", ".db_escape($row['maincat_id']).", ".db_escape($row['stock_id']).",
            ".db_escape($row['perpc']).", ".db_escape($row['waste']).", ".db_escape($row['stk_extra']).",
            ".db_escape($row['stk_total']).", ".db_escape($row['ufilename'])." , ".db_escape($req_date).")";
}
else {
    $sql = "UPDATE ".TB_PREF."purch_plan_details SET sorder_no = ".db_escape($order_no).", style_id = ".db_escape($row['style_id']).",
    maincat_id = ".db_escape($row['maincat_id']).", stk_code = ".db_escape($row['stock_id']).", perpc = ".db_escape($row['perpc']).",
    waste = ".db_escape($row['waste']).", stk_extra = ".db_escape($row['stk_extra']).", stk_total = ".db_escape($row['stk_total']).",
    ufilename = ".db_escape($row['ufilename']).", req_date = ".db_escape($req_date)."  WHERE pp_id = ".db_escape($row['pp_id']);
}

    db_query($sql, 'order Cannot be Added');
}

if($maincat_id == 4)
	$Comments_variable = 'dyed_cmt';
else if($maincat_id == 3)
	$Comments_variable ='greige_cmt';
else if($maincat_id == 1)
	$Comments_variable ='yarn_cmt';
else if($maincat_id == 5)
	$Comments_variable ='acs_cmt';
$sql = "UPDATE ".TB_PREF."purch_plan SET $Comments_variable = ".db_escape($Comments)." WHERE sorder_no = ".db_escape($order_no);
	    	db_query($sql, 'order Cannot be Added');
}
//--------------------------------------------------------------------------------------
function get_plan_comments($order_no ,$maincat_id){
	if($maincat_id == 4)
		$Comments_variable = 'dyed_cmt';
	else if($maincat_id == 3)
		$Comments_variable ='greige_cmt';
	else if($maincat_id == 1)
		$Comments_variable ='yarn_cmt';
	else if($maincat_id == 5)
		$Comments_variable ='acs_cmt';

	$sql = "SELECT $Comments_variable FROM ".TB_PREF."purch_plan WHERE sorder_no = ".db_escape($order_no);
	$result = db_query($sql, "could not get comments");
	$row = db_fetch_row($result);
	return $row[0];
}
//--------------------------------------------------------------------------------------
function get_collection_detail($order_no,$maincat_id, $maincat_id_2 = null){
	$sql = "SELECT * FROM ".TB_PREF."purch_plan_details WHERE sorder_no = ".db_escape($order_no)." AND (maincat_id = ".db_escape($maincat_id)." OR maincat_id = ".db_escape($maincat_id_2).") AND style_id = '' ";
	return db_query($sql, "could not get all detail");
}
//--------------------------------------------------------------------------------------
function get_all_detail($order_no,$maincat_id, $maincat_id_2 = null){
	$sql = "SELECT * FROM ".TB_PREF."purch_plan_details WHERE sorder_no = ".db_escape($order_no)." AND (maincat_id = ".db_escape($maincat_id)." OR maincat_id = ".db_escape($maincat_id_2).") AND style_id != ''";
	return db_query($sql, "could not get all detail");
}
//--------------------------------------------------------------------------------------
function get_collection_qty($order_no){
$sql = "SELECT SUM(quantity) AS collection_qty FROM ".TB_PREF."sales_order_details WHERE order_no = ".db_escape($order_no);
$result = db_query($sql, 'Could not retreive collection quantity');
$row = db_fetch($result);
return $row[0];
}
//--------------------------------------------------------------------------------------
function get_order_qty($order_no , $style_id){
$sql = "SELECT SUM(quantity) AS style_qty FROM ".TB_PREF."sales_order_details s,".TB_PREF."stock_master c WHERE s.order_no = ".db_escape($order_no)." AND c.style_id = ".db_escape($style_id)." AND s.stk_code=c.stock_id";
$result = db_query($sql, 'Could not retreive style quantity');
$row = db_fetch($result);
return $row[0];
}
//--------------------------------------------------------------------------------------
function get_description($stock_id){
	$sql = "SELECT description FROM ".TB_PREF."stock_master WHERE stock_id = ".db_escape($stock_id);
	$result = db_query($sql, "could not get description");
	$row = db_fetch_row($result);
	return $row[0];
}
//--------------------------------------------------------------------------------------
function get_unit($stock_id){
	$sql = "SELECT units FROM ".TB_PREF."stock_master WHERE stock_id = ".db_escape($stock_id);
	$result = db_query($sql, "could not get unit");
	$row = db_fetch_row($result);
	return $row[0];
}
//--------------------------------------------------------------------------------------
function get_last_id(){
$sql = "SELECT pp_id FROM ".TB_PREF."purch_plan_details ORDER BY pp_id DESC LIMIT 1";
$result = db_query($sql, "could not get id");
	$row = db_fetch_row($result);
	return $row[0];
}
//--------------------------------------------------------------------------------------
function get_total_net_qty($order_no, $maincat_id){
	$sql = "SELECT stk_total FROM ".TB_PREF."purch_plan_details WHERE sorder_no = ".db_escape($order_no)." AND maincat_id = ".db_escape($maincat_id);
	$result = db_query($sql, "could not get stk_total");
	$row = db_fetch_row($result);
	return $row[0];
}
//--------------------------------------------------------------------------------------
function get_cat_qty($order_no, $stock_id){
	$sql1 = "SELECT qlty_id FROM ".TB_PREF."stock_master WHERE stock_id = ".db_escape($stock_id);
	$result1 = db_query($sql1, "could not get qlty_id");
	$row1 = db_fetch($result1);
	$qlty_id = $row1[0];

	$sql = "SELECT SUM(stk_total) AS cat_qty FROM ".TB_PREF."purch_plan_details p,".TB_PREF."stock_master s WHERE 
	p.sorder_no = ".db_escape($order_no)." AND s.qlty_id =  ".db_escape($qlty_id)." AND p.stk_code=s.stock_id AND p.maincat_id = 4";
	
	$result = db_query($sql, "could not get stk_total");
	$row = db_fetch_row($result);
	return $row[0];
}
//--------------------------------------------------------------------------------------
function get_collection($order_no){
	$sql = "SELECT reference FROM ".TB_PREF."purch_plan WHERE sorder_no = ".db_escape($order_no);
	$result = db_query($sql, "could not get collection");
	$row = db_fetch_row($result);
	return $row[0];
}
//--------------------------------------------------------------------------------------
