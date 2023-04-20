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
include_once($path_to_root . '/planning/includes/ui/stock_issue_ui.inc');
include_once($path_to_root . '/planning/includes/db/stock_issue_db.inc');


include($path_to_root . '/planning/includes/ui/so_plan_ui.inc');
include($path_to_root . '/planning/includes/db/so_plan_db.inc');






$js = '';
if ($SysPrefs->use_popup_windows)
$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
$js .= get_js_date_picker();
page(_($help_context = 'Stock Issuance'), @$_REQUEST['popup'], false, '', $js);

if (isset($_GET['AddedID'])) {
	$mo_no = $_GET['AddedID'];
	$main = $_GET['svc'];

	display_notification_centered(_('Stock Issuance has been processed'));
	echo '<center><a target="_blank" href="../../planning/view/view_si.php?mo_no='.$mo_no.'&svc='.$main.'" onclick="javascript:openWindow(this.href,this.target); return false;">View this Issuance</a></center>';

	hyperlink_params($_SERVER['PHP_SELF'], _('Enter &Another Issuance'), 'mo_no='.$mo_no.'&svc='.$main);

	hyperlink_params($path_to_root.'/planning/manage/stock_receive.php?', _('Enter &Receiving against this Issuance'), 'mo_no='.$mo_no.'&cat='.$main.'');

	hyperlink_no_params($path_to_root.'/planning/inquiry/mo_search.php', _('Select a different &order for stock issuance'));

	display_footer_exit();
}

if (isset($_GET['mo_no']) && $_GET['svc']) {
	unset($_SESSION['issuance_data']);
	$main = $_GET['svc'];
    $mo_no = $_GET['mo_no'];
	$check = 0;
	if(isset($_GET['ModifyIssuance'])){
		get_issuance_data($mo_no);
		$check = 1;
	}
}

hidden('mo_no', $mo_no);
hidden('main', $main);
hidden('check', $check);
if($_POST['mo_no'] && $_POST['main']){
    $mo_no = $_POST['mo_no'];
	$main = $_POST['main'];
}
if($_POST['check'])
	$check = $_POST['check'];
//function ---------------------------------------------------------------------------------------------
if(isset($_POST['add_issue'])){

	if(!isset($_SESSION['issuance_data'])){
		display_error(_('No items selected.'));
		set_focus('stk_code');
		$Ajax->activate('items_table');
		return;
	}

	if(($main == '00-01'))
		foreach($_SESSION['issuance_data'] as $line_no => $line) {
			if($line['issued'] == 0){
				display_error(_('No Issued Bags.'));
				set_focus('stk_code');
				$Ajax->activate('items_table');
				return;
			}
			if($line['issued'] > $line['qoh']){
				display_error(_('Issued Bags is greater than QOH.'));
				set_focus('stk_code');
				$Ajax->activate('items_table');
				return;
			}
			if($line['issued'] > $line['required']){
				display_error(_('Issued Bags is greater than Required.'));
				set_focus('stk_code');
				$Ajax->activate('items_table');
				return;
			}
			$already_issued = already_issued($mo_no, $line['stk_code']);
			if($line['issued'] > $line['required'] - $already_issued){
				display_error(_('Issued Bags is greater than Required.'));
				set_focus('stk_code');
				$Ajax->activate('items_table');
				return;
			}
		}

	add_issuance_database($_SESSION['issuance_data'], $mo_no, $_POST['maincat'],$_POST['ogp'], $_POST['comment'],
	$_SESSION['wa_current_user']->user,temp_form_no($mo_no, 'stock_issue '), $_POST['sorder']);

	meta_forward($_SERVER['PHP_SELF'], 'AddedID='.$mo_no.'&svc='.$main);

}

function line_start_focus()
{
	global 	$Ajax;
	
	$Ajax->activate('items_table');
}
if (isset($_POST['CancelItemChanges']))
	line_start_focus();

