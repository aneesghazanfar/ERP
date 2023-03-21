<?php
function get_cost_detail($maincat_id){
	$sql = "SELECT * FROM ".TB_PREF."cost_sheet_details WHERE maincat_id=".db_escape($maincat_id)."";
	return db_query($sql, "could not get all detail");
}