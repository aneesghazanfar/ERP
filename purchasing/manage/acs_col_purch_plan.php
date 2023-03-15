<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
$page_security = 'SA_PURCHASEPLAN';
//----------------------------------------------------------------------------------------------
if (isset($_GET['OrderNumber']))
$order_no= $_GET['OrderNumber'];
else $order_no = $_POST['order_no'];
hidden('order_no', $order_no);
$maincat_id = 5;
hidden('maincat_id', $maincat_id);

if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
	$order_no = $_POST['order_no'];
	$result = $_FILES['image']['error'];
	$upload_file = 'Yes'; //Assume all is well to start off with
	$filename = company_path().'/images';

	if (!file_exists($filename))
		mkdir($filename);


	$filename .= '/'. item_img_name($_POST['ufilename']).(substr(trim($_FILES['image']['name']), strrpos($_FILES['image']['name'], '.')));


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
}
if(isset($_POST['Delete'])){
	$Delete_key = $_POST['Delete_key'];
	unset($_SESSION['contract_data'][$Delete_key]);
}

if(isset($_POST['update_item'])) {
    $edit_id = $_POST['edit_id'];
    foreach($_SESSION['contract_data'] as $key => $value) {
        if($key == $edit_id) {
            $_SESSION['contract_data'][$key]['perpc'] = $_POST['perpc'];
            $_SESSION['contract_data'][$key]['stk_extra'] = $_POST['stk_extra'];
			$t_style_qty = get_collection_qty($order_no);
			// $dyedperpc = get_dyedperpc($t_style_qty, $_POST['perpc'], $_POS);
			$_SESSION['contract_data'][$key]['stk_total'] = total_required($t_style_qty, $_POST['perpc'], $_POST['stk_extra']);
			$_SESSION['contract_data'][$key]['ufilename'] = $_POST['ufilename'];
			$_SESSION['contract_data'][$key]['req_date'] = $_POST['req_date'];
			display_notification(_('Order plan has been updated'));
            break;
        }
    }

    // Unset the edit_id field to reset the form
    unset($_POST['edit_id']);
	$Ajax->activate('items_table');
}

if(isset($_POST['AddItem'])) {
   // Create an empty array to store the form data
$contract_data = array();

// Retrieve the existing array of data from the session variable
$existing_data = isset($_SESSION['contract_data']) ? $_SESSION['contract_data'] : array();

// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;

// $contract_data['new'] = $next_id_no;
$contract_data['pp_id'] =null;

// Push the values of each form field into the array, including the new line number
$contract_data['line_no'] = $next_line_no;
$contract_data['stock_id'] = $_POST['stock_id'];
$contract_data['maincat_id'] = $_POST['maincat_id'];
$contract_data['perpc'] = $_POST['perpc'];
$contract_data['waste'] = 0;
$contract_data['stk_extra'] = $_POST['stk_extra'];
$contract_data['t_style_qty']  = $_POST['t_style_qty'];
$contract_data['description'] = get_description($_POST['stock_id']);
$contract_data['units'] = get_unit($_POST['stock_id']);
$contract_data['ufilename'] = $_POST['ufilename'];
$contract_data['stk_extra'] = $_POST['stk_extra'];
$contract_data['stk_total'] = $_POST['stk_total'];
$contract_data['req_date'] = $_POST['req_date'];

// Add the new form data to the existing array of data
$existing_data[] = $contract_data;

// Store the updated array data in the session variable
$_SESSION['contract_data'] = $existing_data;
display_notification(_('New order plan has been added'));
$Ajax->activate('items_table');
}

if(isset($_POST['add_plan'])){

	add_to_database($_SESSION['contract_data'], $_POST['order_no'],$_POST['Comments'], $_POST['maincat_id']);
	display_notification(_('New order plan has been added'));
	get_collection_data($order_no,$maincat_id);
}

function line_start_focus() {
	global 	$Ajax;
	$Ajax->activate('items_table');
}
if (isset($_POST['CancelItemChanges']))
	line_start_focus();

