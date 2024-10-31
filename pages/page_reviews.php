<?php
	$ps_review = stripslashes_deep( get_option('ps_review') );
?>

<script>
    var date_today = "<?php echo date("Y-m-d"); ?>";
</script>

<style>
    #availablePagesModal .uk-modal-header {
        margin-bottom: 0;
    }
    #availablePagesCloneModal .uk-modal-header {
        margin-bottom: 0;
    }
    #availablePostsHolder {
        display: inline;
    }
    .posts-actions2, .posts-actions-bottom2 {
        padding: 8px 10px;
        border-left: 1px solid;
        border-right: 1px solid;
        border-color: #dddddd;
    }

    .posts-actions2 label {
        margin-left: 10px;
        margin-top: 3px;
    }

    .posts-actions-clone {
        padding: 8px 10px;
        border-left: 1px solid;
        border-right: 1px solid;
        border-color: #dddddd;
    }

    .posts-actions-clone label {
        margin-left: 10px;
        margin-top: 3px;
    }

    .posts-actions-bottom2 {
        border-bottom: 1px solid #dddddd;
    }

    .posts-actions2 .dataTables_filter {
        float: right;
        margin-left: 15px;
        font-family: sans-serif;
    }

    .posts-actions2 .dataTables_length {
        float: right;
        font-family: sans-serif;
    }

    .posts-actions-clone .dataTables_filter {
        float: right;
        margin-left: 15px;
        font-family: sans-serif;
    }

    .posts-actions-clone .dataTables_length {
        float: right;
        font-family: sans-serif;
    }

    .posts-actions-bottom2 .dataTables_info {
        float: left;
        font-family: sans-serif;
        padding-top: 0;
    }

    .posts-actions-bottom2 .dataTables_paginate {
        float: right;
        font-family: sans-serif;
    }

    .posts-actions-bottom2 .dataTables_paginate .pagination {
        margin: 0;
        font-family: sans-serif;
    }
    #review_settings_tab .uk-grid {
        margin-top: 12px;
    }
    #review_settings_tab .uk-grid .uk-width-1-1 h3 {
        margin-top: 17px;
        margin-bottom: 0;
    }
    #review_settings_tab .uk-grid .uk-width-1-1 hr {
        margin-top: 5px;
    }
    .review_design_tab .uk-grid,.review_rating_tab .uk-grid, .review_display_tab .uk-grid {
        margin-top: 16px;
    }
</style>

