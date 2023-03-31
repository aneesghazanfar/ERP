
<?php
/**********************************************************************
 Copyright (C) ASMIte Inc.
 contact@march7.group
 ***********************************************************************/
	
$page_security = 'SA_PURCHASEPLAN';
//----------------------------------------------------------------------------------------------
hidden('order_no', $order_no);
$maincat_id = 5;
hidden('maincat_id', $maincat_id);


$unset = true;
$lineNo = find_submit('Edit');

if(isset($_POST['Edit'.$lineNo])){
	$unset = false;
}

	
// Default value for $perpc
// $perpc=1;
	// hidden('perpc', $perpc);
	
	
	if(isset($_POST['Delete'])){
		$Delete_key = $_POST['Delete_key'];
		unset($_SESSION['plan_data'][$Delete_key]);
	}
	//update after edit
	if(isset($_POST['update_item'])) {
		$edit_id = $_POST['edit_id'];
		foreach($_SESSION['plan_data'] as $key => $value) {
			if($key == $edit_id) {
				$ini_qty= get_style_qty($order_no, $value['style_id']);
				$_SESSION['plan_data'][$key]['perpc'] = $_POST['perpc'];
				$_SESSION['plan_data'][$key]['waste'] = $_POST['waste'];
				$total_req = total_req($ini_qty, $_POST['perpc'], $_POST['waste'] );
				$_SESSION['plan_data'][$key]['stk_extra'] = $_POST['stk_extra'];
				$_SESSION['plan_data'][$key]['stk_total'] = net_req($total_req, $_POST['perpc'] , $_POST['stk_extra']);
				$_SESSION['plan_data'][$key]['req_date']  = $_POST['req_date'];
				$_SESSION['plan_data'][$key]['ufilename']  = $_POST['ufilename'];
				display_notification(_('Order plan has been updated'));
				$unset = false;
				break;
			}
		}
		
		// Unset the edit_id field to reset the form
		unset($_POST['edit_id']);
		$Ajax->activate('items_table');
	}


	
	if(isset($_POST['AddItem'])) {
		$unset = false;
		// Create an empty array to store the form data
		$plan_data = array();
		
		// Retrieve the existing array of data from the session variable
		$existing_data = isset($_SESSION['plan_data']) ? $_SESSION['plan_data'] : array();
		
		// Determine the next line number by retrieving the line number of the last item (if it exists) and incrementing it by one
		$next_line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
		$plan_data['pp_id'] =null;
		
		// Push the values of each form field into the array, including the new line number
		$plan_data['line_no'] = $next_line_no;
		$plan_data['maincat_id'] = $_POST['maincat_id'];
		$plan_data['style_id'] = $_POST['style_id'];
		$plan_data['stock_id'] = $_POST['stock_id'];
		$plan_data['description'] = get_description($_POST['stock_id']);
		$plan_data['ini_qty']  = get_style_qty($order_no, $_POST['style_id']);
		$plan_data['units'] = get_unit($_POST['stock_id']);
		$plan_data['perpc'] = $_POST['perpc'];
		$plan_data['waste'] = $_POST['waste'];
		$plan_data['total_req']  = total_req($plan_data['ini_qty'], $_POST['perpc'], $_POST['waste'] );
		$plan_data['stk_extra'] = $_POST['stk_extra'];
		$plan_data['stk_total'] = net_req($plan_data['total_req'], $_POST['stk_extra']);
		$plan_data['req_date'] = $_POST['req_date'];
		$plan_data['ufilename'] = $_POST['ufilename'];

		// Add the new form data to the existing array of data
		$existing_data[] = $plan_data;

		// Store the updated array data in the session variable
		$_SESSION['plan_data'] = $existing_data;
		$Ajax->activate('items_table');
	}
	
	if(isset($_POST['add_plan'])){
		add_to_database($_SESSION['plan_data'], $order_no, $_POST['comment'], $maincat_id);
		display_notification(_('New order plan has been added'));
		get_plan_data($order_no, $maincat_id,true);
	}
	
