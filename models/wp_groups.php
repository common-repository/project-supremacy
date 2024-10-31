<?php

if ( ! class_exists( 'MPRS_Groups' ) ) {

	class MPRS_Groups extends MPRS_Model {

		public static function initialize() {
			add_action('admin_post_prs_import_keyword_planner', array('MPRS_Groups', 'importKeywordPlanner'));
			add_action('admin_post_prs_getGroups',              array('MPRS_Groups', 'getGroups'));
			add_action('admin_post_prs_getGroup',               array('MPRS_Groups', 'getGroup'));
			add_action('admin_post_prs_newGroup',               array('MPRS_Groups', 'newGroup'));
			add_action('admin_post_prs_deleteGroup',            array('MPRS_Groups', 'deleteGroup'));
			add_action('admin_post_prs_deleteGroups',           array('MPRS_Groups', 'deleteGroups'));
			add_action('admin_post_prs_deleteKeywords',         array('MPRS_Groups', 'deleteKeywords'));
			add_action('admin_post_prs_updateGroup',            array('MPRS_Groups', 'updateGroup'));
			add_action('admin_post_prs_moveToProject',          array('MPRS_Groups', 'moveToProject'));
			add_action('admin_post_prs_getCfTemplates',         array('MPRS_Groups', 'getCfTemplates')); // Get all Templates
			add_action('admin_post_prs_saveCfTemplate',         array('MPRS_Groups', 'saveCfTemplate')); // Save Template
			add_action('admin_post_prs_applyCfTemplate',        array('MPRS_Groups', 'applyCfTemplate')); // Set Default Template
			add_action('admin_post_prs_createCfTemplate',       array('MPRS_Groups', 'createCfTemplate')); // Create new Template
			add_action('admin_post_prs_deleteCfTemplate',       array('MPRS_Groups', 'deleteCfTemplate')); // Delete Template
		}

		public static function moveToProject() {
			$project_id  = sanitize_text_field($_POST['project_id']);
			$group_id    = sanitize_text_field($_POST['group_id']);

			self::updateData(array(
				'project_id' => $project_id
			), array(
				'id' => $group_id
			));

			PRS_Init::json('success', 'Group has been moved to a different project.');
		}

		public static function importKeywordPlanner(){

			$projectID = sanitize_text_field($_GET['project']);
			if (isset($_FILES['file-import'])) {

				$root_directory   = get_home_path();
				$csv_path         = $root_directory . md5(microtime()) . '.csv';

				@move_uploaded_file($_FILES['file-import']['tmp_name'], $csv_path);

				$file_contents = @file_get_contents($csv_path);

				@unlink($csv_path);

				$kw_data = array();
				$rows = explode("\n", $file_contents);

				unset($rows[0]);
				foreach ($rows as $row) {
					$r = explode("\t", $row);
					if (sizeof($r) < 2) {
						$r = explode(',', $row);
					}
					for($i = 0; $i < sizeof($r); $i++) {
						$r[$i] = preg_replace('/\s+/', ' ', trim($r[$i]));
						$r[$i] = preg_replace('/[^A-Za-z0-9\. -]/', '', $r[$i]);
						$r[$i] = str_replace('Keywords like ', '', $r[$i]);
					}
					$kw_column = 1;
					$vo_column = 3;
					$cp_column = 5;
					if (isset($r[0])) {
						if ($r[0] == '') {
							continue;
						}
					}
					// Check if bad csv
					if (ctype_alpha($r[3])) {
						$vo_column++;
						$cp_column++;
					}

					$volume = explode('  ', $r[$vo_column]);

					if (isset($volume[1])) {
						$volume = trim(@$volume[1]);
					} else {
						$volume = trim(@$volume[0]);
					}

					$volume = str_replace('K', '000', $volume);
					$volume = str_replace('M', '000000', $volume);

					$cpc = $r[$cp_column];
					if (empty($cpc)) {
						$cpc = '0.00';
					}

					if (!isset($kw_data[$r[0]])) {
						$kw_data[$r[0]] = array();
						$kw_data[$r[0]][] = array(
							'keyword' => $r[$kw_column],
							'volumn' => $volume,
							'cpc' => $cpc
						);
					} else {
						$kw_data[$r[0]][] = array(
							'keyword' => $r[$kw_column],
							'volumn' => $volume,
							'cpc' => $cpc
						);
					}
				}

				$keywords = array();
				foreach ($kw_data as $groupName => $groupData) {
					if (sizeof($groupData) < 1) {
						continue;
					}
					$data = array(
						'project_id' => $projectID,
						'group_name' => $groupName,
						'title' => $groupName,
						'url' => strtolower(sanitize_title($groupName)),
						'h1' => $groupName
					);

					$group_id = self::insertData($data);

					foreach ($groupData as $keyword) {
						$keyword_data = array(
							'group_id' => $group_id,
							'keyword' => $keyword['keyword'],
							'volume' => $keyword['volumn'],
							'cpc' => $keyword['cpc']
						);

						$keywords[] = MPRS_Keywords::insertData($keyword_data);
					}
				}

			}
		}

		public static function newGroup() {
			$project_id = sanitize_text_field($_POST['project_id']);
			$group_name = sanitize_text_field($_POST['group_name']);

			self::insertData(array(
				'project_id'    =>$project_id,
				'group_name'    =>$group_name,
				'date_created'  =>date('Y-m-d H:i:s')
			));
		}

        public static function newGroupFromExistingPost($project_id, $group_name, $post_id = '', $title = '', $url = '', $description = '', $h1 = '', $notes = '') {
            self::insertData(array(
                'project_id'    => $project_id,
                'group_name'    => $group_name,
                'id_page_post'  => $post_id,
                'title'         => $title,
                'url'           => $url,
                'description'   => $description,
                'h1'            => $h1,
                'notes'         => $notes,
                'date_created'  => date('Y-m-d H:i:s')
            ));
        }

		public static function deleteGroups() {
			$group_id   = sanitize_text_field($_POST['group_ids']);

			unset($_POST['group_ids']);
			unset($_POST['action']);

			self::query("DELETE g, k FROM prs_groups g LEFT JOIN prs_keywords k ON g.id = k.group_id WHERE g.id IN ($group_id)");
			wp_send_json(array('status'=>'success', 'message'=>'Groups successfully deleted!'));
		}

		public static function deleteGroup() {
            $group_id   = sanitize_text_field($_POST['group_id']);

            unset($_POST['group_id']);
            unset($_POST['action']);

            self::query("DELETE g, k FROM prs_groups g LEFT JOIN prs_keywords k ON g.id = k.group_id WHERE g.id = $group_id");
            wp_send_json(array('status'=>'success', 'message'=>'Group successfully deleted!'));
        }

        public static function deleteKeywords() {
            $keywords   = $_POST['keywords'];
            unset($_POST['keywords']);
            unset($_POST['action']);
            
            $query = 'DELETE FROM prs_keywords WHERE id IN (';
            foreach ($keywords as $id){
                $query .= sanitize_text_field($id) . ",";
            }
            $query = rtrim($query, ',');
            $query .= ')';

            self::query("{$query}");
            wp_send_json(array('status'=>'success', 'message'=>'Group successfully deleted!'));
        }

		public static function updateGroup() {
			$project_id  = sanitize_text_field($_POST['project_id']);
			$group_id    = sanitize_text_field($_POST['group_id']);
			$originalUrl = sanitize_text_field($_POST['oriUrl']);

			$group = self::getData('*', array(
				'id' => $group_id
			));

			$post_id = $group['id_page_post'];

			unset($_POST['project_id']);
			unset($_POST['group_id']);
			unset($_POST['action']);
			unset($_POST['request_type']);
			unset($_POST['oriUrl']);

			self::updateData($_POST, array(
				'id'=>$group_id,
				'project_id'=>$project_id
			));

			// Update the Post/Page Data
			$post_data = array();

			// Set the new URL
            $post_url = sanitize_text_field($_POST['url']);
			if (isset($post_url)) {

				// Create redirection if needed
				$newUrl = $post_url;

				if ($newUrl != $originalUrl) {
					MPRS_Redirects::add($originalUrl, $newUrl);
				}

				$post_data['post_name'] = MPRS_Seo::extract_url_name($post_url);

				update_post_meta($post_id, 'ps_seo_url', $newUrl);
			}

			// Set the new H1
			if (isset($_POST['h1'])) {
				$post_data['post_title'] = sanitize_text_field($_POST['h1']);
			}

			if (sizeof($post_data) > 0) {
				if ($group !== false) {
					$post_data['ID'] = $post_id;
					wp_update_post($post_data);
				}
			}

			// Update SEO Title / Meta
			update_post_meta( $post_id, 'ps_seo_title', sanitize_title($_POST['title']));
			update_post_meta( $post_id, 'ps_seo_description', sanitize_text_field($_POST['description']));
			update_post_meta( $post_id, 'ps_seo_notes', sanitize_text_field($_POST['notes']));
		}

		public static function getGroups() {

			$project_id = sanitize_text_field($_POST['project_id']);
			$post_type = sanitize_text_field($_POST['post_type']);
			$post_type  = isset($post_type) ? $post_type : false;

			$results     = self::query("SELECT * FROM " . self::TABLE_NAME . " WHERE project_id = '$project_id'");
			$outputArray = array();
			if ($results !== false) {

				for($i = 0; $i < sizeof($results); $i++) {

					$group_post_type = false;
					if (!empty($results[$i]['id_page_post'])) {
						$group_post_type = get_post_type($results[$i]['id_page_post']);
					}

					if ($post_type !== false && !empty($post_type)) {
						if ($group_post_type !== false) {
							if ($post_type !== $group_post_type) {
								continue;
							}
						} else {
							continue;
						}
					}

					$keywords = MPRS_Keywords::getKeywords(TRUE, $results[$i]['id']);
					if (!$keywords) $keywords = array();
					$results[$i]['keywords']  = $keywords;
					$results[$i]['post_type'] = $group_post_type;

					$outputArray[] = $results[$i];
				}

			}
			wp_send_json($outputArray);
		}

		public static function getGroup() {
			$project_id = sanitize_text_field($_POST['project_id']);
			$group_id   = sanitize_text_field($_POST['group_id']);
			$results = self::querySingle("SELECT * FROM " . self::TABLE_NAME . " WHERE project_id = '$project_id' AND id = '$group_id'");
			if (!$results) {
				$results = array();
			} else {
				$keywords = MPRS_Keywords::getKeywords(TRUE, $results['id']);
				if (!$keywords) $keywords = array();
				$results['keywords'] = $keywords;
			}
			wp_send_json($results);
		}

        public static function getCfTemplates() {
            if (!get_option('prs_cf_templates')) {
                wp_send_json(array('status'=>'error', 'default'=> 'Default'));
            }

            if (!get_option('prs_cf_default_template')) {
                $CfTemplates = get_option('prs_cf_templates');
                wp_send_json(array('status'=>'success', 'data'=>$CfTemplates, 'default'=> 'Default'));
            } else {
                $CfTemplates = get_option('prs_cf_templates');
                wp_send_json(array('status'=>'success', 'data'=>$CfTemplates, 'default'=> get_option('prs_cf_default_template')));
            }

        }

		public static function saveCfTemplate() {
		    $data = $_POST;
		    $name = sanitize_text_field($data['name']);

            // Unset data
            unset($data['action']);
            unset($data['name']);

            foreach ($data as $key=>$val) {
                $data[$key] = sanitize_text_field($val);
                if ($val < 0 || $val == '') {
                    wp_send_json(array('status'=>'error', 'message'=>"<i class='uk-icon-exclamation'></i> All fields must be at last 0 and cannot be empty"));
                }
            }

            $option[$name] = array(
                'name' => $name,
                'data' => $data
            );

            if (!get_option('prs_cf_templates')) {
                update_option('prs_cf_templates', $option);

                wp_send_json(array('status'=>'success', 'message'=>"<i class='uk-icon-check'></i> Successfully saved template", 'data'=>get_option('prs_cf_templates')));
            } else {
                $prs_cf_templates = get_option('prs_cf_templates');
                $prs_cf_templates[$name] = $option[$name];
                update_option('prs_cf_templates', $prs_cf_templates);

                wp_send_json(array('status'=>'success', 'message'=>"<i class='uk-icon-check'></i> Successfully saved template", 'data'=>get_option('prs_cf_templates')));
            }
        }

        public function applyCfTemplate() {
            $templateName = sanitize_text_field($_POST['templateName']);

            if (empty($templateName) || $templateName == "") {
                wp_send_json(array('status'=>'error', 'message'=>"<i class='uk-icon-exclamation'></i> Template name not defined"));
            }

            update_option('prs_cf_default_template', $templateName);
            wp_send_json(array('status'=>'success', 'message'=>"<i class='uk-icon-check'></i> Template successfully applied"));

        }

        public function createCfTemplate() {
            $data = $_POST;
            $name = sanitize_text_field($data['name']);

            // Unset data
            unset($data['action']);
            unset($data['name']);

            if (empty($name) || $name == "") {
                wp_send_json(array('status'=>'error', 'message'=>"<i class='uk-icon-exclamation'></i> Template name not defined"));
            }

            foreach ($data as $key=>$val) {
                $data[$key] = sanitize_text_field($val);
                if ($val < 0 || $val == '') {
                    wp_send_json(array('status'=>'error', 'message'=>"<i class='uk-icon-exclamation'></i> All fields must be at last 0 and cannot be empty"));
                }
            }

            $option[$name] = array(
                'name' => $name,
                'data' => $data
            );

            if (!get_option('prs_cf_templates')) {
                update_option('prs_cf_templates', $option);
                if (!get_option('prs_cf_default_template')) {
                    update_option('prs_cf_default_template', $name);
                }
                wp_send_json(array('status'=>'success', 'message'=>"<i class='uk-icon-check'></i> Successfully saved template", 'data'=>get_option('prs_cf_templates')));
            } else {
                $prs_cf_templates = get_option('prs_cf_templates');

                if ($prs_cf_templates[$name]) {
                    wp_send_json(array('status'=>'error', 'message'=>"<i class='uk-icon-exclamation'></i> Template with this name already exist, please chose a different name"));
                } else {
                    $prs_cf_templates[$name] = $option[$name];

                    if (!get_option('prs_cf_default_template')) {
                        update_option('prs_cf_default_template', $name);
                    }

                    update_option('prs_cf_templates', $prs_cf_templates);
                    wp_send_json(array('status'=>'success', 'message'=>"<i class='uk-icon-check'></i> Successfully saved template", 'data'=>get_option('prs_cf_templates')));
                }
            }

        }

        public function deleteCfTemplate() {
            $name = sanitize_text_field($_POST['templateName']);

            if (empty($name) || $name == "") {
                wp_send_json(array('status'=>'error', 'message'=>"<i class='uk-icon-exclamation'></i> Template name not defined"));
            }

            if (!get_option('prs_cf_default_template')) {
                if (!get_option('prs_cf_templates')) {
                    wp_send_json(array('status'=>'error', 'message'=>"<i class='uk-icon-exclamation'></i> You cannot delete Default Template"));
                }
            } else {
                if (get_option('prs_cf_default_template') == $name) {
                    wp_send_json(array('status'=>'error', 'message'=>"<i class='uk-icon-exclamation'></i> You cannot delete Default Template"));
                }
            }

            $prs_cf_templates = get_option('prs_cf_templates');
            unset($prs_cf_templates[$name]);
            update_option('prs_cf_templates', $prs_cf_templates);
            wp_send_json(array('status'=>'success', 'message'=>"<i class='uk-icon-check'></i> Successfully deleted", 'data'=>get_option('prs_cf_templates')));
        }

		protected static $TABLE_NAME;
		public function __construct(){
			static::$TABLE_NAME = self::TABLE_NAME;
		}

		// MySQL
		const TABLE_NAME = 'prs_groups';
		public static function createTable() {
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$charset_collate = $wpdb->get_charset_collate();
			$creation_query =
				'CREATE TABLE ' . self::TABLE_NAME . ' (
			        `id` int(11) NOT NULL AUTO_INCREMENT,
			        `project_id` int(11),
			        `id_page_post` int(11),
			        `group_name` varchar(255),
			        `title` varchar(255),
			        `url` varchar(255),
			        `description` text,
			        `h1` varchar(255),
			        `date_created` datetime,
			        `position` int(11) default 999,
			        `notes` longtext,
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