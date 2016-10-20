<?php
/**
 * Plugin Name:  MyArcadePlugin Lite
 * Plugin URI:   http://myarcadeplugin.com
 * Description:  WordPress Arcade Plugin
 * Version:      5.1.0
 * Author:       Daniel Bakovic
 * Author URI:   http://myarcadeplugin.com
 * Requires at least: 4.0
 * Tested up to: 4.3
 */

/**
 * Copyright @ Daniel Bakovic - contact@myarcadeplugin.com
 *
 * Do not sell! Do not distribute! Check our license Terms!
 * http://myarcadeplugin.com/tos-agb/
 */

/**
 * Init MyArcadePlugin when WordPress initializes
 *
 * @version 5.15.0
 * @access  public
 * @return  void
 */
function myarcade_init() {
  global $myarcade_distributors, $myarcade_game_type_custom;

  // Set required constants
  myarcade_initial_constants();

  // Load localization
  load_plugin_textdomain( 'myarcadeplugin', false, dirname( plugin_basename(__FILE__) ) . '/lang');

  // Include required files
  myarcade_includes();

  add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'myarcade_action_links' );

  add_action('admin_notices', 'myarcade_notices');

  // Set default distributors and custom game types
  myarcade_set_distributors();
  myarcade_set_game_type_custom();
}
add_action( 'init', 'myarcade_init' );

/**
 * Defines initial constants
 *
 * @version 5.19.0
 * @return  void
 */
function myarcade_initial_constants() {

  // Define MyArcadePlugin version
  define('MYARCADE_VERSION', '5.1.0');

  // You need at least PHP Version 5.3.0+ to run this plugin
  define('MYARCADE_PHP_VERSION', '5.3.0');

  // Set download directories for backward compatibility
  $upload_dir = myarcade_upload_dir();
  $base = basename( $upload_dir['baseurl'] );

  // Download Folders
  define('MYARCADE_GAMES_DIR',  $base . '/games/');
  define('MYARCADE_THUMBS_DIR', $base . '/thumbs/');

  // Define needed table constants for backward compatibility
  global $wpdb;
  define('MYARCADE_GAME_TABLE',      $wpdb->prefix.'myarcadegames');
  define('MYARCADE_SETTINGS_TABLE',  $wpdb->prefix.'myarcadesettings');
  define('MYARCADE_SCORES_TABLE',    $wpdb->prefix.'myarcadescores');
  define('MYARCADE_HIGHSCORES_TABLE',$wpdb->prefix.'myarcadehighscores');
  define('MYARCADE_MEDALS_TABLE',    $wpdb->prefix.'myarcademedals');
  define('MYARCADE_USER_TABLE',      $wpdb->prefix.'myarcadeuser');

  // Define the plugins abs path
  $dirname = basename( dirname( __FILE__ ) );
  define('MYARCADE_DIR',        WP_PLUGIN_DIR     . '/' . $dirname );
  define('MYARCADE_CORE_DIR',   MYARCADE_DIR      . '/core');
  define('MYARCADE_JS_DIR',     MYARCADE_CORE_DIR . '/js');
  define('MYARCADE_URL',        WP_PLUGIN_URL     . '/' . $dirname );
  define('MYARCADE_CORE_URL',   MYARCADE_URL      . '/core');
  define('MYARCADE_MODULE_URL', MYARCADE_URL      . '/modules');
  define('MYARCADE_JS_URL',     MYARCADE_CORE_URL . '/js');

  define('MYARCADE_PLUGIN_SLUG', basename( dirname( __FILE__ ) ) );
}

/**
 * Include required files used in admin and on the frontend.
 *
 * @version 5.15.0
 * @return  void
 */
function myarcade_includes() {

  require_once 'core/debug.php';
  require_once 'core/template.php';
  require_once 'core/game.php';
  require_once 'core/output.php';
  require_once 'core/user.php';

  // DO THIS ONLY ON BACKEND
  if ( is_admin() ) {
    //set_site_transient('update_plugins', null);
    require_once 'core/myarcade_admin.php';
  }

  // Do this on the backend and on cron triggers
  if ( is_admin() ||  defined('MYARCADE_DOING_ACTION') || defined('DOING_CRON') ) {
    require_once 'core/feedback.php';
    require_once 'core/addgames.php';
    require_once 'core/file.php';
  }
}

/**
 * Set default game distributors
 *
 * @version 5.19.0
 * @return  void
 */