if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {	
	$order_no = $_POST['order_no'];
	$result = $_FILES['image']['error'];
	$upload_file = 'Yes'; //Assume all is well to start off with
	$filename = company_path().'/images';
	
	if (!file_exists($filename))
	mkdir($filename);
	
	hidden('ufilename', uniqid());

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
if (isset($_POST['CancelItemChanges']))
line_start_focus();

function edit(&$order, $order_no, $line, $maincat_id) {
	global $Ajax;
	global $id;
	
	if ($id == $line && $line != -1) {
		foreach($order as $key=>$value){
			if($key == $line){
				hidden('edit_id', $key);
				$_POST['stock_id'] = $value['stock_id'];
				$_POST['perpc'] = $value['perpc'];
				$_POST['waste'] = $value['waste'];
				$_POST['stk_extra'] = $value['stk_extra'];
				$_POST['stk_total'] = $value['stk_total'];
				$_POST['ufilename'] = $value['ufilename'];
				// $Ajax->activate('items_table');
				break;
			}
		}
		label_cell($_POST['style_id']);
		$ini_qty= get_style_qty($order_no, $_POST['style_id']);
		label_cell($_POST['stock_id']);
		label_cell(get_description($_POST['stock_id']));
		label_cell(get_unit($_POST['stock_id']));
		qty_cell($ini_qty);
		small_qty_cells_ex(null, 'perpc', 0,false);
		small_qty_cells_ex(null, 'waste', 0,false);
		$total_req = total_req($ini_qty, $_POST['perpc'], $_POST['waste']);
		qty_cell($total_req);
		small_qty_cells_ex(null, 'stk_extra', 0, false);
		$stk_total = net_req($total_req, $_POST['stk_extra']);
		qty_cell($stk_total);
		date_cells(null, 'req_date', null, null, 0, 0, 0, null, false);
		file_cells(null, 'image','image');
		hidden('ufilename', $_POST['ufilename']);
	}
	else{
		stock_style_list_cells( 'style_id', null,  true,$order_no);
		plan_sales_items_list_cells(null,'stock_id', null, false, true, true, $maincat_id);
		$ini_qty= get_style_qty($order_no, $_POST['style_id']);
		hidden('ini_qty', $ini_qty);
		label_cell(get_unit($_POST['stock_id']));
		qty_cell($ini_qty);
		small_qty_cells_ex(null, 'perpc', 1,false);
		small_qty_cells_ex(null, 'waste', 0, false);
		//need to change $perpc as per requirement
		$total_req = total_req($ini_qty, $_POST['perpc'], $_POST['waste']);
		qty_cell($total_req);
		hidden('total_req', $total_req);
		small_qty_cells_ex(null, 'stk_extra', 0, false);
		$stk_total = net_req($total_req, $_POST['stk_extra']);
		qty_cell($stk_total);
		hidden('stk_total', $stk_total);
		date_cells(null, 'req_date', null, null, 0, 0, 0, null, false);
		file_cells(null, 'image','image');
		$Ajax->activate('items_table');
	}
	if ($id != -1) {
		button_cell('update_item', _('Update'), _('Confirm changes'), ICON_UPDATE);
		button_cell('CancelItemChanges', _('Cancel'), _('Cancel changes'), ICON_CANCEL);
	}
	else{
		submit_cells('AddItem', _('Add Item'), "colspan=2 align='center'", _('Add new item to document'), true);
		hidden('ufilename', uniqid());
	}

end_row();
}



start_form(true);

div_start('items_table');
if((list_updated('style_id') || list_updated('stock_id')) && (isset($_POST['waste']) >= 0)){
	$unset = false;
}
get_plan_data($order_no, $maincat_id , $unset);
display_heading("Plan Accessories Against Sales Order");
start_table(TABLESTYLE, "width='90%'");
$th = array(_('Style Id'), _('Acs Code'), _('Acs Desc'), _('UoM'), _('Tot St Items'), _('Qty/Pc'), _('Waste %'), _('Total Qty'), _('Ex Qty %'), _('Req Qty'), _('Req by'),_('Image'), '', '');
table_header($th);
						start_row();
						$id = find_row('Edit');
						$editable_items = true;
						if ($id == -1 && $editable_items)
							edit($_SESSION['plan_data'], $order_no, -1, $maincat_id);
							foreach ($_SESSION['plan_data'] as $key => $value) {
								start_row();
								if($id != $key || !$editable_items){
								label_cell($value['style_id']);
								label_cell($value['stock_id']);
								label_cell(get_description($value['stock_id']));
								$ini_qty = get_style_qty($order_no, $value['style_id']);
								label_cell(get_unit($value['stock_id']));
								qty_cell($ini_qty);
								qty_cell($value['perpc']);
								qty_cell($value['waste']);
//Need to change perpc as per requirement
								$total_req = total_req($ini_qty, $value['perpc'], $value['waste']);
								qty_cell($total_req);
								qty_cell($value['stk_extra']);
								qty_cell($value['stk_total']);
								label_cell($value['req_date']);
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
								label_cell( $stock_img_link);
								edit_button_cell('Edit'.$value['line_no'], _('Edit'), _('Edit document line'));
								delete_button_cell('Delete'.$value['line_no'], _('Delete'), _('Remove line from document'));
								if(isset($_POST['Delete'.$value['line_no']])){
									unset($_SESSION['plan_data'][$key]);
									$Ajax->activate('items_table');
								}
								end_row();
						}
						else{
							edit($_SESSION['plan_data'], $order_no, $key, $maincat_id);
						}
				}
			end_table(1);
			$comment = get_plan_comments($order_no, $maincat_id);
			start_table(TABLESTYLE2);
			textarea_row(_('Remarks:'), 'comment', $comment, 70, 4);
			end_table(1);
submit_center_first('add_plan',_('Place Plan'),  _('Check entered data and save document'), 'default');
div_end();
end_form();