if (isset($_POST['Add_Issuance'])) {
	//do not add if required qty is 0
	if((required_bags($_POST['stk_code'],$_POST['stk_code'][1]) == 0) && ($main == '00-01')){
		display_error(_('No required bags.'));
		set_focus('stk_code');
		$Ajax->activate('items_table');
		return;
	}
	if(($_POST['issued'] >= check_rec_qty($mo_no,$_POST['sorder'])) && ($main == '00-01')){
		display_error(_('Greater than receive limit.'));
		set_focus('stk_code');
		$Ajax->activate('items_table');
		return;
	}
	// Create an empty array to store the form data
	$issuance_data = array();
	
	// Retrieve the existing array of data from the session variable
	$existing_data = isset($_SESSION['issuance_data']) ? $_SESSION['issuance_data'] : array();

	// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
	$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
	$issuance_data['id'] = null;
	
	// Push the values of each form field into the array, including the new line number
	$issuance_data['line_no'] = $next_line_no;
    $issuance_data['si_id'] = detail_id('si_id','stock_issue ');
	$issuance_data['stk_code'] = $_POST['stk_code'];
	$issuance_data['description'] = get_description($_POST['stk_code']);
	$issuance_data['units'] = get_unit($_POST['stk_code']);
    $issuance_data['qoh'] = get_qoh_on_date($_POST['stk_code']);
    $issuance_data['required'] = required_bags($_POST['stk_code'],$_POST['stk_code'][1]);
    $issuance_data['issued'] = $_POST['issued'];

    $issuance_data['lot_no'] = $_POST['lot_no'];
	
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
			$_SESSION['issuance_data'][$key]['lot_no'] = $_POST['lot_no'];
		}
	}
	$Ajax->activate('items_table');
}
//Header ----------------------------------------------------------------------------------------
div_start('items_table');
start_form(true);
start_table(TABLESTYLE_NOBORDER, "width='70%'");
if($main == '00-01'){
	display_heading("Knitting");

}
elseif($main == '00-02'){
	display_heading("Dyeing");
}

echo '<tr><td>';
start_table(TABLESTYLE, "width='95%'");
label_row(_('Order No'), $mo_no);
sale_dropdown_list_cells('Sale Order No', 'sorder', true, $mo_no);

label_row(_('Manufacturer'), get_sup_name($mo_no));


end_table();
echo "</td><td>";
start_table(TABLESTYLE, "width='95%'");
label_row(_('Date'), Today(), "class='label'", 0, 0, null, true);
if($check == 1)
	label_row(_('Issue Form No'), get_form_no($mo_no,'stock_issue'));
else
	label_row(_('Issue Form No'), temp_form_no($mo_no, 'stock_issue '));
// label_row(_('Status'), check_status($mo_no));
// label_row(_('Entry By'), get_user_name(form_no()), "class='label'", 0, 0, null, true);
label_row(_('Delivery Address'), get_del_add($mo_no));
// text_row(_('Out Gate Pass'), 'ogp', null, 20, 20);
end_table();

echo '</td></tr>';

end_table();
//----------------------------------------------------------------------------------------

