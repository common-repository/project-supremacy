<?php

if ( ! class_exists( 'MPRS_Extend_widgets' ) ) {

	class MPRS_Extend_widgets  {

		public static function initialize() {
			add_filter('in_widget_form', array('MPRS_Extend_widgets', 'extend'), 10, 3 );
			add_filter('widget_update_callback', array('MPRS_Extend_widgets', 'save'), 10, 2 );
			add_filter('widget_display_callback', array('MPRS_Extend_widgets', 'render'), 10, 3 );
		}

		public static function render( $instance, $widget, $args ) {
			$object = $GLOBALS['wp_query']->get_queried_object();
			if ( is_object( $object ) ) {
                $ID = $object->ID;

				$display_type = isset($instance['ps_ew_display_type'] ) ? $instance['ps_ew_display_type'] : 'none';
				$saved_posts  = isset($instance['ps_ew_posts']) ? $instance['ps_ew_posts'] : array();
				$saved_pages  = isset($instance['ps_ew_pages']) ? $instance['ps_ew_pages'] : array();

				if (in_array($ID, $saved_pages) || in_array($ID, $saved_posts)) {
				    if ($display_type === 'hide') {
						return false;
                    }
                } else {
					if ($display_type === 'show') {
						return false;
					}
                }
			}
			return $instance;
		}

		public static function extend( $widget, $return, $instance ) {
			$display_type = isset( $instance['ps_ew_display_type'] ) ? $instance['ps_ew_display_type'] : 'none';

			$saved_posts = isset($instance['ps_ew_posts']) ? $instance['ps_ew_posts'] : array();
			$saved_pages = isset($instance['ps_ew_pages']) ? $instance['ps_ew_pages'] : array();

			$args = array(
				'posts_per_page'   => 100,
				'orderby'          => 'date',
				'order'            => 'DESC',
				'post_type'        => 'post',
				'post_status'      => 'publish',
				'suppress_filters' => true
			);
			$posts = get_posts( $args );
			$args['post_type'] = 'page';
			$pages = get_posts( $args );


			?>

            <hr>

            <p>
                <label for="<?php echo $widget->get_field_id('ps_ew_display_type'); ?>">
                    <b>Display Type:</b>
                    <select class="widefat" id="<?php echo $widget->get_field_id('ps_ew_display_type'); ?>" name="<?php echo $widget->get_field_name('ps_ew_display_type'); ?>" >
                        <option <?php echo (($display_type === 'none') ? 'selected' : ''); ?> value="none">––– Select –––</option>
                        <option <?php echo (($display_type === 'hide') ? 'selected' : ''); ?> value="hide">Hide on selected pages</option>
                        <option <?php echo (($display_type === 'show') ? 'selected' : ''); ?> value="show">Show on selected pages</option>
                    </select>
                </label>
            </p>

            <h4>Pages</h4>

            <div class="ps_ew_container">

            <?php foreach($pages as $page) { ?>
                <p>
                    <input <?php if (in_array($page->ID, $saved_pages)) echo 'checked';?> type="checkbox" value="<?php echo $page->ID;?>" class="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'prs-page-' ) . $page->ID ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'ps_ew_pages' ) ); ?>[]">
                    <label for="<?php echo esc_attr( $widget->get_field_id( 'prs-page-' ) . $page->ID  ); ?>"><b><i><?php echo $page->post_title;?></i></b>
                </p>
            <?php } ?>

            </div>

            <h4>Posts</h4>

            <div class="ps_ew_container">

			<?php foreach($posts as $post) { ?>
                <p>
                    <input <?php if (in_array($post->ID, $saved_posts)) echo 'checked';?> type="checkbox" value="<?php echo $post->ID;?>" class="checkbox" id="<?php echo esc_attr( $widget->get_field_id( 'prs-post-' ). $post->ID  ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'ps_ew_posts' ) ); ?>[]">
                    <label for="<?php echo esc_attr( $widget->get_field_id( 'prs-post-' ). $post->ID  ); ?>"><b><i><?php echo $post->post_title;?></i></b>
                </p>
			<?php } ?>

            </div>

			<?php
		}

		public static function save( $instance, $new_instance ) {
			$instance['ps_ew_display_type'] = $new_instance['ps_ew_display_type'];
			$instance['ps_ew_posts']        = $new_instance['ps_ew_posts'];
			$instance['ps_ew_pages']        = $new_instance['ps_ew_pages'];
			return $instance;
		}


	}
}