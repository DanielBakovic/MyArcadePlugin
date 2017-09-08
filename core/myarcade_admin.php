<?php
/**
 * Admin functions
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

// Include Post Meta functions
require_once( MYARCADE_CORE_DIR . '/admin/admin-post-meta.php' );

/**
 * Register MyArcade menus
 *
 * @version 5.30.0
 * @access  public
 * @return  void
 */
function myarcade_admin_menu() {

  $general = get_option('myarcade_general');

  if ( $general && isset($general['allow_user']) && $general['allow_user'] ) {
    $permisssion = 'edit_posts';
  }
  else {
    $permisssion = 'manage_options';
  }

  add_menu_page('MyArcade', 'MyArcade', $permisssion , basename(__FILE__), 'myarcade_show_stats_page', MYARCADE_URL . '/assets/images/arcade.png', 55);
  add_submenu_page(basename(__FILE__), __('Dashboard', 'myarcadeplugin'), __('Dashboard', 'myarcadeplugin'), $permisssion, basename(__FILE__), 'myarcade_show_stats_page');

    add_submenu_page( basename(__FILE__),
                      __("Fetch Games", 'myarcadeplugin'),
                      __("Fetch Games", 'myarcadeplugin'),
                      'manage_options', 'myarcade-fetch', 'myarcade_fetch_page');

    $hookname = add_submenu_page( basename(__FILE__),
                      __("Import Games", 'myarcadeplugin'),
                      __("Import Games", 'myarcadeplugin'),
                      $permisssion, 'myarcade-import-games', 'myarcade_import_games_page');

      add_action('load-'.$hookname, 'myarcade_import_scripts');

    add_submenu_page( basename(__FILE__),
                      __("Publish Games", 'myarcadeplugin'),
                      __("Publish Games", 'myarcadeplugin'),
                      'manage_options', 'myarcade-publish-games',  'myarcade_publish_games_page');

    add_submenu_page( basename(__FILE__),
                      __("Manage Games", 'myarcadeplugin'),
                      __("Manage Games", 'myarcadeplugin'),
                      'manage_options', 'myarcade-manage-games', 'myarcade_manage_games_page');

    add_submenu_page( basename(__FILE__),
                      __("Manage Scores", 'myarcadeplugin'),
                      __("Manage Scores", 'myarcadeplugin'),
                      'manage_options', 'myarcade-manage-scores', 'myarcade_manage_scores_page');

    add_submenu_page( basename(__FILE__),
                      __("Statistics", 'myarcadeplugin'),
                      __("Statistics", 'myarcadeplugin'),
                      'manage_options', 'myarcade-stats', 'myarcade_stats_page');

  add_submenu_page( basename(__FILE__),
                    __("Settings"),
                    __("Settings"),
                    'manage_options', 'myarcade-edit-settings', 'myarcade_settings_page');
}
add_action('admin_menu', 'myarcade_admin_menu', 9);

/**
 * Display the bulk game publishing page
 *
 * @version 5.13.0
 * @return void
 */
function myarcade_publish_games_page() {
  include_once( MYARCADE_CORE_DIR . '/admin/admin-publish.php' );
  myarcade_publish_games();
}

/**
 * Display the fetch games page
 *
 * @version 5.13.0
 * @return void
 */
function myarcade_fetch_page() {
  include_once( MYARCADE_CORE_DIR . '/admin/admin-fetch.php' );
  myarcade_fetch();
}

/**
 * Display the import games page
 *
 * @version 5.13.0
 * @return  void
 */
function myarcade_import_games_page() {
  include_once( MYARCADE_CORE_DIR . '/admin/admin-import.php' );
  myarcade_import_games();
}

/**
 * Displays the dashboard page
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_show_stats_page() {
  include_once( MYARCADE_CORE_DIR . '/admin/admin-dashboard.php' );
  myarcade_show_stats();
}

/**
 * Display the settings page
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_settings_page() {
  include_once( MYARCADE_CORE_DIR . '/admin/admin-settings.php' );
  myarcade_settings();
}

/**
 * Display the manage games page
 *
 * @version 5.13.0
 * @access  public
 * @return  [type] [description]
 */
function myarcade_manage_games_page() {
  include_once( MYARCADE_CORE_DIR . '/admin/admin-manage.php' );
  myarcade_manage_games();
}

/**
 * [myarcade_manage_scores_page description]
 *
 * @version 5.13.0
 * @access  public
 * @return  [type] [description]
 */
function myarcade_manage_scores_page() {
  include_once( MYARCADE_CORE_DIR . '/admin/admin-manage-scores.php' );
  myarcade_manage_scores();
}

/**
 * Display the stats page
 *
 * @version 5.30.0
 * @return  void
 */
function myarcade_stats_page() {
  include_once( MYARCADE_CORE_DIR . '/admin/admin-stats.php' );
  myarcade_stats();
}

