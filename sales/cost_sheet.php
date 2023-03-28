<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
 ***********************************************************************/
$page_security = 'SA_SALESORDER';
$path_to_root = '..';
include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/includes/ui.inc');
include($path_to_root . '/purchasing/includes/db/so_plan_db.inc');
include($path_to_root . '/purchasing/includes/ui/so_plan_ui.inc');
include_once($path_to_root . '/sales/includes/ui/cost_sheet_ui.inc');
include_once($path_to_root . '/includes/ui/ui_lists.inc');
include_once($path_to_root . '/sales/includes/db/cost_sheet_db.inc');
$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'Cost Sheet'), @$_REQUEST['popup'], false, '', $js);
if (isset($_GET['cs_id'])) {
	$cs_id = $_GET['cs_id'];
	unset($_SESSION['cost_data']);
}
hidden('cs_id', $cs_id);
// else{
// 	$cs_id = $_POST['cs_id'];
// }
// hidden('cs_id', $cs_id);
//function---------------------------------------------------------------------------------------------------
if (isset($_POST['add_Cost'])) {
	add_cost_data_to_db($_SESSION['cost_data'],$_POST['cs_id'], $_SESSION['wa_current_user']->user,date('y/m/d'),$_POST['style'],$_POST['shipping_terms'], $_POST['sp_ins'],$_POST['status']
,$_POST['overhead'],$_POST['local_freight'],$_POST['container_freight'],$_POST['insurance'],$_POST['commission'],$_POST['profit'],$_POST['exchange_rate'],$_POST['ufilename']);
get_data_data(1,true);
get_data_data(2,true);
get_data_data(3,true);
get_data_data(4,true);
get_data_data(5,true);
get_data_data(6,true);
}

if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {	
	$order_no = $_POST['order_no'];
	$result = $_FILES['image']['error'];
	$upload_file = 'Yes'; //Assume all is well to start off with
	$filename = company_path().'/images';
	
	if (!file_exists($filename))
	mkdir($filename);
	

	$filename .= '/'. item_img_name($_POST['cs_id']).(substr(trim($_FILES['image']['name']), strrpos($_FILES['image']['name'], '.')));
	
	
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
if (isset($_POST['Addyarn'])) {
	// Create an empty array to store the form data
	$cost_data = array();
	
	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['cost_data']) ? $_SESSION['cost_data'] : array();
	
	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$cost_data['id'] = null;
	
	// Push the values of each form field into the array, including the new line number
	$cost_data['line_no'] = $next_line_no;
	$cost_data['maincat_id'] = $_POST['yan_maincat'];
	$cost_data['cs_id'] = $_POST['cs_id'];
	$cost_data['stk_code'] = $_POST['ystk_code'];
	$cost_data['description'] = get_description($_POST['ystk_code']);
	$cost_data['units'] = get_unit($_POST['ystk_code']);
	$cost_data['waste'] = 0;
	$cost_data['consumption'] = $_POST['yconsumption'];
	$cost_data['rate'] = $_POST['yrate'];
	$cost_data['amount'] = amount();
	
	// Add the new form data to the existing array of data
	$existing_data[] = $cost_data;
	
	// Store the updated array data in the session variable
	$_SESSION['cost_data'] = $existing_data;
	$Ajax->activate('items_table');
}

if (isset($_POST['update_yarn'])) {
	$edit_id = $_POST['edit_id'];
	foreach ($_SESSION['cost_data'] as $key => $value) {
		if ($key == $edit_id) {
			$_SESSION['cost_data'][$key]['consumption'] = $_POST['yconsumption'];
			$_SESSION['cost_data'][$key]['rate'] = $_POST['yrate'];
		}
	}
	$Ajax->activate('items_table');
}

function line_start_focus()
{
	global 	$Ajax;
	
	$Ajax->activate('items_table');
}
if (isset($_POST['CancelItemChanges']))
line_start_focus();

