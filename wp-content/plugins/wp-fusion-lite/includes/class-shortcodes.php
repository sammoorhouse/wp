<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WPF_Shortcodes {

	public function __construct() {

		add_shortcode( 'wpf', array( $this, 'shortcodes' ) );
		add_shortcode( 'wpf_update_tags', array( $this, 'shortcode_update_tags' ) );
		add_shortcode( 'wpf_update_meta', array( $this, 'shortcode_update_meta' ) );

		add_shortcode( 'wpf_loggedin', array( $this, 'shortcode_loggedin' ) );
		add_shortcode( 'wpf_loggedout', array( $this, 'shortcode_loggedout' ) );

		if( ! shortcode_exists( 'user_meta' ) ) {
			add_shortcode( 'user_meta', array( $this, 'shortcode_user_meta' ), 10, 2 );
		}

	}


	/**
	 * Handles content restriction shortcodes
	 *
	 * @access public
	 * @return mixed
	 */

	public function shortcodes( $atts, $content = '' ) {

		if( ( is_array( $atts ) && in_array( 'logged_out', $atts ) ) || $atts == 'logged_out' ) {
			$atts['logged_out'] = true;
		}

		$atts = shortcode_atts( array(
			'tag'    		=> '',
			'not'    		=> '',
			'method' 		=> '',
			'logged_out'	=> false
		), $atts, 'wpf' );

		// Hide content for non-logged in users
		if ( ! is_user_logged_in() && $atts['logged_out'] == false) {
			return false;
		}

		$user_tags = wp_fusion()->user->get_tags();

		$proceed_tag = false;
		$proceed_not = false;

		if ( ! empty( $atts['tag'] ) ) {

			$tags       = array();
			$tags_split = explode( ',', $atts['tag'] );

			// Get tag IDs where needed
			foreach ( $tags_split as $tag ) {
				if ( is_numeric( $tag ) ) {
					$tags[] = $tag;
				} else {
					$tags[] = wp_fusion()->user->get_tag_id( trim( $tag ) );
				}
			}

			foreach ( $tags as $tag ) {

				if ( in_array( $tag, $user_tags ) ) {
					$proceed_tag = true;

					if ( $atts['method'] == 'any' ) {
						break;
					}

				} else {
					$proceed_tag = false;

					if ( $atts['method'] != 'any' ) {
						break;
					}
				}
			}

			// If we're overriding
			if ( $current_filter = get_query_var( 'wpf_tag' ) ) {
				if ( in_array( $current_filter, $tags ) ) {
					$proceed_tag = true;
				}
			}

		} else {
			$proceed_tag = true;
		}


		if ( ! empty( $atts['not'] ) ) {

			$tags       = array();
			$tags_split = explode( ',', $atts['not'] );

			// Get tag IDs where needed
			foreach ( $tags_split as $tag ) {
				if ( is_numeric( $tag ) ) {
					$tags[] = $tag;
				} else {
					$tags[] = wp_fusion()->user->get_tag_id( trim( $tag ) );
				}
			}

			foreach ( $tags as $tag ) {
				if ( in_array( $tag, $user_tags ) ) {
					$proceed_not = false;
					break;
				} else {
					$proceed_not = true;
				}
			}

			// If we're overriding
			if ( $current_filter = get_query_var( 'wpf_tag' ) ) {
				if ( in_array( $current_filter, $tags ) ) {
					return false;
				}
			}

		} else {
			$proceed_not = true;
		}

		// Check for else condition
		if ( preg_match('/(?<=\[else\]).*(?=\[\/else])/s', $content, $else_content) ) {
			$else_content = $else_content[0];
			$content = preg_replace('/\[else\].*\[\/else]/s', '', $content );
		}

		if( $proceed_tag == true && $proceed_not == true ) {
			$can_access = true;
		} else {
			$can_access = false;
		}

		global $post;

		// If admins are excluded from restrictions
		if ( wp_fusion()->settings->get( 'exclude_admins' ) == true && current_user_can( 'manage_options' ) ) {
			$can_access = true;
		}

		$can_access = apply_filters( 'wpf_user_can_access', $can_access, get_current_user_id(), $post->ID, $tags_split );

		if ( $can_access == true ) {

			return do_shortcode( shortcode_unautop( $content ) );

		} elseif ( ! empty( $else_content ) ) {

			return do_shortcode( shortcode_unautop( $else_content ) );

		}

	}


	/**
	 * Update tags shortcode
	 *
	 * @access public
	 * @return null
	 */

	public function shortcode_update_tags( $atts ) {

		if ( is_user_logged_in() && ! is_admin() ) {
			wp_fusion()->user->get_tags( get_current_user_id(), true );
		}

	}

	/**
	 * Update meta data shortcode
	 *
	 * @access public
	 * @return null
	 */

	public function shortcode_update_meta( $atts ) {

		if ( is_user_logged_in() && ! is_admin() ) {
			wp_fusion()->user->pull_user_meta( get_current_user_id() );
		}

	}

	/**
	 * Show a piece of user meta
	 *
	 * @access public
	 * @return string
	 */

	public function shortcode_user_meta( $atts, $content = null ) {

		$atts = shortcode_atts( array('field' => '', 'date-format' => '', 'format' => ''), $atts );

		if ( empty( $atts['field'] ) ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			return do_shortcode( $content );
		}

		if ( $atts['field'] == 'user_id' ) {
			$atts['field'] = 'ID';
		}

		$user_data = get_userdata( get_current_user_id() );

		if( is_object($user_data) && property_exists( $user_data->data, $atts['field'] ) ) {

			$value = $user_data->data->{$atts['field']};

		} else {

			$value = get_user_meta( get_current_user_id(), $atts['field'], true );

		}

		$value = apply_filters( 'wpf_user_meta_shortcode_value', $value, $atts['field'] );

		if( ! empty( $atts['date-format'] ) && ! empty( $value ) ) {

			if( is_numeric( $value ) ) {

				$value = date( $atts['date-format'], $value );

			} else {

				$value = date( $atts['date-format'], strtotime( $value ) );

			}

		}

		if ( $atts['format'] == 'ucwords' ) {
			$value = ucwords( $value );
		}

		if( empty( $value ) ) {
			return do_shortcode($content);
		} else {
			return $value;
		}

	}

	/**
	 * Show content only for logged in users 
	 *
	 * @access public
	 * @return string Content
	 */

	public function shortcode_loggedin( $atts, $content = null ) {

		if ( ( is_user_logged_in() && ! is_null( $content ) ) || is_feed() ) {
			return do_shortcode( $content );
		}

	}


	/**
	 * Show content only for logged out users 
	 *
	 * @access public
	 * @return string Content
	 */

	public function shortcode_loggedout( $atts, $content = null ) {

		if ( ( ! is_user_logged_in() && ! is_null( $content ) ) || is_feed() ) {
			return do_shortcode( $content );
		}

	}

}

new WPF_Shortcodes;