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
$page_security = 'SA_SALESTRANSVIEW';
$path_to_root = '../..';
include_once($path_to_root . '/sales/includes/cart_class.inc');

include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/includes/date_functions.inc');

include_once($path_to_root . '/sales/includes/sales_ui.inc');
include_once($path_to_root . '/sales/includes/sales_db.inc');

include($path_to_root.'/purchasing/includes/db/so_plan_db.inc');
include($path_to_root.'/purchasing/includes/ui/so_plan_ui.inc');




$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 600);

if ($_GET['trans_type'] == ST_SALESQUOTE) {
	page(_($help_context = 'View Sales Quotation'), true, false, '', $js);
	display_heading(sprintf(_('Sales Quotation #%d'),$_GET['trans_no']));
}	
else {
	page(_($help_context = 'View Sales Order Plan'), true, false, '', $js);
	display_heading(sprintf(_('Sales Order #%d'),$_GET['trans_no']));
}

$order_no = $_GET['trans_no'];

if (isset($_SESSION['View']))
	unset ($_SESSION['View']);

$_SESSION['View'] = new Cart($_GET['trans_type'], $_GET['trans_no']);

start_table(TABLESTYLE2, "width='95%'", 5);

if ($_GET['trans_type'] != ST_SALESQUOTE) {
	echo '<tr valign=top><td>';
	display_heading2(_('Order Information'));
	echo '</td></tr>';
}	

echo '<tr valign=top><td>';

start_table(TABLESTYLE, "width='95%'");
label_row(_('Customer Name'), $_SESSION['View']->customer_name, "class='tableheader2'",
	'colspan=3');
start_row();
label_cells(_('Customer Order Ref.'), $_SESSION['View']->cust_ref, "class='tableheader2'");
label_cells(_('Deliver To Branch'), $_SESSION['View']->deliver_to, "class='tableheader2'");
end_row();
start_row();
label_cells(_('Ordered On'), $_SESSION['View']->document_date, "class='tableheader2'");
if ($_GET['trans_type'] == ST_SALESQUOTE)
	label_cells(_('Valid until'), $_SESSION['View']->due_date, "class='tableheader2'");
elseif ($_SESSION['View']->reference == 'auto')
	label_cells(_('Due Date'), $_SESSION['View']->due_date, "class='tableheader2'");
else
	label_cells(_('Requested Delivery'), $_SESSION['View']->due_date, "class='tableheader2'");
end_row();
start_row();
label_cells(_('Order Currency'), $_SESSION['View']->customer_currency, "class='tableheader2'");
label_cells(_('Deliver From Location'), $_SESSION['View']->location_name, "class='tableheader2'");
end_row();


if ($_SESSION['View']->payment_terms['days_before_due']<0) {
	start_row();
	label_cells(_('Payment Terms'), $_SESSION['View']->payment_terms['terms'], "class='tableheader2'");
	label_cells(_('Required Pre-Payment'), price_format($_SESSION['View']->prep_amount), "class='tableheader2'");
	end_row();
	start_row();
	label_cells(_('Non-Invoiced Prepayments'), price_format($_SESSION['View']->alloc), "class='tableheader2'");
	label_cells(_('All Payments Allocated'), price_format($_SESSION['View']->sum_paid), "class='tableheader2'");
	end_row();
}
else
	label_row(_('Payment Terms'), $_SESSION['View']->payment_terms['terms'], "class='tableheader2'", "colspan=3");

label_row(_('Delivery Address'), nl2br($_SESSION['View']->delivery_address), "class='tableheader2'", "colspan=3");
label_row(_('Reference'), $_SESSION['View']->reference, "class='tableheader2'", "colspan=3");
label_row(_('Telephone'), $_SESSION['View']->phone, "class='tableheader2'", "colspan=3");
label_row(_('E-mail'), "<a href='mailto:".$_SESSION['View']->email."'>".$_SESSION['View']->email."</a>","class='tableheader2'", "colspan=3");
label_row(_('Comments'), nl2br($_SESSION['View']->Comments), "class='tableheader2'", "colspan=3");
end_table();


