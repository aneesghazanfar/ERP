<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
$page_security = 'SA_PURCHASEPLAN';
//----------------------------------------------------------------------------------------------
if (isset($_GET['OrderNumber']))
$order_no= $_GET['OrderNumber'];
else $order_no = $_POST['order_no'];
hidden('order_no', $order_no);
$maincat_id = 4;
hidden('maincat_id', $maincat_id);


if(isset($_POST['update_item'])) {
    $edit_id = $_POST['edit_id'];


    foreach($_SESSION['dyed_data'] as $key => $value) {
        if($key == $edit_id) {
            $_SESSION['dyed_data'][$key]['perpc'] = $_POST['dfperpc'];
			$_SESSION['dyed_data'][$key]['waste'] = $_POST['dfwaste'];
            $_SESSION['dyed_data'][$key]['stk_extra'] = $_POST['dfstk_extra'];
			$t_style_qty = get_order_qty($order_no, $value['style_id']);
			$dyedperpc = get_dyedperpc($t_style_qty, $_POST['dfperpc'], $_POST['dfwaste']);
			$_SESSION['dyed_data'][$key]['stk_total'] = total_required($t_style_qty, $dyedperpc, $_POST['dfstk_extra']);
			$_SESSION['dyed_data'][$key]['req_date'] = $_POST['req_date'];
			display_notification(_('Order plan has been updated'));

            break;
        }
    }
    // Unset the edit_id field to reset the form
    unset($_POST['edit_id']);
	$Ajax->activate('items_table');
}
if(isset($_POST['AddItem'])) {
   // Create an empty array to store the form data
$dyed_data = array();

// Retrieve the existing array of data from the session variable
$existing_data = isset($_SESSION['dyed_data']) ? $_SESSION['dyed_data'] : array();

// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
$dyed_data['pp_id'] =null;
// Push the values of each form field into the array, including the new line number
$dyed_data['line_no'] = $next_line_no;
$dyed_data['style_id'] = $_POST['style_id'];
$dyed_data['stock_id'] = $_POST['stock_id'];
$dyed_data['maincat_id'] = $_POST['maincat_id'];
$dyed_data['perpc'] = $_POST['dfperpc'];
$dyed_data['waste'] = $_POST['dfwaste'];
$dyed_data['dyedperpc'] = $_POST['dyedperpc'];
$dyed_data['stk_extra'] = $_POST['dfstk_extra'];
$dyed_data['t_style_qty']  = $_POST['t_style_qty'];
$dyed_data['description'] = get_description($_POST['stock_id']);
$dyed_data['units'] = get_unit($_POST['stock_id']);
$dyed_data['stk_extra'] = $_POST['dfstk_extra'];
$dyed_data['stk_total'] = $_POST['stk_total'];
$dyed_data['req_date'] = $_POST['req_date'];
$dyed_data['ufilename'] = '';
// Add the new form data to the existing array of data
$existing_data[] = $dyed_data;
// Store the updated array data in the session variable
$_SESSION['dyed_data'] = $existing_data;
display_notification(_('New order plan has been added'));
$Ajax->activate('items_table');
}
if(isset($_POST['add_plan'])){
	add_to_database($_SESSION['dyed_data'], $_POST['order_no'],$_POST['Comments'], $_POST['maincat_id']);
	display_notification(_('New order plan has been added'));
	get_dyed_data($order_no,$maincat_id);
}
function line_start_focus() {
	global 	$Ajax;
	$Ajax->activate('items_table');
}
if (isset($_POST['CancelItemChanges']))
	line_start_focus();
