<?php

if ( ! class_exists( 'MPRS_Review' ) ) {

	class MPRS_Review extends WP_Widget {

		public static function initialize() {
            add_action( 'widgets_init', array( 'MPRS_Review', 'registerWidget' ) );
            add_shortcode('prs_reviews_widget', array('MPRS_Reviews', 'reviewsWidgetShortcode'));
		}

		public static function registerWidget() {
			register_widget( 'MPRS_Review' );
		}

		function __construct() {
			$widget_ops = array(
				'classname' => 'MPRS_Review',
				'description' => 'Form for submitting reviews for your website.',
			);
			parent::__construct( false, '[PSv3] - Review Widget', $widget_ops );
		}

		function widget( $args, $instance ) {

		    $render = function($args, $instance){
			    include(PRS_PATH . '/pages/metabox/prs_review.php');
            };

		    $render($args, $instance);
		}

		function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['popup_mode'] = $new_instance['popup_mode'];
			$instance['stars_only'] = $new_instance['stars_only'];
			$instance['alpha_mode'] = $new_instance['alpha_mode'];

			return $instance;
		}

		function form( $instance ) {

			$popup_mode = '';
			if (isset($instance['popup_mode'])) {
				if ($instance['popup_mode'] == 1) {
					$popup_mode = 'checked';
				}
			}

			$alpha_mode = '';
			if (isset($instance['alpha_mode'])) {
				if ($instance['alpha_mode'] == 1) {
					$alpha_mode = 'checked';
				}
			}

			$stars_only = '';
			if (isset($instance['stars_only'])) {
				if ($instance['stars_only'] == 1) {
					$stars_only = 'checked';
				}
			}

			?>

            <p>
                <input type="checkbox" <?php echo $popup_mode;?> value="1" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'alpha_mode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'alpha_mode' ) ); ?>">
                <label for="<?php echo esc_attr( $this->get_field_id( 'alpha_mode' ) ); ?>"><b><i>Alpha Mode</i></b>
            </p>

			<p>
				<input type="checkbox" <?php echo $popup_mode;?> value="1" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'popup_mode' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popup_mode' ) ); ?>">
				<label for="<?php echo esc_attr( $this->get_field_id( 'popup_mode' ) ); ?>"><b><i>Popup Mode</i></b>
			</p>

            <p>
                <input type="checkbox" <?php echo $stars_only;?> value="1" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'stars_only' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'stars_only' ) ); ?>">
                <label for="<?php echo esc_attr( $this->get_field_id( 'stars_only' ) ); ?>"><b><i>Widget Ratings Mode</i></b>
            </p>


			<?php

			$admin_url = admin_url();
			echo "<p>To configure more settings for Review widget, please go to <a href='{$admin_url}admin.php?page=prs-reviews' target='_blank'>Reviews</a> page and tweak settings accordingly. </p>";
		}

	}
}