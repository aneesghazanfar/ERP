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
$page_security = 'SA_WORKCENTRES';
$path_to_root = '../..';

include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/includes/ui.inc');
include_once($path_to_root . '/includes/ui/ui_lists.inc');
include_once($path_to_root . '/manufacturing/includes/issuance_ui.inc');
include_once($path_to_root . '/manufacturing/includes/db/issuance_db.inc');

include($path_to_root . '/purchasing/includes/ui/so_plan_ui.inc');
include($path_to_root . '/purchasing/includes/db/so_plan_db.inc');






$js = '';
if ($SysPrefs->use_popup_windows)
$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
$js .= get_js_date_picker();
page(_($help_context = 'Yarn Issuance'), @$_REQUEST['popup'], false, '', $js);
if (isset($_GET['mo_no'])) {
    unset($_SESSION['issuance_data']);
    $mo_no = $_GET['mo_no'];
    get_issuance_data($maincat_id);
}
hidden('mo_no', $mo_no);
if($_POST['mo_no'])
    $mo_no = $_POST['mo_no'];
$maincat_id = 1;
hidden('maincat_id', $maincat_id);
echo $mo_no;
//function ---------------------------------------------------------------------------------------------
if(isset($_POST['add_yarn'])){
	add_issuance_database($_SESSION['issuance_data'], $mo_no, $maincat_id,$_POST['ogp'], $_POST['comment'],$_SESSION['wa_current_user']->user,form_no());
	display_notification(_('New order plan has been added'));
    get_issuance_data($mo_no, $maincat_id,true);
	$Ajax->activate('items_table');

}

function line_start_focus()
{
	global 	$Ajax;
	
	$Ajax->activate('items_table');
}
if (isset($_POST['CancelItemChanges']))
line_start_focus();
if (isset($_POST['Add_Issuance'])) {
	// Create an empty array to store the form data
	$issuance_data = array();
	
	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['issuance_data']) ? $_SESSION['issuance_data'] : array();
	
	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$issuance_data['id'] = null;
	
	// Push the values of each form field into the array, including the new line number
	$issuance_data['line_no'] = $next_line_no;
    $issuance_data['si_id'] = form_no();
	$issuance_data['stk_code'] = $_POST['stk_code'];
	$issuance_data['description'] = get_description($_POST['stk_code']);
	$issuance_data['units'] = get_unit($_POST['stk_code']);
    $issuance_data['qoh'] = get_qoh_on_date($_POST['stk_code']);
    $issuance_data['required'] = required_bags($_POST['stk_code'],$maincat_id);
    $issuance_data['issued'] = $_POST['issued'];
    //$issuance_data['lot_no'] = $myrow['lot_no'];
	
	// Add the new form data to the existing array of data
	$existing_data[] = $issuance_data;
	
	// Store the updated array data in the session variable
	$_SESSION['issuance_data'] = $existing_data;
	$Ajax->activate('items_table');
}

if (isset($_POST['update_Issuance'])) {
	$edit_id = $_POST['edit_id'];
	foreach ($_SESSION['issuance_data'] as $key => $value) {
		if ($key == $edit_id) {
			$_SESSION['issuance_data'][$key]['issued'] = $_POST['issued'];
		}
	}
	$Ajax->activate('items_table');
}
//Header ----------------------------------------------------------------------------------------
div_start('items_table');
start_form(true);
start_table(TABLESTYLE_NOBORDER, "width='70%'");

echo '<tr><td>';
start_table(TABLESTYLE, "width='95%'");
label_row(_('Knitting Contract'), $mo_no);
label_row(_('Knitting Party'), get_sup_name($mo_no));
label_row(_('Date'), date('d-m-Y'), "class='label'", 0, 0, null, true);
textarea_row(_('Out Gate Pass'), 'ogp', null, 20, 1);


end_table();
echo "</td><td>";
start_table(TABLESTYLE, "width='95%'");
label_row(_('Form No'), form_no());
label_row(_('Status'), check_status($mo_no));
label_row(_('User'), $_SESSION['wa_current_user']->user, "class='label'", 0, 0, null, true);
label_row(_('Delivery Address'), get_del_add($mo_no));
end_table();

echo '</td></tr>';

end_table();
//----------------------------------------------------------------------------------------

function edit(&$order,  $line, $maincat_id)
{
    global $Ajax;
	global $id;
	
	if ($id == $line && $line != -1) {
        foreach ($order as $key => $value) {
            if ($key == $line) {
                hidden('edit_id', $key);
				$_POST['stk_code'] = $value['stk_code'];
				$_POST['issued'] = $value['issued'];
				$_POST['required'] = $value['required'];
				$_POST['qoh'] = $value['qoh'];
				$Ajax->activate('items_table');
				break;
			}
		}
		label_cell($_POST['stk_code']);
		label_cell(get_description($_POST['stk_code']));
		label_cell(get_unit($_POST['stk_code']));
        qty_cell($_POST['required']);
        qty_cell($_POST['qoh']);
        small_qty_cells_ex(null, 'issued', 0, false);
	} else {
        plan_sales_items_list_cells(null, 'stk_code', null, false, true, true, $maincat_id);
		label_cell(get_unit($_POST['stk_code']));
        qty_cell(required_bags($_POST['stk_code'],$maincat_id));
        qty_cell(get_qoh_on_date($_POST['stk_code']));
		small_qty_cells_ex(null, 'issued', 0, true);
	}
	if ($id != -1) {
        button_cell('update_Issuance', _('Update'), _('Confirm changes'), ICON_UPDATE);
        button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
	} else {
        submit_cells('Add_Issuance', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
	}
	end_row();
}
//I----------------------------------------------------------------------------------------

// var_dump($_SESSION['issuance_data']);
start_table(TABLESTYLE, "width='70%'");
$th = array(_('Yarn Code'), _('Yarn Description'), _('UoM'), _('Required Bags'), _('Available in Inventory'), _('Issued Bags'), '', '');
table_header($th);
start_row();
$id = find_row('Edit');

hidden('maincat_id', $maincat_id);
$editable_items = true;
if ($id == -1 && $editable_items)
	edit($_SESSION['issuance_data'],  -1, $maincat_id);
foreach ($_SESSION['issuance_data'] as $key => $value) {
	start_row();
		if (($id != $key || !$editable_items)) {
			label_cell($value['stk_code']);
			label_cell(get_description($value['stk_code']));
			label_cell(get_unit($value['stk_code']));
			qty_cell($value['required']);
			qty_cell($value['qoh']);
			qty_cell($value['issued']);
			edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
			delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
			if (isset($_POST['Delete' . $value['line_no']])) {
				unset($_SESSION['issuance_data'][$key]);
				$Ajax->activate('items_table');
			}
			end_row();
		} else {
			edit($_SESSION['issuance_data'], $key, $maincat_id);
		}
	}


end_table();


echo '<br>';

start_table(TABLESTYLE2);
textarea_row(_('Special Instructions:'), 'comment', null, 70, 4);
end_table(1);
submit_center_first('add_yarn',_('Add Yarn'),  _('Check entered data and save document'), 'default');
end_form();
div_end();
end_page();




