<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
$page_security = 'SA_MANUFPLAN';
$path_to_root = '..';
include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/ui.inc');
include($path_to_root.'/sales/includes/db/order_plan_db.inc');
$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'Manage Plans'), @$_REQUEST['popup'], false, '', $js);
simple_page_mode(true);

//----------------------------------------------------------------------------------------------
if (isset($_GET['OrderNumber'])) {
$order_no= $_GET['OrderNumber'];
}
else $order_no = $_POST['order_no'];
hidden('order_no', $order_no);

function can_process() {
	if (strlen($_POST['resource']) == 0) {
		display_error(_('The resource cannot be empty.'));
		set_focus('resource');
		return false;
	}
	if (strlen($_POST['plan_qty']) == 0) {
		display_error(_('The planned quanity cannot be empty.'));
		set_focus('plan_qty');
		return false;
	}
	if(date_comp($_POST['plan_sdate'], $_POST['plan_edate']) > 0) {
		display_error(_('Start date cannot be after end date.'));
		set_focus('plan_sdate');
		$error = 1;
	}
	return true;
}
if ($order_no < 1) {
	/* This page can only be called with an order number for invoicing*/

	display_error(_('This page can only be opened if an order has been selected. Please select it first.'));

	hyperlink_params($path_to_root.'/sales/inquiry/sales_orders_view.php', _('Select a Sales Order to Plan'), 'OutstandingOnly=1');

	end_page();
	exit;
}


//----------------------------------------------------------------------------------------------
if ($Mode=='ADD_ITEM' && can_process()) {
	add_plan($_POST['order_no'], $_POST['resource'], $_POST['plan_qty'], $_POST['ach_qty'], date2sql($_POST['plan_sdate']), date2sql($_POST['plan_edate']), $_POST['remarks']);
	display_notification(_('New order plan has been added'));
	$Mode = 'RESET';
}

if ($Mode=='UPDATE_ITEM' && can_process()) {
	update_plan($selected_id, $_POST['order_no'], $_POST['resource'], $_POST['plan_qty'], $_POST['ach_qty'], date2sql($_POST['plan_sdate']), date2sql($_POST['plan_edate']), $_POST['remarks']);
	display_notification(_('Selected order plan has been updated'));
	$Mode = 'RESET';
}

if ($Mode == 'Delete') {
// PREVENT DELETES IF DEPENDENT RECORDS IN 'sales_orders'
			delete_plan($selected_id);
			display_notification(_('Selected order plan has been deleted'));
	$Mode = 'RESET';
}

if ($Mode == 'RESET') {
	$selected_id = -1;
	$sav = get_post('show_inactive');
	unset($_POST);
	$_POST['show_inactive'] = $sav;
}

//----------------------------------------------------------------------------------------------
$ordrow = get_order($order_no);
start_form();
start_table(TABLESTYLE2, "width='95%'", 5);
echo '<tr><td>'; // outer table
start_table(TABLESTYLE, "width='100%'");
start_row();
label_cells(_('Customer'), get_customer_name($ordrow['debtor_no']), "class='tableheader2'");
label_cells(_('Ordering Branch'), get_branch_name($ordrow['branch_code']), "class='tableheader2'");
label_cells(_('Customer Reference'), get_salesman_name($ordrow['customer_ref']), "class='tableheader2'");
label_cells(_('Reference'), $ordrow['reference'], "class='tableheader2'");
end_row();
start_row();
label_cells(_('For Sales Order'), get_customer_trans_view_str(ST_SALESORDER, $order_no), "class='tableheader2'");
if ($ordrow['repeat_of'] != 0)
label_cells(_('Repeat of'), get_customer_trans_view_str(ST_SALESORDER, $ordrow['repeat_of']), "class='tableheader2'");
else
label_cells(_('Repeat of'), 'New', "class='tableheader2'");
label_cells(_('Total Quantity'), $ordrow['qty_total'], "class='tableheader2'");
label_cells(_('Total Value'), $ordrow['total'].' '.$ordrow['ord_curr'], "class='tableheader2'");
end_row();
start_row();
label_cells(_('Order Date'), $ordrow['ord_date'], "class='tableheader2'");
label_cells(_('Delivery Date'), $ordrow['delivery_date'], "class='tableheader2'");
label_cells(_('Delivery From'), $ordrow['from_stk_loc'], "class='tableheader2'");
label_cells(_('Merchandizer'), get_salesman_name($ordrow['salesman']), "class='tableheader2'");
end_row();
end_table();
echo '</td></tr>';
end_table(1); // outer table

$result = get_plans(check_value('show_inactive'), $order_no);
start_table(TABLESTYLE);
$th = array(_('Resource'), _('Quantity'), _('Ach Qty'), _('Start Date'), _('End Date'), _('Days'), _('Per Day'), _('Remarks'), '', '');
inactive_control_column($th);
table_header($th);
$k = 0; //row print counter
while ($myrow = db_fetch($result)) {
	alt_table_row_color($k);
	//get resource
		 $res_id=$myrow['resource'];
		 $sql = "SELECT op_proc_code FROM ".TB_PREF."op_proc WHERE op_proc_id='$res_id'";
		 $result_id = db_query($sql, 'could not get order plan');
		 $resrow = db_fetch($result_id);
label_cell($resrow['op_proc_code'], 'align=center');
qty_cell($myrow['plan_qty'], false, 0);
qty_cell($myrow['ach_qty'], false, 0);
label_cell($myrow['plan_sdate']);
label_cell($myrow['plan_edate']);
$totdays=date_diff2(sql2date($myrow['plan_edate']), sql2date($myrow['plan_sdate']), 'd');
$perday=ceil($myrow['plan_qty'] / ($totdays+1));
qty_cell($totdays + 1, false, 0);
qty_cell($perday, false, 0);
label_cell($myrow['remarks']);

	inactive_control_cell($myrow['plan_id'], $myrow['inactive'], 'sales_order_plan', 'plan_id');
	edit_button_cell('Edit'.$myrow['plan_id'], _('Edit'));
	delete_button_cell('Delete'.$myrow['plan_id'], _('Delete'));
	end_row();
}

inactive_control_row($th);
end_table(1);
if ($selected_id != -1) {
	if ($Mode == 'Edit') {
		$myrow = get_plan($selected_id);
		$_POST['order_no']	= $myrow['order_no'];
		$_POST['resource']	= $myrow['resource'];
		$_POST['plan_qty']	= $myrow['plan_qty'];
		$_POST['ach_qty'] = $myrow['ach_qty'];
		$_POST['plan_sdate'] = sql2date($myrow['plan_sdate']);
		$_POST['plan_edate'] = sql2date($myrow['plan_edate']);
		$_POST['remarks'] = $myrow['remarks'];
	}
	hidden('selected_id', $selected_id);
}
start_table(TABLESTYLE);
$th = array(_('Resource'), _('Quantity'), _('Ach Qty'), _('Start Date'), _('End Date'), _('Remarks'));
table_header($th);
op_proc_list_cells(null, 'resource', null, true, ' ');
small_qty_cells(null, 'plan_qty', $ordrow['qty_total'], null, null, 0);
small_qty_cells(null, 'ach_qty', 0, null, null, 0);
date_cells(null, 'plan_sdate');
date_cells(null, 'plan_edate', '', null, 0, 0, 0, null, true);
text_cells(null,'remarks', null, 25, 50);
end_table(1);
submit_add_or_update_center($selected_id == -1, '', 'both');

end_form();

end_page();
