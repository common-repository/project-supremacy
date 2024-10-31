<?php

if ( ! class_exists( 'MPRS_Keywords' ) ) {

	class MPRS_Keywords extends MPRS_Model {

		public static function initialize() {
			add_action('admin_post_prs_getKeyword', array('MPRS_Keywords', 'getKeyword'));
			add_action('admin_post_prs_getKeyword', array('MPRS_Keywords', 'getKeywords'));
			add_action('admin_post_prs_addKeyword', array('MPRS_Keywords', 'addKeyword'));
			add_action('admin_post_prs_updateKeywords', array('MPRS_Keywords', 'updateKeywords'));
			add_action('admin_post_prs_keywordChangeGroup', array('MPRS_Keywords', 'keywordChangeGroup'));
		}

        //--------------------------------------------
        //
        //             MySQL Operations
        //
        //--------------------------------------------

        protected static $TABLE_NAME;
        public function __construct(){
            static::$TABLE_NAME = self::TABLE_NAME;
        }

        // MySQL
        const TABLE_NAME = 'prs_keywords';
        public static function createTable() {
            global $wpdb;
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            $charset_collate = $wpdb->get_charset_collate();
            $creation_query =
                'CREATE TABLE ' . self::TABLE_NAME . ' (
			        `id` int(11) NOT NULL AUTO_INCREMENT,
			        `group_id` int(11),
			        `keyword` varchar(255),
			        `volume` varchar(255),
			        `cpc` varchar(255),
			        `inurl` varchar(255),
			        `intitle` varchar(255),
			        `phrase` varchar(255),
			        `broad` varchar(255),
			        `date_created` datetime,
			        `position` int(11) default 999,
			        `queued` int(1) default 0,
			        `rank` longtext,
			        PRIMARY KEY  (`id`)
			    ) ' .$charset_collate. ';';
            return @dbDelta( $creation_query );
        }
        public static function removeTable() {
            global $wpdb;
            $query = 'DROP TABLE IF EXISTS ' . self::TABLE_NAME . ';';
            $wpdb->query( $query );
        }

        //--------------------------------------------
        //
        //               Functions
        //
        //--------------------------------------------

		public static function updateKeywords() {
			$group_id = sanitize_text_field($_POST['group_id']);
			$keywords = json_decode(urldecode(sanitize_text_field($_POST['keywords'])), true);

			foreach($keywords as $keyword) {
				$id = $keyword['id'];
				unset($keyword['id']);

				$fields = array('volume', 'inurl', 'intitle', 'phrase' ,'broad');

				foreach($fields as $f) {
					if (isset($keyword[$f])) {
						$keyword[$f] = str_replace( ',', '', $keyword[$f] );
					}
				}

				self::updateData(
					$keyword,
					array(
						'group_id'=>$group_id,
						'id'=>$id
					)
				);
			}
		}

		public static function keywordChangeGroup() {
			$original_group_id = sanitize_text_field($_POST['original_group_id']);
			$target_group_id   = sanitize_text_field($_POST['target_group_id']);
			$keyword_ids       = sanitize_text_field($_POST['keyword_ids']);

			$keyword_ids       = explode(',', $keyword_ids);
			foreach($keyword_ids as $keyword_id) {
				self::updateData(
					array(
						'group_id' => $target_group_id
					),
					array(
						'group_id' => $original_group_id,
						'id'       => $keyword_id
					)
				);
			}
		}

		public static function addKeyword() {
			$group_id  = sanitize_text_field($_POST['group_id']);
			$keywords  = $_POST['keywords'];

			$keywords  = explode("\n", $keywords);

			foreach($keywords as $keyword) {

				self::insertData(array(
					'keyword'      => sanitize_text_field($keyword),
					'group_id'     => $group_id,
					'rank'         => '0',
					'date_created' => date('Y-m-d H:i:s')
				));

			}
		}

		public static function getKeyword($return = FALSE, $gid = 0, $kid = 0) {
			$keyword_id = ($kid == 0) ? sanitize_text_field($_POST['keyword_id']) : $kid;
			$group_id   = ($gid == 0) ? sanitize_text_field($_POST['group_id']) : $gid;
			$results = self::querySingle("SELECT * FROM " . self::TABLE_NAME . " WHERE group_id = '$group_id' AND id = '$keyword_id'");
			if (!$results) {
				$results = array();
			}
			if ($return) {
				return $results;
			} else {
				wp_send_json($results);
			}
		}

		public static function getKeywords($return = FALSE, $gid = 0) {
			$group_id   = ($gid == 0) ? sanitize_text_field($_POST['group_id']) : $gid;
			$results = self::query("SELECT * FROM " . self::TABLE_NAME . " WHERE group_id = '$group_id' ORDER BY position ASC");
			if (!$results) {
				$results = array();
			}
			if ($return) {
				return $results;
			} else {
				wp_send_json($results);
			}
		}

	}

}