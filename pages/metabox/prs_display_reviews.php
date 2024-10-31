<?php

// Get the Page ID
$object  = $GLOBALS['wp_query']->get_queried_object();
$page_id = 0;

if ( is_object( $object ) && ! MPRS_Seo::is_home_static_page() && ! MPRS_Seo::is_home_posts_page() ) {
	$page_id = $object->ID;
} else if ( isset( $isShortcode ) ) {
	$page_id = $post->ID;
}

// Review Settings
$ps_review = get_option( 'ps_review' );

// Reviews Array
$reviews = array();

// Should display Random Reviews
$shouldRandom = false;
if ( @$instance['random_reviews'] == 1 ) {
	$shouldRandom = true;
}

// Get all Reviews
if ( @$ps_review['settings']['per_page_reviews'] == 1 ) {
	$reviews = MPRS_Reviews::getReviewsForPage( $page_id, false, $shouldRandom );
} else {
	$reviews = MPRS_Reviews::getReviewsGlobal(false, $shouldRandom);
}

// Count reviews
$reviewCount = sizeof( $reviews );

// Should Limit Reviews
$limit_reviews = false;
if ( @$instance['limit_reviews'] == 1 && @$instance['limit_reviews_number'] > 0 ) {
	$limit_reviews = $instance['limit_reviews_number'];
}

// Should display AggregateRating
$displayReviewsHeading = null;
if ( @$instance['aggregate_rating'] == 1 ) {

	$ratingValue = 0;

	foreach ( $reviews as $r ) {
		$ratingValue = $ratingValue + $r['rating'];
	}

	if (!empty($reviewCount)) {

		$ratingValue = $ratingValue / $reviewCount;
		$ratingValue = number_format( $ratingValue, 1 );

		$displayReviewsHeading = ( @$ps_review['details']['display_reviews_text'] == null ) ? '{calc} Rating From {sum} Reviews.' : @$ps_review['details']['display_reviews_text'];
		$displayReviewsHeading = str_replace( '{calc}', '<b>' . $ratingValue . "</b>", $displayReviewsHeading );
		$displayReviewsHeading = str_replace( '{sum}', '<b>' . $reviewCount . '</b>', $displayReviewsHeading );

    }
}

$displayReviewsTitle = ( @$ps_review['details']['display_reviews_heading'] == null ) ? null : @$ps_review['details']['display_reviews_heading'];;


?>

<?php if ( !isset($isShortcode) ) { ?>
<aside class="widget">
<?php } ?>

    <div class="prs-review-display-container">

        <?php if ( $displayReviewsTitle !== null ) { ?>
            <div class="prs-review-display-heading">
                <h2><?php echo $displayReviewsTitle; ?></h2>
            </div>
        <?php } ?>

		<?php if ( $displayReviewsHeading !== null ) { ?>
            <div class="prs-review-container-aggregate"><?php echo $displayReviewsHeading; ?></div>
		<?php } ?>

        <!-- Display Reviews -->
		<?php foreach ( $reviews as $r ) {

			// Date
			$date = date( 'd, M Y', strtotime( $r['date'] ) );

			// Stars
			$full_stars  = $r['rating'];
			$empty_stars = 5 - $r['rating'];

			// Review Author
			$name = '';
			if ( ! empty( $r['name'] ) )      $name = '<b>' . stripslashes( $r['name'] ) . '</b> <br>';
			if ( ! empty( $r['website'] ) )   $name = '<a href="' . $r['website'] . '" target="_blank">' . $name . '</a>';
			if ( ! empty( $r['age'] ) )       $name .= $r['age'];
			if ( ! empty( $r['location'] ) )  $name .= ' from ' . $r['location'];
			if ( ! empty( $r['email'] ) )     $name .= ' (<a style="color: blue;" href="mailto:' . $r['email'] . '" target="_blank"><i class="fa fa-at"></i></a>)';
			if ( ! empty( $r['telephone'] ) ) $name .= ' (<a style="color: blue;" href="tel:' . $r['telephone'] . '" target="_blank"><i class="fa fa-phone"></i></a>)';

			// Limit Reviews
			$limit_class = '';
			if ( $limit_reviews !== false ) {
				if ( $limit_reviews === 0 ) {
					$limit_class = 'review-hidden';
				} else {
					$limit_reviews --;
				}
			}

			?>

            <div class="prs-review-container <?php echo $limit_class; ?>">

				<?php if ( ! empty( $r['title'] ) ) { ?>
                    <div class="prs-review-title"><?php echo $r['title']; ?></div>
				<?php } ?>

                <div class="prs-review-spacer"><i class="fa fa-quote-right"></i></div>

                <!-- Print Stars -->
                <div class="prs-review-stars">

					<?php for ( $i = 0; $i < $full_stars; $i ++ ) { ?>
                        <i class="fa fa-star"></i>
					<?php } ?>

					<?php for ( $i = 0; $i < $empty_stars; $i ++ ) { ?>
                        <i class="fa fa-star-o"></i>
					<?php } ?>

                    <span class="prs-review-date"> on <?php echo $date; ?></span>

                </div>

				<?php if ( ! empty( $r['review'] ) ) { ?>
                    <div class="prs-review-body"><?php echo stripslashes( $r['review'] ); ?></div>
				<?php } ?>

                <div class="prs-review-author"><?php echo stripslashes( $name ); ?></div>

            </div>

		<?php } ?>

		<?php if ( $limit_reviews !== false && !empty($reviewCount) ) { ?>
            <div class="prs-review-more"><a href="#" class="prs-show-reviews"><i class="fa fa-caret-down"></i> <span>Show more</span></a>
            </div>
		<?php } ?>


        <!-- No Reviews Message -->
		<?php
		if ( empty( $reviews ) ) {
			if ( isset( $ps_review['details'] ) ) {
				if ( isset( $ps_review['details']['no_reviews_message'] ) ) {
					if ( ! empty( $ps_review['details']['no_reviews_message'] ) ) {
						echo '<p>' . $ps_review['details']['no_reviews_message'] . '</p>';
					} else {
						echo '<p><i class="fa fa-frown-o"></i> Nobody yet left a review. Be first?</p>';
					}
				} else {
					echo '<p><i class="fa fa-frown-o"></i> Nobody yet left a review. Be first?</p>';
				}
			} else {
				echo '<p><i class="fa fa-frown-o"></i> Nobody yet left a review. Be first?</p>';
			}
		}
		?>

    </div>