function edit(&$order,  $order_no, $line , $maincat_id) {
	global $Ajax;
	global $id;

	if ($id == $line && $line != -1) {

		foreach($order as $key=>$value){
			if($key == $line){
				hidden('edit_id', $key);
				$_POST['collection'] = $value['collection'];
				$_POST['stock_id'] = $value['stock_id'];
				$_POST['perpc'] = $value['perpc'];
				$_POST['stk_extra'] = $value['stk_extra'];
				$_POST['stk_total'] = $value['stk_total'];
				$_POST['ufilename'] = $value['ufilename'];
				$_POST['req_date'] = $value['req_date'];
				$Ajax->activate('items_table');
				break;
			}
		}
			$_POST['t_style_qty'] = get_collection_qty($order_no);
			qty_cell($_POST['t_style_qty']);
			label_cells(null, $_POST['stock_id']);
			label_cells(null, get_description($_POST['stock_id']));
			$unit = get_unit($_POST['stock_id']);
			label_cell($unit);
			small_qty_cells_ex(null, 'perpc', 0,false);
			small_qty_cells_ex(null, 'stk_extra', 0,false);
			$stk_total = total_required($_POST['t_style_qty'], $_POST['perpc'], $_POST['stk_extra']);
			hidden('stk_total', $stk_total);
			qty_cell($stk_total);
			date_cells(null, 'req_date', null, null, 0, 0, 0, null, false);
			file_cells(null, 'image','image');
	}
	else{
			$_POST['t_style_qty'] = get_collection_qty($order_no);
			qty_cell($_POST['t_style_qty']);
			sales_items_list_cells(null,'stock_id', $_POST['stock_id'], false, true, true, $maincat_id);
			$unit = get_unit($_POST['stock_id']);
			label_cell($unit);
			small_qty_cells_ex(null, 'perpc', 0,true);
			small_qty_cells_ex(null, 'stk_extra', 0,true);
			$stk_total = total_required($_POST['t_style_qty'], $_POST['perpc'], $_POST['stk_extra']);
			hidden('stk_total', $stk_total);
			qty_cell($stk_total);
			date_cells(null, 'req_date', null, null, 0, 0, 0, null, true);
			file_cells(null, 'image','image');
			$Ajax->activate('items_table');
	}
if ($id != -1) {
	button_cell('update_item', _('Update'), _('Confirm changes'), ICON_UPDATE);
	button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
}
else{
	submit_cells('AddItem', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
}
hidden('ufilename', uniqid());
end_row();
}
start_form(true);
div_start('items_table');
display_heading("Plan Orerwise Acessories Against Sales Order");
start_table(TABLESTYLE, "width='90%'");
	$th = array(_('Total Qty'), _('Accessory Code'), _('Accessory Desc'), _('UoM'), _('Qty/Pc'), _('Extra Qty %'), _('Req Qty'), _('Req by'),  _('Image'), '', '');
			table_header($th);
			start_row();
			global $SysPrefs;
			$id = find_row('Edit');
			$editable_items = true;
			if ($id == -1 && $editable_items)
				edit($_SESSION['contract_data'], $order_no, -1, $maincat_id);

			foreach ($_SESSION['contract_data'] as $key => $value) {
				start_row();
				if($id != $key || !$editable_items){

				$t_style_qty = get_collection_qty($order_no);
				qty_cell($t_style_qty);
				view_stock_status_cell($value['stock_id']);
				$des = get_description($value['stock_id']);
				$unit = get_unit($value['stock_id']);
				label_cell($des);
				label_cell($unit);
				qty_cell($value['perpc']);
				qty_cell($value['stk_extra']);
				qty_cell($value['stk_total']);
				$ufilename = $value['ufilename'];
				$ufilename = str_replace(' ', '_', $ufilename);
				$stock_img_link = null;
				foreach (array('jpg', 'png', 'gif') as $ext) {
					$file = company_path().'/images/'. $ufilename .'.'.$ext;
					if (file_exists($file)) {
						$stock_img_link = "<img id='item_img' alt = 'no image found' src='".$file."?nocache=".rand()."'"." height='".$SysPrefs->pic_height."' border='0'>";
						break;
					}
				}
				label_cell($value['req_date']);
				label_cell( $stock_img_link);
				edit_button_cell('Edit'.$value['line_no'], _('Edit'), _('Edit document line'));
				delete_button_cell('Delete'.$value['line_no'], _('Delete'), _('Remove line from document'));
				hidden('Delete_key', $key);
				end_row();
			}
			else{
				edit($_SESSION['contract_data'], $order_no, $key, $maincat_id);
			}
	}
end_table(1);
$old_comments = get_plan_comments($order_no,$maincat_id);
start_table(TABLESTYLE2);
textarea_row(_('Remarks:'), 'Comments', $old_comments, 70, 4);
end_table(1);
submit_center_first('add_plan',_('Place Plan'),  _('Check entered data and save document'), 'default');
div_end();
end_form();
