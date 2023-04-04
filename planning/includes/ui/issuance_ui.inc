<?php

function get_issuance_data($maincat_id, $unset=false){
    if ($unset) {
        unset($_SESSION['issuance_data']);
    }

    $result = get_issuance_detail(form_no(), $maincat_id);
    $line_no = 1;
    while ($myrow = db_fetch($result)) {
        $issuance_data['line_no'] = $line_no++;
        $issuance_data['id'] = $myrow['id'];
        $issuance_data['si_id'] = $myrow['si_id'];
        $issuance_data['stk_code'] = $myrow['stk_code'];
        $issuance_data['required'] = $myrow['required'];
        $issuance_data['qoh'] = get_qoh_on_date($myrow['stk_code']);        
        $issuance_data['issued'] = $myrow['issued'];
        $issuance_data['lot_no'] = $myrow['lot_no'];
        $existing_data = isset($_SESSION['issuance_data']) ? $_SESSION['issuance_data'] : array();

        $existing_data[] = $issuance_data;
        $_SESSION['issuance_data'] = $existing_data;
        


    }
}
//--------------------------------------------------------------------------------------
function required_bags($stock_id,$maincat_id){
    $sql = "SELECT act_req FROM ".TB_PREF."purch_plan_summary WHERE stk_code = ".db_escape($stock_id)." AND maincat_id = ".db_escape($maincat_id);
    $result = db_query($sql, "could not get required bags");
    $row = db_fetch_row($result);
    return ($row[0]/45.36);


}


function get_item_cat($order_no){
    $sql = "SELECT item_code FROM ".TB_PREF."purch_order_details WHERE order_no = ".db_escape($order_no);
    $result = db_query($sql, "could not get item code");
    $row = db_fetch_row($result);
    return substr($row[0], 0, 5);

}

function already_issuse($mo_no){
    $sql = "SELECT SUM(sdetail.issued) FROM ".TB_PREF."stock_issue_details sdetail , ".TB_PREF."stock_issue sissue 
    WHERE sissue.mo_no = ".db_escape($mo_no)."
    AND sissue.si_id = sdetail.si_id";

    $result = db_query($sql, "could not get issued");
    $row = db_fetch_row($result);
    return $row[0];

}

//--------------------------------------------------------------------------------------------

function get_Rolls_count($lot_no){
    $sql = "SELECT roll_count FROM ".TB_PREF."lot WHERE lot_no = ".db_escape($lot_no);
    $result = db_query($sql, "could not get roll count");
    $row = db_fetch_row($result);
    return $row[0];
}
//--------------------------------------------------------------------------------------------
function get_weight($lot_no){
    $sql = "SELECT weight FROM ".TB_PREF."lot WHERE lot_no = ".db_escape($lot_no);
    $result = db_query($sql, "could not get weight");
    $row = db_fetch_row($result);
    return $row[0];
}



//---------------------------------------------------------------------------------------------


function lot_no_item_list($name, $submit_on_change=false, $mo_no,$selected_id=null ) {
    $sql = "SELECT lot_no, lot_no FROM ".TB_PREF."lot WHERE mo_no = ".db_escape($mo_no);
    return combo_input($name, $selected_id, $sql, 'lot_no', 'lot_no',
		array(
			'spec_id' => -1,
			'search_submit' => true,
			'select_submit'=> $submit_on_change,
			'async' => false,
	));
}

function lot_no_item_list_cells($label, $name, $submit_on_change=false, $mo_no = null) {
	
    
	if ($label != null)
		echo "<td>".$label."</td>\n";
echo "<td>";
	echo lot_no_item_list($name, $submit_on_change, $mo_no); 
    echo "</td>\n";
}