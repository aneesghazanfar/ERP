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


$path_to_root = '..';
$page_security = 'SA_SALESORDER';

include_once($path_to_root.'/purchasing/includes/po_class.inc');
include_once($path_to_root . '/includes/session.inc');
include_once($path_to_root . '/sales/includes/sales_ui.inc');
include_once($path_to_root . '/sales/includes/ui/sales_order_ui.inc');
include_once($path_to_root . '/sales/includes/sales_db.inc');
include_once($path_to_root . '/sales/includes/db/sales_types_db.inc');
include_once($path_to_root . '/reporting/includes/reporting.inc');
include_once($path_to_root . '/sales/includes/db/sales_order_plan_db.inc');


$js = '';

if ($SysPrefs->use_popup_windows)
	$js .= get_js_open_window(900, 500);

if (user_use_date_picker())
	$js .= get_js_date_picker();


if (isset($_GET['OrderNumber'])) {
$order_no= $_GET['OrderNumber'];
}
else $order_no = $_POST['order_no'];
hidden('order_no', $order_no);
$maincat_id = 4;
hidden('maincat_id', $maincat_id);

// start
// Anees Ghazanfar 25/2/2023
//Description: add New function for Total Required	formula

//end.
if (isset($_GET['OrderNumber']) && is_numeric($_GET['OrderNumber'])) {
	$help_context = 'Plan Sales Order';
	$_SESSION['page_title'] = sprintf( _('Plan Sales Order # %d'), $_GET['OrderNumber']);

	unset($_SESSION['form_data']);

$result = get_all_detail($order_no);
$line_no = 1;
while($myrow = db_fetch($result)) {
	$form_data['line_no'] =$line_no++;
	$form_data['id'] = $myrow['id'];
	$form_data['style_id'] = $myrow['style_id'];
    $form_data['stock_id'] = $myrow['stk_code'];
	$form_data['stk_type'] = $myrow['stk_type'];
	$form_data['t_style_qty']  = get_total_quantity($order_no, $myrow['style_id']);
	$form_data['description'] = get_description($myrow['stk_code']);
	$form_data['units'] = get_unit($myrow['stk_code']);
    $form_data['perpc'] = $myrow['perpc'];
    $form_data['stk_extra'] = $myrow['stk_extra'];
	$form_data['stk_total'] = $myrow['stk_total'];
	$form_data['filename'] = $myrow['filename'];	
	$form_data['unique_name'] = $myrow['unique_name'];
	$form_data['req_date'] = $myrow['req_date'];

	$existing_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : array();

    // Add the new form data to the existing array
    $existing_data[] = $form_data;

    // Store the updated array data in the session variable
    $_SESSION['form_data'] = $existing_data;

}

}

page($_SESSION['page_title'], false, false, '', $js);

function total_required($t_style_qty , $perpc , $stk_extra){
	if($stk_extra != 0)
		return $t_style_qty * ( $perpc + (($perpc / $stk_extra)));
	else
		return 0;



}

// function add_new_item() {
// 	add_plan($_POST['order_no'], $_POST['style_id'], $_POST['maincat_id'],$_POST['stock_id'], $_POST['perpc'], $_POST['stk_extra'],date2sql($_POST['req_date']));
// 	display_notification(_('New order plan has been added'));
// }
$unique_name = $order_no."-".$_POST['style_id']."-".$_POST['stock_id']."-".get_description($_POST['stock_id']);

if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
	$order_no = $_POST['order_no'];
	$result = $_FILES['image']['error'];
	$upload_file = 'Yes'; //Assume all is well to start off with
	$filename = company_path().'/images';
	
	if (!file_exists($filename))
		mkdir($filename);
	

	$filename .= '/'. item_img_name($unique_name).(substr(trim($_FILES['image']['name']), strrpos($_FILES['image']['name'], '.')));


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
	$Ajax->activate('items_table');
	$Ajax->activate('edit_items_table');
}


if(isset($_POST['update'])) {
    $edit_id = $_POST['edit_id'];
    // Loop through $_SESSION['form_data'] to find the row to update
    foreach($_SESSION['form_data'] as $key => $value) {
        if($value['id'] == $edit_id) {
            // Update the relevant fields with the values from the form
            $_SESSION['form_data'][$key]['style_id'] = $_POST['style_id'];
            $_SESSION['form_data'][$key]['stock_id'] = $_POST['stock_id'];
            $_SESSION['form_data'][$key]['perpc'] = $_POST['perpc'];
			$_SESSION['form_data'][$key]['stk_total'] = $_POST['stk_total'];
            $_SESSION['form_data'][$key]['stk_extra'] = $_POST['stk_extra'];
			$_SESSION['form_data'][$key]['filename'] = $_POST['unique_name'];
			$_SESSION['form_data'][$key]['unique_name'] = $_POST['unique_name'];
            $_SESSION['form_data'][$key]['req_date'] = $_POST['req_date'];
			$Ajax->activate('items_table');
			$Ajax->activate('edit_items_table');

            // Add any other fields that need to be updated
            // ...

            // Break out of the loop since we have found and updated the row
            break;
        }
    }

    // Unset the edit_id field to reset the form
    unset($_POST['edit_id']);
	$_REQUEST['edit_id']=null;



}


