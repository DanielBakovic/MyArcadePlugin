<?php
/**
 * MyArcade Template Functions
 *
 * Functions used in the template files to output content
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @copyright 2009-2015 Daniel Bakovic
 * @license http://myarcadeplugin.com
 */

/**
 * Copyright @ Daniel Bakovic - contact@myarcadeplugin.com
 * Do not modify! Do not sell! Do not distribute! -
 * Check our license Terms!
 */

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

/**
 * Add MyArcade comment on the theme footer
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_comment() {
  echo "\n"."<!-- Powered by MyArcadePlugin Lite - http://myarcadeplugin.com -->"."\n";
}
add_action('wp_footer', 'myarcade_comment');

/**
 * Add MyArcade comment on theme header
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_generator_tag() {
  echo "\n" . '<meta name="generator" content="MyArcadePlugin Lite ' . esc_attr( MYARCADE_VERSION ) . '" />' . "\n";
}
add_action('wp_head', 'myarcade_generator_tag');
?>