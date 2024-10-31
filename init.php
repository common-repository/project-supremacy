<?php

/** Global Variables */
define( 'PRS_REQUIRED_PHP_VERSION', '5.4' );
define( 'PRS_REQUIRED_WP_VERSION',  '4.4' );
define( 'PRS_REQUIRED_WP_NETWORK',  FALSE );
define( 'PRS_PATH'               ,  dirname(__FILE__));
define( 'PRS_SLUG'               , 'project-supremacy/project-supremacy.php');
define( 'PRS_SLUG_NAME'          , 'project-supremacy');
define( 'PRS_URL'                ,  plugin_dir_url( __FILE__ ));
define( 'PRS_DISABLE_REDIRECTS'  ,  get_option('ps_disable_redirects'));
define( 'PRS_FORCE_SEO'          ,  get_option('ps_seo_always_on'));
define( 'PRS_REMOVE_FOOTPRINT'   ,  get_option('ps_remove_footprint'));
/** Global Variables */

/** Define Global JS/CSS */
$prs_global_js  = array(
	'prs_uikit',
	'prs_uikit_notify',
	'prs_uikit_accordion',
	'prs_uikit_lightbox',
	'prs_uikit_grid',
	'prs_uikit_tooltip',
	'prs_main',
	'prs_ajaxq'
);
$prs_global_css = array(
	'prs_main',
	'prs_uikit',
	'prs_uikit_notify',
	'prs_uikit_tooltip',
	'prs_font-awesome'
);

/** Define Pages */
$prs_pages = array(
    // Main Tab
    array(
        "Type" => "MENU",
        "Page_Title" => "Supremacy V3",
        "Menu_Title" => "Supremacy V3",
        "Capability" => "manage_options",
        "Slug" => "prs-settings",
        "Parent_Slug" => "",
        "Icon" => "/assets/img/logo_menu.png",
        "JavaScript" => array(
	        'prs_page_settings',
	        'prs_tagsinput',
	        'prs_uikit_search'
        ),
        "Css" => array(
        	'prs_animate',
	        'prs_tagsinput',
	        'prs_uikit_search'
        )
    ),
	// Settings
	array(
		"Type" => "SUBMENU",
		"Page_Title" => "Dashboard",
		"Menu_Title" => "Dashboard",
		"Capability" => "manage_options",
		"Slug" => "prs-settings",
		"Parent_Slug" => "prs-settings",
		"Icon" => "/assets/img/logo_menu.png",
		"JavaScript" => array(
			'prs_page_settings',
			'prs_tagsinput',
			'prs_uikit_search'
		),
		"Css" => array(
			'prs_animate',
			'prs_tagsinput',
			'prs_uikit_search'
		)
	),
	// SEO Settings
	array(
		"Type" => "SUBMENU",
		"Page_Title" => "SEO Settings",
		"Menu_Title" => "SEO Settings",
		"Capability" => "manage_options",
		"Slug" => "prs-seo",
		"Parent_Slug" => "prs-settings",
		"Icon" => "/assets/img/logo_menu.png",
		"JavaScript" => array(
			'prs_page_seo',
			'media-upload',
			'thickbox'
		),
		"Css" => array(
			'thickbox'
		),
		'Feature' => 'seo'
	),
	// Reviews
	array(
		"Type" => "SUBMENU",
		"Page_Title" => "Reviews",
		"Menu_Title" => "Reviews",
		"Capability" => "manage_options",
		"Slug" => "prs-reviews",
		"Parent_Slug" => "prs-settings",
		"Icon" => "/assets/img/logo_menu.png",
		"JavaScript" => array(
			'prs_page_reviews_datepicker',
			'prs_page_reviews',
			'prs_uikit_slideset',
			'jquery-ui-sortable',
			'prs_datatables',
			'prs_datatables_uikit'
		),
		"Css" => array(
			'prs_review_widget',
			'prs_review_display'
		),
		'Feature' => 'reviews'
	),
	// Project Management
	array(
		"Type" => "SUBMENU",
		"Page_Title" => "Project Management",
		"Menu_Title" => "Project Management",
		"Capability" => "manage_options",
		"Slug" => "prs-projects",
		"Parent_Slug" => "prs-settings",
		"Icon" => "/assets/img/logo_menu.png",
		"JavaScript" => array(
			'prs_datatables',
			'prs_datatables_uikit',
			'prs_tablesorter',
			'prs_jstree',
            'prs_uikit_upload',
            'prs_select2',
            'prs_tagsinput',
            'prs_jquery_sortable',
            'prs_jquery_multisortable',
            'prs_page_projects',
			'prs_text_cloud',
		),
		"Css" => array(
			'prs_datatables',
			'prs_page_projects',
			'prs_jstree',
            'prs_select2',
            'prs_tagsinput',
			'prs_text_cloud',
		),
		'Feature' => 'projectplanner'
	)

);
/** Define Pages */


/** Turn on error reporting if debug is on */
if (PRS_DEBUG) {
	@ini_set('display_errors', 1);
	@error_reporting(E_ALL);
} else {
	// Turn off PHP errors / warnings
	@ini_set('display_errors', 0);
	@error_reporting(0);
}

/** Set the Version in Options */
if (!get_option('PRS_CURRENT_VERSION')) {
	update_option('PRS_CURRENT_VERSION', PRS_CURRENT_VERSION);
}
/** Set the Version in Options */

/** Include all dependencies */
$files = glob(PRS_PATH.'/inc/wp_*');
foreach($files as $f) { require_once ( $f ); }
/** Include all dependencies */


/** Start the plugin */
if ( PRS_Init::verify_requirements() ) {
	PRS_Init::init();
} else {
	add_action( 'admin_notices', function(){
		global $wp_version;
		require_once(PRS_PATH . '/pages/notices/requirements-error.php');
	} );
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	deactivate_plugins( PRS_SLUG );
}

add_filter('plugin_action_links', 'prs_additional_links', 10, 2);

function prs_additional_links($links_array, $plugin_file_name)
{
	if(strpos($plugin_file_name, 'project-supremacy.php')) {
		array_unshift($links_array, '<a href="https://v3.projectsupremacy.com/get-started/" title="Buy Pro Now" target="_blank">Buy Pro</a>');
	}
	return $links_array;
}
