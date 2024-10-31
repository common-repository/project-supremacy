<?php

if ( ! class_exists( 'MPRS_Seo' ) ) {

	class MPRS_Seo {

		public static function initialize() {
		    // Save Post
			add_action( 'save_post', array( 'MPRS_Seo', 'save' ) );

			// Save settings
			add_action( 'admin_post_prs_save_general', array( 'MPRS_Seo', 'saveGeneral' ) );
			add_action( 'admin_post_prs_save_posttypes', array( 'MPRS_Seo', 'savePostTypes' ) );
			add_action( 'admin_post_prs_save_taxonomies', array( 'MPRS_Seo', 'saveTaxonomies' ) );
			add_action( 'admin_post_prs_save_miscellaneous', array( 'MPRS_Seo', 'saveMiscellaneous' ) );

			// Meta Box
			add_action( 'add_meta_boxes', array( 'MPRS_Seo', 'renderBoxes' ) );

			// Extra Category/Term options
			add_action ('edit_category_form_fields', array( 'MPRS_Seo', 'extraTermFields' ) );
			add_action ('edit_category', array( 'MPRS_Seo', 'saveExtraTermFields' ) );

			add_action ('edit_tag_form_fields', array( 'MPRS_Seo', 'extraTermFields' ) );
			add_action ('edit_post_tag', array( 'MPRS_Seo', 'saveExtraTermFields' ) );

			// Titles
			add_filter( 'pre_get_document_title', array( 'MPRS_Seo', 'changeTitle' ), 20 );
			add_filter( 'wp_title', array( 'MPRS_Seo', 'changeTitle' ), 20, 3 );
			add_filter( 'woocommerce_page_title', array( 'MPRS_Seo', 'changeTitle' ), 20, 1 );

			// Description
			add_action( 'wp_head', array( 'MPRS_Seo', 'changeDescription' ) );

			// Open Graph
			add_action( 'wp_head', array( 'MPRS_Seo', 'changeOpenGraph' ) );

			// Meta Robots
			add_action( 'wp_head', array( 'MPRS_Seo', 'changeMetaRobots' ) );

			// Webmaster Verification
			add_action( 'wp_head', array( 'MPRS_Seo', 'webmasterVerification' ) );

			// Target Keyword
			add_action( 'wp_head', array( 'MPRS_Seo', 'forceMetaKeywords' ) );

			// Global Scripts
			add_action('wp_footer', array( 'MPRS_Seo', 'renderCustomScripts' ));

			// Add Custom Columns for SEO enabled posts
			add_filter('manage_posts_columns',       array( 'MPRS_Seo', 'addCustomColumn'));
			add_action('manage_posts_custom_column', array( 'MPRS_Seo', 'renderCustomColumn'), 11, 2);
			add_filter('manage_pages_columns',       array( 'MPRS_Seo', 'addCustomColumn'));
			add_action('manage_pages_custom_column', array( 'MPRS_Seo', 'renderCustomColumn'), 11, 2);

			/// Add new Bulk Actions for SEO enabled posts
			add_action('admin_footer-edit.php',        array( 'MPRS_Seo', 'addBulkActions'));
			add_action('admin_action_prs_seo_enable',  array( 'MPRS_Seo', 'handleBulkAction'));
			add_action('admin_action_prs_seo_disable', array( 'MPRS_Seo', 'handleBulkAction'));
		}

		public function extraTermFields( $tag ) {
			require_once(PRS_PATH . '/pages/metabox/prs_terms.php');
        }

        public function saveExtraTermFields( $term_id ) {

	        if ( isset( $_POST['meta'] ) ) {
		        $id  = $term_id;
		        $tax = $_POST['meta']['taxonomy'];
		        $cat_meta = get_option( $tax . '_' . $id );
		        unset($_POST['meta']['taxonomy']);
		        $cat_keys = array_keys($_POST['meta']);
		        foreach ($cat_keys as $key){
			        if (isset($_POST['meta'][$key])){
				        $cat_meta[$key] = $_POST['meta'][$key];
			        }
		        }
		        //save the option array
		        update_option( $tax . '_' . $id, $cat_meta );
	        }
        }

		public static function renderCustomScripts() {

			$object = $GLOBALS['wp_query']->get_queried_object();
			if ( is_object( $object ) ) {
				$scripts = get_post_meta($object->ID, 'ps_seo_scripts', true);

				$disable_global = get_post_meta($object->ID, 'ps_seo_disable_global_scripts', true);
				if ($disable_global != 1) {
					$scripts = get_option('ps_seo_global_scripts') . "\n" . $scripts;
                }

				if (empty($scripts)) {
					$scripts = get_option('ps_seo_global_scripts');
				}
			} else {
				$scripts = get_option('ps_seo_global_scripts');
            }

		    if (!empty($scripts)) {
		        echo stripslashes($scripts) . "\n";
            }
        }

		public static function addBulkActions() {

			$post_types = self::getAllPostTypes();

			global $post_type;

			if (in_array($post_type, $post_types)) {
				?>
				<script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery("#bulk-action-selector-top").append(
                            '<optgroup label="Project Supremacy">' +
                            '<option value="prs_seo_enable">✓ Enable</option>' +
                            '<option value="prs_seo_disable">✕ Disable</option>' +
                            '</optgroup>'
                        );
                        jQuery("#bulk-action-selector-bottom").append(
                            '<optgroup label="Project Supremacy">' +
                            '<option value="prs_seo_enable">✓ Enable</option>' +
                            '<option value="prs_seo_disable">✕ Disable</option>' +
                            '</optgroup>'
                        );
                    });
				</script>
				<?php
			}
		}

		public static function handleBulkAction() {
			$action    = $_REQUEST['action'];
			$post_type = $_REQUEST['post_type'];

			$sendback = admin_url("edit.php?post_type=$post_type");

			$allowed_actions = array("prs_seo_enable", "prs_seo_disable");
			if (!in_array($action, $allowed_actions)) wp_redirect($sendback);

			$post_ids = $_REQUEST['post'];

			if (empty($post_ids)) wp_redirect($sendback);

			switch ($action) {

				case 'prs_seo_enable':

					foreach ($post_ids as $post_id) {
						update_post_meta($post_id, 'ps_seo_enabled', 1);
					}

					break;
				case 'prs_seo_disable':

					foreach ($post_ids as $post_id) {
						update_post_meta($post_id, 'ps_seo_enabled', 0);
					}

					break;
				default:
					wp_redirect($sendback);
			}

			wp_redirect($sendback);
			exit();
		}

		public static function addCustomColumn($columns) {
			return array_merge($columns,
				array(
					'project_supremacy_seo' => '<img title="Indicates if Project Supremacy SEO is turned on for this Post/Page." src="' . PRS_URL . '/assets/img/logo_menu.png"/>',
				));
		}

		public static function renderCustomColumn($column, $post_id) {
			if ($column == 'project_supremacy_seo') {
				$seo_enabled = get_post_meta($post_id, 'ps_seo_enabled' , true);
				if ($seo_enabled == 1 || PRS_FORCE_SEO == 1) {
					echo "<i style='color:green'>✓</i>";
				} else {
					echo "<i style='color:red'>✕</i>";
				}
			}
		}

		public static function forceMetaKeywords() {

			if ( !get_option('ps_seo_target_keyword') )
				return;

			if ( get_option('ps_seo_target_keyword') == "0" )
				return;

			if (!$keyword = MPRS_Seo::getKeywords())
				return;

			if (PRS_REMOVE_FOOTPRINT == FALSE) {
				echo "\n<!-- Project Supremacy – Meta Keywords -->\n";
            }

			echo "<meta name=\"keywords\" content=\"$keyword\">";

			if (PRS_REMOVE_FOOTPRINT == FALSE) {
				echo "\n<!-- Project Supremacy – Meta Keywords -->\n\n";
			}
		}

		public static function savePostTypes() {
			if (isset($_POST['ps_seo_post_types'])) {
				if (! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'prs_save_posttypes' ) ) {
					PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
				} else {
					$post_types = $_POST['ps_seo_post_types'];
					if (is_array($post_types) && !empty($post_types)) {
						update_option('ps_seo_post_types', $post_types);
					}
					PRS_Init::json( 'success', 'Your post type settings have been saved.' );
				}
			}
			PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
		}

		public static function saveTaxonomies() {
			if (isset($_POST['ps_seo_taxonomies'])) {
				if (! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'prs_save_taxonomies' ) ) {
					PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
				} else {
					$taxonomies = $_POST['ps_seo_taxonomies'];
					if (is_array($taxonomies) && !empty($taxonomies)) {
						update_option('ps_seo_taxonomies', $taxonomies);
					}
					PRS_Init::json( 'success', 'Your taxonomy settings have been saved.' );
				}
			}
			PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
		}

		public static function saveMiscellaneous() {
			if ( isset( $_POST['ps_seo_miscellaneous'] ) ) {
				if (! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'prs_save_miscellaneous' ) ) {
					PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
				} else {
					$miscellaneous = $_POST['ps_seo_miscellaneous'];
					if ( is_array( $miscellaneous ) && ! empty( $miscellaneous ) ) {
						update_option( 'ps_seo_miscellaneous', $miscellaneous );
					}
					PRS_Init::json( 'success', 'Your miscellaneous settings have been saved.' );
				}
			}
			PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
		}

		public static function saveGeneral() {
			if (!empty($_POST)) {
				if (! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'prs_save_general' ) ) {
					PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
				} else {
					foreach($_POST as $key=>$value) {
						if (strpos($key, 'ps_seo') !== false) {
							update_option($key, prs_stripAllSlashes( htmlspecialchars( trim($value) ) ));
						}
					}
					PRS_Init::json( 'success', 'Your general settings have been saved.' );
				}
			}
			PRS_Init::json( 'error', 'Sorry, you are not auththorized user.' );
		}

		public static function webmasterVerification() {
			$webmaster_template = array();

			$bing             = get_option('ps_seo_verify_bing');
			$google           = get_option('ps_seo_verify_google');
			$google_analytics = get_option('ps_seo_verify_google_analytics');
			$pinterest        = get_option('ps_seo_verify_pinterest');
			$yandex           = get_option('ps_seo_verify_yandex');

			if (!empty($bing)) {
				$webmaster_template[] = '<meta name="msvalidate.01" content="'.esc_attr($bing).'" />';
			}
			if (!empty($google)) {
				$webmaster_template[] = '<meta name="google-site-verification" content="'.esc_attr($google).'"/>';
			}
			if (!empty($google_analytics)) {

				$webmaster_template[] = '<script async src="https://www.googletagmanager.com/gtag/js?id='.esc_attr($google_analytics).'"></script>';
				$webmaster_template[] = '<script>';
				$webmaster_template[] = '  window.dataLayer = window.dataLayer || [];';
				$webmaster_template[] = '  function gtag(){dataLayer.push(arguments);}';
				$webmaster_template[] = '  gtag(\'js\', new Date());';
                $webmaster_template[] = '  gtag(\'config\', \''.esc_attr($google_analytics).'\');';
                $webmaster_template[] = '</script>';


			}
			if (!empty($pinterest)) {
				$webmaster_template[] = '<meta name="p:domain_verify" content="'.esc_attr($pinterest).'"/>';
			}
			if (!empty($yandex)) {
				$webmaster_template[] = '<meta name="yandex-verification" content="'.esc_attr($yandex).'" />';
			}
			if (sizeof($webmaster_template) > 0) {
			    if (PRS_REMOVE_FOOTPRINT == FALSE) {
				    echo "\n<!-- Project Supremacy – Webmaster Verification -->\n";
                }

				echo join("\n", $webmaster_template);

				if (PRS_REMOVE_FOOTPRINT == FALSE) {
					echo "\n<!-- Project Supremacy – Webmaster Verification -->\n\n";
				}
			}
		}

		public static function changeMetaRobots() {
			if (is_feed()) return false;

			$robots = '';

			 if ( is_singular() ) {

				 $robots = MPRS_Seo::getRobots();

			 } elseif ( is_category() || is_tag() || is_tax() ) {

				 $robots = MPRS_Seo::getRobotsTaxonomy();

			 } elseif ( is_search() ) {

			     $robots = MPRS_Seo::getRobotsMisc();

             } elseif ( is_author() ) {

				 $robots = MPRS_Seo::getRobotsMisc();

			 } elseif ( is_post_type_archive() ) {

				 $robots = MPRS_Seo::getRobotsMisc();

			 } elseif ( is_archive()  ) {

				 $robots = MPRS_Seo::getRobotsMisc();

			 } elseif ( is_404() ) {

				 $robots = MPRS_Seo::getRobotsMisc();

			 }


			if ( get_option('ps_seo_force_noodp') == "1" ) {
			    if (empty($robots)) {
			    	$robots = 'noodp';
			    } else {
			    	$robots = explode(',', $robots);
			    	$robots[] = 'noodp';
			    	$robots = join(',', $robots);
			    }
			}

			if ( get_option('ps_seo_index_subpages') == "1" ) {
				if (MPRS_Seo::is_sub_page()) {
					if (empty($robots)) {
						$robots = 'noindex';
					} else {
						$robots = explode(',', $robots);
						$robots = array_diff($robots, array("index", "noindex"));
						$robots[] = 'noindex';
						$robots = join(',', $robots);
					}
				}
			}

			if (!empty($robots)) {
				if (PRS_REMOVE_FOOTPRINT == FALSE) {
					echo "\n<!-- Project Supremacy – Meta Robots -->\n";
				}

				echo "<meta name='robots' content='$robots'/>";

				if (PRS_REMOVE_FOOTPRINT == FALSE) {
					echo "\n<!-- Project Supremacy – Meta Robots -->\n";
				}
			}

			return true;
		}

		public static function getRobots() {
			$object = $GLOBALS['wp_query']->get_queried_object();
			if ( is_object( $object ) ) {

				// Load all variables
				$meta = MPRS_Seo::formatMetaVariables(get_post_meta($object->ID));

				if (@$meta['ps_seo_enabled'] == 1 || PRS_FORCE_SEO == 1) {

					if (@$meta['ps_seo_metarobots_enabled'] == TRUE) {
						$robots = explode(',', @$meta['ps_seo_metarobots_advanced']);
						if (!is_array($robots) || sizeof($robots) < 1) {
							$robots = array();
						}
						$robots[] = @$meta['ps_seo_metarobots_index'];
						$robots[] = @$meta['ps_seo_metarobots_follow'];
						return join(',', $robots);
					}

				} else {

					$post_type = ( isset( $object->post_type ) ? $object->post_type : $object->query_var );
					$post_types = get_option('ps_seo_post_types');

					$robots = @$post_types[$post_type]['nofollow'];
					if ($robots == TRUE) {
						return 'noindex,follow';
					} else {
						return false;
					}
				}
			} else {
				return false;
			}
		}

		public static function getRobotsTaxonomy() {
			$object = $GLOBALS['wp_query']->get_queried_object();
			if ( is_object( $object ) ) {

				$taxonomy   = $object->taxonomy;
				$taxonomies = get_option( 'ps_seo_taxonomies' );

				$robots = @$taxonomies[ $taxonomy ]['nofollow'];

				$meta = get_option( $taxonomy . '_' . $object->term_id );
				if ( @$meta['term_seo_nofollow'] == TRUE ) {

					return 'noindex,follow';

				} else if ($robots == TRUE) {

					return 'noindex,follow';

				} else {

					return false;

				}

			} else {
				return false;
			}
		}

		private static function getCurrentMisc() {
	        if (is_search()) {
	            return 'search';
            } elseif (is_author()) {
	            return 'author';
            } elseif (is_post_type_archive()) {
		        return 'archive_post';
	        } elseif (is_archive()) {
		        return 'archive';
	        } elseif (is_404()) {
		        return 'not_found';
	        } else {
	            return false;
            }
        }

		public static function getRobotsMisc() {
			$miscellaneous = get_option('ps_seo_miscellaneous');
			$misc = self::getCurrentMisc();
			if (isset($miscellaneous[$misc])) {
				$robots = @$miscellaneous[$misc]['nofollow'];
				if ($robots == TRUE) {
					return 'noindex,follow';
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public static function changeOpenGraph() {
			if (is_feed()) return false;

			$og = '';

			if ( MPRS_Seo::is_home_static_page() ) {

				$og = MPRS_Seo::getOGHome();

			} else if ( MPRS_Seo::is_home_posts_page() ) {

				$og = MPRS_Seo::getOGHome();

			} else if ( is_singular() ) {

				$og = MPRS_Seo::getOG();

			}

			if (!empty($og)) {
				if (PRS_REMOVE_FOOTPRINT == FALSE) {
					echo "\n<!-- Project Supremacy – Open Graph -->\n";
				}

				echo $og;

				if (PRS_REMOVE_FOOTPRINT == FALSE) {
					echo "\n<!-- Project Supremacy – Open Graph -->\n";
				}
			}

			return true;
		}

		public static function getOG() {
			$object = $GLOBALS['wp_query']->get_queried_object();
			if ( is_object( $object ) ) {
				// Load all variables
				$meta = MPRS_Seo::formatMetaVariables(get_post_meta($object->ID));

				if (isset($meta['ps_seo_enabled']) && $meta['ps_seo_enabled'] != TRUE) {
					return false;
				}

				$og = '';
				$fb_OG = array(
					'Title'       => self::replaceVars(@$meta['ps_seo_fb_title']),
					'Description' => self::replaceVars(@$meta['ps_seo_fb_desc']),
					'Image'       => self::replaceVars(@$meta['ps_seo_fb_img'])
				);
				$tw_OG = array(
					'Title'       => self::replaceVars(@$meta['ps_seo_tw_title']),
					'Description' => self::replaceVars(@$meta['ps_seo_tw_desc']),
					'Image'       => self::replaceVars(@$meta['ps_seo_tw_img'])
				);

				if (!empty($fb_OG['Title']) && !empty($fb_OG['Description']) && !empty($fb_OG['Image'])) {
					$og .= '<meta property="og:locale" content="en_US"/>' . "\n";
					$og .= '<meta property="og:type" content="article"/>' . "\n";
					$og .= '<meta property="og:title" content="'.stripslashes($fb_OG['Title']).'"/>' . "\n";
					$og .= '<meta property="og:description" content="'.stripslashes($fb_OG['Description']).'"/>' . "\n";
					$og .= '<meta property="og:url" content="' . self::getScheme() . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '"/>' . "\n";
					$og .= '<meta property="og:site_name" content="' . get_bloginfo('name') . '"/>' . "\n";
					$og .= '<meta property="og:image" content="'. $fb_OG['Image'] . '" />';
				}
				if (!empty($tw_OG['Title']) && !empty($tw_OG['Description']) && !empty($tw_OG['Image'])) {
					$og .= '<meta name="twitter:card" content="summary"/>' . "\n";
					$og .= '<meta name="twitter:title" content="'.stripslashes($tw_OG['Title']).'"/>' . "\n";
					$og .= '<meta name="twitter:description" content="'.stripslashes($tw_OG['Description']).'"/>' . "\n";
					$og .= '<meta name="twitter:image" content="'.$tw_OG['Image'].'"/>';
				}

				return $og;
			}

			return false;
		}

		public static function getOGHome() {

		    $og = self::getOG();
		    if (!empty($og)) {
			    return $og;
            }

			$og = '';
			$fb_OG = array(
				'Title'       => self::replaceVars(get_option( 'ps_seo_title_fb' )),
				'Description' => self::replaceVars(get_option( 'ps_seo_description_fb' )),
				'Image'       => self::replaceVars(get_option( 'ps_seo_image_fb' ))
			);
			$tw_OG = array(
				'Title'       => self::replaceVars(get_option( 'ps_seo_title_tw' )),
				'Description' => self::replaceVars(get_option( 'ps_seo_description_tw' )),
				'Image'       => self::replaceVars(get_option( 'ps_seo_image_tw' ))
			);
			if (!empty($fb_OG['Title']) && !empty($fb_OG['Description']) && !empty($fb_OG['Image'])) {
				$og .= '<meta property="og:locale" content="en_US"/>' . "\n";
				$og .= '<meta property="og:type" content="article"/>' . "\n";
				$og .= '<meta property="og:title" content="'.stripslashes($fb_OG['Title']).'"/>' . "\n";
				$og .= '<meta property="og:description" content="'.stripslashes($fb_OG['Description']).'"/>' . "\n";
				$og .= '<meta property="og:url" content="' . self::getScheme() . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '"/>' . "\n";
				$og .= '<meta property="og:site_name" content="' . get_bloginfo('name') . '"/>' . "\n";
				$og .= '<meta property="og:image" content="'. $fb_OG['Image'] . '" />';
			}
			if (!empty($tw_OG['Title']) && !empty($tw_OG['Description']) && !empty($tw_OG['Image'])) {
				$og .= '<meta name="twitter:card" content="summary"/>' . "\n";
				$og .= '<meta name="twitter:title" content="'.stripslashes($tw_OG['Title']).'"/>' . "\n";
				$og .= '<meta name="twitter:description" content="'.stripslashes($tw_OG['Description']).'"/>' . "\n";
				$og .= '<meta name="twitter:image" content="'.$tw_OG['Image'].'"/>';
			}
			return $og;
		}

		public static function getScheme() {
			if ( (! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') || (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ) {
				return 'https';
			} else {
				return 'http';
			}
		}

		public static function changeDescription() {

		    if (is_feed()) return false;

			$description = '';

			if ( MPRS_Seo::is_home_static_page() ) {

				$description = MPRS_Seo::getDescriptionHome();

			} else if ( MPRS_Seo::is_home_posts_page() ) {

				$description = MPRS_Seo::getDescriptionHome();

			} else if ( is_singular() ) {

				$description = MPRS_Seo::getDescription();

			} elseif ( is_search() ) {

				$description = MPRS_Seo::getMiscDescription();

			} elseif ( is_category() || is_tag() || is_tax() ) {

				$description = MPRS_Seo::getTermDescription();

			} elseif ( is_author() ) {

				$description = MPRS_Seo::getMiscDescription();

			} elseif ( is_post_type_archive() ) {

				$description = MPRS_Seo::getMiscDescription();

			} elseif ( is_archive() ) {

				$description = MPRS_Seo::getMiscDescription();

			} elseif ( is_404() ) {

				$description = MPRS_Seo::getMiscDescription();

			}

			if (!empty($description)) {
				if (PRS_REMOVE_FOOTPRINT == FALSE) {
					echo "\n<!-- Project Supremacy – Meta Description -->\n";
				}

				echo '<meta name="description" content="'.esc_attr($description).'">';

				if (PRS_REMOVE_FOOTPRINT == FALSE) {
					echo "\n<!-- Project Supremacy – Meta Description -->\n";
				}
			}

			return true;
		}

		public static function getKeywords() {
			$object = $GLOBALS['wp_query']->get_queried_object();
			if ( is_object( $object ) ) {
				// Load all variables
				$meta = MPRS_Seo::formatMetaVariables(get_post_meta($object->ID));

				if (@$meta['ps_seo_enabled'] != 1 && PRS_FORCE_SEO != 1) {
					return false;
				}

				if ( !empty($meta['ps_seo_keyword']) ) {
					return self::replaceVars($meta['ps_seo_keyword'], $object->ID);
				}

				return false;
			}

			return false;
		}

		public static function getDescription() {
			$object = $GLOBALS['wp_query']->get_queried_object();
			if ( is_object( $object ) ) {
				// Load all variables
				$meta = MPRS_Seo::formatMetaVariables(get_post_meta($object->ID));

				if (@$meta['ps_seo_enabled'] != 1 && PRS_FORCE_SEO != 1) {
					return false;
				}

				if ( !empty($meta['ps_seo_description']) ) {
					return self::replaceVars($meta['ps_seo_description'], $object->ID);
				} else {
					$post_type = ( isset( $object->post_type ) ? $object->post_type : $object->query_var );
					$post_types = get_option('ps_seo_post_types');

					$template = @$post_types[$post_type]['description'];
					$template = self::replaceVars($template, $object->ID);

					if (!empty($template)) {
						return $template;
					}
				}

				return false;
			}

			return false;
		}

		public static function getTermDescription() {
			$object = $GLOBALS['wp_query']->get_queried_object();
			if ( is_object( $object ) ) {

				$taxonomies = get_option('ps_seo_taxonomies');
				$taxonomy = $object->taxonomy;

				$meta = get_option( $taxonomy . '_' . $object->term_id);

				if (isset($meta['term_seo_description']) && !empty($meta['term_seo_description']))
				    return MPRS_Seo::replaceVars($meta['term_seo_description']);
				if (isset($taxonomies[$taxonomy])) {
					return MPRS_Seo::replaceVars($taxonomies[$taxonomy]['description']);
				} else {
					return false;
				}

			} else {
				return false;
			}
		}

		public static function getMiscDescription() {
			$miscellaneous = get_option('ps_seo_miscellaneous');
			$misc = self::getCurrentMisc();
			if (isset($miscellaneous[$misc])) {
				return MPRS_Seo::replaceVars($miscellaneous[$misc]['description']);
			} else {
				return false;
			}
		}

		public static function getDescriptionHome() {

		    $description = self::getDescription();
		    if (empty($description)) {
			    $description = get_option('ps_seo_description');
            }

			return self::replaceVars($description);
		}

		public static function changeTitle($title, $separator = '', $separator_location = '') {
			// Ignore Feeds
			if ( is_feed() ) {
				return $title;
			}

			// Original Title
			$original_title = $title;

			if ( MPRS_Seo::is_home_static_page() ) {

				$title = MPRS_Seo::getTitleHome();
				if ( ! is_string( $title ) || '' === $title ) {
					$title = $original_title;
				}

			} else if ( MPRS_Seo::is_home_posts_page() ) {

				$title = MPRS_Seo::getTitleHome();
				if (empty($title)) {
					$title = $original_title;
				}

			} else if ( is_singular() ) {

				$title = MPRS_Seo::getTitle();

				if ( ! is_string( $title ) || '' === $title ) {
					$title = $original_title;
				}

			} elseif ( is_search() ) {

				$title = MPRS_Seo::getMiscTitle();

				if ( ! is_string( $title ) || '' === $title ) {
					$title = $original_title;
				}

			} elseif ( is_category() || is_tag() || is_tax() ) {

				$title = MPRS_Seo::getTermTitle();

				if ( ! is_string( $title ) || '' === $title ) {
					$title = $original_title;
				}

			} elseif ( is_author() ) {

				$title = MPRS_Seo::getMiscTitle();

				if ( ! is_string( $title ) || '' === $title ) {
					$title = $original_title;
				}

			} elseif ( is_post_type_archive() ) {

				$title = MPRS_Seo::getMiscTitle();

				if ( ! is_string( $title ) || '' === $title ) {
					$title = $original_title;
				}

			} elseif ( is_archive() ) {

				$title = MPRS_Seo::getMiscTitle();

				if ( ! is_string( $title ) || '' === $title ) {
					$title = $original_title;
				}

			} elseif ( is_404() ) {

				$title = MPRS_Seo::getMiscTitle();

				if ( ! is_string( $title ) || '' === $title ) {
					$title = $original_title;
				}

			} else {

				$title = MPRS_Seo::getMiscTitle();

				if ( ! is_string( $title ) || '' === $title ) {
					$title = $original_title;
				}

			}

			return $title;
		}

		public static function replaceVars($string, $post_id = 0, $pre_replace = array()) {

			if (is_array($string)) {
			    if (sizeof($string) > 0) {
				    $string = $string[0];
                } else {
			        $string = '';
                }
			}

			include_once(ABSPATH.'wp-admin/includes/plugin.php');
			$theme = wp_get_theme();

		    if ( is_plugin_active('wpglow-builder/wpglow-builder.php') || is_plugin_active('tf-numbers-number-counter-animaton/tf-random_numbers.php') || 'Pro Styler Rev' == $theme->name ) {
			    $vars = array(
				    '%%sitename%%'           => get_bloginfo( 'name' ),
				    '%%tagline%%'            => get_bloginfo( 'description' ),
				    '%%sep%%'                => ( ! get_option( 'ps_seo_title_separator' ) ) ? '-' : get_option( 'ps_seo_title_separator' ),
				    '%%title%%'              => ( $post_id !== 0 ) ? get_the_title( $post_id ) : '',
				    '%%parent_title%%'       => ( $post_id !== 0 ) ? get_the_title( wp_get_post_parent_id( $post_id ) ) : '',
				    '%%date%%'               => get_the_date( '', $post_id ),
				    '%%pretty_date%%'        => get_the_date( 'F Y', $post_id ),
				    '%%tag%%'                => ( $post_id !== 0 ) ? self::getPostTags( $post_id ) : '',
				    '%%category%%'           => ( $post_id !== 0 ) ? self::getPostCategories( $post_id ) : '',
				    '%%category_primary%%'   => ( $post_id !== 0 ) ? self::getPostCategoryPrimary( $post_id ) : '',
				    '%%term_title%%'         => ( is_category() || is_tag() || is_tax() ) ? @$GLOBALS['wp_query']->get_queried_object()->name : '',
                    '%%search_query%%'       => get_search_query(true),
				    '%%ps_seo_title%%'       => ( $post_id !== 0 ) ? get_post_meta( $post_id, 'ps_seo_title', TRUE ) : '',
				    '%%ps_seo_description%%' => ( $post_id !== 0 ) ? get_post_meta( $post_id, 'ps_seo_description', TRUE ) : '',
				    '%%author_name%%'        => get_the_author(),
			    );
            } else {
			    $vars = array(
				    '%%sitename%%'           => get_bloginfo( 'name' ),
				    '%%tagline%%'            => get_bloginfo( 'description' ),
				    '%%sep%%'                => ( ! get_option( 'ps_seo_title_separator' ) ) ? '-' : get_option( 'ps_seo_title_separator' ),
				    '%%title%%'              => ( $post_id !== 0 ) ? get_the_title( $post_id ) : '',
				    '%%parent_title%%'       => ( $post_id !== 0 ) ? get_the_title( wp_get_post_parent_id( $post_id ) ) : '',
				    '%%date%%'               => get_the_date( '', $post_id ),
				    '%%pretty_date%%'        => get_the_date( 'F Y', $post_id ),
				    '%%excerpt%%'            => ( $post_id !== 0 ) ? get_the_excerpt( $post_id ) : '',
				    '%%tag%%'                => ( $post_id !== 0 ) ? self::getPostTags( $post_id ) : '',
				    '%%category%%'           => ( $post_id !== 0 ) ? self::getPostCategories( $post_id ) : '',
				    '%%category_primary%%'   => ( $post_id !== 0 ) ? self::getPostCategoryPrimary( $post_id ) : '',
				    '%%term_title%%'         => ( is_category() || is_tag() || is_tax() ) ? @$GLOBALS['wp_query']->get_queried_object()->name : '',
				    '%%search_query%%'       => get_search_query(true),
				    '%%ps_seo_title%%'       => ( $post_id !== 0 ) ? get_post_meta( $post_id, 'ps_seo_title', TRUE ) : '',
				    '%%ps_seo_description%%' => ( $post_id !== 0 ) ? get_post_meta( $post_id, 'ps_seo_description', TRUE ) : '',
				    '%%author_name%%'        => get_the_author(),
			    );
            }
            foreach($pre_replace as $name=>$value) {
	            if (is_array($name)) $name = $name[0];
	            if (is_array($value)) $value = $value[0];
	            $string = str_replace($name, $value, $string);
            }
			foreach($vars as $name=>$value) {;
				if (is_array($name)) $name = $name[0];
                if (is_array($value)) $value = $value[0];
		        $string = str_replace($name, $value, $string);
			}
			return $string;
		}

		public static function getMiscTitle() {
			$miscellaneous = get_option('ps_seo_miscellaneous');
			$misc = self::getCurrentMisc();
			if (isset($miscellaneous[$misc])) {
				return MPRS_Seo::replaceVars($miscellaneous[$misc]['title']);
			} else {
				return false;
			}
		}

		public static function getTermTitle() {
			$object = $GLOBALS['wp_query']->get_queried_object();
			if ( is_object( $object ) ) {

				$taxonomies = get_option('ps_seo_taxonomies');
				$taxonomy   = $object->taxonomy;

				$meta = get_option( $taxonomy . '_' . $object->term_id);

				if (isset($meta['term_seo_title']) && !empty($meta['term_seo_title'])) {
					return MPRS_Seo::replaceVars($meta['term_seo_title']);
                } else if (isset($taxonomies[$taxonomy])) {
					return MPRS_Seo::replaceVars($taxonomies[$taxonomy]['title']);
				} else {
					return false;
				}

			} else {
				return false;
			}
		}

		public static function getTitleHome() {

		    $title = self::getTitle();

		    if (empty($title)) {
			    $title = get_option('ps_seo_title');
            }

			return self::replaceVars($title);
		}

		public static function getTitle() {

			$object = $GLOBALS['wp_query']->get_queried_object();
			if ( is_object( $object ) ) {
				// Load all variables
				$meta = MPRS_Seo::formatMetaVariables(get_post_meta($object->ID));

				if (@$meta['ps_seo_enabled'] != 1 && PRS_FORCE_SEO != 1) {
					return false;
				}

				if ( !empty($meta['ps_seo_title']) ) {
					return self::replaceVars($meta['ps_seo_title'], $object->ID);
				} else {
					$post_type = ( isset( $object->post_type ) ? $object->post_type : $object->query_var );
					$post_types = get_option('ps_seo_post_types');

					$template = @$post_types[$post_type]['title'];
					$template = self::replaceVars($template, $object->ID);

					if (!empty($template)) {
						return $template;
					}
				}

				return false;
			}

			return false;
		}

		public static function renderBoxes( $post_type ) {
			if (!get_option('ps_hidden')) {
				if ( in_array( $post_type, self::getAllPostTypes()) ) {
					add_meta_box(
						'prs_seo'
						, 'Project Supremacy - On Page SEO'
						, array( 'MPRS_Seo', 'renderSEO' )
						, $post_type
						, 'advanced'
						, 'high'
					);
				}
            }
		}

		public static function save($post_id) {

		    // Fix for trashing posts/pages
            if ( !isset($_POST['post_ID']) ) {
                return $post_id;
            }
            // Fix for Fusion Builder page ID
			if ( $_POST['post_ID'] != $post_id ) {
				$post_id = $_POST['post_ID'];
			}

			// Check if our nonce is set.
			if ( ! isset( $_POST['prs_nonce'] ) ) {
				return $post_id;
			}

			if ( wp_is_post_revision( $post_id ) )
				return $post_id;

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			// Check the user's permissions.
			if ( 'page' === $_POST['post_type'] ) {

				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}

			} else {

				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return $post_id;
				}
			}

			/**
			 *  BEGIN THE SAVING PROCESS
			 */

			// Shortcode support
			$_POST['ps_seo_title']       = trim(strip_tags(do_shortcode($_POST['ps_seo_title'])));
			$_POST['ps_seo_description'] = trim(strip_tags(do_shortcode($_POST['ps_seo_description'])));

			// Change the URL if modified
			$newUrl = self::extract_url($post_id);
			$oriUrl = $_POST['ps_seo_url'];

			// ONLY PERFORM ON UPDATES
            if (
                    $_POST['post_status']          === 'publish' &&
                    $_POST['original_post_status'] === 'publish' &&
                    @$_POST['save']                 === 'Update' &&
                    PRS_DISABLE_REDIRECTS          !== true
            ) {
	            if ($newUrl != $oriUrl) {
		            MPRS_Redirects::add($oriUrl, $newUrl);
	            }
            }

			// Update the URL in Group
			MPRS_Groups::updateData(
				array(
					'url'         => $newUrl,
					'title'       => @$_POST['ps_seo_title'],
					'description' => @$_POST['ps_seo_description'],
					'notes'       => @$_POST['ps_seo_notes'],
                    'h1'          => @$_POST['post_title']
				),
				array(
					'id_page_post' => $post_id
				) );

			/** SEO */
			update_post_meta( $post_id, 'ps_seo_enabled',                @$_POST['ps_seo_enabled'] );
			update_post_meta( $post_id, 'ps_seo_title',                  @$_POST['ps_seo_title'] );
			update_post_meta( $post_id, 'ps_seo_description',            @$_POST['ps_seo_description'] );
			update_post_meta( $post_id, 'ps_seo_url',                    $newUrl );
			update_post_meta( $post_id, 'ps_seo_keyword',                @$_POST['ps_seo_keyword'] );
			update_post_meta( $post_id, 'ps_seo_notes',                  @$_POST['ps_seo_notes'] );
			update_post_meta( $post_id, 'ps_seo_scripts',                @$_POST['ps_seo_scripts'] );
			update_post_meta( $post_id, 'ps_seo_disable_global_scripts', @$_POST['ps_seo_disable_global_scripts'] );


			update_post_meta( $post_id, 'ps_seo_metarobots_enabled',    @$_POST['ps_seo_metarobots_enabled'] );
			update_post_meta( $post_id, 'ps_seo_metarobots_index',      @$_POST['ps_seo_metarobots_index'] );
			update_post_meta( $post_id, 'ps_seo_metarobots_follow',     @$_POST['ps_seo_metarobots_follow'] );

			// ps_seo_metarobots_advanced is an array?
            if ( !is_null(@$_POST['ps_seo_metarobots_advanced']) ) {
                $_POST['ps_seo_metarobots_advanced'] = join(',', $_POST['ps_seo_metarobots_advanced']);
            }

			update_post_meta( $post_id, 'ps_seo_metarobots_advanced',   @$_POST['ps_seo_metarobots_advanced'] );

			update_post_meta( $post_id, 'ps_seo_canonical',             @$_POST['ps_seo_canonical'] );

			update_post_meta( $post_id, 'ps_seo_tw_title',              @$_POST['ps_seo_tw_title'] );
			update_post_meta( $post_id, 'ps_seo_tw_desc',               @$_POST['ps_seo_tw_desc'] );
			update_post_meta( $post_id, 'ps_seo_tw_img',                @$_POST['ps_seo_tw_img'] );

			update_post_meta( $post_id, 'ps_seo_fb_title',              @$_POST['ps_seo_fb_title'] );
			update_post_meta( $post_id, 'ps_seo_fb_desc',               @$_POST['ps_seo_fb_desc'] );
			update_post_meta( $post_id, 'ps_seo_fb_img',                @$_POST['ps_seo_fb_img'] );

			if ( ! wp_is_post_revision( $post_id ) ) {
				remove_action( 'save_post', array( 'MPRS_Seo', 'save' ) );
				add_action( 'save_post', array( 'MPRS_Seo', 'save' ) );
			}

			return $post_id;
		}

		public static function renderSEO($post) {
			require_once(PRS_PATH . '/pages/metabox/prs_seo.php');
		}

		public static function formatMetaVariables($meta) {
			$tmp = array();
			if (empty($meta) || !$meta) {
				return $tmp;
			}
			foreach($meta as $key=>$value) {
				$tmp[$key] = $value[0];
			}
			return $tmp;
		}

		public static function extract_url_name($url) {
		    $url = explode("/", $url);
		    if (isset($url[sizeof($url) - 2])) {
		        $url = $url[sizeof($url) - 2];
            } else {
		        $url = $url[0];
            }
			return $url;
		}

		public static function extract_url($post_id) {
			$url = get_permalink($post_id);
			$url = str_replace($_SERVER['HTTP_HOST'], '', $url);
			$url = str_replace('http://', '', $url);
			$url = str_replace('https://', '', $url);
			return $url;
		}

		public static function is_home_static_page() {
			return ( is_front_page() && 'page' == get_option( 'show_on_front' ) && is_page( get_option( 'page_on_front' ) ) );
		}
		public static function is_posts_page() {
			return ( is_home() && 'page' == get_option( 'show_on_front' ) );
		}
		public static function is_home_posts_page() {
			return ( is_home() && 'posts' == get_option( 'show_on_front' ) );
		}
		public static function is_sub_page() {
			$object = $GLOBALS['wp_query']->get_queried_object();
			if ( is_object( $object ) ) {
				if (is_page() && $object->post_parent > 0) return true;
				return false;
			}

			return false;
		}

		public static function getPostTags($id) {
			$post_tags = wp_get_post_tags( $id );
			$tags = array();
			foreach($post_tags as $tag) {
				$tags[] = $tag->name;
			}
			return join(', ', $tags);
		}

		public static function getPostCategories($id) {
			$post_categories = wp_get_post_categories( $id );
			$cats = array();
			foreach($post_categories as $c){
				$cat = get_category( $c );
				$cats[] = $cat->name;
			}
			return join(', ', $cats);
		}

		public static function getPostCategoryPrimary($id) {
			$category_primary_id = get_post_meta($id, '_category_permalink', true);
			if (!empty($category_primary_id)) {
				$category = get_category($category_primary_id);
				return $category->name;
			} else {
				return '';
			}
		}

		public static function getAllTaxonomies() {
			return get_taxonomies();
		}

		public static function getAllPostTypes() {
			$post_types = array('post','page');
			foreach(get_post_types( array('_builtin'=>false, 'public'=>true),'names' ) as $k=>$p) {
				$post_types[] = $p;
			}
			return $post_types;
		}

		public static function getAllPosts($ONLY_IDS = FALSE) {
			global $wpdb;
			$post_types = self::getAllPostTypes();
			$post_types = "'" . implode("','", $post_types) . "'";
			$out = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type IN ($post_types)", ARRAY_A);
			if ($ONLY_IDS) {
				$n = array();
				foreach($out as $p) {
					$n[] = intval($p['ID']);
				}
				$out = $n;
			}
			return $out;
		}
	}

}
