<?php
	// Get default templates
	$post_types = get_option('ps_seo_post_types');
	$post_type = $post->post_type;

	$template = @$post_types[$post_type];

	// Load all variables
	$meta = MPRS_Seo::formatMetaVariables(get_post_meta($post->ID));

	$home_url = preg_replace('#^https?://#', '', get_home_url());

/**
 *  SPECIAL FIXES FOR SPECIALLY RETARDED THEMES & PLUGINS
 */
// THRIVE
// ------------------------------------------------------------------------
$custom_fields = get_post_custom($post->ID);
$body = '';
if (isset($custom_fields['tve_save_post_author-focused-homepage'])) {
	$body = $custom_fields['tve_save_post_author-focused-homepage'][0];
	$body = trim(preg_replace('/\s\s+/', ' ', $body));
	$body = preg_replace('~[\r\n]+~', '', $body);
	$body = str_replace("'","\\'", $body);
	echo "<script>var thriveBody = '$body';</script>";
}
// ------------------------------------------------------------------------

?>
<input type="hidden" id="prs_post_id" value="<?=$post->ID;?>"/>

<button type="button" data-target="seo" class="tab-button activated beParent"><i class="fa fa-gears"></i> General</button>
<button type="button" data-target="robots" class="tab-button beParent"><i class="fa fa-bug"></i> Robots</button>
<button type="button" data-target="social" class="tab-button beParent"><i class="fa fa-facebook-square"></i> Social</button>
<button type="button" data-target="notes" class="tab-button beParent"><i class="fa fa-file-text"></i> Notes</button>
<button type="button" data-target="scripts" class="tab-button beParent"><i class="fa fa-file-code-o"></i> Scripts</button>

<!-- SEO Enabled -->
<input type="hidden" name="ps_seo_enabled" id="ps_seo_enabled" value="<?php echo (@$meta['ps_seo_enabled'] == 1 || PRS_FORCE_SEO == 1) ? '1' : '0'; ?>"/>

<!-- NoOnce -->
<?php wp_nonce_field( 'prs_nonce_box', 'prs_nonce' ); ?>

<div class="prs-slider-frame beParent">
	<span class="slider-button <?php echo (@$meta['ps_seo_enabled'] == 1 || PRS_FORCE_SEO == 1) ? 'on' : ''; ?>" data-element="ps_seo_enabled"><?php echo (@$meta['ps_seo_enabled'] == 1 || PRS_FORCE_SEO == 1) ? 'ON' : 'OFF'; ?></span>
</div>


