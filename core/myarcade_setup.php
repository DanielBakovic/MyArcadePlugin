<?php
/**
 * Install / Update functions
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

/**
 * Installation function
 *
 * @version 5.30.0
 * @access  public
 * @return  void
 */
function myarcade_install() {
  global $wpdb, $wp_version;

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

  $collate = '';

  if ( ! empty( $wpdb->charset ) ) {
    $collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
  }

  if ( ! empty( $wpdb->collate ) ) {
    $collate .= " COLLATE {$wpdb->collate}";
  }

  // Check if games table exisits
  if ($wpdb->get_var("show tables like '".$wpdb->prefix . 'myarcadegames'."'") != $wpdb->prefix . 'myarcadegames') {

    // Create new games table
    $sql = "CREATE TABLE `".$wpdb->prefix . 'myarcadegames'."` (
      `id` int(11) NOT NULL auto_increment,
      `postid` int(11) DEFAULT NULL,
      `uuid` text collate utf8_unicode_ci NOT NULL,
      `game_tag` text collate utf8_unicode_ci NOT NULL,
      `game_type` text collate utf8_unicode_ci NOT NULL,
      `name` text collate utf8_unicode_ci NOT NULL,
      `slug` text collate utf8_unicode_ci NOT NULL,
      `categories` text collate utf8_unicode_ci NOT NULL,
      `description` text collate utf8_unicode_ci NOT NULL,
      `tags` text collate utf8_unicode_ci NOT NULL,
      `instructions` text collate utf8_unicode_ci NOT NULL,
      `controls` text collate utf8_unicode_ci NOT NULL,
      `rating` text collate utf8_unicode_ci NOT NULL,
      `height` text collate utf8_unicode_ci NOT NULL,
      `width` text collate utf8_unicode_ci NOT NULL,
      `thumbnail_url` text collate utf8_unicode_ci NOT NULL,
      `swf_url` text collate utf8_unicode_ci NOT NULL,
      `screen1_url` text collate utf8_unicode_ci NOT NULL,
      `screen2_url` text collate utf8_unicode_ci NOT NULL,
      `screen3_url` text collate utf8_unicode_ci NOT NULL,
      `screen4_url` text collate utf8_unicode_ci NOT NULL,
      `video_url`   text collate utf8_unicode_ci NOT NULL,
      `created` text collate utf8_unicode_ci NOT NULL,
      `leaderboard_enabled` text collate utf8_unicode_ci NOT NULL,
      `highscore_type` text collate utf8_unicode_ci NOT NULL,
      `score_bridge` text collate utf8_unicode_ci NOT NULL,
      `coins_enabled` text collate utf8_unicode_ci NOT NULL,
      `status` text collate utf8_unicode_ci NOT NULL,
      PRIMARY KEY  (`id`)
    ) $collate;";

    dbDelta($sql);
  }

  // Check if the table needs to be upgraded..
  myarcade_upgrade_games_table();

  // Upgrade general settings
  myarcade_update_general_settings();

  // Update distributor settings
  myarcade_update_distributor_settings();

  // Update categories
  myarcade_update_categories();

  // Check for upgrade to the new settings structure
  if ( ! get_option('myarcade_version') ) {
    update_option('myarcade_version', MYARCADE_VERSION);
  }
  else {
    // version information exists.. regular upgrade
    if ( get_option('myarcade_version') != MYARCADE_VERSION ) {
      set_transient('myarcade_settings_update_notice', true, 60*60*24*30 ); // 30 days
    }
    update_option('myarcade_version', MYARCADE_VERSION);
  }

  // Check if scores table exisits
  if ($wpdb->get_var("show tables like '".$wpdb->prefix.'myarcadescores'."'") != $wpdb->prefix.'myarcadescores') {

    $sql = "CREATE TABLE `".$wpdb->prefix.'myarcadescores'."` (
      `id`        int(11) NOT NULL auto_increment,
      `session`   text collate utf8_unicode_ci NOT NULL,
      `date`      text collate utf8_unicode_ci NOT NULL,
      `datatype`  text collate utf8_unicode_ci NOT NULL,
      `game_tag`  text collate utf8_unicode_ci NOT NULL,
      `user_id`   text collate utf8_unicode_ci NOT NULL,
      `score`     text collate utf8_unicode_ci NOT NULL,
      `sortorder` text collate utf8_unicode_ci NOT NULL,
      PRIMARY KEY  (`id`)
    ) $collate;";

    dbDelta($sql);
  }
  else {
    // Table already exists..
    // Check if the table needs to be upgraded..
    myarcade_upgrade_scores_table();
  }

  if ($wpdb->get_var("show tables like '".$wpdb->prefix.'myarcadehighscores'."'") != $wpdb->prefix.'myarcadehighscores') {

    $sql = "CREATE TABLE `".$wpdb->prefix.'myarcadehighscores'."` (
      `id`        INT(11) NOT NULL auto_increment,
      `game_tag`  text collate utf8_unicode_ci NOT NULL,
      `user_id`   text collate utf8_unicode_ci NOT NULL,
      `score`     text collate utf8_unicode_ci NOT NULL,
      PRIMARY KEY  (`id`)
    ) $collate;";

    dbDelta($sql);
  }

  // Check if scores table exisits
  if ($wpdb->get_var("show tables like '".$wpdb->prefix.'myarcademedals'."'") != $wpdb->prefix.'myarcademedals') {

    $sql = "CREATE TABLE `".$wpdb->prefix.'myarcademedals'."` (
      `id`          int(11) NOT NULL auto_increment,
      `date`        text collate utf8_unicode_ci NOT NULL,
      `game_tag`    text collate utf8_unicode_ci NOT NULL,
      `user_id`     text collate utf8_unicode_ci NOT NULL,
      `score`       text collate utf8_unicode_ci NOT NULL,
      `name`        text collate utf8_unicode_ci NOT NULL,
      `description` text collate utf8_unicode_ci NOT NULL,
      `thumbnail`   text collate utf8_unicode_ci NOT NULL,
      PRIMARY KEY  (`id`)
    ) $collate;";

    dbDelta($sql);
  }

  // Check if game plays table exisits
  if ($wpdb->get_var("show tables like '".$wpdb->prefix.'myarcadeuser'."'") != $wpdb->prefix.'myarcadeuser') {

    $sql = "CREATE TABLE `".$wpdb->prefix.'myarcadeuser'."` (
      `id`          int(11) NOT NULL auto_increment,
      `user_id`     int(11) NOT NULL,
      `points`      int(11) NOT NULL DEFAULT '0',
      `plays`       int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY  (`id`)
    ) $collate;";

    dbDelta($sql);
  }

  // Create the stats table
  $sql = "CREATE TABLE {$wpdb->prefix}myarcade_plays (
    ID bigint(20) NOT NULL auto_increment,
    post_id bigint(20) NOT NULL,
    user_id bigint(20) default NULL,
    date datetime NOT NULL default '0000-00-00 00:00:00',
    duration bigint(20) default NULL,
    PRIMARY KEY  (ID),
    KEY post_id (post_id),
    KEY user_id (user_id)
  ) $collate;";

  dbDelta( $sql );

  myarcade_create_directories();

  // Add plugin installation date and variable for rating div
  add_option( 'myarcade_install_date', date('Y-m-d h:i:s') );

  // Add plugin tracking optin option
  add_option( 'myarcade_allow_tracking', 'unknown' );
  // Register tracker send event
  wp_schedule_event( time(), 'daily', 'myarcade_tracker_send_event' );
}