/**
 * Load game import scripts
 *
 * @version 5.29.1
 * @access  public
 * @return  void
 */
function myarcade_import_scripts() {
  wp_enqueue_script( 'myarcade-import', MYARCADE_URL . '/assets/js/import.js', array( 'jquery', 'jquery-form' ), false, true );

  wp_localize_script( 'myarcade-import', 'MyArcadeImport', array(
    'cannot_import' => __( "Error: Can not import that game!", 'myarcadeplugin' ),
    'game_missing'  => __( "No game file added!", 'myarcadeplugin' ),
    'thumb_missing' => __( "No thumbnail added!", 'myarcadeplugin' ),
    'name_missing'        => __( "Game name not set!", 'myarcadeplugin' ),
    'description_missing' => __( "There is no game description!", 'myarcadeplugin' ),
    'category_missing'    => __( "Select at least one category!", 'myarcadeplugin' ),
    'max_filesize_exceeded' => __( "ERROR: Max allowed file size exceeded!", 'myarcadeplugin' ),
    'error_string' => __( "Error:", 'myarcadeplugin' ),
    'rich_editing'  => get_user_option('rich_editing'),
    'allowed_size'  => myarcade_get_max_post_size_bytes(),
    )
  );
}

/**
 * Load required scripts
 *
 * @version 5.19.0
 * @access  public
 * @return  void
 */
function myarcade_admin_scripts() {
  global $pagenow;

  $screen = get_current_screen();

  if ($pagenow == 'post.php') {
    wp_register_script( 'myarcade_writepanel', MYARCADE_URL . '/assets/js/writepanel.js', array('jquery') );
    wp_enqueue_script('myarcade_writepanel');
  }

  if ( $pagenow == 'admin.php' ) {
    switch ( $screen->id ) {
      case 'myarcade_page_myarcade-publish-games': {
        wp_enqueue_script( 'jquery-ui-progressbar', MYARCADE_URL . '/assets/js/jquery.ui.progressbar.min.js', array( 'jquery-ui-core', 'jquery-ui-widget' ), '1.8.6' );
        wp_enqueue_style( 'jquery-ui-myarcadeplugin', MYARCADE_URL . '/assets/css/jquery-ui.css' );
      } break;

      case 'myarcade_page_myarcade-fetch': {
        wp_enqueue_script( 'myarcadeplugin-script', MYARCADE_URL . '/assets/js/myarcadeplugin.js', array( 'jquery' ) );
      } break;
    }
  }
}
add_action('admin_enqueue_scripts', 'myarcade_admin_scripts');

/**
 * Retrieve file location folders for the wp media uploader
 *
 * @version 5.29.0
 * @since   5.29.0
 * @return  array|bool Array of folder parts or false if not a game or error
 */
function myarcade_get_post_upload_dirs() {

  // Check if we try to upload a new game image/file to existing post
  $type = filter_input( INPUT_POST, 'type' );
  $post_id = filter_input( INPUT_POST, 'post_id' );

  if ( false !== strpos( $type, 'myarcade_') && is_game( $post_id ) ) {
    // Get game details
    $game_type = get_post_meta( $post_id, 'mabp_game_type', true );

    if ( ! $game_type ) {
      $game_type = 'custom';
    }

    $game_slug = get_post_field( 'post_name', $post_id );

    remove_filter( 'wp_handle_upload_prefilter', 'myarcade_wp_handle_upload_prefilter' );
    remove_filter('upload_dir', 'myarcade_game_post_upload_dir');

    $upload_dir_specific = myarcade_get_folder_path( $game_slug, $game_type );

    add_filter( 'wp_handle_upload_prefilter', 'myarcade_wp_handle_upload_prefilter' );
    add_filter('upload_dir', 'myarcade_game_post_upload_dir');

    return $upload_dir_specific;
  }

  return false;
}

/**
 * Raname game files uploaded with wp media uploader to fit into the MyArcade folder structure
 *
 * @version 5.29.0
 * @since   5.29.0
 * @param   array $file Array of (name, type, tmp_name). Like $_FILE
 * @return  $file Array
 */
function myarcade_wp_handle_upload_prefilter( $file ) {

  $upload_dir_specific = myarcade_get_post_upload_dirs();

  if ( $upload_dir_specific ) {
    $type = filter_input( INPUT_POST, 'type' );
    $post_id = filter_input( INPUT_POST, 'post_id' );
    $game_slug = get_post_field( 'post_name', $post_id );
    $extension = pathinfo( $file['name'], PATHINFO_EXTENSION );

    // Get the new unique file name
    if ( 'myarcade_image' == $type ) {
     $dir = 'thumbsdir';
    }
    else {
      $dir = 'gamesdir';
    }

    // Overwrite the file name
    $file['name'] = wp_unique_filename( $upload_dir_specific[ $dir ], $game_slug . '.' . $extension );
  }

  return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'myarcade_wp_handle_upload_prefilter' );