function edit_yan(&$order,  $line, $maincat_id, $maincat_id_2)
{
	global $Ajax;
	global $id;
	
	if ($id == $line && $line != -1) {
		foreach ($order as $key => $value) {
			if ($key == $line) {
				hidden('edit_id', $key);
				$_POST['ystk_code'] = $value['stk_code'];
				$_POST['yconsumption'] = $value['consumption'];
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
		qty_cell(amount());
	} else {
		plan_sales_items_list_cells(null, 'ystk_code', null, false, true, true, $maincat_id, $maincat_id_2);
		label_cell(get_unit($_POST['ystk_code']));
		small_qty_cells_ex(null, 'yconsumption', 0, false);
		small_qty_cells_ex(null, 'yrate', 0, false);
		qty_cell(amount());
	}
	if ($id != -1) {
		button_cell('update_yarn', _('Update'), _('Confirm changes'), ICON_UPDATE);
		button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
	} else {
		submit_cells('Addyarn', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
	}
	end_row();
}

if (isset($_POST['Addfabric'])) {
	// Create an empty array to store the form data
	$cost_data = array();

	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['cost_data']) ? $_SESSION['cost_data'] : array();

	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$cost_data['id'] = null;

	// Push the values of each form field into the array, including the new line number
	$cost_data['line_no'] = $next_line_no;
	$cost_data['maincat_id'] = $_POST['fabric_maincat'];
	$cost_data['cs_id'] = $_POST['cs_id'];
	$cost_data['stk_code'] = $_POST['fstk_code'];
	$cost_data['description'] = get_description($_POST['fstk_code']);
	$cost_data['units'] = get_unit($_POST['fstk_code']);
	$cost_data['waste'] = $_POST['fwaste'];
	$cost_data['consumption'] = $_POST['fconsumption'];
	$cost_data['rate'] = $_POST['frate'];
	$cost_data['amount'] = amount();

	// Add the new form data to the existing array of data
	$existing_data[] = $cost_data;

	// Store the updated array data in the session variable
	$_SESSION['cost_data'] = $existing_data;
	$Ajax->activate('items_table');
}

if (isset($_POST['update_fabric'])) {
	$edit_id = $_POST['edit_id'];
	foreach ($_SESSION['cost_data'] as $key => $value) {
		if ($key == $edit_id) {
			$_SESSION['cost_data'][$key]['rate'] = $_POST['frate'];
			$_SESSION['cost_data'][$key]['waste']  = $_POST['fwaste'];
			$_SESSION['cost_data'][$key]['consumption'] = $_POST['fconsumption'];
		}
	}
	$Ajax->activate('items_table');
}

function edit_Fabric(&$order,  $line, $maincat_id)
{
	global $Ajax;
	$id = find_row('Edit');

	if ($id == $line && $line != -1) {
		foreach ($order as $key => $value) {
			if ($key == $line) {
				hidden('edit_id', $key);
				$_POST['fstk_code'] = $value['stk_code'];
				$_POST['fconsumption'] = $value['consumption'];
				$_POST['frate'] = $value['rate'];
				$_POST['fwaste'] = $value['waste'];
				$Ajax->activate('items_table');
				break;
			}
		}
		label_cell($_POST['fstk_code']);
		label_cell(get_description($_POST['fstk_code']));
		label_cell(get_unit($_POST['fstk_code']));
		small_qty_cells_ex(null, 'frate', 0, false);
		small_qty_cells_ex(null, 'fwaste', 0, false);
		small_qty_cells_ex(null, 'fconsumption', 0, false);
		qty_cell(amount());
	} else {
		plan_sales_items_list_cells(null, 'fstk_code', null, false, true, true, $maincat_id);
		label_cell(get_unit($_POST['fstk_code']));
		small_qty_cells_ex(null, 'frate', 0, false);
		small_qty_cells_ex(null, 'fwaste', 0, false);
		small_qty_cells_ex(null, 'fconsumption', 0, false);
		qty_cell(amount());
	}
	if ($id != -1) {
		button_cell('update_fabric', _('Update'), _('Confirm changes'), ICON_UPDATE);
		button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
	} else {
		submit_cells('Addfabric', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
	}
	end_row();
}

if (isset($_POST['AddDyed'])) {
	// Create an empty array to store the form data
	$cost_data = array();

	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['cost_data']) ? $_SESSION['cost_data'] : array();

	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$cost_data['id'] = null;

	// Push the values of each form field into the array, including the new line number
	$cost_data['line_no'] = $next_line_no;
	$cost_data['maincat_id'] = $_POST['pabric_maincat'];
	$cost_data['cs_id'] = $_POST['cs_id'];
	$cost_data['stk_code'] = $_POST['pstk_code'];
	$cost_data['description'] = get_description($_POST['pstk_code']);
	$cost_data['units'] = get_unit($_POST['pstk_code']);
	$cost_data['consumption'] = $_POST['pconsumption'];
	$cost_data['rate'] = amount();
	$cost_data['amount'] = amount();

	// Add the new form data to the existing array of data
	$existing_data[] = $cost_data;

	// Store the updated array data in the session variable
	$_SESSION['cost_data'] = $existing_data;
	$Ajax->activate('items_table');
}

if (isset($_POST['update_Dyed'])) {
	$edit_id = $_POST['edit_id'];
	foreach ($_SESSION['cost_data'] as $key => $value) {
		if ($key == $edit_id) {
			// $_SESSION['cost_data'][$key]['rate'] = $_POST['frate'];
			// $_SESSION['cost_data'][$key]['waste']  = $_POST['fwaste'];
			$_SESSION['cost_data'][$key]['consumption'] = $_POST['pconsumption'];
		}
	}
	$Ajax->activate('items_table');
}

function edit_perpcFabric(&$order,  $line, $maincat_id)
{
	global $Ajax;
	$id = find_row('Edit');

	if ($id == $line && $line != -1) {
		foreach ($order as $key => $value) {
			if ($key == $line) {
				hidden('edit_id', $key);
				$_POST['pstk_code'] = $value['stk_code'];
				$_POST['pconsumption'] = $value['consumption'];
				$_POST['prate'] = $value['rate'];
				$_POST['pwaste'] = $value['waste'];
				$Ajax->activate('items_table');
				break;
			}
		}
		label_cell($_POST['pstk_code']);
		label_cell(get_description($_POST['pstk_code']));
		label_cell(get_unit($_POST['pstk_code']));
		qty_cell(amount());
		small_qty_cells_ex(null, 'pconsumption', 0, false);
		qty_cell(amount());
	} else {
		plan_sales_items_list_cells(null, 'pstk_code', null, false, true, true, $maincat_id);
		label_cell(get_unit($_POST['pstk_code']));
		qty_cell(amount());
		small_qty_cells_ex(null, 'pconsumption', 0, true);
		qty_cell(amount());
	}
	if ($id != -1) {
		button_cell('update_Dyed', _('Update'), _('Confirm changes'), ICON_UPDATE);
		button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
	} else {
		submit_cells('AddDyed', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
	}
	end_row();
}

if (isset($_POST['AddAcc'])) {
	// Create an empty array to store the form data
	$cost_data = array();

	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['cost_data']) ? $_SESSION['cost_data'] : array();

	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$cost_data['id'] = null;

	// Push the values of each form field into the array, including the new line number
	$cost_data['line_no'] = $next_line_no;
	$cost_data['maincat_id'] = $_POST['accabric_maincat'];
	$cost_data['cs_id'] = $_POST['cs_id'];
	$cost_data['stk_code'] = $_POST['accstk_code'];
	$cost_data['description'] = get_description($_POST['accstk_code']);
	$cost_data['units'] = get_unit($_POST['accstk_code']);
	$cost_data['consumption'] = $_POST['accconsumption'];
	$cost_data['rate'] = amount();
	$cost_data['amount'] = amount();

	// Add the new form data to the existing array of data
	$existing_data[] = $cost_data;

	// Store the updated array data in the session variable
	$_SESSION['cost_data'] = $existing_data;
	$Ajax->activate('items_table');
}

if (isset($_POST['update_Acc'])) {
	$edit_id = $_POST['edit_id'];
	foreach ($_SESSION['cost_data'] as $key => $value) {
		if ($key == $edit_id) {
			$_SESSION['cost_data'][$key]['consumption'] = $_POST['accconsumption'];
		}
	}
	$Ajax->activate('items_table');
}

function edit_acc(&$order,  $line, $maincat_id, $accabric_maincat_2)
{
	global $Ajax;
	$id = find_row('Edit');

	if ($id == $line && $line != -1) {
		foreach ($order as $key => $value) {
			if ($key == $line) {
				hidden('edit_id', $key);
				$_POST['accstk_code'] = $value['stk_code'];
				$_POST['accconsumption'] = $value['consumption'];
				$_POST['accrate'] = $value['rate'];
				$_POST['accwaste'] = $value['waste'];
				$Ajax->activate('items_table');
				break;
			}
		}
		label_cell($_POST['accstk_code']);
		label_cell(get_description($_POST['accstk_code']));
		label_cell(get_unit($_POST['accstk_code']));
		qty_cell(amount());
		small_qty_cells_ex(null, 'accconsumption', 0, false);
		qty_cell(amount());
	} else {
		plan_sales_items_list_cells(null, 'accstk_code', null, false, true, true, $maincat_id , $accabric_maincat_2);
		label_cell(get_unit($_POST['accstk_code']));
		qty_cell(amount());
		small_qty_cells_ex(null, 'accconsumption', 0, true);
		qty_cell(amount());
	}
	if ($id != -1) {
		button_cell('update_Acc', _('Update'), _('Confirm changes'), ICON_UPDATE);
		button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
	} else {
		submit_cells('AddAcc', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
	}
	end_row();
}

//Header Table----------------------------------------------------------------------------------------------
// is jaga
start_form(true);
div_start('items_table');
start_table(TABLESTYLE_NOBORDER, "width='93%'");

echo '<tr><td>';
start_table(TABLESTYLE, "width='95%'");
label_row(_('Form No.'), $cs_id);
shipping_terms(_("Shipping Terms:"), 'shipping_terms');
text_cells(_('Status'), 'status', null, 21, 5, null, "class='label'");
start_row();
label_cell("Style", "class='label'");
// label_cells
style_list_cells("style",  null, true);
end_row();

end_table();
echo "</td><td>";
start_table(TABLESTYLE, "width='95%'");
label_cells(_('Date'), date('d-m-Y'), "class='label'", 0, 0, null, true);
start_row();
label_cells(_('User'), $_SESSION['wa_current_user']->user, "class='label'", 0, 0, null, true);
end_row();
textarea_cells(_('Special Instructions:'), 'sp_ins', null, 30, 1,'','',"class='label'");
start_row();
label_cell("Image", "class='label'");
file_cells(null, 'image', 'image');
end_row();

end_table();
echo '</td></tr>';
end_table();



//Header Table End-------------------------------------------------------------------------------------------
echo "<br>";
//Yarn Table----------------------------------------------------------------------------------------------
start_table(TABLESTYLE, "width=90%");
get_data_data(1);
get_data_data(2);
get_data_data(3);
get_data_data(4);
get_data_data(5);
get_data_data(6);
$th = array(_('Yarn Code'), _('Yarn Desc'), _('UoM'), _('Percentage Consumption'), _('Yarn Rate/Bag'), _('Amount'), '', '');
table_header($th);
start_row();
$id = find_row('Edit');
$yan_maincat = 1;
hidden('yan_maincat', $yan_maincat);
$yan_maincat_2 = 2;
$editable_items = true;
if ($id == -1 && $editable_items)
	edit_yan($_SESSION['cost_data'],  -1, $yan_maincat, $yan_maincat_2);
foreach ($_SESSION['cost_data'] as $key => $value) {
	start_row();
	if (($value['maincat_id'] == $yan_maincat) || ($value['maincat_id'] == $yan_maincat_2)) {
		if (($id != $key || !$editable_items)) {
			label_cell($value['stk_code']);
			label_cell(get_description($value['stk_code']));
			label_cell(get_unit($value['stk_code']));
			qty_cell($value['consumption']);
			qty_cell($value['rate']);
			qty_cell(amount());
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
start_row();
label_row(_('Yarn Cost'), amount(), "colspan=5 align='right'");
label_row(_('Knitting Charges/Bag'), knitt_bag_formula(), "colspan=5 align='right'");
small_qty_cells_ex(_('Knitting Charges'), total_formula(), '', true, "colspan=5 align='right'");
label_row(_('Ecru Fabric Cost/kg'), total_cost(), "colspan=5 align='right'");
end_row();
end_table(1);
//Yarn Table End-------------------------------------------------------------------------------------------

start_table(TABLESTYLE, "width=90%");
$th = array(_('Fabric Code'), _('Fabric Desc'), _('UoM'), _('Rate/kgs'), _('Wastage% '), _('Percentage Consumption'), _('Total'), '', '');
table_header($th);
start_row();
$id = find_row('Edit');
$fabric_maincat = 3;
hidden('fabric_maincat', $fabric_maincat);
$editable_items = true;
if ($id == -1 && $editable_items)
	edit_Fabric($_SESSION['cost_data'],  -1, $fabric_maincat);
foreach ($_SESSION['cost_data'] as $key => $value) {
	start_row();
	if ($value['maincat_id'] == $fabric_maincat) {
		if (($id != $key || !$editable_items)) {
			label_cell($value['stk_code']);
			label_cell(get_description($value['stk_code']));
			label_cell(get_unit($value['stk_code']));
			qty_cell($value['rate']);
			qty_cell($value['waste']);
			qty_cell($value['consumption']);
			qty_cell(amount());
			edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
			delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
			if (isset($_POST['Delete' . $value['line_no']])) {
				unset($_SESSION['cost_data'][$key]);
				$Ajax->activate('items_table');
			}
			end_row();
		} else {
			edit_Fabric($_SESSION['cost_data'], $key, $fabric_maincat);
		}
	}
}
start_row();
label_row(_('Dyeing Cost/kg'), amount(), "colspan=6 align='right'");
label_row(_('Dyed Fabric Cost/kg'), knitt_bag_formula(), "colspan=6 align='right'");
end_row();

end_table(1);

//fabric end-----------------------------------------------------------------------------------------------

start_table(TABLESTYLE, "width=90%");
$th = array(_('Dyed Code'), _('Dyed Desc'), _('UoM'), _('Rate/Kgs'), _('Consumption per Piece(In Grams)'), _('Total'), '', '');
table_header($th);
start_row();
$id = find_row('Edit');
$pabric_maincat = 4;
hidden('pabric_maincat', $pabric_maincat);
$editable_items = true;
if ($id == -1 && $editable_items)
	edit_perpcFabric($_SESSION['cost_data'],  -1, $pabric_maincat);
foreach ($_SESSION['cost_data'] as $key => $value) {
	start_row();
	if ($value['maincat_id'] == $pabric_maincat) {
		if (($id != $key || !$editable_items)) {
			label_cell($value['stk_code']);
			label_cell(get_description($value['stk_code']));
			label_cell(get_unit($value['stk_code']));
			qty_cell($value['rate']);
			qty_cell($value['consumption']);
			qty_cell(amount());
			edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
			delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
			if (isset($_POST['Delete' . $value['line_no']])) {
				unset($_SESSION['cost_data'][$key]);
				$Ajax->activate('items_table');
			}
			end_row();
		} else {
			edit_perpcFabric($_SESSION['cost_data'], $key, $pabric_maincat);
		}
	}
}
label_row(_('Per Piece Fabric Cost'), amount(), "colspan=5 align='right'");
end_table(1);
//-----------------------------------------------------------------------------------------------------------


start_table(TABLESTYLE, "width=90%");
$th = array(_('Acc Code'), _('Acc Desc'), _('UoM'), _('Rate/Kgs'), _('Consumption per Piece(In Grams)'), _('Total'), '', '');
table_header($th);
start_row();
$id = find_row('Edit');
$accabric_maincat = 5;
$accabric_maincat_2 = 6;
hidden('accabric_maincat', $accabric_maincat);
$editable_items = true;
if ($id == -1 && $editable_items)
	edit_acc($_SESSION['cost_data'],  -1, $accabric_maincat, $accabric_maincat_2);
foreach ($_SESSION['cost_data'] as $key => $value) {
	start_row();
	if ($value['maincat_id'] == $accabric_maincat || $value['maincat_id'] == $accabric_maincat_2) {
		if (($id != $key || !$editable_items)) {
			label_cell($value['stk_code']);
			label_cell(get_description($value['stk_code']));
			label_cell(get_unit($value['stk_code']));
			qty_cell($value['rate']);
			qty_cell($value['consumption']);
			qty_cell(amount());
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
label_row(_('Per Piece Fabric Cost'), amount(), "colspan=5 align='right'");
label_row(_('Total Labor Cost'), amount(), "colspan=5 align='right'");
label_row(_('Total Per Piece Cost'), amount(), "colspan=5 align='right'");
end_table(1);

//Footer table-----------------------------------------------------------------------------------------------

start_table(TABLESTYLE_NOBORDER, "width='93%'");

echo '<tr><td>';

start_table(TABLESTYLE, "width='95%'");

label_cell_text('Overhead/Piece','over_persentage');
qty_cell(amount());
hidden('overhead', amount());

label_row(_('Net Manufacturing Cost'), total_cost());
small_qty_row(_('Local Freight Charges'), 'local_freight');
small_qty_row(_('Container Freight'), 'container_freight');
small_qty_row(_('Insurance Charges'), 'insurance');
label_row(_('Total Price per Piece'), total_cost());
end_table();
echo "</td><td>";

start_table(TABLESTYLE,"width='95%'");
label_cell_text('Commission','com_persentage');
hidden('commission', amount());
qty_cell(amount());
end_row();
$tab = "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
label_cell_text('Profit'.$tab,'pro_persentage');
hidden('profit', amount());
// label_cell('Profit');
// small_qty_cells_ex(null, 'pro_persentage', 0, false);
qty_cell(amount());
label_row(_('PKR Sale Price per Piece'), total_cost());
small_qty_row(_('Exchange Rate'), 'exchange_rate');
small_qty_row(_('Sale Price in Foreign Currency'), total_formula());
end_table();

echo '</td></tr>';
end_table();

submit_center_first('add_Cost',_('Place Cost'),  _('Check entered data and save document'), 'default');



end_form();
div_end();
end_page();