<div class="prs-box activated seo">
	<!-- SEO -->
	<div class="tab-box displayed uk-grid uk-grid-collapse">
		<div class="uk-width-large-1-2 uk-width-medium-1-1 uk-width-small-1-1">
			<label class="ps-label"><i class="fa fa-search"></i> Search Engine Results Page - Preview:</label>
			<p class="uk-form-help-block"><span class="uk-badge uk-badge-success"><i class="fa fa-info-circle"></i></span>
				Below you can check how your page will approximately look like on search engines.
			</p>

			<div class="prs-serp left">
				<!-- SEO Title -->
				<input type="hidden" name="ps_seo_title" id="seo_title" value="<?php echo @$meta['ps_seo_title']; ?>"/>
				<div class="prs-editor" data-target="seo_title" id="prs-title" contenteditable="true" placeholder="<?=MPRS_Seo::replaceVars($template['title'], $post->ID);?>"><?php echo @$meta['ps_seo_title']; ?></div>

				<!-- SEO URL -->
				<input type="hidden" name="ps_seo_url" value="<?php echo MPRS_Seo::extract_url($post->ID);  ?>"/>
				<div class="prs-editor" id="prs-url" contenteditable="false">
					<?php echo site_url(MPRS_Seo::extract_url($post->ID));  ?>
				</div>

				<!-- SEO Description -->
				<input type="hidden" name="ps_seo_description" id="seo_description" value="<?php echo @$meta['ps_seo_description']; ?>"/>
				<div class="prs-editor" data-target="seo_description" id="prs-description" contenteditable="true" placeholder="<?=MPRS_Seo::replaceVars($template['description'], $post->ID);?>"><?php echo @$meta['ps_seo_description']; ?></div>
			</div>

		</div>
		<div class="uk-width-large-1-2 uk-width-medium-1-1 uk-width-small-1-1">

			<label class="ps-label"><i class="fa fa-dashboard"></i> On Page Health Analysis:</label>
			<p class="uk-form-help-block"><span class="uk-badge uk-badge-success"><i class="fa fa-info-circle"></i></span>
				Below you can see the approximate on page SEO health of your current page.
			</p>

			<div class="prs-serp right">
				<div class="uk-grid uk-grid-collapse">
					<div class="uk-width-large-4-10" style="border-right: 1px solid #bcbbbb">
						<label class="ps-label ps-label-block">Target Keyword <i class="fa fa-info-circle" data-uk-tooltip="" title="You can insert a keyword here that you wish to target this post on."></i></label>
						<small class="ps-metric">
							<input type="text" name="ps_seo_keyword" id="seo_keyword" placeholder="eg. cheap cars" value="<?php echo @$meta['ps_seo_keyword']; ?>"/>
						</small>

						<label class="ps-label ps-label-block">Title Length <i class="fa fa-info-circle" data-uk-tooltip="" title="Displays the current number of characters in SEO Title."></i></label>
						<small class="ps-metric">

                            <div class="count-seo-container-left">

                                <i class="fa fa-desktop" data-uk-tooltip="" title="Desktop devices limits."></i>
                                <span class="count-seo-bold">
                                    <span class="count-seo-title">0</span> / 70
                                </span>
                                chars.

                            </div>

                            <div class="count-seo-container-right">

                                <i class="fa fa-mobile" data-uk-tooltip="" title="Mobile devices limits."></i>
                                <span class="count-seo-bold">
                                    <span class="count-seo-title-mobile">0</span> / 78
                                </span>
                                chars.

                            </div>

                            <div class="clear"></div>

                        </small>

						<label class="ps-label ps-label-block">Description Length <i class="fa fa-info-circle" data-uk-tooltip="" title="Displays the current number of characters in SEO Description."></i></label>
						<small class="ps-metric">

                            <div class="count-seo-container-left">

                                <i class="fa fa-desktop" data-uk-tooltip="" title="Desktop devices limits."></i>
                                <span class="count-seo-bold">
                                    <span class="count-seo-description">0</span> / 300
                                </span>
                                chars.

                            </div>

                            <div class="count-seo-container-right">

                                <i class="fa fa-mobile" data-uk-tooltip="" title="Mobile devices limits."></i>
                                <span class="count-seo-bold">
                                    <span class="count-seo-description-mobile">0</span> / 120
                                </span>
                                chars.

                            </div>

                            <div class="clear"></div>

                        </small>

						<label class="ps-label ps-label-block">Keyword Density <i class="fa fa-info-circle" data-uk-tooltip="" title="Displays the percentage of your Target Keyword appearing in your post content."></i></label>
						<small class="ps-metric" style="padding-left: 4px;">

                            <span class="count-seo-bold">
                                <span class="count-seo-density">0</span>
                            </span>

                            Density in Total

                            <span class="count-seo-bold">
                                <span class="count-seo-words">0</span>
                            </span>

                            Words
                        </small>
					</div>
					<div class="uk-width-large-6-10" style="padding-left: 20px">
						<label class="ps-label ps-label-block"><i class="fa fa-edit"></i> Content Analysis</label>
						<ul class="content-ul">
							<li id="prs_ca_keyword_title"></li>
							<li id="prs_ca_keyword_desc"></li>
							<li id="prs_ca_keyword_body"></li>
							<li id="prs_ca_keyword_url"></li>
							<li id="prs_ca_h1_keyword"></li>
							<li id="prs_ca_h2_keyword"></li>
							<li id="prs_ca_h3_keyword"></li>
						</ul>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<div class="prs-box robots">
	<!-- Meta -->
	<div class="uk-grid uk-grid-collapse">
		<div class="uk-width-1-2 responsive">
			<label class="ps-label"><i class="fa fa-bug"></i> Meta Robots - Settings:</label>
			<p class="uk-form-help-block"><span class="uk-badge uk-badge-success"><i class="fa fa-info-circle"></i></span>
				Below you can set up what robots your page will display.
			</p>

			<div class="uk-grid uk-grid-collapse uk-grid-form-row">
				<div class="uk-width-1-2">
					<label>Meta Robots State:</label>
				</div>
				<div class="uk-width-1-2">
					<input type="hidden" name="ps_seo_metarobots_enabled" id="seo_robots_enabled" value="<?php echo @$meta['ps_seo_metarobots_enabled']; ?>"/>
					<div class="prs-slider-frame small">
						<span class="seo_robots_enabled slider-button  <?php echo (@$meta['ps_seo_metarobots_enabled']) ? 'on' : ''; ?>" data-element="seo_robots_enabled"><?php echo (@$meta['ps_seo_metarobots_enabled']) ? 'ON' : 'OFF'; ?></span>
					</div>
				</div>
			</div>

			<div class="uk-grid uk-grid-collapse uk-grid-form-row">
				<div class="uk-width-1-2">
					<label for="seo_robots_index">Meta Robots - Index Type:</label>
				</div>
				<div class="uk-width-1-2">
					<select name="ps_seo_metarobots_index" id="seo_robots_index" class="render_select" data-selected="<?php echo @$meta['ps_seo_metarobots_index']; ?>">
						<option value="">–– Select ––</option>
						<option value="index">Index this Page</option>
						<option value="noindex">Do not Index this Page</option>
					</select>
				</div>
			</div>

			<div class="uk-grid uk-grid-collapse uk-grid-form-row">
				<div class="uk-width-1-2">
					<label for="seo_robots_follow">Meta Robots - Follow Type:</label>
				</div>
				<div class="uk-width-1-2">
					<select name="ps_seo_metarobots_follow" id="seo_robots_follow" class="render_select" data-selected="<?php echo @$meta['ps_seo_metarobots_follow']; ?>">
                        <option value="">–– Select ––</option>
                        <option value="follow">Follow this Page</option>
						<option value="nofollow">Do not Follow this Page</option>
					</select>
				</div>
			</div>

			<div class="uk-grid uk-grid-collapse uk-grid-form-row">
				<div class="uk-width-1-1">
					<label for="seo_robots_advanced">Meta Robots - Advanced:</label>

					<select name="ps_seo_metarobots_advanced[]" class="chosen-select" multiple id="seo_robots_advanced" data-selected="<?php echo @$meta['ps_seo_metarobots_advanced']; ?>">
						<option value="noodp">NO ODP</option>
						<option value="noydir">NO YDIR</option>
						<option value="noimageindex">No Image Index</option>
						<option value="noarchive">No Archive</option>
						<option value="nosnippet">No Snippet</option>
					</select>

				</div>
			</div>


		</div>
		<div class="uk-width-1-2 responsive">
			<label class="ps-label"><i class="fa fa-eye"></i> Meta Robots - Preview:</label>
			<p class="uk-form-help-block"><span class="uk-badge uk-badge-success"><i class="fa fa-info-circle"></i></span>
				Below you can see how your meta robots looks like in page source.
			</p>

			<pre class="meta-robots-preview">This is a preview</pre>

			<div class="uk-form-row">
				<label for="seo_canonical">Canonical (Alternative) URL:</label>
				<input placeholder="eg. http://yourwebsite.com/YourDifferentURL" type="text" id="seo_canonical" name="ps_seo_canonical" value="<?php echo @$meta['ps_seo_canonical']; ?>"/>
			</div>

		</div>
	</div>