/**
 * Upgrade games table
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_upgrade_games_table() {
  global $wpdb;

  // Upgrade to 1.8
  $gametable_cols = $wpdb->get_col("SHOW COLUMNS FROM ".$wpdb->prefix . 'myarcadegames');

  if (!in_array('rating', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `rating` text collate utf8_unicode_ci NOT NULL
      AFTER `controls`
    ");
  }

  if (!in_array('game_tag', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `game_tag` text collate utf8_unicode_ci NOT NULL
      AFTER `uuid`
    ");
  }

  // Update to 2.0
  $gametable_cols = $wpdb->get_col("SHOW COLUMNS FROM ".$wpdb->prefix . 'myarcadegames');

  if (!in_array('postid', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `postid` text collate utf8_unicode_ci NOT NULL
      AFTER `id`
    ");
  }

  if (!in_array('screen1_url', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `screen1_url` text collate utf8_unicode_ci NOT NULL
      AFTER `swf_url`
    ");
  }

  if (!in_array('screen2_url', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `screen2_url` text collate utf8_unicode_ci NOT NULL
      AFTER `screen1_url`
    ");
  }

  if (!in_array('screen3_url', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `screen3_url` text collate utf8_unicode_ci NOT NULL
      AFTER `screen2_url`
    ");
  }

  if (!in_array('screen4_url', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `screen4_url` text collate utf8_unicode_ci NOT NULL
      AFTER `screen3_url`
    ");
  }

  if (!in_array('coins_enabled', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `coins_enabled` text collate utf8_unicode_ci NOT NULL
      AFTER `leaderboard_enabled`
    ");
  }

  // Upgrade to 2.60
  $gametable_cols = $wpdb->get_col("SHOW COLUMNS FROM ".$wpdb->prefix . 'myarcadegames');

  if (!in_array('game_type', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `game_type` text collate utf8_unicode_ci NOT NULL
      AFTER `game_tag`
    ");
  }

  // Upgrade to 4.00
  $gametable_cols = $wpdb->get_col("SHOW COLUMNS FROM ".$wpdb->prefix . 'myarcadegames');

  if (!in_array('video_url', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `video_url` text collate utf8_unicode_ci NOT NULL
      AFTER `screen4_url`
    ");
  }

  if (!in_array('highscore_type', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `highscore_type` text collate utf8_unicode_ci NOT NULL
      AFTER `leaderboard_enabled`
    ");
  }

  // Upgrade to 5.80
  $wpdb->query("ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."` CHANGE  `postid`  `postid` INT( 11 )");


  /// Upgrade to 5.14.0
  if (!in_array('score_bridge', $gametable_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix . 'myarcadegames'."`
      ADD `score_bridge` text collate utf8_unicode_ci NOT NULL
      AFTER `highscore_type`
    ");
  }
}

/**
 * Upgrade scores table
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_upgrade_scores_table() {
  global $wpdb;

  $settings_cols  = $wpdb->get_col("SHOW COLUMNS FROM ".$wpdb->prefix.'myarcadescores');

  // Upgrade to v4.00
  if (!in_array('session', $settings_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix.'myarcadescores'."`
      ADD `session` text collate utf8_unicode_ci NOT NULL
      AFTER `id`
    ");
  }

  if (!in_array('sortorder', $settings_cols)) {
    $wpdb->query("
      ALTER TABLE `".$wpdb->prefix.'myarcadescores'."`
      ADD `sortorder` text collate utf8_unicode_ci NOT NULL
      AFTER `score`
    ");
  }
}

/**
 * Upgrade the feed categories
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_upgrade_categories() {
  global $wpdb;

  // Include the feed game categories
  include_once 'feedcats.php';

  // Get current settings
  $myarcade_settings  = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix . 'myarcadesettings');
  $old_cat_settings = unserialize($myarcade_settings->game_categories);

  for ($i = 0; $i < count($feedcategories); $i++) {
    foreach ($old_cat_settings as $old_cat) {
      if ($old_cat['Name'] == $feedcategories[$i]['Name']) {
        // Save Category Status and Mapping to the new array
        $feedcategories[$i]['Status']  = $old_cat['Status'];
        $feedcategories[$i]['Mapping'] = $old_cat['Mapping'];
        // Go to the next category
        break;
      }
    }
  }

  // Update the categories
  $categories_str = serialize($feedcategories);
  $wpdb->query("UPDATE ".$wpdb->prefix . 'myarcadesettings'." SET game_categories = '".$categories_str."'");
}

/**
 * Upgrade general settings
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_update_general_settings() {

  $default_settings = MYARCADE_CORE_DIR.'/settings.php';

  if ( file_exists( $default_settings ) ) {
    include( $default_settings );
  }
  else {
    wp_die( 'Required configuration file not found!', 'Error: MyArcadePlugin Activation' );
  }

  // General Settings
  $myarcade_general = get_option('myarcade_general');

  if ( ! $myarcade_general ) {
    $myarcade_general = $myarcade_general_default;
  }
  else {
    // Upgrade settings
    foreach ( $myarcade_general_default as $setting => $val ) {
      if ( ! array_key_exists( $setting, $myarcade_general ) ) {
        $myarcade_general[ $setting ] = $val;
      }
    }
  }

  update_option( 'myarcade_general', $myarcade_general );
}

/**
 * Update distributor's settings
 *
 * @version 5.19.0
 * @return  void
 */
