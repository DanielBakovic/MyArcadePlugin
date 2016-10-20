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
 * @version 5.13.0
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

  add_menu_page('MyArcade', 'MyArcade', $permisssion , basename(__FILE__), 'myarcade_show_stats_page', MYARCADE_CORE_URL . '/images/arcade.png', 55);
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
 * Load game import scripts
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_import_scripts() {
  wp_enqueue_script('jquery-form');
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
    wp_register_script( 'myarcade_writepanel', MYARCADE_JS_URL . '/writepanel.js', array('jquery') );
    wp_enqueue_script('myarcade_writepanel');
  }

  if ( $pagenow == 'admin.php' ) {
    switch ( $screen->id ) {
      case 'myarcade_page_myarcade-publish-games': {
        wp_enqueue_script( 'jquery-ui-progressbar', MYARCADE_JS_URL . '/jquery.ui.progressbar.min.js', array( 'jquery-ui-core', 'jquery-ui-widget' ), '1.8.6' );
        wp_enqueue_style( 'jquery-ui-myarcadeplugin', MYARCADE_JS_URL . '/jquery-ui-1.7.2.custom.css', array(), '1.7.2' );
      } break;

      case 'myarcade_page_myarcade-fetch': {
        wp_enqueue_script( 'myarcadeplugin-script', MYARCADE_JS_URL . '/myarcadeplugin.js', array( 'jquery' ) );
      } break;
    }
  }
}
add_action('admin_enqueue_scripts', 'myarcade_admin_scripts');

/**
 * Modifies the WordPress upload folders
 *
 * @version 5.3.2
 * @access  public
 * @param   array $upload
 * @return  array
 */
