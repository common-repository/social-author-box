<?php
/**
 * Social Author Box plugin file
 *
 * @package Social Author Box
 */

/*
Plugin Name: Social Author Box
Plugin URI: http://leanderlindahl.se/
Description: Creates an "Author Box" Widget with the author's picture, bio and social media links
Author: Leander Lindahl
Author URI: http://leanderlindahl.se/
License: GPL2
Text Domain: social_author_box
Version: 1.5.0
*/

/**
 * Include the options page.
 */
require 'inc/options.php';

/* Add the new Contact Methods to user profile */
add_filter( 'user_contactmethods', 'add_new_contactmethod', 99 );

/**
 * Add CSS for the Author Box Display
 */
function social_author_box_scripts() {
	$options = get_option( 'social_author_box_settings' );

	if ( '' !== $options['social_author_box_checkbox_fa_css'] ) {
		wp_enqueue_style( 'prefix-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3' );
	}
	if ( '' !== $options['social_author_box_checkbox_plugin_css'] ) {
		wp_register_style( 'social-author-box', plugins_url( '/css/social-authorbox.css', __FILE__ ), array(), '1.0', 'screen' );
		wp_enqueue_style( 'social-author-box' );
	}
}
add_action( 'wp_enqueue_scripts', 'social_author_box_scripts' );

/**
 *  Declare the widget
 */
class Social_Authorbox_Widget extends WP_Widget {

