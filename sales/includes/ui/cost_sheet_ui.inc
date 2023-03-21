<?php

function shipping_terms($label , $name, $selected=null) {
echo "<td>".$label."</td>\n<td>";
$allocs = array(
'1'=> _('By Air'),
'2'=> _('By Ship'),
'3' => _('Courier'),
);
echo array_selector($name, $selected, $allocs);
echo "</td>\n";
}

function get_cost_data($maincat_id){

$result = get_cost_detail($maincat_id);
$line_no = 1;
while ($myrow = db_fetch($result)) {
    $cost_data['line_no'] = $line_no++;
    $cost_data['maincat_id'] = $myrow['maincat_id'];
    $cost_data['id'] = $myrow['id'];
    $cost_data['cs_id'] = $myrow['cs_id'];
    $cost_data['stk_code'] = $myrow['stk_code'];
    $cost_data['description'] = get_description($myrow['stk_code']);
    $cost_data['units'] = get_unit($myrow['stk_code']);
    $cost_data['waste'] = $myrow['waste'];
    $cost_data['consumption'] = $myrow['consumption'];
    $cost_data['rate'] = $myrow['rate'];
    $cost_data['amount'] = 0;
    $existing_data = isset($_SESSION['cost_data']) ? $_SESSION['cost_data'] : array();
 // Check if pp_id already exists in session
$index = array_search($cost_data['id'], array_column($existing_data, 'id'));
if ($index !== false) {
// Data already in session, do nothing
}
else {
// Add new data to session
    $existing_data[] = $cost_data;
    $_SESSION['cost_data'] = $existing_data;
}
}
}

function amount(){
    return 2+2;
}

function knitt_bag_formula(){
    return 2+2;
}

function total_formula(){
    return 2+2;
}

function total_cost(){
    return 2+2;
}