/**
 * Modifies the WordPress upload folders
 *
 * @version 5.29.0
 * @access  public
 * @param   array $upload_dir
 * @return  array
 */
function myarcade_game_post_upload_dir( $upload_dir ) {

  $upload_dir_specific = myarcade_get_post_upload_dirs();

  if ( $upload_dir_specific ) {
    $type = filter_input( INPUT_POST, 'type' );

    switch ( $type ) {
      case 'myarcade_image': {
        $upload_dir['path']   = untrailingslashit( $upload_dir_specific['thumbsdir'] );
        $upload_dir['subdir'] = untrailingslashit( str_replace($upload_dir_specific['basedir'], '', $upload_dir_specific['thumbsdir'] ) );
        $upload_dir['url']    = untrailingslashit( $upload_dir_specific['thumbsurl'] );
      } break;

      case 'myarcade_game': {
        $upload_dir['path']   = untrailingslashit( $upload_dir_specific['gamesdir'] );
        $upload_dir['subdir'] = untrailingslashit( str_replace($upload_dir_specific['basedir'], '', $upload_dir_specific['gamesdir'] ) );
        $upload_dir['url']    = untrailingslashit( $upload_dir_specific['gamesurl'] );
      }
    }
  }

  return $upload_dir;
}
add_filter( 'upload_dir', 'myarcade_game_post_upload_dir' );

/**
 * Trigger WordPress media_upload_file action
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_media_upload_game_files() {
  do_action('media_upload_file');
}
add_action('media_upload_myarcade_image', 'myarcade_media_upload_game_files');
add_action('media_upload_myarcade_game', 'myarcade_media_upload_game_files');

/**
 * Extend WordPress upload mimes
 *
 * @version 5.20.0
 * @access  public
 * @param   array  $existing_mimes
 * @return  array
 */
function myarcade_upload_mimes( $existing_mimes=array() ) {
  // Allow DCR file upload
  $existing_mimes['swf'] = 'application/x-shockwave-flash';
  $existing_mimes['dcr'] = 'mime/type';

  return $existing_mimes;
}
add_filter('upload_mimes', 'myarcade_upload_mimes');

/**
 * Adds some ajax functionalities
 */
/**
 * Load requires scripts and styles
 *
 * @version 5.30.0
 * @access  public
 * @return  [type] [description]
 */
function myarcade_load_scriptstyle() {
  global $pagenow;

  if ( $pagenow == 'admin.php' ) {
    // jQuery
    wp_enqueue_script('jquery');

    if ( isset($_GET['page']) && ( ($_GET['page'] == 'myarcade-manage-games') || ($_GET['page'] == 'myarcade-manage-scores') || ($_GET['page'] == 'myarcade-fetch') ) ) {
      // Thickbox
      wp_enqueue_script('thickbox');
      $thickcss = get_option('siteurl')."/".WPINC."/js/thickbox/thickbox.css";
      wp_enqueue_style('thickbox_css', $thickcss, false, false, 'screen');
    }
  }

  $page = filter_input( INPUT_GET, 'page' );

  if ( $pagenow == 'admin.php' || $pagenow == 'post.php' || 'myarcade_admin.php' == $page || 'myarcade-stats' == $page ) {
    // Add MyArcade CSS
    $css = MYARCADE_URL . "/assets/css/myarcadeplugin.css";
    wp_enqueue_style('myarcade_css', $css, false, false, 'screen');
  }
}
add_action('admin_menu', 'myarcade_load_scriptstyle', 99);

/**
 * Show MyArcadePlugin notices
 *
 * @version 5.18.0
 * @access  public
 * @return  void
 */
function myarcade_notices() {
}

/**
 * Show MyArcadePlugin header on plugin option pages
 *
 * @version 5.13.0
 * @param   boolean $echo
 * @return  void
 */
function myarcade_header($echo = true) {
  if (!$echo) {
    return;
  }
  ?>
  <script type="text/javascript">
    jQuery(document).ready(function(){
      jQuery(".toggle_container").hide();
      jQuery("h2.trigger").click(function(){
        jQuery(this).toggleClass("active").next().slideToggle("slow");
      });
    });
  </script>
  <?php
  echo '<div class="wrap">';
}

/**
 * Show MyArcadePlugin footer on plugin options page
 *
 * @version 5.13.0
 * @param   boolean $echo
 * @return  void
 */
function myarcade_footer($echo = true) {
  if (!$echo) {
    return;
  }

  echo '</div>';
}

/**
 * Update old MyArcadePlugin version
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_plugin_update() {
  if ( get_option('myarcade_version') && (get_option('myarcade_version') != MYARCADE_VERSION ) ) {
    myarcade_do_install();
  }
}
add_action('wp_loaded', 'myarcade_plugin_update');

/**
 * Take over the update check
 *
 * @version 5.30.0
 * @access  public
 * @param   object $checked_data
 * @return  object
 */
