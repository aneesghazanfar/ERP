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

//formula functions-----------------------------------------------------------------------------
function multiply($first, $second){
    return  ($first * ($second / 100));
}
//----------------------------------------------------------------------------------------------
function gfab_cost_kg($yarn_cost, $Knitting_Charges, $Knitting_waste){
    return ((($yarn_cost+ $Knitting_Charges)/45.36)*100)/(100-$Knitting_waste);
}
//----------------------------------------------------------------------------------------------
function dfab_cost($gfab_cost_kg, $Dyeing_Charges, $dfab_cost_perpc, $Dyeing_waste){

return ((($gfab_cost_kg+$Dyeing_Charges)*$dfab_cost_perpc)*100)/(100-$Dyeing_waste);
}
//----------------------------------------------------------------------------------------------
function acc_amount($rate, $consumption){
    return  ($rate * $consumption);
}
//----------------------------------------------------------------------------------------------
function addition($first, $second, $third=0,$forth=0){
    return ($first + $second + $third + $forth);
    
}
//----------------------------------------------------------------------------------------------
function overhead_cost($total_perpc_cost, $overhead){
    return ($total_perpc_cost * $overhead)/100;
}
//----------------------------------------------------------------------------------------------
function pkr_sale_price($total_perpc, $profit, $commission){
return ($total_perpc / (100 - $profit - $commission)) * 100;

}
//----------------------------------------------------------------------------------------------
function sale_price_in_FC($pkr_sale_price, $exchange_rate){
    return $pkr_sale_price / $exchange_rate;
}
//----------------------------------------------------------------------------------------------
function check_cost_status($cs_id){
    $sql = "SELECT approval_date FROM ".TB_PREF."cost_sheet WHERE cs_id = ".db_escape($cs_id);
    $result = db_query($sql, "could not get cost_sheet status");
    $row = db_fetch_row($result);
    if (strtotime($row[0]) === false)
        return  'Draft';
    else
        return  'Approved';
}
//----------------------------------------------------------------------------------------------
function fab_total($Per_total, $rate, $waste, $consumption){
    echo $Per_total . "<br>" . $rate . "<br>" . $waste . "<br>" . $consumption;
    return (($Per_total + $rate) * ($waste/100) + $consumption);
}
//----------------------------------------------------------------------------------------------
function label_cell_text($label, $name) {
    $value = isset($_POST[$name]) ? $_POST[$name] : '';
    echo "<td class='label'>$label
        <input class='searchbox' class='amount' type=\"text\" name=\"$name\" value=\"$value\">
    </td>";
}
//----------------------cost sheet-------------------------------------------------------------
function get_cost_data($unset = false){
    if($unset){
		unset($_SESSION['cost_data']);
        unset($_SESSION['fab_data']);
    }
    $result = get_cost_detail('Yarn');
    $line_no = 1;
     while ($myrow = db_fetch($result)) {
        $cost_data['line_no'] = $line_no++;
        $cost_data['id'] = $myrow['id'];
        $cost_data['cs_id'] = $myrow['cs_id'];
        $cost_data['maincat_id'] =substr($myrow['stk_code'], 1, 1);
        $cost_data['fab_id'] = $myrow['fab_id'];
        $cost_data['stk_code'] = $myrow['stk_code'];
        $cost_data['consume'] = $myrow['consume'];
        $cost_data['rate'] = $myrow['rate'];
        $cost_data['processing'] = $myrow['processing'];
        $cost_data['waste'] = $myrow['waste'];

        $existing_data[] = $cost_data;
        $_SESSION['cost_data'] = $existing_data;

     }
}