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
			'async' => true,
	));
}
function stock_style_list_cells( $name, $selected_id=null, $submit_on_change=false,$order_no) {
	echo "<td>";
	echo stock_style_list($name, $selected_id,  $submit_on_change,$order_no);
	echo "</td>\n";
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
//--------------------------------------------------------------------------------------
function get_plan_data($order_no, $maincat_id, $unset=false, $col=null,$com=null){
	echo $com;
	if($unset){
		//echo "in ha bhai";
		unset($_SESSION['plan_data']);
	}
	if($col){
		$result = get_col_detail($order_no, $maincat_id);
	}
	else if($com){
		$result = get_comp_detail($order_no, $maincat_id);
	}
	else
		$result = get_style_detail($order_no, $maincat_id);
$line_no = 1;
while ($myrow = db_fetch($result)) {
    $plan_data['line_no'] = $line_no++;
    $plan_data['maincat_id'] = $myrow['maincat_id'];
    $plan_data['pp_id'] = $myrow['pp_id'];
    $plan_data['style_id'] = $myrow['style_id'];
	if($maincat_id == 1 && $myrow['maincat_id'] == 3){
		$plan_data['cstock_id'] = $myrow['stk_code'];
		$plan_data['cwaste'] = $myrow['waste'];
		$plan_data['cstk_extra'] = $myrow['stk_extra'];
		$plan_data['cstk_total'] = $myrow['stk_total'];
	}
	else{
		$plan_data['cstock_id'] = $plan_data['cstock_id'];
		$plan_data['cwaste'] = $plan_data['cwaste'];
		$plan_data['cstk_extra'] = $plan_data['cstk_extra'];
		$plan_data['cstk_total'] = $plan_data['cstk_total'];
		}
    $plan_data['stock_id'] = $myrow['stk_code'];
    $plan_data['description'] = get_description($myrow['stk_code']);
    $plan_data['ini_qty'] = 0;
    $plan_data['units'] = get_unit($myrow['stk_code']);
    $plan_data['perpc'] = $myrow['perpc'];
    $plan_data['waste'] = $myrow['waste'];
    $plan_data['total_req'] = 0;
    $plan_data['stk_extra'] = $myrow['stk_extra'];
    $plan_data['stk_total'] = $myrow['stk_total'];
    $plan_data['req_date'] = $myrow['req_date'];
    $plan_data['ufilename'] = $myrow['ufilename'];

    $existing_data = isset($_SESSION['plan_data']) ? $_SESSION['plan_data'] : array();
 // Check if pp_id already exists in session
$index = array_search($plan_data['pp_id'], array_column($existing_data, 'pp_id'));
if ($index !== false) {
// Data already in session, do nothing
}
else {
// Add new data to session
    $existing_data[] = $plan_data;
    $_SESSION['plan_data'] = $existing_data;
}
}
}
//-----------------------------------------------------------------------------------
function total_req($ini_qty , $perpc , $waste){
		return ($ini_qty * ($perpc * (100/(100-$waste))));
}
//--------------------------------------------------------------------------------------
function net_req($total_req, $stk_extra){
		return ($total_req + ($total_req * ($stk_extra/100)));
}
//--------------------------------------------------------------------------------------
function plan_sales_items_list($name, $selected_id=null, $all_option=false, $submit_on_change=false, $type='', $opts=array(), $editkey=false, $maincat_id,$maincat_id_2 ) {
	$sql = "SELECT i.item_code, i.description, c.description, count(*)>1 as kit,
			 i.inactive, if(count(*)>1, '0', s.editable) as editable
			FROM
			".TB_PREF."stock_master s,
			".TB_PREF."item_codes i
			LEFT JOIN
			".TB_PREF."stock_category c
			ON i.category_id=c.category_id
			WHERE i.stock_id=s.stock_id
	  AND mb_flag != 'F'";

 	if ($maincat_id || $maincat_id_2) 
		$sql .= " AND (s.maincat_id = ".db_escape($maincat_id) . " OR s.maincat_id = ".db_escape($maincat_id_2).")";
	
	if ($type == 'local') // exclude foreign codes
		$sql .=	" AND !i.is_foreign"; 
	elseif ($type == 'kits') // sales kits
		$sql .=	" AND !i.is_foreign AND i.item_code!=i.stock_id";
	
	$sql .= " AND !i.inactive AND !s.inactive AND !s.no_sale";
	$sql .= " GROUP BY i.item_code";

	return combo_input($name, $selected_id, $sql, 'i.item_code', 'c.description',
		array_merge(
		  array(
			'format' => '_format_stock_items',
			'spec_option' => $all_option===true ?  _('All Items') : $all_option,
			'spec_id' => ALL_TEXT,
			'search_box' => true,
			'search' => array("i.item_code", "c.description", "i.description"),
			'search_submit' => true,
			'size'=>15,
			'select_submit'=> $submit_on_change,
			'async' => false,
			'category' => 2,
			'order' => array('c.description','i.item_code'),
			'editlink' => $editkey ? add_edit_combo('item', $name) : false,
			'editable' => 30,
			'max' => 255
		  ), $opts), $type == 'kits' ? $type : "stock_sales" );
}

function plan_sales_items_list_cells($label, $name, $selected_id=null, $all_option=false, $submit_on_change=false, $editkey=false , $maincat_id = null ,$maincat_id_2 = null) {
	if ($editkey)
		set_editor('item', $name, $editkey);

	if ($label != null)
		echo "<td>".$label."</td>\n";

	echo plan_sales_items_list($name, $selected_id, $all_option, $submit_on_change,
		'', array('cells'=>true, 'max'=>50), $editkey, $maincat_id, $maincat_id_2); // maximum is set to 50 and not default 255.

}
