<?php // PHP STARTS HERE
	global $prs_update;
	$prs = get_option('prs');
?>


<!-- HTML STARTS HERE -->
<div class="wrap prs" style="max-width: 1200px;margin-left: auto;margin-right: auto;">
    <h2 class="logo-title logo-title-big">
        <img class="logo-image" src="<?= PRS_URL; ?>/assets/img/logo.png"/>
	    Project Supremacy
	    -
	    <small class="hand">Welcome!</small>
    </h2>

	<ul class="uk-tab uk-tab-big" data-uk-tab="{connect:'#tab-content'}">
		<li class="uk-active"><a href=""><i class="fa fa-lock"></i> Welcome</a></li>
        <li><a href=""><i class="fa fa-magic"></i> Easy WP Setup</a></li>
        <li><a href=""><i class="fa fa-gears"></i> Settings</a></li>
        <li><a href=""><i class="fa fa-warning"></i> Troubleshooting</a></li>
        <li><a href=""><i class="fa fa-bar-chart"></i> System Status</a></li>
	</ul>

    <div id="tab-content" class="uk-switcher">

        <!-- Plugin Activation -->
        <div>
            <p class="logo-paragraph logo-paragraph-small">
                <i class="fa fa-question-circle"></i> <b>Welcome</b> to a lite version of Project Supremacy v3! Here you have a set of tools to follow and research your website as it grows its rankings. SetUp your newly creating WordPress with just a few clicks.
                Configure miscellaneous settings that tell our plugin how to behave on your website,
            </p>
            <div>
                <img src="<?= PRS_URL; ?>/assets/img/PSv3-1920-Black.jpg"/>
            </div>
        </div>

        <!-- Easy WP Setup -->
        <div>

            <p class="logo-paragraph logo-paragraph-small">
                <i class="fa fa-question-circle"></i> <b>What is Easy WP Setup?</b> Easy WP Setup will enable you to
                set up your newly created WordPress with blazing speed.
                Just pick out the necessary changes from below and make them happen without even breaking a sweat.
            </p>

            <form class="fs">
                <input type="hidden" name="action" value="prs_fs_perform"/>
                <div class="uk-grid">
                    <div class="uk-width-1-2">
                        <!-- General Settings -->
                        <div class="uk-panel">
                            <h3 class="uk-panel-title"><i class="fa fa-gear"></i> General Settings</h3>

                            <div class="slider-container">
                                <input type="hidden" name="fs_remove_pages" id="fs_remove_pages" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_remove_pages">No</span>
                                </div>
                                <p class="slider-label">Remove all <b>Pages</b> <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="This will remove all the sample pages you start with in WordPress."></i>
                                </p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_remove_posts" id="fs_remove_posts" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_remove_posts">No</span>
                                </div>
                                <p class="slider-label">Remove all <b>Posts</b> <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="This will remove all the sample pages you start with in WordPress."></i>
                                </p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_permalinks" id="fs_permalinks" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_permalinks">No</span>
                                </div>
                                <p class="slider-label">Set <b>permalink structure</b> to Post Name <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="This will set up permalink structure to Post Name. Example: /page-name/"></i>
                                </p>
                            </div>

                        </div>
                        <!-- Comments Settings -->
                        <div class="uk-panel">
                            <h3 class="uk-panel-title"><i class="fa fa-comment"></i> Comments Settings</h3>

                            <div class="slider-container">
                                <input type="hidden" name="fs_remove_comments" id="fs_remove_comments" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_remove_comments">No</span>
                                </div>
                                <p class="slider-label">Remove all <b>Comments</b> <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="This will remove all existing comments from your WordPress."></i>
                                </p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_disable_comment_notifications"
                                       id="fs_disable_comment_notifications" value="0"/>
                                <div class="prs-slider-frame">
                                        <span class="slider-button"
                                              data-element="fs_disable_comment_notifications">No</span>
                                </div>
                                <p class="slider-label">Disable <b>new comments</b> notifications <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Disables notifications when new comments are made."></i></p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_disable_comment_moderation"
                                       id="fs_disable_comment_moderation" value="0"/>
                                <div class="prs-slider-frame">
                                        <span class="slider-button"
                                              data-element="fs_disable_comment_moderation">No</span>
                                </div>
                                <p class="slider-label">Disable <b>comments moderation</b> notifications <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Disables notifications for comment moderation."></i></p>
                            </div>

                        </div>
                        <!-- Content Settings -->
                        <div class="uk-panel">
                            <h3 class="uk-panel-title"><i class="fa fa-file-text"></i> Content Settings</h3>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_aboutus" id="fs_create_aboutus" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_create_aboutus">No</span>
                                </div>
                                <p class="slider-label">Create <b>About Us</b> page <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates an About Us page."></i></p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_privacypolicy" id="fs_create_privacypolicy"
                                       value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_create_privacypolicy">No</span>
                                </div>
                                <p class="slider-label">Create <b>Privacy Policy</b> page <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates a Privacy Policy page."></i></p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_termsofuse" id="fs_create_termsofuse"
                                       value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_create_termsofuse">No</span>
                                </div>
                                <p class="slider-label">Create <b>Terms of Use</b> page <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates Terms of Use page."></i></p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_earningsdisclaimer"
                                       id="fs_create_earningsdisclaimer" value="0"/>
                                <div class="prs-slider-frame">
                                        <span class="slider-button"
                                              data-element="fs_create_earningsdisclaimer">No</span>
                                </div>
                                <p class="slider-label">Create <b>Earnings Disclaimer</b> page <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates Earning Disclaimer page."></i></p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_contactus" id="fs_create_contactus" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_create_contactus">No</span>
                                </div>
                                <p class="slider-label">Create <b>Contact Us</b> page <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates Contact Us page."></i></p>
                            </div>

                            <hr>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_amazonassociatedisclosure"
                                       id="fs_create_amazonassociatedisclosure" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_create_amazonassociatedisclosure">No</span>
                                </div>
                                <p class="slider-label">Create <b>Amazon Associate Disclose</b> page <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates Amazon Associates Disclosure page."></i></p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_affiliatedisclosure"
                                       id="fs_create_affiliatedisclosure" value="0"/>
                                <div class="prs-slider-frame">
                                        <span class="slider-button"
                                              data-element="fs_create_affiliatedisclosure">No</span>
                                </div>
                                <p class="slider-label">Create <b>Affiliate Disclose</b> page <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates Affiliate Disclosure page."></i></p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_copyright" id="fs_create_copyright" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_create_copyright">No</span>
                                </div>
                                <p class="slider-label">Create <b>Copyright Notice</b> page <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates Copyright Notice page."></i></p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_antispam" id="fs_create_antispam" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_create_antispam">No</span>
                                </div>
                                <p class="slider-label">Create <b>Anti Spam Policy</b> page <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates Anti Spam Policy page."></i></p>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_medicaldisclaimer"
                                       id="fs_create_medicaldisclaimer" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_create_medicaldisclaimer">No</span>
                                </div>
                                <p class="slider-label">Create <b>Medical Disclaimer</b> page <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates Medical Disclaimer page."></i></p>
                            </div>

                            <hr>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_categories" id="fs_create_categories"
                                       value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_create_categories">No</span>
                                </div>
                                <p class="slider-label">Create <b>Multiple Categories</b> <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates 5 Categories for you to customize."></i></p>
                            </div>

                            <div class="fs_create_categories_list uk-hidden">

                                <input name="fs_create_categories_list[]" type="text"
                                       placeholder="eg. Category Name" class="uk-width-1-1"/>
                                <input name="fs_create_categories_list[]" type="text"
                                       placeholder="eg. Category Name" class="uk-width-1-1"/>
                                <input name="fs_create_categories_list[]" type="text"
                                       placeholder="eg. Category Name" class="uk-width-1-1"/>
                                <input name="fs_create_categories_list[]" type="text"
                                       placeholder="eg. Category Name" class="uk-width-1-1"/>

                                <button type="button"
                                        class="uk-button uk-button-mini uk-button-primary uk-button-add-category"><i
                                            class="fa fa-plus"></i> Add
                                </button>
                                <button type="button"
                                        class="uk-button uk-button-mini uk-button-danger uk-button-remove-category">
                                    <i class="fa fa-close"></i> Remove
                                </button>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_blank_pages" id="fs_create_blank_pages"
                                       value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_create_blank_pages">No</span>
                                </div>
                                <p class="slider-label">Create <b>Multiple Pages</b> <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates 5 blank pages for you to customize."></i></p>
                            </div>

                            <div class="fs_create_blank_pages_list uk-hidden">

                                <input name="fs_create_blank_pages_list[]" type="text" placeholder="eg. Page Name"
                                       class="uk-width-1-1"/>
                                <input name="fs_create_blank_pages_list[]" type="text" placeholder="eg. Page Name"
                                       class="uk-width-1-1"/>
                                <input name="fs_create_blank_pages_list[]" type="text" placeholder="eg. Page Name"
                                       class="uk-width-1-1"/>
                                <input name="fs_create_blank_pages_list[]" type="text" placeholder="eg. Page Name"
                                       class="uk-width-1-1"/>

                                <button type="button"
                                        class="uk-button uk-button-mini uk-button-primary uk-button-add-pages"><i
                                            class="fa fa-plus"></i> Add
                                </button>
                                <button type="button"
                                        class="uk-button uk-button-mini uk-button-danger uk-button-remove-pages"><i
                                            class="fa fa-close"></i> Remove
                                </button>
                            </div>

                            <div class="slider-container">
                                <input type="hidden" name="fs_create_blank_posts" id="fs_create_blank_posts"
                                       value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_create_blank_posts">No</span>
                                </div>
                                <p class="slider-label">Create <b>Multiple Posts</b> <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Creates 5 blank posts for you to customize."></i></p>
                            </div>

                            <div class="fs_create_blank_posts_list uk-hidden">

                                <input name="fs_create_blank_posts_list[]" type="text" placeholder="eg. Post Name"
                                       class="uk-width-1-1"/>
                                <input name="fs_create_blank_posts_list[]" type="text" placeholder="eg. Post Name"
                                       class="uk-width-1-1"/>
                                <input name="fs_create_blank_posts_list[]" type="text" placeholder="eg. Post Name"
                                       class="uk-width-1-1"/>
                                <input name="fs_create_blank_posts_list[]" type="text" placeholder="eg. Post Name"
                                       class="uk-width-1-1"/>

                                <button type="button"
                                        class="uk-button uk-button-mini uk-button-primary uk-button-add-post"><i
                                            class="fa fa-plus"></i> Add
                                </button>
                                <button type="button"
                                        class="uk-button uk-button-mini uk-button-danger uk-button-remove-post"><i
                                            class="fa fa-close"></i> Remove
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <!-- Plugin Settings -->
                        <div class="uk-panel">
                            <h3 class="uk-panel-title"><i class="fa fa-plug"></i> Plugin Settings</h3>

                            <div class="slider-container">
                                <input type="hidden" name="fs_remove_plugins" id="fs_remove_plugins" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_remove_plugins">No</span>
                                </div>
                                <p class="slider-label">Remove default <b>Plugins</b> <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Removes Akismet & Hello Dolly from your WordPress."></i></p>
                            </div>

                            <h3 class="uk-panel-title"><i class="fa fa-search"></i> Plugin Picker <i
                                        class="fa fa-info-circle help-icon" data-uk-tooltip
                                        title="This will allow you to select which plugins you wish to install."></i>
                            </h3>

                            <!-- Plugin Search -->
                            <div class="uk-search uk-active">
                                <input data-type="plugins" class="uk-search-field" id="search_plugins"
                                       placeholder="type here to search for Plugins...">
                                <div class="uk-dropdown uk-dropdown-search" aria-expanded="false"></div>
                            </div>

                            <!-- Plugins results -->
                            <div class="result-container" id="result_plugins">

                            </div>

                            <!-- Plugin tags -->
                            <input name="fs_plugins" id="plugins" value=""/>


                        </div>

                        <!-- Theme Settings -->
                        <div class="uk-panel">
                            <h3 class="uk-panel-title"><i class="fa fa-paint-brush"></i> Theme Settings</h3>

                            <div class="slider-container">
                                <input type="hidden" name="fs_remove_themes" id="fs_remove_themes" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="fs_remove_themes">No</span>
                                </div>
                                <p class="slider-label">Remove default <b>Themes</b> <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="Removes TwentySixteen and TwentyFifteen themes from your WordPress."></i>
                                </p>
                            </div>

                            <h3 class="uk-panel-title"><i class="fa fa-search"></i> Theme Picker <i
                                        class="fa fa-info-circle help-icon" data-uk-tooltip
                                        title="This will allow you to select which themes you wish to install."></i>
                            </h3>

                            <!-- Theme Search -->
                            <div class="uk-search uk-active">
                                <input data-type="themes" class="uk-search-field" id="search_themes"
                                       placeholder="type here to search for Themes...">
                                <div class="uk-dropdown uk-dropdown-search" aria-expanded="false"></div>
                            </div>

                            <!-- Theme results -->
                            <div class="result-container" id="result_themes">

                            </div>

                            <!-- Theme tags -->
                            <input name="fs_themes" id="themes" value=""/>

                        </div>
                    </div>
                </div>

                <button type="submit" class="uk-button uk-button-primary uk-button-big btn-save-changes">
                    <i class="fa fa-magic"></i> Start Easy WP Setup
                </button>

            </form>
        </div>

        <!-- Settings -->
        <div>
            <p class="logo-paragraph logo-paragraph-small">
                <i class="fa fa-question-circle"></i> <b>Set Project Supremacy V3 the way you like it.</b> Configure miscellaneous settings that tell our plugin how to behave on your website.
            </p>

            <div class="uk-grid">
                <div class="uk-width-1-1">

                    <div class="uk-panel">

                        <h3 class="uk-panel-title"><i class="fa fa-gears"></i> Settings</h3>

                        <form class="ts">
                            <input type="hidden" name="action" value="prs_settings"/>

                            <!-- Disabled 301 Redirects -->
                            <div class="slider-container">
                                <input type="hidden" name="prs_disable_redirects" id="prs_disable_redirects"
                                       value="<?= ( PRS_DISABLE_REDIRECTS == true ) ? 1 : 0; ?>"/>
                                <div class="prs-slider-frame">
                                        <span class="slider-button <?= ( PRS_DISABLE_REDIRECTS == true ) ? 'on' : ''; ?>"
                                              data-element="prs_disable_redirects"><?= ( PRS_DISABLE_REDIRECTS == true ) ? 'Yes' : 'No'; ?></span>
                                </div>
                                <p class="slider-label">Disable <b>Automatic</b> Redirects <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="This will disable automatic generation of 301 redirects that are made when you change the URL of an existing page/post."></i>
                                </p>
                            </div>

                            <button type="submit"
                                    class="uk-button uk-button-primary uk-button-big btn-save-changes">
                                <i class="fa fa-save"></i> Save Changes
                            </button>

                        </form>

                    </div>
                </div>
            </div>

        </div>


        <!-- Troubleshooting -->
        <div>
            <p class="logo-paragraph logo-paragraph-small">
                <i class="fa fa-question-circle"></i> <b>Something is not quite right?</b> Before opening a ticket,
                please check if any of these options will help you solve your issues on this website.
            </p>

            <form class="ts">
                <input type="hidden" name="action" value="prs_troubleshooting"/>

                <div class="uk-grid">
                    <div class="uk-width-1-2">

                        <div class="uk-panel">
                            <h3 class="uk-panel-title"><i class="fa fa-database"></i> Database Fixes</h3>

                            <!-- Database Tweaks -->
                            <div class="slider-container">
                                <input type="hidden" name="ts_update_tables" id="ts_update_tables" value="0"/>
                                <div class="prs-slider-frame">
                                    <span class="slider-button" data-element="ts_update_tables">No</span>
                                </div>
                                <p class="slider-label">Update <b>Table</b> Structure <i
                                            class="fa fa-info-circle help-icon" data-uk-tooltip
                                            title="This will regenerate your table structure while keeping the data intact."></i>
                                </p>
                            </div>

                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <h3 class="uk-panel-title"><i class="fa fa-code"></i> Server Fixes</h3>

                        <!-- Set Unlimited Memory -->
                        <div class="slider-container">
                            <input type="hidden" name="ts_memory_limit" id="ts_memory_limit"
                                   value="<?= ( get_option( 'prs_ts_memory_limit' ) == true ) ? 1 : 0; ?>"/>
                            <div class="prs-slider-frame">
                                <span class="slider-button <?= ( get_option( 'prs_ts_memory_limit' ) == true ) ? 'on' : ''; ?>"
                                      data-element="ts_memory_limit"><?= ( get_option( 'prs_ts_memory_limit' ) == true ) ? 'Yes' : 'No'; ?></span>
                            </div>
                            <p class="slider-label">Set <b>Unlimited</b> Memory Limit <i
                                        class="fa fa-info-circle help-icon" data-uk-tooltip
                                        title="Sometimes the servers are configured to use the minimal amount of memory needed for WordPress to run. However, PSv3 would need a bit more if you have a huge website with a lot of pages and posts."></i>
                            </p>
                        </div>

                    </div>
                </div>

                <button type="submit" class="uk-button uk-button-primary uk-button-big btn-save-changes">
                    <i class="fa fa-save"></i> Save Changes
                </button>

            </form>

        </div>

        <!-- System Status -->
        <div>
            <table class="uk-table system-status-table">
                <thead>
                <tr>
                    <th colspan="2">Project Supremacy V3 – Variables</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Current Version</td>
                    <td><?= PRS_CURRENT_VERSION; ?></td>
                </tr>
                <tr>
                    <td>Plugin Path</td>
                    <td><?= PRS_PATH; ?></td>
                </tr>
                <tr>
                    <td>Plugin URL Path</td>
                    <td><?= PRS_URL; ?></td>
                </tr>
                </tbody>
            </table>

            <table class="uk-table system-status-table">
                <thead>
                <tr>
                    <th colspan="2">WordPress Environment</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Home URL:</td>
                    <td><?= get_home_url(); ?></td>
                </tr>
                <tr>
                    <td>Site URL:</td>
                    <td><?= get_site_url(); ?></td>
                </tr>
                <tr>
                    <td>WP Version:</td>
                    <td><?= get_bloginfo( 'version' ); ?></td>
                </tr>
                <tr>
                    <td>WP Multisite:</td>
                    <td><?= ( is_multisite() ) ? '<i class="fa fa-check uk-text-danger"></i>' : '-' ?></td>
                </tr>
                <tr>
                    <td>WP Memory Limit:</td>
                    <td><?= WP_MEMORY_LIMIT; ?></td>
                </tr>
                <tr>
                    <td>WP Debug Mode:</td>
                    <td><?= ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ? '<i class="fa fa-check"><i>' : '<i class="fa fa-close"><i>' ?></td>
                </tr>
                <tr>
                    <td>Language:</td>
                    <td><?= get_bloginfo( 'language' ); ?></td>
                </tr>
                </tbody>
            </table>

            <table class="uk-table system-status-table">
                <thead>
                <tr>
                    <th colspan="2">Server Environment</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Server Info:</td>
                    <td><?= $_SERVER['SERVER_SOFTWARE']; ?></td>
                </tr>
                <tr>
                    <td>PHP Version:</td>
                    <td><?= phpversion(); ?></td>
                </tr>
                <tr>
                    <td>PHP Post Max Size:</td>
                    <td><?= ini_get( 'post_max_size' ); ?></td>
                </tr>
                <tr>
                    <td>PHP Max Upload Size:</td>
                    <td><?= ini_get( 'upload_max_filesize' ); ?></td>
                </tr>
                <tr>
                    <td>PHP Time Limit:</td>
                    <td><?= ini_get( 'max_execution_time' ); ?></td>
                </tr>
                <tr>
                    <td>PHP Max Input Vars:</td>
                    <td><?= ini_get( 'max_input_vars' ); ?></td>
                </tr>
                <tr>
                    <td>PHP Memory Limit:</td>
                    <td><?php
						$PHP_MEMORY_LIMIT = ini_get( 'memory_limit' );
						$PHP_MEMORY_LIMIT = str_replace( 'M', '', $PHP_MEMORY_LIMIT );
						if ( $PHP_MEMORY_LIMIT < 64 && $PHP_MEMORY_LIMIT != - 1 ) {
							?><i title="You need at least 64M memory limit set in PHP."
                                 class="fa fa-close uk-text-danger"></i><?= $PHP_MEMORY_LIMIT; ?>M<?php
						} else {
							if ( $PHP_MEMORY_LIMIT == - 1 ) {
								echo "Unlimited";
							} else {
								echo $PHP_MEMORY_LIMIT . 'M';
							}
						}
						?></td>
                </tr>
                <tr>
                    <td>OpenSSL:</td>
                    <td><?php
						if ( defined( 'OPENSSL_VERSION_NUMBER' ) ) {

							if ( OPENSSL_VERSION_NUMBER >= 268439647 ) {
								?><i class="fa fa-check uk-text-success"></i><?php
							} else {
								?><i title="OpenSSL version must be at least 1.0.1e."
                                     class="fa fa-close uk-text-danger"></i><?php
							}

						} else {
							?><i title="OpenSSL is not installed." class="fa fa-close uk-text-danger"></i><?php
						}
						?></td>
                </tr>
                <tr>
                    <td>cURL:</td>
                    <td><?= ( function_exists( 'curl_init' ) ) ? '<i class="fa fa-check uk-text-success"></i>' : '<i class="fa fa-close uk-text-danger"></i>'; ?></td>
                </tr>
                <tr>
                    <td>ZipArchive:</td>
                    <td><?= ( class_exists( 'ZipArchive' ) ) ? '<i class="fa fa-check uk-text-success"></i>' : '<i class="fa fa-close uk-text-danger"></i>'; ?></td>
                </tr>
                <tr>
                    <td>DOMDocument:</td>
                    <td><?= ( class_exists( 'DOMDocument' ) ) ? '<i class="fa fa-check uk-text-success"></i>' : '<i class="fa fa-close uk-text-danger"></i>'; ?></td>
                </tr>
                <tr>
                    <td>WP Remote Get:</td>
                    <td><?= ( function_exists( 'wp_remote_get' ) ) ? '<i class="fa fa-check uk-text-success"></i>' : '<i class="fa fa-close uk-text-danger"></i>'; ?></td>
                </tr>
                <tr>
                    <td>WP Remote Post:</td>
                    <td><?= ( function_exists( 'wp_remote_post' ) ) ? '<i class="fa fa-check uk-text-success"></i>' : '<i class="fa fa-close uk-text-danger"></i>'; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div> <!-- .wrap -->

