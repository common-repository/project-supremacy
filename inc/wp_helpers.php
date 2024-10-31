<?php
// Function that makes our zip utility skip zip files (aka our backups)
function prs_preZipAdd( $p_event, &$p_header ) {
	$info = pathinfo( $p_header['stored_filename'] );
	// ----- zip files are skipped
	if ( isset( $info['extension'] ) ) {
		if ( $info['extension'] == 'zip' ) {
			return 0;
		} else {
			return 1;
		}
	} else {
		return 1;
	}
}

if(! function_exists('prs_removeSlashes')) {
	function prs_removeSlashes($string)
	{
		$string = implode("", explode("\\", $string));
		return stripslashes(trim($string));
	}
}

if(! function_exists('prs_stripAllSlashes')) {
	function prs_stripAllSlashes($value)
	{
		$value = is_array($value) ?
			array_map('prs_stripAllSlashes', $value) :
			prs_removeSlashes($value);

		return $value;
	}
}

if(! function_exists('prs_stripUnwantedCharTag')) {
	function prs_stripUnwantedCharTag($string=null)
	{
		$string = str_replace('"', '', trim($string));
		return wp_strip_all_tags( $string );
	}
}
