<?php
class Geckos_Flipcards_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// store the widget options
		$widget_ops = array(
			'classname' => 'geckos_flipcards_widget content-vertical-massive clearfix', //classnames added to the list <li> element of the widget
			'description' => __('Add this widget to the widget area of your choice and display your posts with a flip effect', 'geckos-kit'),
		);
		parent::__construct( 'geckos_flipcards_widget', __('Geckos Flipcards', 'geckos-kit'), $widget_ops ); // pass it to WP_Widget
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		extract( $args );
    $title 						= apply_filters('widget_title', $instance['title']);
    $message 					= $instance['message'];
		$image 						= $instance['image'];
		$background_color = $instance['background_color'];
    ?>
          <?php echo $before_widget;

					// making flipcards work in older internet explorer versions ?>
					<!--[if lte IE 9]>
					<style>
					.flipcard .card-back {display: none;}
					.flipcard .card-front {display: block; background-color: #6991c7;}
					.flipcard:hover .card-back {display: block;}
					.flipcard:hover .card-front {display: none;}
					</style>
					<![endif]-->
					<style type="text/css">
						.geckos_flipcards_widget {
							background: url('<?php echo $image;?>') no-repeat center center;
							background-size: cover;
							background-attachment: <?php if( $instance['checkbox'] AND $instance['checkbox'] == '1' ){ echo 'fixed';} ?>
						}
					</style>

						<div id="flipcards" class="container">
							<div class="section-title clearfix text-center">
              <?php if ( $title ) { ?>
                  <h3 class="heading"><?php echo $title; ?></h3>
							<?php } ?>
									<div class="excerpt"><?php echo $message; ?></div>
							</div>

						<?php
						$args = array(
					        'post_type' => 'geckos-flipcards',
					        'post_status' => 'publish',
					        'nopaging' => true,
					        'order' => 'ASC',
					        'orderby' => 'menu_order'
				    	);

				    $query = new WP_Query( $args );

				    if ( $query->have_posts() ) :
				    	while ( $query->have_posts() ) : $query->the_post(); ?>

							<div class="flipcard" ontouchstart="this.classList.toggle('hover');"><!-- ToDo: check the behavior on touch screens! -->
									<div class="card-front">

										<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); }?>

										<h3 class="cards-bg" style="background-color: <?php echo $background_color; ?>;"><?php echo the_title(); ?></h3>
									</div>
									<div class="card-back" style="background-color: <?php echo $background_color; ?>;">
										<div class="flipcard-text">

												<?php echo the_content(); ?>

										</div>
								</div>
							</div><!-- /.col -->

							<?php
							endwhile;

							wp_reset_query();

						endif; ?>

						</div> <!-- .container -->

          <?php echo $after_widget; ?>
    <?php
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// set the default values of each option
		$defaults = array( 'title' => __('Title', 'geckos-kit'), 'message' => __('Short description', 'geckos-kit'), 'image' => '', 'checkbox' => '', 'background_color' => '#e3e3e3' );
		// pull in the instance values (widget settings), array is empty if the widget was just added to the widget area
		$instance = wp_parse_args((array) $instance, $defaults);
		if(isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}
		if(isset($instance['message'])) {
    	$message = esc_attr($instance['message']);
		}
		if(isset($instance['image'])) {
    	$image = esc_url($instance['image']);
		}
		if(isset($instance['checkbox'])) {
    	$checkbox = esc_attr($instance['checkbox']);
		}
		if(isset($instance['background_color'])) {
			$background_color = esc_attr($instance['background_color']);
		}
    ?>
     <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'geckos-kit'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Description', 'geckos-kit'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" type="text" value="<?php echo $message; ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_name( 'image' ); ?>"><?php _e( 'Background Image:', 'geckos-kit' ); ?></label>
      <input name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" class="widefat" type="text" size="36"  value="<?php echo $image; ?>" />
      <input class="upload_image_button button button-primary" type="button" value="Upload Image" />
    </p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'checkbox' ) ); ?>"><?php _e( 'Parallax Effect?', 'geckos-kit' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'checkbox' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'checkbox' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $checkbox ); ?> />
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'background_color' ); ?>"><?php _e( 'Background Color:', 'geckos-kit' ); ?></label>
      <input class="color-picker widefat" name="<?php echo $this->get_field_name( 'background_color' ); ?>" id="<?php echo $this->get_field_id( 'background_color' ); ?>" type="text" data-alpha="true" value="<?php echo $background_color; ?>" />
    </p>
    <?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		// sanitizing user entered data using the strip_tags PHP function
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['message'] = strip_tags($new_instance['message']);
		$instance['image'] = strip_tags($new_instance['image']);
		$instance['checkbox'] = strip_tags($new_instance['checkbox']);
		$instance['background_color'] = strip_tags($new_instance['background_color']);

        return $instance;
	}
} // end class

/**
 * Hooks
 */

// hook the custom register function after the default widgets have been registered
add_action('widgets_init', 'geckos_register_flipcards_widget');


/**
 * Custom functions
 */

function geckos_register_flipcards_widget(){
	register_widget('Geckos_Flipcards_Widget');
}