<!-- HTML STARTS HERE -->
<div class="wrap prs">
    <h2 class="logo-title">
        <img class="logo-image" src="<?= PRS_URL; ?>/assets/img/logo.png"/>
        Project Supremacy - Reviews
        -
        <small class="hand">Manage your website reviews and review widget...</small>

	    <button class="uk-button uk-button-success add_review" type="button"><i class="fa fa-plus"></i> Add New Review</button>

    </h2>

	<p class="logo-paragraph">
		<b><i class="fa fa-question-circle"></i> What can you do here?</b> You can manage your website reviews and configure how your Review Widget looks like and how it behaves.
	</p>


	<ul class="uk-tab uk-tab-big" data-uk-tab="{connect:'#tab-content'}">
		<li class="uk-active"><a href=""><i class="fa fa-comment-o"></i> Reviews</a></li>
		<li><a href=""><i class="fa fa-paint-brush"></i> Customize</a></li>
        <li><a href=""><i class="fa fa-question-circle"></i> Shortcodes</a></li>
	</ul>

	<div id="tab-content" class="uk-switcher">

		<!-- Reviews -->
		<div class="no-padding">
			<div class="uk-block uk-block-muted">
				<h1><i class="fa fa-comments-o"></i> You currently don't have any reviews for your website...</h1>
				<p>
					<i class="fa fa-info-circle"></i> In order for your visitors to be able to leave reviews for your website,
					you can go to <a href="<?=admin_url();?>widgets.php" target="_blank">Appearance > Widgets</a> page and
					drag <b>Project Supremacy - Reviews</b> widgets in one of the widget areas on your website or you can use shortcode <kbd>[prs_reviews_widget]</kbd> in your Page/Post content where you want your Review Widget to appear.
				</p>
			</div>

			<table class="wp-list-table widefat fixed striped rTable" cellspacing="0" width="100%">
				<thead>
				<tr>
					<td class="check-column"><input class="select-review-all" type="checkbox"></td>
                    <th class="column-author">Author</th>
                    <th>Review Content</th>
                    <th class="column-page">In Response To</th>
                    <th class="column-date">Submitted On</th>
				</tr>
				</thead>
                <tbody>
                <tr>
                    <td colspan="5">No reviews found. To collect reviews, use the <kbd>Reviews Widget</kbd> in Appearance > Widgets.</td>
                </tr>
                </tbody>
				<tfoot>
				<tr>
                    <td class="check-column"><input class="select-review-all" type="checkbox"></td>
                    <th class="column-author">Author</th>
                    <th>Review Content</th>
                    <th class="column-page">In Response To</th>
                    <th class="column-date">Submitted On</th>
				</tr>
				</tfoot>
			</table>
		</div>

		<!-- Design -->
		<div>

			<form class="save-review-widget">

				<input type="hidden" name="action" value="prs_saveReviewWidget"/>
				<?php wp_nonce_field( 'prs_saveReviewWidget', '_wpnonce' ); ?>

				<div class="uk-grid uk-grid-small">

					<div class="uk-width-large-5-10 uk-width-medium-5-10">

                        <div class="uk-grid uk-grid-small">

                            <ul class="uk-tab uk-tab-left uk-tab-custom uk-width-large-2-10 uk-width-medium-4-10" data-uk-tab="{connect:'#tab-content-inside', swiping: false}">

                                <li class="divider-tab uk-active"><a href=""><i class="fa fa-gears"></i> Settings</a></li>

                                <li><a href=""><i class="fa fa-cubes"></i> Fields</a></li>
                                <li class="divider-tab"><a href=""><i class="fa fa-info-circle"></i> Placeholders</a></li>

                                <li><a href=""><i class="fa fa-file-text-o"></i> Design</a></li>
                                <li><a href=""><i class="fa fa-list-alt"></i> Reviews</a></li>
                                <li><a href=""><i class="fa fa-star-half-empty"></i> Ratings</a></li>


                            </ul>

                            <div id="tab-content-inside" class="uk-switcher uk-width-large-8-10 uk-width-medium-6-10">

                                <!-- Settings -->
                                <div id="review_settings_tab">

                                    <div class="uk-alert uk-alert-large" data-uk-alert="">
                                        <h3>Global Settings & Behavior</h3>
                                        <p>Changing the settings below results in global changes to the Review Widget looks & feels. </p>
                                    </div>

                                    <div class="uk-grid uk-grid-small">
                                        <div class="uk-width-1-1">

                                            <h3><i class="fa fa-cubes"></i> Function Settings</h3>
                                            <hr>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <div class="slider-container">
                                                            <input type="hidden" name="ps_review[settings][per_page_reviews]" id="per_page_reviews" value="<?=(@$ps_review['settings']['per_page_reviews'] == 1) ? 1 : 0;?>">
                                                            <p class="slider-label">Use <b>Per Page</b> Reviews <i class="fa fa-info-circle help-icon" data-uk-tooltip="" title="Reviews will be visible only on specific pages/posts where they were originally left. Useful if you have a website selling products."></i></p>
                                                            <div class="prs-slider-frame">
                                                                <span class="slider-button <?=(@$ps_review['settings']['per_page_reviews'] == 1) ? 'on' : '';?>" data-element="per_page_reviews"><?=(@$ps_review['settings']['per_page_reviews'] == 1) ? 'Yes' : 'No';?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <div class="slider-container">
                                                            <input type="hidden" name="ps_review[settings][natural_reviews]" id="natural_reviews" value="<?=(@$ps_review['settings']['natural_reviews'] == 1) ? 1 : 0;?>">
                                                            <p class="slider-label"><b>Natural</b> Reviews <i class="fa fa-info-circle help-icon" data-uk-tooltip="" title="All ratings from reviews will be calculated into assigned Schema(s) for current page/post."></i></p>
                                                            <div class="prs-slider-frame">
                                                                <span class="slider-button <?=(@$ps_review['settings']['natural_reviews'] == 1) ? 'on' : '';?>" data-element="natural_reviews"><?=(@$ps_review['settings']['natural_reviews'] == 1) ? 'Yes' : 'No';?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <div class="slider-container">
                                                            <!-- TODO functionality needs to be made for this ps_review[settings][prevent_multiple] -->
                                                            <input type="hidden" name="ps_review[settings][prevent_multiple]" id="prevent_multiple" value="<?=(@$ps_review['settings']['prevent_multiple'] == 1) ? 1 : 0;?>">
                                                            <p class="slider-label">Stop <b>Multiple</b> Reviews <i class="fa fa-info-circle help-icon" data-uk-tooltip="" title="Adds cookie tracking to your visitors to prevent adding more than one review per day."></i></p>
                                                            <div class="prs-slider-frame">
                                                                <span class="slider-button <?=(@$ps_review['settings']['prevent_multiple'] == 1) ? 'on' : '';?>" data-element="prevent_multiple"><?=(@$ps_review['settings']['prevent_multiple'] == 1) ? 'Yes' : 'No';?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <div class="slider-container">
                                                            <input type="hidden" name="ps_review[settings][reviews_approve]" id="reviews_approve" value="<?=(@$ps_review['settings']['reviews_approve'] == 1) ? 1 : 0;?>">
                                                            <p class="slider-label">Auto <b>Approve</b> Reviews <i class="fa fa-info-circle help-icon" data-uk-tooltip="" title="If you turn on this option, reviews will be automatically approved without the need for your interaction. If you're using ratings instead of reviews, please see the next option on the right."></i></p>
                                                            <div class="prs-slider-frame">
                                                                <span class="slider-button <?=(@$ps_review['settings']['reviews_approve'] == 1) ? 'on' : '';?>" data-element="reviews_approve"><?=(@$ps_review['settings']['reviews_approve'] == 1) ? 'Yes' : 'No';?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <div class="slider-container">
                                                            <input type="hidden" name="ps_review[settings][stars_approve]" id="stars_approve" value="<?=(@$ps_review['settings']['stars_approve'] == 1) ? 1 : 0;?>">
                                                            <p class="slider-label">Auto <b>Approve</b> Ratings <i class="fa fa-info-circle help-icon" data-uk-tooltip="" title="If you have Widget Ratings Mode activated globally, or for certain pages using a shortcode, you can turn on this option to automatically approve ratings."></i></p>
                                                            <div class="prs-slider-frame">
                                                                <span class="slider-button <?=(@$ps_review['settings']['stars_approve'] == 1) ? 'on' : '';?>" data-element="stars_approve"><?=(@$ps_review['settings']['stars_approve'] == 1) ? 'Yes' : 'No';?></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <h3><i class="fa fa-external-link"></i> Pop Up Settings</h3>
                                            <hr>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <div class="slider-container">
                                                            <input type="hidden" name="ps_review[settings][popup]" id="popup" value="<?=(@$ps_review['settings']['popup'] == 1) ? 1 : 0;?>">
                                                            <p class="slider-label">Button <b>Popup</b> Mode <i class="fa fa-info-circle help-icon" data-uk-tooltip="" title="Instead of showing Review Widget directly on page, this will make it appear as a popup when clicked on a button. Useful if you have limited space on your website."></i></p>
                                                            <div class="prs-slider-frame">
                                                                <span class="slider-button <?=(@$ps_review['settings']['popup'] == 1) ? 'on' : '';?>" data-element="popup"><?=(@$ps_review['settings']['popup'] == 1) ? 'Yes' : 'No';?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <div class="slider-container" style="<?=(@$ps_review['settings']['popup'] == 0) ? 'display:none;' : ''; ?>">
                                                            <input type="hidden" name="ps_review[settings][popup_text]" id="popup_text" value="<?=(@$ps_review['settings']['popup_text'] == 1) ? 1 : 0;?>">
                                                            <p class="slider-label">Use Text <b>Popup</b> Button <i class="fa fa-info-circle help-icon" data-uk-tooltip="" title="If you want to display a text link instead of a button to display the review widget popup, turn this on."></i></p>
                                                            <div class="prs-slider-frame">
                                                                <span class="slider-button <?=(@$ps_review['settings']['popup_text'] == 1) ? 'on' : '';?>" data-element="popup_text"><?=(@$ps_review['settings']['popup_text'] == 1) ? 'Yes' : 'No';?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <div class="slider-container" style="<?=(@$ps_review['settings']['popup'] == 0) ? 'display:none;' : ''; ?>">
                                                            <input type="hidden" name="ps_review[settings][exit_popup]" id="exit_popup" value="<?=(@$ps_review['settings']['exit_popup'] == 1) ? 1 : 0;?>">
                                                            <p class="slider-label">Exit <b>Popup</b> Mode <i class="fa fa-info-circle help-icon" data-uk-tooltip="" title="Stop visitors from leaving your website without leaving a review! This operation will be only available when Popup Mode is enabled."></i></p>
                                                            <div class="prs-slider-frame">
                                                                <span class="slider-button <?=(@$ps_review['settings']['exit_popup'] == 1) ? 'on' : '';?>" data-element="exit_popup"><?=(@$ps_review['settings']['exit_popup'] == 1) ? 'Yes' : 'No';?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-1">
                                                    <div class="uk-panel">
                                                        <label>Popup Button Text: <i class="fa fa-info-circle" data-uk-tooltip="" title="Changes the text of a button used to display review widget in popup mode."></i></label>
                                                        <input type="text" class="uk-input-custom text-left" value="<?=@$ps_review['details']['popup_button_title'];?>" name="ps_review[details][popup_button_title]" placeholder="eg. Submit"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <h3><i class="fa fa-gear"></i> Mode Settings</h3>
                                            <hr>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <div class="slider-container">
                                                            <input type="hidden" name="ps_review[settings][stars_only]" id="stars_only" value="<?=(@$ps_review['settings']['stars_only'] == 1) ? 1 : 0;?>">
                                                            <p class="slider-label">Widget <b>Ratings</b> Mode <i class="fa fa-info-circle help-icon" data-uk-tooltip="" title="Turns Review Widget into the Ratings Widget. Basically removes Submit Review button along with all other input fields except stars and instead adds ratings when stars are clicked."></i></p>
                                                            <div class="prs-slider-frame">
                                                                <span class="slider-button <?=(@$ps_review['settings']['stars_only'] == 1) ? 'on' : '';?>" data-element="stars_only"><?=(@$ps_review['settings']['stars_only'] == 1) ? 'Yes' : 'No';?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <div class="slider-container">
                                                            <input type="hidden" name="ps_review[settings][alpha_bg]" id="alpha_bg" value="<?=(@$ps_review['settings']['alpha_bg'] == 1) ? 1 : 0;?>">
                                                            <p class="slider-label">Alpha <b>Widget</b> Mode <i class="fa fa-info-circle help-icon" data-uk-tooltip="" title="If you want your widget to blend in with the rest of the background, use this option."></i></p>
                                                            <div class="prs-slider-frame">
                                                                <span class="slider-button <?=(@$ps_review['settings']['alpha_bg'] == 1) ? 'on' : '';?>" data-element="alpha_bg"><?=(@$ps_review['settings']['alpha_bg'] == 1) ? 'Yes' : 'No';?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <h3><i class="fa fa-envelope-o"></i> Message Settings</h3>
                                            <hr>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-1">
                                                    <div class="uk-panel">
                                                        <label>Thank You - Message: <i class="fa fa-info-circle" data-uk-tooltip="" title="Changes the message user receives after he leaves a review."></i></label>
                                                        <input type="text" class="uk-input-custom text-left" value="<?=@$ps_review['details']['thank_you'];?>" name="ps_review[details][thank_you]" placeholder="eg. Thank you for leaving a Review!"/>
                                                    </div>

                                                    <div class="uk-panel" style="margin-top: 20px;">
                                                        <label>No Reviews - Message: <i class="fa fa-info-circle" data-uk-tooltip="" title="Changes the message user sees when there are no reviews for current content."></i></label>
                                                        <input type="text" class="uk-input-custom text-left" value="<?=@$ps_review['details']['no_reviews_message'];?>" name="ps_review[details][no_reviews_message]" placeholder="eg. Nobody yet left a review. Be first?!"/>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>


                                </div>

                                <!-- Fields -->
                                <div>

                                    <div class="uk-alert uk-alert-large" data-uk-alert="">
                                        <h3>Review Widget – Fields</h3>
                                        <p>In this section you can choose which fields should be present / required on your Review Widget. You can also change the order of fields by dragging them around the list.</p>
                                    </div>

                                    <input type="hidden" name="ps_review[fields]" value="<?=(@$ps_review['fields'] != NULL) ? $ps_review['fields'] : ''?>"/>

                                    <ul class="uk-sortable fields" data-uk-sortable="{handleClass:'uk-sortable-handle'}">

                                        <li>
                                            <div class="uk-panel uk-panel-box" data-name="name"><i
                                                        class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
                                                Name

                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-switch" data-value="0"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-required" title="Require Field" data-value="0" style="display: none;"><i class="fa fa-ban"></i></button>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="uk-panel uk-panel-box" data-name="review"><i
                                                        class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
                                                Review

                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-switch" data-value="0"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-required" title="Require Field" data-value="0" style="display: none;"><i class="fa fa-ban"></i></button>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="uk-panel uk-panel-box" data-name="rating"><i
                                                        class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
                                                Rating
                                            </div>
                                        </li>
                                        <li>
                                            <div class="uk-panel uk-panel-box" data-name="email"><i
                                                        class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
                                                E-Mail Address

                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-switch" data-value="0"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-required" title="Require Field" data-value="0" style="display: none;"><i class="fa fa-ban"></i></button>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="uk-panel uk-panel-box" data-name="website"><i
                                                        class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
                                                Website
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-switch" data-value="0"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-required" title="Require Field" data-value="0" style="display: none;"><i class="fa fa-ban"></i></button>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="uk-panel uk-panel-box" data-name="title"><i
                                                        class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
                                                Title
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-switch" data-value="0"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-required" title="Require Field" data-value="0" style="display: none;"><i class="fa fa-ban"></i></button>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="uk-panel uk-panel-box" data-name="telephone"><i
                                                        class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
                                                Telephone
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-switch" data-value="0"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-required" title="Require Field" data-value="0" style="display: none;"><i class="fa fa-ban"></i></button>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="uk-panel uk-panel-box" data-name="location"><i
                                                        class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
                                                Location
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-switch" data-value="0"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-required" title="Require Field" data-value="0" style="display: none;"><i class="fa fa-ban"></i></button>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="uk-panel uk-panel-box" data-name="age"><i
                                                        class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right"></i>
                                                Age
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-switch" data-value="0"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="uk-float-right">
                                                    <button type="button" class="uk-button uk-button-mini uk-button-required" title="Require Field" data-value="0" style="display: none;"><i class="fa fa-ban"></i></button>
                                                </div>
                                            </div>
                                        </li>

                                    </ul>

                                </div>

                                <!-- Placeholders -->
                                <div class="ps_review_placeholders">

                                    <div class="uk-alert uk-alert-large" data-uk-alert="">
                                        <h3>Review Widget – Placeholders</h3>
                                        <p>Changing the fields below will allow you to set placeholders for fields when there is no text in them, ie. when they're empty.</p>
                                    </div>

                                    <div class="uk-panel">
                                        <label for="review_name_placeholder">Name:</label>
                                        <input type="text" class="uk-input-custom text-left" id="review_name_placeholder" data-name="name" value="eg. John" />
                                    </div>

                                    <div class="uk-panel">
                                        <label for="review_review_placeholder">Review:</label>
                                        <input type="text" class="uk-input-custom text-left" id="review_review_placeholder" data-name="review" value="eg. This is really a cool website!" name="ps_review[placeholders][review]" />
                                    </div>

                                    <div class="uk-panel">
                                        <label for="review_email_placeholder">E-mail:</label>
                                        <input type="text" class="uk-input-custom text-left" id="review_email_placeholder" data-name="email" value="eg. your@email.com" name="ps_review[placeholders][email]" />
                                    </div>

                                    <div class="uk-panel">
                                        <label for="review_website_placeholder">Website:</label>
                                        <input type="text" class="uk-input-custom text-left" id="review_website_placeholder" data-name="website" value="eg. http://www.website.com" name="ps_review[placeholders][website]" />
                                    </div>

                                    <div class="uk-panel">
                                        <label for="review_title_placeholder">Title:</label>
                                        <input type="text" class="uk-input-custom text-left" id="review_title_placeholder" data-name="title" value="eg. I like this product" name="ps_review[placeholders][title]" />
                                    </div>

                                    <div class="uk-panel">
                                        <label for="review_tel_placeholder">Telephone:</label>
                                        <input type="text" class="uk-input-custom text-left" id="review_tel_placeholder" data-name="telephone" value="eg. 1-800-500-6000" name="ps_review[placeholders][telephone]" />
                                    </div>

                                    <div class="uk-panel">
                                        <label for="review_loc_placeholder">Location :</label>
                                        <input type="text" class="uk-input-custom text-left" id="review_loc_placeholder" data-name="location" value="eg. Los Angeles" name="ps_review[placeholders][location]" />
                                    </div>

                                    <div class="uk-panel">
                                        <label for="review_age_placeholder">Age:</label>
                                        <input type="text" class="uk-input-custom text-left" id="review_age_placeholder" data-name="age" value="eg. 35" name="ps_review[placeholders][age]" />
                                    </div>

                                </div>

                                <!-- Design -->
                                <div>
                                    <div class="uk-alert uk-alert-large" data-uk-alert="">
                                        <h3>Review Widget – Text & Colors</h3>
                                        <p>Change the fields below to alter how your Review Widget will look like. You'll see all the changes you make in the preview area on the right.</p>
                                    </div>

                                    <div class="uk-grid uk-grid-small review_design_tab">
                                        <div class="uk-width-1-1">

                                            <h3><i class="fa fa-file"></i> Text Design</h3>
                                            <hr>

                                            <div class="uk-grid">
                                                <div class="uk-width-2-3">
                                                    <div class="uk-panel">
                                                        <label>Heading: <i class="fa fa-info-circle" data-uk-tooltip="" title="Changes the main heading of the Review Widget. HTML allowed."></i></label>
                                                        <input type="text" class="uk-input-custom text-left" value="<?=@$ps_review['details']['title'];?>" name="ps_review[details][title]" placeholder="eg. Leave a Review"/>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <label for="heading-size">Heading Size:</label>
                                                        <input type="range" value="<?=@$ps_review['font_size']['heading']?>" min="10" max="40" name="ps_review[font_size][heading]" id="heading-size" class="input-range"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="uk-grid">
                                                <div class="uk-width-2-3">
                                                    <div class="uk-panel">
                                                        <label>Subheading: <i class="fa fa-info-circle" data-uk-tooltip="" title="Changes the subheading of the Review Widget. HTML allowed."></i></label>
                                                        <textarea rows="1" class="uk-input-custom text-left" name="ps_review[details][text]" placeholder="eg. Describe what should your visitors do..."><?=@$ps_review['details']['text'];?></textarea>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <label for="subheading-size">Subheading Size:</label>
                                                        <input type="range" value="<?=@$ps_review['font_size']['subheading']?>" min="8" max="20" name="ps_review[font_size][subheading]" id="subheading-size" class="input-range"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="uk-grid">
                                                <div class="uk-width-2-4">
                                                    <div class="uk-panel">
                                                        <label>Submit Button Text: <i class="fa fa-info-circle" data-uk-tooltip="" title="Changes the text of a button used to submit reviews."></i></label>
                                                        <input type="text" class="uk-input-custom text-left" value="<?=@$ps_review['details']['button_title'];?>" name="ps_review[details][button_title]" placeholder="eg. Submit"/>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-4">
                                                    <div class="uk-panel" style="text-align: center;margin-top: 20px;">
                                                        <input value="<?=(@$ps_review['colors']['button_text'] != NULL) ? $ps_review['colors']['button_text'] : '#656565';?>" type="color" id="color-picker-7" name="ps_review[colors][button_text]" class="color-picker"/>
                                                        <label for="color-picker-7">Text</label>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-4">
                                                    <div class="uk-panel" style="text-align: center;margin-top: 20px;">
                                                        <input value="<?=(@$ps_review['colors']['button_background'] != NULL) ? $ps_review['colors']['button_background'] : '#eaeaea';?>" type="color" id="color-picker-6" name="ps_review[colors][button_background]" class="color-picker"/>
                                                        <label for="color-picker-6">Background</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <h3><i class="fa fa-paint-brush"></i> Field & Widget Design</h3>
                                            <hr>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-2">
                                                    <div class="uk-panel">
                                                        <label for="widget-theme">Widget Theme: <i class="fa fa-info-circle" data-uk-tooltip="" title="Allows you to use different widget themes to match your website design."></i></label>
                                                        <select name="ps_review[settings][widget_theme]" id="widget-theme" class="uk-input-custom">
                                                            <option <?=(@$ps_review['settings']['widget_theme'] == 0) ? 'selected' : '';?> value="0">Default</option>
                                                            <option <?=(@$ps_review['settings']['widget_theme'] == 1) ? 'selected' : '';?> value="1">Flat</option>
                                                            <option <?=(@$ps_review['settings']['widget_theme'] == 2) ? 'selected' : '';?> value="2">Minimal</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-2">
                                                    <div class="uk-panel">
                                                        <label for="widget-width">Widget Width: <i class="fa fa-info-circle" data-uk-tooltip="" title="Allows you to switch between fixed width & automatic full width for Review Widget."></i></label>
                                                        <select name="ps_review[settings][widget_width]" id="widget-width" class="uk-input-custom">
                                                            <option <?=(@$ps_review['settings']['widget_width'] == 0) ? 'selected' : '';?> value="0">Fixed</option>
                                                            <option <?=(@$ps_review['settings']['widget_width'] == 1) ? 'selected' : '';?> value="1">Auto</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-2">
                                                    <div class="uk-panel">
                                                        <label for="alignment">Content Alignment: <i class="fa fa-info-circle" data-uk-tooltip="" title="Allows you to use different alignment for content inside of Review Widget."></i></label>
                                                        <select name="ps_review[settings][alignment]" id="alignment" class="uk-input-custom">
                                                            <option <?=(@$ps_review['settings']['alignment'] == 'left') ? 'selected' : '';?> value="left">Left</option>
                                                            <option <?=(@$ps_review['settings']['alignment'] == 'center') ? 'selected' : '';?> value="center">Center</option>
                                                            <option <?=(@$ps_review['settings']['alignment'] == 'right') ? 'selected' : '';?> value="right">Right</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-2">
                                                    <div class="uk-panel">
                                                        <label for="form-labels">Label Rendering Mode: <i class="fa fa-info-circle" data-uk-tooltip="" title="Allows you to change how the form labels will look like on your Review Widget."></i></label>
                                                        <select name="ps_review[settings][form_labels]" id="form-labels" class="uk-input-custom">
                                                            <option <?=(@$ps_review['settings']['form_labels'] == 0) ? 'selected' : '';?> value="0">Above the text boxes</option>
                                                            <option <?=(@$ps_review['settings']['form_labels'] == 1) ? 'selected' : '';?> value="1">Next to text boxes</option>
                                                            <option <?=(@$ps_review['settings']['form_labels'] == 2) ? 'selected' : '';?> value="2">As placeholders</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-4">
                                                    <div class="uk-panel">
                                                        <label for="widget-padding">Widget Padding:</label>
                                                        <input type="range" value="<?=@$ps_review['padding']['widget']?>" min="1" max="50" name="ps_review[padding][widget]" id="widget-padding" class="input-range"/>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-4">
                                                    <div class="uk-panel">
                                                        <label for="input-padding">Field Padding:</label>
                                                        <input type="range" value="<?=@$ps_review['padding']['input']?>" min="1" max="50" name="ps_review[padding][input]" id="input-padding" class="input-range"/>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-4">
                                                    <div class="uk-panel">
                                                        <label for="input-size">Field Text Size:</label>
                                                        <input type="range" value="<?=@$ps_review['font_size']['input']?>" min="8" max="25" name="ps_review[font_size][input]" id="input-size" class="input-range"/>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-4">
                                                    <div class="uk-panel">
                                                        <label for="label-size">Label Text Size:</label>
                                                        <input type="range" value="<?=@$ps_review['font_size']['label']?>" min="8" max="25" name="ps_review[font_size][label]" id="label-size" class="input-range"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-1">
                                                    <div class="uk-panel">
                                                        <label for="stars-size">Stars Size:</label>
                                                        <input type="range" value="<?=@$ps_review['font_size']['stars']?>" min="14" max="50" name="ps_review[font_size][stars]" id="stars-size" class="input-range"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <h3><i class="fa fa-table"></i> Color Design</h3>
                                            <hr>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <input value="<?=(@$ps_review['colors']['background'] != NULL) ? $ps_review['colors']['background'] : '#ffffff';?>" type="color" id="color-picker-1" name="ps_review[colors][background]" class="color-picker"/>
                                                        <label for="color-picker-1">Background</label>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <input value="<?=(@$ps_review['colors']['border'] != NULL) ? $ps_review['colors']['border'] : '#bbbbbb';?>" type="color" id="color-picker-2" name="ps_review[colors][border]" class="color-picker"/>
                                                        <label for="color-picker-2">Border</label>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <input value="<?=(@$ps_review['colors']['text'] != NULL) ? $ps_review['colors']['text'] : '#444444';?>" type="color" id="color-picker-3" name="ps_review[colors][text]" class="color-picker"/>
                                                        <label for="color-picker-3">Text</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="uk-grid">
                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <input value="<?=(@$ps_review['colors']['input_background'] != NULL) ? $ps_review['colors']['input_background'] : '#ffffff';?>" type="color" id="color-picker-4" name="ps_review[colors][input_background]" class="color-picker"/>
                                                        <label for="color-picker-4">Input Background</label>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <input value="<?=(@$ps_review['colors']['stars'] != NULL) ? $ps_review['colors']['stars'] : '#000012';?>" type="color" id="color-picker-8" name="ps_review[colors][stars]" class="color-picker"/>
                                                        <label for="color-picker-8">Stars</label>
                                                    </div>
                                                </div>

                                                <div class="uk-width-1-3">
                                                    <div class="uk-panel">
                                                        <input value="<?=(@$ps_review['colors']['input_text'] != NULL) ? $ps_review['colors']['input_text'] : '#000012';?>" type="color" id="color-picker-5" name="ps_review[colors][input_text]" class="color-picker"/>
                                                        <label for="color-picker-5">Input Text</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <!-- Reviews -->
                                <div class="review_display_tab">

                                    <div class="uk-alert uk-alert-large" data-uk-alert="">
                                        <h3>Display Reviews Widget – Text & Colors</h3>
                                        <p>Change the fields below to alter how your Display Reviews Widget will look like. You'll see all the changes you make in the preview area on the right.</p>
                                    </div>

                                    <h3><i class="fa fa-file"></i> Text Design</h3>
                                    <hr>

                                    <div class="uk-grid">
                                        <div class="uk-width-2-3">
                                            <div class="uk-panel">
                                                <label>Heading: <i class="fa fa-info-circle" data-uk-tooltip="" title="Change this field if you want Heading above your ratings, if you leave this field emtpy it will not be shown. HTML allowed."></i></label>
                                                <input type="text" class="uk-input-custom text-left" value="<?=@$ps_review['details']['display_reviews_heading'];?>" name="ps_review[details][display_reviews_heading]" placeholder="eg. Check out our Reviews!"/>
                                            </div>
                                        </div>
                                        <div class="uk-width-1-3">
                                            <div class="uk-panel">
                                                <label for="display-heading-size">Heading Size:</label>
                                                <input type="range" value="<?=@$ps_review['details']['heading_size']?>" min="10" max="40" name="ps_review[details][heading_size]" id="display-heading-size" class="input-range"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-grid review_display_tab">
                                        <div class="uk-width-2-3">
                                            <div class="uk-panel">
                                                <label>Subheading: <i class="fa fa-info-circle" data-uk-tooltip="" title="Changes the subheading of the Display Reviews Widget. Use {calc} & {sum} inside of this field to display calculated ratings & number of reviews / ratings. HTML allowed."></i></label>
                                                <input type="text" class="uk-input-custom text-left" value="<?=@$ps_review['details']['display_reviews_text'];?>" name="ps_review[details][display_reviews_text]" placeholder="eg. {calc} Rating From {sum} Reviews."/>
                                            </div>
                                        </div>
                                        <div class="uk-width-1-3">
                                            <div class="uk-panel">
                                                <label for="display-subheading-size">Subheading Size:</label>
                                                <input type="range" value="<?=@$ps_review['details']['subheading_size']?>" min="8" max="20" name="ps_review[details][subheading_size]" id="display-subheading-size" class="input-range"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-grid review_display_tab">
                                        <div class="uk-width-1-1">
                                            <div class="uk-panel">
                                                <label for="stars-size-display">Star Size:</label>
                                                <input type="range" value="<?=@$ps_review['details']['display_star_size']?>" min="10" max="40" name="ps_review[details][display_star_size]" id="stars-size-display" class="input-range"/>
                                            </div>
                                        </div>
                                    </div>

                                    <h3><i class="fa fa-table"></i> Color Design</h3>
                                    <hr>

                                    <div class="uk-grid uk-grid-small">
                                        <div class="uk-width-1-2">

                                            <div class="uk-panel">
                                                <input value="<?=(@$ps_review['colors_display']['background'] != NULL) ? $ps_review['colors_display']['background'] : '#fbfbfb';?>" type="color" id="color-picker-51" name="ps_review[colors_display][background]" class="color-picker"/>
                                                <label for="color-picker-1">Background</label>
                                            </div>

                                            <div class="uk-panel">
                                                <input value="<?=(@$ps_review['colors_display']['border'] != NULL) ? $ps_review['colors_display']['border'] : '#fbfbfb';?>" type="color" id="color-picker-52" name="ps_review[colors_display][border]" class="color-picker"/>
                                                <label for="color-picker-2">Border</label>
                                            </div>

                                        </div>
                                        <div class="uk-width-1-2">

                                            <div class="uk-panel">
                                                <input value="<?=(@$ps_review['colors_display']['text'] != NULL) ? $ps_review['colors_display']['text'] : '#626a74';?>" type="color" id="color-picker-53" name="ps_review[colors_display][text]" class="color-picker"/>
                                                <label for="color-picker-3">Text</label>
                                            </div>

                                            <div class="uk-panel">
                                                <input value="<?=(@$ps_review['colors_display']['stars'] != NULL) ? $ps_review['colors_display']['stars'] : '#626a74';?>" type="color" id="color-picker-58" name="ps_review[colors_display][stars]" class="color-picker"/>
                                                <label for="color-picker-8">Stars</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- Ratings -->
                                <div class="review_rating_tab">

                                    <div class="uk-alert uk-alert-large" data-uk-alert="">
                                        <h3>Widget Ratings Mode – Text & Colors</h3>
                                        <p>Change the fields below to alter how your Review Widget will look like when you use the "Widget <b>Ratings</b> Mode". You'll see all the changes you make in the preview area on the right.</p>
                                    </div>

                                    <h3><i class="fa fa-file"></i> Text Design</h3>
                                    <hr>

                                    <div class="uk-grid">
                                        <div class="uk-width-2-3">
                                            <div class="uk-panel">
                                                <label>Heading: <i class="fa fa-info-circle" data-uk-tooltip="" title="Changes the main heading of the Review Widget when Widget Ratings Mode is turned on. Use {num} inside of this field to display percentage or ratings. HTML allowed."></i></label>
                                                <input type="text" class="uk-input-custom text-left" value="<?=@$ps_review['details']['rating_text'];?>" name="ps_review[details][rating_text]" placeholder="eg. {num} of users found this article interesting"/>
                                            </div>
                                        </div>

                                        <div class="uk-width-1-3">


                                            <div class="uk-panel">
                                                <label for="rating-heading-size">Heading Size:</label>
                                                <input type="range" value="<?=@$ps_review['details']['rating_heading_size']?>" min="10" max="40" name="ps_review[details][rating_heading_size]" id="rating-heading-size" class="input-range"/>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="uk-grid">
                                        <div class="uk-width-2-3">
                                            <div class="uk-panel">
                                                <label>Instruction Text: <i class="fa fa-info-circle" data-uk-tooltip="" title="Changes the instruction text that tells the user how to use the ratings widget."></i></label>
                                                <input type="text" class="uk-input-custom text-left" value="<?=@$ps_review['details']['rating_info'];?>" name="ps_review[details][rating_info]" placeholder="eg. Click a star to add your rating"/>
                                            </div>
                                        </div>

                                        <div class="uk-width-1-3">


                                            <div class="uk-panel">
                                                <label for="rating-instruction-size">Instruction Size:</label>
                                                <input type="range" value="<?=@$ps_review['details']['rating_instruction_size']?>" min="8" max="20" name="ps_review[details][rating_instruction_size]" id="rating-heading-size" class="input-range"/>
                                            </div>


                                        </div>
                                    </div>


                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            <div class="uk-panel">
                                                <label>Thank You - Message: <i class="fa fa-info-circle" data-uk-tooltip="" title="Changes the message user sees when there are no ratings for current content."></i></label>
                                                <input type="text" class="uk-input-custom text-left" value="<?=@$ps_review['details']['rating_thank_you'];?>" name="ps_review[details][rating_thank_you]" placeholder="eg. Thank you for leaving a rating!"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            <div class="uk-panel">
                                                <label>No Ratings - Message: <i class="fa fa-info-circle" data-uk-tooltip="" title="Changes the message user sees when there are no ratings for current content."></i></label>
                                                <input type="text" class="uk-input-custom text-left" value="<?=@$ps_review['details']['no_ratings_message'];?>" name="ps_review[details][no_ratings_message]" placeholder="eg. Nobody yet left a rating. Be first?!"/>
                                            </div>
                                        </div>
                                    </div>

                                    <h3><i class="fa fa-table"></i> Color Design</h3>
                                    <hr>

                                    <div class="uk-grid uk-grid-small">
                                        <div class="uk-width-1-2">
                                            <div class="uk-panel">
                                                <input value="<?=(@$ps_review['colors']['rating_heading'] != NULL) ? $ps_review['colors']['rating_heading'] : '#434440';?>" type="color" id="color-picker-11" name="ps_review[colors][rating_heading]" class="color-picker"/>
                                                <label for="color-picker-11">Heading</label>
                                            </div>
                                        </div>
                                        <div class="uk-width-1-2">
                                            <div class="uk-panel">
                                                <input value="<?=(@$ps_review['colors']['rating_info'] != NULL) ? $ps_review['colors']['rating_info'] : '#3a3a3a70';?>" type="color" id="color-picker-12" name="ps_review[colors][rating_info]" class="color-picker"/>
                                                <label for="color-picker-12">Information Text</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

					</div>
					<div class="uk-width-large-5-10 uk-width-medium-5-10">
						<div class="form-container" id="preview-area">

							<!-- Preview Form -->
							<div class="review-widget">

                                <div class="review-widget-title">
									<h2>Leave a Review</h2>
								</div>

                                <div class="review-widget-text">
									Please be kind and leave us a review!
								</div>

                                <div class="review-widget-stars-ratings-sum">
                                    <b>100%</b> Please be kind and leave us a review!
                                </div>

								<div class="review-widget-block-container">

								</div>

								<button class="review-widget-button" type="button">Submit Review</button>

                                <span class="review-widget-stars-ratings-info">
                                    Click a star to add your rating
                                </span>

							</div>


                            <!-- Preview Form -->
                            <div class="review-display" style="display: none">
                                <div class="prs-review-display-heading"><h2></h2></div>
                                <div class="prs-review-container-aggregate" style="width: auto;">
                                    <b>5</b>/<b>5</b> Rating From <b>13</b> Reviews.
                                </div>

                                <div class="prs-review-container" style="width: auto;">
                                    <div class="prs-review-spacer">
                                        <i class="fa fa-quote-right"></i>
                                    </div>
                                    <div class="prs-review-stars">
                                        <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i>
                                        <span class="prs-review-date"> on 02, Apr 2017</span>
                                    </div>
                                    <div class="prs-review-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc tristique sollicitudin ligula, ut elementum ipsum tempor at. Integer laoreet dignissim eros, eu tincidunt leo. Ut finibus lectus quis elit cursus pulvinar. Fusce ornare, enim non convallis tincidunt, diam neque cursus tellus, sit amet fringilla ipsum metus eu sapien.
                                    </div>
                                    <div class="prs-review-author">
                                        <b>Michael</b>
                                        <br>
                                    </div>
                                </div>
                            </div>
						</div>
                        <div class="uk-alert uk-alert-warning review_widget_rating_mode_alert" style="display: none">
                            To use the "Widget Ratings Mode" either set the "Widget Ratings Mode" to Yes in Settings, or when using Review Widget shortcode use the attribute <kbd>stars_only=1</kbd>.
                        </div>
					</div>
				</div>

				<button class="uk-button uk-button-success uk-button-save-review-design"><i class="fa fa-save"></i> Save Changes</button>

			</form>
		</div>

        <!-- Shortcodes -->
        <div>
            <div class="uk-grid uk-grid-small">
                <div class="uk-width-1-2">
                    <kbd class="review-shortcodes">[prs_reviews_widget]</kbd>

                    <p class="review-shortcodes-info">
                        <i class="fa fa-info-circle"></i> Used to display the Review Widget where users can leave their reviews for your website (or the current page).
                    </p>

                    <span class="review-shortcodes-options-label">Options:</span>

                    <ul class="review-shortcodes-options">
                        <li>alpha_mode=<kbd>1</kbd> (displays the Review Widget in transparent background mode)</li>
                        <li>popup_mode=<kbd>1</kbd> (displays the Review Widget in button Popup Mode)</li>
                        <li>popup_text=<kbd>1</kbd> (displays the popup button as a text link)</li>
                        <li>exit_popup=<kbd>1</kbd> (displays widget when user is trying to exit page. Only works with popup_mode enabled )</li>
                        <li>stars_only=<kbd>1</kbd> (displays the Review Widget in Stars Only Mode)</li>
                    </ul>

                    <span class="review-shortcodes-example-label">Example:</span>

                    <kbd class="review-shortcodes-example">[prs_reviews_widget popup_mode=1 popup_text=1 stars_only=1 alpha_mode=1 exit_popup=1]</kbd>

                </div>
                <div class="uk-width-1-2">
                    <kbd class="review-shortcodes">[prs_reviews]</kbd>

                    <p class="review-shortcodes-info">
                        <i class="fa fa-info-circle"></i> Used to display reviews left by users on specific page or post.
                    </p>

                    <span class="review-shortcodes-options-label">Options:</span>

                    <ul class="review-shortcodes-options">
                        <li>aggregate_rating=<kbd>1</kbd> (shows average review rating and total left reviews)</li>
                        <li>random_reviews=<kbd>1</kbd> (displays random reviews)</li>
                        <li>limit_reviews=<kbd>1</kbd> (limits the number of reviews displayed)</li>
                        <li>limit_reviews_number=<kbd>5</kbd> (number of reviews to be displayed if <b>limit_reviews</b> is activated)</li>
                    </ul>

                    <span class="review-shortcodes-example-label">Example:</span>

                    <kbd class="review-shortcodes-example">[prs_reviews aggregate_rating=1 random_reviews=1 limit_reviews=1 limit_reviews_number=5]</kbd>

                </div>
            </div>
        </div>

	</div>

	<!-- Edit Review -->
	<div id="edit_review" class="uk-modal">
		<form class="edit_review_submit">

			<input type="hidden" class="review-action" name="action" value="prs_editReview"/>
			<input type="hidden" class="review-id" id="review-id" name="id" value="0"/>
			<input type="hidden" class="review-approved" id="review-approved" name="approved" value="1"/>
			<?php wp_nonce_field( 'prs_editReview', '_wpnonce' ); ?>

			<div class="uk-modal-dialog">

				<button type="button" class="uk-modal-close uk-close"></button>
				<div class="uk-modal-header">
					<h2><i class="fa fa-edit"></i> Edit Review</h2>
				</div>

				<div class="uk-grid uk-grid-small">
					<div class="uk-width-1-2">

						<div class="uk-form-row">
							<label class="uk-form-label" for="review-name">Name:</label>
							<div class="uk-form-controls">
								<input type="text" id="review-name" name="name" placeholder="eg. John Doe" data-placeholder="eg. John Doe" data-alt-placeholder="Your Name" required>
							</div>
						</div>

						<div class="uk-form-row">
							<label class="uk-form-label" for="review-review">Review:</label>
							<div class="uk-form-controls">
								<textarea rows="7" id="review-review" name="review" placeholder="eg. This is a really nice product" data-placeholder="eg. This is a really nice product" data-alt-placeholder="Your Review" required></textarea>
							</div>
						</div>

						<div class="uk-form-row">
							<label class="uk-form-label" for="review-email">E-Mail Address:</label>
							<div class="uk-form-controls">
								<input type="text" id="review-email" name="email" placeholder="eg. johndoe@email.com" data-placeholder="eg. johndoe@email.com" data-alt-placeholder="E-Mail Address">
							</div>
						</div>

						<div class="uk-form-row">
							<label class="uk-form-label" for="review-website">Website:</label>
							<div class="uk-form-controls">
								<input type="text" id="review-website" name="website" placeholder="eg. http://website.com" data-placeholder="eg. http://website.com" data-alt-placeholder="Your Website">
							</div>
						</div>

					</div>
					<div class="uk-width-1-2">

                        <div class="uk-form-row">
                            <label class="uk-form-label" for="review-title">Title:</label>
                            <div class="uk-form-controls">
                                <input type="text" id="review-title" name="title" placeholder="eg. I like this product" data-placeholder="eg. I like this product" data-alt-placeholder="Your Title">
                            </div>
                        </div>

						<div class="uk-form-row">
							<label class="uk-form-label" for="review-telephone">Telephone:</label>
							<div class="uk-form-controls">
								<input type="text" id="review-telephone" name="telephone" placeholder="eg. +1 800 500 4025" data-placeholder="eg. +1 800 500 4025" data-alt-placeholder="Your Phone Number">
							</div>
						</div>

						<div class="uk-form-row">
							<label class="uk-form-label" for="review-location">Location:</label>
							<div class="uk-form-controls">
								<input type="text" id="review-location" name="location" placeholder="eg. New York" data-placeholder="eg. New York" data-alt-placeholder="Your Location">
							</div>
						</div>

						<div class="uk-form-row">
							<label class="uk-form-label" for="review-age">Age:</label>
							<div class="uk-form-controls">
								<input type="text" id="review-age" name="age" placeholder="eg. 32" data-placeholder="eg. 32" data-alt-placeholder="Your Age">
							</div>
						</div>

						<div class="uk-form-row">
							<label class="uk-form-label" for="review-rating">Rating:</label>
							<div class="uk-form-controls">
								<input type="text" id="review-rating" name="rating" placeholder="eg. 1 - 5" required>
							</div>
						</div>

                        <div class="uk-form-row">
                            <label class="uk-form-label" for="review-date">Review Date: <small class="uk-text-muted">optional</small></label>
                            <div class="uk-form-controls">
                                <input type="text" id="review-date" name="date" data-uk-datepicker="{format:'YYYY-MM-DD'}">
                            </div>
                        </div>

					</div>
				</div>

				<div class="uk-modal-footer uk-text-right">
					<button type="submit" class="uk-button uk-button-primary"><i class="fa fa-edit"></i> Save Changes</button>
					<button type="button" class="uk-button uk-modal-close">Cancel</button>
				</div>

			</div>

		</form>
	</div>