function myarcade_check_for_update( $checked_data ) {

  if ( empty($checked_data->checked) ) {
    return $checked_data;
  }

  $request_args = array(
    'slug' => MYARCADE_PLUGIN_SLUG,
    'version' => $checked_data->checked[ MYARCADE_PLUGIN_FOLDER_NAME . '/myarcadeplugin.php' ],
  );

  $request_string = prepare_request('update_check', $request_args);

  // Start checking for an update
  $raw_response = wp_remote_post( MYARCADE_UPDATE_API . 'check.php', $request_string );

  if (!is_wp_error($raw_response) && isset($raw_response['response']['code']) && ($raw_response['response']['code'] == 200)) {
    $response = unserialize($raw_response['body']);
  }

  if (isset($response) && is_object($response) && !empty($response)) {
    // Feed the update data into WP updater
    $checked_data->response[ MYARCADE_PLUGIN_FOLDER_NAME .'/myarcadeplugin.php' ] = $response;
  }

  return $checked_data;
}
if ( ! defined('WP_ENV') || WP_ENV != 'development' ) {
  add_filter('pre_set_site_transient_update_plugins', 'myarcade_check_for_update');
}

/**
 * Take over the plugin info screen
 *
 * @version 5.30.0
 * @access  public
 * @param   bolean $unused
 * @param   string $action
 * @param   object $args
 * @return  stdClass
 */
function myarcade_api_call( $unused, $action, $args ) {

  if ( ! isset( $args->slug ) || $args->slug != MYARCADE_PLUGIN_SLUG ) {
    // Proceed only if this is request for our own plugin
    return false;
  }

  // Get the current version
  $plugin_info = get_site_transient('update_plugins');
  $current_version = $plugin_info->checked[ MYARCADE_PLUGIN_FOLDER_NAME .'/myarcadeplugin.php'];

  $args->version = $current_version;

  $request_string = prepare_request($action, $args);

  $request = wp_remote_post( MYARCADE_UPDATE_API . 'check.php', $request_string );

  if ( is_wp_error( $request ) ) {
    $response = new WP_Error( 'plugins_api_failed', __( 'An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>' ), $request->get_error_message() );
  }
  else {
    $response = unserialize( $request['body'] );

    if ( $response === false ) {
      $response = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
    }
  }

  return $response;
}
add_filter('plugins_api', 'myarcade_api_call', 10, 3);
//set_site_transient( 'update_plugins', null );

/**
 * Create request query for the update check
 *
 * @version 5.13.0
 * @access  public
 * @param   string $action
 * @param   array  $args
 * @return  array
 */
function prepare_request( $action, $args ) {
  global $wp_version;

  return array (
      'body' => array (
      'action' => $action,
      'request' => serialize($args),
      'url' => get_bloginfo('url'),
      'item' => MYARCADE_PLUGIN_SLUG,
    ),
    'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
  );
}

/**
 * Show a game
 *
 * @version 5.13.0
 * @access  public
 * @param   object $game Game object
 * @return  void
 */