function myarcade_downloads_upload_dir( $upload ) {

  switch ( filter_input( INPUT_POST, 'type' ) ) {
    case 'myarcade_image': {
      $upload['subdir'] = '/thumbs';
      $upload['path'] =  $upload['basedir'] . $upload['subdir'];
      $upload['url'] = $upload['baseurl'] . $upload['subdir'];
    } break;

    case 'myarcade_game': {
      $upload['subdir'] = '/games';
      $upload['path'] =  $upload['basedir'] . $upload['subdir'];
      $upload['url'] = $upload['baseurl'] . $upload['subdir'];
    } break;

    default:
      // Do nothing
    break;
  }

  return $upload;
}
add_filter('upload_dir', 'myarcade_downloads_upload_dir');
add_action('media_upload_myarcade_image', 'myarcade_media_upload_game_files');
add_action('media_upload_myarcade_game', 'myarcade_media_upload_game_files');

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
 * @version 5.13.0
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

  if ( $pagenow == 'admin.php' || $pagenow == 'post.php' || ( isset($_GET['page']) && $_GET['page'] == 'myarcade_admin.php') ) {
    // Add MyArcade CSS
    $css = MYARCADE_CORE_URL."/myarcadeplugin.css";
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
  if ( get_option( 'myarcade_rating_div' ) == "no" ) {
    $install_date = get_option( 'myarcade_install_date' );
    $display_date = date('Y-m-d h:i:s');
    $datetime1 = new DateTime($install_date);
    $datetime2 = new DateTime($display_date);
    $diff_intrval = round(($datetime2->format('U') - $datetime1->format('U')) / (60*60*24));

    if ( $diff_intrval >= 7 ) {
      echo '<div class="updated notice map_fivestar">
        <p>Awesome, you\'ve been using <strong>MyArcadePlugin</strong> for a while. May we ask you to give it a <strong>5-star</strong> rating on Wordpress?
          <br /><strong>Your MyArcadePlugin Team</strong>
          <ul>
            <li><a href="https://wordpress.org/support/view/plugin-reviews/myarcadeblog#postform" class="thankyou" target="_new" title="Ok, you deserved it" style="font-weight:bold;">Ok, you deserved it</a></li>
              <li><a href="javascript:void(0);" class="mapHideRating" title="I already did" style="font-weight:bold;">I already did</a></li>
              <li><a href="javascript:void(0);" class="mapHideRating" title="No, not good enough" style="font-weight:bold;">No, not good enough</a></li>
          </ul>
      </div>
      <script>
      jQuery( document ).ready(function( $ ) {
      jQuery(\'.mapHideRating\').click(function(){
          var data={\'action\':\'hide_rating\'}
               jQuery.ajax({
          url: "'.admin_url( 'admin-ajax.php' ).'",
          type: "post",
          data: data,
          dataType: "json",
          async: !0,
          success: function(e) {
              if (e=="success") {
                 jQuery(\'.map_fivestar\').slideUp(\'slow\');
              }
          }
           });
          })
      });
      </script>
      ';
    }
  }
}

/**
 * Hide MyArcadePlugin rating
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_hide_rating_div() {
  update_option('myarcade_rating_div','yes');
    echo json_encode(array("success")); exit;
}
add_action('wp_ajax_hide_rating','myarcade_hide_rating_div');

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

  $play_url = MYARCADE_CORE_URL.'/playgame.php?gameid='.$game->id;

  // Buttons
  $publish     = "<button class=\"button-secondary\" onclick = \"jQuery('#gstatus_$game->id').html('<div class=\'gload\'> </div>');jQuery.post('".admin_url('admin-ajax.php')."',{action:'myarcade_handler',gameid:'$game->id',func:'publish'},function(data){jQuery('#gstatus_$game->id').html(data);});\">".__("Publish", 'myarcadeplugin')."</button>&nbsp;";
  $draft     = "<button class=\"button-secondary\" onclick = \"jQuery('#gstatus_$game->id').html('<div class=\'gload\'> </div>');jQuery.post('".admin_url('admin-ajax.php')."',{action:'myarcade_handler',gameid:'$game->id',func:'draft'},function(data){jQuery('#gstatus_$game->id').html(data);});\">".__("Draft", 'myarcadeplugin')."</button>&nbsp;";
  $delete      = "<button class=\"button-secondary\" onclick = \"jQuery('#gstatus_$game->id').html('<div class=\'gload\'> </div>');jQuery.post('".admin_url('admin-ajax.php')."',{action:'myarcade_handler',gameid:'$game->id',func:'delete'},function(data){jQuery('#gstatus_$game->id').html(data);});\">".__("Delete", 'myarcadeplugin')."</button>&nbsp;";
  $delgame     = "<div class=\"myhelp\"><img style=\"cursor: pointer;border:none;padding:0;\" src='".MYARCADE_CORE_URL."/images/delete.png' alt=\"Remove game from the database\" onclick = \"jQuery('#gstatus_$game->id').html('<div class=\'gload\'> </div>');jQuery.post('".admin_url('admin-ajax.php')."',{action:'myarcade_handler',gameid:'$game->id',func:'remove'},function(){jQuery('#gamebox_$game->id').fadeOut('slow');});\" />
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
 * @version 5.3.2
 * @access  public
 * @return  void
 */
function myarcade_handler() {
  global $wpdb;

  // Check if the current user has permissions to do that...
  if ( ! current_user_can('manage_options') ) {
    wp_die('You do not have permissions access this site!');
  }

  $gameID = intval( filter_input( INPUT_POST, 'gameid' ) );
  $action = sanitize_text_field( filter_input( INPUT_POST, 'func' ) );


  switch ( $action ) {
    /* Manage Games */
    case "publish":
    case "draft": {
      if ( ! $gameID ) {
        echo "No Game ID!";
        die();
      }

      // Publish this game
      myarcade_add_games_to_blog( array('game_id' => $gameID, 'echo' => false, 'post_status' => $action ) );

      // Get game status
      $status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix . 'myarcadegames'." WHERE id = '$gameID'");
      echo $status;
    } break;

    case "delete": {
      if ( ! $gameID ) {
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
      if ( ! $gameID ) {
        echo "No Game ID!";
        die();
      }

      // Remove this game from mysql database
      $query = "DELETE FROM ".$wpdb->prefix . 'myarcadegames'." WHERE id = {$gameID} LIMIT 1";
      $wpdb->query( $query );
      echo "removed";
    } break;

    /* Category Mapping */
    case "addmap": {
      $mapping = intval( filter_input( INPUT_POST, 'mapcat' ) );
      $feedcat = sanitize_text_field( filter_input( INPUT_POST, 'feedcat' ) );

      if ( $mapping ) {
        // Init var for map processing
        $map_category = true;

        $section = sanitize_text_field( filter_input( INPUT_POST, 'section' ) );

        if ( 'general' == $section ) {
          // Get game_categories as array
          $feedcategories = get_option('myarcade_categories');

          for ($i=0; $i<count($feedcategories); $i++) {
            if ( $feedcategories[$i]['Slug'] == $feedcat ) {
              if ( empty($feedcategories[$i]['Mapping']) ) {
                $feedcategories[$i]['Mapping'] = $mapping;
              }
              else {
                // Check, if this category is already mapped
                $mapped_cats = explode(',', $feedcategories[$i]['Mapping']);
                foreach ($mapped_cats as $mapped_cat) {
                  if ($mapped_cat == $mapping ) {
                    $map_category = false;
                    break;
                  }
                }

                $feedcategories[$i]['Mapping'] = $feedcategories[$i]['Mapping'] . "," . $mapping;
              }

              break;
            }
          }

          if ($map_category == true) {
            // Update Mapping
            update_option('myarcade_categories', $feedcategories);

            $general= get_option('myarcade_general');

            if ( $general['post_type'] == 'post' ) {
              $cat_name = get_cat_name( $mapping );
            } else {
              if (taxonomy_exists($general['custom_category'])) {
                $cat_name_tax = get_term_by('id', $mapping, $general['custom_category']);
                $cat_name = $cat_name_tax->name;
              }
            }

            ?>
            <span id="general_delmap_<?php echo $mapping; ?>_<?php echo $feedcategories[$i]['Slug']; ?>" class="remove_map">
              <img style="flaot:left;top:4px;position:relative;" src="<?php echo MYARCADE_CORE_URL; ?>/images/remove.png" alt="UnMap" onclick="myabp_del_map('<?php echo $mapping; ?>', '<?php echo $feedcategories[$i]['Slug']; ?>', 'general')" />&nbsp;<?php echo $cat_name; ?>
            </span>
            <?php
          }
        }
      }
    } break;

    case "delmap": {
      $mapcat = intval( filter_input( INPUT_POST, 'mapcat' ) );
      $feedcat = sanitize_text_field( filter_input( INPUT_POST, 'feedcat' ) );

      if ( $mapcat ) {
        $update_mapping = false;

        $section = filter_input( INPUT_POST, 'section' );

        if ( 'general' == $section ) {
          // Get game_categories as array
          $feedcategories = get_option('myarcade_categories');

          for ($i=0; $i<count($feedcategories); $i++) {
            if ( $feedcategories[$i]['Slug'] == $feedcat ) {
              $mapped_cats = explode(',', $feedcategories[$i]['Mapping']);

              for($j=0; $j<count($mapped_cats); $j++) {
                if ( $mapped_cats[$j] == $mapcat ) {
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
      $scoreid = intval( filter_input( INPUT_POST, 'scoreid' ) );

      if ( $scoreid ) {

        // get score
        $old_score = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.'myarcadescores'." WHERE id = '{$scoreid}'");
        // Get highscore
        $highscore = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.'myarcadehighscores'." WHERE game_tag = '{$old_score->game_tag}' AND user_id = '{$old_score->user_id}' AND score = '{$old_score->score}'");

        if ( $highscore ) {
          // The user is highscore holder
          // Remove highscore
          $wpdb->query("DELETE FROM ".$wpdb->prefix.'myarcadehighscores'." WHERE id = '{$highscore->id}'");
        }


        $wpdb->query("DELETE FROM ".$wpdb->prefix.'myarcadescores'." WHERE id = '".$scoreid."'");
      }
    } break;

    case "delete_game_scores": {
      $game_tag = sanitize_text_field( filter_input( INPUT_POST, 'game_tag' ) );

      if ( $game_tag ) {
        $wpdb->query( "DELETE FROM " . $wpdb->prefix.'myarcadescores' . " WHERE game_tag = '{$game_tag}'" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix.'myarcadehighscores' . " WHERE game_tag = '{$game_tag}'" );
      }
    } break;

    case "dircheck": {
      $directory = sanitize_text_field( filter_input( INPUT_POST, 'directory' ) );

      if ( $directory ) {

        $upload_dir = myarcade_upload_dir();

        if ( $directory == 'games' ) {
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

    default:
      // Do nothing
    break;
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
?>