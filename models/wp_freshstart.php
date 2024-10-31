<?php

if ( ! class_exists( 'MPRS_Freshstart' ) ) {

	class MPRS_Freshstart {

		public static function initialize() {
			add_action('admin_post_prs_fs_search_plugins', array('MPRS_Freshstart', 'search_WP_API'));
			add_action('admin_post_prs_fs_perform', array('MPRS_Freshstart', 'performFreshStart'));
		}

		public static function performFreshStart() {
			$fs_remove_pages                     = sanitize_text_field($_POST['fs_remove_pages']);
			$fs_remove_posts                     = sanitize_text_field($_POST['fs_remove_posts']);
			$fs_permalinks                       = sanitize_text_field($_POST['fs_permalinks']);
			$fs_remove_comments                  = sanitize_text_field($_POST['fs_remove_comments']);
			$fs_disable_comment_notifications    = sanitize_text_field($_POST['fs_disable_comment_notifications']);
			$fs_disable_comment_moderation       = sanitize_text_field($_POST['fs_disable_comment_moderation']);
			$fs_create_aboutus                   = sanitize_text_field($_POST['fs_create_aboutus']);
			$fs_create_privacypolicy             = sanitize_text_field($_POST['fs_create_privacypolicy']);
			$fs_create_termsofuse                = sanitize_text_field($_POST['fs_create_termsofuse']);
			$fs_create_earningsdisclaimer        = sanitize_text_field($_POST['fs_create_earningsdisclaimer']);
			$fs_create_contactus                 = sanitize_text_field($_POST['fs_create_contactus']);
			$fs_create_amazonassociatedisclosure = sanitize_text_field($_POST['fs_create_amazonassociatedisclosure']);
			$fs_create_affiliatedisclosure       = sanitize_text_field($_POST['fs_create_affiliatedisclosure']);
			$fs_create_copyright                 = sanitize_text_field($_POST['fs_create_copyright']);
			$fs_create_antispam                  = sanitize_text_field($_POST['fs_create_antispam']);
			$fs_create_medicaldisclaimer         = sanitize_text_field($_POST['fs_create_medicaldisclaimer']);
			$fs_create_categories                = sanitize_text_field($_POST['fs_create_categories']);
			$fs_create_categories_list           = sanitize_text_field($_POST['fs_create_categories_list']);
			$fs_create_blank_pages               = sanitize_text_field($_POST['fs_create_blank_pages']);
			$fs_create_blank_pages_list          = sanitize_text_field($_POST['fs_create_blank_pages_list']);
			$fs_create_blank_posts               = sanitize_text_field($_POST['fs_create_blank_posts']);
			$fs_create_blank_posts_list          = sanitize_text_field($_POST['fs_create_blank_posts_list']);
			$fs_remove_plugins                   = sanitize_text_field($_POST['fs_remove_plugins']);
			$fs_remove_themes                    = sanitize_text_field($_POST['fs_remove_themes']);
			$fs_plugins                          = sanitize_text_field($_POST['fs_plugins']);
			$fs_themes                           = sanitize_text_field($_POST['fs_themes']);

			/**
			 *  Remove default Plugins
			 */
			if ($fs_remove_plugins == 1) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php');
				require_once( ABSPATH . 'wp-admin/includes/file.php');

				@deactivate_plugins(array(
					'hello-dolly/hello.php',
					'akismet/akismet.php'
				));

				@delete_plugins(array(
					'hello-dolly/hello.php',
					'akismet/akismet.php'
				));

			}

			/**
			 *  Remove default Themes
			 */
			if ($fs_remove_themes == 1) {

				$themes = wp_get_themes();
				$themes_to_remove = array(
					'twentyfifteen',
					'twentysixteen'
				);

				// Loop through installed themes.
				foreach ( $themes as $theme ) {

					// This is the name of the theme.
					$name = $theme->get_template();

					// If it's not one we want to keep...
					if ( in_array( $name, $themes_to_remove ) ) {
						$stylesheet = $theme->get_stylesheet();

						// Delete the theme.
						@delete_theme( $stylesheet, false );
					}
				}
			}

			/**
			 *  Remove Pages
			 */
			if ($fs_remove_pages == 1) {
				global $wpdb;
				MPRS_Model::removeData(array('post_type' => 'page'), $wpdb->posts);
			}

			/**
			 *  Remove Posts
			 */
			if ($fs_remove_posts == 1) {
				global $wpdb;
				MPRS_Model::removeData(array('post_type' => 'post'), $wpdb->posts);
			}

			/**
			 *  Set Permalinks
			 */
			if ($fs_permalinks == 1) {
				global $wp_rewrite;
				$wp_rewrite->set_permalink_structure('/%postname%/');
				$wp_rewrite->flush_rules();
			}

			/**
			 *  Remove Comments
			 */
			if ($fs_remove_comments == 1) {
				$comments = get_comments();
				foreach($comments as $comment) {
					wp_delete_comment($comment->comment_ID);
				}
			}

			/**
			 *  Disable Comment Notifications (New)
			 */
			if ($fs_disable_comment_notifications == 1) {
				update_option('comments_notify', 0);
			}

			/**
			 *  Disable Comment Notificatiosn (Moderation)
			 */
			if ($fs_disable_comment_moderation == 1) {
				update_option('moderation_notify', 0);
			}

			/** Create Pages */
			// About us
			if ($fs_create_aboutus == 1) {
				$args = array(
					'post_type' => 'page',
					'post_title' => 'About Us',
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_slug' => 'about-us'
				);
				wp_insert_post($args);
			}
			// Privacy Policy
			if ($fs_create_privacypolicy == 1) {
				$args = array(
					'post_type' => 'page',
					'post_title' => 'Privacy Policy',
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_slug' => 'privacy'
				);
				wp_insert_post($args);
			}
			// Terms of Use
			if ($fs_create_termsofuse == 1) {
				$args = array(
					'post_type' => 'page',
					'post_title' => 'Terms of Use',
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_slug' => 'terms'
				);
				wp_insert_post($args);
			}
			// Earning Disclaimer
			if ($fs_create_earningsdisclaimer == 1) {
				$args = array(
					'post_type' => 'page',
					'post_title' => 'Earnings Disclaimer',
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_slug' => 'earnings-disclaimer'
				);
				wp_insert_post($args);
			}
			// Contact Us
			if ($fs_create_contactus == 1) {
				$args = array(
					'post_type' => 'page',
					'post_title' => 'Contact Us',
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_slug' => 'contact'
				);
				wp_insert_post($args);
			}
			// Amazon Associates Disclosure
			if ($fs_create_amazonassociatedisclosure == 1) {
				$args = array(
					'post_type' => 'page',
					'post_title' => 'Amazon Associates Disclose',
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_slug' => 'amazon-associates-disclosure'
				);
				wp_insert_post($args);
			}
			// Affiliate Disclosure
			if ($fs_create_affiliatedisclosure == 1) {
				$args = array(
					'post_type' => 'page',
					'post_title' => 'Affiliate Disclosure',
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_slug' => 'affiliate-disclosure'
				);
				wp_insert_post($args);
			}
			// Copyright Notice
			if ($fs_create_copyright == 1) {
				$args = array(
					'post_type' => 'page',
					'post_title' => 'Copyright Notice',
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_slug' => 'copyright'
				);
				wp_insert_post($args);
			}
			// Anti Spam Policy
			if ($fs_create_antispam == 1) {
				$args = array(
					'post_type' => 'page',
					'post_title' => 'Anti Spam Policy',
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_slug' => 'anti-spam-policy'
				);
				wp_insert_post($args);
			}
			// Medical Disclaimer
			if ($fs_create_medicaldisclaimer == 1) {
				$args = array(
					'post_type' => 'page',
					'post_title' => 'Medical Disclaimer',
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_slug' => 'medical-disclaimer'
				);
				wp_insert_post($args);
			}

			// Create Categories
			if ($fs_create_categories == 1) {
				if (is_array($fs_create_categories_list)) {
					foreach($fs_create_categories_list as $category) {
						wp_create_category($category);
					}
				}
			}

			// Create Pages
			if ($fs_create_blank_pages == 1) {
				if (is_array($fs_create_blank_pages_list)) {
					foreach($fs_create_blank_pages_list as $page) {
						$args = array(
							'post_type'   => 'page',
							'post_title'  => $page,
							'post_status' => 'publish',
							'post_author' => get_current_user_id(),
							'post_slug'   => strtolower(esc_url($page))
						);
						wp_insert_post($args);
					}
				}
			}

			// Create Posts
			if ($fs_create_blank_posts == 1) {
				if (is_array($fs_create_blank_posts_list)) {
					foreach($fs_create_blank_posts_list as $post) {
						$args = array(
							'post_type' => 'post',
							'post_title' => $post,
							'post_status' => 'publish',
							'post_author' => get_current_user_id(),
							'post_slug' => strtolower(esc_url($post))
						);
						wp_insert_post($args);
					}
				}
			}
			/** Create Pages */

			/** Download and Install Plugins */
			$errors = array();
			if (!empty($fs_plugins)) {
				$plugins = explode(',', $fs_plugins);
				foreach($plugins as $plugin) {
					$plugin_path = self::downloadWordPressPlugin($plugin);
					if (!$plugin_path) {
						$errors[] = array('type'=>'plugin', 'name'=>$plugin);
					} else {
						$result = self::installWordPressPlugin($plugin, $plugin_path);
						if (!$result) {
							$errors[] = array('type'=>'plugin', 'name'=>$plugin);
						} else {
							@activate_plugin($result);
						}
					}
				}
			}

			/** Download and Install Themes */
			if (!empty($fs_themes)) {
				$themes = explode(',', $fs_themes);
				foreach($themes as $theme) {
					$theme_path = self::downloadWordPressTheme($theme);
					if (!$theme_path) {
						$errors[] = array('type'=>'plugin', 'name'=>$theme);
					} else {
						$result = self::installWordPressTheme($theme, $theme_path);
						if (!$result) {
							$errors[] = array('type'=>'plugin', 'name'=>$theme);
						}
					}
				}
			}

			// Send output
			if (!empty($errors)) {
				wp_send_json(array('status'=>'error', 'data'=>$errors));
			} else {
				wp_send_json(array('status'=>'success'));
			}
		}

		public static function installWordPressPlugin($slug, $path) {
			do_action( 'pre_install_plugin', $slug );
			$plugins_directory = str_replace($slug . '.zip', '', $path) . 'wp-content/plugins';
			$zip = new ZipArchive;
			if ($zip->open($path) === TRUE) {

				$folderName = @$zip->getNameIndex(0);
				if (!empty($folderName)) {
					$zip->extractTo($plugins_directory);
					$zip->close();
					@unlink($path);

					chdir($plugins_directory . '/' . $folderName);
					foreach (glob("*.php") as $filename) {
						$content = file_get_contents($filename);
						if (strpos($content, 'Plugin Name:') !== false) {
							return $folderName . $filename;
						}
					}
					return $folderName . $slug . '.php';
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public static function downloadWordPressPlugin($slug) {
			$link             = 'https://downloads.wordpress.org/plugin/' . $slug . '.zip';
			$root_directory   = get_home_path();
			$plugin_path      = $root_directory . $slug . '.zip';

			$asOptions  = array(
	            'method' => 'POST',
	            'timeout' => 30,
	            'redirection' => 5,
	            'httpversion' => '1.0',
	            'blocking' => true,
	            'sslverify'   => false
	        );
			$data = @ wp_remote_get($link, $asOptions);
			if ( is_wp_error( $data ) ) {
				return false;
			}
			$data = $data['body'];

			$destination = $plugin_path;
			$file = fopen($destination, "w+");
			fputs($file, $data);
			fclose($file);

			return $plugin_path;
		}

		public static function installWordPressTheme($slug, $path) {
			$themes_directory = str_replace($slug . '.zip', '', $path) . 'wp-content/themes';
			$zip = new ZipArchive;
			if ($zip->open($path) === TRUE) {
				$zip->extractTo($themes_directory);
				$zip->close();
				@unlink($path);
				return true;
			} else {
				return false;
			}
		}

		public static function downloadWordPressTheme($slug) {
			$link             = 'https://downloads.wordpress.org/theme/' . $slug . '.zip';
			$root_directory   = get_home_path();
			$theme_path       = $root_directory . $slug . '.zip';

			$asOptions  = array(
	            'method' => 'POST',
	            'timeout' => 30,
	            'redirection' => 5,
	            'httpversion' => '1.0',
	            'blocking' => true,
	            'sslverify'   => false
	        );
			$data = @ wp_remote_get($link, $asOptions);
			if ( is_wp_error( $data ) ) {
				return false;
			}
			$data = $data['body'];

			$destination = $theme_path;
			$file = fopen($destination, "w+");
			fputs($file, $data);
			fclose($file);

			return $theme_path;
		}


		public static function search_WP_API($return = FALSE) {
			$result = self::wp_api_search(
				$_POST['type'],
				'query_' . $_POST['type'],
				array(
					'search' => $_POST['search']
				)
			);
			if (!$return) {
				wp_send_json($result);
			} else {
				return $result;
			}
		}

		private static function wp_api_search($type, $action, $args) {
			if ( is_array( $args ) ) {
				$args = (object) $args;
			}

			if ( ! isset( $args->per_page ) ) {
				$args->per_page = 24;
			}

			if ( ! isset( $args->locale ) ) {
				$args->locale = get_locale();
			}

			$url = $http_url = 'http://api.wordpress.org/'.$type.'/info/1.0/';
			if ( $ssl = wp_http_supports( array( 'ssl' ) ) )
				$url = set_url_scheme( $url, 'https' );

			$http_args = array(
				'timeout' => 15,
				'body' => array(
					'action' => $action,
					'request' => serialize( $args )
				)
			);

			$result = wp_remote_post( $url, $http_args );

			if (is_wp_error( $result )) {
				return 'error';
			}

			return unserialize($result['body']);
		}

	}
}
