<?php

if (!class_exists('PRS_Init')) {

    class PRS_Init
    {
        // When plugin is activated
        public static function activate()
        {
	        PRS_Init::createTables();
        }

        // When plugin is de-activated
        public static function deactivate()
        {

        }

        // When plugin is uninstalled
        public static function uninstall()
        {
            // Remove Tables
            PRS_Init::removeTables();
        }

        // Create/Modify Tables on new Update
        public static function checkVersion()
        {
            if (get_option('PRS_CURRENT_VERSION') != PRS_CURRENT_VERSION) {
                PRS_Init::createTables();
                update_option('PRS_CURRENT_VERSION', PRS_CURRENT_VERSION);
            }
        }

        // Print JSON
        public static function json($type, $message, $data = NULL)
        {
            wp_send_json(array(
                'status' => $type,
                'message' => $message,
                'data' => $data
            ));
            wp_die();
        }

        // Init hooks
        public static function hooks()
        {
            add_action('admin_menu', array('PRS_Init', 'createPages'));
            add_action('admin_init', array('PRS_Init', 'loadAssets'));
            add_action('admin_enqueue_scripts', array('PRS_Init', 'loadAdminAssets'), 10, 1);
            add_action('wp_enqueue_scripts', array('PRS_Init', 'loadUserAssets'), 10, 1);
	        add_action('admin_print_scripts', array('PRS_Init', 'debugAssets') , 10);
	        add_filter('widget_text', 'do_shortcode');

           // PRS_Post
            add_action('admin_post_prs_save_options', array('PRS_Post', 'saveOptions'));
        }

        // Enqueue scripts admin scripts
        public static function loadAdminAssets($hook)
        {
            // This section is for loading tiny MCE custom buttons on plugins
            // and themes that are using tiny MCE on their pages
            //
            // Just add hook for particular page and tiny MCE will work with Project Supremacy Plugin
            if ( $hook == 'serp-tech_page_serp-tech-builder' || $hook == 'widgets.php' ) {
                wp_enqueue_script('prs_troubleshooter');
                wp_localize_script( 'prs_troubleshooter', 'prs_tinymce_data',
                    array(
                        'keywords'   => MPRS_Tinymce_buttons::getKeywords()
                    )
                );

                wp_localize_script('prs_troubleshooter', 'prs_data',
                    array(
                        'wp_get' => admin_url('admin-ajax.php'),
                        'wp_post' => admin_url('admin-post.php'),
                        'plugins_url' => plugins_url( '/', dirname(__FILE__) ),
                        'sitename' => get_bloginfo('name'),
                        'uploads_dir' => wp_upload_dir()
                    )
                );

	            wp_enqueue_script('prs_admin');
	            wp_enqueue_style('prs_admin');
	            wp_enqueue_style('prs_admin_bootstrap_grid');
	            wp_enqueue_style('prs_uikit_datatables');
	            wp_enqueue_style('prs_font-awesome');


	            wp_enqueue_script('prs_uikit');
	            wp_enqueue_script('prs_uikit_pagination');
	            wp_enqueue_script('prs_uikit_accordion');
	            wp_enqueue_script('prs_uikit_notify');
	            wp_enqueue_style('prs_uikit_notify');

	            // File upload scripts
	            wp_enqueue_script('media-upload');
	            wp_enqueue_script('thickbox');
	            wp_enqueue_style('thickbox');

	            // Chosen
	            wp_enqueue_script('prs_chosen');
	            wp_enqueue_style('prs_chosen');
            }

            if ($hook == 'post-new.php' || $hook == 'post.php' || $hook == 'term.php') {
                wp_enqueue_script('prs_admin');
                wp_enqueue_style('prs_admin');
                wp_enqueue_style('prs_admin_bootstrap_grid');
                wp_enqueue_style('prs_uikit_datatables');
                wp_enqueue_style('prs_font-awesome');

                wp_enqueue_script('prs_uikit_datatables_core');
                wp_enqueue_script('prs_uikit_datatables');
                wp_enqueue_script('prs_uikit');
                wp_enqueue_script('prs_uikit_pagination');
                wp_enqueue_script('prs_uikit_accordion');
                wp_enqueue_script('prs_uikit_notify');
	            wp_enqueue_script('prs_uikit_tooltip');
	            wp_enqueue_style('prs_uikit_tooltip');
                wp_enqueue_style('prs_uikit_notify');

                // File upload scripts
                wp_enqueue_script('media-upload');
                wp_enqueue_script('thickbox');

                // Chosen
                wp_enqueue_script('prs_chosen');
                wp_enqueue_style('prs_chosen');

                wp_enqueue_style('thickbox');

                wp_localize_script('prs_admin', 'prs_data',
                    array(
                        'wp_get' => admin_url('admin-ajax.php'),
                        'wp_post' => admin_url('admin-post.php'),
                        'plugins_url' => plugins_url( '/', dirname(__FILE__) ),
                        'sitename' => get_bloginfo('name'),
                        'uploads_dir' => wp_upload_dir()
                    )
                );

            }
            // End section Tiny MCE fix //
        }

        // Register all Scripts and Styles that are being used in User Area
        public static function loadUserAssets()
        {
            // Register Scripts
            /** Load Global JS **/
            wp_register_script('prs_review_widget', PRS_URL . 'assets/js/review-widget.js', array('jquery'), true);

            /** Load global CSS */
	        wp_register_style('prs_font_awesome', PRS_URL . 'assets/css/vendor/font-awesome.min.css');
	        wp_register_style('prs_review_widget', PRS_URL . 'assets/css/review-widget.css');
	        wp_register_style('prs_review_display', PRS_URL . 'assets/css/review-display.css');

            /**
             *  Add a global JS object to main script
             */
            wp_localize_script('prs_user', 'prs_data',
                array(
                    'wp_get' => admin_url('admin-ajax.php'),
                    'wp_post' => admin_url('admin-post.php'),
                    'plugins_url' => plugins_url(),
                    'sitename' => get_bloginfo('name')
                )
            );

            // Register Styles

            // Enqueue Scripts
            wp_enqueue_script('prs_review_widget');


            wp_enqueue_style('prs_font_awesome');
	        wp_enqueue_style('prs_review_widget');
	        wp_enqueue_style('prs_review_display');
        }

        // Debug all the loaded assets
	    public static function debugAssets() {
        	if (PRS_DEBUG == TRUE) {
		        global $wp_scripts;
		        echo "\n<!-- Project Supremacy - Assets Debug \n";
		        foreach( $wp_scripts->queue as $handle ) {
		        	echo "[$handle]\n";
		        }
		        echo "     Project Supremacy - Assets Debug -->\n";
	        }

		    /**
		     *  Fix enqueues that we have conflict with
		     */
		    if (isset($_GET['page']) && $_GET['page'] == 'prs-projects') {
			    wp_deregister_script('jquery-ui-dialog');
			    wp_dequeue_script('jquery-ui-dialog');
		    }
		    if (isset($_GET['page']) && $_GET['page'] == 'prs-reviews') {
                wp_enqueue_script('jquery-ui-sortable');
			    wp_deregister_script('yg_front_js');
			    wp_dequeue_script('yg_front_js');
			    wp_deregister_script('yg_color_js');
			    wp_dequeue_script('yg_color_js');
		    }
	    }

        // Register all Scripts and Styles that are being used in Admin Area
        public static function loadAssets()
        {
            // Scripts
            wp_register_script('prs_uikit', PRS_URL . 'assets/js/uikit/uikit.min.js', array('jquery'), true);
            wp_register_script('prs_uikit_notify', PRS_URL . 'assets/js/uikit/components/notify.min.js', array('jquery'), true);
            wp_register_script('prs_uikit_accordion', PRS_URL . 'assets/js/uikit/components/accordion.min.js', array('jquery'), true);
            /* wp_register_script('prs_uikit_datepicker', PRS_URL . 'assets/js/uikit/components/datepicker.min.js', array('jquery'), true); */
            wp_register_script('prs_uikit_lightbox', PRS_URL . 'assets/js/uikit/components/lightbox.min.js', array('jquery'), true);
            wp_register_script('prs_uikit_grid', PRS_URL . 'assets/js/uikit/components/grid.min.js', array('jquery'), true);
            wp_register_script('prs_uikit_tooltip', PRS_URL . 'assets/js/uikit/components/tooltip.min.js', array('jquery'), true);
            wp_register_script('prs_uikit_search', PRS_URL . 'assets/js/uikit/components/search.min.js', array('jquery'), true);
            /* wp_register_script('prs_uikit_sortable', PRS_URL . 'assets/js/uikit/components/sortable.min.js', array('jquery'), true); */
            wp_register_script('prs_uikit_upload', PRS_URL . 'assets/js/uikit/components/upload.min.js', array('jquery'), true);
            wp_register_script('prs_jquery_sortable', PRS_URL . 'assets/js/uikit/components/jquery-ui.min.js', array('jquery'), true);
            wp_register_script('prs_jquery_multisortable', PRS_URL . 'assets/js/vendor/jquery.multisortable.js', array('jquery'), true);
            wp_register_script('prs_uikit_datatables_core', PRS_URL . 'assets/js/vendor/jquery.dataTables.min.js', array('jquery'), true);
            wp_register_script('prs_uikit_datatables', PRS_URL . 'assets/js/vendor/dataTables.uikit.min.js', array('jquery'), true);
            wp_register_script('prs_uikit_slideset', PRS_URL . 'assets/js/uikit/components/slideset.min.js', array('jquery'), true);
            wp_register_script('prs_uikit_pagination', PRS_URL . 'assets/js/uikit/components/pagination.min.js', array('jquery'), true);
            wp_register_script('prs_uikit_accordion', PRS_URL . 'assets/js/uikit/components/accordion.min.js', array('jquery'), true);
            wp_register_script('prs_ajaxq', PRS_URL . 'assets/js/ajaxq.js', array('jquery'), true);
            wp_register_script('prs_jstree', PRS_URL . 'assets/js/vendor/jstree.min.js', array('jquery'), true);
            wp_register_script('prs_tagsinput', PRS_URL . 'assets/js/vendor/jquery.tagsinput.js', array('jquery'), true);
            wp_register_script('prs_chosen', PRS_URL . 'assets/js/vendor/chosen.jquery.min.js', array('jquery'), true);
            wp_register_script('prs_select2', PRS_URL . 'assets/js/vendor/select2.min.js', array('jquery'), true);
            /* wp_register_script('prs_google_charts', '//www.gstatic.com/charts/loader.js'); */

            // Datatables
            wp_register_script('prs_datatables', PRS_URL . 'assets/js/vendor/jquery.dataTables.min.js', array('jquery', 'prs_uikit'), true);
            wp_register_script('prs_datatables_uikit', PRS_URL . 'assets/js/vendor/dataTables.uikit.min.js', array('jquery', 'prs_uikit', 'prs_datatables'), true);

            // Table Sorter
            wp_register_script('prs_tablesorter', PRS_URL . 'assets/js/vendor/jquery.tablesorter.min.js', array('jquery'), true);

            // Page Specific Scripts
            wp_register_script('prs_page_settings', PRS_URL . 'assets/js/page_settings.js', array('jquery'), true);
            wp_register_script('prs_page_projects', PRS_URL . 'assets/js/page_projects.js', array('jquery'), true);
	        wp_register_script('prs_text_cloud', PRS_URL . 'assets/js/vendor/jqcloud-1.0.4.js', array('jquery'), true);
            wp_register_script('prs_page_seo', PRS_URL . 'assets/js/page_seo.js', array('jquery'), true);
            wp_register_script('prs_page_reviews_datepicker', PRS_URL . 'assets/js/uikit/components/datepicker.min.js', array('jquery'), true);
            wp_register_script('prs_page_reviews', PRS_URL . 'assets/js/page_reviews.js', array('jquery'), true);

            wp_register_script('prs_admin', PRS_URL . 'assets/js/prs_admin.js', array('jquery'), true, true);

            // Troubleshooter
            wp_register_script('prs_troubleshooter', PRS_URL . 'assets/js/troubleshooter.js', array('jquery'), true);

            /** Load Global JS **/
            wp_register_script('prs_main', PRS_URL . 'assets/js/main.js', array('jquery'), true);

            /**
             *  Add a global JS object to main script
             */
            wp_localize_script('prs_main', 'prs_data',
                array(
                    'wp_get' => admin_url('admin-ajax.php'),
                    'wp_post' => admin_url('admin-post.php'),
                    'wp_admin' => admin_url(),
                    'plugins_url' => plugins_url( '/', dirname(__FILE__) ),
                    'sitename' => get_bloginfo('name')
                )
            );

            // Styles
            wp_register_style('prs_uikit', PRS_URL . 'assets/css/uikit/uikit.min.css');
            wp_register_style('prs_uikit_notify', PRS_URL . 'assets/css/uikit/components/notify.min.css');
            wp_register_style('prs_uikit_datepicker', PRS_URL . 'assets/css/uikit/components/datepicker.min.css');
            wp_register_style('prs_uikit_tooltip', PRS_URL . 'assets/css/uikit/components/tooltip.min.css');
            wp_register_style('prs_uikit_search', PRS_URL . 'assets/css/uikit/components/search.min.css');
            wp_register_style('prs_font-awesome', PRS_URL . 'assets/css/vendor/font-awesome.min.css');
            wp_register_style('prs_chosen', PRS_URL . 'assets/css/vendor/chosen.min.css');
            wp_register_style('prs_animate', PRS_URL . 'assets/css/vendor/animate.css');
            wp_register_style('prs_main', PRS_URL . 'assets/css/main.css');
            wp_register_style('prs_admin', PRS_URL . 'assets/css/prs_admin.css');
            wp_register_style('prs_admin_bootstrap_grid', PRS_URL . 'assets/css/vendor/uikit-grid.css');
            wp_register_style('prs_datatables', PRS_URL . 'assets/css/vendor/dataTables.uikit.min.css');
            wp_register_style('prs_tagsinput', PRS_URL . 'assets/css/vendor/jquery.tagsinput.css');
            wp_register_style('prs_jstree', PRS_URL . 'assets/css/vendor/jstree/themes/default/style.min.css');
            wp_register_style('prs_uikit_datatables', PRS_URL . 'assets/css/vendor/dataTables.uikit.min.css');
            wp_register_style('prs_review_widget', PRS_URL . 'assets/css/review-widget.css');
            wp_register_style('prs_review_display', PRS_URL . 'assets/css/review-display.css');
            wp_register_style('prs_select2', PRS_URL . 'assets/css/vendor/select2.min.css');

            /**
             *  Page Specific CSS
             */
            wp_register_style('prs_page_projects', PRS_URL . 'assets/css/prs_page_projects.css');
            wp_register_style('prs_text_cloud', PRS_URL . 'assets/css/vendor/jqcloud.css');
        }


        // Create Admin Menu Pages
        public static function createPages()
        {
            global $prs_pages, $prs_global_js, $prs_global_css;
            foreach ($prs_pages as $p) {

                $page_hook_suffix = null;
                if ($p['Type'] == 'MENU') {
                    $page_hook_suffix = add_menu_page($p['Page_Title'], $p['Menu_Title'], $p['Capability'], $p['Slug'], array('PRS_Init', 'loadPage'), PRS_URL . $p['Icon'], 2);
                } else {
	                $page_hook_suffix = add_submenu_page($p['Parent_Slug'], $p['Page_Title'], $p['Menu_Title'], $p['Capability'], $p['Slug'], array('PRS_Init', 'loadPage'));
                }
                add_action('admin_print_scripts-' . $page_hook_suffix, function () use ($p, $prs_global_js) {
                    foreach ($prs_global_js as $enqueueName) {
                        wp_enqueue_script($enqueueName);
                    }
                    foreach ($p['JavaScript'] as $enqueueName) {
                        wp_enqueue_script($enqueueName);
                    }
                });
                add_action('admin_print_styles-' . $page_hook_suffix, function () use ($p, $prs_global_css) {
                    foreach ($prs_global_css as $enqueueName) {
                        wp_enqueue_style($enqueueName);
                    }
                    foreach ($p['Css'] as $enqueueName) {
                        wp_enqueue_style($enqueueName);
                    }
                });
            }
        }

        // Load a Page
        public static function loadPage()
        {
            $page = sanitize_text_field($_GET['page']);
            $page = 'page_' . str_replace('prs-', '', $page) . '.php';
            $page = PRS_PATH . '/pages/' . $page;
            if (file_exists($page)) {
                require_once($page);
            } else {
                echo "<h1>404 - Page not Found!</h1>";
            }
        }

        // Verify if site has what it takes to run a plugin
        public static function verify_requirements()
        {
            global $wp_version;
            if (version_compare(PHP_VERSION, PRS_REQUIRED_PHP_VERSION, '<')) {
                return false;
            }
            if (version_compare($wp_version, PRS_REQUIRED_WP_VERSION, '<')) {
                return false;
            }
            return true;
        }

        // Load all Models and init them
        public static function loadModels()
        {
            $models = glob(PRS_PATH . '/models/wp_*');
            foreach ($models as $m) {
                require_once($m);

                // Init the model
                $x = explode('wp_', $m);
                $class = 'MPRS_' . ucfirst(str_replace('.php', '', $x[1]));
                if (method_exists($class, 'initialize')) {
                    call_user_func(array($class, 'initialize'));
                }
            }
        }

        // Register activation/deactivation/uninstall hooks
        public static function registerPluginHooks()
        {
            register_activation_hook(PRS_SLUG, array('PRS_Init', 'activate'));
            register_deactivation_hook(PRS_SLUG, array('PRS_Init', 'deactivate'));
            register_uninstall_hook(PRS_SLUG, array('PRS_Init', 'uninstall'));
        }

        // Create/Modify Tables
        public static function createTables()
        {
            $models = glob(PRS_PATH . '/models/wp_*');
            foreach ($models as $m) {
                $x = explode('wp_', $m);
                $class = 'MPRS_' . ucfirst(str_replace('.php', '', $x[1]));

                if (method_exists($class, 'createTable')) {
                    call_user_func(array($class, 'createTable'));
                }
            }
        }

        // Remove Tables
        public static function removeTables()
        {
            $models = glob(PRS_PATH . '/models/wp_*');
            foreach ($models as $m) {
                $x = explode('wp_', $m);
                $class = 'MPRS_' . ucfirst(str_replace('.php', '', $x[1]));

                if (method_exists($class, 'removeTable')) {
                    call_user_func(array($class, 'removeTable'));
                }
            }
        }

        // Init the plugin
        public static function init()
        {
	        // Init the models
            self::loadModels();

            // Register hooks
            self::registerPluginHooks();

            // Init the hooks
            self::hooks();

            // Perform a version check
            self::checkVersion();
        }
    }

}
