<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
//UI--------------------------------------------------------------------------------------
function find_row($prefix, $numeric=true) {
	foreach($_POST as $postkey=>$postval ) {
		if (strpos($postkey, $prefix) === 0) {
			$id = substr($postkey, strlen($prefix));
			return (($numeric ? (int)$id : $id)-1);
		}
	}
	return $numeric ? -1 : null;
}
//--------------------------------------------------------------------------------------

function stock_style_list($name, $selected_id=null, $submit_on_change=false,$order_no) {
$sql = "SELECT DISTINCT s.clt_style, CONCAT(s.clt_style,' | ',s.style_id) as style_ref FROM ".TB_PREF."stock_master s,".TB_PREF."sales_order_details c WHERE s.stock_id=c.stk_code AND c.order_no=".db_escape($order_no);
	return combo_input($name, $selected_id, $sql, 'clt_style', 'style_ref',
		array(
			'spec_id' => -1,
			'search_submit' => true,
			'select_submit'=> $submit_on_change,
	));
}
function stock_style_list_cells( $name, $selected_id=null, $submit_on_change=false,$order_no) {
	echo "<td>";
	echo stock_style_list($name, $selected_id,  $submit_on_change,$order_no);
	echo "</td>\n";
}

//--------------------------------------------------------------------------------------
function net_req($stk_total, $stk_extra){
		return ($stk_total + ($stk_total * ($stk_extra/100)));
}
//-----------------------------------------------------------------------------------
function get_perpc($t_style_qty , $perpc , $waste){
		return ($t_style_qty * ($perpc * (100/(100-$waste))));
}
//-----------------------------------------------------------------------------------
// function total_yarn($total_qty , $waste){
// 		return ($total_qty * (100/(100-$waste)));
// }
//-----------------------------------------------------------------------------------
function total_net_req($stk_total, $waste, $stk_extra){
		$net_req = total_req($stk_total, $waste);
		return ($net_req + ($net_req * ($stk_extra/100)));
}
//-----------------------------------------------------------------------------------
function total_req($stk_total , $waste){
		return ($stk_total * (100/(100-$waste)));
}
//--------------------------------------------------------------------------------------
function get_dyed_data($order_no,$maincat_id){
		unset($_SESSION['dyed_data']);
	$result = get_all_detail($order_no,$maincat_id);
	$line_no = 1;
while($myrow = db_fetch($result)) {
	$dyed_data['line_no'] =$line_no++;
	$dyed_data['pp_id'] = $myrow['pp_id'];
	$dyed_data['style_id'] = $myrow['style_id'];
    $dyed_data['stock_id'] = $myrow['stk_code'];
	$dyed_data['maincat_id'] = $myrow['maincat_id'];
	$dyed_data['t_style_qty']  = get_style_total($order_no, $myrow['style_id']);
	$dyed_data['description'] = get_description($myrow['stk_code']);
	$dyed_data['units'] = get_unit($myrow['stk_code']);
    $dyed_data['perpc'] = $myrow['perpc'];
	$dyed_data['waste'] = $myrow['waste'];
    $dyed_data['stk_extra'] = $myrow['stk_extra'];
	$dyed_data['stk_total'] = $myrow['stk_total'];
	$dyed_data['req_date'] = $myrow['req_date'];
	$dyed_data['ufilename'] = $myrow['ufilename'];
	$existing_data = isset($_SESSION['dyed_data']) ? $_SESSION['dyed_data'] : array();
    $existing_data[] = $dyed_data;
    $_SESSION['dyed_data'] = $existing_data;
}
}
//--------------------------------------------------------------------------------------
function get_fabric_data($order_no,$maincat_id){
	unset($_SESSION['fabric_data']);
	$result = get_all_detail($order_no,$maincat_id);
	$line_no = 1;
while($myrow = db_fetch($result)) {
	$fabric_data['line_no'] =$line_no++;
	$fabric_data['pp_id'] = $myrow['pp_id'];
	$fabric_data['style_id'] = $myrow['style_id'];
    $fabric_data['stock_id'] = $myrow['stk_code'];
	$fabric_data['maincat_id'] = $myrow['maincat_id'];
//	$fabric_data['total_Net_Qty'] = get_total_net_qty($order_no, 4);
//	$fabric_data['t_style_qty']  = get_style_total($order_no, $myrow['style_id']);
	$fabric_data['description'] = get_description($myrow['stk_code']);
	$fabric_data['units'] = get_unit($myrow['stk_code']);
//    $fabric_data['perpc'] = $myrow['perpc'];
	$fabric_data['waste'] = $myrow['waste'];
    $fabric_data['stk_extra'] = $myrow['stk_extra'];
	$fabric_data['stk_total'] = $myrow['stk_total'];
	$fabric_data['req_date'] = $myrow['req_date'];
//	$fabric_data['ufilename'] = $myrow['ufilename'];
	$existing_data = isset($_SESSION['fabric_data']) ? $_SESSION['fabric_data'] : array();
    $existing_data[] = $fabric_data;
    $_SESSION['fabric_data'] = $existing_data;
}
}
//--------------------------------------------------------------------------------------
function get_yarn_data($order_no,$maincat_id, $maincat_id_2){
		unset($_SESSION['yarn_data']);
	$result = get_all_detail($order_no,$maincat_id, $maincat_id_2);
	$line_no = 1;
while($myrow = db_fetch($result)) {
	$yarn_data['line_no'] =$line_no++;
	$yarn_data['pp_id'] = $myrow['pp_id'];
	$yarn_data['style_id'] = $myrow['style_id'];
    $yarn_data['stock_id'] = $myrow['stk_code'];
	$yarn_data['maincat_id'] = $myrow['maincat_id'];
	$yarn_data['description'] = get_description($myrow['stk_code']);
	$yarn_data['units'] = get_unit($myrow['stk_code']);
    $yarn_data['perpc'] = $myrow['perpc'];
	$yarn_data['waste'] = $myrow['waste'];
    $yarn_data['stk_extra'] = $myrow['stk_extra'];
	$yarn_data['stk_total'] = $myrow['stk_total'];
	$yarn_data['req_date'] = $myrow['req_date'];
	$yarn_data['ufilename'] = $myrow['ufilename'];
	$existing_data = isset($_SESSION['yarn_data']) ? $_SESSION['yarn_data'] : array();
    $existing_data[] = $yarn_data;
    $_SESSION['yarn_data'] = $existing_data;
}
}
//--------------------------------------------------------------------------------------
function get_acs_data($order_no,$maincat_id){
		unset($_SESSION['acs_data']);
	$result = get_all_detail($order_no,$maincat_id);
	$line_no = 1;
while($myrow = db_fetch($result)) {
	$acs_data['line_no'] =$line_no++;
	$acs_data['pp_id'] = $myrow['pp_id'];
	$acs_data['style_id'] = $myrow['style_id'];
    $acs_data['stock_id'] = $myrow['stk_code'];
	$acs_data['maincat_id'] = $myrow['maincat_id'];
	$acs_data['t_style_qty']  = get_style_total($order_no, $myrow['style_id']);
	$acs_data['description'] = get_description($myrow['stk_code']);
	$acs_data['units'] = get_unit($myrow['stk_code']);
    $acs_data['perpc'] = $myrow['perpc'];
	$acs_data['waste'] = $myrow['waste'];
    $acs_data['stk_extra'] = $myrow['stk_extra'];
	$acs_data['stk_total'] = $myrow['stk_total'];
	$acs_data['ufilename'] = $myrow['ufilename'];
	$acs_data['req_date'] = $myrow['req_date'];
	$existing_data = isset($_SESSION['acs_data']) ? $_SESSION['acs_data'] : array();
    $existing_data[] = $acs_data;
    $_SESSION['acs_data'] = $existing_data;
}
}
//--------------------------------------------------------------------------------------
function get_collection_data($order_no,$maincat_id){
		unset($_SESSION['col_data']);
	$result = get_collection_detail($order_no,$maincat_id);
	$line_no = 1;
while($myrow = db_fetch($result)) {
	$col_data['line_no'] =$line_no++;
	$col_data['pp_id'] = $myrow['pp_id'];
    $col_data['stock_id'] = $myrow['stk_code'];
	$col_data['maincat_id'] = $myrow['maincat_id'];
	$col_data['t_style_qty']  = get_collection_qty($order_no);
	$col_data['description'] = get_description($myrow['stk_code']);
	$col_data['units'] = get_unit($myrow['stk_code']);
    $col_data['perpc'] = $myrow['perpc'];
	$col_data['waste'] = $myrow['waste'];
    $col_data['stk_extra'] = $myrow['stk_extra'];
	$col_data['stk_total'] = $myrow['stk_total'];
	$col_data['ufilename'] = $myrow['ufilename'];
	$col_data['req_date'] = $myrow['req_date'];
	$existing_data = isset($_SESSION['col_data']) ? $_SESSION['col_data'] : array();
    $existing_data[] = $col_data;
    $_SESSION['col_data'] = $existing_data;
}
}
//--------------------------------------------------------------------------------------
function amount_cells_ex_new($label, $name, $size, $max=null, $init=null, $submit_on_change=false, $params=null, $post_label=null, $dec=null) {
	global $Ajax;

	if (!isset($dec))
		$dec = user_price_dec();
	if (!isset($_POST[$name]) || $_POST[$name] == '') {
		if ($init !== null)
			$_POST[$name] = $init;
		else
			$_POST[$name] = '';
	}
	if ($label != null) {
		if ($params == null)
			$params = "class='label'";
		label_cell($label, $params);
	}
	if (!isset($max))
		$max = $size;

	if ($label != null)
		echo "<td>";
	else
		echo "<td align='right'>";
	$class = $submit_on_change ? 'class="searchbox"' : '';

	echo "<input $class class='amount' type=\"text\" name=\"$name\" size=\"$size\" maxlength=\"$max\" dec=\"$dec\" value=\"" . $_POST[$name]. "\">";

	if ($post_label) {
		echo "<span id='_{$name}_label'> $post_label</span>";
		$Ajax->addUpdate($name, '_'.$name.'_label', $post_label);
	}
	echo "</td>\n";
	$Ajax->addUpdate($name, $name, $_POST[$name]);
	$Ajax->addAssign($name, $name, 'dec', $dec);
}

//--------------------------------------------------------------------------------------
function small_qty_cells_ex($label, $name, $init=null, $submit_on_change=false, $params=null, $post_label=null, $dec=null) {
	if (!isset($dec))
		$dec = user_qty_dec();
	amount_cells_ex_new($label, $name, 7, 12, $init, $submit_on_change, $params, $post_label, $dec);
}
