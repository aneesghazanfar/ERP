<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
$page_security = 'SA_SALESORDER';
$path_to_root = '..';
include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/ui.inc');
include($path_to_root.'/purchasing/includes/db/so_plan_db.inc');
include($path_to_root.'/purchasing/includes/ui/so_plan_ui.inc');
$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'Manage Purchase Plans'), @$_REQUEST['popup'], false, '', $js);
simple_page_mode(true);
//----------------------------------------------------------------------------------------------
if (isset($_GET['OrderNumber'])){
$order_no= $_GET['OrderNumber'];
unset($_SESSION['plan_data']);
}
else {
	$order_no = $_POST['order_no'];
}
hidden('order_no', $order_no);

	$sql = "SELECT * FROM ".TB_PREF."sales_orders WHERE order_no=".db_escape($order_no);
	$o_result = db_query($sql, 'could not get item print');
	$ordrow = db_fetch($o_result);

start_table(TABLESTYLE2, "width='95%'", 5);
echo '<tr><td>'; // outer table
start_table(TABLESTYLE, "width='100%'");
start_row();
label_cells(_('Customer'), get_customer_name($ordrow['debtor_no']), "class='tableheader2'");
label_cells(_('Ordering Branch'), get_branch_name($ordrow['branch_code']), "class='tableheader2'");
label_cells(_('Customer Reference'), $ordrow['customer_ref'], "class='tableheader2'");
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
//----------------------------------------------------------------------------------------------
start_form(true);
tabbed_content_start('tabs', array(
	'dyed' => array(_('Dyed Fabric Purchase Plan'), true),
	'greige' => array(_('Greige Fabric Purchase Plan'), true),
	'yarn' => array(_('Yarn Purchase Plan'), true),
	'com' => array(_('Composite Purchase Plan'), true),
	'acs' => array(_('Accessories Purchase Plan (by Style)'), true),
	'col' => array(_('Accessories Purchase Plan (by Collection)'), true),
	'sum' => array(_('Purchase Demand'), true),
	));
	switch (get_post('_tabs_sel')) {
		default:
		case 'dyed':
		include_once($path_to_root.'/purchasing/manage/dfab_purch_plan.php');
			break;
		case 'greige':
			include_once($path_to_root.'/purchasing/manage/gfab_purch_plan.php');
			break;
		case 'com':
			include_once($path_to_root.'/purchasing/manage/com_purch_plan.php');
			break;
		case 'yarn':
			include_once($path_to_root.'/purchasing/manage/yarn_purch_plan.php');
			break;
		case 'acs':
			include_once($path_to_root.'/purchasing/manage/acs_purch_plan.php');
			break;
		case 'col':
			include_once($path_to_root.'/purchasing/manage/acs_col_purch_plan.php');
			break;
		case 'sum':
			include_once($path_to_root.'/purchasing/manage/summary_purch_plan.php');
	};
br();
tabbed_content_end();
end_form();
end_page();
