<?php
function get_cost_detail($maincat_id){
	$sql = "SELECT * FROM ".TB_PREF."cost_sheet_details";
	return db_query($sql, "could not get all detail");
}
function get_cs_id(){
    $sql = "SELECT cs_id FROM ".TB_PREF."cost_sheet ORDER BY cs_id DESC LIMIT 1";
    $result = db_query($sql, "could not get all detail");
    $row = db_fetch_row($result);
    return ($row[0] + 1);
    }
function add_cost_data_to_db(&$cost_data, $cs_id, $user, $cost_date, $style, $shipping_terms, $cmt, $status,$overhead, $freight, $container, $insurance
,$commission, $profit, $exchange_rate, $ufilename){

	foreach($cost_data as $key => $row) {

			if($row['id'] == null ) 
				{
					$sql = "INSERT INTO ".TB_PREF."cost_sheet_details (cs_id, maincat_id, stk_code, waste, consumption, rate) VALUES 
					(".db_escape($row['cs_id']).", ".db_escape($row['maincat_id']).", ".db_escape($row['stk_code']).", ".db_escape($row['waste']).", ".db_escape($row['consumption']).", ".db_escape($row['rate']).")";
						
				}
				else {
					$sql = "UPDATE ".TB_PREF."cost_sheet_details SET waste=".db_escape($row['waste']).", consumption=".db_escape($row['consumption']).", rate=".db_escape($row['rate'])." WHERE id=".db_escape($row['id'])."";
				
				}

		db_query($sql, 'order Cannot be Added');
	}

	$sql = "INSERT INTO ".TB_PREF."cost_sheet (
            cs_id,
            user,
            cost_date,
            style_id,
            shipping,
            cmt,
            status,
            overhead,
            freight,
            container,
            insurance,
            commission,
            profit,
            exch,
			ufilename
        ) VALUES (
            ".db_escape($cs_id).",
            ".db_escape($user).",
            ".db_escape($cost_date).",
            ".db_escape($style).",
            ".db_escape($shipping_terms).",
            ".db_escape($cmt).",
            ".db_escape($status).",
            ".db_escape($overhead).",
            ".db_escape($freight).",
            ".db_escape($container).",
            ".db_escape($insurance).",
            ".db_escape($commission).",
            ".db_escape($profit).",
            ".db_escape($exchange_rate).",
			".db_escape($ufilename)."

        ) ON DUPLICATE KEY UPDATE
            user = ".db_escape($user).",
            cost_date = ".db_escape($cost_date).",
            style_id = ".db_escape($style).",
            shipping = ".db_escape($shipping_terms).",
            cmt = ".db_escape($cmt).",
            status = ".db_escape($status).",
            overhead = ".db_escape($overhead).",
            freight = ".db_escape($freight).",
            container = ".db_escape($container).",
            insurance = ".db_escape($insurance).",
            commission = ".db_escape($commission).",
            profit = ".db_escape($profit).",
            exch = ".db_escape($exchange_rate).",
			ufilename = ".db_escape($ufilename);	


			
       // WHERE cs_id = ".db_escape($cs_id);
	
  db_query($sql, 'order Cannot be Added');

}