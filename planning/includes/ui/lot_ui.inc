<?php


//-------------------------------------------------------------------------------------
function dropdown_list($name, $submit_on_change=false, $table_name, $column1_name, $column2_name, $selected_id=null) {
    $sql = "SELECT $column1_name, $column2_name FROM ".TB_PREF.$table_name;
    return combo_input($name, $selected_id, $sql, $column1_name, $column2_name,
		array(
			'spec_id' => -1,
			'search_submit' => true,
			'select_submit'=> $submit_on_change,
			'async' => false,
	));
}

function dropdown_list_cells($label, $name, $submit_on_change=false, $table_name, $column1_name, $column2_name) {
	
    
	if ($label != null)
		echo "<td class='label'>".$label."</td>\n";
    echo "<td>";
	    echo dropdown_list($name, $submit_on_change, $table_name, $column1_name, $column2_name); 
    echo "</td>\n";
}
//-------------------------------------------------------------------------------------

function get_roll_no_list($name, $submit_on_change=false, $selected_id=null, $mo_no, $session_data, $issues_roll) {
    $sql = "SELECT roll_no, roll_no FROM ".TB_PREF."stock_rec_details rd, ".TB_PREF."stock_rec r WHERE rd.sr_id = r.sr_id 
    AND r.mo_no = ".db_escape($mo_no)."
    AND r.maincat_id = 3" ;

    foreach($session_data as $key => $row) {
        $sql .= " AND rd.roll_no != ".db_escape($row['roll_no']);
    } 
    foreach($issues_roll as $key => $row) {
        $sql .= " AND rd.roll_no != ".db_escape($row['roll_no']);
    }


    return combo_input($name, $selected_id, $sql, roll_no, roll_no,
		array(
			'spec_id' => -1,
			'search_submit' => true,
			'select_submit'=> $submit_on_change,
			'async' => false,
	));
}

function get_roll_no_list_cells($label, $name, $submit_on_change=false, $selected_id=null, $mo_no, $session_data, $issues_roll) {
	if ($label != null)
		echo "<td class='label'>".$label."</td>\n";
    echo "<td>";
	    echo get_roll_no_list($name, $submit_on_change, $selected_id=null, $mo_no, $session_data, $issues_roll); 
    echo "</td>\n";
}
//-------------------------------------------------------------------------------------
function get_lot_data($mo_no, $unset = false){

  if ($unset) {
        unset($_SESSION['lot_data1']);
    }

    $result = get_lot_detail($mo_no, 4);
    $line_no = 1;
    while ($myrow = db_fetch($result)) {
        $lot_data['line_no'] = $line_no++;
        $lot_data['id'] = $myrow['id'];
        $lot_data['lot_id'] = $myrow['lot_id'];        
		$lot_data['roll_no'] = $myrow['roll_no'];
        $existing_data[] = $lot_data;
        $_SESSION['lot_data1'] = $existing_data;
        
    }
}
