<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
$page_security = 'SA_PURCHASEPLAN';
//----------------------------------------------------------------------------------------------
hidden('order_no', $order_no);
function can_process($order_no) {
	if (strlen($_POST['plan_req']) == 0) {
		display_error(_('The required quantity cannot be empty.'));
		set_focus('plan_req');
		return false;
	}
	if(!isset($_REQUEST['UPDATE_ITEM'])){
		$result = get_sums(check_value('show_inactive'), $order_no);
		while ($myrow = db_fetch($result)) {
			if ($myrow['stk_code'] == $_POST['stk_code']) {
				display_error(_('The selected item is already added to the order.'));
				set_focus('stk_code');
				return false;
			}
		}
	}
	return true;
}

global $Ajax;
$Ajax->activate('items_table');
//----------------------------------------------------------------------------------------------
if ($Mode=='ADD_ITEM' && can_process($order_no)) {
	add_sum($order_no, $_POST['stk_code'], $_POST['maincat_id'], $_POST['act_req'], $_POST['plan_req'], date2sql($_POST['req_date']));
	display_notification(_('New order plan demand has been added'));
	$Mode = 'RESET';
}

if ($Mode=='UPDATE_ITEM' && can_process($order_no)) {
	update_sum($selected_id, $order_no, $_POST['stk_code'], $_POST['maincat_id'], $_POST['act_req'], $_POST['plan_req'], date2sql($_POST['req_date']));
	display_notification(_('Selected order plan demand has been updated'));
	$Mode = 'RESET';
}

if ($Mode == 'Delete') {
// PREVENT DELETES IF DEPENDENT RECORDS IN 'sales_orders'
			delete_sum($selected_id);
			display_notification(_('Selected order plan demand has been deleted'));
	$Mode = 'RESET';
}

if ($Mode == 'RESET') {
	$selected_id = -1;
	$sav = get_post('show_inactive');
	unset($_POST);
	$_POST['show_inactive'] = $sav;
}

//----------------------------------------------------------------------------------------------
start_form();
display_heading("Purchase Demand Summary Against Sale Order");
$result = get_sums(check_value('show_inactive'), $order_no);
start_table(TABLESTYLE);
$th = array(_('Category'), _('Item Code'), _('Item Desc'), _('UoM'), _('Actual Qty'), _('Req Qty'), _('Req by'), '', '');
inactive_control_column($th);
table_header($th);
$k = 0; //row print counter
while ($myrow = db_fetch($result)) {
	alt_table_row_color($k);
	//get resource
		 $itemrow = get_item_detail($myrow['stk_code']);
		 $maincat_id=$myrow['maincat_id'];
		 $sql = "SELECT CONCAT(main_cat_code,' | ',main_cat_name) as maincat_ref FROM ".TB_PREF."stock_main_cat WHERE main_cat_id =".db_escape($maincat_id);
		 $result_id = db_query($sql, 'could not get category');
		 $resrow = db_fetch($result_id);
	label_cell($resrow['maincat_ref'], 'align=center');
	label_cell($myrow['stk_code']);
	label_cell($itemrow['description']);
	label_cell($itemrow['units']);
	qty_cell($myrow['act_req'], false, 1);
	qty_cell($myrow['plan_req'], false, 1);
	label_cell(sql2date($myrow['req_date']));
	inactive_control_cell($myrow['ppsum_id'], $myrow['inactive'], 'purch_plan_summary', 'ppsum_id');
	edit_button_cell('Edit'.$myrow['ppsum_id'], _('Edit'));
	delete_button_cell('Delete'.$myrow['ppsum_id'], _('Delete'));
	end_row();
}

inactive_control_row($th);
end_table(1);
if ($selected_id != -1) {
	if ($Mode == 'Edit') {
		$myrow = get_sum($selected_id);
		$_POST['maincat_id']	= $myrow['maincat_id'];
		$_POST['stk_code']	= $myrow['stk_code'];
		$_POST['act_req'] = $myrow['act_req'];
		$_POST['plan_req'] = $myrow['plan_req'];
		$_POST['req_date'] = sql2date($myrow['req_date']);
	}
	hidden('selected_id', $selected_id);
}



div_start('items_table');
start_table(TABLESTYLE);
$th = array(_('Category'), _('Item Code'), _('Item Desc'), _('UoM'), _('Actual Qty'), _('Req Qty'), _('Req by'));
table_header($th);
maincat_plan_list_cells('maincat_id', null, true);
item_plan_list_cells('stk_code', null, true, $_POST['maincat_id'], $order_no);
$itemrow = get_item_detail($_POST['stk_code']);
label_cell($itemrow['description']);
label_cell($itemrow['units']);
$act_req = get_summary_qty($order_no, $_POST['stk_code']);
qty_cell($act_req);
hidden('act_req', $act_req);
if (is_null($act_req))
label_cell('0');
else
small_qty_cells(null, 'plan_req', $act_req, null, null, 0);
date_cells(null, 'req_date');
end_table(1);
div_end();
submit_add_or_update_center($selected_id == -1, '', 'both');
end_form();