if(isset($_POST['AddItem'])) {
   // Create an empty array to store the form data
$form_data = array();

// Retrieve the existing array of data from the session variable
$existing_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : array();

// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;


$form_data['id'] =null;

// Push the values of each form field into the array, including the new line number
$form_data['line_no'] = $next_line_no;
$form_data['style_id'] = $_POST['style_id'];
$form_data['stock_id'] = $_POST['stock_id'];
$form_data['stk_type'] = $_POST['maincat_id'];
$form_data['perpc'] = $_POST['perpc'];
$form_data['stk_extra'] = $_POST['stk_extra'];
$form_data['req_date'] = $_POST['req_date'];
$form_data['t_style_qty']  = $_POST['t_style_qty'];
$form_data['description'] = get_description($_POST['stock_id']);
$form_data['units'] = get_unit($_POST['stock_id']);
$form_data['filename'] = $_POST['unique_name'];	
$form_data['unique_name'] = $_POST['unique_name'];
$form_data['stk_extra'] = $_POST['stk_extra'];
$form_data['stk_total'] = $_POST['stk_total'];
$form_data['req_date'] = $_POST['req_date'];

// Add the new form data to the existing array of data
$existing_data[] = $form_data;

// Store the updated array data in the session variable
$_SESSION['form_data'] = $existing_data;

	$Ajax->activate('edit_items_table');

	$Ajax->activate('items_table');


}

if(isset($_POST['add'])){

	add_to_database($_SESSION['form_data'], $_POST['order_no']);

}


$result = get_all_detail($order_no);



start_form(true);


div_start('items_table');

display_heading("Plan Sales Order");

start_table(TABLESTYLE, "width='80%'");

			$th = array(_('Style No.'), _('Total Quantity'), _('Accessory Code'), _('Accessory Description'),_('UoM'), _('Qty Per Piece'), _('Extra Quantity%'), _('Total Required'), _('Required by Date'), _('Image') , _('Action'));

			table_header($th);
			start_row();
			global $Ajax;
			global $SysPrefs;
			
			foreach ($_SESSION['form_data'] as $key => $value) {
				
				start_row();
				label_cell($value['style_id']);
				$t_style_qty = get_total_quantity($order_no, $value['style_id']);
				qty_cell($t_style_qty);
				view_stock_status_cell($value['stock_id']);
				$des = get_description($value['stock_id']);
				$unit = get_unit($value['stock_id']);
				label_cell($des);
				label_cell($unit);
				qty_cell($value['perpc']);
				qty_cell($value['stk_extra']);
				qty_cell($value['stk_total']);
				label_cell($value['req_date']);
				foreach (array('jpg', 'png', 'gif') as $ext) {
					$file = company_path().'/images/'. $value['unique_name'].'.'.$ext;
					if (file_exists($file)) {
						$stock_img_link = "<img id='item_img' alt = 'no image found' src='".$file."?nocache=".rand()."'"." height='".$SysPrefs->pic_height."' border='0'>";
						break;
					}
				}
				label_cell( $stock_img_link);
				edit_button_cell('Edit'.$value['line_no'], _('Edit'), _('Edit document line'));
				if(isset($_POST['Edit'.$value['line_no']])){
					$_POST['style_id'] = $value['style_id'];
					$_POST['stock_id'] = $value['stock_id'];
					$_POST['perpc'] = $value['perpc'];
					$_POST['stk_extra'] = $value['stk_extra'];
					$_POST['stk_total'] = $value['stk_total'];
					$_POST['req_date'] = $value['req_date'];
					$_POST['unique_name'] = $value['unique_name'];
					hidden('edit_id', $value['id']);
					$Ajax->activate('items_table');


					$edit = true;
					
				}

				end_row();
			}


			


end_table(1);
div_end();

div_start('edit_items_table');

start_table(TABLESTYLE, "width='80%'");
$th = array(_('Style No.'), _('Total Quantity'), _('Accessory Code'), _('Accessory Description'),_('UoM'), _('Qty Per Piece'), _('Extra Quantity%'), _('Total Required'), _('Required by Date'), _('Image') , _('Action'));

			table_header($th);
			start_row();
			stock_style_list_cells_oderWise(null,$order_no, 'style_id', null, false, true, false);
			$t_style_qty = get_total_quantity($order_no, $_POST['style_id']);
			hidden('t_style_qty',$t_style_qty);

			qty_cell($t_style_qty);
			sales_items_list_cells(null,'stock_id',$_POST['stock_id'], $maincat_id, false, true, true);
			$unit = get_unit($_POST['stock_id']);
			label_cell($unit);
			text_cells_ex(null, 'perpc',null, 52, null ,'','','',true);
			text_cells_ex(null, 'stk_extra', null, 52, null ,'','','',true);
			$stk_total = total_required($_POST['t_style_qty'], $_POST['perpc'], $_POST['stk_extra']);
			hidden('stk_total',$stk_total);
			qty_cell($_POST['stk_total']);
			date_cells(null, 'req_date', null, null, 0, 0, 0, null, true);
			file_cells(null, 'image','image');
			if($_REQUEST['edit_id']!=null || $edit)
			submit_cells('update', _('Update'), "colspan=2 align='center'", _('Update item to document'), true);
			else
			submit_cells('AddItem', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
			hidden('unique_name', $unique_name);
			$Ajax->activate('edit_items_table');


			end_row();

end_table(1);
div_end();
start_table(TABLESTYLE2);
textarea_row(_('Remarks:'), 'Comments', null, 70, 4);
end_table(1);
submit_center_first('add',_('add'),  _('Check entered data and save document'), 'default');
end_form();
end_page();
