<?php
/**
 * Created by PhpStorm.
 * User: bstef
 * Date: 12/14/2016
 * Time: 7:55 PM
 */

$object  = $GLOBALS['wp_query']->get_queried_object();
$page_id = 0;

if (
        is_object($object) &&
        !MPRS_Seo::is_home_static_page() &&
        !MPRS_Seo::is_home_posts_page()
) {
    $page_id = $object->ID;
} else if (isset($isShortcode)) {
    $page_id = $post->ID;
}

// Unique Identifier
$unique_id = 'rw-' . substr(md5($page_id * rand(0,9999)), 0, 5);

$ps_review = get_option('ps_review');

$ps_stars = false;
$ps_stars_percentage = false;

$classes = array();
if (@$ps_review['settings']['form_labels'] == 1) {
	$classes[] = 'review-widget-labels';
}
if (@$ps_review['settings']['form_labels'] == 2) {
	$classes[] = 'review-widget-placeholders';
}
if (@$ps_review['settings']['widget_width'] == 1) {
	$classes[] = 'review-widget-auto-width';
}
if (@$ps_review['settings']['widget_theme'] == 1) {
	$classes[] = 'review-widget-flat';
}
if (@$ps_review['settings']['widget_theme'] == 2) {
	$classes[] = 'review-widget-minimal';
}
if (@$ps_review['settings']['alpha_bg'] == 1 || @$instance['alpha_mode'] == 1) {
	$classes[] = 'review-widget-alpha';
}

if (@$ps_review['settings']['popup'] == 1 || @$instance['popup_mode'] == 1) {
	$classes[] = 'review-widget-popup';
}
if (@$ps_review['settings']['alignment'] == NULL) {
	$classes[] = 'review-widget-left';
} else {
	$classes[] = 'review-widget-' . $ps_review['settings']['alignment'];
}

if (
        @$ps_review['settings']['stars_only'] == 1 ||
        @$instance['stars_only'] == 1
) {

    // Set the stars mode to ON
    $ps_stars = true;

    // Add the stars mode class
	$classes[] = 'review-widget-stars-only';

	// Check if schema gave us the rating value already
    if (isset($GLOBALS['currentRatingValue'])) {

	    $ps_stars_percentage = number_format($GLOBALS['currentRatingValue'], 0, '.', '');

    } else {

	    // Nope, calculate ourselves
	    $ratings = array();

	    if (@$ps_review['settings']['per_page_reviews'] == 1) {
		    $ratings = MPRS_Reviews::getReviewsForPage( $page_id, true );
	    } else {
		    $ratings = MPRS_Reviews::getReviewsGlobal(true);
	    }

	    $ratingsValue  = 0;
	    $totalRatings  = sizeof( $ratings );

	    foreach($ratings as $r) {
		    $ratingsValue = $ratingsValue + $r['rating'];
	    }

	    if (!empty($ratingsValue)) {
		    $ps_stars_percentage = number_format((($ratingsValue / $totalRatings) / 5) * 100, 0, '.', '');
        }
    }

	if (empty($ps_stars_percentage)) {
		$ps_stars_percentage = false;
	}

}


// Merge all the classes
$classes = join(' ', $classes);
?>

<div class="<?php echo $unique_id;?>">

<!-- Project Supremacy - Review Widget -->
<script type="application/javascript">
    ps_widgets.push({
        name: '<?php echo $unique_id;?>',
        data: {
            ps_admin_url         : '<?php echo admin_url();?>admin-post.php',
            ps_thank_you         : '<?php echo (@$ps_review['details']['thank_you'] == NULL) ? 'Thank you for leaving us a review!' : $ps_review['details']['thank_you']?>',
            ps_rating_thank_you  : '<?php echo (@$ps_review['details']['rating_thank_you'] == NULL) ? 'Thank you for leaving a rating!' : $ps_review['details']['rating_thank_you']?>',
            ps_stars_only        : '<?php echo $ps_stars;?>',
            ps_stars_init        : '<?php echo $ps_stars_percentage;?>'
        }
    })
</script>

