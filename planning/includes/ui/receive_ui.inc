<?php
/**********************************************************************
	Copyright (C) ASMIte Inc.
	contact@march7.group
***********************************************************************/
//UI--------------------------------------------------------------------------------------

function get_rec_cat($order_no){
	  $sql = "SELECT maincat_id FROM ".TB_PREF."purch_plan_summary WHERE sorder_no = ".db_escape($order_no);
    $result = db_query($sql, "could not get item code");
    $row = db_fetch_row($result);
	return $row[0];
    //return substr($row[0], 1, 1);

}
//----------------------------------------------------------------------------------------
function receive_result($label , $name, $selected=null) {
echo "<td>";
$allocs = array(
'PASS'=> _('PASS'),
'FAIL'=> _('FAIL'),
);
echo array_selector($name, $selected, $allocs);
echo "</td>\n";
}

//----------------------------------------------------------------------------------------
function dropdown_with_multiselect($name, $selected_id=null, $submit_on_change=false, $table_name, $column_name) {
	$sql = "SELECT $column_name, id FROM ".TB_PREF."$table_name";
	
	$result = db_query($sql, "could not get $column_name");

	echo  '<select name="' . $name . '[]" multiple id="' . $name . '" style="border-radius: 0;">';

	while ($myrow = db_fetch($result)) {
		echo '<option value="' . $myrow[1] . '">' . $myrow[0] . '</option>';
	}
	echo '</select>';

}

function multiselect_cells($label, $name, $selected_id=null, $submit_on_change=false, $table_name) {
	if ($label != null)
		echo "<td>".$label."</td>\n";
	echo "<td>";
	echo dropdown_with_multiselect($name.'_id', $selected_id, $submit_on_change, $table_name, 'id'); // Call dropdown_with_multiselect for 'id' column
	echo "</td>\n";
	echo "<td>";
	echo dropdown_with_multiselect($name.'_descr', $selected_id, $submit_on_change, $table_name, 'descr'); // Call dropdown_with_multiselect for 'descr' column
	echo "</td>\n";
}

function multiselect_row($label, $name, $selected_id=null, $submit_on_change=false, $table_name, $column_name) {
	echo "<tr><td class='label'>".$label."</td>";
	echo multiselect_cells(null, $name, $selected_id, $submit_on_change, $table_name, $column_name);
	echo "</tr>\n";
}
//----------------------------------------------------------------------------------------

function fault_list($name, $selected_id=null, $all_option=false, $submit_on_change=false, $type='', $opts=array(), $editkey=false) {
	$sql = "SELECT id, descr FROM
			".TB_PREF."faults";

	return combo_input($name, $selected_id, $sql, 'id', 'descr',
		array_merge(
		  array(
			'format' => '_format_stock_items',
			'spec_option' => $all_option===true ?  _('All Items') : $all_option,
			'spec_id' => ALL_TEXT,
			'search_box' => true,
			'search' => array("id", "descr", "descr"),
			'search_submit' => true,
			'size'=>15,
			'select_submit'=> $submit_on_change,
			'category' => 2,
			'order' => array('descr','id'),
			'editlink' => $editkey ? add_edit_combo('item', $name) : false,
			'editable' => 30,
			'async' => true,
			'multi' => true,
			'max' => 255
		  ), $opts), $type == 1 );
}

function fault_list_cells($label, $name, $selected_id=null, $all_option=false, $submit_on_change=false, $editkey=false) {
	if ($editkey)
		set_editor('item', $name, $editkey);

	if ($label != null)
		echo "<td>".$label."</td>\n";

	echo fault_list($name, $selected_id, $all_option, $submit_on_change,
		'', array('cells'=>true, 'max'=>50), $editkey); // maximum is set to 50 and not default 255.

}
//----------------------------------------------------------------------------------------
function roll_no_list($name, $lot_no, $session_data, $selected_id=null, $submit_on_change=false) {
	$sql = "SELECT ls.roll_no, ls.roll_no FROM ".TB_PREF."lot_details ls, ".TB_PREF."lot l WHERE ls.lot_id = l.lot_id AND l.lot_no = ".db_escape($lot_no) ;
 	foreach($session_data as $key => $row) {

		$sql .= " AND ls.roll_no != ".db_escape($row['roll_no']);
	}
	
	return combo_input($name, $selected_id, $sql, 'ls.roll_no', 'ls.roll_no',
		array(
			'spec_id' => -1,
			'search_submit' => true,
			'select_submit'=> $submit_on_change,
			'async' => false,
	));
}
function roll_no_list_cells( $name, $lot_no, $session_data, $selected_id=null, $submit_on_change=false) {
	echo "<td>";
	echo roll_no_list($name, $lot_no, $session_data, $selected_id,  $submit_on_change);
	echo "</td>\n";
}
//----------------------------------------------------------------------------------------
function get_receive_data($mo_no,$cat, $unset=false){
    if ($unset) {
        unset($_SESSION['receive_data']);
    }
	if($cat == '00-01')
        $maincat_id = 3;
    else if($cat == '00-02')
        $maincat_id = 4;
    $result = get_receive_detail($mo_no, $maincat_id);
    $line_no = 1;
    while ($myrow = db_fetch($result)) {
        $receive_data['line_no'] = $line_no++;
        $receive_data['id'] = $myrow['id'];
        $receive_data['form_no'] = $myrow['form_no'];
		$receive_data['sorder_no'] = $myrow['sorder_no'];
		$receive_data['roll_no'] = $myrow['roll_no'];
		$receive_data['style_id'] = $myrow['style_id'];;	
		$receive_data['weight'] = $myrow['weight'];
		$receive_data['width'] = $myrow['width'];
		$receive_data['ply'] = $myrow['ply'];
		$receive_data['gsm'] = $myrow['gsm'];
		$receive_data['knit'] = $myrow['knit'];
		$receive_data['tuck'] = $myrow['tuck'];
		$receive_data['q_loop'] = $myrow['q_loop'];
		$receive_data['fault_id'] = $myrow['fault_id'];
		$receive_data['fault_allow'] = $myrow['fault_allow'];
		$receive_data['result'] = $myrow['result'];
 
        $existing_data[] = $receive_data;
        $_SESSION['receive_data'] = $existing_data;
        


    }
}