function myarcade_show_game($game) {

  $contest = '';

  if ($game->leaderboard_enabled) {
    $leader = 'enabled';
  }
  else {
    $leader = 'disabled';
  }

  $play_url = MYARCADE_URL.'/core/playgame.php?gameid='.$game->id;
  $edit_url = MYARCADE_URL.'/core/editgame.php?gameid='.$game->id;

  // Buttons
  $publish     = "<button class=\"button-secondary\" onclick = \"jQuery('#gstatus_$game->id').html('<div class=\'gload\'> </div>');jQuery.post('".admin_url('admin-ajax.php')."',{action:'myarcade_handler',gameid:'$game->id',func:'publish'},function(data){jQuery('#gstatus_$game->id').html(data);});\">".__("Publish", 'myarcadeplugin')."</button>&nbsp;";
  $draft     = "<button class=\"button-secondary\" onclick = \"jQuery('#gstatus_$game->id').html('<div class=\'gload\'> </div>');jQuery.post('".admin_url('admin-ajax.php')."',{action:'myarcade_handler',gameid:'$game->id',func:'draft'},function(data){jQuery('#gstatus_$game->id').html(data);});\">".__("Draft", 'myarcadeplugin')."</button>&nbsp;";
  $delete      = "<button class=\"button-secondary\" onclick = \"jQuery('#gstatus_$game->id').html('<div class=\'gload\'> </div>');jQuery.post('".admin_url('admin-ajax.php')."',{action:'myarcade_handler',gameid:'$game->id',func:'delete'},function(data){jQuery('#gstatus_$game->id').html(data);});\">".__("Delete", 'myarcadeplugin')."</button>&nbsp;";
  $delgame     = "<div class=\"myhelp\"><img style=\"cursor: pointer;border:none;padding:0;\" src='".MYARCADE_URL."/assets/images/delete.png' alt=\"Remove game from the database\" onclick = \"jQuery('#gstatus_$game->id').html('<div class=\'gload\'> </div>');jQuery.post('".admin_url('admin-ajax.php')."',{action:'myarcade_handler',gameid:'$game->id',func:'remove'},function(){jQuery('#gamebox_$game->id').fadeOut('slow');});\" />
                <span class=\"myinfo\">".__("Remove this game from the database", 'myarcadeplugin')."</span></div>
 ";

  // Chek game dimensions
  if ( empty($game->height) ) {
    $game->height = '600';
  }

  if ( empty($game->width)  ) {
    $game->width = '480';
  }

  $edit ='<a href="#" onclick="alert(\'If you want to edit games please consider upgrading to MyArcadePlugin Pro\');return false;" class="button-secondary edit" title="'.__("Edit", 'myarcadeplugin').'">'.__("Edit", 'myarcadeplugin').'</a>&nbsp;';

  if ($game->status == 'published') {
    $edit_post = '<a href="post.php?post='.$game->postid.'&action=edit" class="button-secondary" target="_blank">Edit Post</a>&nbsp;';

    // contest button
    if ($game->leaderboard_enabled && defined("MYARCADECONTEST_VERSION") ) {
      $contest = '<a class="button" href="post-new.php?post_type=contest&gameid='.$game->postid.'">'.__( 'New Contest', 'myarcadeplugin').'</a>';
    }
  }
  else {
   $edit_post = '';
  }

  // Generate content for the game box
  if ($game->status == 'published') {
    $name = get_the_title($game->postid);
    $thumb_url = get_post_meta($game->postid, 'mabp_thumbnail_url', true);
    $game_post = get_post($game->postid);
    $description = strip_tags($game_post->post_content);

    if ( strlen($description) > 320 ) {
      $dots = '..';
    }
    else {
      $dots = '';
    }

    $description = mb_substr( stripslashes($description), 0, 320) . $dots;

    $categs = wp_get_post_categories($game->postid);
    $categories = false;
    if ( $categs ) {
      $count = count($categs);
      for ($i=0; $i<$count; $i++) {
        $c = get_category($categs[$i]);
        $categories .= $c->name;
        if ($i < ($count - 1) ) {
         $categories .= ', ';
        }
      }
    }

    if ($categories) {
      $categories = '<div style="margin-top:6px;"><strong>Categories:</strong> '.$categories."</div>";
    }
  }
  else {

    $game_categs = false;

    if ( isset($game->categs) ) {
      $game_categs = $game->categs;
    }
    elseif ( isset($game->categories) ) {
      $game_categs = $game->categories;
    }

    if ( is_array($game_categs) ) {
      $categories = '';
      $count = count($game_categs);
      for ($i=0; $i<$count; $i++) {
        $categories .= $game_categs[$i];
        if ($i < ($count - 1) ) {
         $categories .= ', ';
        }
      }
    }
    else {
      $categories = $game_categs;
    }

    $name      = $game->name;
    $thumb_url = $game->thumbnail_url;

    $description = str_replace(array("\r", "\r\n", "\n"), ' ', $game->description);

    if ( strlen($description) > 320 ) {
      $dots = '..';
    }
    else {
      $dots = '';
    }

    $description = mb_substr( stripslashes($description), 0, 280) . $dots;

    if ( isset($categories) ) {
      $categories = '<div style="margin-top:6px;"><strong>Categories:</strong> '.$categories."</div>";
    }
    else {
      $categories = '';
    }
  }
  ?>
  <div class="show_game" id="gamebox_<?php echo $game->id;?>">
    <div class="block">
      <table class="optiontable" width="100%">
        <tr valign="top">
          <td width="110" align="center">
            <img src="<?php echo $thumb_url; ?>" width="100" height="100" alt="" />
            <div class="g-features">
              <span class="lb_<?php echo $leader; ?>" title="Leaderboards <?php echo ucfirst($leader); ?>"></span>
            </div>
          </td>
          <td colspan="2">
            <table>
              <tr valign="top">
                <td width="520">
                  <strong><div id="gname_<?php echo $game->id;?>"><?php echo $name; ?></div></strong>
                </td>
                <td>
                  <?php
                  if ( isset($game->game_type) ) {
                    $type = $game->game_type;
                  }
                  elseif ( isset($game->type) ) {
                    $type = $game->type;
                  }
                  else {
                    $type = '';
                  }

                  echo ucfirst($type);
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $description; ?>
                  <br />
                  <?php echo $categories; ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td align="center">
            <p style="margin-top:3px"><a class="thickbox button-primary" title="<?php echo $name; ?>" href="<?php echo $play_url; ?>&keepThis=true&TB_iframe=true&height=<?php echo $game->height; ?>&width=<?php echo $game->width; ?>"><?php _e("Play", 'myarcadeplugin')?></a></p>
          </td>
          <td>
            <?php echo $delgame; ?>

            <?php
              switch ($game->status) {
                case 'ignored':
                case 'new':         echo $delete; echo $edit; echo $publish; echo $draft; break;
                case 'published':   echo $delete; echo $edit_post; echo $contest; break;
                case 'deleted':     echo $edit; echo $publish; echo $draft; break;
              }
            ?>
          </td>
          <td width="130">
            <div id="gstatus_<?php echo $game->id;?>" style="margin: 0;font-weight:bold;float:right;">
              <?php echo $game->status; ?>
            </div>
          </td>
        </tr>

      </table>
    </div>
  </div>
  <?php
}

