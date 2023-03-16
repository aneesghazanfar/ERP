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
$maincat_id = 1;
$maincat_id_2 = 2;
hidden('maincat_id', $maincat_id);
hidden('maincat_id_2', $maincat_id_2);

if(isset($_POST['Delete'])){
	$Delete_key = $_POST['Delete_key'];
	unset($_SESSION['yarn_data'][$Delete_key]);
}

if(isset($_POST['update_item'])) {
    $edit_id = $_POST['edit_id'];

    foreach($_SESSION['yarn_data'] as $key => $value) {
        if($key == $edit_id) {
      		$_SESSION['yarn_data'][$key]['stk_extra'] = $_POST['yan_stk_extra'];
			$_SESSION['yarn_data'][$key]['waste'] = $_POST['yan_waste'];
			$get_total_net_qty= get_total_net_qty($order_no, 3);
			$_SESSION['yarn_data'][$key]['stk_total'] = total_required_yarn($get_total_net_qty,$_POST['yan_waste'], $_POST['yan_stk_extra']);
			$_SESSION['yarn_data'][$key]['req_date'] = $_POST['req_date'];
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
$yarn_data = array();

// Retrieve the existing array of data from the session variable
$existing_data = isset($_SESSION['yarn_data']) ? $_SESSION['yarn_data'] : array();

// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
$yarn_data['pp_id'] =null;

// Push the values of each form field into the array, including the new line number
$yarn_data['line_no'] = $next_line_no;
$yarn_data['style_id'] = $_POST['style_id'];
$yarn_data['stock_id'] = $_POST['stock_id'];
$yarn_data['maincat_id'] = $_POST['maincat_id'];
$yarn_data['maincat_id_2'] = $_POST['maincat_id_2'];
$yarn_data['perpc'] = 0;
$yarn_data['waste'] = $_POST['yan_waste'];
$yarn_data['dyedperpc'] = $_POST['dyedperpc'];
$yarn_data['stk_extra'] = $_POST['yan_stk_extra'];
$yarn_data['t_style_qty']  = $_POST['t_style_qty'];
$yarn_data['description'] = get_description($_POST['stock_id']);
$yarn_data['units'] = get_unit($_POST['stock_id']);
$yarn_data['ufilename'] = $_POST['ufilename'];
$yarn_data['stk_extra'] = $_POST['yan_stk_extra'];
$yarn_data['stk_total'] = $_POST['stk_total'];
$yarn_data['req_date'] = $_POST['req_date'];
$yarn_data['ufilename'] = $_POST['ufilename'];

// Add the new form data to the existing array of data
$existing_data[] = $yarn_data;

// Store the updated array data in the session variable
$_SESSION['yarn_data'] = $existing_data;
display_notification(_('New order plan has been added'));

$Ajax->activate('items_table');
}

if(isset($_POST['add_plan'])){
	add_to_database($_SESSION['yarn_data'], $_POST['order_no'],$_POST['Comments'], $_POST['maincat_id'], $_POST['maincat_id_2']);
	display_notification(_('New order plan has been added'));
	get_yarn_data($_POST['order_no'], $maincat_id, $maincat_id_2);
}

function line_start_focus() {
	global 	$Ajax;

	$Ajax->activate('items_table');
}

if (isset($_POST['CancelItemChanges']))
	line_start_focus();

function edit(&$order,  $order_no, $line , $maincat_id, $maincat_id_2) {
	global $Ajax;
	global $id;

	if ($id == $line && $line != -1) {
		foreach($order as $key=>$value){
			if($key == $line){
				hidden('edit_id', $key);
				$_POST['stock_id'] = $value['stock_id'];
				$_POST['perpc'] = $value['perpc'];
				$_POST['yan_waste'] = $value['waste'];
				$_POST['yan_stk_extra'] = $value['stk_extra'];
				$_POST['stk_total'] = $value['stk_total'];
				$Ajax->activate('items_table');
				break;
			}
		}
			label_cells( null, $_POST['style_id']);
			// $_POST['t_style_qty'] = get_order_qty($order_no, $_POST['style_id']);
			// qty_cell($_POST['t_style_qty']);
			$get_total_net_qty= get_total_net_qty($order_no, 3);
			qty_cell($get_total_net_qty);
			label_cells(null, $_POST['stock_id']);
			label_cells(null, get_description($_POST['stock_id']));
			$unit = get_unit($_POST['stock_id']);
			$total_yarn = total_required_yarn($get_total_net_qty,$_POST['yan_waste'], $_POST['yan_stk_extra']);
			label_cell($unit);
			small_qty_cells_ex(null, 'yan_waste', 0,false);
			qty_cell($total_yarn);
			small_qty_cells_ex(null, 'yan_stk_extra', 0,false);
			$stk_total = total_required_yarn($get_total_net_qty,$_POST['yan_waste'], $_POST['yan_stk_extra']);
			qty_cell($stk_total );
			date_cells(null, 'req_date', null, null, 0, 0, 0, null, false);
			hidden('stk_total', $stk_total);
	}
	else{
			stock_style_list_cells( 'style_id', $_POST['style_id'],  true,$order_no);
			// $_POST['t_style_qty'] = get_order_qty($order_no, $_POST['style_id']);
			// qty_cell($_POST['t_style_qty']);
			$get_total_net_qty= get_total_net_qty($order_no, 3);
			qty_cell($get_total_net_qty);
			sales_items_list_cells(null,'stock_id', $_POST['stock_id'], false, true, true , $maincat_id	,$maincat_id_2);
			$unit = get_unit($_POST['stock_id']);
			$total_yarn = total_required_yarn($get_total_net_qty,$_POST['yan_waste'], $_POST['yan_stk_extra']);
			label_cell($unit);
			small_qty_cells_ex(null, 'yan_waste', 0,true);
			qty_cell($total_yarn);
			small_qty_cells_ex(null, 'yan_stk_extra', 0,true);
			$stk_total = total_required_yarn($get_total_net_qty,$_POST['yan_waste'], $_POST['yan_stk_extra']);
			qty_cell($stk_total);
			date_cells(null, 'req_date', null, null, 0, 0, 0, null, true);
			hidden('stk_total', $stk_total);
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
display_heading("Plan Yarn Against Sales Order");
start_table(TABLESTYLE, "width='90%'");
			$th = array(_('Style Id'),_('Total Qty'), _('Yarn Code'), _('Yarn Desc'), _('UoM'), _('Knit Waste %'), _('Tot Qty %'), _('Extra Qty %'), _('Req Qty'), _('Req by'), '', '');
			table_header($th);
			start_row();
			global $SysPrefs;
			$id = find_row('Edit');

			$editable_items = true;
			if ($id == -1 && $editable_items)
				edit($_SESSION['yarn_data'], $order_no, -1, $maincat_id,$maincat_id_2);

			foreach ($_SESSION['yarn_data'] as $key => $value) {
				start_row();
				if($id != $key || !$editable_items){
					$des = get_description($value['stock_id']);
					$unit = get_unit($value['stock_id']);
					$get_total_net_qty= get_total_net_qty($order_no, 3);
					$total_yarn = total_required_yarn($get_total_net_qty,$value['waste'], $value['stk_extra']);
					// $t_style_qty = get_order_qty($order_no, $value['style_id']);
					label_cell($value['style_id']);
					qty_cell($get_total_net_qty);
					// qty_cell($t_style_qty);
					view_stock_status_cell($value['stock_id']);
					label_cell($des);
					label_cell($unit);
					qty_cell($value['waste']);
					qty_cell($total_yarn);
					qty_cell($value['stk_extra']);
					qty_cell($value['stk_total']);
					label_cell($value['req_date']);
					edit_button_cell('Edit'.$value['line_no'], _('Edit'), _('Edit document line'));
					delete_button_cell('Delete'.$value['line_no'], _('Delete'), _('Remove line from document'));
					if(isset($_POST['Delete'.$value['line_no']])){
						unset($_SESSION['yarn_data'][$key]);
						$Ajax->activate('items_table');
					}
					end_row();
			}
			else{
				edit($_SESSION['yarn_data'], $order_no, $key, $maincat_id,$maincat_id_2);
			}
	}
end_table(1);
$old_comments = get_plan_comments($order_no,$maincat_id,$maincat_id_2);
start_table(TABLESTYLE2);
textarea_row(_('Remarks:'), 'Comments', $old_comments, 70, 4);
end_table(1);
submit_center_first('add_plan',_('Place Plan'),  _('Check entered data and save document'), 'default');
div_end();
end_form();
