<?php

if ( ! class_exists( 'MPRS_Tinymce_buttons' ) ) {

	class MPRS_Tinymce_buttons {

		public static function initialize() {
			// Fix elementor editor on frontend - don't load TinyMCE plugin when using Elementor editor
			if ( isset($_GET['elementor']) ) return;
			// Fix for Thrive editor (/?tve=true) when editing page
			// if tve is not set and if it's not on true, load plugin
			if ( isset($_GET['tve']) ) return;
			// Check if the page is set
			if (isset($_GET['page'])) {
				// Fix for Optimize Press page builder - don't load our tinymce plugin on their Live Editor
				if ($_GET['page'] == 'optimizepress-page-builder') return;
				// Again Thrive Apprentice conflict
				if ($_GET['page'] == 'tva_dashboard') return;

			}

			add_filter("mce_external_plugins",            array('MPRS_Tinymce_buttons', 'addPlugin'));
			add_filter('mce_buttons',                     array('MPRS_Tinymce_buttons', 'registerButtons'));
			add_action('admin_enqueue_scripts',           array('MPRS_Tinymce_buttons', 'loadAssets'), 10, 1 );
		}

		public static function loadAssets() {
			wp_localize_script( 'prs_admin', 'prs_tinymce_data',
				array(
					'keywords'   => self::getKeywords()
				)
			);
		}

		public static function addPlugin($plugin_array) {
			$plugin_array['prs_keywords']   = PRS_URL . '/assets/js/tinymce_buttons/prs_keywords.js';
			return $plugin_array;
		}

		public static function registerButtons($buttons) {
			array_push($buttons, "prs_keywords");
			return $buttons;
		}

		public static function getKeywords() {
			$formatted = array();
			$data = MPRS_Keywords::query("
						SELECT
							prs_keywords.id,
							prs_keywords.keyword,
							prs_groups.url,
							prs_groups.group_name												
						FROM prs_keywords JOIN prs_groups ON prs_groups.id = prs_keywords.group_id
						ORDER BY prs_keywords.keyword ASC
					");
			if (sizeof($data) > 0) {
				foreach ($data as $d) {
					if ($d['keyword'] != '') {
						$d['url'] = sanitize_title_with_dashes($d['url']);
						$formatted[$d['group_name']][] = $d;
					}
				}
			}

			return $formatted;
		}


	}
}