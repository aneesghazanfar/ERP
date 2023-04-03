<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
//--------------------------------------------------------------------------------------

function add_plan($order_no, $resource, $plan_qty, $ach_qty, $plan_sdate, $plan_edate, $remarks) {
	$sql = "INSERT INTO ".TB_PREF."process_plan (sorder_no, resource, plan_qty, ach_qty, plan_sdate, plan_edate, remarks)
		VALUES (" . db_escape($order_no) . ", " .
		db_escape($resource). ", " .
		db_escape($plan_qty). ", " .
		db_escape($ach_qty). ", ".
		db_escape($plan_sdate).", ".
		db_escape($plan_edate).", " .
		db_escape($remarks) . ")";

	db_query($sql, 'The order plan could not be added');
}

//--------------------------------------------------------------------------------------

function update_plan($selected_id, $order_no, $resource, $plan_qty, $ach_qty, $plan_sdate, $plan_edate, $remarks) {
	$sql = "UPDATE ".TB_PREF."process_plan SET
		sorder_no =" . db_escape($order_no). " ,
		resource =" . db_escape($resource). " ,
		plan_qty =" . db_escape($plan_qty). " ,
		ach_qty =" . db_escape($ach_qty). " ,
		plan_sdate =" .db_escape($plan_sdate). " ,
		plan_edate =" .db_escape($plan_edate). " ,
		remarks =" . db_escape($remarks). "
		WHERE plan_id = ".db_escape($selected_id);

	db_query($sql, 'The order plan could not be updated');
}

//--------------------------------------------------------------------------------------

function delete_plan($selected_id) {
	$sql="DELETE FROM ".TB_PREF."process_plan WHERE plan_id=".db_escape($selected_id);

	db_query($sql, 'could not delete order plan');
}

//--------------------------------------------------------------------------------------

function get_plans($show_inactive, $order_no) {
	$sql = "SELECT * FROM ".TB_PREF."process_plan WHERE sorder_no=".db_escape($order_no);
	if (!$show_inactive) $sql .= " AND !inactive";
 	$sql .= " ORDER BY plan_id";
 	return db_query($sql, 'could not get order plans');
 }

//--------------------------------------------------------------------------------------

function get_plan($selected_id) {
	$sql = "SELECT * FROM ".TB_PREF."process_plan WHERE plan_id=".db_escape($selected_id);
	$result = db_query($sql, 'could not get order plan');
	return db_fetch($result);
}

//--------------------------------------------------------------------------------------

function get_order($order_no) {
	$sql = "SELECT * FROM ".TB_PREF."sales_orders WHERE order_no=".db_escape($order_no);
	$o_result = db_query($sql, 'could not get item print');
	return db_fetch($o_result);
}
