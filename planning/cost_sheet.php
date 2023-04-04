<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
$page_security = 'SA_SALESORDER';
$path_to_root = '..';
include_once($path_to_root.'/includes/session.inc');
include_once($path_to_root.'/includes/ui.inc');
include($path_to_root . '/planning/includes/db/so_plan_db.inc');
include($path_to_root . '/planning/includes/ui/so_plan_ui.inc');
include_once($path_to_root . '/sales/includes/ui/cost_sheet_ui.inc');
include_once($path_to_root . '/includes/ui/ui_lists.inc');
include_once($path_to_root . '/sales/includes/db/cost_sheet_db.inc');


$js = '';
if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 600);
if (user_use_date_picker())
	$js .= get_js_date_picker();
page(_($help_context = 'Manage Purchase Plans'), @$_REQUEST['popup'], false, '', $js);

if (isset($_GET['cs_id'])) {
	unset($_SESSION['cost_data']);
}
$cs_id = get_cs_id();
hidden('cs_id', $cs_id);
// else{
simple_page_mode(true);
//functions-------------------------------------------------------------------------------------------------------------------
function edit_yan(&$order,  $line, $maincat_id, $maincat_id_2)
{
	global $Ajax;
	$id = find_row('Edit');
	
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
		small_qty_cells_ex(null, 'yconsumption', 0, true);
		small_qty_cells_ex(null, 'yrate', 0, true);
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
		small_qty_cells_ex(null, 'rate', 0, true);
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

//Header Table -----------------------------------------------------------------------------------------
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
textarea_cells(_('Special Instructions:'), 'sp_ins', null, 30, 2,'','',"class='label'");
end_table();
echo "</td><td>";
start_table(TABLESTYLE, "width='95%'");
label_cell("Image", "class='label'");
file_cells(null, 'image', 'image');
foreach (array('jpg', 'png', 'gif') as $ext) {
	if($_POST['cs_id']!=null)
		$filename = $_POST['cs_id'];
	else
		$filename = $cs_id;

		$file = company_path().'/images/'. $filename .'.'.$ext;
	
	if (file_exists($file)) {
		$stock_img_link = "<img id='item_img' alt = 'no image found' src='".$file."?nocache=".rand()."'"." height='".$SysPrefs->pic_height."' border='0'>";
		break;
	}
}
	label_cell( $stock_img_link,);
end_table();



echo '</td></tr>';
end_table();//Header Table End----------------------------------------------------------------------------------------------
function fabric_1() {
	echo "<br>";
	start_table(TABLESTYLE, "width=70%");
	get_cost_data(1);
var_dump($_SESSION['cost_data']);
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
foreach ($_SESSION['cost_data'] as $key => $value) {
	start_row();
	if (($value['maincat_id'] == $yan_maincat) || ($value['maincat_id'] == $yan_maincat_2)) {
		if (($id != $key || !$editable_items)) {
			label_cell($value['stk_code']);
			label_cell(get_description($value['stk_code']));
			label_cell(get_unit($value['stk_code']));
			qty_cell($value['consumption']);
			qty_cell($value['rate']);
			qty_cell($value['amount']);
			$yarn_cost = $yarn_cost + $value['amount'];
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
label_row(_('Yarn Cost'), $yarn_cost, "colspan=5 align='right'");
small_qty_cells_ex(_('Knitting Charges/Bag'), 'Knitting_Charges', '', true, "colspan=5 align='right'");
start_row();
small_qty_cells_ex(_('Knitting Waste %'), 'Knitting_waste', '', true, "colspan=5 align='right'");

end_row();
start_row();

plan_sales_items_list_cells(null, 'pstk_code', null, false, true, true, 4);
label_cell(get_unit($_POST['pstk_code']));
label_cells(_('Yarn Cost'), $yarn_cost, "colspan=2 align='right'");
label_row(_('Greige Fab Cost/kg'), $yarn_cost, "colspan=5 align='right'");
start_row();
small_qty_cells_ex(_('Dyeing Charges/Kg'), 'Knitting_waste', '', true, "colspan=5 align='right'");
start_row();
small_qty_cells_ex(_('Dyeing Waste %'), 'Knitting_waste', '', true, "colspan=5 align='right'");
start_row();
small_qty_cells_ex(_('Dyed Fab / Piece (Kg)'), 'Knitting_waste', '', true, "colspan=5 align='right'");
label_row(_('Dyed Fabric Cost'), $yarn_cost, "colspan=5 align='right'");


end_row();
end_table(1);
}
	function fabric_2() {
		echo '2';
	}
		function fabric_3() {
			echo '3';
		}
			function fabric_4() {
				echo '4';
			}
				function fabric_5() {
					echo '5';
				}

start_form(true);
tabbed_content_start('tabs', array(
	'fab1' => array(_('Dyed Fab 1'), true),
	'fab2' => array(_('Dyed Fab 2'), true),
	'fab3' => array(_('Dyed Fab 3'), true),
	'fab4' => array(_('Dyed Fab 4'), true),
	'fab5' => array(_('Dyed Fab 5'), true),
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
	};
br();
tabbed_content_end();

end_form();
//acc table-------------------------------------------------------------------------------------------

// start_table(TABLESTYLE, "width=90%");
// $th = array(_('Acc Code'), _('Acc Desc'), _('UoM'), _('Rate/Kgs'), _('Consumption per Piece(In Grams)'), _('Total'), '', '');
// table_header($th);
// start_row();
// $id = find_row('Edit');
// $accabric_maincat = 5;
// $accabric_maincat_2 = 6;
// hidden('accabric_maincat', $accabric_maincat);
// $editable_items = true;
// if ($id == -1 && $editable_items)
// 	edit_acc($_SESSION['cost_data'],  -1, $accabric_maincat, $accabric_maincat_2);
// foreach ($_SESSION['cost_data'] as $key => $value) {
// 	start_row();
// 	if ($value['maincat_id'] == $accabric_maincat || $value['maincat_id'] == $accabric_maincat_2) {
// 		if (($id != $key || !$editable_items)) {
// 			label_cell($value['stk_code']);
// 			label_cell(get_description($value['stk_code']));
// 			label_cell(get_unit($value['stk_code']));
// 			qty_cell($value['rate']);
// 			qty_cell($value['consumption']);
// 			qty_cell(amount());
// 			edit_button_cell('Edit' . $value['line_no'], _('Edit'), _('Edit document line'));
// 			delete_button_cell('Delete' . $value['line_no'], _('Delete'), _('Remove line from document'));
// 			if (isset($_POST['Delete' . $value['line_no']])) {
// 				unset($_SESSION['cost_data'][$key]);
// 				$Ajax->activate('items_table');
// 			}
// 			end_row();
// 		} else {
// 			edit_acc($_SESSION['cost_data'], $key, $accabric_maincat, $accabric_maincat_2);
// 		}
// 	}
// }
// label_row(_('Acessories Cost'), amount(), "colspan=5 align='right'");
// end_table(1);

// //footer table-------------------------------------------------------------------------------------------

// start_table(TABLESTYLE_NOBORDER, "width='93%'");

// echo '<tr><td>';

// start_table(TABLESTYLE, "width='95%'");

// label_cell_text('Overhead/Piece','over_persentage');
// qty_cell(amount());
// hidden('overhead', amount());

// label_row(_('Net Manufacturing Cost'), amount());
// qty_row(_('Local Freight Charges'),'local_freight') ;
// qty_row(_('Container Freight'), 'container_freight');
// qty_row(_('Insurance Charges'), 'insurance');
// label_row(_('Total Price per Piece'), amount());
// end_table();
// echo "</td><td>";

// start_table(TABLESTYLE,"width='95%'");
// label_cell_text('Commission','com_persentage');
// hidden('commission', amount());
// qty_cell(amount());
// end_row();
// $tab = "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
// label_cell_text('Profit'.$tab,'pro_persentage');
// hidden('profit', amount());
// qty_cell(amount());
// label_row(_('PKR Sale Price per Piece'), amount());
// qty_row(_('Exchange Rate'), 'exchange_rate');
// qty_row(_('Sale Price in Foreign Currency'), amount());
// end_table();

// echo '</td></tr>';
// end_table();
echo '<br>';
submit_center_first('add_Cost',_('Place Cost'),  _('Check entered data and save document'), 'default');



end_form();
div_end();
//----------------------------------------------------------------------------------------------
end_page();
