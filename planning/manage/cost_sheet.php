<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
$page_security = 'SA_SALESORDER';
$path_to_root = '../..';
include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/ui.inc');
include($path_to_root . '/planning/includes/db/so_plan_db.inc');
include($path_to_root . '/planning/includes/ui/so_plan_ui.inc');
include_once($path_to_root . '/planning/includes/ui/cost_sheet_ui.inc');
include_once($path_to_root . '/planning/includes/db/cost_sheet_db.inc');
include_once($path_to_root . '/includes/ui/ui_lists.inc');


$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'Manage Purchase Plans'), @$_REQUEST['popup'], false, '', $js);


$_SESSION['fab_data'];
$unset = true;
if (list_updated('ystk_code') || list_updated('accstk_code') || isset($_REQUEST['1_dye_stk_code']) || isset($_REQUEST['2_dye_stk_code'])
|| isset($_REQUEST['3_dye_stk_code']) || isset($_REQUEST['4_dye_stk_code']) || isset($_REQUEST['5_dye_stk_code'])
|| isset($_REQUEST['shipping_terms']) || isset($_REQUEST['style']) || isset($_REQUEST['currency'])){
	$unset = false;
}
get_cost_data($unset);
$lineNo = find_submit('Edit');

if(isset($_POST['Edit'.$lineNo])){
	$unset = false;
}

