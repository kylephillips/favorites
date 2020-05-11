<?php
/*
Plugin Name: Favorites
Plugin URI: http://favoriteposts.com
Description: Simple and flexible favorite buttons for any post type.
Version: 2.3.2
Author: Kyle Phillips
Author URI: https://github.com/kylephillips
Text Domain: favorites
Domain Path: /languages/
License: GPLv2 or later.
Copyright: Kyle Phillips
*/

/*  Copyright 2019 Kyle Phillips

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
* Check Wordpress and PHP versions before instantiating plugin
*/
register_activation_hook( __FILE__, 'favorites_check_versions' );

define( 'FAVORITES_PLUGIN_FILE', __FILE__ );

function favorites_check_versions( $wp = '3.9', $php = '5.3.2' ) {
    global $wp_version;
    if ( version_compare( PHP_VERSION, $php, '<' ) ) $flag = 'PHP';
    elseif ( version_compare( $wp_version, $wp, '<' ) ) $flag = 'WordPress';
    else return;
    $version = 'PHP' == $flag ? $php : $wp;
    
    if (function_exists('deactivate_plugins')){
        deactivate_plugins( basename( __FILE__ ) );
    }
    
    wp_die('<p>The <strong>Favorites</strong> plugin requires'.$flag.'  version '.$version.' or greater.</p>','Plugin Activation Error',  array( 'response'=>200, 'back_link'=>TRUE ) );
}

if( !class_exists('Bootstrap') ) :
    favorites_check_versions();
    require_once(__DIR__ . '/vendor/autoload.php');
    require_once(__DIR__ . '/app/Favorites.php');
    require_once(__DIR__ . '/app/API/functions.php');
    Favorites::init();
endif;