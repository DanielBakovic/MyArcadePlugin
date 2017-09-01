<?php
/**
 * SpilGames
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// No direct Access
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Display distributor settings on admin page
 *
 * @version 5.15.0
 * @access  public
 * @return  void
 */
function myarcade_settings_spilgames() {
  $spilgames = myarcade_get_settings( 'spilgames' );
  $defaults = myarcade_default_settings_spilgames();
  $spilgames = wp_parse_args( $spilgames, $defaults );
  ?>
  <h2 class="trigger"><?php _e("Spil Games", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php printf( __( "%s distributes Flash and HTML5 games.", 'myarcadeplugin' ), '<a href="http://publishers.spilgames.com/" target="_blank">Spil Games</a>' ); ?>
            </i>
          </td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="spilgamesurl" value="<?php echo $spilgames['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Fetch Games", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="spilgameslimit" value="<?php echo $spilgames['limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched at once. Enter 'all' (without quotes) if you want to fetch all games. Otherwise enter an integer.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Platform", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="spilgames_platform" id="spilgames_platform">
              <option value="Crossplatform" <?php myarcade_selected( $spilgames['platform'], 'Crossplatform'); ?> ><?php _e( "Cross platform", 'myarcadeplugin'); ?></option>
              <option value="Desktop" <?php myarcade_selected($spilgames['platform'], 'Desktop'); ?> ><?php _e("Desktop", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select the game compatibility. Cross platform games can be played on mobile and desktop devices.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Thumbnail Size", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="spilgamesthumbsize" id="spilgamesthumbsize">
              <option value="1" <?php myarcade_selected($spilgames['thumbsize'], '1'); ?> ><?php _e("Small (100x75)", 'myarcadeplugin'); ?></option>
              <option value="2" <?php myarcade_selected($spilgames['thumbsize'], '2'); ?> ><?php _e("Medium (120x90)", 'myarcadeplugin'); ?></option>
              <option value="3" <?php myarcade_selected($spilgames['thumbsize'], '3'); ?> ><?php _e("Large (200x120)", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select the size of the thumbnails that should be used for games from Spil Games. Default size is small (100x75).", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Gamer Player API", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="checkbox" name="spilgames_player_api" value="true" <?php myarcade_checked($spilgames['player_api'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to use the Spilgames Game Player API to embed games. Spilgames will add revenue sharing trough the API in the future.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="checkbox" name="spilgames_cron_fetch" value="true" <?php myarcade_checked($spilgames['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="spilgames_cron_fetch_limit" value="<?php echo $spilgames['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="spilgames_cron_publish" value="true" <?php myarcade_checked($spilgames['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="spilgames_cron_publish_limit" value="<?php echo $spilgames['cron_publish_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be published on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

      </table>
      <input class="button button-primary" id="submit" type="submit" name="submit" value="<?php _e("Save Settings", 'myarcadeplugin'); ?>" />
    </div>
  </div>
  <?php
}

/**
 * Retrieve distributor's default settings
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  array Default settings
 */
function myarcade_default_settings_spilgames() {
  return array(
    'feed'          => 'http://publishers.spilgames.com/rss-3',
    'limit'         => '20',
    'platform'      => 'Crossplatform',
    'thumbsize'     => '1',
    'player_api'    => false,
    'cron_fetch'    => false,
    'cron_fetch_limit' => '1',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
    'status'        => 'publish',
  );
}

/**
 * Handle distributor settings update
 *
 * @version 5.26.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_spilgames() {

  myarcade_check_settings_nonce();
  $defaults = myarcade_default_settings_spilgames();

  // Spil Games Settings
  $spilgames = array();
  $spilgames['feed'] = esc_url_raw( filter_input( INPUT_POST, 'spilgamesurl', FILTER_DEFAULT, array( "options" => array( "default" => $defaults['feed'] ) ) ) );
  $spilgames['limit'] = intval( filter_input( INPUT_POST, 'spilgameslimit', FILTER_DEFAULT, array( "options" => array( "default" => $defaults['limit'] ) ) ) );
  $spilgames['thumbsize'] = esc_sql( filter_input( INPUT_POST, 'spilgamesthumbsize', FILTER_DEFAULT, array( "options" => array( "default" => $defaults['thumbsize'] ) ) ) );

  $spilgames['cron_fetch']          = (isset($_POST['spilgames_cron_fetch'])) ? true : false;
  $spilgames['cron_fetch_limit']    = (isset($_POST['spilgames_cron_fetch_limit']) ) ? intval($_POST['spilgames_cron_fetch_limit']) : 1;
  $spilgames['cron_publish']        = (isset($_POST['spilgames_cron_publish']) ) ? true : false;
  $spilgames['cron_publish_limit']  = (isset($_POST['spilgames_cron_publish_limit']) ) ? intval($_POST['spilgames_cron_publish_limit']) : 1;
  $spilgames['player_api'] = (isset($_POST['spilgames_player_api'])) ? true : false;
  $spilgames['platform'] = esc_sql( filter_input( INPUT_POST, 'spilgames_platform' ) );

  // Update Settings
  update_option('myarcade_spilgames', $spilgames);
}

/**
 * Display distributor fetch games options
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_settings_spilgames() {

  $spilgames = myarcade_get_fetch_options_spilgames();
  ?>

  <div class="myarcade_border white hide mabp_680" id="spilgames">
    <label><?php _e("Filter by search query", 'myarcadeplugin'); ?>: </label>
    <input type="text" size="40"  name="searchspilgames" value="<?php echo $spilgames['search']; ?>" />
    <p class="myarcade_hr">&nbsp;</p>
    <div style="float:left;width:150px;">
      <input type="radio" name="fetchmethodspilgames" value="latest" <?php myarcade_checked($spilgames['method'], 'latest');?>>
    <label><?php _e("Latest Games", 'myarcadeplugin'); ?></label>
    <br />
    <input type="radio" name="fetchmethodspilgames" value="offset" <?php myarcade_checked($spilgames['method'], 'offset');?>>
    <label><?php _e("Use Offset", 'myarcadeplugin'); ?></label>
    </div>
    <div class="myarcade_border" style="float:left;padding-top: 5px;background-color: #F9F9F9">
    Fetch <input type="number" name="limitspilgames" value="<?php echo $spilgames['limit']; ?>" /> games <span id="offsspilgames" class="hide">from page <input id="radiooffsspilgames" type="number" name="offsetspilgames" value="<?php echo $spilgames['offset']; ?>" /> </span>
    </div>
    <div class="clear"></div>
  </div>
  <?php
}

/**
 * Generate an options array with submitted fetching parameters
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  array Fetching options
 */
function myarcade_get_fetch_options_spilgames() {

  // Get distributor settings
  $settings = myarcade_get_settings( 'spilgames' );
  $defaults = myarcade_default_settings_spilgames();
  $settings = wp_parse_args( $settings, $defaults );

  $settings['search'] = '';
  $settings['method'] = 'latest';
  $settings['offset'] = 1;

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    $settings['search']  = filter_input( INPUT_POST, 'searchspilgames' );
    $settings['limit']   = filter_input( INPUT_POST, 'limitspilgames' );
    $settings['method']  = filter_input( INPUT_POST, 'fetchmethodspilgames', FILTER_SANITIZE_STRING, array( "options" => array( "default" => 'latest') ) );
    $settings['offset']  = filter_input( INPUT_POST, 'offsetspilgames', FILTER_SANITIZE_STRING, array( "options" => array( "default" => '1') ) );
  }

  return $settings;
}

/**
 * Retrieve available distributor's categories mapped to MyArcadePlugin categories
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  array Distributor categories
 */
function myarcade_get_categories_spilgames() {
  return array(
    "Action"      => "Action,War",
    "Adventure"   => true,
    "Arcade"      => false,
    "Board Game"  => "Board and Card,Mahjong,Bubble Shooter,Match-3,Sudoku",
    "Casino"      => false,
    "Defense"     => false,
    "Customize"   => "Doll,Celebrity,Creation,Halloween,Kids,Painting",
    "Dress-Up"    => "Dress Up,Girls,Kissing,Make Up,Makeover,Princess",
    "Driving"     => "Racing,Uphill Racing",
    "Education"   => false,
    "Fighting"    => false,
    "Jigsaw"      => false,
    "Multiplayer" => false,
    "Other"       => "Fun,Animal,Skill,Hidden Objects,Cooking,Platform,Seasonal",
    "Puzzles"     => "Puzzle,Physics",
    "Rhythm"      => "Music",
    "Shooting"    => true,
    "Sports"      => true,
    "Strategy"    => "Simulation,Tower Defense,Time Management,Social"
  );
}

/**
 * Fetch SpilGames games
 *
 * @version 5.26.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_spilgames( $args = array() ) {
  global $wpdb;

  $defaults = array(
    'echo'     => false,
    'settings' => array()
  );

  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $new_games = 0;
  $add_game = false;

  $spilgames      = myarcade_get_fetch_options_spilgames();
  $spilgames_categories = myarcade_get_categories_spilgames();
  $feedcategories = get_option('myarcade_categories');

  // Init settings var's
  if ( ! empty( $settings ) ) {
    $settings = array_merge($spilgames, $settings);
  }
  else {
    $settings = $spilgames;
  }

  if ( !isset($settings['method']) ) {
    $settings['method'] = 'latest';
  }

  // Generate Feed URL
  $feed = add_query_arg( array("format" => "json"), trim( $settings['feed'] ) );

  // Check if there is a feed limit. If not, feed all games
  if ( ! empty( $settings['limit'] ) ) {
    $feed = add_query_arg( array("limit" => $settings['limit'] ), $feed );
  }

  // Add platform query
  $feed = add_query_arg( array( "platform" => $settings['platform'] ), $feed );

  if ( $settings['method'] == 'offset' ) {
    $feed = add_query_arg( array("page" => $settings['offset'] ), $feed );
  }

  // Add search query
  if ( ! empty( $settings['search'] ) ) {
    $feed = add_query_arg( array( "q" => $settings['search'] ), $feed );
  }

  // Add source attribute
  $feed = add_query_arg( array( "source" => 'MyArcadePlugin' ), $feed );

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  // Fetch Spilgames games
  $json_games = myarcade_fetch_games( array('url' => $feed, 'service' => 'json', 'echo' => $echo) );

  //====================================
  if ( ! empty($json_games->entries ) ) {

    $images = array('png', 'jpg', 'jpeg', 'gif', 'bmp');

    foreach ($json_games->entries as $game_obj) {

      $game = new stdClass();
      $game->uuid = $game_obj->id . '_spilgames';
      // Generate a game tag for this game
      $game->game_tag = md5($game_obj->id . 'spilgames');

      $add_game   = false;

      // Map ategories
      if ( ! empty($game_obj->category) ) {
        $categories = explode(',', $game_obj->category);
        $categories = array_map( 'trim', $categories );
      }
      else {
        $categories = array( 'Other' );
      }

      // Initialize the category string
      $categories_string = 'Other';

      foreach($categories as $gamecat) {
        $gamecat = htmlspecialchars_decode ( trim($gamecat) );

        foreach ( $feedcategories as $feedcat ) {
          if ( $feedcat['Status'] == 'checked' ) {
            if ( ! empty( $spilgames_categories[ $feedcat['Name'] ] ) ) {
              // Set category name to check
              if ( $spilgames_categories[ $feedcat['Name'] ] === true ) {
                $cat_name = $feedcat['Name'];
              }
              else {
                $cat_name = $spilgames_categories[ $feedcat['Name'] ];
              }
            }

            if ( strpos( $cat_name, $gamecat ) !== false ) {
              $add_game = true;
              $categories_string = $feedcat['Name'];
              break 2;
            }
          }
        }
      } // END - Category-Check

      if (!$add_game) {
        continue;
      }

      switch ( $spilgames['thumbsize'] ) {
        case '1': {
          $thumbnail_url = $game_obj->thumbnails->small;
          $ext = pathinfo( $thumbnail_url, PATHINFO_EXTENSION);
          if ( in_array( $ext, $images ) ) {
            break;
          }
        }
        case '2': {
          $thumbnail_url = $game_obj->thumbnails->medium;
          $ext = pathinfo( $thumbnail_url, PATHINFO_EXTENSION);
          if ( in_array( $ext, $images ) ) {
            break;
          }
        }
        case '3': {
          $thumbnail_url = $game_obj->thumbnails->large;
          $ext = pathinfo( $thumbnail_url, PATHINFO_EXTENSION);
          if ( in_array( $ext, $images ) ) {
            break;
          }
        }
        default : {
          // We did not find a valid thumbnail image
          // Use default image
          $thumbnail_url = MYARCADE_URL . "/images/noimage.png";
        }
      }

      // Check if this is a HTML5 game. If so, then change game type and generate an iframe code
      $extension = pathinfo( $game_obj->gameUrl , PATHINFO_EXTENSION );
      if ( strpos( $extension, 'htm' ) !== false ) {
        $game->type          = 'iframe';
      }
      else {
        $game->type          = 'spilgames';
      }

      $game->name          = esc_sql($game_obj->title);
      $game->slug          = myarcade_make_slug($game_obj->title);
      $game->description   = esc_sql($game_obj->description);
      $game->categs        = esc_sql($categories_string);
      $game->swf_url       = esc_sql($game_obj->gameUrl);
      $game->thumbnail_url = esc_sql($thumbnail_url);
      $game->width         = $game_obj->width;
      $game->height        = $game_obj->height;

      // Add game to the database
      if ( myarcade_add_fetched_game( $game, $args ) ) {
        $new_games++;
      }
    }
  }

  // Show, how many games have been fetched
  myarcade_fetched_message( $new_games, $echo );
}

/**
 * Return if games can be downloaded by this distirbutor
 *
 * @version 5.26.0
 * @since   5.26.0
 * @access  public
 * @return  bool True if games can be downloaded
 */
function myarcade_can_download_spilgames() {
  return false;
}
?>