<?php if ( !isset($isShortcode) ) { ?>
</aside>
<?php } ?>

<!-- Custom Styles for Display Reviews Widget -->
<style>
    <?php if ( @$ps_review['details']['heading_size'] != null ) { ?>
    .prs-review-display-container .prs-review-display-heading {
        font-size: <?php echo $ps_review['details']['heading_size'];?>px !important;
    }
    <?php } ?>

    <?php if ( @$ps_review['details']['subheading_size'] != null ) { ?>
    .prs-review-display-container .prs-review-container-aggregate {
        font-size: <?php echo $ps_review['details']['subheading_size'];?>px !important;
    }
    <?php } ?>

    <?php if ( @$ps_review['colors_display']['background'] != null ) { ?>

    .prs-review-display-container .prs-review-container-aggregate {
        background: <?php echo $ps_review['colors_display']['background'];?> !important;
    }

    .prs-review-display-container .prs-review-container {
        background: <?php echo $ps_review['colors_display']['background'];?> !important;
    }

    <?php } ?>

    <?php if ( @$ps_review['colors_display']['border'] != null ) { ?>

    .prs-review-display-container .prs-review-container-aggregate {
        border-color: <?php echo $ps_review['colors_display']['border'];?> !important;
    }

    .prs-review-display-container .prs-review-container {
        border-color: <?php echo $ps_review['colors_display']['border'];?> !important;
    }

    <?php } ?>

    <?php if ( @$ps_review['colors_display']['stars'] != null ) { ?>

    .prs-review-display-container .prs-review-container .prs-review-stars i {
        color: <?php echo $ps_review['colors_display']['stars'];?> !important;

    <?php if ( @$ps_review['details']['display_star_size'] != null ) { ?>
        font-size: <?php echo $ps_review['details']['display_star_size'];?>px !important;
    <?php } ?>
    }

    <?php } ?>

    <?php if ( @$ps_review['colors_display']['text'] != null ) { ?>

    .prs-review-display-container .prs-review-container-aggregate {
        color: <?php echo $ps_review['colors_display']['text'];?> !important;
    }

    .prs-review-display-container .prs-review-container {
        color: <?php echo $ps_review['colors_display']['text'];?> !important;
    }

    .prs-review-display-container .prs-review-display-heading h2 {
        color: <?php echo $ps_review['colors_display']['text'];?> !important;
    }

    <?php } ?>

</style>
