<?php

if ( ! class_exists( 'MPRS_Settings' ) ) {

	class MPRS_Settings {

		public static function initialize() {
			add_action( 'admin_post_prs_settings', array( 'MPRS_Settings', 'saveSettings' ) );
			add_action( 'admin_post_prs_export_options', array( 'MPRS_Settings', 'exportOptions' ) );
			add_action( 'admin_post_prs_import_options', array( 'MPRS_Settings', 'importOptions' ) );
		}

		public static function saveSettings() {

			$status_messages = array();

			/**
			 *  Disable 301 Redirects
			 */
			if ( isset( $_POST['prs_disable_redirects'] ) ) {
				if ( $_POST['prs_disable_redirects'] == 1 ) {
					update_option( 'ps_disable_redirects', true );
					$status_messages[] = 'Redirects disabled.';
				} else {
					update_option( 'ps_disable_redirects', false );
					$status_messages[] = 'Redirects enabled.';
				}
			}


			/**
			 *  Remove HTML comments footprint
			 */
			if ( isset( $_POST['prs_remove_footprint'] ) ) {
				if ( $_POST['prs_remove_footprint'] == 1 ) {
					update_option( 'ps_remove_footprint', true );
					$status_messages[] = 'Footprint disabled.';
				} else {
					update_option( 'ps_remove_footprint', false );
					$status_messages[] = 'Footprint enabled.';
				}
			}

			PRS_Init::json( 'success', "Operation completed! \n" . join( "\n", $status_messages ) );

		}

		public static function exportOptions() {

			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=test.txt");
			global $wpdb;

			$all_wp_options = array(
				'ps_seo_title_separator',
				'ps_seo_target_keyword',
				'ps_seo_force_noodp',
				'ps_seo_index_subpages',
				'ps_seo_always_on',
				'ps_seo_title',
				'ps_seo_description',
				'ps_seo_post_types',
				'ps_seo_taxonomies',
				'ps_seo_miscellaneous',
				'ps_seo_title_fb',
				'ps_seo_description_fb',
				'ps_seo_image_fb',
				'ps_seo_title_tw',
				'ps_seo_description_tw',
				'ps_seo_image_tw',
				'ps_seo_verify_bing',
				'ps_seo_verify_google',
				'ps_seo_verify_pinterest',
				'ps_seo_verify_yandex',
				'ps_remove_footprint',
				'prs_ts_memory_limit'
			);

			$return = array();
			foreach ( $all_wp_options as $wp_option ) {
				if (!get_option($wp_option))
					continue;
				$return['options'][$wp_option] = get_option($wp_option);
			}

			$all_post_meta = array(
				'ps_seo_enabled',
				'ps_seo_title',
				'ps_seo_url',
				'ps_seo_description',
				'ps_seo_keyword',
				'ps_seo_metarobots_enabled',
				'ps_seo_metarobots_index',
				'ps_seo_metarobots_follow',
				'ps_seo_metarobots_advanced',
				'ps_seo_canonical',
				'ps_seo_tw_title',
				'ps_seo_tw_desc',
				'ps_seo_tw_img',
				'ps_seo_fb_title',
				'ps_seo_fb_desc',
				'ps_seo_fb_img',
				'ps_seo_notes',
			);

			$temp = array();

			foreach ( $all_post_meta as $meta ) {
				$results = $wpdb->get_results("SELECT post_id, meta_key, meta_value FROM wp_postmeta WHERE meta_key = '{$meta}'", ARRAY_A);
				$temp[] = $results;
			}
			$by_id = array();
			foreach ( $temp as $t ) {
				if ( is_array($t) ) {
					foreach ( $t as $s ) {
						if ( isset($s['post_id']) ) {
							$post_id = $s['post_id'];
							unset($s['post_id']);
							$by_id[$post_id][] = $s;
						}
					}
				}
			}

			$return['postmeta'] = $by_id;

			header('Content-disposition: attachment; filename=PSv3_Export_Settings_' . date('Y-m-d_H:i:s') . '.psexp');
			header('Content-type: application/json');
			echo json_encode($return, TRUE);

		}

		public static function importOptions() {

			if ( !isset($_FILES['import_options_file']) ) {
				PRS_Init::json( 'error', 'File is not sent!' );
			}

			$file = $_FILES['import_options_file'];

			if ( $file['error'] == UPLOAD_ERR_OK && is_uploaded_file($file['tmp_name'])) {

				$info = pathinfo($file['name']);
				if ($info["extension"] != "psexp") {
					PRS_Init::json( 'error', 'File does not contain right extension!' );
				}

				$json = file_get_contents($file['tmp_name']);
				$json = @json_decode($json, TRUE);

				if ($json === NULL && json_last_error() !== JSON_ERROR_NONE) {
					PRS_Init::json( 'error', 'File that you uploaded is corrupted.' );
				}
				if ( !$json['options'] ) PRS_Init::json( 'error', 'File that you uploaded is corrupted.' );
				if ( !$json['postmeta'] ) PRS_Init::json( 'error', 'File that you uploaded is corrupted.' );

				$options = $json['options'];
				$postmeta = $json['postmeta'];

				foreach ( $options as $key => $option ) {
					update_option($key, $option);
				}

				foreach ( $postmeta as $key => $metas ) {
					$post_id = $key;
					if ( get_post_status( $post_id ) != FALSE ) {
						foreach ( $metas as $value) {
							update_post_meta( $post_id, $value['meta_key'] , $value['meta_value'] );
						}
					}
				}
				PRS_Init::json( 'success', 'Successfully imported settings.' );

			} else {
				PRS_Init::json( 'error', 'Error on upload, please contact support.' );
			}




		}


	}

}