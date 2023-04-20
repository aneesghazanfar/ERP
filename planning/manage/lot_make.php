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

include_once($path_to_root . '/planning/includes/ui/lot_ui.inc');
include_once($path_to_root . '/planning/includes/db/lot_db.inc');

$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'Lot Making'), @$_REQUEST['popup'], false, '', $js);
if (isset($_GET['AddedID'])) {
	$mo_no = $_GET['AddedID'];
	display_notification_centered(_('Lot has been added'));
	echo '<center><a target="_blank" href="../../planning/view/view_lot.php?mo_no='.$mo_no.'" onclick="javascript:openWindow(this.href,this.target); return false;">View this Lot</a></center>';

	hyperlink_params($_SERVER['PHP_SELF'], _('Enter &Another LOT'), null);

	// hyperlink_params($path_to_root.'/planning/manage/stock_receive.php', _('Enter &Receiving against this Issuance'), 'mo_no='.$mo_no);

	hyperlink_no_params($path_to_root.'/planning/inquiry/mo_search.php', _('Select a different &order for stock receive'));

	display_footer_exit();
}

$unset = true;
$_SESSION['lot_data'];
// unset($_SESSION['lot_data']);
//functions-------------------------------------------------------------------------------
if(isset($_POST['add_lot_in_db'])){
	add_lot($_SESSION['lot_data'], $_POST['contract_no'], $_POST['lot_no'],
	$_POST['roll_count'], $_POST['roll_weight'], $_POST['color'], $_POST['comment'], $_SESSION['wa_current_user']->user);
	meta_forward($_SERVER['PHP_SELF'], 'AddedID='.$_POST['contract_no']);

}

if (isset($_POST['Add_lot'])) {
	if($_POST['roll_no'] == null){
		display_error(_('No roll left.'));
		set_focus('roll_no');
		$Ajax->activate('items_table');
		return;
	}
	$unset = false;
	$lot_data = array();
	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['lot_data']) ? $_SESSION['lot_data'] : array();
	
	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$lot_data['id'] = null;
	// Push the values of each form field into the array, including the new line number
	$lot_data['line_no'] = $next_line_no;
	$lot_data['lot_id'] = detail_id('lot_id','lot ');	
	$lot_data['roll_no'] = $_POST['roll_no'];
	$existing_data[] = $lot_data;
	// display_notification($lot_data['lot_id']);
	// Store the updated array data in the session variable
	$_SESSION['lot_data'] = $existing_data;
	var_dump($_SESSION['lot_data']);
	// unset($_POST['weight'], $_POST['width'], $_POST['ply'], $_POST['gsm'],
	// $_POST['knit'], $_POST['tuck'], $_POST['q_loop'], $_POST['fault_id'], $_POST['fault_allow'], $_POST['result']);	
	$Ajax->activate('items_table');
}



function edit(&$lot_data, $line_no)
{
	global $unset;
	get_roll_no_list_cells(null, 'roll_no', true, null, $_POST['contract_no'],$_SESSION['lot_data'], $_SESSION['lot_data1']);
	label_cell(get_uom_style('units', $_POST['contract_no']));
	// a similar function is used in issue_ui.inc we have to change it
	label_cell(get_roll_weight($_POST['roll_no']));
	submit_cells('Add_lot', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);   
}


//header-------------------------------------------------------------------------------
start_form(true);
div_start('items_table');
start_table(TABLESTYLE_NOBORDER, "width='70%'");
display_heading("Lot Making");
echo '<tr><td>';
start_table(TABLESTYLE, "width='95%'");
dropdown_list_cells('Order No', 'contract_no', true, 'purch_orders', 'order_no', 'order_no');
start_row();
label_row(_('Sale Order No'), get_sorder_no($_POST['contract_no']));
// dropdown_list_cells('Export Contract', 'export_no', true, 'sales_orders', 'order_no', 'order_no');
label_row(_('Manufacturer'), get_sup_name($_POST['contract_no']));
dropdown_list_cells('Color', 'color', true, 'item_color', 'item_color_id', 'item_color_name');
label_row(_('Fabric Description'),get_fab_description($_POST['contract_no']));
end_table();
echo "</td>";
echo '<td>';
start_table(TABLESTYLE, "width='95%'");
$lot_no = "lot_".get_next_roll_no($_POST['contract_no'])."_".$_POST['contract_no']."_".get_number();
hidden('lot_no', $lot_no);
label_row(_('LOT Number'), $lot_no);
// label_cell(_('Form No'), "class='label'");
label_row(_('Form No'),detail_id('lot_id','lot '));
// qty_cell(detail_id('lot_id','lot '));

label_row(_('Status'), temp_check_status($_POST['contract_no'],'lot '));
foreach ($_SESSION['lot_data'] as $key => $value) {
	$roll_count = $key + 1;
	$roll_weight += get_roll_weight($value['roll_no']);
}
hidden('roll_count', $roll_count);
hidden('roll_weight', $roll_weight);
label_row(_('Roll No Count'), $roll_count);
label_row(_('Roll Weight'), $roll_weight);


end_table();
// echo "</td><td>";

// start_table(TABLESTYLE, "width='95%'");

// end_table();

echo '</td></tr>';
end_table();


//main table-------------------------------------------------------------------------------
start_table(TABLESTYLE, "width='70%'");
get_lot_data($_POST['contract_no'], $unset);
$th = array(_('Roll No.'), _('UOM'), _('Weight'), '');
table_header($th);
// var_dump($_SESSION['lot_data1']);
$id = find_row('Edit');
$editable_items = true;
if ($id == -1 && $editable_items)
	edit($_SESSION['lot_data'],  -1);
foreach ($_SESSION['lot_data'] as $key => $value) {
	start_row();
		if (($id != $key || !$editable_items)) {
            label_cell($value['roll_no']);
			label_cell(get_uom_style('units', $_POST['contract_no']));
			// similar function"get_weight()" is used in issue_ui.inc we have to change it
			label_cell(get_roll_weight($value['roll_no']));
			// label_cell(get_uom_style('units', 1));
            // label_cell($value['weight']);
            // edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
			// delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
			// if (isset($_POST['Delete' . $value['line_no']])) {
			// 	unset($_SESSION['lot_data'][$key]);
			// 	$Ajax->activate('items_table');
			// }
			end_row();
		} 
		// else {
		// 	//edit($_SESSION['lot_data'], $key);
		// }
	}	


end_table(1);

//footer-------------------------------------------------------------------------------
start_table(TABLESTYLE2);
textarea_row(_('Special Instructions:'), 'comment', null, 70, 4);
end_table(1);
// if($check)
// submit_center_first('add_receive',_('Edit Receive Stock'),  _('Check entered data and save document'), 'default');
// else
submit_center_first('add_lot_in_db',_('Add Lot'),  _('Check entered data and save document'), 'default');
end_form();
div_end();
end_page();