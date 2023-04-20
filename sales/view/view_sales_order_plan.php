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

include($path_to_root.'/planning/includes/db/so_plan_db.inc');
include($path_to_root.'/planning/includes/ui/so_plan_ui.inc');




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
get_plan_data($order_no, 4 , true);
$perpc=1;
hidden('perpc', $perpc);

display_heading2(_('Dyed Details'));

start_table(TABLESTYLE, "width='95%'");
$th = array(_('Style Id'), _('Dyed Fab Code'), _('Fabric Desc'), _('UoM'), _('Tot St Items'), _('Qty/Pc'), _('Cut Waste %'), _('Total Qty'), _('Extra Qty %'), _('Req Qty'), _('Req by'));
table_header($th);

foreach ($_SESSION['plan_data'] as $key => $value) {
		start_row();
		label_cell($value['style_id']);
		label_cell($value['stock_id']);
		label_cell(get_description($value['stock_id']));
		$ini_qty = get_style_qty($order_no, $value['style_id']);
		label_cell(get_unit($value['stock_id']));
		qty_cell($ini_qty);
		qty_cell($value['perpc']);
		qty_cell($value['waste']);
	//Need to change perpc as per requirement
		$total_req = total_req($ini_qty, $value['perpc'], $value['waste']);
		qty_cell($total_req);
		qty_cell($value['stk_extra']);
		qty_cell($value['stk_total']);
		label_cell($value['req_date']);
		end_row();

}

end_table();
get_plan_data($order_no, 3 , true);

// echo "<br>";
display_heading2(_('Fabric Details'));
start_table(TABLESTYLE, "width='95%'");
$th = array(_('Style Id'), _('Greige Fab Code'), _('Fabric Desc'), _('UoM'), _('Tot Qlty Qty'), _('Dye Waste %'), _('Total Qty'), _('Ex Qty %'), _('Req Qty'), _('Req by'));
	table_header($th);
	start_row();
	foreach ($_SESSION['plan_data'] as $key => $value) {
		start_row();
		label_cell($value['style_id']);
		label_cell($value['stock_id']);
		label_cell(get_description($value['stock_id']));
		$ini_qty = get_cat_qty($order_no, $value['stock_id'], 4, 'qlty_id');
		label_cell(get_unit($value['stock_id']));
		qty_cell($ini_qty);
		// qty_cell($value['perpc']);
		qty_cell($value['waste']);
		// Need to change perpc as per requirement
		$total_req = total_req($ini_qty, $perpc, $value['waste']);
		qty_cell($total_req);
		qty_cell($value['stk_extra']);
		qty_cell($value['stk_total']);
		label_cell($value['req_date']);
		end_row();
	}
end_table();
get_plan_data($order_no, 1 , true);

echo '<br>';
display_heading2(_('Yarn Details'));
start_table(TABLESTYLE, "width='95%'");
$th = array(_('Style Id'), _('Yarn Code'), _('Yarn Desc'), _('UoM'), _('Tot Const Qty'), _('Knit Waste %'), _('Total Qty'), _('Ex Qty %'), _('Req Qty'), _('Req by'));
	table_header($th);
	start_row();
	foreach ($_SESSION['plan_data'] as $key => $value) {
		start_row();
		label_cell($value['style_id']);
		label_cell($value['stock_id']);
		label_cell(get_description($value['stock_id']));
		$ini_qty = get_cat_qty($order_no, $value['stock_id'], 3, 'const_id');
		label_cell(get_unit($value['stock_id']));
		qty_cell($ini_qty);
		// qty_cell($value['perpc']);
		qty_cell($value['waste']);
		// Need to change perpc as per requirement
		$total_req = total_req($ini_qty, $perpc, $value['waste']);
		qty_cell($total_req);
		qty_cell($value['stk_extra']);
		qty_cell($value['stk_total']);
		label_cell($value['req_date']);
		end_row();
	}
end_table();
get_plan_data($order_no, 5 , true);
echo '<br>';
display_heading2(_('Accessories Details (by Style)'));
start_table(TABLESTYLE, "width='95%'");
$th = array(_('Style Id'), _('Acs Code'), _('Acs Desc'), _('UoM'), _('Tot St Items'), _('Qty/Pc'), _('Waste %'), _('Total Qty'), _('Ex Qty %'), _('Req Qty'), _('Req by'),_('Image'));
table_header($th);
	start_row();
	foreach ($_SESSION['plan_data'] as $key => $value) {
		start_row();
		label_cell($value['style_id']);
		label_cell($value['stock_id']);
		label_cell(get_description($value['stock_id']));
		$ini_qty = get_style_qty($order_no, $value['style_id']);
		label_cell(get_unit($value['stock_id']));
		qty_cell($ini_qty);
		qty_cell($value['perpc']);
		qty_cell($value['waste']);
//Need to change perpc as per requirement
		$total_req = total_req($ini_qty, $value['perpc'], $value['waste']);
		qty_cell($total_req);
		qty_cell($value['stk_extra']);
		qty_cell($value['stk_total']);
		label_cell($value['req_date']);
		$ufilename = $value['ufilename'];
		$ufilename = str_replace(' ', '_', $ufilename);
		$stock_img_link = null;
		foreach (array('jpg', 'png', 'gif') as $ext) {
			$file = company_path().'/images/'. $ufilename .'.'.$ext;

			if (file_exists($file)) {
				$stock_img_link = "<img id='item_img' alt = 'no image found' src='".$file."?nocache=".rand()."'"." height='".$SysPrefs->pic_height."' border='0'>";
				break;
			}
		}
		label_cell( $stock_img_link);
		end_row();
	}
end_table();
get_plan_data($order_no, 5 , true, 1);
echo '<br>';
display_heading2(_('Accessories(By  Details (by Collection)'));
start_table(TABLESTYLE, "width='95%'");
$th = array(_('Acs Code'), _('Acs Desc'), _('UoM'), _('Tot St Items'), _('Qty/Pc'), _('Waste %'), _('Total Qty'), _('Ex Qty %'), _('Req Qty'), _('Req by'),_('Image'));
table_header($th);
	start_row();

	foreach ($_SESSION['plan_data'] as $key => $value) {
		start_row();
		// label_cell($value['style_id']);
		label_cell($value['stock_id']);
		label_cell(get_description($value['stock_id']));
		$ini_qty = get_col_detail($order_no,5);
		label_cell(get_unit($value['stock_id']));
		qty_cell($ini_qty);
		qty_cell($value['perpc']);
		qty_cell($value['waste']);
//Need to change perpc as per requirement
		$total_req = total_req($ini_qty, $value['perpc'], $value['waste']);
		qty_cell($total_req);
		qty_cell($value['stk_extra']);
		qty_cell($value['stk_total']);
		label_cell($value['req_date']);
		$ufilename = $value['ufilename'];
		$ufilename = str_replace(' ', '_', $ufilename);
		$stock_img_link = null;
		foreach (array('jpg', 'png', 'gif') as $ext) {
			$file = company_path().'/images/'. $ufilename .'.'.$ext;

			if (file_exists($file)) {
				$stock_img_link = "<img id='item_img' alt = 'no image found' src='".$file."?nocache=".rand()."'"." height='".$SysPrefs->pic_height."' border='0'>";
				break;
			}
		}
		label_cell( $stock_img_link);
		end_row();
	}
end_table();







end_page(true, false, false, $_GET['trans_type'], $_GET['trans_no']);