/**
 * MyArcade AJAX handler
 *
 * @version 5.13.0
 * @access  public
 * @return  [type] [description]
 */
function myarcade_handler() {
  global $wpdb;

  // Check if the current user has permissions to do that...
  if ( ! current_user_can('manage_options') ) {
    wp_die('You do not have permissions access this site!');
  }

  if ( isset( $_POST['gameid']) ) {
    $gameID = $_POST['gameid'];
  }

  switch ($_POST['func']) {
    /* Manage Games */
    case "publish":
    case "draft": {
      if ( !isset($gameID) || empty($gameID) ) {
        echo "No Game ID!"; die();
      }

      // Publish this game
      myarcade_add_games_to_blog( array('game_id' => $gameID, 'echo' => false, 'post_status' => $_POST['func'] ) );

      // Get game status
      $status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix . 'myarcadegames'." WHERE id = '$gameID'");
      echo $status;
    } break;

    case "delete": {
      if ( !isset($gameID) || empty($gameID) ) {
        echo "No Game ID!";
        die();
      }

      // Check if game is published
      $game = $wpdb->get_row("SELECT postid, name FROM ".$wpdb->prefix . 'myarcadegames'." WHERE id = '$gameID'", ARRAY_A);
      $postid = $game['postid'];

      if ( !$postid )  {
        // Alternative check for older versions of MyArcadePlugin
        $name = $game['name'];
        $postid = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '$name'");
      }

      if ($postid) {
        myarcade_delete_game($postid);
        // Delete wordpress post
        wp_delete_post($postid);
      }

      // Update game status
      $query = "UPDATE ".$wpdb->prefix . 'myarcadegames'." SET status = 'deleted', postid = '' WHERE id = $gameID";
      $wpdb->query($query);

      // Get game status
      $status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix . 'myarcadegames'." WHERE id = '$gameID'");
      echo $status;
    } break;

    case "remove": {
      if ( !isset($gameID) || empty($gameID) ) {
        echo "No Game ID!";
        die();
      }

      // Remove this game from mysql database
      $query = "DELETE FROM ".$wpdb->prefix . 'myarcadegames'." WHERE id = $gameID LIMIT 1";
      $wpdb->query($query);
      echo "removed";
    } break;

    /* Category Mapping */
    case "addmap": {
      if (intval($_POST['mapcat']) > 0) {
        // Init var for map processing
        $map_category = true;

        $section = filter_input( INPUT_POST, 'section' );

        if ( 'general' == $section ) {
          // Get game_categories as array
          $feedcategories = get_option('myarcade_categories');

          for ($i=0; $i<count($feedcategories); $i++) {
            if ($feedcategories[$i]['Slug'] == $_POST['feedcat']) {
              if ( empty($feedcategories[$i]['Mapping']) ) {
                $feedcategories[$i]['Mapping'] = $_POST['mapcat'];
              }
              else {
                // Check, if this category is already mapped
                $mapped_cats = explode(',', $feedcategories[$i]['Mapping']);
                foreach ($mapped_cats as $mapped_cat) {
                  if ($mapped_cat == $_POST['mapcat']) {
                    $map_category = false;
                    break;
                  }
                }

                $feedcategories[$i]['Mapping'] = $feedcategories[$i]['Mapping'] . "," . $_POST['mapcat'];
              }

              break;
            }
          }

          if ($map_category == true) {
            // Update Mapping
            update_option('myarcade_categories', $feedcategories);

            $general= get_option('myarcade_general');

            if ( $general['post_type'] == 'post' ) {
              $cat_name = get_cat_name($_POST['mapcat']);
            } else {
              if (taxonomy_exists($general['custom_category'])) {
                $cat_name_tax = get_term_by('id', $_POST['mapcat'], $general['custom_category']);
                $cat_name = $cat_name_tax->name;
              }
            }

            ?>
            <span id="general_delmap_<?php echo $_POST['mapcat']; ?>_<?php echo $feedcategories[$i]['Slug']; ?>" class="remove_map">
              <img style="flaot:left;top:4px;position:relative;" src="<?php echo MYARCADE_URL; ?>/assets/images/remove.png" alt="UnMap" onclick="myabp_del_map('<?php echo $_POST['mapcat']; ?>', '<?php echo $feedcategories[$i]['Slug']; ?>', 'general')" />&nbsp;<?php echo $cat_name; ?>
            </span>
            <?php
          }
        }
      }
    } break;

    case "delmap": {
      if ( intval($_POST['mapcat']) > 0 ) {
        $update_mapping = false;

        $section = filter_input( INPUT_POST, 'section' );

        if ( 'general' == $section ) {
          // Get game_categories as array
          $feedcategories = get_option('myarcade_categories');

          for ($i=0; $i<count($feedcategories); $i++) {
            if ($feedcategories[$i]['Slug'] == $_POST['feedcat']) {
              $mapped_cats = explode(',', $feedcategories[$i]['Mapping']);

              for($j=0; $j<count($mapped_cats); $j++) {
                if ($mapped_cats[$j] == $_POST['mapcat']) {
                  unset($mapped_cats[$j]);
                  $feedcategories[$i]['Mapping'] = implode(',', $mapped_cats);
                  $update_mapping = true;
                  break;
                }
              }
              break;
            }
          }

          if ($update_mapping == true) {
            update_option('myarcade_categories', $feedcategories);
          }
        }
      }
    } break;

    /* Database Actions */
    case "delgames": {
      $wpdb->query("TRUNCATE TABLE ".$wpdb->prefix . 'myarcadegames');
      ?>
      <script type="text/javascript">
        alert('All games deleted!');
      </script>
      <?php
    } break;

    case "remgames": {
      $wpdb->query("DELETE FROM ".$wpdb->prefix . 'myarcadegames'." WHERE status = 'deleted'");
      ?>
      <script type="text/javascript">
        alert('Games marked as "deleted" where removed from the database!');
      </script>
      <?php
    } break;

    case "zeroscores": {
      $wpdb->query("DELETE FROM ".$wpdb->prefix.'myarcadescores'." WHERE score = '0' OR score = ''");
      ?>
      <script type="text/javascript">
        alert('Zero scores deleted!');
      </script>
      <?php
    } break;

    case "delscores": {
      $wpdb->query("TRUNCATE TABLE ".$wpdb->prefix.'myarcadescores');
      $wpdb->query("TRUNCATE TABLE ".$wpdb->prefix.'myarcadehighscores');
      $wpdb->query("TRUNCATE TABLE ".$wpdb->prefix.'myarcademedals');
      ?>
      <script type="text/javascript">
        alert('All scores deleted!');
      </script>
      <?php
    } break;

    case "delete_score": {
      if ( isset( $_POST['scoreid'] ) ) {

        // get score
        $old_score = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.'myarcadescores'." WHERE id = '{$_POST['scoreid']}'");
        // Get highscore
        $highscore = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.'myarcadehighscores'." WHERE game_tag = '{$old_score->game_tag}' AND user_id = '{$old_score->user_id}' AND score = '{$old_score->score}'");

        if ( $highscore ) {
          // The user is highscore holder
          // Remove highscore
          $wpdb->query("DELETE FROM ".$wpdb->prefix.'myarcadehighscores'." WHERE id = '{$highscore->id}'");
        }


        $wpdb->query("DELETE FROM ".$wpdb->prefix.'myarcadescores'." WHERE id = '".$_POST['scoreid']."'");
      }
    } break;

    case "delete_game_scores": {
      $game_tag = filter_input( INPUT_POST, 'game_tag' );

      if ( $game_tag ) {
        $wpdb->query( "DELETE FROM " . $wpdb->prefix.'myarcadescores' . " WHERE game_tag = '{$game_tag}'" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix.'myarcadehighscores' . " WHERE game_tag = '{$game_tag}'" );
      }
    } break;

    case "dircheck": {
      if ( isset( $_POST['directory'] ) ) {

        $upload_dir = myarcade_upload_dir();

        if ( $_POST['directory'] == 'games' ) {
          if ( !is_writable( $upload_dir['gamesdir'] ) ) {
            echo '<p class="mabp_error mabp_680">'.sprintf(__("The games directory '%s' must be writeable (chmod 777) in order to download games.", 'myarcadeplugin'), $upload_dir['gamesdir']).'</p>';
          }
        } else {
          if ( !is_writable( $upload_dir['thumbsdir'] ) ) {
            echo '<p class="mabp_error mabp_680">'.sprintf(__("The thumbs directory '%s' must be writeable (chmod 777) in order to download thumbnails or screenshots.", 'myarcadeplugin'), $upload_dir['thumbsdir']).'</p>';
          }
        }
      }
    } break;
  }

  wp_die();
}
add_action('wp_ajax_myarcade_handler', 'myarcade_handler');

