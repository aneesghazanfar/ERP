<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
$page_security = 'SA_SALESORDER';
$path_to_root = '..';
include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/ui.inc');
include($path_to_root.'/purchasing/includes/db/so_plan_db.inc');
include($path_to_root.'/purchasing/includes/ui/so_plan_ui.inc');
include_once($path_to_root.'/sales/includes/ui/cost_sheet_ui.inc');
include_once($path_to_root.'/includes/ui/ui_lists.inc');
include_once($path_to_root.'/sales/includes/db/cost_sheet_db.inc');
$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'Cost Sheet'), @$_REQUEST['popup'], false, '', $js);
if (isset($_GET['cc_id'])){
	$cc_id= $_GET['cc_id'];
	unset($_SESSION['cost_data']);
}
//Header Table----------------------------------------------------------------------------------------------
start_table(TABLESTYLE, "width=50%");
start_row();
label_cells(_('Form No.'), $cc_id);
shipping_terms(_("Shipping Terms:") , 'shipping_terms');
text_cells(_('Status.'), 'status', null, 21,5 , null, '');
end_row();
start_row();
label_cell("Style");
stock_style_list_cells(_("Style:"), 'style', null, true, true);
label_cells(_('Date'), date('Y-m-d'), 0, 0, 0, null, true);
label_cells(_('User'), $_SESSION['wa_current_user']->user, 0, 0, 0, null, true);
end_row();
start_row();
textarea_cells(_('Special Instructions:'), 'sp_ins', null, 30, 3);
file_cells(null, 'image','image');
end_row();
end_table(1); 
//----------------------------------------------------------------------------------------------------------
//Fabric Cost Table-----------------------------------------------------------------------------------------
if(isset($_POST['AddItem'])) {
	// $unset = false;
	// Create an empty array to store the form data
	$cost_data = array();
	
	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['cost_data']) ? $_SESSION['cost_data'] : array();
	
	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$cost_data['id'] =null;
	
	// Push the values of each form field into the array, including the new line number
	$cost_data['line_no'] = $next_line_no;
    $cost_data['maincat_id'] = $_POST['maincat_id'];
    $cost_data['cs_id'] = $_POST['cs_id'];
    $cost_data['stk_code'] = $_POST['stk_code'];
    $cost_data['description'] = get_description($_POST['stk_code']);
    $cost_data['units'] = get_unit($_POST['stk_code']);
    $cost_data['waste'] = $_POST['waste'];
    $cost_data['consumption'] = $_POST['consumption'];
    $cost_data['rate'] = $_POST['rate'];
    $cost_data['amount'] = amount();

	// Add the new form data to the existing array of data
	$existing_data[] = $cost_data;

	// Store the updated array data in the session variable
	$_SESSION['cost_data'] = $existing_data;
	$Ajax->activate('items_table');
}

if(isset($_POST['update_item'])) {
	$edit_id = $_POST['edit_id'];
	foreach($_SESSION['cost_data'] as $key => $value) {
		if($key == $edit_id) {
			$_SESSION['cost_data'][$key]['consumption'] = $_POST['consumption'];
			$_SESSION['cost_data'][$key]['rate'] = $_POST['rate'];
		}
	}
	$Ajax->activate('items_table');
}
function line_start_focus() {
	global 	$Ajax;

	$Ajax->activate('items_table');
}

if (isset($_POST['CancelItemChanges']))
	line_start_focus();
	

function edit_yan(&$order,  $line, $maincat_id , $maincat_id_2) {
	global $Ajax;
	global $id;
	
	if ($id == $line && $line != -1) {
		foreach($order as $key=>$value){
			if($key == $line){
				hidden('edit_id', $key);
				$_POST['stk_code'] = $value['stk_code'];
				$_POST['consumption'] = $value['consumption'];
				$_POST['rate'] = $value['rate'];
				$Ajax->activate('items_table');
				break;
			}
		}
		label_cell($_POST['stk_code']);
		label_cell(get_description($_POST['stk_code']));
		label_cell(get_unit($_POST['stk_code']));
		small_qty_cells_ex(null, 'consumption', 0, false);
		small_qty_cells_ex(null, 'rate', 0, false);
		qty_cell(amount());
	}
	else{
		plan_sales_items_list_cells(null,'stk_code', null, false, true, true, $maincat_id,$maincat_id_2);
		label_cell(get_unit($_POST['stk_code']));
		small_qty_cells_ex(null, 'consumption', 0, false);
		small_qty_cells_ex(null, 'rate', 0, false);
		qty_cell(amount());

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
start_table(TABLESTYLE, "width=50%");
get_cost_data(1);
$th = array( _('Yarn Code'), _('Yarn Desc'), _('UoM'), _('Percentage Consumption'), _('Yarn Rate/Bag'), _('Amount'), '', '');
table_header($th);
	start_row();
		$id = find_row('Edit');
		$yan_maincat = 1;
		$yan_maincat_2 = 2;
		$editable_items = true;
		// var_dump($_POST);
		if ($id == -1 && $editable_items)
			edit_yan($_SESSION['cost_data'],  -1, $yan_maincat , $yan_maincat_2);
			foreach ($_SESSION['cost_data'] as $key => $value) {
				// echo "id = $id " . "key = $key <br>";
				start_row();
				if($id != $key || !$editable_items){
				label_cell($value['stk_code']);
				label_cell(get_description($value['stk_code']));
				label_cell(get_unit($value['stk_code']));
				qty_cell($value['consumption']);
				qty_cell($value['rate']);
				qty_cell(amount());
				edit_button_cell('Edit'.$value['line_no'], _('Edit'), _('Edit document line'));
				delete_button_cell('Delete'.$value['line_no'], _('Delete'), _('Remove line from document'));
				if(isset($_POST['Delete'.$value['line_no']])){
					// unset($_SESSION['cost_data'][$key]);
					$Ajax->activate('items_table');
				}
				end_row();
		}
		else{
			edit_yan($_SESSION['cost_data'], $key, $yan_maincat , $yan_maincat_2);
		}
	}
	start_row();
		label_cells(_('Yarn Cost'), amount(), "colspan=5 align='right'");
	end_row();
	start_row();
		label_cells(_('Knitting Charges/Bag'), knitt_bag_formula(), "colspan=5 align='right'");
	end_row();
	start_row();
	small_qty_cells_ex(_('Knitting Charges'), total_formula(),'',true, "colspan=5 align='right'");
	end_row();
	start_row();
		label_cells(_('Ecru Fabric Cost/kg'), total_cost(), "colspan=5 align='right'");
	end_table(1);
// footer start -----------------------------------------------------------------------
start_table(TABLESTYLE, "width=20%");

label_row(_('Net Manufacturing Cost'), total_cost(),true);
small_qty_row(_('Local Freight Charges'), total_formula(),'',true);
small_qty_row(_('Container Freight'), total_formula(),'',true);
small_qty_row(_('Insurance Charges'), total_formula(),'',true);
label_row(_('Total Price per Piece'), total_cost(),true);

end_table(1);


div_end();
end_form();
end_page();
