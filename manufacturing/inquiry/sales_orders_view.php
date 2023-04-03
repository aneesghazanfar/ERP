<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
$path_to_root = '../..';

include_once($path_to_root . '/includes/db_pager.inc');
include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/sales/includes/sales_ui.inc');
include_once($path_to_root . '/reporting/includes/reporting.inc');

$page_security = 'SA_SALESTRANSVIEW';

set_page_security( @$_POST['order_view_mode'], array(	'OutstandingOnly' => 'SA_SALESDELIVERY'),
	array(	'OutstandingOnly' => 'SA_SALESDELIVERY'));

	$trans_type = ST_SALESORDER;

if ($trans_type == ST_SALESORDER) {
	if (isset($_GET['OutstandingOnly']) && ($_GET['OutstandingOnly'] == true)) {
		$_POST['order_view_mode'] = 'OutstandingOnly';
		$_SESSION['page_title'] = _($help_context = 'Plan Purchase from Pending Sales Orders');
	}
	elseif (!isset($_POST['order_view_mode'])) {
		$_POST['order_view_mode'] = false;
		$_SESSION['page_title'] = _($help_context = 'Plan Purchase from All Sales Orders');
	}
}

$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page($_SESSION['page_title'], false, false, '', $js);

//---------------------------------------------------------------------------------------------
//	Query format functions
//
function check_overdue($row) {
	global $trans_type;
		return ($row['type'] == 0
			&& date1_greater_date2(Today(), sql2date($row['delivery_date']))
			&& ($row['TotDelivered'] < $row['TotQuantity']));
}

function view_link($dummy, $order_no) {
	global $trans_type;
	return  get_customer_trans_view_str($trans_type, $order_no);
}

function prt_link($row) {
	global $trans_type;
	return print_document_link($row['order_no'], _('Print'), $trans_type, ICON_PRINT);
}

function plan_link($row) {
	global $trans_type, $page_nested;
		$order = get_sales_order_header($row['order_no'], ST_SALESORDER);
		$approval_date = $order['approval_date'];
		if($approval_date == NULL){
			return pager_link( _('Process Planning'), '/manufacturing/process_order_plan.php?OrderNumber='.$row['order_no'], ICON_PLAN);
		}
		else
			return '<i class="'.ICON_PLAN.'" style="color:grey;" disabled title="This order has not been approved yet"></i>';
}

$id = find_submit('_chgtpl');
if ($id != -1) {
	sales_order_set_template($id, check_value('chgtpl'.$id));
	$Ajax->activate('orders_tbl');
}

if (isset($_POST['Update']) && isset($_POST['last'])) {
	foreach($_POST['last'] as $id => $value)
		if ($value != check_value('chgtpl'.$id))
			sales_order_set_template($id, !check_value('chgtpl'.$id));
}

$show_dates = !in_array($_POST['order_view_mode'], array('OutstandingOnly'));

//---------------------------------------------------------------------------------------------
//	Order range form
//
if (get_post('_OrderNumber_changed') || get_post('_OrderReference_changed')) { // enable/disable selection controls
	$disable = get_post('OrderNumber') !== '' || get_post('OrderReference') !== '';

	if ($show_dates) {
		$Ajax->addDisable(true, 'OrdersAfterDate', $disable);
		$Ajax->addDisable(true, 'OrdersToDate', $disable);
	}

	$Ajax->activate('orders_tbl');
}

start_form();

start_table(TABLESTYLE_NOBORDER);
start_row();
ref_cells(_('#:'), 'OrderNumber', '',null, '', true);
ref_cells(_('Ref'), 'OrderReference', '',null, '', true);
if ($show_dates) {
	date_cells(_('from:'), 'OrdersAfterDate', '', null, -user_transaction_days());
	date_cells(_('to:'), 'OrdersToDate', '', null, 1);
}
locations_list_cells(_('Location:'), 'StockLocation', null, true, true);

if($show_dates) {
	end_row();
	end_table();

	start_table(TABLESTYLE_NOBORDER);
	start_row();
}
stock_items_list_cells(_('Item:'), 'SelectStockFromList', null, true, true);

if (!$page_nested)
	customer_list_cells(_('Select a customer: '), 'customer_id', null, true, true);
if ($trans_type == ST_SALESQUOTE)
	check_cells(_('Show All:'), 'show_all');

submit_cells('SearchOrders', _('Search'),'',_('Select documents'), 'default');
hidden('order_view_mode', $_POST['order_view_mode']);
hidden('type', $trans_type);

end_row();

end_table(1);

//---------------------------------------------------------------------------------------------
//	Orders inquiry table
//
$sql = get_sql_for_sales_orders_view($trans_type, get_post('OrderNumber'), get_post('order_view_mode'), get_post('SelectStockFromList'), get_post('OrdersAfterDate'), get_post('OrdersToDate'), get_post('OrderReference'), get_post('StockLocation'), get_post('customer_id'));
	$cols = array(
		_('Order #') => array('fun'=>'view_link', 'align'=>'right', 'ord' =>''),
		_('Ref') => array('type' => 'sorder.reference', 'ord' => '') ,
		_('Customer') => array('type' => 'debtor.name' , 'ord' => '') ,
		_('Branch'),
		_('Cust Order Ref'),
		_('Order Date') => array('type' =>  'date', 'ord' => ''),
		_('Required By') =>array('type'=>'date', 'ord'=>''),
		_('Delivery To'),
		_('Order Total') => array('type'=>'amount', 'ord'=>''),
		'Type' => 'skip',
		_('Currency') => array('align'=>'center'),
	);
	array_append($cols, array(
		array('insert'=>true, 'fun'=>'plan_link'),
		array('insert'=>true, 'fun'=>'prt_link')));


$table =& new_db_pager('orders_tbl', $sql, $cols);
$table->set_marker('check_overdue', _('Marked items are overdue.'));

$table->width = '80%';

display_db_pager($table);
submit_center('Update', _('Update'), true, '', null);

end_form();
end_page();