function myarcade_update_distributor_settings() {
  global $myarcade_distributors;

  // Load distributors if not already loaded..
  if ( empty( $myarcade_distributors ) ) {
    myarcade_set_distributors();
  }

  foreach ( $myarcade_distributors as $key => $name ) {
    // Default settings function
    $settings_function = 'myarcade_default_settings_' . $key;
    $default_settings = array();

    if ( function_exists( $settings_function ) ) {
      $default_settings = $settings_function();
    }
    else {
      // Function doesn't exist. Try to find the distributor integration file
      $distributor_file = apply_filters( 'myarcade_distributor_integration', MYARCADE_CORE_DIR . '/feeds/' . $key . '.php', $key );

      if ( file_exists( $distributor_file ) ) {
        include_once( $distributor_file );

        if ( function_exists( $settings_function ) ) {
          $default_settings = $settings_function();
        }
      }
    }

    // Get options
    $options = get_option( 'myarcade_' . $key );

    // Check if options exists
    if ( ! $options && ! empty( $default_settings ) ) {
      // Add new options
      add_option( 'myarcade_' . $key, $default_settings, '', 'no' );
    }
    else {
      // Options already exists. We need an update
      foreach ( $default_settings as $setting => $val ) {
        if ( ! array_key_exists( $setting, $options ) ) {
          $options[ $setting ] = $val;
        }
      }

      // Update settings
      update_option( 'myarcade_' . $key, $options );
    } // end if default sttings exists
  } // end foreach distributors
}