echo '<center>';
var_dump(get_dyed_data($order_no,4));
get_fabric_data($order_no,3);
get_yarn_data($order_no,1,2);
get_acs_data($order_no,5);
get_collection_data($order_no,5);

display_heading2(_('Dyed Details'));

start_table(TABLESTYLE, "width='95%'");
$th = array(_('Style No.'), _('Total Quantity'), _('Accessory Code'), _('Accessory Description'), _('UoM'), _('Qty Per Piece'), _('Cutting Wastage%'), _('Dyed Fabric Per Piece'), _('Extra Quantity%'), _('Total Required'), _('Req Date'));
table_header($th);

var_dump($_SESSION['dyed_data']);

foreach ($_SESSION['dyed_data'] as $key => $value) {
	start_row();
		$des = get_description($value['stock_id']);
		$unit = get_unit($value['stock_id']);
		$t_style_qty = get_order_qty($order_no, $value['style_id']);
		$dyedperpc = get_dyedperpc($t_style_qty, $value['perpc'], $value['waste']);
		label_cell($value['style_id']);
		qty_cell($t_style_qty);
		view_stock_status_cell($value['stock_id']);
		label_cell($des);
		label_cell($unit);
		qty_cell($value['perpc']);
		qty_cell($value['waste']);
		qty_cell($dyedperpc);
		qty_cell($value['stk_extra']);
		qty_cell($value['stk_total']);
		label_cell($value['req_date']);
		end_row();

}



end_table();
echo "<br>";
display_heading2(_('Fabric Details'));

start_table(TABLESTYLE, "width='95%'");
$th = array(_('Style No.'), _('Total Quantity'), _('Accessory Code'), _('Accessory Description'),_('UoM'), _('Total Net Quantity'),_('Dyeing Wastage%'),_('Total Ecru Fabric'), _('Extra Quantity%'), _('Total Required'), _('Req Date'));
table_header($th);

foreach ($_SESSION['fabric_data'] as $key => $value) {
	start_row();
		$des = get_description($value['stock_id']);
		$unit = get_unit($value['stock_id']);
		$get_total_net_qty= get_total_net_qty($order_no, 4);
		$t_style_qty = get_order_qty($order_no, $value['style_id']);
		$total_fabric = get_total_fabric($get_total_net_qty, $value['waste'] );
		label_cell($value['style_id']);
		qty_cell($t_style_qty);
		view_stock_status_cell($value['stock_id']);
		label_cell($des);
		label_cell($unit);
		qty_cell($get_total_net_qty);
		qty_cell($value['waste']);
		qty_cell($total_fabric);
		qty_cell($value['stk_extra']);
		qty_cell($value['stk_total']);
		label_cell($value['req_date']);
	end_row();

}



end_table();

// echo "<br>";
// display_heading2(_('Fabric Details'));

// start_table(TABLESTYLE, "width='95%'");
// $th = array(_('Style No.'), _('Total Quantity'), _('Accessory Code'), _('Accessory Description'),_('UoM'), _('Total Net Quantity'),_('Dyeing Wastage%'),_('Total Ecru Fabric'), _('Extra Quantity%'), _('Total Required'), _('Req Date'));
// table_header($th);

// foreach ($_SESSION['fabric_data'] as $key => $value) {
// 	start_row();
// 		$des = get_description($value['stock_id']);
// 		$unit = get_unit($value['stock_id']);
// 		$get_total_net_qty= get_total_net_qty($order_no, 4);
// 		$t_style_qty = get_order_qty($order_no, $value['style_id']);
// 		$total_fabric = get_total_fabric($get_total_net_qty, $value['waste'] );
// 		label_cell($value['style_id']);
// 		qty_cell($t_style_qty);
// 		view_stock_status_cell($value['stock_id']);
// 		label_cell($des);
// 		label_cell($unit);
// 		qty_cell($get_total_net_qty);
// 		qty_cell($value['waste']);
// 		qty_cell($total_fabric);
// 		qty_cell($value['stk_extra']);
// 		qty_cell($value['stk_total']);
// 		label_cell($value['req_date']);
// 	end_row();

// }



end_table();



end_page(true, false, false, $_GET['trans_type'], $_GET['trans_no']);