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

function qoh(){
    return 2+2;
}