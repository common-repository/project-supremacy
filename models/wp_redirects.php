<?php

if ( ! class_exists( 'MPRS_Redirects' ) ) {

	class MPRS_Redirects extends MPRS_Model {

		public static function initialize() {
			add_action('admin_post_prs_get_redirects', array('MPRS_Redirects', 'getRedirects'));
			add_action('admin_post_prs_add_redirect', array('MPRS_Redirects', 'addRedirect'));
			add_action('admin_post_prs_edit_redirect', array('MPRS_Redirects', 'editRedirect'));
			add_action('admin_post_prs_delete_redirect', array('MPRS_Redirects', 'deleteRedirect'));
			add_action('admin_post_prs_delete_all_redirects', array('MPRS_Redirects', 'deleteAllRedirects'));
			add_action( 'template_redirect', array('MPRS_Redirects', 'doRedirect') );
		}


		public static function add($old, $new) {
			// Check if empty
			if (empty($old) || empty($new)) {
				return;
			}

			// Remove old data
			self::removeData(array( 'new' => $new ));
			self::removeData(array( 'new' => $old ));

			$old = ltrim($old, '/');
			$old = rtrim($old, '/');

			$new = ltrim($new, '/');
			$new = rtrim($new, '/');

			self::insertData(array(
				'old' => $old,
				'new' => $new,
				'date_created' => date('Y-m-d H:i:s')
			));
		}

		public static function addRedirect() {
			$oldURL = esc_url_raw($_POST['oldURL']);
			$newURL = esc_url_raw($_POST['newURL']);

			self::add($oldURL, $newURL);
		}

		public static function editRedirect() {
			$id = sanitize_text_field($_POST['id']);
			$newURL = sanitize_text_field($_POST['newURL']);

			self::updateData(
				array(
					'new' => $newURL
				),
				array(
					'id' => $id
				)
			);
		}

		public static function doRedirect() {

			if (PRS_DISABLE_REDIRECTS === true) return;

			$redirects   = self::getAllData();
			$request_uri = rtrim($_SERVER['REQUEST_URI'], '/');
			$request_uri_ltrim = ltrim($request_uri, '/');

			foreach($redirects as $r) {
				if ($request_uri === $r['old'] || $request_uri_ltrim === $r['old']) {

					// Check if external
					if (strpos($r['new'], 'http') !== false) {
						wp_redirect($r['new'], 301);
					} else {
						wp_redirect(site_url('/' . $r['new'] . '/'), 301);
					}
					die();
				}
			}
		}

		public static function deleteRedirect() {
			$ID = sanitize_text_field($_POST['id']);

			$RemoveIDs = explode(',' , $ID);

			foreach ($RemoveIDs as $i_d) {
				self::removeData(array('id'=>$i_d));
			}

		}

		public static function deleteAllRedirects() {
			self::truncate('prs_redirects');
		}



		public static function getRedirects() {
			PRS_Init::json('success', 'Retrieved all redirects.', self::query("SELECT id, old, new, DATE_FORMAT(date_created, '%b %D, %Y') as date_created FROM prs_redirects"));
		}

		protected static $TABLE_NAME;
		public function __construct(){
			static::$TABLE_NAME = self::TABLE_NAME;
		}

		// MySQL
		const TABLE_NAME = 'prs_redirects';
		public static function createTable() {
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$charset_collate = $wpdb->get_charset_collate();
			$creation_query =
				'CREATE TABLE ' . self::TABLE_NAME . ' (
			        `id` int(11) NOT NULL AUTO_INCREMENT,
			        `old` varchar(255),
			        `new` varchar(255),		  
			        `date_created` datetime,			        
			        PRIMARY KEY  (`id`)
			    ) ' .$charset_collate. ';';
			@dbDelta( $creation_query );
		}
		public static function removeTable() {
			global $wpdb;
			$query = 'DROP TABLE IF EXISTS ' . self::TABLE_NAME . ';';
			$wpdb->query( $query );
		}

	}

}