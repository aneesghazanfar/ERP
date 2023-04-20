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

include_once($path_to_root . '/planning/includes/db/receive_db.inc');
include_once($path_to_root . '/planning/includes/ui/receive_ui.inc');
include_once($path_to_root . '/planning/includes/db/stock_issue_db.inc');
include_once($path_to_root . '/planning/includes/ui/stock_issue_ui.inc');

$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'Fabric Receive'), @$_REQUEST['popup'], false, '', $js);
if (isset($_GET['AddedID'])) {
	$mo_no = $_GET['AddedID'];
	$cat = $_GET['cat'];

	display_notification_centered(_('Stock receive has been processed'));
	echo '<center><a target="_blank" href="../../planning/view/view_sr.php?mo_no='.$mo_no.'&cat='.$cat.'" onclick="javascript:openWindow(this.href,this.target); return false;">View this Delivery</a></center>';

	hyperlink_params($_SERVER['PHP_SELF'], _('Enter &Another Receive'), 'mo_no='.$mo_no.'&cat='.$cat);

	// hyperlink_params($path_to_root.'/planning/manage/stock_receive.php', _('Enter &Receiving against this Issuance'), 'mo_no='.$mo_no);

	hyperlink_no_params($path_to_root.'/planning/inquiry/mo_search.php', _('Select a different &order for stock receive'));

	display_footer_exit();
}

if (isset($_GET['mo_no']) || isset($_GET['cat'])) {
	unset($_SESSION['receive_data']);
    $mo_no = $_GET['mo_no'];
	$cat = $_GET['cat'];
	if(isset($_GET['ModifyReceive'])){
		$check = 1;
	}
	get_receive_data($mo_no, $cat);
	
}
hidden('check', $check);
hidden('cat', $cat);
hidden('mo_no', $mo_no);
if($_POST['mo_no'] && $_POST['cat']){
	$mo_no= $_POST['mo_no'];
	$cat = $_POST['cat'];
}
if($_POST['check'])
$check = $_POST['check'];
//function--------------------------------------------------------------------------
if(isset($_POST['add_receive'])){
	add_receive($_SESSION['receive_data'], temp_form_no($mo_no, 'stock_rec '), $mo_no, $cat, 
	$_POST['igp_ref'],$_POST['comment'], $_SESSION['wa_current_user']->user);
	
	unset($_SESSION['receive_data'],$_POST['weight'], $_POST['width'], $_POST['ply'], $_POST['gsm'],
	$_POST['knit'], $_POST['tuck'], $_POST['q_loop'], $_POST['fault_id'], $_POST['fault_allow'], $_POST['result']);

	meta_forward($_SERVER['PHP_SELF'], 'AddedID='.$mo_no.'&cat='.$cat);

	$Ajax->activate('items_table');

}

if (isset($_POST['Add_Rec'])) {
	if(isset($_POST['fault_id_id']))
			$_POST['fault_id'] = $_POST['fault_id_id'];
	elseif(isset($_POST['fault_id_descr']))
		$_POST['fault_id'] = $_POST['fault_id_descr'];
	if($_POST['roll_no'] == null){
		display_error(_('No roll left.'));
		set_focus('roll_no');
		$Ajax->activate('items_table');
		return;
	}
	
	elseif($_POST['fault_id'] == null){
		display_error(_('Please select at least one fault code.'));
		set_focus('fault_id');
		$Ajax->activate('items_table');
		return;
	}
	// Create an empty array to store the form data
	$receive_data = array();
	
	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['receive_data']) ? $_SESSION['receive_data'] : array();
	
	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$receive_data['id'] = null;
	
	// Push the values of each form field into the array, including the new line number
	$receive_data['line_no'] = $next_line_no;
    $receive_data['form_no'] = detail_id('sr_id','stock_rec ');
	$receive_data['sorder_no'] = $_POST['sorder'];
	$receive_data['roll_no'] = $_POST['roll_no'];
	$receive_data['style_id'] = $_POST['style'];	
	$receive_data['weight'] = $_POST['weight'];
    $receive_data['width'] = $_POST['width'];
    $receive_data['ply'] = $_POST['ply'];
    $receive_data['gsm'] = $_POST['gsm'];
    $receive_data['knit'] = $_POST['knit'];
    $receive_data['tuck'] = $_POST['tuck'];
    $receive_data['q_loop'] = $_POST['q_loop'];
    $receive_data['fault_id'] = implode(', ', $_POST['fault_id']);
    $receive_data['fault_allow'] = $_POST['fault_allow'];
    $receive_data['result'] = $_POST['result'];
	// Add the new form data to the existing array of data
	$existing_data[] = $receive_data;
	
	// Store the updated array data in the session variable
	$_SESSION['receive_data'] = $existing_data;
	// unset($_POST['weight'], $_POST['width'], $_POST['ply'], $_POST['gsm'],
	// $_POST['knit'], $_POST['tuck'], $_POST['q_loop'], $_POST['fault_id'], $_POST['fault_allow'], $_POST['result']);	
	$Ajax->activate('items_table');
}
if (isset($_POST['CancelItemChanges'])){
	$Ajax->activate('items_table');
}