<input type="hidden" name="ps_review[fields]" value="<?php echo (@$ps_review['fields'] != NULL) ? $ps_review['fields'] : ''?>"/>

<?php if (@$ps_review['settings']['popup'] == 1 || @$instance['popup_mode'] == 1) { ?>
<div class="review-widget-popup-container"></div>
<?php } ?>

<?php if (!isset($isShortcode)) { ?>
<aside class="widget">
<?php } ?>

<div class="review-widget <?php echo $classes;?>">
	<form class="ps-submit-review">

        <!-- Stars Only -->
        <?php if ($ps_stars === true) { ?>
            <input type="hidden" name="stars_only" value="1"/>
        <?php } else { ?>
            <input type="hidden" name="stars_only" value="0"/>
        <?php } ?>

		<!-- Action -->
		<input type="hidden" name="action" value="prs_newReview"/>

		<!-- Page ID -->
		<input type="hidden" name="page_id" value="<?php echo $page_id?>"/>

		<div class="review-widget-title">
			<h2><?php echo (@$ps_review['details']['title'] == NULL) ? 'Leave a Review' : @$ps_review['details']['title'];?></h2>
		</div>
		<div class="review-widget-text"><?php echo (@$ps_review['details']['text'] == NULL) ? 'Please be kind and leave us a review!' : @$ps_review['details']['text'];?></div>

        <div class="review-widget-stars-ratings-sum">
            <?php if ($ps_stars_percentage !== false) { ?>
                <?php echo (@$ps_review['details']['rating_text'] == NULL) ? '' : str_replace('{num}', '<b>' . $ps_stars_percentage . '%</b> ', @$ps_review['details']['rating_text']);?>
            <?php } else { ?>
	            <?php echo (@$ps_review['details']['no_ratings_message'] == NULL) ? 'Nobody yet left a rating. Be first?' : @$ps_review['details']['no_ratings_message'];?>
            <?php } ?>
        </div>

		<div class="review-widget-block-container">

		</div>

		<button class="review-widget-button" type="submit"><i class="fa fa-paper-plane"></i> <?php echo (@$ps_review['details']['button_title'] == NULL) ? 'Submit Review' : @$ps_review['details']['button_title'];?></button>

        <span class="review-widget-stars-ratings-info">
            <?php echo (@$ps_review['details']['rating_info'] == NULL) ? 'Click a star to add your rating' : @$ps_review['details']['rating_info'];?>
        </span>

	</form>
</div>

<?php if (!isset($isShortcode)) { ?>
</aside>
<?php } ?>

<?php if (@$ps_review['settings']['popup'] == 1 || @$instance['popup_mode'] == 1) { ?>
	<?php if (!isset($isShortcode)) { ?>
        <aside class="widget">
    <?php } ?>
        <?php if (@$ps_review['settings']['popup_text'] == 1 || @$instance['popup_text'] == 1) { ?>
            <a href="#" id="review-widget-popup-button" class="<?php echo (@$ps_review['settings']['exit_popup'] == 1 || @$instance['exit_popup'] == 1) ? 'exit-popup-window' : '';?>"><i class="fa fa-external-link"></i> <?php echo (@$ps_review['details']['popup_button_title'] == NULL) ? 'Leave a Review' : @$ps_review['details']['popup_button_title'];?></a>
        <?php } else { ?>
            <button type="button" id="review-widget-popup-button" class="<?php echo (@$ps_review['settings']['exit_popup'] == 1 || @$instance['exit_popup'] == 1) ? 'exit-popup-window' : '';?>"><i class="fa fa-external-link"></i> <?php echo (@$ps_review['details']['popup_button_title'] == NULL) ? 'Leave a Review' : @$ps_review['details']['popup_button_title'];?></button>
        <?php } ?>
	<?php if (!isset($isShortcode)) { ?>
        </aside>
    <?php } ?>
<?php } ?>

