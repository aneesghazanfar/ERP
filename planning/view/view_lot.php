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

include_once($path_to_root . '/planning/includes/db/receive_db.inc');
include_once($path_to_root . '/planning/includes/ui/receive_ui.inc');
include_once($path_to_root . '/planning/includes/ui/lot_ui.inc');
include_once($path_to_root . '/planning/includes/db/lot_db.inc');
include($path_to_root . '/planning/includes/ui/so_plan_ui.inc');
include($path_to_root . '/planning/includes/db/so_plan_db.inc');

$js = '';
if ($SysPrefs->use_popup_windows)
$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
$js .= get_js_date_picker();
page(_($help_context = 'Lot View'), @$_REQUEST['popup'], false, '', $js);

if (isset($_GET['mo_no'])) {
    $mo_no = $_GET['mo_no'];
}


start_table(TABLESTYLE_NOBORDER, "width='70%'");

display_heading("Lot Making");
echo '<tr><td>';
start_table(TABLESTYLE, "width='95%'");
label_row(_('Contract'), $mo_no);
label_row(_('Manufacturer'), get_sup_name($mo_no));
label_row(_('Export Contract'), get_sorder_no($mo_no));
label_row(_('Color'), get_color($mo_no));
// label_row(_('Style'), get_style($mo_no));
label_row(_('Fabric Description'),get_fab_description($mo_no));
end_table();
echo "</td>";
echo '<td>';
start_table(TABLESTYLE, "width='95%'");
$lot_no = "lot_".get_next_roll_no($mo_no)."_".$mo_no."_".get_number();
hidden('lot_no', $lot_no);
label_row(_('LOT Number'), $lot_no);
// label_cell(_('Form No'), "class='label'");
label_row(_('Form No'),detail_id('lot_id','lot '));
// qty_cell(detail_id('lot_id','lot '));

label_row(_('Status'), temp_check_status($mo_no,'lot '));
// foreach ($_SESSION['lot_data'] as $key => $value) {
// 	$roll_count = $key + 1;
// 	$roll_weight += get_roll_weight($value['roll_no']);
// }
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

display_heading2(_('Line Details'));
start_table(TABLESTYLE, "width='70%'");
$th = array(_('Issue Date'), _('Issued By'), _('Roll No'), _('UoM'), _('Weight'), _('Status'), _('Approval Date'), _('Approved By'));
table_header($th);
$result = get_si_data($mo_no, 'lot ');
while ($myrow = db_fetch($result)) {
	$lot_line_details = get_line_details('lot_id ', 'lot_details ', $myrow['lot_id']);
	// var_dump(count($lot_line_details));
	// var_dump($lot_line_details);
		start_row();
	while ($myrow1 = db_fetch($lot_line_details)) {
			
		label_cell(sql2date($myrow['lot_date']));
		label_cell(get_user($myrow['entry_by'])['real_name']);
	
		label_cell($myrow1['roll_no']);
		label_cell(get_uom_style('units', $mo_no));
		// similar function"get_weight()" is used in issue_ui.inc we have to change it
		label_cell(get_roll_weight($myrow1['roll_no']));
	
		$status = $myrow['approval_date'] ? 'Approved' : 'Not Approved';
		label_cell($status);
		label_cell($myrow['approval_date']);
		$username = get_user($myrow['approve_by'])['real_name'];
		label_cell($username);
		end_row();
	
	}
}