if (isset($_POST['update_Rec'])) {
	$edit_id = $_POST['edit_id'];
	foreach ($_SESSION['receive_data'] as $key => $value) {
		if ($key == $edit_id) {
			$_SESSION['receive_data'][$key]['weight'] = $_POST['weight'];
			$_SESSION['receive_data'][$key]['width'] = $_POST['width'];
			
			$_SESSION['receive_data'][$key]['ply'] = $_POST['ply'];
			$_SESSION['receive_data'][$key]['gsm'] = $_POST['gsm'];
			$_SESSION['receive_data'][$key]['knit'] = $_POST['knit'];
			$_SESSION['receive_data'][$key]['tuck'] = $_POST['tuck'];
			$_SESSION['receive_data'][$key]['q_loop'] = $_POST['q_loop'];
			$_SESSION['receive_data'][$key]['fault_allow'] = $_POST['fault_allow'];
			$_SESSION['receive_data'][$key]['result'] = $_POST['result'];
		}
	}
	$Ajax->activate('items_table');
}

function edit(&$order,  $line)
{
    global $Ajax;
	global $id;
	global $mo_no;
	global $cat;

	
	if ($id == $line && $line != -1) {
		foreach ($order as $key => $value) {
			if ($key == $line) {
				hidden('edit_id', $key);
				$_POST['weight'] = $value['weight'];
				$_POST['width'] = $value['width'];
				$_POST['ply'] = $value['ply'];
				$_POST['gsm'] = $value['gsm'];
				$_POST['knit'] = $value['knit'];
				$_POST['tuck'] = $value['tuck'];
				$_POST['q_loop'] = $value['q_loop'];
				$_POST['fault_id'] = $value['fault_id'];
				$_POST['fault_allow'] = $value['fault_allow'];
				$_POST['result'] = $value['result'];
				$Ajax->activate('items_table');
				break;
			}
		}
		$existing_data = isset($_SESSION['receive_data']) ? $_SESSION['receive_data'] : array();

		$uniqnum = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;

		$roll_no = get_next_roll_no($mo_no)."_".$mo_no."_".$uniqnum;;
		hidden('roll_no', $roll_no);
		label_cell($roll_no);
		label_cell(get_uom_style('units', $mo_no));
			// label_cell(get_uom($mo_no));
		if($cat=='00-02')
		label_cell(get_uom_style('clt_style', $mo_no));
		small_qty_cells_ex(null, 'weight', null, true, null, 0);
		small_qty_cells(null, 'width', null, null, null, 0);
		small_qty_cells(null, 'ply', null, null, null, 0);
		small_qty_cells(null, 'gsm', null, null, null, 0);
		if($cat=='00-01'){
		small_qty_cells(null, 'knit', null, null, null, 0);
		small_qty_cells(null, 'tuck', null, null, null, 0);
		small_qty_cells(null, 'q_loop', null, null, null, 0);
		}
		// $fault_id = implode(', ', $_POST['fault_id']);
		label_cell($_POST['fault_id']);
		$fault_desc = implode(', ',(get_fault_description($_POST['fault_id'])));
		label_cell($fault_desc);
		qty_cell(count(explode(", ", $_POST['fault_id'])));
		small_qty_cells(null, 'fault_allow', null, null, null, 0);
		receive_result(null,'result');	
	} else {
		$existing_data = isset($_SESSION['receive_data']) ? $_SESSION['receive_data'] : array();

		$uniqnum = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
		$roll_no = get_next_roll_no($mo_no)."_".$mo_no."_".$uniqnum;
		
		if($cat=='00-01'){
		hidden('roll_no', $roll_no);
			label_cell($roll_no);
		}
		if($cat=='00-02')
		roll_no_list_cells('roll_no', $_POST['lot_no'], $_SESSION['receive_data'], null);
		label_cell(get_uom_style('units', $mo_no));
		if($cat=='00-02'){
		hidden('style', get_uom_style('clt_style', $mo_no));
			label_cell(get_uom_style('clt_style', $mo_no));
			hidden('knit', 0);
			hidden('tuck', 0);
			hidden('q_loop', 0);
	}
		small_qty_cells_ex(null, 'weight', null, true, null, 0);
		small_qty_cells(null, 'width', null, null, null, 0);
		small_qty_cells(null, 'ply', null, null, null, 0);
		small_qty_cells(null, 'gsm', null, null, null, 0);
		if($cat=='00-01'){
			hidden('style', 0);
		small_qty_cells(null, 'knit', null, null, null, 0);
		small_qty_cells(null, 'tuck', null, null, null, 0);
		small_qty_cells(null, 'q_loop', null, null, null, 0);
		}
		multiselect_cells(null, 'fault_id', null, true, 'faults');
		// fault_list_cells(null, 'fault_id', null, false, true, true);
		

	
		// $fault_desc = '';
		// // if(isset($_POST['fault_id']))
		// // 	$fault_desc = implode(', ',(get_fault_description($_POST['fault_id'])));
		// label_cell($fault_desc);
		qty_cell("0");
		small_qty_cells(null, 'fault_allow', null, null, null, 0);
		receive_result(null,'result');	
	}
	if ($id != -1) {
        button_cell('update_Rec', _('Update'), _('Confirm changes'), ICON_UPDATE);
        button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
	} else {
        submit_cells('Add_Rec', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
	}
	end_row();

}

// header-----------------------------------------------------------------------------

echo '<br>';
start_form(true);
div_start('items_table');
start_table(TABLESTYLE_NOBORDER, "width='70%'");
if($cat=='00-01')
	display_heading("Greige Fabric Receiving");
if($cat=='00-02')
	display_heading("Dyed Fabric Receiving");

echo '<tr><td>';
start_table(TABLESTYLE, "width='95%'");
label_row(_('Order No'), $mo_no);
label_row(_('Manufacturer'), get_sup_name($mo_no));
sale_dropdown_list_cells('Sale Order No', 'sorder', true, $mo_no);
label_row(_('Fabric Description'),get_fab_description($mo_no, $_POST['sorder']));
text_row(_('IGP Reference'), 'igp_ref', null, 20, 20);

end_table();
echo "</td>";
echo '<td>';
start_table(TABLESTYLE, "width='95%'");
label_row(_('Form No'), temp_form_no($mo_no, 'stock_rec '));
label_row(_('Status'), temp_check_status($mo_no,'stock_rec '));
if($cat=='00-01'){
label_cell(_('Required Quantity'), "class='label'");
qty_cell (get_req_qty($mo_no));
start_row();
label_cell(_('Already Received'), "class='label'");
qty_cell (get_rec_qty($mo_no));
start_row();
label_cell(_('Received in this Form'), "class='label'");
foreach ($_SESSION['receive_data'] as $key => $value) {
	$total_rec += $value['weight'];

}
qty_cell ($total_rec);
}
elseif($cat=='00-02'){
	lot_no_item_list_cells('LOT Number', 'lot_no', true, $mo_no);
	start_row();
	label_cell(_('Total Rolls'), "class='label'");
	qty_cell (get_roll_qty($mo_no));
	start_row();
	label_cell(_('Total Weights'), "class='label'");
	qty_cell (get_weight_qty($mo_no));
}

end_table();
// echo "</td><td>";

// start_table(TABLESTYLE, "width='95%'");

// // label_row(_('Entry By'), null, "class='label'", 0, 0, null, true);
// // label_row(_('Date'),null, "class='label'", 0, 0, null, true);
// end_table();

echo '</td></tr>';

end_table();
// var_dump($_SESSION['receive_data']);

start_table(TABLESTYLE, "width='70%'");
if($cat == '00-01')
	$th = array(_('Roll No.'), _('UOM'), _('Weight'), _('Width'), _('Ply'), _('GSM'),_('Knit'),_('Tuck'),_('Loop'), _('Fault Code'), _('Fault Description'), _('Total Faults'), _('Allowed Faults'), _('Result'), '', '');
elseif($cat == '00-02')
	$th = array(_('Roll No.'), _('UOM'),_('Style ID'), _('Weight'), _('Width'), _('Ply'), _('GSM'), _('Fault Code'), _('Fault Description'), _('Total Faults'), _('Allowed Faults'), _('Result'), '', '');
table_header($th);
$id = find_row('Edit');
$editable_items = true;
if ($id == -1 && $editable_items)
	edit($_SESSION['receive_data'],  -1);
foreach ($_SESSION['receive_data'] as $key => $value) {
	start_row();
		if (($id != $key || !$editable_items)) {
			if($value['sorder_no'] == $_POST['sorder']){
			label_cell($value['roll_no']);
			label_cell(get_uom_style('units', $mo_no));
			// label_cell(get_uom($mo_no));
			if($cat=='00-02')
				label_cell(get_uom_style('clt_style', $mo_no));
			qty_cell($value['weight']);
			qty_cell($value['width']);
			qty_cell($value['ply']);
			qty_cell($value['gsm']);
			if($cat=='00-01'){
			qty_cell($value['knit']);
			qty_cell($value['tuck']);
			qty_cell($value['q_loop']);
			}
			label_cell($value['fault_id']);
			// var_dump($value['fault_id']);
			$fault_desc = implode(', ',(get_fault_description($value['fault_id'])));
			label_cell($fault_desc);
			qty_cell(count(explode(", ", $value['fault_id'])));
			qty_cell($value['fault_allow']);
			label_cell($value['result']);
			edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
			delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
			if (isset($_POST['Delete' . $value['line_no']])) {
				unset($_SESSION['receive_data'][$key]);
				$Ajax->activate('items_table');
			}
			end_row();
		}} else {
			edit($_SESSION['receive_data'], $key);
		}
	}



end_table(1);

	// echo '<br>';

	start_table(TABLESTYLE2);
	textarea_row(_('Special Instructions:'), 'comment', null, 70, 4);
	end_table(1);
	if($check)
	submit_center_first('add_receive',_('Edit Receive Stock'),  _('Check entered data and save document'), 'default');
	else
	submit_center_first('add_receive',_('Receive Stock'),  _('Check entered data and save document'), 'default');
end_form();
div_end();
end_page();
