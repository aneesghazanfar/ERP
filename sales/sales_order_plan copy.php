<?php
/**********************************************************************
	Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
	See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/

/*
*	Entry/Modify Sales Quotations
*	Entry/Modify Sales Order
*	Entry Direct Delivery
*	Entry Direct Invoice
*/

$path_to_root = '..';
$page_security = 'SA_SALESORDER';

include_once($path_to_root.'/purchasing/includes/po_class.inc');
include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/sales/includes/sales_ui.inc');
include_once($path_to_root . '/sales/includes/ui/sales_order_ui.inc');
include_once($path_to_root . '/sales/includes/sales_db.inc');
include_once($path_to_root . '/sales/includes/db/sales_types_db.inc');
include_once($path_to_root . '/reporting/includes/reporting.inc');
include_once($path_to_root . '/sales/includes/db/sales_order_db.inc');


$js = '';

if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 500);

if (user_use_date_picker())
	$js .= get_js_date_picker();


if (isset($_GET['OrderNumber'])) {
$order_no= $_GET['OrderNumber'];
}
else $order_no = $_POST['order_no'];
hidden('order_no', $order_no);
// if ($order_no < 1) {
// 	display_error(_('This page can only be opened if an order has been selected. Please select an ordder.'));
// 	hyperlink_params($path_to_root.'/purchasing/inquiry/sales_orders_view.php', _('Select a Sales Order to Plan'), 'OutstandingOnly=1');
// 	end_page();
// 	exit;
// }
if (isset($_GET['OrderNumber']) && is_numeric($_GET['OrderNumber'])) {
	$help_context = 'Plan Sales Order';
	$_SESSION['page_title'] = sprintf( _('Plan Sales Order # %d'), $_GET['OrderNumber']);
	create_cart(ST_SALESORDER, $_GET['OrderNumber']);
}

page($_SESSION['page_title'], false, false, '', $js);

function create_cart($type, $trans_no) { 
	global $Refs, $SysPrefs;

		$_SESSION['Items'] = new Cart($type, array($trans_no));
	}

function line_start_focus() {
	global 	$Ajax;

	$Ajax->activate('items_edit_table');
	set_focus('_stock_id_edit');
}
function handle_update_item() {
	if ($_POST['UpdateItem'])
	$_SESSION['Items']->update_edited_cart_item($_POST['LineNo'], $_POST['style_id'],input_num('perpc') ,input_num('stk_extra'), $_POST['req_date']);
	
	page_modified();
	line_start_focus();
}

function handle_new_item() {

	// $_SESSION['Items']->add_plan_items($_POST['LineNo'],$_POST['stock_id']);
	// add_to_order($_SESSION['Items'], get_post('stock_id') ,input_num('qty'), input_num('price'), input_num('Disc') / 100, get_post('stock_id_text'), $_POST['style_id']);
	add_item_plan();
	page_modified();
	line_start_focus();
}

function handle_delete_item($line_no) {
	if ($_SESSION['Items']->some_already_delivered($line_no) == 0)
		$_SESSION['Items']->remove_from_cart($line_no);
	else
		display_error(_('This item cannot be deleted because some of it has already been delivered.'));
	
	line_start_focus();
}
$id = find_submit('Delete');
if ($id!=-1)
	handle_delete_item($id);
if (isset($_POST['UpdateItem']))
	handle_update_item();

if (isset($_POST['CancelItemChanges']))
	line_start_focus();

if (isset($_POST['AddItem']))
	handle_new_item();

start_form(true);

$maincat_id = 4;
	display_order_detail('Sales Order', $_SESSION['Items'], $maincat_id, true);

start_table(TABLESTYLE2);
textarea_row(_('Remarks:'), 'Comments', null, 70, 4);
end_table(1);
div_end();
submit_add_or_update_center($selected_id == -1, '', 'both');
end_form();
end_page();
