<?php
/**
 * Enqueue Styles
 *
 * @package     TimJensen\WP_Hangman
 * @author      Tim Jensen <tim@timjensen.us>
 * @license     GNU General Public License 2.0+
 * @link        https://www.timjensen.us
 * @since       0.1.0
 */

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_styles' );
/**
 * Enqueues the front end styles.
 *
 * @since 0.1.0
 *
 * @return void
 */
function enqueue_styles() {
	$suffix = ( defined( 'STYLE_DEBUG' ) && STYLE_DEBUG ) ? '' : '.min';
	wp_register_style( 'wp-hangman-styles', WP_HANGMAN_ASSETS_URL . "/style{$suffix}.css", null, '0.2.1' );
	wp_register_style( 'wp-hangman-font', '//fonts.googleapis.com/css?family=Ek+Mukta', null, '0.2.1' );
}


