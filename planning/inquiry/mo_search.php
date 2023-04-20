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


include_once($path_to_root."/planning/includes/ui/stock_issue_ui.inc");

include_once($path_to_root."/planning/includes/ui/receive_ui.inc");

$js = "";
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 500);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = "Manufacturing Orders Stock Inquiry"), false, false, "", $js);

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
	// return get_trans_view_str(ST_PURCHORDER, $trans["order_no"]);
	//Start - MUZZAMMIL - path to view_mo page which is created to show Manuf Order details.
	return '<a target="_blank" href="../../manufacturing/view/view_mo.php?trans_no='.$trans["order_no"].'" onclick="javascript:openWindow(this.href,this.target); return false;">'.$trans["order_no"].'</a>';
	//End - MUZZAMMIL
}

function prt_link($row)
{
	return print_document_link($row['order_no'], _("Print"), true, ST_PURCHORDER, ICON_PRINT);
}

//Start - MUZZAMMIL - 13-Apr-2023
//Adding edit issue link
function edit_issue_link($row) {
	$svc = get_item_cat($row['order_no']);
	return '<a href="../../planning/manage/stock_issue.php?mo_no='.$row["order_no"].'&svc='.$svc.'&ModifyIssuance=Yes" title="Edit Stock Issue"><i class="'.ICON_EDIT.'"></i></a>';
}
//Adding edit receive link
function edit_receive_link($row) {
	$svc = get_item_cat($row['order_no']);
	return '<a href="../../planning/manage/stock_receive.php?mo_no='.$row["order_no"].'&cat='.$svc.'&ModifyReceive=Yes" title="Edit Stock Receive"><i class="'.ICON_SR_EDIT.'"></i></a>';
}
//End - MUZZAMMIL - 13-Apr-2023

//Start - Anees - 06-Apr-2023
//Added new function for issue link
function issue_link($row) 
{
	$svc =  get_item_cat($row['order_no']);
	$order_header = get_po($row['order_no']);
	// $already_issued = already_issued($row['order_no']);
	// $required = get_required($row['order_no']);
	// || ($already_issued == $required && $required != 0)
	if($order_header['approve_by'] == NULL)
		return '<i class="'.ICON_SEND.'" style="color:grey;" disabled title="Manufacture Order pending approval or required quantity already issued, cannot issue stock"></i>';
	else
		return trans_issue_stock(ST_PURCHORDER, $row["order_no"], $svc);
}
//End - Anees - 06-Apr-2023

//Start - MUZZAMMIL - 29-Mar-2023
//Added new function for approval
function issue_approve_link($row) {
	$svc = get_item_cat($row['order_no']);

	return '<a target="_blank" href="../../planning/view/approve_si.php?mo_no='.$row["order_no"].'&svc='.$svc.'" onclick="javascript:openWindow(this.href,this.target); return false;" title="Approve Stock Issue"><i class="'.ICON_SUBMIT_WARNING.'"></i></a>';
}
//End - MUZZAMMIL - 29-Mar-2023

//Start - MUZZAMMIL - 10-Apr-2023
//Added new function for stock issue view
function stock_issue_view($row) {
	$order_no = $row['order_no'];
	$form_no = get_form_no($order_no, 'stock_issue');
	$svc =  get_item_cat($row['order_no']);
	return '<a target="_blank" href="../../planning/view/view_si.php?mo_no='.$row["order_no"].'&svc='.$svc.'" onclick="javascript:openWindow(this.href,this.target); return false;" title="View Stock Issue">'.$form_no.'</a>';
}
//End - MUZZAMMIL - 10-Apr-2023

//Start - Anees - modified receive link to open stock receive page
function receive_link($row) 
{
	$svc =  get_item_cat($row['order_no']);
  return pager_link( _("Stock Receive"),
	"/planning/manage/stock_receive.php?mo_no=" . $row["order_no"].'&cat='.$svc, ICON_RECEIVE);
}
//End - Anees - modified receive link to open stock receive page

//Start - MUZZAMMIL - 29-Mar-2023
//Added new function for approval
function receive_approve_link($row) {
	$order_no = $row['order_no'];
	$svc = get_item_cat($row['order_no']);

	return '<a target="_blank" href="../../planning/view/approve_sr.php?mo_no='.$row["order_no"].'&svc='.$svc.'" onclick="javascript:openWindow(this.href,this.target); return false;" title="Approve Stock Issue"><i class="'.ICON_SUBMIT_WARNING.'"></i></a>';
}
//End - MUZZAMMIL - 29-Mar-2023

//Start - MUZZAMMIL - 10-Apr-2023
//Added new function for stock issue view
function stock_receive_view($row) {
	$order_no = $row['order_no'];
	$form_no = get_form_no($order_no, 'stock_rec');
	$svc =  get_item_cat($row['order_no']);
	return '<a target="_blank" href="../../planning/view/view_sr.php?mo_no='.$row["order_no"].'&svc='.$svc.'" onclick="javascript:openWindow(this.href,this.target); return false;" title="View Stock Issue">'.$form_no.'</a>';
}
//End - MUZZAMMIL - 10-Apr-2023

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
		//Start - MUZZAMMIL - 10-Apr-2023 - Removed the currency column
		// _("Currency") => array('align'=>'center'), 
		//End - MUZZAMMIL - Removed the currency column
		_("Order Total") => 'amount',
		//Start - MUZZAMMIL - 10-Apr-2023
		//Added new column for stock_issue and receive view
		_('Stock Issue') => array('fun'=>'stock_issue_view', 'align'=>'center'),
		_('Stock Receive') => array('fun'=>'stock_receive_view', 'align'=>'center'),
		//End - MUZZAMMIL - 10-Apr-2023
		//Start - MUZZAMMIL - 29-Mar-2023
		//Added new column for stock_issue and approval
		array('insert'=>true, 'fun'=>'issue_link'),
		array('insert'=>true, 'fun'=>'edit_issue_link'),
		array('insert'=>true, 'fun'=>'issue_approve_link'),
		//Added new column for stock_receive and approval
		array('insert'=>true, 'fun'=>'receive_link'),
		array('insert'=>true, 'fun'=>'edit_receive_link'),
		array('insert'=>true, 'fun'=>'receive_approve_link'),
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
