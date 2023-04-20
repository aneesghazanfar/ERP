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
include_once($path_to_root . '/planning/includes/db/stock_issue_db.inc');
include_once($path_to_root . '/planning/includes/ui/stock_issue_ui.inc');


include($path_to_root . '/planning/includes/ui/so_plan_ui.inc');
include($path_to_root . '/planning/includes/db/so_plan_db.inc');

$js = '';
if ($SysPrefs->use_popup_windows)
$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
$js .= get_js_date_picker();
page(_($help_context = 'Stock Issuance'), @$_REQUEST['popup'], false, '', $js);
if (isset($_GET['mo_no'])) {
    $main = $_GET['svc'];
    $mo_no = $_GET['mo_no'];
}
hidden('mo_no', $mo_no);
hidden('main', $main);
if($_POST['mo_no'] && $_POST['main']){
    $mo_no = $_POST['mo_no'];
	$main = $_POST['main'];
}
//data fetching ---------------------------------------------------------------------------------------------
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
label_row(_('Issue Form No'), $mo_no);
label_row(_('Manufacturer'), get_sup_name($mo_no));
label_row(_('Delivery Address'), get_del_add($mo_no));
end_table();
echo "</td><td>";
start_table(TABLESTYLE, "width='95%'");
// label_row(_('Out Gate Pass'), $stock_data['ogp']);
end_table();
echo '</td></tr>';
end_table();
//Line Details------------------------------------------------------------------------------------------
echo '<br>';
display_heading2(_('Line Details'));
$stock_data = get_si_data($mo_no, 'stock_issue ');
if($main == '00-01'){
    start_table(TABLESTYLE, "width='70%'");
    $th = array(_('Issue Date'), _('Issued By'), _('Yarn Code'), _('Yarn Description'), _('UoM'), _('Required Bags'), _('Issued'), _('Available in Inventory'), _('Status'), _('Approval Date'), _('Approved By'));
    table_header($th);
    while ($myrow = db_fetch($stock_data)) {
        $si_line_details = get_line_details('si_id ', 'stock_issue_details ', $myrow['si_id']);
        while ($myrow1 = db_fetch($si_line_details)) {
            start_row();
            label_cell($myrow['issue_date']);
            label_cell(get_user($myrow['entry_by'])['real_name']);
            label_cell($myrow1['stk_code']);
            label_cell(get_description($myrow1['stk_code']));
            label_cell(get_unit($myrow1['stk_code']));
            qty_cell($myrow1['required']);
            qty_cell($myrow1['issued']);
            qty_cell(get_qoh_on_date($myrow1['stk_code'], $myrow['issue_date']));
            
            
            
        $status = $myrow['approval_date'] ? 'Approved' : 'Not Approved';
        label_cell($status);
        label_cell($myrow['approval_date']);
        $username = get_user($myrow['approve_by'])['real_name'];
        label_cell($username);
        end_row();
    }
}
}
elseif($main == '00-02'){
	start_table(TABLESTYLE, "width='70%'");
	$th = array(_('LOT number'), _('Number of Rolls'), _('Total Weigth'));
	table_header($th);
    while ($myrow = db_fetch($si_line_details)) {
        start_row();
        label_cell($myrow['lot_no']);
        qty_cell(get_Rolls_count($_POST['lot_no']));
		qty_cell(get_weight($_POST['lot_no']));
        end_row();
    }
}
end_table();
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