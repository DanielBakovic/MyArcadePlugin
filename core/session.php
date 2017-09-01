<?php
/**
 * Session functions
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
 * Start a new session
 *
 * @version 5.21.1
 * @access  public
 * @return  void
 */
function myarcade_start_session() {

  $current_user = wp_get_current_user();

  // --- SESSION START -- //
  $id = session_id();

  if ( empty($id) ) {
    @session_start();

    $_SESSION['uuid'] = $current_user->ID;

    if ( !isset($_SESSION['plays']) ) {
      $_SESSION['plays'] = 0;
    }

    if ( !isset($_SESSION['last_play']) ) {
      $_SESSION['last_play'] = 0;
    }
  }
  else {
    $_SESSION['uuid'] = $current_user->ID;
  }
}

/**
 * Logout Handle - End user session
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_end_session() {
  if ( session_id() ) {
    @session_destroy();
  }
}
add_action('wp_logout', 'myarcade_end_session');

/**
 * Retrieve a session variable
 *
 * @version 5.13.0
 * @access  public
 * @param   string $var Var name that should retrieved
 * @return  mixed Var value
 */
function myarcade_session_get( $var ) {
  if ( isset( $_SESSION[$var]) ) {
    return $_SESSION[$var];
  }
  else {
    return false;
  }
}

/**
 * Set a session variable
 *
 * @version 5.13.0
 * @access  public
 * @param   string $var   Var name
 * @param   mixed $value Var value
 * @return  void
 */
function myarcade_session_set($var, $value) {
  $_SESSION[$var] = $value;
}
?>