function myarcade_set_distributors() {
  global $myarcade_distributors;

  // Set default game distributors
  $myarcade_distributors = apply_filters( 'myarcade_game_distributors', array(
      'twopg'         => '2 Player Games',
      'famobi'        => 'Famobi',
      'gamepix'       => 'GamePix',
      'myarcadefeed'  => 'MyArcadeFeed',
      'softgames'     => 'Softgames',
      'spilgames'     => 'Spil Games',
      'unityfeeds'    => 'UnityFeeds',
      'agf'           => '- PRO - Arcade Game Feed',
      'bigfish'       => '- PRO - Big Fish Games',
      'fgd'           => '- PRO - FlashGameDistribution',
      'fog'           => '- PRO - FreeOnlineGames',
      'gamefeed'      => '- PRO - GameFeed',
      'htmlgames'     => '- PRO - HTML Games',
      'kongregate'    => '- PRO - Kongregate',
      'plinga'        => '- PRO - Plinga',
      'scirra'        => '- PRO - Scirra',
    )
  );
}

/**
 * Set default custom game types
 *
 * @version 5.15.0
 * @return  void
 */
function myarcade_set_game_type_custom() {
  global $myarcade_game_type_custom;

  // Set default game types
  $myarcade_game_type_custom = array(
    'embed'     => __( "Embed Code", 'myarcadeplugin' ),
    'custom'    => __( "Flash (SWF)", 'myarcadeplugin' ),
    'iframe'    => __( "Iframe URL", 'myarcadeplugin' ),
    'ibparcade' => __( "- PRO - IBPArcade Game", 'myarcadeplugin' ),
    'phpbb'     => __( "- PRO - PHPBB Game", 'myarcadeplugin' ),
    'dcr'       => __( "- PRO - Shochwave (DCR)", 'myarcadeplugin' ),
    'unity'     => __( "- PRO - Unity", 'myarcadeplugin' ),
  );
}

/**
 * Load game import handler
 *
 * @version 5.13.0
 * @return  void
 */
function myarcade_import_handler() {
  require_once( MYARCADE_CORE_DIR . '/admin/import_handler.php');
}
add_action('wp_ajax_myarcade_import_handler', 'myarcade_import_handler');

/**
 * Load frontend scripts.
 * Loads SWFObject if activated on MyArcadePlugin options.
 *
 * @version 5.13.0
 * @return  void
 */
function myarcade_frontend_scripts() {
  if ( is_admin() || ! is_single() || ! is_game() ) {
    return;
  }

  $general = get_option( 'myarcade_general' );

  if ( isset( $general['swfobject']) && $general['swfobject'] ) {
    wp_enqueue_script( 'swfobject' );
  }
}
add_action( 'wp_print_scripts', 'myarcade_frontend_scripts' );

/**
 * Defines MyArcadePlugin cron intervals
 *
 * @version 5.13.0
 * @access  public
 * @return  arrray cron schedule intervals
 */
function myarcade_get_cron_intervals() {
  // Set MyArcadePlugin cron intervals
  return apply_filters('myarcade_cron_intervals', array(
      '1minute'   => array( 'interval' => 60,  'display' => __('1 Minute', 'myarcadeplugin') ),
      '5minutes'  => array( 'interval' => 300, 'display' => __('5 Minutes',  'myarcadeplugin') ),
      '10minutes' => array( 'interval' => 600, 'display' => __('10 Minutes', 'myarcadeplugin') ),
      '15minutes' => array( 'interval' => 900, 'display' => __('15 Minutes', 'myarcadeplugin') ),
      '30minutes' => array( 'interval' => 1800,'display' => __('30 Minutes', 'myarcadeplugin') ),
      'weekly'    => array( 'interval' => 604800, 'display' => __('Once Weekly', 'myarcadeplugin') ),
    )
  );
}

/**
 * Exstends the WP cron function
 *
 * @version 5.13.0
 * @param  array $schedules WordPress schedules
 * @return array
 */
function myarcade_extend_cron( $schedules ) {

  $myarcade_cron_intervals = myarcade_get_cron_intervals();

  // Add MyArcadePlugin cron intervals
  foreach( $myarcade_cron_intervals as $key => $value ) {
    $schedules[$key] = $value;
  }

  return $schedules;
}
add_filter('cron_schedules', 'myarcade_extend_cron');

/**
 * Call MyArcadePlugin install function
 *
 * @version 5.15.0
 * @access  public
 * @param   bool $network_wide TRUE if this is a network activation (multisite)
 * @return  void
 */
function myarcade_do_install( $network_wide = FALSE ) {
  global $wpdb;

  if ( ! defined('MYARCADE_VERSION') ) {
    myarcade_initial_constants();
  }

  myarcade_includes();

  require_once( MYARCADE_CORE_DIR . '/myarcade_setup.php' );

  myarcade_install();
}
register_activation_hook( __FILE__, 'myarcade_do_install' );

/**
 * Call MyArcadePlugin uninstall function
 *
 * @version 5.15.0
 * @access  public
 * @param   bool $network_wide TRUE if this is a network activation (multisite)
 * @return  void
 */
function myarcade_do_uninstall( $network_wide = FALSE ) {
  global $wpdb;

  require_once( MYARCADE_CORE_DIR . '/myarcade_setup.php' );

  myarcade_uninstall();
}
register_deactivation_hook( __FILE__, 'myarcade_do_uninstall' );

