<?php

function shipping_terms($label , $name, $selected=null) {
echo "<tr><td class='label'>".$label."</td>\n<td>";
$allocs = array(
'1'=> _('By Air'),
'2'=> _('By Ship'),
'3' => _('Courier'),
);
echo array_selector($name, $selected, $allocs);
echo "</td></tr>\n";
}

function style_list($name, $selected_id=null, $submit_on_change=false) {
$sql = "SELECT DISTINCT clt_style, CONCAT(clt_style,' | ',style_id) as style_ref FROM ".TB_PREF."stock_master";
	return combo_input($name, $selected_id, $sql, 'clt_style', 'style_ref',
		array(
			'spec_id' => -1,
			'search_submit' => true,
			'select_submit'=> $submit_on_change,
			'async' => true,
	));
}
function style_list_cells( $name, $selected_id=null, $submit_on_change=false) {
	echo "<td>";
	echo style_list($name, $selected_id,  $submit_on_change);
	echo "</td>\n";
}

function amount($yconsumption, $yrate){
    return ($yconsumption * $yrate/100);
   //     return 2+2;

}
// not used when final
function amount1(){
    return 2+2;
}
// not used when final


function knitt_bag_formula(){
    return 2+2;
}

function total_formula(){
    return 2+2;
}

function total_cost(){
    return 2+2;
}

function total_rate(){
    return 2+2;
}

function fab_total($Per_total, $rate, $waste, $consumption){
    echo $Per_total . "<br>" . $rate . "<br>" . $waste . "<br>" . $consumption;
    return (($Per_total + $rate) * ($waste/100) + $consumption);
}

function label_cell_text($label, $name) {
    $value = isset($_POST[$name]) ? $_POST[$name] : '';
    echo "<td class='label'>$label
        <input class='searchbox' class='amount' type=\"text\" name=\"$name\" value=\"$value\">
    </td>";
}

function get_data_data($maincat_id , $unset = false){
    if($unset)
		unset($_SESSION['cost_data']);
    $existing_data = isset($_SESSION['cost_data']) ? $_SESSION['cost_data'] : array();
	$line_no = count($existing_data) > 0 ? $existing_data[count($existing_data) - 1]['line_no'] + 1 : 1;
    $result = get_cost_detail($maincat_id);
    $yarn_amount = 0;
    while ($myrow = db_fetch($result)) {
        $cost_data['line_no'] = $line_no++;
        $cost_data['id'] = $myrow['id'];
        $cost_data['cs_id'] = $myrow['cs_id'];
        $cost_data['maincat_id'] = $myrow['maincat_id'];
        $cost_data['stk_code'] = $myrow['stk_code'];
        $cost_data['description'] = get_description($myrow['stk_code']);
        $cost_data['units'] = get_unit($myrow['stk_code']);
        $cost_data['waste'] = $myrow['waste'];
        $cost_data['consumption'] = $myrow['consumption'];
        $cost_data['rate'] = $myrow['rate'];
        if($myrow['maincat_id'] == 1 || $myrow['maincat_id'] == 2){
            $cost_data['amount'] = amount($myrow['consumption'], $myrow['rate']);
            $yarn_amount = $cost_data['amount'];
        }
        else if($myrow['maincat_id'] == 3 ){
            $cost_data['amount'] = fab_total($yarn_amount, $myrow['rate'], $myrow['waste'], $myrow['consumption']);
        }
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