	/**
	 * The Widget Constructor.
	 *
	 * @comment CodeSniffer requires a doc comment here.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'PopAuthorBox',
			'description' => 'Creates an Author Box Widget with the author\'s picture, bio and social media links',
		);
		parent::__construct( 'PopAuthorBox', 'Social Author Box', $widget_ops );
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @param array $instance       Array of current settings.
	 */
	public function form( $instance ) {
		// Create variables to avoid undefined index php error.
		$facebook   = '';
		$twitter    = '';
		$googleplus = '';
		$linkedin   = '';

		// Check values.
		if ( $instance ) {
			if ( isset( $instance['facebook'] ) && ! empty( $instance['facebook'] ) ) {
				$facebook = esc_attr( $instance['facebook'] );
			}
			if ( isset( $instance['twitter'] ) && ! empty( $instance['twitter'] ) ) {
				$twitter = esc_attr( $instance['twitter'] );
			}
			if ( isset( $instance['googleplus'] ) && ! empty( $instance['googleplus'] ) ) {
				$googleplus = esc_attr( $instance['googleplus'] );
			}
			if ( isset( $instance['linkedin'] ) && ! empty( $instance['linkedin'] ) ) {
				$linkedin = esc_attr( $instance['linkedin'] );
			}
		} else {
			$facebook   = '';
			$twitter    = '';
			$googleplus = '';
			$linkedin   = '';
			add_option( 'social_authorbox_facebook', $facebook );
			add_option( 'social_authorbox_twitter', $twitter );
			add_option( 'social_authorbox_googleplus', $googleplus );
			add_option( 'social_authorbox_linkedin', $linkedin );
		}
		?>
	<p><?php esc_html_e( 'Active social media profiles:', 'wp_widget_plugin' ); ?></p>
	<p>
	  <input id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $facebook ); ?> />
	  <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook', 'wp_widget_plugin' ); ?></label>
	</p>
	<p>
	  <input id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $linkedin ); ?> />
	  <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'LinkedIn', 'wp_widget_plugin' ); ?></label>
	</p>
	<p>
	  <input id="<?php echo esc_attr( $this->get_field_id( 'googleplus' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'googleplus' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $googleplus ); ?> />
	  <label for="<?php echo esc_attr( $this->get_field_id( 'googleplus' ) ); ?>"><?php esc_html_e( 'Google Plus', 'wp_widget_plugin' ); ?></label>
	</p>
	<p>
	  <input id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $twitter ); ?> />
	  <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter', 'wp_widget_plugin' ); ?></label>
	</p>
	<p><i><?php esc_html_e( 'URL to the profiles must be added in the respective User Profiles', 'wp_widget_plugin' ); ?></i></p>
		<?php
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * @param array $new_instance       New settings for this instance.
	 * @param array $old_instance       Old settings for this instance.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		// Fields.
		$instance['facebook'] = wp_strip_all_tags( $new_instance['facebook'] );
		update_option( 'social_authorbox_facebook', wp_strip_all_tags( $new_instance['facebook'] ) );

		$instance['twitter'] = wp_strip_all_tags( $new_instance['twitter'] );
		update_option( 'social_authorbox_twitter', wp_strip_all_tags( $new_instance['twitter'] ) );

		$instance['googleplus'] = wp_strip_all_tags( $new_instance['googleplus'] );
		update_option( 'social_authorbox_googleplus', wp_strip_all_tags( $new_instance['googleplus'] ) );

		$instance['linkedin'] = wp_strip_all_tags( $new_instance['linkedin'] );
		update_option( 'social_authorbox_linkedin', wp_strip_all_tags( $new_instance['linkedin'] ) );

		return $instance;
	}

	/**
	 * Sub-classes should over-ride this function to generate their widget code.
	 *
	 * @param array $args           Display arguments including 'before_title', 'after_title',
	 * 'before_widget', and 'after_widget'.
	 * @param array $instance       The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {

		// Echo $args['before_widget'] won't pass PHPCS.
		// Interesting discussion here:
		// https://wordpress.stackexchange.com/questions/
		// 249015/php-coding-standards-widgets-and-sanitization.
		echo $args['before_widget'];

		// WIDGET CODE GOES HERE.
		$author_box = '<div class="author-box">';

		$author_box .= '<div class="author-photo-wrapper"><span class="author-photo">' . get_avatar( get_the_author_meta( 'ID' ) ) . '</span></div><div class="author-description">' . wpautop( get_the_author_meta( 'description' ) ) . '</div>';

		$author_box .= '<div class="author-social-links"><ul>';
		if ( get_the_author_meta( 'facebook_profile' ) && isset( $instance['facebook'] ) && $instance['facebook'] ) {
			$author_box .= '<li class="facebook"><a href="' . get_the_author_meta( 'facebook_profile' ) . '" title="' . get_the_author() . ' på LinkedIn" target="_blank"><i class="fa fa-facebook-square"></i></a></li>';
		}
		if ( get_the_author_meta( 'linkedin_profile' ) && isset( $instance['linkedin'] ) && $instance['linkedin'] ) {
			$author_box .= '<li class="linkedin"><a href="' . get_the_author_meta( 'linkedin_profile' ) . '" title="' . get_the_author() . ' på LinkedIn" target="_blank"><i class="fa fa-linkedin-square"></i></a></li>';
		}
		if ( get_the_author_meta( 'google_profile' ) && isset( $instance['googleplus'] ) && $instance['googleplus'] ) {
			$author_box .= '<li class="googleplus"><a href="' . get_the_author_meta( 'google_profile' ) . '" title="' . get_the_author() . ' på Google+" target="_blank"><i class="fa fa-google-plus-square"></i></a></li>';
		}
		if ( get_the_author_meta( 'twitter_profile' ) && isset( $instance['twitter'] ) && $instance['twitter'] ) {
			$author_box .= '<li class="twitter"><a href="' . get_the_author_meta( 'twitter_profile' ) . '" title="' . get_the_author() . ' på Twitter" target="_blank"><i class="fa fa-twitter-square"></i></a></li>';
		}
		$author_box .= '</ul></div>';

		$author_box .= '</div>';

		echo $author_box;

		echo $args['after_widget'];
	}
}

/**
 * Function to register the widget.
 */
function social_author_box_register_widget() {
	return register_widget( 'Social_Authorbox_Widget' );
}
add_action( 'widgets_init', 'social_author_box_register_widget' );

/**
 * Adding custom fields to user profile
 *
 * @param array $contactmethods           Methods by which author can be contacted.
 */
function add_new_contactmethod( $contactmethods ) {
	if ( get_option( 'social_authorbox_facebook' ) ) {
		$contactmethods['facebook_profile'] = 'Facebook URL';
	}
	if ( get_option( 'social_authorbox_linkedin' ) ) {
		$contactmethods['linkedin_profile'] = 'LinkedIn URL';
	}
	if ( get_option( 'social_authorbox_googleplus' ) ) {
		$contactmethods['google_profile'] = 'GooglePlus URL';
	}
	if ( get_option( 'social_authorbox_twitter' ) ) {
		$contactmethods['twitter_profile'] = 'Twitter URL';
	}

	return $contactmethods;
}

/**
 * Author description
 */
function author_description() {
	global $post;
	$source = get_post_meta( $post->ID, 'author_desc', true );
	if ( $source ) {
		return $source; } else {
		return get_the_author_meta( 'description' );
		}
}