/**
 * Add MyArcade action links on plugins page
 *
 * @version 5.15.0
 * @param   array $links Default links
 * @return  array links
 */
function myarcade_action_links( $links ) {
  $plugin_links = array(
    '<a href="' . admin_url( 'admin.php?page=myarcade-edit-settings' ) . '">' . __( 'Settings', 'myarcadeplugin' ) . '</a>',
    '<a href="http://myarcadeplugin.com/documentation/">' . __( 'Docs', 'myarcadeplugin' ) . '</a>',
    '<a href="http://myarcadeplugin.com/forum/">' . __( 'Premium Support', 'myarcadeplugin' ) . '</a>',
  );

  return array_merge( $plugin_links, $links );
}

/**
 * Get MyArcade upload directories
 *
 * @version 5.15.0
 * @return  array Upload directories (absolute and url)
 */
function myarcade_upload_dir() {

  $wp_upload_dir = wp_upload_dir();

  $games_base   = 'games';
  $thumbs_base  = 'thumbs';

  /**
   * Example
   * 'basedir' => string 'C:\xampp\htdocs\myarcadeplugin5/wp-content/uploads/'
   * 'baseurl' => string 'http://myarcadeplugin5.loc/wp-content/uploads/'
   * 'gamesdir' => string 'C:\xampp\htdocs\myarcadeplugin5/wp-content/uploads/games/'
   * 'gamesurl' => string 'http://myarcadeplugin5.loc/wp-content/uploads/games/'
   * 'gamesbase' => string 'uploads/games/'
   * 'thumbsdir' => string 'C:\xampp\htdocs\myarcadeplugin5/wp-content/uploads/thumbs/'
   * 'thumbsurl' => string 'http://myarcadeplugin5.loc/wp-content/uploads/thumbs/'
   * 'thumbsbase' => string 'uploads/thumbs/'
   */

  $upload_dir = array(
    'basedir'   => $wp_upload_dir['basedir'] . '/',
    'baseurl'   => $wp_upload_dir['baseurl'] . '/',
    'gamesdir'  => $wp_upload_dir['basedir'] . '/' . $games_base . '/',
    'gamesurl'  => $wp_upload_dir['baseurl'] . '/' . $games_base . '/',
    'gamesbase' => basename( $wp_upload_dir['baseurl'] ) . '/' .$games_base . '/',
    'thumbsdir' => $wp_upload_dir['basedir'] . '/' . $thumbs_base . '/',
    'thumbsurl' => $wp_upload_dir['baseurl'] . '/' . $thumbs_base . '/',
    'thumbsbase' => basename( $wp_upload_dir['baseurl'] ) . '/' .$thumbs_base . '/',
  );

  return apply_filters( 'myarcade_upload_dir', $upload_dir );
}

/**
 * MyArcadePlugin Premium Hint
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_premium_img() {
  echo '<img src="'.MYARCADE_URL.'/images/locked.png" alt="Pro Version Only!" title="Pro Version Only!" />';
}
/**
 * MyArcadePlugin Upgrade Hint
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_premium_message( $class = 'mabp_800') {
  ?>
  <div class="mabp_info <?php echo $class; ?>">
    <?php myarcade_premium_img() ?> Please consider upgrading to <a href="http://myarcadeplugin.com" title="Upgrade">MyArcadePlugin Pro</a> if you want to use this feature.
  </div>
  <?php
}

/**
 * Locate and include distributor's integration file
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_distributor_integration( $key ) {

  $distributor_file = apply_filters( 'myarcade_distributor_integration', MYARCADE_CORE_DIR . '/feeds/' . $key . '.php', $key );

  if ( file_exists( $distributor_file ) ) {
    include_once( $distributor_file );
  }
}

/**
 * Get distributor's settings
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  array Settings
 */
function myarcade_get_settings( $key ) {
  global $myarcade_distributors;

  $settings = get_option( 'myarcade_' . $key );

  if ( ! $settings ) {

    // Load distributors if not already loaded..
    if ( empty( $myarcade_distributors ) ) {
      myarcade_set_distributors();
    }

    if ( array_key_exists( $key, $myarcade_distributors ) ) {
      // Default settings function
      $settings_function = 'myarcade_default_settings_' . $key;

      if ( function_exists( $settings_function ) ) {
        $settings = $settings_function();
      }
      else {
        // Function doesn't exist. Try to find the distributor integration file
        $distributor_file = apply_filters( 'myarcade_distributor_integration', MYARCADE_CORE_DIR . '/feeds/' . $key . '.php', $key );

        if ( file_exists( $distributor_file ) ) {
          include_once( $distributor_file );

          if ( function_exists( $settings_function ) ) {
            $settings = $settings_function();
          }
        }
      }
    }
  }

  if ( ! $settings ) {
    $settings = array();
  }

  return $settings;
}
?>