/**
 * Update category settings
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_update_categories() {

  // Include the feed game categories
  $catfile = MYARCADE_CORE_DIR.'/feedcats.php';

  if ( file_exists( $catfile ) ) {
    include( $catfile );
  }
  else {
    wp_die( 'Required configuration file not found!', 'Error: MyArcadePlugin Activation' );
  }

  // Get Categories
  $myarcade_categories = get_option('myarcade_categories');

  if ( false === $myarcade_categories ) {
    add_option('myarcade_categories', $feedcategories, '', 'no');
  }
  elseif ( empty( $myarcade_categories ) ) {
    update_option('myarcade_categories', $feedcategories);
  }
  else {
    // Upgrade Categories if needed
    for ($i = 0; $i < count($feedcategories); $i++) {
      foreach ($myarcade_categories as $old_cat) {
        if ($old_cat['Name'] == $feedcategories[$i]['Name']) {
          // Save Category Status and Mapping to the new array
          $feedcategories[$i]['Status']  = $old_cat['Status'];
          $feedcategories[$i]['Mapping'] = $old_cat['Mapping'];
          // Go to the next category
          break;
        }
      }
    }

    update_option('myarcade_categories', $feedcategories);
  }
}

/**
 * Uninstall MyArcadePlugin
 *
 * @version 5.30.0
 * @access  public
 * @return  void
 */
function myarcade_uninstall() {
  wp_clear_scheduled_hook( 'myarcade_tracker_send_event' );
}
?>