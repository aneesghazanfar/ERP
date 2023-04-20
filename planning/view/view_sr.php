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

include_once($path_to_root . '/planning/includes/db/receive_db.inc');
include_once($path_to_root . '/planning/includes/ui/receive_ui.inc');


$js = '';
if ($SysPrefs->use_popup_windows)
$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
$js .= get_js_date_picker();
page(_($help_context = 'Stock Issuance'), @$_REQUEST['popup'], false, '', $js);
if (isset($_GET['mo_no']) || isset($_GET['cat'])) {
	unset($_SESSION['receive_data']);
    $mo_no = $_GET['mo_no'];
	$cat = $_GET['cat'];
	if(isset($_GET['ModifyReceive'])){
		get_receive_data($mo_no);
		$check = 1;
	}
	
}
hidden('check', $check);
hidden('cat', $cat);
hidden('mo_no', $mo_no);
if($_POST['mo_no'] && $_POST['cat']){
	$mo_no= $_POST['mo_no'];
	$cat = $_POST['cat'];
}
//data fetching ---------------------------------------------------------------------------------------------
$stock_data = get_si_data($mo_no, 'stock_rec ');
//Header ----------------------------------------------------------------------------------------
div_start('items_table');
start_form(true);
// start_table(TABLESTYLE_NOBORDER, "width='70%'");

// echo '<tr><td>';
if($cat=='00-01')
	display_heading("Fabric Receive");
if($cat=='00-02')
	display_heading("Dyed Receive");
start_table(TABLESTYLE, "width='95%'");
label_row(_('Contract'), $mo_no);
label_row(_('Form No'), temp_form_no($mo_no, 'stock_rec '));
label_row(_('Manufacturer'), get_sup_name($mo_no));
label_row(_('Fabric Description'),get_fab_description($mo_no));
// text_row(_('IGP Reference'), 'igp_ref', null, 20, 20);

end_table();
// echo "</td>";
// echo '<td>';
// start_table(TABLESTYLE, "width='95%'");
// if($cat=='00-01'){
//     label_cell(_('Required Quantity'), "class='label'");
//     qty_cell (get_req_qty($mo_no));
//     start_row();
//     label_cell(_('Already Received'), "class='label'");
//     qty_cell (get_rec_qty($mo_no));
//     start_row();
//     label_cell(_('Received in this Form'), "class='label'");
//     foreach ($_SESSION['receive_data'] as $key => $value) {
//         $total_rec += $value['weight'];
        
//     }
//     qty_cell ($total_rec);
// }
// elseif($cat=='00-02'){
//     lot_no_item_list_cells('LOT Number', 'lot_no', false, $mo_no);
// 	start_row();
// 	label_cell(_('Total Rolls'), "class='label'");
// 	qty_cell (get_roll_qty($mo_no));
// 	start_row();
// 	label_cell(_('Total Weights'), "class='label'");
// 	qty_cell (get_weight_qty($mo_no));
// }

// end_table();
// echo "</td><td>";

// start_table(TABLESTYLE, "width='95%'");

// // label_row(_('Entry By'), get_user_name($mo_no), "class='label'", 0, 0, null, true);
// // label_row(_('Date'),null, "class='label'", 0, 0, null, true);
// // label_row(_('Status'), temp_check_status($mo_no,'stock_rec '));
// end_table();

// echo '</td></tr>';

// end_table();
//Line Details------------------------------------------------------------------------------------------
echo '<br>';
start_table(TABLESTYLE, "width='70%'");

display_heading2(_('Line Details'));
if($cat == '00-01')
	$th = array(_('Issue Date'), _('Sale Order NO'), _('Roll No.'), _('UOM'), _('Weight'), _('Width'), _('Ply'), _('GSM'),_('Knit'),_('Tuck'),_('Loop'), _('Fault Code'), _('Fault Description'), _('Total Faults'), _('Allowed Faults'), _('Status'),_('Approval Status'), _('Approval Date'),_('Approved By'),);
elseif($cat == '00-02')
	$th = array(_('Issue Date'), _('Sale Order NO'), _('Roll No.'), _('UOM'),_('Style ID'), _('Weight'), _('Width'), _('Ply'), _('GSM'), _('Fault Code'), _('Fault Description'), _('Total Faults'), _('Allowed Faults'), _('Status'),_('Approval Status'), _('Approval Date'),_('Approved By'),);
table_header($th);
$stock_data = get_si_data($mo_no, 'stock_rec ');
while ($myrow = db_fetch($stock_data)) {
	$si_line_details = get_line_details('sr_id ', 'stock_rec_details ', $myrow['sr_id']);
	while ($myrow1 = db_fetch($si_line_details)) {
		start_row();
		label_cell(sql2date($myrow['rec_date']));
		label_cell($myrow1['sorder_no']);
		label_cell($myrow1['roll_no']);
		label_cell(get_uom_style('units', $mo_no));
		if($cat == '00-02'){
			label_cell($myrow1['style_id']);
		}
		label_cell($myrow1['weight']);
		label_cell($myrow1['width']);
		label_cell($myrow1['ply']);
		label_cell($myrow1['gsm']);
		if($cat == '00-01'){
			label_cell($myrow1['knit']);
			label_cell($myrow1['tuck']);
			label_cell($myrow1['q_loop']);
		}
		label_cell($myrow1['fault_id']);
		$fault_desc = implode(', ',(get_fault_description($myrow1['fault_id'])));
		label_cell($fault_desc);
		qty_cell(count(explode(", ", $myrow1['fault_id'])));
		label_cell($myrow1['fault_allow']);
		label_cell($myrow1['result']);

		$status = $myrow['approval_date'] ? 'Approved' : 'Not Approved';
        label_cell($status);
        label_cell($myrow['approval_date']);
        $username = get_user($myrow['approve_by'])['real_name'];
        label_cell($username);
        end_row();

}


}

end_table(1);
//Footer ----------------------------------------------------------------------------------------
if (db_num_rows($stock_data) == 0) {
    echo "<center>";
    echo "<br>";
    echo '<b>No Stock Issued for this Order.</b>';
    echo "</center>";
}
echo '<br><br>';
end_form();
div_end();
end_page(true, false, false, ST_PURCHORDER, $_GET['mo_no']);