<?php

if ( ! class_exists( 'MPRS_Reviews' ) ) {

	class MPRS_Reviews extends MPRS_Model  {

		public static function initialize() {
			add_action('admin_post_prs_bulkReviews',      array('MPRS_Reviews', 'bulkReview'));
			add_action('admin_post_prs_unapproveReview',  array('MPRS_Reviews', 'unapproveReview'));
			add_action('admin_post_prs_approveReview',    array('MPRS_Reviews', 'approveReview'));
			add_action('admin_post_prs_cloneReview',      array('MPRS_Reviews', 'cloneReview'));
			add_action('admin_post_prs_removeReview',     array('MPRS_Reviews', 'removeReview'));
			add_action('admin_post_prs_getReviews',       array('MPRS_Reviews', 'getReviews_Datatables'));
			add_action('admin_post_prs_getReview',        array('MPRS_Reviews', 'getReview'));
			add_action('admin_post_prs_editReview',       array('MPRS_Reviews', 'editReview'));
			add_action('admin_post_prs_newReview',        array('MPRS_Reviews', 'newReview'));
			add_action('admin_post_prs_saveReviewWidget', array('MPRS_Reviews', 'saveReviewWidget'));
		}

		public static function reviewsWidgetShortcode($instance) {

			$render = function($instance) {
				global $post;
				$isShortcode = true;
				ob_start();
				include(PRS_PATH . '/pages/metabox/prs_review.php');
				return ob_get_clean();
			};

			return $render($instance);
		}

		public static function reviewsDisplayShortcode($instance) {

			$render = function($instance) {
				global $post;
				$isShortcode = true;
				ob_start();
				include(PRS_PATH . '/pages/metabox/prs_display_reviews.php');
				return ob_get_clean();
			};

			return $render($instance);

		}

		public static function saveReviewWidget() {
			wp_cache_delete ( 'alloptions', 'options' );
			if (!empty($_POST)) {
				if(!isset($_POST['ps_review'])) {
					PRS_Init::json( 'error', 'Sorry, something went worng. Please try again!' );
				}

				$psReview = array();
				foreach ($_POST['ps_review'] as $key => $value) {
					foreach($value as $k => $v) {
						$value[$k] = prs_stripAllSlashes(htmlspecialchars($v));
					}
					$psReview[$key] = $value;
				}

				if (! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'prs_saveReviewWidget' ) ) {
					PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
				} else {
					if(isset($psReview)) {
						update_option('ps_review', $psReview);
						PRS_Init::json( 'success', 'Your settings have been saved.' );
					}
				}
			}
			PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
		}

		public static function bulkReview() {

			if( isset($_POST['ids']) && isset($_POST['type']) ) {
				$ids  = $_POST['ids'];
				$type = sanitize_text_field($_POST['type']);

				if ($type === 'approve') {

					self::updateData(array(
						'approved' => 1
					), array(
						'id' => $ids
					));

				} else if ($type === 'unapprove') {

					self::updateData(array(
						'approved' => 0
					), array(
						'id' => $ids
					));

				} else if ($type === 'delete') {

					self::removeData(array(
						'id' => $ids
					));

				} else if ($type === 'move') {

					if(isset($_POST['post_id'])) {

						$post_id = $_POST['post_id'];
						$ids = explode(',' , ps_exif_address($ids));

						self::updateData(array(
							'page_id' => $post_id
						), array(
							'id' => $ids
						));

					}

				}
			}

		}

		public static function cloneReview() {

			if(isset($_POST['post_ids']) && isset($_POST['review_id'])) {
				$post_ids   = explode(',' , sanitize_text_field($_POST['post_ids']));
				$review_id  = sanitize_text_field($_POST['review_id']);

				$review_data = self::getData('*', array(
					'id' => $review_id
				));

				// remove id
				unset($review_data['id']);

				foreach ( $post_ids as $post_id ) {
					$review_data['page_id'] = $post_id;

					self::insertData($review_data);
				}

				wp_send_json(array('status'=>'success', 'message'=>"All data successfully cloned"));
			}

		}

		public static function approveReview() {
			if(isset($_POST['id'])) {
				$review_id = trim(sanitize_text_field($_POST['id']));

				self::updateData(array(
					'approved' => 1
				), array(
					'id' => $review_id
				));
			}
		}

		public static function unapproveReview() {
			if(isset($_POST['id'])) {
				$review_id = trim(sanitize_text_field($_POST['id']));

				self::updateData(array(
					'approved' => 0
				), array(
					'id' => $review_id
				));
			}
		}

		public static function removeReview() {
			if(isset($_POST['id'])) {
				$review_id = trim(sanitize_text_field($_POST['id']));

				self::removeData(array(
					'id' => $review_id
				));
			}
		}

		public static function countReviews($id = null) {
			if ($id != null) {
				$id = array(
					'page_id' => $id
				);
			}
			return self::getCount($id);
		}

		private static function validateVars($vars) {
			$allowed_vars = array(
				"action",
				"name",
				"review",
				"title",
				"rating",
				"email",
				"website",
				"telephone",
				"location",
				"age",
				"page_id",
				"stars_only"
			);
			foreach($allowed_vars as $var) {
				if (!isset($vars[$var])) {
					return false;
				}
			}
			return $vars;
		}

		public static function getReviewsGlobal( $stars_only = false, $random_order = false ) {

			$stars = '';
			if ($stars_only === true) {
				$stars = 'AND stars_only = 1';
			}

			$order = 'rating DESC';
			if ($random_order === true) {
				$order = 'RAND()';
			}

            $query = "SELECT * FROM " . static::TABLE_NAME . " WHERE approved = 1 " . $stars . " ORDER BY " . $order;

			return self::query($query);
		}

		public static function getReviewsForPage( $page_id = 0, $stars_only = null, $random_order = false ) {

			$stars = '';
			if ($stars_only === true) {
				$stars = 'AND stars_only = 1';
			} else if ($stars_only === false) {
				$stars = 'AND stars_only = 0';
			}

			$order = 'rating DESC';
			if ($random_order === true) {
				$order = 'RAND()';
			}

			if ($page_id === 0) {
				$query = "SELECT * FROM " . static::TABLE_NAME . " WHERE (page_id = 0 OR page_id IS NULL) AND approved = 1 " . $stars . " ORDER BY " . $order;
			} else {
				$query = "SELECT * FROM " . static::TABLE_NAME . " WHERE page_id = '$page_id' AND approved = 1 " . $stars . " ORDER BY " . $order;
			}

			return self::query($query);
		}

		public static function getReview() {
			$data = self::getData('*', array(
				'id' => $_POST['id']
			));
			$data['review'] = stripcslashes( $data['review'] );
			wp_send_json($data);
		}

		public static function editReview() {
			$data = $_POST;
			if(isset($data) && !empty($data)) {
				if (! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'prs_editReview' ) ) {
					PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
				} else {
					$ID = $data['id'];

					unset($data['action']);
					unset($data['id']);
					$data['date'] = (isset($data['date']) && !empty($data['date'])) ? $data['date'] : date('Y-m-d H:i:s');

					if(isset($data['_wpnonce'])){
						unset($data['_wpnonce']);
						unset($data['_wp_http_referer']);
					}
					
					if ($ID == 0) {
						self::insertData($data);
					} else {
						self::updateData($data, array(
							'id' => $ID
						));
					}
					PRS_Init::json('success', 'Successfully finished operation.');
				}
			}
			PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
		}

		public static function getReviews_Datatables() {
			global $wpdb;

			$aColumns = array(
				'id',
				'name',
				'title',
				'review',
				'rating',
				'email',
				'website',
				'telephone',
				'location',
				'age',
				'date',
				'page_id',
				'approved'
			);

			/* Indexed column (used for fast and accurate table cardinality) */
			$sIndexColumn = "id";

			/* DB table to use */
			$sTable = self::TABLE_NAME;

			/*
			 * Paging
			 */
			$sLimit = '';
			if ( isset( $_POST['iDisplayStart'] ) && $_POST['iDisplayLength'] != '-1' ) {
				$sLimit = "LIMIT " . ( $_POST['iDisplayStart'] ) . ", " .
				          ( $_POST['iDisplayLength'] );
			} else {
				$sLimit = "LIMIT 0,50";
			}


			/*
			 * Ordering
			 */
			$sOrder = '';
			if ( isset( $_POST['iSortCol_0'] ) ) {
				$sOrder = "ORDER BY  ";
				for ( $i = 0; $i < intval( $_POST['iSortingCols'] ); $i ++ ) {
					if ( $_POST[ 'bSortable_' . intval( $_POST[ 'iSortCol_' . $i ] ) ] == "true" ) {
						$sOrder .= $_POST[ 'mDataProp_' . intval( $_POST[ 'iSortCol_' . $i ] ) ]  . "
				 	" . ( $_POST[ 'sSortDir_' . $i ] ) . ", ";
					}
				}

				$sOrder = substr_replace( $sOrder, "", - 2 );
				if ( $sOrder == "ORDER BY" ) {
					$sOrder = "";
				}
			}

			$customFilters = array(
				'approved' => $_POST['ReviewState'],
				'search'   => $_POST['sSearch']
			);

			$customWhere = "";

			foreach($customFilters as $key=>$column){

				if($column != ''){
					if($customWhere == ""){
						$customWhere = "WHERE ";
					}else{
						$customWhere .= " AND ";
					}

					if($key == 'search'){
						$customWhere .= " (`name` LIKE '%" . ($column) . "%' OR `review` LIKE '%" . ($column) . "%') ";
					}else{
						$customWhere .= $key . " = " . ($column) . " ";
					}
				}
			}

			/*
			 * SQL queries
			 * Get data to display
			 */
			$sQuery  = "
				SELECT SQL_CALC_FOUND_ROWS " . str_replace( " , ", " ", implode( ", ", $aColumns ) ) . "
				FROM   $sTable
				$customWhere
				$sOrder
				$sLimit";

			$rResult = $wpdb->get_results( $sQuery, ARRAY_A );

			$sQuery             = "SELECT FOUND_ROWS()";
			$aResultFilterTotal = $wpdb->get_results( $sQuery, ARRAY_A );
			$iFilteredTotal     = $aResultFilterTotal[0]['FOUND_ROWS()'];

			/* Total data set length */
			$sQuery       = "
				SELECT COUNT(" . $sIndexColumn . ")
				FROM   $sTable";

			$aResultTotal = $wpdb->get_results( $sQuery, ARRAY_A );
			$iTotal       = $aResultTotal[0]['COUNT(id)'];

			$datt = array();
			foreach($rResult as $d) {

				$d['name'] = stripslashes($d['name']);
				$d['review'] = stripslashes($d['review']);

				if ($d['page_id'] == 0) {

					$frontpage_id = get_option( 'page_on_front' );

					$d['page_url']   = get_site_url();

					if (empty($frontpage_id)) {

						$d['page_title'] = 'Global Review';
						$d['page_edit']  = $d['page_url'];

					} else {

						$d['page_title'] = 'Homepage';
						$d['page_edit']  = get_edit_post_link($frontpage_id);

					}

				} else {
					$d['page_title'] = get_the_title($d['page_id']);
					$d['page_url']   = get_permalink($d['page_id']);
					$d['page_edit']  = get_edit_post_link($d['page_id']);
				}

				unset($d['page_id']);
				$datt[] = $d;
			}

			/*
			 * Output
			 */
			$output = array(
				"sEcho"                => intval( $_POST['sEcho'] ),
				"iTotalRecords"        => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData"               => $datt
			);

			echo json_encode( $output );
		}

		public static function newReview() {
			$vars = self::validateVars($_POST);
			if ($vars == false) {
				PRS_Init::json('error', 'Invalid Request!');
			}

			// Check if cookie tracking is on
			$ps_review = get_option('ps_review');
			if (
				isset($ps_review['settings']['prevent_multiple']) &&
				$ps_review['settings']['prevent_multiple'] == '1'
			) {
				if (!self::isAllowedToPost()) {
					PRS_Init::json('error', 'You already submitted a review!');
				}
			}

			if ( empty($vars['website']) ) {
				$vars['website'] = '';
			} else {
				$vars['website'] = esc_url_raw($vars['website']);
			}

			// Ratings
			$stars_only = intval($vars['stars_only']);

			$approved = 0;

			// Check if auto approve is on
			if (@$ps_review['settings']['stars_approve'] == 1 && $stars_only == 1) {
				$approved = 1;
			}
			if (@$ps_review['settings']['reviews_approve'] == 1 && $stars_only == 0) {
				$approved = 1;
			}

			$data = array(
				'name'       => sanitize_text_field($vars['name']),
				'review'     => sanitize_text_field($vars['review']),
				'title'      => sanitize_text_field($vars['title']),
				'rating'     => intval($vars['rating']),
				'email'      => sanitize_email($vars['email']),
				'website'    => $vars['website'],
				'telephone'  => sanitize_text_field($vars['telephone']),
				'location'   => sanitize_text_field($vars['location']),
				'age'        => intval($vars['age']),
				'approved'   => $approved,
				'date'       => date('Y-m-d H:i:s'),
				'page_id'    => intval($vars['page_id']),
				'stars_only' => $stars_only
			);

			$result = self::insertData($data);

			if (is_integer($result)) {
				PRS_Init::json('success', 'Review added!', $result);
			} else {
				PRS_Init::json('error', $result);
			}
		}

		private static function isAllowedToPost() {
			if (isset($_COOKIE['_psrl'])) {
				return false;
			} else {
				setcookie('_psrl', true,  time() + 86400); // 1 day
			}
			return true;
		}

		protected static $TABLE_NAME;
		public function __construct(){
			static::$TABLE_NAME = self::TABLE_NAME;
		}

		// MySQL
		const TABLE_NAME = 'prs_reviews';
		public static function createTable() {
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$charset_collate = $wpdb->get_charset_collate();
			$creation_query =
				'CREATE TABLE ' . self::TABLE_NAME . ' (
			         `id` int(11) NOT NULL AUTO_INCREMENT,
                     `name` varchar(255) DEFAULT NULL,
                     `title` varchar(255) DEFAULT NULL,
                     `review` text,
                     `rating` int(1) DEFAULT NULL,
                     `email` varchar(255) DEFAULT NULL,
                     `website` varchar(255) DEFAULT NULL,
                     `telephone` varchar(255) DEFAULT NULL,
                     `location` text,
                     `age` int(2) DEFAULT NULL,
                     `date` datetime DEFAULT NULL,
                     `page_id` int(11) DEFAULT 0,
                     `approved` int(1) DEFAULT 0,
                     `stars_only` int(1) DEFAULT 0,
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