</div>
<div class="prs-box social">
	<!-- Social -->
	<div class="uk-grid uk-grid-collapse">
        <div class="uk-width-1-1" style="margin-bottom: 20px">
            <div class="uk-form-row" style="background: #e2faff;border-radius: 4px;">
                <span class="uk-badge uk-badge-success"><i class="fa fa-info-circle"></i></span> After SAVING your page with your new Open Graph changes, make sure to visit this url to clear out the old cache in Facebook so that your posts show correctly immediately:
                <a href="https://developers.facebook.com/tools/debug/sharing/?q=<?php echo urlencode(site_url(MPRS_Seo::extract_url($post->ID)));  ?>" target="_blank">Facebook Sharing Tool</a>
            </div>
        </div>
		<div class="uk-width-1-2 responsive">
			<label class="ps-label"><i class="fa fa-twitter-square"></i> Twitter OG Settings:</label>
			<p class="uk-form-help-block"><span class="uk-badge uk-badge-success"><i class="fa fa-info-circle"></i></span>
				Set up how your page will appear when shared on Twitter.
			</p>

			<div class="uk-form-row m-t-10">
				<label for="seo_tw_title">Title:</label>
				<input placeholder="My Title" type="text" id="seo_tw_title" name="ps_seo_tw_title" value="<?php echo @$meta['ps_seo_tw_title']; ?>"/>
			</div>

			<div class="uk-form-row">
				<label for="seo_tw_description">Description:</label>
				<textarea id="seo_tw_description" name="ps_seo_tw_desc" rows="5" placeholder="eg. My Description"><?php echo @$meta['ps_seo_tw_desc']; ?></textarea>
			</div>

			<div class="uk-form-row">
				<label for="seo_tw_image">Image: <button class="uk-button uk-button-mini uk-button-primary imageSelect" type="button" data-target="seo_tw_image">Browse</button></label>
				<input placeholder="eg. http://mywebsite.com/myimage.png" type="text" id="seo_tw_image" name="ps_seo_tw_img" value="<?php echo @$meta['ps_seo_tw_img']; ?>"/>
			</div>

            <div class="uk-form-row" style="height: 383px;">
                <h3><i class="fa fa-image"></i> Preview:</h3>

                <div class="tw_post_preview_holder">
                    <div class="tw_post_preview_img">
                        <a href="" target="_blank">
                            <img src="" style="width: 100%">
                        </a>
                    </div>

                    <div class="tw_post_preview_body">
                        <div class="tw_post_preview_title">Facebook Title</div>
                        <div class="tw_post_preview_desc">Facebook Description</div>
                        <div class="tw_post_preview_host"><?php echo $home_url ?></div>
                    </div>
                </div>
            </div>

		</div>
		<div class="uk-width-1-2 responsive">
			<label class="ps-label"><i class="fa fa-facebook-square"></i> Facebook OG Settings:</label>
			<p class="uk-form-help-block"><span class="uk-badge uk-badge-success"><i class="fa fa-info-circle"></i></span>
				Set up how your page will appear when shared on Facebook.
			</p>

			<div class="uk-form-row m-t-10">
				<label for="seo_fb_title">Title:</label>
				<input placeholder="My Title" type="text" id="seo_fb_title" name="ps_seo_fb_title" value="<?php echo @$meta['ps_seo_fb_title']; ?>"/>
			</div>

			<div class="uk-form-row">
				<label for="seo_fb_description">Description:</label>
				<textarea id="seo_fb_description" name="ps_seo_fb_desc" rows="5" placeholder="eg. My Description"><?php echo @$meta['ps_seo_fb_desc']; ?></textarea>
			</div>

			<div class="uk-form-row">
				<label for="seo_fb_image">Image: <button class="uk-button uk-button-mini uk-button-primary imageSelect" type="button" data-target="seo_fb_image">Browse</button></label>
				<input placeholder="eg. http://mywebsite.com/myimage.png" type="text" id="seo_fb_image" name="ps_seo_fb_img" value="<?php echo @$meta['ps_seo_fb_img']; ?>"/>
			</div>

            <div class="uk-form-row">
                <h3><i class="fa fa-image"></i> Preview:</h3>

                <div class="fb_post_preview_holder">
                    <div class="fb_post_preview_img">
                        <a href="" target="_blank">
                            <img src="">
                        </a>
                    </div>

                    <div class="fb_post_preview_body">
                        <div class="fb_post_preview_title">Facebook Title</div>
                        <div class="fb_post_preview_desc">Facebook Description</div>
                        <div class="fb_post_preview_host"><?php echo $home_url ?></div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
