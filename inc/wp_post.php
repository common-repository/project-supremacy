<?php
! defined( 'ABSPATH' ) and exit;


if ( ! class_exists( 'PRS_Post' ) ) {

	class PRS_Post {
        /** GENERAL ---------------------------------------------------------------------------- */
        // Save Options
        public static function saveOptions() {
            $prs = $_POST['prs'];
            if (!is_array($prs)) wp_die('Unknown Error');
            if (!is_admin()) wp_die('Unknown Error');
            $prs_option = get_option('prs');
            foreach($prs as $key=>$value) {
	            $prs_option[$key] = sanitize_text_field($value);
            }
            update_option('prs', $prs_option);
        }
        /** GENERAL ---------------------------------------------------------------------------- */
	}

}