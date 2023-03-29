<?php
/**********************************************************************
 Copyright (C) ASMIte Inc.
 contact@march7.group
 ***********************************************************************/
$page_security = 'SA_PURCHASEPLAN';
//----------------------------------------------------------------------------------------------
hidden('order_no', $order_no);
$maincat_id = 3;
hidden('maincat_id', $maincat_id);
$unset = true;

$lineNo = find_submit('Edit');

if(isset($_POST['Edit'.$lineNo])){
	$unset = false;
}
	
	
	
if(isset($_POST['Delete'])){
	$Delete_key = $_POST['Delete_key'];
	unset($_SESSION['plan_data'][$Delete_key]);
}
//update after edit
if(isset($_POST['update_item'])) {
	$edit_id = $_POST['edit_id'];
	foreach($_SESSION['plan_data'] as $key => $value) {
		if($key == $edit_id) {
			$ini_qty= get_cat_qty($order_no, $value['stock_id'], 4, 'qlty_id');
			//					$_SESSION['plan_data'][$key]['perpc'] = $_POST['perpc'];
			$_SESSION['plan_data'][$key]['waste'] = $_POST['waste'];
			$total_req = total_req($ini_qty, $_POST['perpc'], $_POST['waste'] );
			$_SESSION['plan_data'][$key]['stk_extra'] = $_POST['stk_extra'];
			$_SESSION['plan_data'][$key]['stk_total'] = net_req($total_req, $_POST['stk_extra']);
			$_SESSION['plan_data'][$key]['req_date']  = $_POST['req_date'];
			//					$_SESSION['plan_data'][$key]['ufilename']  = 'ufilename';
			display_notification(_('Order plan has been updated'));
			$unset = false;
			break;
		}
	}
	
	// Unset the edit_id field to reset the form
	unset($_POST['edit_id']);
	$Ajax->activate('items_table');
}


	
if(isset($_POST['AddItem'])) {
	$unset = false;
	// Create an empty array to store the form data

	$plan_data = array();
	
	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['plan_data']) ? $_SESSION['plan_data'] : array();
	
	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$plan_data['pp_id'] =null;
	
	// Push the values of each form field into the array, including the new line number
	$plan_data['line_no'] = $next_line_no;
	$plan_data['maincat_id'] = $_POST['maincat_id'];
	$plan_data['style_id'] = $_POST['style_id'];
	$plan_data['stock_id'] = $_POST['stock_id'];
	$plan_data['description'] = get_description($_POST['stock_id']);
	$plan_data['ini_qty']  = get_cat_qty($order_no, $_POST['stock_id'], 4, 'qlty_id');
	$plan_data['units'] = get_unit($_POST['stock_id']);
	$plan_data['perpc'] = 0;
	$plan_data['perpc'] = 1;
	$plan_data['waste'] = $_POST['waste'];
	$plan_data['total_req']  = total_req($plan_data['ini_qty'], 1, $_POST['waste'] );
	$plan_data['stk_extra'] = $_POST['stk_extra'];
	//$plan_data['stk_total'] = $_POST['stk_total'];
	$plan_data['stk_total'] = net_req($plan_data['total_req'], $_POST['stk_extra']);
	$plan_data['req_date'] = $_POST['req_date'];
	$plan_data['ufilename'] = '';

	// Add the new form data to the existing array of data
	$existing_data[] = $plan_data;

	// Store the updated array data in the session variable
	$_SESSION['plan_data'] = $existing_data;
	$Ajax->activate('items_table');
}

if(isset($_POST['add_plan'])){
	add_to_database($_SESSION['plan_data'], $order_no, $_POST['comment'], $maincat_id);
	display_notification(_('New order plan has been added'));
	get_plan_data($order_no, $maincat_id,true);
}



if (isset($_POST['CancelItemChanges']))
	line_start_focus();
	
