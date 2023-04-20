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

include_once($path_to_root . '/planning/includes/db/receive_db.inc');
include_once($path_to_root . '/planning/includes/ui/receive_ui.inc');

$js = '';
if ($SysPrefs->use_popup_windows)
$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
$js .= get_js_date_picker();
page(_($help_context = 'Approve Stock Issuance'), @$_REQUEST['popup'], false, '', $js);
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
$stock_data = get_si_data($mo_no, 'stock_rec ');
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
echo '</td></tr>';
end_table();
//Line Details------------------------------------------------------------------------------------------
echo '<br>';
start_table(TABLESTYLE, "width='70%'");

display_heading2(_('Line Details'));
if($main == '00-01')
	$th = array(_('Issue Date'), _('Sale Order NO'), _('Roll No.'), _('UOM'), _('Weight'), _('Width'), _('Ply'), _('GSM'),_('Knit'),_('Tuck'),_('Loop'), _('Fault Code'), _('Fault Description'), _('Total Faults'), _('Allowed Faults'), _('Status'),_('Approval Status'), _('Approval Date'),_('Approved By'),);
elseif($main == '00-02')
	$th = array(_('Issue Date'), _('Sale Order NO'), _('Roll No.'), _('UOM'),_('Style ID'), _('Weight'), _('Width'), _('Ply'), _('GSM'), _('Fault Code'), _('Fault Description'), _('Total Faults'), _('Allowed Faults'), _('Status'),_('Approval Status'), _('Approval Date'),_('Approved By'),);
table_header($th);
$stock_data = get_si_data($mo_no, 'stock_rec ');
while ($myrow = db_fetch($stock_data)) {
	$si_line_details = get_line_details('sr_id ', 'stock_rec_details ', $myrow['sr_id']);
	while ($myrow1 = db_fetch($si_line_details)) {
		start_row();
		label_cell(sql2date($myrow['rec_date']));
		label_cell($myrow1['roll_no']);
		label_cell(get_uom_style('units', $mo_no));
		if($main == '00-02'){
			label_cell($myrow1['style_id']);
		}
		label_cell($myrow1['weight']);
		label_cell($myrow1['width']);
		label_cell($myrow1['ply']);
		label_cell($myrow1['gsm']);
		if($main == '00-01'){
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
        if($status == 'Not Approved')
            $count++;
        label_cell($status);
        label_cell($myrow['approval_date']);
        $username = get_user($myrow['approve_by'])['real_name'];
        label_cell($username);
        end_row();

}


}

end_table(1);
echo '<br>';
//Footer ----------------------------------------------------------------------------------------
echo "<center>";
echo "<br>";
if ($count == 0 && db_num_rows($stock_data) == 0) {
    echo '<b>No Stock Issued for this Order.</b>';
}
elseif($count == 0){
    echo '<b>This Stock Issue is already approved.</b>';
} else {
    echo '<form method="post">';
        echo '<button type="submit" name="approve_si" class="inputsubmit" ><i class="'.ICON_SUBMIT.'" onclick="javascript:closeWindow();" ></i>&nbsp;Approve</button>';
    echo '</form>';
}
echo "</center>";

//Checkout the Post request for Approve button
if (isset($_POST['approve_si'])) {
    //Approve only those SI which are not approved
    $approve_data = get_si_data($mo_no, 'stock_rec ');
    while ($myrow = db_fetch($approve_data)) {
        if($myrow['approval_date'] == NULL){
            approve_si_sr('stock_rec ', 'sr_id',  $myrow['sr_id']);
        }
    }
    $_SESSION['message'] = "Stock Receive Approved Successfully";
	echo "<script>
			window.opener.location.reload();
			window.close();
		</script>";
	exit;
}
echo '<br><br>';
end_form();
div_end();
end_page();




