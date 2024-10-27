<?php 
/*
Plugin Name: Are You a Human PlayThru extension for Contact Form 7
Plugin URI: http://areyouahuman.com/
Description: PlayThru is now available for Contact Form 7 integration.
Author: Are You A human
Author URI: http://www.areyouahuman.com/
Version: 1.0.1
*/

/*  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
define('ALLOW_INCLUDE', true);

require_once('includes/ayah.class.php');

define('ASD_PLUGIN_FILE', __FILE__ );

$cf7_ayah = new CF7ayah('ayah_options', 'cf7ayahext');
function wpcf7_ayah_shortcode_handler($tag) {
		//require("wp-content/plugins/are-you-a-human/ayah.php");
		error_log(getcwd());
		
		$type = $tag['type'];
		$name = $tag['name'];
		$options = (array) $tag['options'];
		$values = (array) $tag['values'];
		
		
		foreach ( $options as $option ) {
			if ( preg_match( '%^id:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) {
				$id_att = $matches[1];
	
			} elseif ( preg_match( '%^class:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) {
				$class_att .= ' ' . $matches[1];
	
			} elseif ( preg_match( '%^([0-9]*)[/x]([0-9]*)$%', $option, $matches ) ) {
				$size_att = (int) $matches[1];
				$maxlength_att = (int) $matches[2];
	
			} elseif ( preg_match( '%^tabindex:(\d+)$%', $option, $matches ) ) {
				$tabindex_att = (int) $matches[1];
	
			}
		}
	
		ayah_get_options();

		//Make a new integration library object
		$ayah = ayah_load_library();
		
		//Add some CSS that we use for every form
		//echo ayah_css();
		
		//Insert the game markup
		$publisher_html = $ayah->getPublisherHTML();
		
		$html = '<span class="wpcf7-form-control-wrap ' . $name . '" id="' . $name . '">' . $publisher_html . '</span>';

		return $html;
}
add_shortcode( 'ayah', 'wpcf7_ayah_shortcode_handler');

register_activation_hook( __FILE__ , array($cf7_ayah, 'activate'));


?>