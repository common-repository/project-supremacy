<?php

if ( ! class_exists( 'MPRS_Troubleshooting' ) ) {

	class MPRS_Troubleshooting extends MPRS_Model {

		public static function initialize() {
			add_action('admin_post_prs_troubleshooting', array('MPRS_Troubleshooting', 'doTroubleshooting'));
		}

		public static function doTroubleshooting() {
			$status_messages = array();

			/**
			 *  Set the memory limit to Unlimited
			 */
			if (isset($_POST['ts_memory_limit'])) {
				if ($_POST['ts_memory_limit'] == 1) {
					update_option('prs_ts_memory_limit', TRUE);
					$status_messages[] = 'Lifted memory limit successfully.';
				} else {
					update_option('prs_ts_memory_limit', FALSE);
				}
			}

			/**
			 *   Regenerate Table Structure
			 */
			if (isset($_POST['ts_update_tables'])) {
				if ($_POST['ts_update_tables'] == 1) {
					PRS_Init::createTables();
					$status_messages[] = 'Updated Table Structure.';
				}
			}


			PRS_Init::json('success', 'Operation completed!', $status_messages);
		}
	}

}