function edit_dyed(&$order,  $order_no, $line , $maincat_id) {
	global $Ajax;
	global $id;
	if ($id == $line && $line != -1) {
		foreach($order as $key=>$value){
			if($key == $line){
				hidden('edit_id', $key);
				$_POST['style_id'] = $value['style_id'];
				$_POST['stock_id'] = $value['stock_id'];
				$_POST['dfperpc'] = $value['perpc'];
				$_POST['dfwaste'] = $value['waste'];
				$_POST['dfstk_extra'] = $value['stk_extra'];
				$_POST['stk_total'] = $value['stk_total'];
				$_POST['req_date'] = $value['req_date'];
				$Ajax->activate('items_table');
				break;
			}
		}
		label_cells( null, $_POST['style_id']);
		$_POST['t_style_qty'] = get_order_qty($order_no, $_POST['style_id']);
		qty_cell($_POST['t_style_qty']);
		label_cells(null, $_POST['stock_id']);
		label_cells(null,get_description($_POST['stock_id']));
		$unit = get_unit($_POST['stock_id']);
		label_cell($unit);
		small_qty_cells_ex(null, 'dfperpc', 1, true);
		small_qty_cells_ex(null, 'dfwaste', 0, true);

		$dyedperpc = get_dyedperpc($_POST['t_style_qty'], $_POST['dfperpc'], $_POST['dfwaste']);
		qty_cell($dyedperpc);
		small_qty_cells_ex(null, 'dfstk_extra', 0);

		$stk_total = total_required($_POST['t_style_qty'], $dyedperpc, $_POST['dfstk_extra']);
		qty_cell($stk_total);
		hidden('stk_total', $stk_total);
		date_cells(null, 'req_date', null, null, 0, 0, 0, null, false);
	}
	else{

		stock_style_list_cells( 'style_id', null,  true,$order_no);
		$_POST['t_style_qty'] = get_order_qty($order_no, $_POST['style_id']);
		qty_cell($_POST['t_style_qty']);
		sales_items_list_cells(null,'stock_id', null, false, true, true, $maincat_id);
		$unit = get_unit($_POST['stock_id']);
		label_cell($unit);
		small_qty_cells_ex(null, 'dfperpc', 1,true);
		small_qty_cells_ex(null, 'dfwaste', 0,true);
		$dyedperpc = get_dyedperpc($_POST['t_style_qty'], $_POST['dfperpc'], $_POST['dfwaste']);
		qty_cell($dyedperpc);
		hidden('dyedperpc', $dyedperpc);
		small_qty_cells_ex(null, 'dfstk_extra', 0,true);

		$stk_total = total_required($_POST['t_style_qty'], $dyedperpc, $_POST['dfstk_extra']);
		qty_cell($stk_total);
		hidden('stk_total', $stk_total);
		date_cells(null, 'req_date', null, null, 0, 0, 0, null, true);
		$Ajax->activate('items_table');
	}
if ($id != -1) {
	button_cell('update_item', _('Update'), _('Confirm changes'), ICON_UPDATE);
	button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
}
else{
	submit_cells('AddItem', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
}
end_row();
}
start_form(true);
div_start('items_table');
display_heading("Plan Dyed Fabrics Against Sales Order");
start_table(TABLESTYLE, "width='90%'");
			$th = array(_('Style Id'), _('Total Qty'), _('Dyed Fab Code'), _('Fabric Desc'), _('UoM'), _('Qty/Pc'), _('Cut Waste %'), _('Dyed Fab/PC'), _('Extra Qty %'), _('Req Qty'), _('Req by'), '', '');
			table_header($th);
			start_row();
			$id = find_row('Edit');
			$editable_items = true;
			if ($id == -1 && $editable_items)
			edit_dyed($_SESSION['dyed_data'], $order_no, -1, $maincat_id);
			foreach ($_SESSION['dyed_data'] as $key => $value) {
				start_row();
				if($id != $key || !$editable_items){
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
					edit_button_cell('Edit'.$value['line_no'], _('Edit'), _('Edit document line'));
					delete_button_cell('Delete'.$value['line_no'], _('Delete'), _('Remove line from document'));
					if(isset($_POST['Delete'.$value['line_no']])){
						unset($_SESSION['dyed_data'][$key]);
						$Ajax->activate('items_table');
					}
					end_row();
			}
			else{
				edit_dyed($_SESSION['dyed_data'], $order_no, $key, $maincat_id);
			}
	}
end_table(1);
$old_comments = get_plan_comments($order_no,$maincat_id);
start_table(TABLESTYLE2);
textarea_row(_('Remarks:'), 'Comments', $old_comments, 70, 4);
end_table(1);
submit_center_first('add_plan',_('Place Plan'),  _('Check entered data and save document'), 'default');
div_end();
end_form();
