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
$maincat_id = 3;
hidden('maincat_id', $maincat_id);

if(isset($_POST['Delete'])){
	$Delete_key = $_POST['Delete_key'];
	unset($_SESSION['fabric_data'][$Delete_key]);
}

if(isset($_POST['update_item'])) {
    $edit_id = $_POST['edit_id'];
    foreach($_SESSION['fabric_data'] as $key => $value) {
        if($key == $edit_id) {
			$_SESSION['fabric_data'][$key]['stk_total'] = $_POST['stk_total'];
			$_SESSION['fabric_data'][$key]['waste'] = $_POST['gfwaste'];
			$cat_qty= get_cat_qty($order_no, $value['stock_id'], 4, 'qlty_id');
			$total_fabric = total_req($cat_qty, $_POST['gfwaste'] );
      		$_SESSION['fabric_data'][$key]['stk_extra'] = $_POST['gfstk_extra'];
			$_SESSION['fabric_data'][$key]['stk_total'] = net_req($total_fabric, $_POST['gfstk_extra']);
			$_SESSION['fabric_data'][$key]['req_date']  = $_POST['req_date'];
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
$fabric_data = array();

// Retrieve the existing array of data from the session variable
$existing_data = isset($_SESSION['fabric_data']) ? $_SESSION['fabric_data'] : array();

// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
$fabric_data['pp_id'] =null;

// Push the values of each form field into the array, including the new line number
$fabric_data['line_no'] = $next_line_no;
$fabric_data['style_id'] = $_POST['style_id'];
$fabric_data['stock_id'] = $_POST['stock_id'];
$fabric_data['maincat_id'] = $_POST['maincat_id'];
$fabric_data['perpc'] = 0;
$fabric_data['waste'] = $_POST['gfwaste'];
$fabric_data['dyedperpc'] = $_POST['dyedperpc'];
$fabric_data['stk_extra'] = $_POST['gfstk_extra'];
$fabric_data['t_style_qty']  = 0;
$fabric_data['description'] = get_description($_POST['stock_id']);
$fabric_data['units'] = get_unit($_POST['stock_id']);
$fabric_data['ufilename'] = '';
$fabric_data['stk_extra'] = $_POST['gfstk_extra'];
$fabric_data['stk_total'] = $_POST['stk_total'];
$fabric_data['req_date'] = $_POST['req_date'];

// Add the new form data to the existing array of data
$existing_data[] = $fabric_data;

// Store the updated array data in the session variable
$_SESSION['fabric_data'] = $existing_data;
display_notification(_('New order plan has been added'));

$Ajax->activate('items_table');
}

if(isset($_POST['add_plan'])){

	add_to_database($_SESSION['fabric_data'], $_POST['order_no'],$_POST['Comments'], $_POST['maincat_id']);
	display_notification(_('New order plan has been added'));
	get_fabric_data($order_no,$maincat_id);
}

if (isset($_POST['CancelItemChanges']))
	line_start_focus();

function edit(&$order,  $order_no, $line , $maincat_id) {
	global $Ajax;
	global $id;

	if ($id == $line && $line != -1) {
		foreach($order as $key=>$value){
			if($key == $line){
				hidden('edit_id', $key);
				$_POST['stock_id'] = $value['stock_id'];
				$_POST['perpc'] = $value['perpc'];
				$_POST['gfwaste'] = $value['waste'];
				$_POST['gfstk_extra'] = $value['stk_extra'];
				$_POST['stk_total'] = $value['stk_total'];
				$Ajax->activate('items_table');
				break;
			}
		}
		label_cells( null, $_POST['style_id']);
		$cat_qty= get_cat_qty($order_no, $_POST['stock_id'], 4, 'qlty_id');
		qty_cell($cat_qty);
		label_cells(null, $_POST['stock_id']);
		label_cells(null, get_description($_POST['stock_id']));
		$unit = get_unit($_POST['stock_id']);
		$total_fabric = total_req($cat_qty, $_POST['gfwaste'] );
		label_cell($unit);
		small_qty_cells_ex(null, 'gfwaste', 0,false);
		qty_cell($total_fabric);
		hidden('total_fabric', $total_fabric);
		small_qty_cells_ex(null, 'gfstk_extra', 0,false);
		$stk_total = net_req($total_fabric, $_POST['gfstk_extra']);
		qty_cell($stk_total);
		date_cells(null, 'req_date', null, null, 0, 0, 0, null, false);
		hidden('stk_total', $stk_total);
		hidden('total_fabric', $total_fabric);
	}
	else{
		stock_style_list_cells( 'style_id', null,  true,$order_no);
		$cat_qty= get_cat_qty($order_no, $_POST['stock_id'], 4, 'qlty_id');
		qty_cell($cat_qty);
		sales_items_list_cells(null,'stock_id', null, false, true, true, $maincat_id);
		$unit = get_unit($_POST['stock_id']);
		label_cell($unit);
		small_qty_cells_ex(null, 'gfwaste', 0,true);
		$total_fabric = total_req($cat_qty, $_POST['gfwaste'] );
		qty_cell($total_fabric);
		hidden('total_fabric', $total_fabric);
		small_qty_cells_ex(null, 'gfstk_extra', 0,true);
		$stk_total = net_req($total_fabric, $_POST['gfstk_extra']);
		qty_cell($stk_total);
		date_cells(null, 'req_date', null, null, 0, 0, 0, null, true);
		hidden('stk_total', $stk_total);
		hidden('total_fabric', $total_fabric);
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
display_heading("Plan Greige Fabrics Against Sales Order");
start_table(TABLESTYLE, "width='90%'");
			$th = array(_('Style Id'), _('Total Qty'), _('Greige Fab Code'), _('Fabric Desc'), _('UoM'), _('Dye Waste %'), _('Tot Greige Qty'), _('Ex Qty %'), _('Req Qty'), _('Req by'), '', '');
						table_header($th);
						start_row();
						$id = find_row('Edit');
						$editable_items = true;
						if ($id == -1 && $editable_items)
							edit($_SESSION['fabric_data'], $order_no, -1, $maincat_id);
						foreach ($_SESSION['fabric_data'] as $key => $value) {
							start_row();
							if($id != $key || !$editable_items){
								$des = get_description($value['stock_id']);
								$unit = get_unit($value['stock_id']);
								$cat_qty= get_cat_qty($order_no, $value['stock_id'], 4, 'qlty_id');
								$total_fabric = total_req($cat_qty, $value['waste'] );
								label_cell($value['style_id']);
								qty_cell($cat_qty);
								view_stock_status_cell($value['stock_id']);
								label_cell($des);
								label_cell($unit);
								qty_cell($value['waste']);
								qty_cell($total_fabric);
								qty_cell($value['stk_extra']);
								qty_cell($value['stk_total']);
								label_cell($value['req_date']);
								edit_button_cell('Edit'.$value['line_no'], _('Edit'), _('Edit document line'));
								delete_button_cell('Delete'.$value['line_no'], _('Delete'), _('Remove line from document'));
								if(isset($_POST['Delete'.$value['line_no']])){
									unset($_SESSION['fabric_data'][$key]);
									$Ajax->activate('items_table');

								}					end_row();

						}
						else{
							edit($_SESSION['fabric_data'], $order_no, $key, $maincat_id);
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