<!-- Custom Colors -->
<style>
	.<?php echo $unique_id;?> .review-widget {
	<?php if (@$ps_review['colors']['background'] != NULL) { ?>
		background: <?php echo $ps_review['colors']['background'];?>;
	<?php } ?>
	<?php if (@$ps_review['colors']['text'] != NULL) { ?>
		color: <?php echo $ps_review['colors']['text'];?>;
	<?php } ?>
	<?php if (@$ps_review['colors']['border'] != NULL) { ?>
		border-color: <?php echo $ps_review['colors']['border'];?>;
	<?php } ?>
    <?php if (@$ps_review['padding']['widget'] != NULL) { ?>
        padding: <?php echo $ps_review['padding']['widget'];?>px;
    <?php } ?>
	}
    .<?php echo $unique_id;?> .review-widget-button {
	<?php if (@$ps_review['colors']['button_background'] != NULL) { ?>
		background: <?php echo $ps_review['colors']['button_background'];?>;
	<?php } ?>
	<?php if (@$ps_review['colors']['button_text'] != NULL) { ?>
		color: <?php echo $ps_review['colors']['button_text'];?>;
	<?php } ?>
	}
    .<?php echo $unique_id;?> .review-widget-label, .review-widget-title > h2 {
	<?php if (@$ps_review['colors']['text'] != NULL) { ?>
		color: <?php echo $ps_review['colors']['text'];?>;
	<?php } ?>
	}
    .<?php echo $unique_id;?> .review-widget-label {
    <?php if (@$ps_review['font_size']['label'] != NULL) { ?>
        font-size: <?php echo $ps_review['font_size']['label'];?>px;
    <?php } ?>
    }
    .<?php echo $unique_id;?> .review-widget-title > h2 {
    <?php if (@$ps_review['font_size']['heading'] != NULL) { ?>
        font-size: <?php echo $ps_review['font_size']['heading'];?>px;
    <?php } ?>
    }
    .<?php echo $unique_id;?> .review-widget-text {
    <?php if (@$ps_review['font_size']['subheading'] != NULL) { ?>
        font-size: <?php echo $ps_review['font_size']['subheading'];?>px;
    <?php } ?>
    }

    .<?php echo $unique_id;?> .review-widget-stars-ratings-sum {
    <?php if (@$ps_review['colors']['rating_heading'] != NULL) { ?>
        color: <?php echo $ps_review['colors']['rating_heading'];?>;
    <?php } ?>
    <?php if (@$ps_review['details']['rating_heading_size'] != NULL) { ?>
        font-size: <?php echo $ps_review['details']['rating_heading_size'];?>px;
    <?php } ?>
    }

    .<?php echo $unique_id;?> .review-widget-stars-ratings-info {
    <?php if (@$ps_review['colors']['rating_info'] != NULL) { ?>
        color: <?php echo $ps_review['colors']['rating_info'];?>;
    <?php } ?>
    <?php if (@$ps_review['details']['rating_instruction_size'] != NULL) { ?>
        font-size: <?php echo $ps_review['details']['rating_instruction_size'];?>px;
    <?php } ?>
    }

    .<?php echo $unique_id;?> .review-widget-input {
	<?php if (@$ps_review['colors']['input_background'] != NULL) { ?>
		background: <?php echo $ps_review['colors']['input_background'];?>;
	<?php } ?>
	<?php if (@$ps_review['colors']['input_text'] != NULL) { ?>
		color: <?php echo $ps_review['colors']['input_text'];?>;
	<?php } ?>
    <?php if (@$ps_review['font_size']['input'] != NULL) { ?>
        font-size: <?php echo $ps_review['font_size']['input'];?>px;
    <?php } ?>
    <?php if (@$ps_review['padding']['input'] != NULL) { ?>
        padding: <?php echo $ps_review['padding']['input'];?>px;
    <?php } ?>
	}
    .<?php echo $unique_id;?> .review-widget-stars i {
	<?php if (@$ps_review['colors']['stars'] != NULL) { ?>
		color: <?php echo $ps_review['colors']['stars'];?>;
	<?php } ?>
    <?php if (@$ps_review['font_size']['stars'] != NULL) { ?>
        font-size: <?php echo $ps_review['font_size']['stars'];?>px !important;
    <?php } ?>
	}
</style>
<!-- Project Supremacy - Review Widget -->

</div>