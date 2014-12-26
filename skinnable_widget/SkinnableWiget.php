<?php
/**
 * Plugin Name: Skinnable Widget
 * Plugin URI:
 * Description: A widget whose layout can be overridden in a theme.
 * Author: Ryan Welcher
 * Version: 1.0
 * Author URI: http://www.ryanwelcher.com
 */


class SkinnableWidget extends WP_Widget {

	/**
	 * @const The name of the template file to look for
	 */
	const TEMPLATE_NAME = 'skinnable-widget-view.php';

	/**
	 * Standard __construct method
	 */
	function __construct() {

		//setup some options
		$widget_options = array(
			'classname'     => 'skinnable-widget',
			'description'   => 'A widget whose layout can be overridden in a theme',
		);

		//call WP_Widget's __construct method
		parent::__construct( 'rw_skinnable_widget', 'Skinnable Widget', $widget_options );

	}



	/**
	 * Admin side widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	function form( $instance ) {
		// get the saved values (if any) and store them in local variables
		$title = ( isset( $instance['title'] ) ) ? $instance['title'] : '';
		$message = ( isset(  $instance['message'] ) ) ? $instance['message'] : '';

		//output the form as html
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Title: </label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) );?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title );?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'message' ) ); ?>"> <?php _e( 'Message:' );?></label>
			<textarea class="widefat" id="<?php echo  esc_attr( $this->get_field_id( 'message' ) );?>" name="<?php echo  esc_attr( $this->get_field_name( 'message') );?>"><?php echo esc_textarea( $message );?></textarea>
		</p>
	<?php
	}



	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['message'] = ( ! empty( $new_instance['message'] ) ) ? strip_tags( $new_instance['message'] ) : '';
		return $instance;
	}



	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {

		// get the saved values (if any) and store them in local variables
		$title = ( $instance['title'] ) ? $instance['title'] : '';
		$message = ( $instance['message'] ) ? $instance['message'] : '';

		//add to the arguments array any custom items we want to use in the template
		$args['title'] = $title;
		$args['message'] = $message;

		//render the output
		$this->sw_load_template( $args );
	}


	/**
	 * Method to load the template
	 * @param array The information we're going to pass to the template. Typically, the args parameter from the widget() method.
	 */
	private function sw_load_template( array $_vars ) {

		//first look to see if there is template in the theme
		$possible_locations = array(
			'custom_templates/'.self::TEMPLATE_NAME,
			self::TEMPLATE_NAME
		);

		//use the wordpress method locate_templaet() to find any custom views
		$_template = locate_template( $possible_locations, false, false);

		// use the default one if the theme doesn't have it
		if(!$_template) {
			$_template = '_views/' . self::TEMPLATE_NAME ;
		}
		// load it
		extract($_vars);
		require $_template;
	}
}

/**
 * End of Class
 */




/**
 * Init the widget
 */
function rw_skinnable_widget_init() {
	register_widget("SkinnableWidget");
}
add_action('widgets_init','rw_skinnable_widget_init');
?>