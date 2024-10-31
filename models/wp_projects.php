<?php

if ( ! class_exists( 'MPRS_Projects' ) ) {

	class MPRS_Projects extends MPRS_Model {

		public static function initialize() {
			add_action('admin_post_prs_get_projects', array('MPRS_Projects', 'getProjects'));
			add_action('admin_post_prs_new_project', array('MPRS_Projects', 'newProject'));
			add_action('admin_post_prs_rename_project', array('MPRS_Projects', 'renameProject'));
			add_action('admin_post_prs_remove_project', array('MPRS_Projects', 'removeProject'));
            add_action('admin_post_prs_export_project', array('MPRS_Projects', 'exportProject'));
            add_action('admin_post_prs_import_project', array('MPRS_Projects', 'importProject'));

            add_action('admin_post_prs_create_page_post', array('MPRS_Projects', 'createPagePost'));

            add_action('admin_post_prs_make_groups', array('MPRS_Projects', 'makeGroups'));

            add_action('admin_post_prs_get_posts', array('MPRS_Projects', 'getPosts'));
            add_action('admin_post_prs_get_post_types', array('MPRS_Projects', 'getPostTypes'));
			add_action('admin_post_prs_attach_to_page_post', array('MPRS_Projects', 'attachToPagePost'));
		}

		protected static $TABLE_NAME;
		public function __construct(){
			static::$TABLE_NAME = self::TABLE_NAME;
		}

		//--------------------------------------------
		//
		//             MySQL Operations
		//
		//--------------------------------------------

		// MySQL
		const TABLE_NAME = 'prs_projects';
		public static function createTable() {
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$charset_collate = $wpdb->get_charset_collate();
			$creation_query =
				'CREATE TABLE ' . self::TABLE_NAME . ' (
			        `id` int(11) NOT NULL AUTO_INCREMENT,
			        `project_name` varchar(255),
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

		// Attach to Page/Post
		public static function attachToPagePost() {

			$post_id     = sanitize_text_field($_POST['post_id']);
			$group_id    = sanitize_text_field($_POST['group_id']);
			$attach_type = sanitize_text_field($_POST['attach_type']);

			$post  = get_post($post_id);
			$group = MPRS_Groups::getData("*", array(
				'id' => $group_id
			));

			$h1    = null;
			$title = null;
			$desc  = null;
			$notes = null;

			if ($attach_type == 'page') {

				$h1    = $post->post_title;
				$title = get_post_meta( $post_id, 'ps_seo_title', true);
				$desc  = get_post_meta( $post_id, 'ps_seo_description', true);
				$notes = get_post_meta( $post_id, 'ps_seo_notes', true);

			} else if ($attach_type == 'group') {

				$h1    = $group['h1'];
				$title = $group['title'];
				$desc  = $group['description'];
				$notes = $group['notes'];

			}

			MPRS_Groups::updateData(array(
				'id_page_post' => $post_id,
				'h1'           => $h1,
				'url'          => MPRS_Seo::extract_url($post_id),
				'title'        => $title,
				'description'  => $desc,
				'notes'        => $notes
			), array(
				'id' => $group_id
			));

			$title = update_post_meta( $post_id, 'ps_seo_title', $title);
			$desc  = update_post_meta( $post_id, 'ps_seo_description', $desc);
			$notes = update_post_meta( $post_id, 'ps_seo_notes', $notes);

			$post_data = array(
				'ID'           => $post_id,
				'post_title'   => $h1
			);

			// Update the post into the database
			wp_update_post( $post_data );

			PRS_Init::json('success', 'Successfully attached page/post.');
		}

		// Get Posts
		public static function getPosts() {
            global $wpdb;

            $aColumns = array(
                'ID',
                'post_author',
                'post_date',
                'post_title',
                'post_status',
                'comment_status',
                'post_name',
                'post_parent',
                'guid',
                'post_type',
                'comment_count'
            );

            /* Indexed column (used for fast and accurate table cardinality) */
            $sIndexColumn = "ID";

            /* DB table to use */
            $sTable = $wpdb->prefix . 'posts';

            /*
             * Paging
             */
            $sLimit = '';
            $iDisplayStart  = sanitize_text_field($_POST['iDisplayStart']);
            $iDisplayLength = sanitize_text_field($_POST['iDisplayLength']);
            if ( isset( $iDisplayStart ) && $iDisplayLength != '-1' ) {
                $sLimit = "LIMIT " . ( $iDisplayStart ) . ", " .
                    ( $iDisplayLength );
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

            // Determine Post Types
            $allowedPostTypes = MPRS_Seo::getAllPostTypes();

            $sWhere = " WHERE post_type IN ('" . join("','", $allowedPostTypes) . "') ";

            if (isset($_POST['PostsType'])) {
                if (!empty($_POST['PostsType'])) {
                    $sWhere = " WHERE post_type = '" . esc_sql($_POST['PostsType']) . "' ";
                }
            }

            $sWhere .= " AND post_status IN ('publish', 'future', 'draft', 'pending') ";

            if (isset($_POST['sSearch'])) {
                if (!empty($_POST['sSearch'])) {
                    $safeSearch = esc_sql($_POST['sSearch']);
                    $sWhere    .= " AND (post_title LIKE '%$safeSearch%' OR ID LIKE '%$safeSearch%' OR post_name LIKE '%$safeSearch%') ";
                }
            }

            /*
             * SQL queries
             * Get data to display
             */
            $sQuery  = "
				SELECT SQL_CALC_FOUND_ROWS " . str_replace( " , ", " ", implode( ", ", $aColumns ) ) . "
				FROM $sTable 
				$sWhere 
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
            $iTotal       = $aResultTotal[0]['COUNT(ID)'];

            /**
             *  Get Author Name / Count Reviews / Get SEO Status
             */
            for($i = 0; $i < sizeof($rResult); $i++) {

                $author = get_user_by('id', $rResult[$i]['post_author']);

                $rResult[$i]['post_author_name'] = isset($author->user_login) ? $author->user_login : 'n/a';
                $rResult[$i]['seo']              = (int)get_post_meta( $rResult[$i]['ID'], 'ps_seo_enabled', true);
                $rResult[$i]['reviews']          = (int)MPRS_Reviews::countReviews($rResult[$i]['ID']);

            }


            /*
             * Output
             */
            $output = array(
                "sEcho"                => intval( $_POST['sEcho'] ),
                "iTotalRecords"        => $iTotal,
                "iTotalDisplayRecords" => $iFilteredTotal,
                "aaData"               => $rResult
            );

            wp_send_json($output);
            wp_die();
		}

		// Get PostTypes
		public static function getPostTypes() {
            $post_types = MPRS_Seo::getAllPostTypes();
            PRS_Init::json('success', 'Retrieved post types.', $post_types);
		}

		// Make Groups
        public static function makeGroups()
        {
            $ids = sanitize_text_field($_POST['ids']);
            $posts      = explode(',', $ids);
            $project_id = sanitize_text_field($_POST['project_id']);

            if ( !isset($project_id) || empty($project_id) ) {
                PRS_Init::json('error', 'Project ID is missing, please enter some project!');
            }

            if ( !is_array($posts) ) {
                PRS_Init::json('error', 'Please select some posts!');
            }

            foreach ( $posts as $post_id ) {
                $post = get_post($post_id);

                $ps_seo_enabled = get_post_meta($post_id, 'ps_seo_enabled', true);
                $wp_post_title  = $post->post_title;
                $wp_post_url    = MPRS_Seo::extract_url($post_id);
                $wp_description = '';

	            $ps_seo_title       = get_post_meta($post_id, 'ps_seo_title', true);
	            $ps_seo_description = get_post_meta($post_id, 'ps_seo_description', true);
	            $ps_seo_notes       = get_post_meta($page_id, 'ps_seo_notes', true);

                MPRS_Groups::newGroupFromExistingPost(
                	$project_id,
	                $wp_post_title,
	                $post_id,
	                $ps_seo_title,
	                $wp_post_url,
	                $ps_seo_description,
	                $wp_post_title,
	                $ps_seo_notes
                );

            }

            PRS_Init::json('success', 'Successfully Created Groups!');
        }

        // Get all projects
		public static function getProjects() {
			wp_send_json(array("aaData"=>MPRS_Projects::getAllData()));
		}

		// Rename Project
		public static function renameProject() {
			$project_name = sanitize_text_field($_POST['project_name']);
			$project_id   = sanitize_text_field($_POST['project_id']);

			MPRS_Projects::updateData(array(
				'project_name' => $project_name
			),array(
				'id' => $project_id
			));
		}

		// Create new Project
		public static function newProject() {
			$projectName = sanitize_text_field($_POST['project_name']);
			$dateCreated = date('Y-m-d H:i:s');
			MPRS_Projects::insertData(array(
				'project_name'=>$projectName,
				'date_created'=>$dateCreated
			));
		}

		// Remove Project
		public static function removeProject() {
			$projectID = sanitize_text_field($_POST['project_id']);
            self::query("DELETE p, g, k FROM prs_projects p LEFT JOIN prs_groups g ON p.id = g.project_id LEFT JOIN prs_keywords k ON g.id = k.group_id WHERE p.id = '$projectID'");

		}

		//--------------------------------------------
		//
		//               Functions
		//
		//--------------------------------------------


        public static function createPagePost(){
		    $request_type = sanitize_text_field($_POST['request_type']);
            if ($request_type == 'single') {
	            $group_id          = intval( $_POST['group_id'] );
	            $group_name        = esc_html( $_POST['group_name'] );
	            $group_title       = trim( esc_html( $_POST['title'] ) );
	            $group_url         = trim( esc_html( $_POST['url'] ) );
	            $group_description = trim( esc_html( $_POST['description'] ) );
	            $group_h1          = trim( esc_html( $_POST['h1'] ) );
	            $post_type         = sanitize_text_field($_POST['type']);
	            $post_note         = trim( esc_html( $_POST['notes'] ) );
            } else {
	            $group_id          = sanitize_text_field($_POST['group_id']);
	            $post_type         = sanitize_text_field($_POST['type']);
	            $group             = self::querySingle( "SELECT * FROM prs_groups WHERE id = '$group_id'" );
	            $group_name        = esc_html( $group['group_name'] );
	            $group_title       = trim( esc_html( $group['title'] ) );
	            $group_url         = trim( esc_html( $group['url'] ) );
	            $group_description = trim( esc_html( $group['description'] ) );
	            $group_h1          = trim( esc_html( $group['h1'] ) );
	            $post_note         = trim( esc_html( $_POST['notes'] ) );
            }


            if ($post_type != 'page' && $post_type != 'post') {
                $post_type = 'page';
            }
            if($group_title == ''){
	            PRS_Init::json('error', 'Your group title is missing!');
            }

            if($group_url == ''){
	            PRS_Init::json('error', 'Your group URL is missing!');
            }

            if($group_h1 == ''){
	            PRS_Init::json('error', 'Your group header is missing!');
            }

            // Check if the Group is already associated with a Page/Post
	        $group = MPRS_Groups::getData('*', array(
	        	'id' => $group_id
	        ));

	        if ($group === false) {
		        PRS_Init::json('error', 'Specified group does not exist.');
	        }

	        if (!empty($group['id_page_post'])) {
		        PRS_Init::json('warning', 'Already exists!', admin_url() . "post.php?post={$group['id_page_post']}&action=edit");
	        }

            // Create Page/Post.
            global $user_ID;

	        $page['post_type']    = $post_type;
	        $page['post_content'] = '';
	        $page['post_parent']  = 0;
	        $page['post_author']  = $user_ID;
	        $page['post_status']  = 'draft';
	        $page['post_title']   = $group_h1;
            $page_id = wp_insert_post($page);

            $data = array(
                'group_name'    => $group_name,
                'title'         => $group_title,
                'url'           => $group_url,
                'description'   => $group_description,
                'h1'            => $group_h1,
                'notes'         => $post_note,
                'id_page_post'  => $page_id,
            );
            $where = array(
                'id' => $group_id
            );
            self::updateData($data, $where, 'prs_groups');

            if ($page_id == 0) {
	            PRS_Init::json('error', 'Could not create page at the moment!');
            }
            wp_update_post(array(
                'ID' => $page_id,
                'post_name' => $group_url,
            ));
            update_post_meta($page_id, '_yoast_wpseo_title', $group_title);
            update_post_meta($page_id, '_yoast_wpseo_metadesc', $group_description);
            update_post_meta($page_id, 'ps_seo_title', $group_title);
            update_post_meta($page_id, 'ps_seo_description', $group_description);
            update_post_meta($page_id, 'ps_seo_notes', $post_note);

	        PRS_Init::json('success', 'Created!', admin_url() . "post.php?post={$page_id}&action=edit");
        }

        // Download to CSV
        public static function exportProject(){
            // Export to csv.
            if(!isset($_GET['project_id'])){
                die('Project ID is missing!');
            }

            $project_id = sanitize_text_field($_GET['project_id']);
            $project_id = intval($project_id);
            if($project_id > 0){
                self::exportToCsv($project_id);
            }else{
                die('Project ID is missing!');
            }
        }

        public static function exportToCsv($project_id){

            $projectData = self::querySingle("SELECT project_name FROM " . self::TABLE_NAME . " WHERE id = '$project_id'");
            if(isset($projectData['project_name'])){
                $projectName = $projectData['project_name'];
            }else{
                $projectName = '';
            }
            unset($projectData);

            $projectGroups = self::query("SELECT * FROM prs_groups WHERE project_id = '$project_id'");

            $output = '"Project Name","' . $projectName . '",';
            $output .= "\n";
            $output .= '"Total Groups","' . count($projectGroups) . '",';
            $output .= "\n";
            foreach ($projectGroups as $group) {
                $group_id = $group['id'];
                $keywords = self::query("SELECT * FROM prs_keywords WHERE group_id = '$group_id'");
                $output .= "\n";
                $output .= 'Group,Title,URL,DESC,H1,';
                $output .= "\n";
                $output .= '"' . $group['group_name'] . '","' . $group['title'] . '","' . $group['url'] . '","' . $group['description'] . '","' . $group['h1'] . '",';
                $output .= "\n";
                $output .= 'Keyword,Volume,CPC,Broad,Phrase,inTITLE,inURL,"' . count($keywords) . '",';
                $output .= "\n";
                foreach ($keywords as $keyword) {
                    $output .= '"' . $keyword['keyword'] . '",="' . $keyword['volume'] . '",="' . $keyword['cpc'] . '",="' . $keyword['broad'] . '",="' . $keyword['phrase'] . '",="' . $keyword['intitle'] . '",="' . $keyword['inurl'] . '",';
                    $output .= "\n";
                }
            }
            $filename = $projectName . ".csv";
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);

            echo $output;
            exit;


        }
        // Import Project from CSV
        public static function importProject(){

            // check user role
            if ( !current_user_can('manage_options') ) {
                wp_die('Not allowed');
            }

            if (isset($_FILES['file-import'])) {
	            // Handle the uploaded file
	            $root_directory   = get_home_path();
	            $csv_path         = $root_directory . md5(microtime()) . '.csv';

	            @move_uploaded_file($_FILES['file-import']['tmp_name'], $csv_path);

	            $file_contents = @file_get_contents($csv_path);

	            @unlink($csv_path);

                $file_contents = str_replace('=', '', $file_contents);
                $file_contents = str_replace('"', '', $file_contents);
                $file_contents = explode("\n", $file_contents);

                $project_name = explode(",", $file_contents[0]);
                $project_name = $project_name[1];

                // Create project
                $data = array(
                    'project_name' => trim($project_name),
                    'date_created' => date("Y-m-d H:i:s")
                );

                $project_id = self::insertData($data);

                $expectGroup = false;
                $current_group_id = 0;
                for($i = 2; $i < sizeof($file_contents); $i++) {
                    $current = $file_contents[$i];
                    if ($current == '') {
                        $i += 1;
                        $expectGroup = true;
                        continue;
                    }
                    if ($expectGroup == true) {
                        $expectGroup = false;
                        $data = array(
                            'project_id' => $project_id
                        );
                        $current_group_id = self::insertData($data, 'prs_groups');
                        $x = explode(",", $current);
                        $data = array(
                            'group_name'    => $x[0],
                            'title'         => $x[1],
                            'url'           => $x[2],
                            'description'   => $x[3],
                            'h1'            => $x[4],
                            'date_created'  => date('Y-m-d H:i:s')
                        );
                        self::updateData($data, array('id'=>$current_group_id), 'prs_groups');
                        $i += 1;
                        continue;
                    }
                    $x = explode(",", $current);
                    $keyword_name = trim($x[0]);
                    $keyword_volume = $x[1];
                    $keyword_cpc = $x[2];
                    $keyword_broad = $x[3];
                    $keyword_phrase = $x[4];
                    $keyword_intitle = $x[5];
                    $keyword_inurl = $x[6];

                    $data = array(
                        'group_id' => $current_group_id,
                    );
                    $keyword_id = self::insertData($data , 'prs_keywords');

                    $data = array();

                    if (!empty($keyword_name)) {
                        $data['keyword'] = $keyword_name;
                    } else {
                        $data['keyword'] = '';
                    }
                    if (!empty($keyword_volume) && $keyword_volume != 0) {
                        $data['volume'] = $keyword_volume;
                    } else {
                        $data['volume'] = 0;
                    }
                    if (!empty($keyword_cpc) && $keyword_cpc != 0) {
                        $data['cpc'] = $keyword_cpc;
                    } else {
                        $data['cpc'] = 0;
                    }
                    if (!empty($keyword_broad) && $keyword_broad != 0) {
                        $data['broad'] = $keyword_broad;
                    } else {
                        $data['broad'] = 0;
                    }
                    if (!empty($keyword_phrase) && $keyword_phrase != 0) {
                        $data['intitle'] = $keyword_phrase;
                    } else {
                        $data['intitle'] = 0;
                    }
                    if (!empty($keyword_intitle) && $keyword_intitle != 0) {
                        $data['phrase'] = $keyword_intitle;
                    } else {
                        $data['phrase'] = 0;
                    }
                    if (!empty($keyword_inurl) && $keyword_inurl != 0) {
                        $data['inurl'] = $keyword_inurl;
                    } else {
                        $data['inurl'] = 0;
                    }

                    self::updateData($data, array('id'=>$keyword_id), 'prs_keywords');
                }
            }
            die();
            
        }

	}

}