/**
 * Display settings update notice
 *
 * @version 5.13.0
 * @access  public
 * @return  [type] [description]
 */
function myarcade_plugin_update_notice() {
  // Avoid message displaying when settings have been saved
  if ( isset($_POST['feedaction']) && $_POST['feedaction'] == 'save' ) {
    return;
  }
  ?>
  <div style="border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;background:#FEB1B1;border:1px solid #FE9090;color:#820101;font-size:14px;font-weight:bold;height:auto;margin:30px 15px 15px 0px;overflow:hidden;padding:4px 10px 6px;line-height:30px;">
    MyArcadePlugin was just updated / installed - Please visit the <a href="admin.php?page=myarcade-edit-settings">Plugin Options Page</a> and setup the plugin!
  </div>
  <?php
}

// Check if we should display the settings update notice
if ( get_transient('myarcade_settings_update_notice') ) {
  add_action('admin_notices', 'myarcade_plugin_update_notice', 99);
}

/**
 * Helper function for form selections
 *
 * @version 5.13.0
 * @param   string $selected Selected item
 * @param   string $current  Current item
 * @return  void
 */
function myarcade_selected( $selected, $current ) {
  if ( $selected === $current) {
    echo ' selected';
  }
}

/**
 * Helper function for check boxes
 *
 * @version 5.13.0
 * @param   mixed $var
 * @param   mixed $value
 * @return  void
 */
