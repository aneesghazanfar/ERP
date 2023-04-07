<?php
/**********************************************************************
    
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
$page_security = 'SA_SUPPTRANSVIEW';
$path_to_root = "../..";
include($path_to_root."/includes/db_pager.inc");
include($path_to_root."/includes/session.inc");

include($path_to_root."/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root."/reporting/includes/reporting.inc");


include_once($path_to_root."/planning/includes/ui/issuance_ui.inc");

$js = "";
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 500);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = "Search Outstanding Purchase Orders"), false, false, "", $js);

if (isset($_GET['order_number']))
{
	$_POST['order_number'] = $_GET['order_number'];
}
//-----------------------------------------------------------------------------------
// Ajax updates
//
if (get_post('SearchOrders')) 
{
	$Ajax->activate('orders_tbl');
} elseif (get_post('_order_number_changed')) 
{
	$disable = get_post('order_number') !== '';

	$Ajax->addDisable(true, 'OrdersAfterDate', $disable);
	$Ajax->addDisable(true, 'OrdersToDate', $disable);
	$Ajax->addDisable(true, 'StockLocation', $disable);
	$Ajax->addDisable(true, '_SelectStockFromList_edit', $disable);
	$Ajax->addDisable(true, 'SelectStockFromList', $disable);

	if ($disable) {
		$Ajax->addFocus(true, 'order_number');
	} else
		$Ajax->addFocus(true, 'OrdersAfterDate');

	$Ajax->activate('orders_tbl');
}


//---------------------------------------------------------------------------------------------

start_form();

start_table(TABLESTYLE_NOBORDER);
start_row();
ref_cells(_("#:"), 'order_number', '',null, '', true);

date_cells(_("from:"), 'OrdersAfterDate', '', null, -user_transaction_days());
date_cells(_("to:"), 'OrdersToDate');

locations_list_cells(_("Location:"), 'StockLocation', null, true);
end_row();
end_table();

start_table(TABLESTYLE_NOBORDER);
start_row();

stock_items_list_cells(_("Item:"), 'SelectStockFromList', null, true);

supplier_list_cells(_("Select a supplier: "), 'supplier_id', null, true, true);

submit_cells('SearchOrders', _("Search"),'',_('Select documents'), 'default');
end_row();
end_table(1);
//---------------------------------------------------------------------------------------------
function trans_view($trans)
{
	return get_trans_view_str(ST_PURCHORDER, $trans["order_no"]);
}

function issue_link($row) 
{
	$svc =  get_item_cat($row['order_no']);

	return trans_edit_stock(ST_PURCHORDER, $row["order_no"], $svc);
}

function prt_link($row)
{
	return print_document_link($row['order_no'], _("Print"), true, ST_PURCHORDER, ICON_PRINT);
}

// Start - MUZZAMMIL - 06-Apr-2023 - Added for stock issue
// function issue_link($row) 
// {
//   return pager_link( _("Stock Issue"),
// 	"/purchasing/po_receive_items.php?PONumber=" . $row["order_no"], ICON_DOC);
// }
// End - MUZZAMMIL - 06-Apr-2023 - Added for stock issue

//Start - MUZZAMMIL - 29-Mar-2023
//Added new function for approval
function issue_approve_link($row) {
	$order_no = $row['order_no'];
	return '<a target="_blank" href="../../purchasing/view/approve_po.php?trans_no='.$row["order_no"].'" onclick="javascript:openWindow(this.href,this.target); return false;" title="Approve Stock Issue"><i class="'.ICON_SUBMIT.'"></i></a>';
}
//End - MUZZAMMIL - 29-Mar-2023

function receive_link($row) 
{
  return pager_link( _("Stock Receive"),
	"/purchasing/po_receive_items.php?PONumber=" . $row["order_no"], ICON_RECEIVE);
}

//Start - MUZZAMMIL - 29-Mar-2023
//Added new function for approval
function recieve_approve_link($row) {
	$order_no = $row['order_no'];
	return '<a target="_blank" href="../../purchasing/view/approve_po.php?trans_no='.$row["order_no"].'" onclick="javascript:openWindow(this.href,this.target); return false;" title="Approve Stock Recieve"><i class="'.ICON_SUBMIT.'"></i></a>';
}
//End - MUZZAMMIL - 29-Mar-2023

function check_overdue($row)
{
	return $row['OverDue']==1;
}
//---------------------------------------------------------------------------------------------

//figure out the sql required from the inputs available
$sql = get_sql_for_po_search(get_post('OrdersAfterDate'), get_post('OrdersToDate'), get_post('supplier_id'), get_post('StockLocation'),
	$_POST['order_number'], get_post('SelectStockFromList'), true);
//$result = db_query($sql,"No orders were returned");

//Start
//MUZZAMMIL HUSSAIN - 2023-Mar-30
//Added for showing the feedback message
if (isset($_SESSION['message'])) {
	display_notification($_SESSION['message']);
	unset($_SESSION['message']);
}
//End

/*show a table of the orders returned by the sql */
$cols = array(
		_("#") => array('fun'=>'trans_view', 'ord'=>''), 
		_("Reference"), 
		_("Supplier") => array('ord'=>''),
		_("Location"),
		_("Supplier's Reference"), 
		_("Order Date") => array('name'=>'ord_date', 'type'=>'date', 'ord'=>'desc'),
		_("Currency") => array('align'=>'center'), 
		_("Order Total") => 'amount',
		// array('insert'=>true, 'fun'=>'edit_stock'),
		array('insert'=>true, 'fun'=>'issue_link'),
		//Start - MUZZAMMIL - 29-Mar-2023
		//Added new column for stock_recieve approval
		array('insert'=>true, 'fun'=>'issue_approve_link'),
		//End - MUZZAMMIL - 29-Mar-2023
		array('insert'=>true, 'fun'=>'receive_link'),
		//Start - MUZZAMMIL - 29-Mar-2023
		//Added new column for stock_recieve approval
		array('insert'=>true, 'fun'=>'recieve_approve_link'),
		//End - MUZZAMMIL - 29-Mar-2023
		array('insert'=>true, 'fun'=>'prt_link')
);

if (get_post('StockLocation') != ALL_TEXT) {
	$cols[_("Location")] = 'skip';
}

$table =& new_db_pager('orders_tbl', $sql, $cols);
$table->set_marker('check_overdue', _("Marked orders have overdue items."));

$table->width = "80%";

display_db_pager($table);

end_form();
end_page();