<div class="prs-box notes">
    <!-- Notes -->
    <div class="uk-grid uk-grid-small">
        <div class="uk-width-large-1-1">
            <label for="ps_seo_notes" class="ps-label"><i class="fa fa-file-text"></i> Notes for this Page/Post:</label>
            <p class="uk-form-help-block"><span class="uk-badge uk-badge-success"><i class="fa fa-info-circle"></i></span>
                Quickly write down anything that may be helpful to know about this particular Page/Post.
            </p>
            <textarea id="ps_seo_notes" rows="5" name="ps_seo_notes"><?php echo @$meta['ps_seo_notes']; ?></textarea>
        </div>
    </div>
</div>
<div class="prs-box scripts">
    <!-- Scripts -->
    <div class="uk-grid uk-grid-small">
        <div class="uk-width-large-1-1">
            <label for="ps_seo_notes" class="ps-label"><i class="fa fa-file-code-o"></i> Custom Scripts for this Page/Post:</label>
            <p class="uk-form-help-block"><span class="uk-badge uk-badge-success"><i class="fa fa-info-circle"></i></span>
                Insert any scripts here that you want to be included to your website globally (include <kbd>&lt;script&gt;</kbd> & <kbd>&lt;/script&gt;</kbd> tags as well).

            </p>
            <input type="hidden" value="0" name="ps_seo_disable_global_scripts"/>

            <br>

            <label for="ps_seo_disable_global_scripts"><input <?php checked(@$meta['ps_seo_disable_global_scripts'], true); ?> type="checkbox" value="1" name="ps_seo_disable_global_scripts" id="ps_seo_disable_global_scripts"> <b>Do not render Global Scripts</b></label>

            <textarea id="ps_seo_scripts" rows="6" name="ps_seo_scripts"><?php echo @$meta['ps_seo_scripts']; ?></textarea>
        </div>
    </div>
</div>