</div> <!-- .wrap -->

<!-- Tutorials -->
<div id="tutorials" class="uk-modal">
    <div class="uk-modal-dialog uk-modal-dialog-large">
        <button type="button" class="uk-modal-close uk-close"></button>
        <div class="uk-modal-header">
            <h2><i class="fa fa-info-circle"></i> Tutorials</h2>
        </div>
        <div class="uk-modal-body">

        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="uk-button uk-modal-close">Close</button>
        </div>
    </div>
</div>

<!-- Available Pages -->
<div id="availablePagesModal" class="uk-modal">
    <div class="uk-modal-dialog">
        <button type="button" class="uk-modal-close uk-close"></button>
        <div class="uk-modal-header">
            <h2><i class="fa fa-file"></i> Pages/Posts</h2>
        </div>
        <div class="uk-modal-body">
            <input type="hidden" id="selectedReviews" name="selectedReviews" value="">
            <table class="wp-list-table widefat fixed striped postsTable2" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th width="70">Action</th>
                    <th>Title</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="4" class="uk-text-center"><i class="fa fa-spin fa-refresh"></i> Loading</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th width="70">Action</th>
                    <th>Title</th>
                    <th>Date</th>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="uk-button uk-modal-close">Close</button>
        </div>
    </div>
</div>

<!-- Available Pages for Cloning -->
<div id="availablePagesCloneModal" class="uk-modal">
    <div class="uk-modal-dialog">
        <button type="button" class="uk-modal-close uk-close"></button>
        <div class="uk-modal-header">
            <h2><i class="fa fa-file"></i> Pages/Posts</h2>
        </div>
        <div class="uk-modal-body">
            <input type="hidden" id="selectedReviewId" name="selectedReviewId" value="">
            <table class="wp-list-table widefat fixed striped postsCloneTable" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td class="check-column"><input class="select-posts-all" type="checkbox"></td>
                    <th>Title</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="4" class="uk-text-center"><i class="fa fa-spin fa-refresh"></i> Loading</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td class="check-column"><input class="select-posts-all" type="checkbox"></td>
                    <th>Title</th>
                    <th>Date</th>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="uk-button uk-button-primary" id="cloneReview"><i class="fa fa-clone"></i> Clone</button>
            <button type="button" class="uk-button uk-modal-close">Close</button>
        </div>
    </div>
</div>

<div class="tut_holder hide">
    <h2 style="margin-top: 20px"></h2>
    <iframe width="100%" height="480" frameborder="0" allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen"
            src="">
    </iframe>
</div>
<!-- Tutorials -->
