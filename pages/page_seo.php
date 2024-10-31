<!-- HTML STARTS HERE -->
<script>
    var home_url = '<?php echo get_home_url(); ?>';
</script>
<style>
    .fb_post_preview_holder {
        box-shadow: 0 0 1px 1px rgba(0, 0, 0, .15);
    }
    .fb_post_preview_holder .fb_post_preview_img{
        overflow: hidden;
        height: 245px;
        border-color: #e9ebee #e9ebee #d1d1d1;
        width: 100%;
        position: relative;
        border-bottom: 1px solid #e0e0e0;
    }
    .fb_post_preview_holder .fb_post_preview_img a img {
        position: absolute;
        top: -1000%;
        bottom: -1000%;
        left: -1000%;
        right: -1000%;
        margin: auto;
        width: 101%;
    }
    .fb_post_preview_holder .fb_post_preview_body {
        border: 1px solid #e9ebee;
        height: auto;
        margin: -1px 0;
        max-height: 100px;
        padding: 10px 12px;
    }
    .fb_post_preview_holder .fb_post_preview_body .fb_post_preview_title {
        font-size: 18px;
        font-weight: 500;
        line-height: 22px;
        margin-bottom: 5px;
        max-height: 110px;
        overflow: hidden;
        word-wrap: break-word;
        font-family: Georgia, serif;
        letter-spacing: normal;
    }

    .fb_post_preview_holder .fb_post_preview_body .fb_post_preview_desc {
        font-family: Helvetica, Arial, sans-serif;
        line-height: 16px;
        max-height: 80px;
        font-size: 12px;
    }

    .fb_post_preview_holder .fb_post_preview_body .fb_post_preview_host {
        font-size: 11px;
        line-height: 11px;
        text-transform: uppercase;
        color: #90949c;
        padding-top: 9px;
    }

    .tw_post_preview_holder {
        border-radius: .42857em;
        border-width: 1px;
        border-style: solid;
        border-color: #E1E8ED;
        box-sizing: border-box;
        color: inherit!important;
        overflow: hidden;
        background: white;
    }
    .tw_post_preview_holder .tw_post_preview_img div {
        top: -1px;
        left: -1px;
        bottom: -1px;
        right: -1px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
    }

    .tw_post_preview_holder .tw_post_preview_img {
        width: 8.81667em;
        float: left;
        border-right-width: 1px;
        background-color: #E1E8ED;
        border-style: solid;
        border-color: inherit;
        border-width: 0;
        min-height: 110px;
    }
    .tw_post_preview_holder .tw_post_preview_img a img {
        height: 110px;
        object-fit: cover;
    }
    .tw_post_preview_holder .tw_post_preview_body {
        width: calc(100% - 8.81667em - 2px);
        float: left;
        padding: .75em;
        box-sizing: border-box;
        text-decoration: none;
    }
    .tw_post_preview_holder .tw_post_preview_body .tw_post_preview_title {
        max-height: 29px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 1em;
        margin: 0 0 .15em;
        font-weight: bold;
    }

    .tw_post_preview_holder .tw_post_preview_body .tw_post_preview_host {
        text-transform: lowercase;
        color: #8899A6;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<div class="wrap prs" style="max-width: 1200px;margin-left: auto;margin-right: auto;">

	<h2 class="logo-title">
		<img class="logo-image" src="<?= PRS_URL; ?>/assets/img/logo.png"/>
		Project Supremacy - SEO Settings
		-
		<small class="hand">Set up how your website behaves...</small>

        <button type="button" class="uk-button uk-button-primary uk-button-open-shortcodes"><i class="fa fa-code"></i> View Shortcodes</button>
	</h2>

	<p class="logo-paragraph">
		<b><i class="fa fa-question-circle"></i> What should you do here?</b> From here you can apply all the settings
		that will be applied globally for your website's SEO.
	</p>

	<ul class="uk-tab uk-tab-big" data-uk-tab="{connect:'#tab-content'}">
		<li class="uk-active"><a href=""><i class="fa fa-gears"></i> General</a></li>
		<li><a href=""><i class="fa fa-refresh"></i> Redirects</a></li>
		<li><a href=""><i class="fa fa-paste"></i> Post Types</a></li>
		<li><a href=""><i class="fa fa-plug"></i> Taxonomies</a></li>
		<li><a href=""><i class="fa fa-asterisk"></i> Misc</a></li>
        <li><a href=""><i class="fa fa-facebook"></i> Open Graph</a></li>
        <li><a href=""><i class="fa fa-google"></i> Webmaster</a></li>
        <li><a href=""><i class="fa fa-file-code-o"></i> Scripts</a></li>
	</ul>

	<div id="tab-content" class="uk-switcher">
		<!-- Homepage -->
		<div>
			<div class="uk-block uk-block-muted">

				<form class="save-general">

					<input type="hidden" name="action" value="prs_save_general"/>
                    <?php wp_nonce_field( 'prs_save_general', '_wpnonce' ); ?>
					<div class="uk-container-normal">

						<div class="uk-grid uk-grid-match" data-uk-grid-margin="">

                            <!-- Title Separators -->
                            <div class="uk-width-medium-1-2 uk-row-first">
								<h2><i class="fa fa-chain"></i> Title Separator</h2>

								<fieldset class="titleSeparators" id="separator" data-value="<?=get_option('ps_seo_title_separator');?>">
									<input type="radio" class="radio" id="separator-sc-dash"
									       name="ps_seo_title_separator"
									       value="-">
									<label class="radio" for="separator-sc-dash">-</label>

									<input type="radio" class="radio" id="separator-sc-ndash"
									       name="ps_seo_title_separator"
									       value="–">
									<label class="radio" for="separator-sc-ndash">–</label>

									<input type="radio" class="radio" id="separator-sc-mdash"
									       name="ps_seo_title_separator"
									       value="—">
									<label class="radio" for="separator-sc-mdash">—</label>

									<input type="radio" class="radio" id="separator-sc-middot"
									       name="ps_seo_title_separator"
									       value="·">
									<label class="radio" for="separator-sc-middot">·</label>

									<input type="radio" class="radio" id="separator-sc-bull"
									       name="ps_seo_title_separator"
									       value="•">
									<label class="radio" for="separator-sc-bull">•</label>

									<input type="radio" class="radio" id="separator-sc-star"
									       name="ps_seo_title_separator"
									       value="*">
									<label class="radio" for="separator-sc-star">*</label>

									<input type="radio" class="radio" id="separator-sc-smstar"
									       name="ps_seo_title_separator"
									       value="⋆">
									<label class="radio" for="separator-sc-smstar">⋆</label>

									<input type="radio" class="radio" id="separator-sc-pipe"
									       name="ps_seo_title_separator"
									       value="|">
									<label class="radio" for="separator-sc-pipe">|</label>

									<input type="radio" class="radio" id="separator-sc-tilde"
									       name="ps_seo_title_separator"
									       value="~">
									<label class="radio" for="separator-sc-tilde">~</label>

									<input type="radio" class="radio" id="separator-sc-laquo"
									       name="ps_seo_title_separator"
									       value="«">
									<label class="radio" for="separator-sc-laquo">«</label>

									<input type="radio" class="radio" id="separator-sc-raquo"
									       name="ps_seo_title_separator"
									       value="»">
									<label class="radio" for="separator-sc-raquo">»</label>

									<input type="radio" class="radio" id="separator-sc-lt"
									       name="ps_seo_title_separator"
									       value="<">
									<label class="radio" for="separator-sc-lt">&lt;</label>

									<input type="radio" class="radio" id="separator-sc-gt"
									       name="ps_seo_title_separator"
									       value=">">
									<label class="radio" for="separator-sc-gt">&gt;</label>
								</fieldset>

								<p>
									<i class="fa fa-info-circle"></i> Set up which title separator should be used for
									separating parts in your titles.
								</p>
							</div>

                            <!-- Misc. Settings -->
                            <div class="uk-width-medium-1-2">
                                <h2><i class="fa fa-cogs"></i> Misc. Settings</h2>

                                <div class="uk-grid uk-grid-small">
                                    <div class="uk-width-1-2">
                                        <div class="slider-container">
                                            <!-- TODO -- functionality needs to be made for this ps_seo_target_keyword -->
                                            <input type="hidden" name="ps_seo_target_keyword" id="ps_seo_target_keyword"
                                                   value="0">
                                            <div class="prs-slider-frame">
												<span class="slider-button <?=(get_option('ps_seo_target_keyword')) ? 'on' : ''; ?>"
                                                      data-element="ps_seo_target_keyword"><?=(get_option('ps_seo_target_keyword')) ? 'Yes' : 'No'; ?></span>
                                            </div>
                                            <p class="slider-label">Meta Keywords Tag <i
                                                        class="fa fa-info-circle help-icon"
                                                        data-uk-tooltip=""
                                                        title="Will take 'Target Keyword' from your PS SEO enabled pages & posts and turn it into a meta keywords tag."></i>
                                            </p>
                                            <div class="uk-clearfix"></div>
                                        </div>

                                        <div class="slider-container">
                                            <!-- TODO -- functionality needs to be made for this ps_seo_force_noodp -->
                                            <input type="hidden" name="ps_seo_force_noodp" id="ps_seo_force_noodp"
                                                   value="0">
                                            <div class="prs-slider-frame">
												<span class="slider-button <?=(get_option('ps_seo_force_noodp')) ? 'on' : ''; ?>"
                                                      data-element="ps_seo_force_noodp"><?=(get_option('ps_seo_force_noodp')) ? 'Yes' : 'No'; ?></span>
                                            </div>
                                            <p class="slider-label">Force <code>noodp</code> meta robots <i
                                                        class="fa fa-info-circle help-icon" data-uk-tooltip=""
                                                        title="Prevents search engines from using the DMOZ description in the search results for all pages on this site. Note: If you set a custom description for a page or post, it will have the noodp tag regardless of this setting."></i>
                                            </p>
                                            <div class="uk-clearfix"></div>
                                        </div>

                                    </div>
                                    <div class="uk-width-1-2">
                                        <div class="slider-container">
                                            <!-- TODO -- functionality for this needs to be made ps_seo_index_subpages -->
                                            <input type="hidden" name="ps_seo_index_subpages" id="ps_seo_index_subpages"
                                                   value="0">
                                            <div class="prs-slider-frame">
												<span class="slider-button <?=(get_option('ps_seo_index_subpages')) ? 'on' : ''; ?>"
                                                      data-element="ps_seo_index_subpages"><?=(get_option('ps_seo_index_subpages')) ? 'Yes' : 'No'; ?></span>
                                            </div>
                                            <p class="slider-label">Don't index Subpages <i
                                                        class="fa fa-info-circle help-icon" data-uk-tooltip=""
                                                        title="If you want to prevent /members/thank-you/ and further of any archive to show up in the search results, set this to Yes."></i>
                                            </p>
                                            <div class="uk-clearfix"></div>
                                        </div>

                                        <div class="slider-container">
                                            <input type="hidden" name="ps_seo_always_on" id="ps_seo_always_on"
                                                   value="0">
                                            <div class="prs-slider-frame">
												<span class="slider-button <?=(get_option('ps_seo_always_on')) ? 'on' : ''; ?>"
                                                      data-element="ps_seo_always_on"><?=(get_option('ps_seo_always_on')) ? 'Yes' : 'No'; ?></span>
                                            </div>
                                            <p class="slider-label">Force enable PS SEO <i
                                                        class="fa fa-info-circle help-icon" data-uk-tooltip=""
                                                        title="If you have this checked on, each time you create a new page or post, it will automatically have PS SEO turned on."></i></p>
                                            <div class="uk-clearfix"></div>
                                        </div>
                                    </div>
                                </div>


                            </div>

						</div>

						<hr class="uk-grid-separator">

						<div class="uk-grid uk-grid-match" data-uk-grid-margin="">

                            <!-- Homepage SEO -->
                            <div class="uk-width-medium-3-4 uk-row-first uk-container-center">

								<!-- Homepage Title and Description -->
								<div class="uk-panel">
									<h2><i class="fa fa-home"></i> Homepage SEO</h2>
                                    <div class="uk-alert uk-alert-large" data-uk-alert="">
                                        <p>
                                            <i class="fa fa-info-circle"></i> Set up a default Title and Description for your website's Homepage.
                                        </p>
                                    </div>
                                    <div class="uk-alert uk-alert-large" data-uk-alert="">
                                        <p><i class="fa fa-info-circle"></i> When your home page settings in Wordpress are set to "Your Latest Posts" this will control the title and description of your home page.
                                             When a static page is set as the home page, the PS-SEO Title and Description will override this setting.
                                        </p>
                                    </div>
								</div>

								<div class="uk-panel">
									<h3><i class="fa fa-code"></i> Title Template:</h3>

									<!-- Title -->
									<input type="text" class="uk-input-big uk-input-custom text-left"
									       name="ps_seo_title"
									       placeholder="eg. My Title"
									       value="<?=stripslashes_deep( prs_stripUnwantedCharTag( get_option('ps_seo_title') ) );?>"
									/>

								</div>

								<div class="uk-panel">
									<h3><i class="fa fa-code"></i> Description Template:</h3>

									<!-- Description -->
									<textarea rows="10" class="uk-input-big uk-input-custom text-left"
									          name="ps_seo_description" placeholder="eg. My Description"
									><?=stripslashes_deep( get_option('ps_seo_description') );?></textarea>

								</div>

							</div>

						</div>

						<hr class="uk-grid-separator">

						<div class="uk-grid uk-grid-match" data-uk-grid-margin="">
							<div class="uk-width-1-1">
								<button type="submit" class="uk-button uk-button-big uk-button-success btn-save-changes"><i
										class="fa fa-save"></i> Save Changes
								</button>
							</div>
						</div>

					</div>

				</form>

			</div>
		</div>

        <!-- Mange Redirects -->
        <div>
            <div class="uk-block uk-block-muted">
                <table class="uk-table uk-table-hover table-redirects">
                    <thead>
                    <tr>
                        <th><i class="fa fa-check-square-o"></i></th>
                        <th><i class="fa fa-globe"></i> URL</th>
                        <th><i class="fa fa-refresh"></i> Redirects to</th>
                        <th><i class="fa fa-calendar"></i> Date Created</th>
                        <th><i class="fa fa-gears"></i></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr><td colspan="4">Can't find any active redirects.</td></tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th><i class="fa fa-check-square-o"></i></th>
                        <th><i class="fa fa-globe"></i> URL</th>
                        <th><i class="fa fa-refresh"></i> Redirects to</th>
                        <th><i class="fa fa-calendar"></i> Date Created</th>
                        <th><i class="fa fa-gears"></i></th>
                    </tr>
                    </tfoot>
                </table>

                <div class="uk-modal-footer uk-text-right">
                    <button type="button" class="uk-button uk-button-success add-new-redirect"><i class="fa fa-plus"></i> Add New Redirect</button>
                    <button type="button" class="uk-button uk-button-danger remove-selected-redirects"><i class="fa fa-trash"></i> Remove Selected Redirects</button>
                    <button type="button" class="uk-button uk-button-danger remove-all-redirects"><i class="fa fa-trash"></i> Remove All Redirects</button>
                </div>
            </div>
        </div>

		<!-- Post Types -->
		<div>
			<div class="uk-block uk-block-muted">
				<?php $post_types = get_option('ps_seo_post_types'); ?>
				<div class="uk-container-normal">
					<form class="save-posttypes">
					<input type="hidden" name="action" value="prs_save_posttypes"/>
                    <?php wp_nonce_field( 'prs_save_posttypes', '_wpnonce' ); ?>
					<div class="uk-grid uk-grid-small">
						<?php foreach(MPRS_Seo::getAllPostTypes() as $post_type) { ?>

							<div class="uk-width-1-2">

								<!-- Post Type (Title,Description & No-follow) -->
								<div class="uk-panel">
									<h2><i class="fa fa-star"></i> <?=ucfirst($post_type);?></h2>
									<p>
										<i class="fa fa-info-circle"></i> Default Title and Description for
										<code><?=$post_type;?></code> post type.
									</p>
								</div>

								<div class="uk-panel">
									<h3><i class="fa fa-code"></i> Title Template:</h3>

									<!-- Title -->
									<input type="text" class="uk-input-big uk-input-custom text-left"
									       name="ps_seo_post_types[<?=$post_type;?>][title]"
									       placeholder="eg. My Title"
									       value="<?=stripslashes_deep( prs_stripUnwantedCharTag(@$post_types[$post_type]['title']));?>"
									/>

								</div>

								<div class="uk-panel">
									<h3><i class="fa fa-code"></i> Description Template:</h3>

									<!-- Description -->
									<textarea rows="5" class="uk-input-big uk-input-custom text-left"
									          name="ps_seo_post_types[<?=$post_type;?>][description]" placeholder="eg. My Description"
									><?=stripslashes_deep( prs_stripUnwantedCharTag(@$post_types[$post_type]['description']));?></textarea>

								</div>

								<div class="uk-panel">
									<div class="slider-container">
										<input type="hidden" name="ps_seo_post_types[<?=$post_type;?>][nofollow]" id="ps_seo_slider-<?=$post_type;?>"
										       value="<?=@$post_types[$post_type]['nofollow'];?>">
										<div class="prs-slider-frame">
												<span class="slider-button <?=(@$post_types[$post_type]['nofollow'] == 1) ? 'on' : '';?>"
												      data-element="ps_seo_slider-<?=$post_type;?>"><?=(@$post_types[$post_type]['nofollow'] == 1) ? 'Yes' : 'No';?></span>
										</div>
										<p class="slider-label">Don't Index & Follow <i
												class="fa fa-info-circle help-icon"
												data-uk-tooltip=""
												title="Do not index these kind of post types but add meta robots follow to them."></i>
										</p>
									</div>
								</div>

							</div>

						<?php } ?>
					</div>

					<div class="uk-grid uk-grid-match" data-uk-grid-margin="">
						<div class="uk-width-1-1">
							<button type="submit" class="uk-button uk-button-big uk-button-success"><i
									class="fa fa-save"></i> Save Changes
							</button>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Taxonomies -->
		<div>
			<div class="uk-block uk-block-muted">
				<div class="uk-container-normal">
					<form class="save-taxonomies">
					<input type="hidden" name="action" value="prs_save_taxonomies"/>
                    <?php wp_nonce_field( 'prs_save_taxonomies', '_wpnonce' ); ?>
					<div class="uk-grid uk-grid-small">
						<?php $taxonomies = get_option('ps_seo_taxonomies'); ?>
						<?php foreach(MPRS_Seo::getAllTaxonomies() as $taxonomy) {

							// Extract the taxonomy real name
							$tax = get_taxonomy($taxonomy);

							?>

							<div class="uk-width-1-2">

								<!-- Post Type (Title,Description & No-follow) -->
								<div class="uk-panel">
									<h2><i class="fa fa-star"></i> <?=ucfirst($tax->label);?></h2>
									<p>
										<i class="fa fa-info-circle"></i> Default Title and Description for
										<code><?=$tax->label;?></code> taxonomy.
									</p>
								</div>

								<div class="uk-panel">
									<h3><i class="fa fa-code"></i> Title Template:</h3>

									<!-- Title -->
									<input type="text" class="uk-input-big uk-input-custom text-left"
									       name="ps_seo_taxonomies[<?=$taxonomy;?>][title]"
									       placeholder="eg. My Title"
									       value="<?=stripslashes_deep( prs_stripUnwantedCharTag(@$taxonomies[$taxonomy]['title']));?>"
									/>

								</div>

								<div class="uk-panel">
									<h3><i class="fa fa-code"></i> Description Template:</h3>

									<!-- Description -->
									<textarea rows="5" class="uk-input-big uk-input-custom text-left"
									          name="ps_seo_taxonomies[<?=$taxonomy;?>][description]" placeholder="eg. My Description"
									><?=stripslashes_deep( prs_stripUnwantedCharTag(@$taxonomies[$taxonomy]['description']));?></textarea>

								</div>

								<div class="uk-panel">
									<div class="slider-container">
										<input type="hidden" name="ps_seo_taxonomies[<?=$taxonomy;?>][nofollow]" id="ps_seo_slider-<?=$taxonomy;?>"
										       value="<?=@$taxonomies[$taxonomy]['nofollow'];?>">
										<div class="prs-slider-frame">
												<span class="slider-button <?=(@$taxonomies[$taxonomy]['nofollow'] == 1) ? 'on' : '';?>"
												      data-element="ps_seo_slider-<?=$taxonomy;?>"><?=(@$taxonomies[$taxonomy]['nofollow'] == 1) ? 'Yes' : 'No';?></span>
										</div>
										<p class="slider-label">Don't Index & Follow <i
												class="fa fa-info-circle help-icon"
												data-uk-tooltip=""
												title="Do not index these kind of post types but add meta robots follow to them."></i>
										</p>
									</div>
								</div>

							</div>

						<?php } ?>
					</div>

					<div class="uk-grid uk-grid-match" data-uk-grid-margin="">
						<div class="uk-width-1-1">
							<button type="submit" class="uk-button uk-button-big uk-button-success"><i
									class="fa fa-save"></i> Save Changes
							</button>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>

        <!-- Miscellaneous -->
        <div>
            <div class="uk-block uk-block-muted">
                <div class="uk-container-normal">
                    <form class="save-miscellaneous">
	                    <?php $miscellaneous = get_option('ps_seo_miscellaneous'); ?>
                        <input type="hidden" name="action" value="prs_save_miscellaneous"/>
                        <?php wp_nonce_field( 'prs_save_miscellaneous', '_wpnonce' ); ?>
                        <div class="uk-grid uk-grid-small">
                            <!-- Search -->
                            <div class="uk-width-1-2">

                                <!-- Type (Title,Description & No-follow) -->
                                <div class="uk-panel">
                                    <h2><i class="fa fa-star"></i> Search</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Default Title and Description for
                                        <code>Search</code> results.
                                    </p>
                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Title Template:</h3>

                                    <!-- Title -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_miscellaneous[search][title]"
                                           placeholder="eg. My Title"
                                           value="<?=stripslashes_deep( prs_stripUnwantedCharTag(@$miscellaneous['search']['title']));?>"
                                    />

                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Description Template:</h3>

                                    <!-- Description -->
                                    <textarea rows="5" class="uk-input-big uk-input-custom text-left"
                                              name="ps_seo_miscellaneous[search][description]" placeholder="eg. My Description"
                                    ><?=stripslashes_deep( prs_stripUnwantedCharTag(@$miscellaneous['search']['description']));?></textarea>

                                </div>

                                <div class="uk-panel">
                                    <div class="slider-container">
                                        <input type="hidden" name="ps_seo_miscellaneous[search][nofollow]" id="ps_seo_slider-search"
                                               value="<?=@$miscellaneous['search']['nofollow'];?>">
                                        <div class="prs-slider-frame">
												<span class="slider-button <?=(@$miscellaneous['search']['nofollow'] == 1) ? 'on' : '';?>"
                                                      data-element="ps_seo_slider-search"><?=(@$miscellaneous['search']['nofollow'] == 1) ? 'Yes' : 'No';?></span>
                                        </div>
                                        <p class="slider-label">Don't Index & Follow <i
                                                    class="fa fa-info-circle help-icon"
                                                    data-uk-tooltip=""
                                                    title="Do not index these kind of post types but add meta robots follow to them."></i>
                                        </p>
                                    </div>
                                </div>

                            </div>

                            <!-- Author -->
                            <div class="uk-width-1-2">

                                <!-- Type (Title,Description & No-follow) -->
                                <div class="uk-panel">
                                    <h2><i class="fa fa-star"></i> Author</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Default Title and Description for
                                        <code>Author</code> pages.
                                    </p>
                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Title Template:</h3>

                                    <!-- Title -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_miscellaneous[author][title]"
                                           placeholder="eg. My Title"
                                           value="<?=stripslashes_deep( prs_stripUnwantedCharTag(@$miscellaneous['author']['title']));?>"
                                    />

                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Description Template:</h3>

                                    <!-- Description -->
                                    <textarea rows="5" class="uk-input-big uk-input-custom text-left"
                                              name="ps_seo_miscellaneous[author][description]" placeholder="eg. My Description"
                                    ><?=stripslashes_deep( prs_stripUnwantedCharTag(@$miscellaneous['author']['description']));?></textarea>

                                </div>

                                <div class="uk-panel">
                                    <div class="slider-container">
                                        <input type="hidden" name="ps_seo_miscellaneous[author][nofollow]" id="ps_seo_slider-author"
                                               value="<?=@$miscellaneous['author']['nofollow'];?>">
                                        <div class="prs-slider-frame">
												<span class="slider-button <?=(@$miscellaneous['author']['nofollow'] == 1) ? 'on' : '';?>"
                                                      data-element="ps_seo_slider-author"><?=(@$miscellaneous['author']['nofollow'] == 1) ? 'Yes' : 'No';?></span>
                                        </div>
                                        <p class="slider-label">Don't Index & Follow <i
                                                    class="fa fa-info-circle help-icon"
                                                    data-uk-tooltip=""
                                                    title="Do not index these kind of post types but add meta robots follow to them."></i>
                                        </p>
                                    </div>
                                </div>

                            </div>

                            <!-- Archive -->
                            <div class="uk-width-1-2">

                                <!-- Type (Title,Description & No-follow) -->
                                <div class="uk-panel">
                                    <h2><i class="fa fa-star"></i> Archive</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Default Title and Description for
                                        <code>Archives</code>.
                                    </p>
                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Title Template:</h3>

                                    <!-- Title -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_miscellaneous[archive][title]"
                                           placeholder="eg. My Title"
                                           value="<?=stripslashes_deep( prs_stripUnwantedCharTag(@$miscellaneous['archive']['title']));?>"
                                    />

                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Description Template:</h3>

                                    <!-- Description -->
                                    <textarea rows="5" class="uk-input-big uk-input-custom text-left"
                                              name="ps_seo_miscellaneous[archive][description]" placeholder="eg. My Description"
                                    ><?=stripslashes_deep( prs_stripUnwantedCharTag(@$miscellaneous['archive']['description']));?></textarea>

                                </div>

                                <div class="uk-panel">
                                    <div class="slider-container">
                                        <input type="hidden" name="ps_seo_miscellaneous[archive][nofollow]" id="ps_seo_slider-archive"
                                               value="<?=@$miscellaneous['archive']['nofollow'];?>">
                                        <div class="prs-slider-frame">
												<span class="slider-button <?=(@$miscellaneous['archive']['nofollow'] == 1) ? 'on' : '';?>"
                                                      data-element="ps_seo_slider-archive"><?=(@$miscellaneous['archive']['nofollow'] == 1) ? 'Yes' : 'No';?></span>
                                        </div>
                                        <p class="slider-label">Don't Index & Follow <i
                                                    class="fa fa-info-circle help-icon"
                                                    data-uk-tooltip=""
                                                    title="Do not index these kind of post types but add meta robots follow to them."></i>
                                        </p>
                                    </div>
                                </div>

                            </div>

                            <!-- Post Type Archive -->
                            <div class="uk-width-1-2">

                                <!-- Type (Title,Description & No-follow) -->
                                <div class="uk-panel">
                                    <h2><i class="fa fa-star"></i> Post Type Archive</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Default Title and Description for
                                        <code>Post Type Archives</code>.
                                    </p>
                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Title Template:</h3>

                                    <!-- Title -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_miscellaneous[archive_post][title]"
                                           placeholder="eg. My Title"
                                           value="<?=stripslashes_deep( prs_stripUnwantedCharTag(@$miscellaneous['archive_post']['title']));?>"
                                    />

                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Description Template:</h3>

                                    <!-- Description -->
                                    <textarea rows="5" class="uk-input-big uk-input-custom text-left"
                                              name="ps_seo_miscellaneous[archive_post][description]" placeholder="eg. My Description"
                                    ><?=stripslashes_deep( prs_stripUnwantedCharTag(@$miscellaneous['archive_post']['description']));?></textarea>

                                </div>

                                <div class="uk-panel">
                                    <div class="slider-container">
                                        <input type="hidden" name="ps_seo_miscellaneous[archive_post][nofollow]" id="ps_seo_slider-archive_post"
                                               value="<?=@$miscellaneous['archive_post']['nofollow'];?>">
                                        <div class="prs-slider-frame">
												<span class="slider-button <?=(@$miscellaneous['archive_post']['nofollow'] == 1) ? 'on' : '';?>"
                                                      data-element="ps_seo_slider-archive_post"><?=(@$miscellaneous['archive_post']['nofollow'] == 1) ? 'Yes' : 'No';?></span>
                                        </div>
                                        <p class="slider-label">Don't Index & Follow <i
                                                    class="fa fa-info-circle help-icon"
                                                    data-uk-tooltip=""
                                                    title="Do not index these kind of post types but add meta robots follow to them."></i>
                                        </p>
                                    </div>
                                </div>

                            </div>

                            <!-- 404 -->
                            <div class="uk-width-1-2">

                                <!-- Type (Title,Description & No-follow) -->
                                <div class="uk-panel">
                                    <h2><i class="fa fa-star"></i> 404 Page</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Default Title and Description for
                                        <code>404</code> page.
                                    </p>
                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Title Template:</h3>

                                    <!-- Title -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_miscellaneous[not_found][title]"
                                           placeholder="eg. My Title"
                                           value="<?=stripslashes_deep( prs_stripUnwantedCharTag(@$miscellaneous['not_found']['title']));?>"
                                    />

                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Description Template:</h3>

                                    <!-- Description -->
                                    <textarea rows="5" class="uk-input-big uk-input-custom text-left"
                                              name="ps_seo_miscellaneous[not_found][description]" placeholder="eg. My Description"
                                    ><?=stripslashes_deep( prs_stripUnwantedCharTag(@$miscellaneous['not_found']['description']));?></textarea>

                                </div>

                                <div class="uk-panel">
                                    <div class="slider-container">
                                        <input type="hidden" name="ps_seo_miscellaneous[not_found][nofollow]" id="ps_seo_slider-not_found"
                                               value="<?=@$miscellaneous['not_found']['nofollow'];?>">
                                        <div class="prs-slider-frame">
												<span class="slider-button <?=(@$miscellaneous['not_found']['nofollow'] == 1) ? 'on' : '';?>"
                                                      data-element="ps_seo_slider-not_found"><?=(@$miscellaneous['not_found']['nofollow'] == 1) ? 'Yes' : 'No';?></span>
                                        </div>
                                        <p class="slider-label">Don't Index & Follow <i
                                                    class="fa fa-info-circle help-icon"
                                                    data-uk-tooltip=""
                                                    title="Do not index these kind of post types but add meta robots follow to them."></i>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="uk-grid uk-grid-match" data-uk-grid-margin="">
                            <div class="uk-width-1-1">
                                <button type="submit" class="uk-button uk-button-big uk-button-success"><i
                                            class="fa fa-save"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Open Graph -->
        <div>
            <div class="uk-block uk-block-muted">

                <form class="save-general">
                    <input type="hidden" name="action" value="prs_save_general"/>
                    <?php wp_nonce_field( 'prs_save_general', '_wpnonce' ); ?>
                    <div class="uk-container-normal">

                        <div class="uk-grid uk-grid-match" data-uk-grid-margin="">

                            <!-- Facebook -->
                            <div class="uk-width-medium-1-2">

                                <!-- Homepage Title and Description -->
                                <div class="uk-panel">
                                    <h2><i class="fa fa-facebook"></i> Facebook Open Graph</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Set up default Facebook Open Graph settings
                                        for
                                        your website's Homepage.
                                    </p>
                                    <div class="uk-alert uk-alert-large" data-uk-alert="">
                                        <p><i class="fa fa-info-circle"></i> After SAVING your page with your new Open Graph changes, make sure to visit this url to clear out the old cache in Facebook so that your posts show correctly immediately:
                                        <a href="https://developers.facebook.com/tools/debug/sharing/">Facebook Sharing Tool</a>
                                        </p>
                                    </div>
                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Title Template:</h3>

                                    <!-- Title -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_title_fb"
                                           placeholder="eg. My Title"
                                           value="<?=stripslashes_deep( prs_stripUnwantedCharTag(get_option('ps_seo_title_fb')));?>"
                                    />

                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Description Template:</h3>

                                    <!-- Description -->
                                    <textarea rows="4" class="uk-input-big uk-input-custom text-left"
                                              name="ps_seo_description_fb" placeholder="eg. My Description"
                                    ><?=stripslashes_deep( prs_stripUnwantedCharTag(get_option('ps_seo_description_fb')));?></textarea>

                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-image"></i> Image:
                                        <button type="button" data-target="ps_seo_image_fb"
                                                class="imageSelect uk-button uk-button-success"><i
                                                    class="fa fa-plus"></i>
                                            Browse
                                        </button>
                                    </h3>

                                    <!-- Image -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_image_fb"
                                           id="ps_seo_image_fb" placeholder="eg. http://www.website.com/image.jpg"
                                           value="<?=get_option('ps_seo_image_fb');?>"
                                    />

                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-image"></i> Preview:</h3>

                                    <div class="fb_post_preview_holder">
                                        <div class="fb_post_preview_img">
                                            <a href="" target="_blank">
                                                <img src="">
                                            </a>
                                        </div>

                                        <div class="fb_post_preview_body">
                                            <div class="fb_post_preview_title"></div>
                                            <div class="fb_post_preview_desc"></div>
                                            <div class="fb_post_preview_host"></div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!-- Twitter -->
                            <div class="uk-width-medium-1-2">

                                <!-- Homepage Title and Description -->
                                <div class="uk-panel">
                                    <h2><i class="fa fa-twitter"></i> Twitter Open Graph</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Set up default Twitter Open Graph settings for
                                        your website's Homepage.
                                    </p>

                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Title Template:</h3>

                                    <!-- Title -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_title_tw"
                                           placeholder="eg. My Title"
                                           value="<?=stripslashes_deep( prs_stripUnwantedCharTag(get_option('ps_seo_title_tw')));?>"
                                    />

                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-code"></i> Description Template:</h3>

                                    <!-- Description -->
                                    <textarea rows="4" class="uk-input-big uk-input-custom text-left"
                                              name="ps_seo_description_tw" placeholder="eg. My Description"
                                    ><?=stripslashes_deep( prs_stripUnwantedCharTag(get_option('ps_seo_description_tw')));?></textarea>

                                </div>

                                <div class="uk-panel">
                                    <h3><i class="fa fa-image"></i> Image:
                                        <button type="button" data-target="ps_seo_image_tw"
                                                class="imageSelect uk-button uk-button-success"><i
                                                    class="fa fa-plus"></i>
                                            Browse
                                        </button>
                                    </h3>

                                    <!-- Image -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_image_tw"
                                           placeholder="eg. http://www.website.com/image.jpg"
                                           value="<?=get_option('ps_seo_image_tw');?>"
                                    />

                                </div>

                                <div class="uk-panel" style="height: 383px;">
                                    <h3><i class="fa fa-image"></i> Preview:</h3>

                                    <div class="tw_post_preview_holder">
                                        <div class="tw_post_preview_img">
                                            <a href="" target="_blank">
                                                <img src="">
                                            </a>
                                        </div>

                                        <div class="tw_post_preview_body">
                                            <div class="tw_post_preview_title">Facebook Title</div>
                                            <div class="tw_post_preview_desc">Facebook Description</div>
                                            <div class="tw_post_preview_host">example.com</div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <hr class="uk-grid-separator">

                        <div class="uk-grid uk-grid-match" data-uk-grid-margin="">
                            <div class="uk-width-1-1">
                                <button type="submit" class="uk-button uk-button-big uk-button-success btn-save-changes"><i
                                            class="fa fa-save"></i> Save Changes
                                </button>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>

        <!-- Verifications -->
        <div>
            <div class="uk-block uk-block-muted">

                <form class="save-general">
                    <input type="hidden" name="action" value="prs_save_general"/>
                    <?php wp_nonce_field( 'prs_save_general', '_wpnonce' ); ?>
                    <div class="uk-container-normal">

                        <div class="uk-grid uk-grid-match" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-1">

                                <div class="uk-panel">

                                    <h2><i class="fa fa-bold"></i> Bing</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Bing webmaster verification code.
                                    </p>

                                    <!-- Bing -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_verify_bing" placeholder="eg. 1234567890"
                                           value="<?=get_option('ps_seo_verify_bing');?>"
                                    />

                                </div>

                            </div>
                            <div class="uk-width-medium-1-1">

                                <div class="uk-panel">

                                    <h2><i class="fa fa-google"></i> Google</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Google webmaster verification code.
                                    </p>

                                    <!-- Google -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_verify_google" placeholder="eg. 1234567890"
                                           value="<?=esc_html(get_option('ps_seo_verify_google'));?>"
                                    />

                                </div>

                            </div>
                            <div class="uk-width-medium-1-1">

                                <div class="uk-panel">

                                    <h2><i class="fa fa-google"></i> Google Analytics</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Google Analytics code.
                                    </p>

                                    <!-- Google Analytics -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_verify_google_analytics" placeholder="eg. UA-57398293-12"
                                           value="<?=get_option('ps_seo_verify_google_analytics');?>"
                                    />

                                </div>

                            </div>
                            <div class="uk-width-medium-1-1">

                                <div class="uk-panel">

                                    <h2><i class="fa fa-pinterest"></i> Pinterest</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Pinterest webmaster verification code.
                                    </p>

                                    <!-- Pinterest -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_verify_pinterest" placeholder="eg. 1234567890"
                                           value="<?=get_option('ps_seo_verify_pinterest');?>"
                                    />

                                </div>

                            </div>
                            <div class="uk-width-medium-1-1">

                                <div class="uk-panel">

                                    <h2><i class="fa fa-yahoo"></i> Yandex</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Yandex webmaster verification code.
                                    </p>

                                    <!-- Yandex -->
                                    <input type="text" class="uk-input-big uk-input-custom text-left"
                                           name="ps_seo_verify_yandex" placeholder="eg. 1234567890"
                                           value="<?=get_option('ps_seo_verify_yandex');?>"
                                    />

                                </div>

                            </div>
                        </div>

                        <hr class="uk-grid-separator">

                        <div class="uk-grid uk-grid-match" data-uk-grid-margin="">
                            <div class="uk-width-1-1">
                                <button type="submit" class="uk-button uk-button-big uk-button-success btn-save-changes"><i
                                            class="fa fa-save"></i> Save Changes
                                </button>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>

        <!-- Scripts -->
        <div>
            <div class="uk-block uk-block-muted">

                <form class="save-general">
                    <input type="hidden" name="action" value="prs_save_general"/>
                    <?php wp_nonce_field( 'prs_save_general', '_wpnonce' ); ?>
                    <div class="uk-container-normal">

                        <div class="uk-grid uk-grid-match" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-1">

                                <div class="uk-panel">

                                    <h2><i class="fa fa-code"></i> Global Scripts</h2>
                                    <p>
                                        <i class="fa fa-info-circle"></i> Insert any scripts here that you want to be included to your website globally (include <kbd>&lt;script&gt;</kbd> & <kbd>&lt;/script&gt;</kbd> tags as well).
                                    </p>

                                    <textarea id="ps_seo_global_scripts" name="ps_seo_global_scripts"><?=get_option('ps_seo_global_scripts');?></textarea>

                                </div>

                            </div>
                        </div>

                        <hr class="uk-grid-separator">

                        <div class="uk-grid uk-grid-match" data-uk-grid-margin="">
                            <div class="uk-width-1-1">
                                <button type="submit" class="uk-button uk-button-big uk-button-success btn-save-changes"><i
                                            class="fa fa-save"></i> Save Changes
                                </button>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>
	</div>

    <!-- Shortcodes -->
    <div id="shortcodes" class="uk-modal">
        <div class="uk-modal-dialog">
            <button type="button" class="uk-modal-close uk-close"></button>
            <div class="uk-modal-header">
                <h2><i class="fa fa-code"></i> Shortcodes</h2>
            </div>

            <table class="uk-table uk-table-hover table-shortcodes">
                <tbody>
                    <tr>
                        <td class="shortcode-cell">%%sitename%%</td>
                        <td>The site’s name</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%tagline%%</td>
                        <td>The site’s tagline / description</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%sep%%</td>
                        <td>The separator defined in your SEO settings</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%title%%</td>
                        <td>Replaced with the title of the post/page</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%parent_title%%</td>
                        <td>Replaced with the title of the parent page of the current page</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%term_title%%</td>
                        <td>Replaced with the term name</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%date%%</td>
                        <td>Replaced with the date of the post/page</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%pretty_date%%</td>
                        <td>Replaced with the date of the post/page in format ex. June 2017</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%search_query%%</td>
                        <td>Replaced with the current search query</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%author_name%%</td>
                        <td>Replaced with author's name</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%ps_seo_title%%</td>
                        <td>Replaced with PSv3 SEO Title</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%ps_seo_description%%</td>
                        <td>Replaced with PSv3 SEO Description</td>
                    </tr>

                    <?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); ?>
                    <?php if ( !is_plugin_active('wpglow-builder/wpglow-builder.php') ) { ?>
                        <tr>
                            <td class="shortcode-cell">%%excerpt%%</td>
                            <td>Replaced with the post/page excerpt</td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td class="shortcode-cell">%%tag%%</td>
                        <td>Replaced with the current tag/tags</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%category%%</td>
                        <td>Replaced with the post categories (comma separated)</td>
                    </tr>

                    <tr>
                        <td class="shortcode-cell">%%category_primary%%</td>
                        <td>Replaced with the primary category of the post/page</td>
                    </tr>

                </tbody>
            </table>

            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="uk-button uk-modal-close">Close</button>
            </div>
        </div>
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

<div class="tut_holder hide">
    <h2 style="margin-top: 20px"></h2>
    <iframe width="100%" height="480" frameborder="0" allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen"
            src="">
    </iframe>
</div>
<!-- Tutorials -->