function myarcade_checked( $var, $value ) {
  if ( $var === $value) {
    echo ' checked';
  }
}

/**
 *  Helper function for multi selectors
 *
 * @version 5.13.0
 * @param   mixed $var
 * @param   mixed $value
 * @return  void
 */
function myarcade_checked_array( $var, $value ) {
  if ( is_array($var) ) {
    foreach ($var as $element) {
      if ( $element == $value) {
        echo ' checked';
        break;
      }
    }
  }
}

/**
 * Create required MyArcadePlugin directories
 *
 * @version 5.15.1
 * @access  public
 * @return  void
 */
function myarcade_create_directories() {
  // Game folders
  $upload_dir   = myarcade_upload_dir();

  @wp_mkdir_p( $upload_dir['gamesdir'] );
  @wp_mkdir_p( $upload_dir['thumbsdir']);
  @wp_mkdir_p( $upload_dir['gamesdir'] . '/uploads/swf' );
  @wp_mkdir_p( $upload_dir['gamesdir'] . '/uploads/ibparcade' );
  @wp_mkdir_p( $upload_dir['gamesdir'] . '/uploads/phpbb' );
  @wp_mkdir_p( $upload_dir['gamesdir'] . '/uploads/unity' );
}

/**
 * Display the tracking message notification
 *
 * @version 5.30.0
 * @return  void
 */
function myarcade_tracking_message( $show_skip_button = true ) {
  ?>
  <div class="myarcade_message myarcade_notice">
    <h3><?php _e("MyArcadePlugin Stats", 'myarcadeplugin'); ?></h3>
    <p>
      <?php printf( __( 'Enable site statistics to collect <strong>game plays and play duration</strong> for each game. This will help you to optimize your site and to get a better overview of your visitors. By enabling this feature MyArcadePlugin will collect and send us non-sensitive diagnostic data and usage information. Those data will help us to make MyArcadePlugin even better. %1$sFind out more%2$s.', 'myarcadeplugin' ), '<a href="https://myarcadeplugin.com/usage-tracking/" target="_blank">', '</a>' ); ?></p>
    <p class="submit">
      <a class="button-primary button button-large" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'myarcade_tracker_optin', 'true' ), 'myarcade_tracker_optin', 'myarcade_tracker_nonce' ) ); ?>"><?php esc_html_e( 'Enable', 'myarcadeplugin' ); ?></a>
      <?php if ( $show_skip_button ) : ?>
      <a class="button-secondary button button-large skip"  href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'myarcade_tracker_optout', 'true' ), 'myarcade_tracker_optout', 'myarcade_tracker_nonce' ) ); ?>"><?php esc_html_e( 'No thanks', 'myarcadeplugin' ); ?></a>
      <?php endif; ?>
    </p>
  </div>
<?php
}

/**
 * Handles tracker opt in/out
 *
 * @version 5.30.0
 * @return  void
 */
function myarcade_tracker_optin() {

  $optin  = filter_input( INPUT_GET, 'myarcade_tracker_optin' );
  $optout = filter_input( INPUT_GET, 'myarcade_tracker_optout' );
  $nonce  = filter_input( INPUT_GET, 'myarcade_tracker_nonce' );

  if ( $optin && wp_verify_nonce( $nonce, 'myarcade_tracker_optin' ) ) {
    update_option( 'myarcade_allow_tracking', 'yes' );
    MyArcade_Tracker::send_tracking_data();
  }
  elseif ( $optout && wp_verify_nonce( $nonce, 'myarcade_tracker_optout' ) ) {
    update_option( 'myarcade_allow_tracking', 'no' );
    delete_option( 'myarcade_tracker_last_send' );
  }
}
add_action( 'admin_init', 'myarcade_tracker_optin' );
?>