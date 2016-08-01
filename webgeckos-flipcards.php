<?php
/*
* Plugin Name: WebGeckos Flipcards
* Version: 1.0
* Plugin URI: http://www.webgeckos.com
* Description: Extending WordPress and Layers with Flipcards Widget
* Author: Danijel Rose
* Author URI: http://www.webgeckos.com/
*
* Requires at least: 4.5
* Tested up to: 4.5.3
*
* Layers Plugin: True
* Layers Required Version: 1.5.4
*
* Text Domain: geckos-kit
* Domain Path: /lang/
*/
/*  Copyright 2016  Danijel Rose  (email : info@webgeckos.com)

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
/*
    1. ENQUEUE FILES
    2. ENQUEUE SCRIPTS
    3. HOOKS
    4. SHORTCODES
    5. CUSTOM POST TYPES
    6. CUSTOM TAXONOMIES
    7. CUSTOM FIELDS / META BOXES
*/
if ( ! defined( 'ABSPATH' ) ) exit;

/*
    1. ENQUEUE FILES
*/

require_once(plugin_dir_path(__FILE__) . '/widgets/geckos_flipcards_widget.php');

/*
    2. ENQUEUE SCRIPTS
*/

function geckos_add_scripts(){
  wp_enqueue_style('geckos-style', plugins_url('/css/geckos-style.css', __FILE__) );
  wp_enqueue_script('geckos-scripts', plugin_dir_url(__FILE__) . 'js/geckos-scripts.js', array('jquery'));
}

function geckos_admin_scripts() {
		wp_enqueue_script('media-upload'); // script already registered in WP, needs to be enqueued
		wp_enqueue_script('thickbox'); // script already registered in WP, needs to be enqueued
		wp_enqueue_script('upload_media', plugin_dir_url(__FILE__) . 'js/geckos-upload-media.js', array('jquery'));
    wp_enqueue_script( 'wp-color-picker-alpha', plugin_dir_url(__FILE__) . 'js/wp-color-picker-alpha.js', array( 'wp-color-picker' ) );

		wp_enqueue_style('thickbox'); // styles for media upload
    wp_enqueue_style( 'wp-color-picker' ); // styles for color picker
}

/*
    3. HOOKS
*/

// hook to enqueue scripts
add_action('wp_enqueue_scripts', 'geckos_add_scripts');

// load custom post types and custom taxonomies
add_action('init', 'geckos_cpt');

// enqueue required scripts for media upload
add_action('admin_enqueue_scripts', 'geckos_admin_scripts');

/*
    5. CUSTOM POST TYPES
*/

if ( ! function_exists( 'geckos_cpt' ) ) :

  function geckos_cpt() {

  register_post_type('geckos-flipcards', array(
      'labels'        => array(
              'name' => __( 'Flipcards', 'geckos-kit' ),
              'singular_name' => __( 'Flipcard', 'geckos-kit' )
          ),
      'public'        => true,
      'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
      'menu_icon'     => 'dashicons-images-alt2',
      'has_archive'   => true,
      'show_in_menu'  => true,
      'taxonomies'    => array( 'flipcards-categories' )
  ));

/*
    6. CUSTOM TAXONOMIES
*/

  register_taxonomy('flipcards-categories', 'geckos-flipcards', array(
      'labels' =>
          array(
              'name' => __( 'Flipcards Categories', 'geckos-kit' ),
              'singular_name' => __( 'Flipcards Category', 'geckos-kit' ),
              'add_new_item' => __( 'Add New Flipcards Category', 'geckos-kit' ),
              'edit_item' => __( 'Edit Flipcards Category', 'geckos-kit' ),
              'update_item' => __( 'Update Flipcards Category', 'geckos-kit' )
          ),
      'hierarchical' => true,
      'show_admin_column' => true
  ));

  }
endif;
