<?php

if ( ! class_exists( 'MPRS_Default' ) ) {

	class MPRS_Default {

		public static function initialize() {
			if (!get_option('ps_defaults')) {
				self::loadDefaults();
			}
		}

		private static function loadDefaults() {
			update_option('ps_defaults', true);
			/**
			 *  BEGIN LOADING DEFAULT VALUES
			 */
			self::generateSEOTemplates();
		}

		public static function generateSEOTemplates() {
			// POST TYPES
			$post_templates  = array();
			$post_types = array( 'post', 'page' );
			foreach ( get_post_types( array( '_builtin' => false, 'public' => true ), 'names' ) as $k => $p ) {
				$post_types[] = $p;
			}

			foreach ( $post_types as $p ) {
				$pa              = array(
					'title'          => '%%title%% %%sep%% %%sitename%%',
					'description'    => '',
					'nofollow'       => 0
				);
				$post_templates[ $p ] = $pa;
			}

			update_option('ps_seo_post_types', $post_templates);

			// TAXONOMIES
			$taxonomy_templates = array();
			$taxonomies = get_taxonomies();
			foreach ( $taxonomies as $p ) {
				$pa              = array(
					'title'          => '%%term_title%% %%sep%% %%sitename%%',
					'description'    => '',
					'nofollow'       => 0
				);
				$taxonomy_templates[ $p ] = $pa;
			}

			update_option('ps_seo_taxonomies', $taxonomy_templates);


			// MISCELLANEOUS
			$miscellaneous_templates = array();
			$miscellaneous_templates['search'] = array(
				'title'          => 'Search Results for “%%search_query%%” %%sep%% %%sitename%%',
				'description'    => '',
				'nofollow'       => TRUE
			);
			$miscellaneous_templates['author'] = array(
				'title'          => '%%author_name%% %%sep%% %%sitename%%',
				'description'    => '',
				'nofollow'       => 0
			);
			$miscellaneous_templates['archive'] = array(
				'title'          => '%%pretty_date%% %%sep%% %%sitename%%',
				'description'    => '',
				'nofollow'       => 0
			);
			$miscellaneous_templates['archive_post'] = array(
				'title'          => '%%pretty_date%% %%sep%% %%sitename%%',
				'description'    => '',
				'nofollow'       => 0
			);
			$miscellaneous_templates['not_found'] = array(
				'title'          => 'Page not Found %%sep%% %%sitename%%',
				'description'    => '',
				'nofollow'       => TRUE
			);
			update_option('ps_seo_miscellaneous', $miscellaneous_templates);
		}

	}

}