function edit(&$order, $order_no, $line, $maincat_id) {
	global $Ajax;
	global $id;
	
	if ($id == $line && $line != -1) {
		foreach($order as $key=>$value){
			if($key == $line){
				hidden('edit_id', $key);
				$_POST['style_id'] = $value['style_id'];
				$_POST['stock_id'] = $value['stock_id'];
				$_POST['perpc'] = $value['perpc'];
				$_POST['waste'] = $value['waste'];
				$_POST['stk_extra'] = $value['stk_extra'];
				$_POST['stk_total'] = $value['stk_total'];
				$_POST['ufilename'] = $value['ufilename'];
				break;
			}
		}
		label_cell($_POST['style_id']);
		$ini_qty= get_cat_qty($order_no, $_POST['stock_id'], 4, 'qlty_id');
		label_cell($_POST['stock_id']);
		label_cell(get_description($_POST['stock_id']));
		label_cell(get_unit($_POST['stock_id']));
		qty_cell($ini_qty);
		//		small_qty_cells_ex(null, 'perpc', 0,false);
		small_qty_cells_ex(null, 'waste', 0,false);
		$perpc =1;
		$total_req = total_req($ini_qty, $perpc, $_POST['waste']);
		qty_cell($total_req);
		small_qty_cells_ex(null, 'stk_extra', 0, false);
		$stk_total = net_req($total_req, $_POST['stk_extra']);
		qty_cell($stk_total);
		date_cells(null, 'req_date', null, null, 0, 0, 0, null, false);
		//		file_cells(null, 'image','image');
	}
	else{
		stock_style_list_cells( 'style_id', null,  true,$order_no);
		plan_sales_items_list_cells(null,'stock_id', null, false, true, true, $maincat_id);
		$ini_qty= get_cat_qty($order_no, $_POST['stock_id'], 4, 'qlty_id');
		hidden('ini_qty', $ini_qty);
		label_cell(get_unit($_POST['stock_id']));
		qty_cell($ini_qty);
//		small_qty_cells_ex(null, 'perpc', 0,false);
		small_qty_cells_ex(null, 'waste', null, true);
		//need to change $perpc as per requirement
		$perpc =1;
		$total_req = total_req($ini_qty, $perpc, $_POST['waste']);
		qty_cell($total_req);
		hidden('total_req', $total_req);
		small_qty_cells_ex(null, 'stk_extra', 0, true);
		$stk_total = net_req($total_req, $_POST['stk_extra']);
		qty_cell($stk_total);
		hidden('stk_total', $stk_total);
		date_cells(null, 'req_date', null, null, 0, 0, 0, null, true);
		//		file_cells(null, 'image','image');
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
if((list_updated('style_id') || list_updated('stock_id')) && (isset($_POST['waste']) >= 0)){
	$unset = false;
}
get_plan_data($order_no, $maincat_id , $unset);
display_heading("Plan Greige Fabrics Against Sales Order");
start_table(TABLESTYLE, "width='90%'");
$th = array(_('Style Id'), _('Greige Fab Code'), _('Fabric Desc'), _('UoM'), _('Tot Qlty Qty'), _('Dye Waste %'), _('Total Qty'), _('Ex Qty %'), _('Req Qty'), _('Req by'), '', '');
						table_header($th);
						start_row();
						$id = find_row('Edit');
						$editable_items = true;
						if ($id == -1 && $editable_items)
							edit($_SESSION['plan_data'], $order_no, -1, $maincat_id);
							foreach ($_SESSION['plan_data'] as $key => $value) {
								start_row();
								if($id != $key || !$editable_items){
								label_cell($value['style_id']);
								label_cell($value['stock_id']);
								label_cell(get_description($value['stock_id']));
								$ini_qty = get_cat_qty($order_no, $value['stock_id'], 4, 'qlty_id');
								label_cell(get_unit($value['stock_id']));
								qty_cell($ini_qty);
//								qty_cell($value['perpc']);
								qty_cell($value['waste']);
//Need to change perpc as per requirement
								$perpc =1;
								$total_req = total_req($ini_qty, $perpc, $value['waste']);
								qty_cell($total_req);
								qty_cell($value['stk_extra']);
								qty_cell($value['stk_total']);
								label_cell($value['req_date']);
//								label_cell($value['ufilename']);
								edit_button_cell('Edit'.$value['line_no'], _('Edit'), _('Edit document line'));
								delete_button_cell('Delete'.$value['line_no'], _('Delete'), _('Remove line from document'));
								if(isset($_POST['Delete'.$value['line_no']])){
									unset($_SESSION['plan_data'][$key]);
									$Ajax->activate('items_table');
								}
								end_row();
						}
						else{
							edit($_SESSION['plan_data'], $order_no, $key, $maincat_id);
						}
				}
			end_table(1);
			$comment = get_plan_comments($order_no, $maincat_id);
			start_table(TABLESTYLE2);
			textarea_row(_('Remarks:'), 'comment', $comment, 70, 4);
			end_table(1);
submit_center_first('add_plan',_('Place Plan'),  _('Check entered data and save document'), 'default');
div_end();
end_form();
