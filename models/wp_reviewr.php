<?php

if ( ! class_exists( 'MPRS_Reviewr' ) ) {

	class MPRS_Reviewr extends WP_Widget {

		public static function initialize() {
            add_action( 'widgets_init', array( 'MPRS_Reviewr', 'registerWidget' ) );
            add_shortcode('prs_reviews', array('MPRS_Reviews', 'reviewsDisplayShortcode'));
		}

		public static function registerWidget() {
			register_widget( 'MPRS_Reviewr' );
		}

		function __construct() {
			$widget_ops = array(
				'classname' => 'MPRS_Reviewr',
				'description' => 'Widget that displays reviews from PSv3.',
			);
			parent::__construct( false, '[PSv3] - Display Reviews', $widget_ops );
		}

		function widget( $args, $instance ) {

			$render = function($instance){
				include(PRS_PATH . '/pages/metabox/prs_display_reviews.php');
			};

			$render($instance);
		}

		function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['limit_reviews']        = $new_instance['limit_reviews'];
			$instance['random_reviews']       = $new_instance['random_reviews'];
			$instance['limit_reviews_number'] = $new_instance['limit_reviews_number'];
			$instance['aggregate_rating']     = $new_instance['aggregate_rating'];

			return $instance;
		}

		function form( $instance ) {

			$limit_reviews = '';
			if (isset($instance['limit_reviews'])) {
				if ($instance['limit_reviews'] == 1) {
					$limit_reviews = 'checked';
				}
			}

			$limit_reviews_number = 5;
			if (isset($instance['limit_reviews_number'])) {
				if (!empty($instance['limit_reviews_number'])) {
					$limit_reviews_number = $instance['limit_reviews_number'];
				}
			}

			$random_reviews = '';
			if (isset($instance['random_reviews'])) {
				if ($instance['random_reviews'] == 1) {
					$random_reviews = 'checked';
				}
			}

            $aggregate_rating = '';
            if (isset($instance['aggregate_rating'])) {
                if ($instance['aggregate_rating'] == 1) {
                    $aggregate_rating = 'checked';
                }
            }

			?>

			<p>
				<input type="checkbox" <?php echo $limit_reviews;?> value="1" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'limit_reviews' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit_reviews' ) ); ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'limit_reviews' ) ); ?>"><b><i>Limit Displayed Reviews</i></b> - <input placeholder="5" style="width: 43px;text-align: center;padding: 0 !important;height: 21px;" id="<?php echo esc_attr( $this->get_field_id( 'limit_reviews_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit_reviews_number' ) ); ?>" type="number" value="<?php echo $limit_reviews_number; ?>">
			</p>

			<p>
				<input type="checkbox" <?php echo $random_reviews;?> value="1" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'random_reviews' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'random_reviews' ) ); ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'random_reviews' ) ); ?>"><b><i>Display Random Reviews</i></b>
			</p>

            <p>
                <input type="checkbox" <?php echo $aggregate_rating;?> value="1" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'aggregate_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'aggregate_rating' ) ); ?>">
                <label for="<?php echo esc_attr( $this->get_field_id( 'aggregate_rating' ) ); ?>"><b><i>Display Aggregate Rating on the top of Reviews</i></b>
            </p>


			<?php
		}

	}
}