$cs_id = get_cs_id();
hidden('cs_id', $cs_id);
$dfab_cost = 0;
simple_page_mode(true);
//function-------------------------------------------------------------------------------------------
//add to database------------------------------------------------------------------------------------------------
if (isset($_POST['add_Cost'])){
	add_cost_data_to_db($_SESSION['cost_data'], $_SESSION['fab_data'], $cs_id, $_POST['style'], $_POST['shipping_terms'], $_POST['sp_ins'],
	$_POST['total_labor_cost'], $_POST['overhead_cost'], $_POST['local_freight'], $_POST['container_freight'] , $_POST['insurance']
	, $_POST['com_persentage'], $_POST['pro_persentage'], $_POST['currency'], $_POST['exchange_rate'], $_POST['ufilename']
	,$_SESSION['wa_current_user']->user );
}
//upload image--------------------------------------------------------------------------------------------------
if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
	$order_no = $_POST['order_no'];
	$result = $_FILES['image']['error'];
	$upload_file = 'Yes'; //Assume all is well to start off with
	$filename = company_path().'/images';

	if (!file_exists($filename))
	mkdir($filename);
	$fname = "image_". get_cs_id();
	hidden('ufilename', $fname);

	$filename .= '/'. item_img_name($fname).(substr(trim($_FILES['image']['name']), strrpos($_FILES['image']['name'], '.')));


	if ($_FILES['image']['error'] == UPLOAD_ERR_INI_SIZE) {
		display_error(_('The file size is over the maximum allowed.'));
		$upload_file = 'No';
	}
	elseif ($_FILES['image']['error'] > 0) {
		display_error(_('Error uploading file.'));
		$upload_file = 'No';
	}

	//But check for the worst
	if ((list($width, $height, $type, $attr) = getimagesize($_FILES['image']['tmp_name'])) !== false)
	$imagetype = $type;
	else
	$imagetype = false;

	if ($imagetype != IMAGETYPE_GIF && $imagetype != IMAGETYPE_JPEG && $imagetype != IMAGETYPE_PNG) {
		display_warning( _('Only graphics files can be uploaded'));
		$upload_file = 'No';
	}
	elseif (!in_array(strtoupper(substr(trim($_FILES['image']['name']), strlen($_FILES['image']['name']) - 3)), array('JPG','PNG','GIF'))) {
		display_warning(_('Only graphics files are supported - a file extension of .jpg, .png or .gif is expected'));
		$upload_file = 'No';
	}
	elseif ( $_FILES['image']['size'] > ($SysPrefs->max_image_size * 1024)) { //File Size Check
		display_warning(_('The file size is over the maximum allowed. The maximum size allowed in KB is').' '.$SysPrefs->max_image_size);
		$upload_file = 'No';
	}
	elseif ( $_FILES['image']['type'] == 'text/plain' ) {  //File type Check
		display_warning( _('Only graphics files can be uploaded'));
		$upload_file = 'No';
	}

	if ($upload_file == 'Yes') {
		$result  =  move_uploaded_file($_FILES['image']['tmp_name'], $filename);
		$upload_file = 'No';
	}
}
//add and update yarn-------------------------------------------------------------------------------------------------
// add yarn
if(isset($_POST['Addyarn'])){
	$unset = false;
	// unset($_SESSION['cost_data']);

	// Create an empty array to store the cost data
	$cost_data = array();

	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['cost_data']) ? $_SESSION['cost_data'] : array();

	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$cost_data['id'] =null;

	// Push the values of each form field into the array, including the new line number
	$cost_data['line_no'] = $next_line_no;
	
	$cost_data['cs_id'] = $cs_id;
	$cost_data['maincat_id'] = 1;
	$cost_data['fab_id'] = $_POST['fab_id'];
	$cost_data['stk_code'] = $_POST['ystk_code'];
	$cost_data['consume'] = $_POST['yconsumption'];
	$cost_data['rate'] = $_POST['yrate'];
	$cost_data['processing'] = 'Yarn';

	$cost_data['waste'] = 0;
	$existing_data[] = $cost_data;

	// Store the updated array data in the session variable
	$_SESSION['cost_data'] = $existing_data;
	$Ajax->activate('items_table');
}
// update yarn
if(isset($_POST['update_yarn'])) {
	$edit_id = $_POST['edit_id'];
	foreach($_SESSION['cost_data'] as $key => $value) {
		if($key == $edit_id) {
			$_SESSION['cost_data'][$key]['rate'] = $_POST['yrate'];
			$_SESSION['cost_data'][$key]['consume'] = $_POST['yconsumption'];
			display_notification(_('Order plan has been updated'));
			$unset = false;
			break;

		}
	
	}
	unset($_POST['edit_id']);
	$Ajax->activate('items_table');
}
//add and update acc--------------------------------------------------------------------------------------------------
// add acc
if(isset($_POST['AddAcc'])){
	$unset = false;
	// unset($_SESSION['cost_data']);

	// Create an empty array to store the cost data
	$cost_data = array();

	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['cost_data']) ? $_SESSION['cost_data'] : array();

	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$cost_data['id'] =null;

	// Push the values of each form field into the array, including the new line number
	$cost_data['line_no'] = $next_line_no;
	
	$cost_data['cs_id'] = $cs_id;
	$cost_data['maincat_id'] = $_POST['accabric_maincat'];
	$cost_data['fab_id'] = 0;
	$cost_data['stk_code'] = $_POST['accstk_code'];
	$cost_data['consume'] = $_POST['accconsumption'];
	$cost_data['rate'] = $_POST['accrate'];
	$cost_data['processing'] = 'Accessories';
	$cost_data['waste'] = 0;
	$existing_data[] = $cost_data;

	// Store the updated array data in the session variable
	$_SESSION['cost_data'] = $existing_data;
	$Ajax->activate('items_table');
}
// update acc
if(isset($_POST['update_Acc'])) {
	$edit_id = $_POST['edit_id'];
	foreach($_SESSION['cost_data'] as $key => $value) {
		if($key == $edit_id) {
			$_SESSION['cost_data'][$key]['rate'] = $_POST['accrate'];
			$_SESSION['cost_data'][$key]['consume'] = $_POST['accconsumption'];
			display_notification(_('Order plan has been updated'));
			$unset = false;
			break;

		}
	
	}
	unset($_POST['edit_id']);
	$Ajax->activate('items_table');
}
//yarn edit function-------------------------------------------------------------------------------------------------
function edit_yan(&$order,  $line, $maincat_id, $maincat_id_2)
{
	global $Ajax;
	$id = find_row('Edit');
	
	if ($id == $line && $line != -1) {
		foreach ($order as $key => $value) {
			if ($key == $line) {
				hidden('edit_id', $key);
				$_POST['ystk_code'] = $value['stk_code'];
				$_POST['yconsumption'] = $value['consume'];
				$_POST['yrate'] = $value['rate'];
				$Ajax->activate('items_table');
				break;
			}
		}
		label_cell($_POST['ystk_code']);
		label_cell(get_description($_POST['ystk_code']));
		label_cell(get_unit($_POST['ystk_code']));
		small_qty_cells_ex(null, 'yconsumption', 0, false);
		small_qty_cells_ex(null, 'yrate', 0, false);
		qty_cell(multiply($_POST['yrate'], $_POST['yconsumption']));
	} else {
		plan_sales_items_list_cells(null, 'ystk_code', null, false, true, true, $maincat_id, $maincat_id_2);
		label_cell(get_unit($_POST['ystk_code']));
		small_qty_cells_ex(null, 'yconsumption', 0, true);
		small_qty_cells_ex(null, 'yrate', 0, true);
		qty_cell(multiply($_POST['yrate'], $_POST['yconsumption']));
	}
	if ($id != -1) {
		button_cell('update_yarn', _('Update'), _('Confirm changes'), ICON_UPDATE);
		button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
	} else {
		submit_cells('Addyarn', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
	}
	end_row();
}

//acc edit function-------------------------------------------------------------------------------------------------
function edit_acc(&$order,  $line, $maincat_id, $accabric_maincat_2)
{
	global $Ajax;
	$id = find_row('Edit');

	if ($id == $line && $line != -1) {
		foreach ($order as $key => $value) {
			if ($key == $line) {
				hidden('edit_id', $key);
				$_POST['accstk_code'] = $value['stk_code'];
				$_POST['accconsumption'] = $value['consume'];
				$_POST['accrate'] = $value['rate'];
				$_POST['accwaste'] = $value['waste'];
				$Ajax->activate('items_table');
				break;
			}
		}
		label_cell($_POST['accstk_code']);
		label_cell(get_description($_POST['accstk_code']));
		label_cell(get_unit($_POST['accstk_code']));
		small_qty_cells_ex(null, 'accrate', 0, false);
		small_qty_cells_ex(null, 'accconsumption', 0, false);
		$acc_amount = acc_amount($_POST['accrate'], $_POST['accconsumption']);
		qty_cell($acc_amount);
	} else {
		plan_sales_items_list_cells(null, 'accstk_code', null, false, true, true, $maincat_id , $accabric_maincat_2);
		label_cell(get_unit($_POST['accstk_code']));
		small_qty_cells_ex(null, 'accrate', 0, true);
		small_qty_cells_ex(null, 'accconsumption', 0, true);
		$acc_amount = acc_amount($_POST['accrate'], $_POST['accconsumption']);
		qty_cell($acc_amount);
	}
	if ($id != -1) {
		button_cell('update_Acc', _('Update'), _('Confirm changes'), ICON_UPDATE);
		button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
	} else {
		submit_cells('AddAcc', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
	}
	end_row();
}
//tabs function-------------------------------------------------------------------------------------------
function fabric_1() {
	global $Ajax;
	global $unset;
	// get_cost_data($unset);
	echo "<br>";
	$fab_id = 1;
	hidden('fab_id', $fab_id);
	start_table(TABLESTYLE, "width=90%");
	$th = array(_('Yarn Code'), _('Yarn Desc'), _('UoM'), _('Percentage Consumption'), _('Yarn Rate/Bag'), _('amount'), '', '');
	table_header($th);
	start_row();
	$id = find_row('Edit');
	$yan_maincat = 1;
	hidden('yan_maincat', $yan_maincat);
	$yan_maincat_2 = 2;
	$editable_items = true;
	if ($id == -1 && $editable_items)
	edit_yan($_SESSION['cost_data'],  -1, $yan_maincat, $yan_maincat_2);
	$yarn_cost = 0;
	$consume_persentage = 0;
	foreach ($_SESSION['cost_data'] as $key => $value) {
		start_row();
		if($value['maincat_id']==1 && $value['fab_id']== $fab_id){
			if (($id != $key || !$editable_items)) {
				label_cell($value['stk_code']);
				label_cell(get_description($value['stk_code']));
				label_cell(get_unit($value['stk_code']));
				qty_cell($value['consume']);
				qty_cell($value['rate']);
				$amount = multiply($value['rate'], $value['consume']);
				qty_cell($amount);
				$yarn_cost += $amount;
				$consume_persentage += $value['consume'];
				edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
				delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
				if (isset($_POST['Delete' . $value['line_no']])) {
					unset($_SESSION['cost_data'][$key]);
					line_start_focus();
				}
				end_row();
			} else {
				edit_yan($_SESSION['cost_data'], $key, $yan_maincat, $yan_maincat_2);
			}
		}
		
	}
	if($consume_persentage != 100){
		display_error("Yarn consumption percentage should be 100");
	}
	// foreach ($_SESSION['fab_data'] as $key => $value) {
	// 	if($value['fab_id']== $fab_id){
	// 		$_POST[$fab_id.'_Knitting_Charges'] = $value['Knitting_Charges'];
	// 		$_POST[$fab_id.'_Knitting_waste'] = $value['Knitting_waste'];
	// 		$_POST[$fab_id.'_dye_stk_code'] = $value['dye_stk_code'];
	// 		$_POST[$fab_id.'_Dyeing_Charges'] = $value['Dyeing_Charges'];
	// 		$_POST[$fab_id.'_Dyeing_Waste'] = $value['Dyeing_Waste'];
	// 		$_POST[$fab_id.'_dfab_cost_perpc'] = $value['dfab_cost_perpc'];
	// 		// $dfab_cost = $value['dfab_cost'];
	// 		$Ajax->activate('items_table');
	// 			break;
	// 	}
	// }
	
	start_row();
	label_row(_('Yarn Cost'), $yarn_cost, "colspan=5 align='right'");
	small_qty_cells_ex(_('Knitting Charges/Bag'), $fab_id.'_Knitting_Charges', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Knitting Waste %'), $fab_id.'_Knitting_waste', '', true, "colspan=5 align='right'");
	end_row();
	start_row();
	$th = array(_('Dyed Fab Code'), _('Dyed Fab Description'), _('UoM'));
	table_header($th);
	plan_sales_items_list_cells(null, $fab_id.'_dye_stk_code', null, false, false, true, 4);
	label_cell(get_unit($_POST[$fab_id.'_dye_stk_code']));
	$gfab_cost_kg = gfab_cost_kg($yarn_cost,$_POST[$fab_id.'_Knitting_Charges'],$_POST[$fab_id.'_Knitting_waste']);
	label_cells(_('Greige Fab Cost/kg'), number_format($gfab_cost_kg,2), "colspan=2 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyeing Charges/Kg'), $fab_id.'_Dyeing_Charges', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyeing Waste %'), $fab_id.'_Dyeing_Waste', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyed Fab / Piece (Kg)'), $fab_id.'_dfab_cost_perpc', '', true, "colspan=5 align='right'");
	$dfab_cost= dfab_cost($gfab_cost_kg,$_POST[$fab_id.'_Dyeing_Charges'],$_POST[$fab_id.'_dfab_cost_perpc'],$_POST[$fab_id.'_Dyeing_Waste']);
	hidden($fab_id.'_dfab_cost', $dfab_cost);
	label_row(_('Dyed Fabric Cost'), number_format($dfab_cost,2), "colspan=5 align='right'");

if($dfab_cost >0){
	// echo "working";

	$fab_data = array();
	$fab_data['fab_id'] = $fab_id;
	$fab_data['Knitting_Charges'] = $_POST[$fab_id.'_Knitting_Charges'];
	$fab_data['Knitting_waste'] = $_POST[$fab_id.'_Knitting_waste'];
	$fab_data['dye_stk_code'] = $_POST[$fab_id.'_dye_stk_code'];
	$fab_data['Dyeing_Charges'] =$_POST[$fab_id.'_Dyeing_Charges'];
	$fab_data['Dyeing_Waste'] = $_POST[$fab_id.'_Dyeing_Waste'];
	$fab_data['processing'] = 'dying';
	$fab_data['dfab_cost_perpc'] = $_POST[$fab_id.'_dfab_cost_perpc'];
	$fab_data['dfab_cost'] = $dfab_cost;
	
	$existing_data[] = $fab_data;
    $existing_data = isset($_SESSION['fab_data']) ? $_SESSION['fab_data'] : array();
 // Check if pp_id already exists in session
	$index = array_search($fab_data['fab_id'], array_column($existing_data, 'fab_id'));
if ($index !== false) {
// Data already in session, do nothing
}
else {
// Add new data to session
    $existing_data[] = $fab_data;
    $_SESSION['fab_data'] = $existing_data;
}
	
}
$Ajax->activate('items_table');

end_row();
end_table(1);
div_end();
}

function fabric_2() {
	global $Ajax;

	echo "<br>";
	$fab_id = 2;
	hidden('fab_id', $fab_id);
	start_table(TABLESTYLE, "width=90%");
	$th = array(_('Yarn Code'), _('Yarn Desc'), _('UoM'), _('Percentage Consumption'), _('Yarn Rate/Bag'), _('amount'), '', '');
	table_header($th);
	start_row();
	$id = find_row('Edit');
	$yan_maincat = 1;
	hidden('yan_maincat', $yan_maincat);
	$yan_maincat_2 = 2;
	$editable_items = true;
	if ($id == -1 && $editable_items)
	edit_yan($_SESSION['cost_data'],  -1, $yan_maincat, $yan_maincat_2);
	$yarn_cost = 0;
	$consume_persentage = 0;
	foreach ($_SESSION['cost_data'] as $key => $value) {
		start_row();
		if($value['maincat_id']==1 && $value['fab_id']== $fab_id){
			if (($id != $key || !$editable_items)) {
				label_cell($value['stk_code']);
				label_cell(get_description($value['stk_code']));
				label_cell(get_unit($value['stk_code']));
				qty_cell($value['consume']);
				qty_cell($value['rate']);
				$amount = multiply($value['rate'], $value['consume']);
				qty_cell($amount);
				$yarn_cost += $amount;
				$consume_persentage += $value['consume'];
				edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
				delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
				if (isset($_POST['Delete' . $value['line_no']])) {
					unset($_SESSION['cost_data'][$key]);
					line_start_focus();
				}
				end_row();
			} else {
				edit_yan($_SESSION['cost_data'], $key, $yan_maincat, $yan_maincat_2);
			}
		}
		
	}
	if($consume_persentage != 100){
		display_error("Yarn consumption percentage should be 100");
	}
	// foreach ($_SESSION['fab_data'] as $key => $value) {
	// 	if($value['fab_id']== $fab_id){
	// 		$_POST[$fab_id.'_Knitting_Charges'] = $value['Knitting_Charges'];
	// 		$_POST[$fab_id.'_Knitting_waste'] = $value['Knitting_waste'];
	// 		$_POST[$fab_id.'_dye_stk_code'] = $value['dye_stk_code'];
	// 		$_POST[$fab_id.'_Dyeing_Charges'] = $value['Dyeing_Charges'];
	// 		$_POST[$fab_id.'_Dyeing_Waste'] = $value['Dyeing_Waste'];
	// 		$_POST[$fab_id.'_dfab_cost_perpc'] = $value['dfab_cost_perpc'];
	// 		// $dfab_cost = $value['dfab_cost'];
	// 		$Ajax->activate('items_table');
	// 			break;
	// 	}
	// }
	
	start_row();
	label_row(_('Yarn Cost'), $yarn_cost, "colspan=5 align='right'");
	small_qty_cells_ex(_('Knitting Charges/Bag'), $fab_id.'_Knitting_Charges', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Knitting Waste %'), $fab_id.'_Knitting_waste', '', true, "colspan=5 align='right'");
	end_row();
	start_row();
	$th = array(_('Dyed Fab Code'), _('Dyed Fab Description'), _('UoM'));
	table_header($th);
	plan_sales_items_list_cells(null, $fab_id.'_dye_stk_code', null, false, false, true, 4);
	label_cell(get_unit($_POST[$fab_id.'_dye_stk_code']));
	$gfab_cost_kg = gfab_cost_kg($yarn_cost,$_POST[$fab_id.'_Knitting_Charges'],$_POST[$fab_id.'_Knitting_waste']);
	label_cells(_('Greige Fab Cost/kg'), number_format($gfab_cost_kg,2), "colspan=2 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyeing Charges/Kg'), $fab_id.'_Dyeing_Charges', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyeing Waste %'), $fab_id.'_Dyeing_Waste', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyed Fab / Piece (Kg)'), $fab_id.'_dfab_cost_perpc', '', true, "colspan=5 align='right'");
	$dfab_cost= dfab_cost($gfab_cost_kg,$_POST[$fab_id.'_Dyeing_Charges'],$_POST[$fab_id.'_dfab_cost_perpc'],$_POST[$fab_id.'_Dyeing_Waste']);
	hidden($fab_id.'_dfab_cost', $dfab_cost);

	label_row(_('Dyed Fabric Cost'), number_format($dfab_cost,2), "colspan=5 align='right'");

if($dfab_cost >0){
	// echo "working";

	$fab_data = array();
	$fab_data['fab_id'] = $fab_id;
	$fab_data['Knitting_Charges'] = $_POST[$fab_id.'_Knitting_Charges'];
	$fab_data['Knitting_waste'] = $_POST[$fab_id.'_Knitting_waste'];
	$fab_data['dye_stk_code'] = $_POST[$fab_id.'_dye_stk_code'];
	$fab_data['Dyeing_Charges'] =$_POST[$fab_id.'_Dyeing_Charges'];
	$fab_data['Dyeing_Waste'] = $_POST[$fab_id.'_Dyeing_Waste'];
	$fab_data['processing'] = 'dying';
	$fab_data['dfab_cost_perpc'] = $_POST[$fab_id.'_dfab_cost_perpc'];
	$fab_data['dfab_cost'] = $dfab_cost;

	$existing_data[] = $fab_data;
    $existing_data = isset($_SESSION['fab_data']) ? $_SESSION['fab_data'] : array();
 // Check if pp_id already exists in session
	$index = array_search($fab_data['fab_id'], array_column($existing_data, 'fab_id'));
if ($index !== false) {
// Data already in session, do nothing
}
else {
// Add new data to session
    $existing_data[] = $fab_data;
    $_SESSION['fab_data'] = $existing_data;
}	
}
$Ajax->activate('items_table');

end_row();
end_table(1);
div_end();
}
function fabric_3() {
	global $Ajax;

	echo "<br>";
	$fab_id = 3;
	hidden('fab_id', $fab_id);
	start_table(TABLESTYLE, "width=90%");
	$th = array(_('Yarn Code'), _('Yarn Desc'), _('UoM'), _('Percentage Consumption'), _('Yarn Rate/Bag'), _('amount'), '', '');
	table_header($th);
	start_row();
	$id = find_row('Edit');
	$yan_maincat = 1;
	hidden('yan_maincat', $yan_maincat);
	$yan_maincat_2 = 2;
	$editable_items = true;
	if ($id == -1 && $editable_items)
	edit_yan($_SESSION['cost_data'],  -1, $yan_maincat, $yan_maincat_2);
	$yarn_cost = 0;
	$consume_persentage = 0;
	foreach ($_SESSION['cost_data'] as $key => $value) {
		start_row();
		if($value['maincat_id']==1 && $value['fab_id']== $fab_id){
			if (($id != $key || !$editable_items)) {
				label_cell($value['stk_code']);
				label_cell(get_description($value['stk_code']));
				label_cell(get_unit($value['stk_code']));
				qty_cell($value['consume']);
				qty_cell($value['rate']);
				$amount = multiply($value['rate'], $value['consume']);
				qty_cell($amount);
				$yarn_cost += $amount;
				$consume_persentage += $value['consume'];
				edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
				delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
				if (isset($_POST['Delete' . $value['line_no']])) {
					unset($_SESSION['cost_data'][$key]);
					line_start_focus();
				}
				end_row();
			} else {
				edit_yan($_SESSION['cost_data'], $key, $yan_maincat, $yan_maincat_2);
			}
		}
		
	}
	if($consume_persentage != 100){
		display_error("Yarn consumption percentage should be 100");
	}
	// foreach ($_SESSION['fab_data'] as $key => $value) {
	// 	if($value['fab_id']== $fab_id){
	// 		$_POST[$fab_id.'_Knitting_Charges'] = $value['Knitting_Charges'];
	// 		$_POST[$fab_id.'_Knitting_waste'] = $value['Knitting_waste'];
	// 		$_POST[$fab_id.'_dye_stk_code'] = $value['dye_stk_code'];
	// 		$_POST[$fab_id.'_Dyeing_Charges'] = $value['Dyeing_Charges'];
	// 		$_POST[$fab_id.'_Dyeing_Waste'] = $value['Dyeing_Waste'];
	// 		$_POST[$fab_id.'_dfab_cost_perpc'] = $value['dfab_cost_perpc'];
	// 		// $dfab_cost = $value['dfab_cost'];
	// 		$Ajax->activate('items_table');
	// 			break;
	// 	}
	// }
	
	start_row();
	label_row(_('Yarn Cost'), $yarn_cost, "colspan=5 align='right'");
	small_qty_cells_ex(_('Knitting Charges/Bag'), $fab_id.'_Knitting_Charges', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Knitting Waste %'), $fab_id.'_Knitting_waste', '', true, "colspan=5 align='right'");
	end_row();
	start_row();
	$th = array(_('Dyed Fab Code'), _('Dyed Fab Description'), _('UoM'));
	table_header($th);
	plan_sales_items_list_cells(null, $fab_id.'_dye_stk_code', null, false, false, true, 4);
	label_cell(get_unit($_POST[$fab_id.'_dye_stk_code']));
	$gfab_cost_kg = gfab_cost_kg($yarn_cost,$_POST[$fab_id.'_Knitting_Charges'],$_POST[$fab_id.'_Knitting_waste']);
	label_cells(_('Greige Fab Cost/kg'), number_format($gfab_cost_kg,2), "colspan=2 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyeing Charges/Kg'), $fab_id.'_Dyeing_Charges', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyeing Waste %'), $fab_id.'_Dyeing_Waste', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyed Fab / Piece (Kg)'), $fab_id.'_dfab_cost_perpc', '', true, "colspan=5 align='right'");
	$dfab_cost= dfab_cost($gfab_cost_kg,$_POST[$fab_id.'_Dyeing_Charges'],$_POST[$fab_id.'_dfab_cost_perpc'],$_POST[$fab_id.'_Dyeing_Waste']);
	hidden($fab_id.'_dfab_cost', $dfab_cost);

	label_row(_('Dyed Fabric Cost'), number_format($dfab_cost,2), "colspan=5 align='right'");

if($dfab_cost >0){
	// echo "working";

	$fab_data = array();
	$fab_data['fab_id'] = $fab_id;
	$fab_data['Knitting_Charges'] = $_POST[$fab_id.'_Knitting_Charges'];
	$fab_data['Knitting_waste'] = $_POST[$fab_id.'_Knitting_waste'];
	$fab_data['dye_stk_code'] = $_POST[$fab_id.'_dye_stk_code'];
	$fab_data['Dyeing_Charges'] =$_POST[$fab_id.'_Dyeing_Charges'];
	$fab_data['Dyeing_Waste'] = $_POST[$fab_id.'_Dyeing_Waste'];
	$fab_data['processing'] = 'dying';
	$fab_data['dfab_cost_perpc'] = $_POST[$fab_id.'_dfab_cost_perpc'];
	$fab_data['dfab_cost'] = $dfab_cost;

	$existing_data[] = $fab_data;
    $existing_data = isset($_SESSION['fab_data']) ? $_SESSION['fab_data'] : array();
 // Check if pp_id already exists in session
	$index = array_search($fab_data['fab_id'], array_column($existing_data, 'fab_id'));
if ($index !== false) {
// Data already in session, do nothing
}
else {
// Add new data to session
    $existing_data[] = $fab_data;
    $_SESSION['fab_data'] = $existing_data;
}
}
$Ajax->activate('items_table');

end_row();
end_table(1);
div_end();
}
function fabric_4() {
	global $Ajax;

	echo "<br>";
	$fab_id = 4;
	hidden('fab_id', $fab_id);
	start_table(TABLESTYLE, "width=90%");
	$th = array(_('Yarn Code'), _('Yarn Desc'), _('UoM'), _('Percentage Consumption'), _('Yarn Rate/Bag'), _('amount'), '', '');
	table_header($th);
	start_row();
	$id = find_row('Edit');
	$yan_maincat = 1;
	hidden('yan_maincat', $yan_maincat);
	$yan_maincat_2 = 2;
	$editable_items = true;
	if ($id == -1 && $editable_items)
	edit_yan($_SESSION['cost_data'],  -1, $yan_maincat, $yan_maincat_2);
	$yarn_cost = 0;
	$consume_persentage = 0;
	foreach ($_SESSION['cost_data'] as $key => $value) {
		start_row();
		if($value['maincat_id']==1 && $value['fab_id']== $fab_id){
			if (($id != $key || !$editable_items)) {
				label_cell($value['stk_code']);
				label_cell(get_description($value['stk_code']));
				label_cell(get_unit($value['stk_code']));
				qty_cell($value['consume']);
				qty_cell($value['rate']);
				$amount = multiply($value['rate'], $value['consume']);
				qty_cell($amount);
				$yarn_cost += $amount;
				$consume_persentage += $value['consume'];
				edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
				delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
				if (isset($_POST['Delete' . $value['line_no']])) {
					unset($_SESSION['cost_data'][$key]);
					line_start_focus();
				}
				end_row();
			} else {
				edit_yan($_SESSION['cost_data'], $key, $yan_maincat, $yan_maincat_2);
			}
		}
		
	}
	if($consume_persentage != 100){
		display_error("Yarn consumption percentage should be 100");
	}
	// foreach ($_SESSION['fab_data'] as $key => $value) {
	// 	if($value['fab_id']== $fab_id){
	// 		$_POST[$fab_id.'_Knitting_Charges'] = $value['Knitting_Charges'];
	// 		$_POST[$fab_id.'_Knitting_waste'] = $value['Knitting_waste'];
	// 		$_POST[$fab_id.'_dye_stk_code'] = $value['dye_stk_code'];
	// 		$_POST[$fab_id.'_Dyeing_Charges'] = $value['Dyeing_Charges'];
	// 		$_POST[$fab_id.'_Dyeing_Waste'] = $value['Dyeing_Waste'];
	// 		$_POST[$fab_id.'_dfab_cost_perpc'] = $value['dfab_cost_perpc'];
	// 		// $dfab_cost = $value['dfab_cost'];
	// 		$Ajax->activate('items_table');
	// 			break;
	// 	}
	// }
	
	start_row();
	label_row(_('Yarn Cost'), $yarn_cost, "colspan=5 align='right'");
	small_qty_cells_ex(_('Knitting Charges/Bag'), $fab_id.'_Knitting_Charges', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Knitting Waste %'), $fab_id.'_Knitting_waste', '', true, "colspan=5 align='right'");
	end_row();
	start_row();
	$th = array(_('Dyed Fab Code'), _('Dyed Fab Description'), _('UoM'));
	table_header($th);
	plan_sales_items_list_cells(null, $fab_id.'_dye_stk_code', null, false, false, true, 4);
	label_cell(get_unit($_POST[$fab_id.'_dye_stk_code']));
	$gfab_cost_kg = gfab_cost_kg($yarn_cost,$_POST[$fab_id.'_Knitting_Charges'],$_POST[$fab_id.'_Knitting_waste']);
	label_cells(_('Greige Fab Cost/kg'), number_format($gfab_cost_kg,2), "colspan=2 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyeing Charges/Kg'), $fab_id.'_Dyeing_Charges', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyeing Waste %'), $fab_id.'_Dyeing_Waste', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyed Fab / Piece (Kg)'), $fab_id.'_dfab_cost_perpc', '', true, "colspan=5 align='right'");
	$dfab_cost= dfab_cost($gfab_cost_kg,$_POST[$fab_id.'_Dyeing_Charges'],$_POST[$fab_id.'_dfab_cost_perpc'],$_POST[$fab_id.'_Dyeing_Waste']);
	hidden($fab_id.'_dfab_cost', $dfab_cost);

	label_row(_('Dyed Fabric Cost'), number_format($dfab_cost,2), "colspan=5 align='right'");

if($dfab_cost >0){
	// echo "working";

	$fab_data = array();
	$fab_data['fab_id'] = $fab_id;
	$fab_data['Knitting_Charges'] = $_POST[$fab_id.'_Knitting_Charges'];
	$fab_data['Knitting_waste'] = $_POST[$fab_id.'_Knitting_waste'];
	$fab_data['dye_stk_code'] = $_POST[$fab_id.'_dye_stk_code'];
	$fab_data['Dyeing_Charges'] =$_POST[$fab_id.'_Dyeing_Charges'];
	$fab_data['Dyeing_Waste'] = $_POST[$fab_id.'_Dyeing_Waste'];
	$fab_data['processing'] = 'dying';
	$fab_data['dfab_cost_perpc'] = $_POST[$fab_id.'_dfab_cost_perpc'];
	$fab_data['dfab_cost'] = $dfab_cost;

	$existing_data[] = $fab_data;
    $existing_data = isset($_SESSION['fab_data']) ? $_SESSION['fab_data'] : array();
 // Check if pp_id already exists in session
$index = array_search($fab_data['fab_id'], array_column($existing_data, 'fab_id'));
if ($index !== false) {
// Data already in session, do nothing
}
else {
// Add new data to session
    $existing_data[] = $fab_data;
    $_SESSION['fab_data'] = $existing_data;
}	
}
$Ajax->activate('items_table');

end_row();
end_table(1);
div_end();
}
function fabric_5() {
	global $Ajax;

	echo "<br>";
	$fab_id = 5;
	hidden('fab_id', $fab_id);
	start_table(TABLESTYLE, "width=90%");
	$th = array(_('Yarn Code'), _('Yarn Desc'), _('UoM'), _('Percentage Consumption'), _('Yarn Rate/Bag'), _('amount'), '', '');
	table_header($th);
	start_row();
	$id = find_row('Edit');
	$yan_maincat = 1;
	hidden('yan_maincat', $yan_maincat);
	$yan_maincat_2 = 2;
	$editable_items = true;
	if ($id == -1 && $editable_items)
	edit_yan($_SESSION['cost_data'],  -1, $yan_maincat, $yan_maincat_2);
	$yarn_cost = 0;
	$consume_persentage = 0;
	foreach ($_SESSION['cost_data'] as $key => $value) {
		start_row();
		if($value['maincat_id']==1 && $value['fab_id']== $fab_id){
			if (($id != $key || !$editable_items)) {
				label_cell($value['stk_code']);
				label_cell(get_description($value['stk_code']));
				label_cell(get_unit($value['stk_code']));
				qty_cell($value['consume']);
				qty_cell($value['rate']);
				$amount = multiply($value['rate'], $value['consume']);
				qty_cell($amount);
				$yarn_cost += $amount;
				$consume_persentage += $value['consume'];
				edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
				delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
				if (isset($_POST['Delete' . $value['line_no']])) {
					unset($_SESSION['cost_data'][$key]);
					line_start_focus();
				}
				end_row();
			} else {
				edit_yan($_SESSION['cost_data'], $key, $yan_maincat, $yan_maincat_2);
			}
		}
		
	}
	if($consume_persentage != 100){
		display_error("Yarn consumption percentage should be 100");
	}
	// foreach ($_SESSION['fab_data'] as $key => $value) {
	// 	if($value['fab_id']== $fab_id){
	// 		$_POST[$fab_id.'_Knitting_Charges'] = $value['Knitting_Charges'];
	// 		$_POST[$fab_id.'_Knitting_waste'] = $value['Knitting_waste'];
	// 		$_POST[$fab_id.'_dye_stk_code'] = $value['dye_stk_code'];
	// 		$_POST[$fab_id.'_Dyeing_Charges'] = $value['Dyeing_Charges'];
	// 		$_POST[$fab_id.'_Dyeing_Waste'] = $value['Dyeing_Waste'];
	// 		$_POST[$fab_id.'_dfab_cost_perpc'] = $value['dfab_cost_perpc'];
	// 		// $dfab_cost = $value['dfab_cost'];
	// 		$Ajax->activate('items_table');
	// 			break;
	// 	}
	// }
	
	start_row();
	label_row(_('Yarn Cost'), $yarn_cost, "colspan=5 align='right'");
	small_qty_cells_ex(_('Knitting Charges/Bag'), $fab_id.'_Knitting_Charges', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Knitting Waste %'), $fab_id.'_Knitting_waste', '', true, "colspan=5 align='right'");
	end_row();
	start_row();
	$th = array(_('Dyed Fab Code'), _('Dyed Fab Description'), _('UoM'));
	table_header($th);
	plan_sales_items_list_cells(null, $fab_id.'_dye_stk_code', null, false, false, true, 4);
	label_cell(get_unit($_POST[$fab_id.'_dye_stk_code']));
	$gfab_cost_kg = gfab_cost_kg($yarn_cost,$_POST[$fab_id.'_Knitting_Charges'],$_POST[$fab_id.'_Knitting_waste']);
	label_cells(_('Greige Fab Cost/kg'), number_format($gfab_cost_kg,2), "colspan=2 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyeing Charges/Kg'), $fab_id.'_Dyeing_Charges', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyeing Waste %'), $fab_id.'_Dyeing_Waste', '', true, "colspan=5 align='right'");
	start_row();
	small_qty_cells_ex(_('Dyed Fab / Piece (Kg)'), $fab_id.'_dfab_cost_perpc', '', true, "colspan=5 align='right'");
	$dfab_cost= dfab_cost($gfab_cost_kg,$_POST[$fab_id.'_Dyeing_Charges'],$_POST[$fab_id.'_dfab_cost_perpc'],$_POST[$fab_id.'_Dyeing_Waste']);
	hidden($fab_id.'_dfab_cost', $dfab_cost);

	label_row(_('Dyed Fabric Cost'), number_format($dfab_cost,2), "colspan=5 align='right'");

if($dfab_cost >0){
	// echo "working";

	$fab_data = array();
	$fab_data['fab_id'] = $fab_id;
	$fab_data['Knitting_Charges'] = $_POST[$fab_id.'_Knitting_Charges'];
	$fab_data['Knitting_waste'] = $_POST[$fab_id.'_Knitting_waste'];
	$fab_data['dye_stk_code'] = $_POST[$fab_id.'_dye_stk_code'];
	$fab_data['Dyeing_Charges'] =$_POST[$fab_id.'_Dyeing_Charges'];
	$fab_data['Dyeing_Waste'] = $_POST[$fab_id.'_Dyeing_Waste'];
	$fab_data['processing'] = 'dying';
	$fab_data['dfab_cost_perpc'] = $_POST[$fab_id.'_dfab_cost_perpc'];
	$fab_data['dfab_cost'] = $dfab_cost;

	$existing_data[] = $fab_data;
    $existing_data = isset($_SESSION['fab_data']) ? $_SESSION['fab_data'] : array();
 // Check if pp_id already exists in session
	$index = array_search($fab_data['fab_id'], array_column($existing_data, 'fab_id'));
if ($index !== false) {
// Data already in session, do nothing
}
else {
// Add new data to session
    $existing_data[] = $fab_data;
    $_SESSION['fab_data'] = $existing_data;
}	
}
$Ajax->activate('items_table');

end_row();
end_table(1);
div_end();
}
//acc table-------------------------------------------------------------------------------------------
function acc() {
echo "<br>";
global $Ajax;
start_table(TABLESTYLE, "width=50%");
$th = array( _('Fab no'), _('Dyed Fab Code'), _('Dyed Fab Description'), _('UoM'), _('Dyed Fab Cost'));
table_header($th);
$dfab_cost_sum = 0;
foreach ($_SESSION['fab_data'] as $key => $value) {
	start_row();
	label_cell($value['fab_id']);
	label_cell($value['dye_stk_code']);
	label_cell(get_description($value['dye_stk_code']));
	label_cell(get_unit($value['dye_stk_code']));
	label_cell(number_format($value['dfab_cost'],2));
	$dfab_cost_sum += $value['dfab_cost'];
	end_row();

}

end_table(1);
start_table(TABLESTYLE, "width=90%");
						
$th = array(_('Acc Code'), _('Acc Desc'), _('UoM'), _('Rate/Kgs'), _('Consumption per Piece(In Grams)'), _('Total'), '', '');
table_header($th);
start_row();
$id = find_row('Edit');
$accabric_maincat = 2;
$accabric_maincat_2 = 6;
hidden('accabric_maincat', $accabric_maincat);
$editable_items = true;
if ($id == -1 && $editable_items)
	edit_acc($_SESSION['cost_data'],  -1, $accabric_maincat, $accabric_maincat_2);
$acc_cost = 0;
$consume_persentage = 0;
foreach ($_SESSION['cost_data'] as $key => $value) {
	start_row();
	if ($value['maincat_id'] == $accabric_maincat || $value['maincat_id'] == $accabric_maincat_2) {
		if (($id != $key || !$editable_items)) {
			label_cell($value['stk_code']);
			label_cell(get_description($value['stk_code']));
			label_cell(get_unit($value['stk_code']));
			qty_cell($value['rate']);
			qty_cell($value['consume']);
			$acc_amount = acc_amount($value['rate'], $value['consume']);
			qty_cell($acc_amount);
			$acc_cost += $acc_amount;
			$consume_persentage += $value['consume'];
			edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
			delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
			if (isset($_POST['Delete' . $value['line_no']])) {
				unset($_SESSION['cost_data'][$key]);
				$Ajax->activate('items_table');
			}
			end_row();
		} else {
			edit_acc($_SESSION['cost_data'], $key, $accabric_maincat, $accabric_maincat_2);
		}
	}
}

if($consume_persentage != 100){
	display_error("Yarn consumption percentage should be 100");
}
label_row(_('Acessories Cost'), $acc_cost, "colspan=5 align='right'");
end_table(1);

//footer table-------------------------------------------------------------------------------------------
start_table(TABLESTYLE_NOBORDER, "width='93%'");

echo '<tr><td>';

start_table(TABLESTYLE, "width='95%'");
small_qty_cells_ex(_('Total Labor Cost'), 'total_labor_cost', 0, true);
$total_perpc_cost = addition($dfab_cost_sum, $_POST['total_labor_cost'], $acc_cost);
label_row(_('Total Per Piece Cost'), number_format($total_perpc_cost,2));

label_cell_text('Overhead/Piece','over_persentage');
$overhead_cost = overhead_cost($total_perpc_cost, $_POST['over_persentage']);
hidden('overhead_cost', $overhead_cost);
qty_cell($overhead_cost);
$net_manuf = addition($total_perpc_cost,$overhead_cost);
label_row(_('Net Manufacturing Cost'),number_format($net_manuf,2));
small_qty_cells_ex(_('Local Freight Charges'),'local_freight', 0, true) ;
start_row();
small_qty_cells_ex(_('Container Freight'), 'container_freight',0 , true);
start_row();
small_qty_cells_ex(_('Insurance Charges'), 'insurance',0 , true);
end_table();
echo "</td><td>";

$total_perpc = addition($net_manuf, $_POST['local_freight'], $_POST['container_freight'], $_POST['insurance']);
start_table(TABLESTYLE,"width='95%'");
label_row(_('Total Price per Piece'), number_format($total_perpc,2));
label_cell_text('Commission','com_persentage');
hidden('commission', multiply($_POST['pkr_sale_price'] , $_POST['com_persentage']));
qty_cell($_POST['commission']);
end_row();
$tab = "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
label_cell_text('Profit'.$tab,'pro_persentage');

hidden('profit', multiply($_POST['pkr_sale_price'] , $_POST['pro_persentage']));
qty_cell($_POST['profit']);
$pkr_sale_price = pkr_sale_price($total_perpc, $_POST['pro_persentage'], $_POST['com_persentage']);
hidden('pkr_sale_price', $pkr_sale_price);
label_row(_('PKR Sale Price per Piece'), number_format($pkr_sale_price,2));
currencies_list_row(_('Foreign Currency'), 'currency', null, true);
small_qty_cells_ex(_('Exchange Rate(for example 1 Dollar=283.65'), 'exchange_rate',0 , true);

label_row(_('Sale Price in Foreign Currency'), number_format(sale_price_in_FC($pkr_sale_price , $_POST['exchange_rate']),2));

end_table();

echo '</td></tr>';
$Ajax->activate('items_table');
end_table();

echo '<br>';
submit_center_first('add_Cost',_('Place Cost'),  _('Check entered data and save document'), 'default');
echo '<br>';
}

//Header Table -----------------------------------------------------------------------------------------
start_form(true);
div_start('items_table');
start_table(TABLESTYLE_NOBORDER, "width='93%'");

echo '<tr><td>';
start_table(TABLESTYLE, "width='95%'");
label_row(_('Form No.'), $cs_id);
shipping_terms(_("Shipping Terms:"), 'shipping_terms');
label_row(_('Status'), check_cost_status($cs_id));
start_row();
label_cell("Style", "class='label'");
style_list_cells("style",  null, true);
end_row();
end_table();
echo "</td><td>";
start_table(TABLESTYLE, "width='95%'");
label_cells(_('Date'), date('d-m-Y'), "class='label'", 0, 0, null, true);
start_row();
label_cells(_('User'), $_SESSION['wa_current_user']->user, "class='label'", 0, 0, null, true);
end_row();
textarea_cells(_('Special Instructions:'), 'sp_ins', null, 30, 2,'','',"class='label'");
end_table();
echo "</td><td>";
start_table(TABLESTYLE, "width='95%'");
label_cell("Image", "class='label'");
file_cells(null, 'image', 'image');
foreach (array('jpg', 'png', 'gif') as $ext) {
	if($_POST['cs_id']!=null)
	$filename = 'image_'. $_POST['cs_id'];
	else
	$filename = 'image_'.$cs_id;
	hidden('filename', $filename);
	
	$file = company_path().'/images/'. $filename .'.'.$ext;
	
	if (file_exists($file)) {
		$stock_img_link = "<img id='item_img' alt = 'no image found' src='".$file."?nocache=".rand()."'"." height='".$SysPrefs->pic_height."' border='0'>";
		break;
	}
}
label_cell($stock_img_link);
end_table();
echo '</td></tr>';
end_table();
//Header Table End----------------------------------------------------------------------------------------------
//tabs----------------------------------------------------------------------------------------------------------
tabbed_content_start('tabs', array(
	'fab1' => array(_('Dyed Fab 1'), true),
	'fab2' => array(_('Dyed Fab 2'), true),
	'fab3' => array(_('Dyed Fab 3'), true),
	'fab4' => array(_('Dyed Fab 4'), true),
	'fab5' => array(_('Dyed Fab 5'), true),
	'acc' => array(_('Acc'), true),
	
));
switch (get_post('_tabs_sel')) {
	default:
	case 'fab1':
		fabric_1();
		break;
	case 'fab2':
		fabric_2();
		break;
	case 'fab3':
		fabric_3();
		break;
	case 'fab4':
		fabric_4();
		break;
	case 'fab5':
		fabric_5();
		break;
	case 'acc':
		acc();
	};
	br();
tabbed_content_end();					
div_end();
end_form();
//----------------------------------------------------------------------------------------------
end_page();