function edit(&$order,  $line, $maincat_id, $maincat_id_2)
{
    global $Ajax;
	global $id;
	global $mo_no;
	global $main;

	
	if ($id == $line && $line != -1) {
		foreach ($order as $key => $value) {
			if ($key == $line) {
				hidden('edit_id', $key);
				$_POST['stk_code'] = $value['stk_code'];
				$_POST['issued'] = $value['issued'];
				$_POST['required'] = $value['required'];
				$_POST['qoh'] = $value['qoh'];
				$_POST['lot_no'] = $value['lot_no'];
				$Ajax->activate('items_table');
				break;
			}
		}
		if($main == '00-01'){
			label_cell($_POST['stk_code']);
			label_cell(get_description($_POST['stk_code']));
			label_cell(get_unit($_POST['stk_code']));
			qty_cell($_POST['required']);
			qty_cell(already_issued($mo_no, $_POST['stk_code']));
			qty_cell($_POST['qoh']);
			small_qty_cells_ex(null, 'issued', 0, false);
		}
		else if($main == '00-02'){
			lot_no_item_list_cells(null, 'lot_no', false, $mo_no);

			qty_cell(get_Rolls_count($_POST['lot_no']));
			qty_cell(get_weight($_POST['lot_no']));
			hidden('issued', 0);

		}
	} else {
		if($main == '00-01'){
		plan_sales_items_list_cells(null, 'stk_code', null, false, true, true, $maincat_id, $maincat_id_2, $mo_no, $_POST['sorder']);
		label_cell(get_unit($_POST['stk_code']));
        qty_cell(required_bags($_POST['stk_code'],$_POST['stk_code'][1]));
		foreach ($order as $key => $value) {
			$sum_issued += (float) $value["issued"];
		}
		$already_issued = already_issued($mo_no, $_POST['stk_code']) + $sum_issued;
		qty_cell($already_issued);
		
        qty_cell(get_qoh($_POST['stk_code'], $mo_no, $_POST['sorder']));
		
		if(get_qoh_on_date($_POST['stk_code'])<0)
			display_warning(_('Insufficient quantity in hand for selected item.'));
		small_qty_cells_ex(null, 'issued', 1, false);

		if(($_POST['issued']>=required_bags($_POST['stk_code'],$_POST['stk_code'][1])))
			display_warning(_('You can not issue more than required  bags.'));
		hidden('maincat',1);
	}
	elseif($main == '00-02'){
		lot_no_item_list_cells(null, 'lot_no', true, $mo_no);
		qty_cell(get_Rolls_count($_POST['lot_no']));
		qty_cell(get_weight($_POST['lot_no']));
		hidden('issued', 0);
		hidden('maincat',4);


	}
	}
	if ($id != -1) {
        button_cell('update_Issuance', _('Update'), _('Confirm changes'), ICON_UPDATE);
        button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
	} else {
        submit_cells('Add_Issuance', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
	}
	end_row();

}

//------------------------------------------------------------------------------------------

// var_dump($_SESSION['issuance_data']);
if($main == '00-01'){
start_table(TABLESTYLE, "width='70%'");
$th = array(_('Yarn Code'), _('Yarn Description'), _('UoM'), _('Required Bags'), _('Already issued'), _('Available in Inventory'), _('Issued Bags'), '', '');
table_header($th);
start_row();
$id = find_row('Edit');
if($main == '00-01'){
	$maincat_id = 1;
	$maincat_id_2 = 2;
}
elseif($main == '00-02'){
	$maincat_id = 1;
	$maincat_id_2 = 3;
}
hidden('maincat_id', $maincat_id);
hidden('maincat_id_2', $maincat_id_2);
$editable_items = true;
if ($id == -1 && $editable_items)
	edit($_SESSION['issuance_data'],  -1, $maincat_id, $maincat_id_2);
foreach ($_SESSION['issuance_data'] as $key => $value) {
	start_row();
		if (($id != $key || !$editable_items)) {
			label_cell($value['stk_code']);
			label_cell(get_description($value['stk_code']));
			label_cell(get_unit($value['stk_code']));
			qty_cell($value['required']);
			qty_cell(already_issued($mo_no, $value['stk_code']));
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
			edit($_SESSION['issuance_data'], $key, $maincat_id, $maincat_id_2);
		}
	}
}

elseif($main == '00-02'){
	$maincat_id = 1;
$maincat_id_2 = 3;
hidden('maincat_id', $maincat_id);
hidden('maincat_id_2', $maincat_id_2);
	start_table(TABLESTYLE, "width='70%'");
	$th = array(_('LOT number'), _('Number of Rolls'), _('Total Weigth'), '', '');
	table_header($th);
	start_row();
	$id = find_row('Edit');
	
	hidden('maincat_id', $maincat_id);
	$editable_items = true;
	if ($id == -1 && $editable_items)
		edit($_SESSION['issuance_data'],  -1, $maincat_id, $maincat_id_2);

	foreach ($_SESSION['issuance_data'] as $key => $value) {
		start_row();
			if (($id != $key || !$editable_items)) {
				label_cell($value['lot_no']);
				qty_cell(get_Rolls_count($value['lot_no']));
				qty_cell(get_weight($value['lot_no']));
				edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
				delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
				if (isset($_POST['Delete' . $value['line_no']])) {
					unset($_SESSION['issuance_data'][$key]);
					$Ajax->activate('items_table');
				}
				end_row();
			} else {
				edit($_SESSION['issuance_data'], $key, $maincat_id, $maincat_id_2);


			}

		}

}

end_table();

echo '<br>';

start_table(TABLESTYLE2);
textarea_row(_('Special Instructions:'), 'comment', null, 70, 4);
end_table(1);
if($check == 1)
	submit_center_first('add_issue',_('Comit Changes'),  _('Check entered data and save document'), 'default');
else
	submit_center_first('add_issue',_('Issue Stock'),  _('Check entered data and save document'), 'default');

end_form();
div_end();
end_page();