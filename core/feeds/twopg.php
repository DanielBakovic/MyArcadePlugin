<?php
/**
 * 2 Player Games
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
function myarcade_settings_twopg() {

  $twopg = myarcade_get_settings( 'twopg' );
  ?>
  <h2 class="trigger"><?php _e("2 Player Games", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php printf( __( "%s distributes Flash games.", 'myarcadeplugin' ), '<a href="http://2pg.com" target="_blank">2 Player Games</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="twopg_url" value="<?php echo $twopg['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Fetch All Categories", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="checkbox" name="twopg_all_categories" value="true" <?php myarcade_checked($twopg['all_categories'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Activate this if you want to fetch all games independent of your activated categories.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="twopg_cron_publish" value="true" <?php myarcade_checked($twopg['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="twopg_cron_publish_limit" value="<?php echo $twopg['cron_publish_limit']; ?>" />
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
 * @version 5.27.0
 * @since   5.19.0
 * @access  public
 * @return  array Default settings
 */
function myarcade_default_settings_twopg() {
  return array(
    'feed'          => 'http://old.2pg.com/myarcadeplugin_feed.xml',
    'all_categories' => false,
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
  );
}

/**
 * Handle distributor settings update
 *
 * @version 5.19.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_twopg() {

  myarcade_check_settings_nonce();

  $twopg = array();
  $twopg['feed'] = (isset($_POST['twopg_url'])) ? esc_sql($_POST['twopg_url']) : '';
  $twopg['all_categories'] = (isset($_POST['twopg_all_categories'])) ? true : false;
  $twopg['cron_publish'] = (isset($_POST['twopg_cron_publish']) ) ? true : false;
  $twopg['cron_publish_limit'] = (isset($_POST['twopg_cron_publish_limit']) ) ? intval($_POST['twopg_cron_publish_limit']) : 1;
    // Update settings
    update_option('myarcade_twopg', $twopg);
}

/**
 * Fetch 2PG games
 *
 * @version 5.26.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_twopg($args) {
 global $wpdb;

  $defaults = array(
    'echo'     => false,
    'settings' => array()
  );

  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $new_games = 0;
  $add_game = false;

  $twopg  = myarcade_get_settings( 'twopg' );
  $feedcategories = get_option('myarcade_categories');

  // Init settings vars
  if ( !empty($settings) ) {
    $settings = array_merge($twopg, $settings);
  }
  else {
    $settings = $twopg;
  }

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  $games = myarcade_fetch_games( array(
      'url'     => $settings['feed'],
      'service' => 'xml',
      'echo'    => true
    )
  );

  if ( !empty($games) && isset($games->gameset) ) {
    foreach ($games->gameset->game as $game_obj) {

      $game = new stdClass();
      $game->uuid     = $game_obj->id . '_twopg';
      // Generate a game tag for this game
      $game->game_tag = md5( $game_obj->id . $game_obj->name . 'twopg' );

      $categories = explode( ',', $game_obj->category );

      if ( ! $settings['all_categories'] ) {
        $add_game = false;
        // Category-Check
        foreach ($feedcategories as $feedcat) {
          foreach ( $categories as $category ) {
            if ( ($feedcat['Name'] == $category) && ($feedcat['Status'] == 'checked') ) {
              $add_game = true;
              break;
            }
          }
          if ( $add_game ) {
            break;
          }
        }

        // Should we add this game?
        if ($add_game == false) { continue; }
      }

      // Decode URL
      $game_obj->gamecode = urldecode($game_obj->gamecode);

      // Check for file extension or embed code
      if ( strpos( $game_obj->gamecode, 'src=') !== FALSE ) {
        // This is an embed code game
        $game->type = 'embed';
      }
      else {
        $extension = pathinfo( $game_obj->gamecode , PATHINFO_EXTENSION );

        switch ( $extension ) {
          case 'dcr' : {
            $game->type = 'dcr';
          } break;

          case 'unity3d' : {
            $game->type = 'unity';
          }

          case 'html' : {
            $game->type = 'iframe';
          } break;

          default : {
            $game->type = 'twopg';
          } break;
        }
      }

      $game->name           = esc_sql( $game_obj->name );
      $game->slug           = myarcade_make_slug($game_obj->name);
      $game->description    = esc_sql($game_obj->description);
      $game->instructions    = esc_sql($game_obj->instructions);
      $game->categs         = esc_sql($game_obj->category);
      $game->thumbnail_url  = esc_sql($game_obj->thumbnail);
      $game->swf_url        = esc_sql($game_obj->gamecode);
      $game->width         = esc_sql($game_obj->width);
      $game->height        = esc_sql($game_obj->height);
      $game->screen1_url    = !empty($game_obj->screenshot_1) ? $game_obj->screenshot_1 : '';
      $game->screen2_url    = !empty($game_obj->screenshot_2) ? $game_obj->screenshot_2 : '';
      $game->screen3_url    = !empty($game_obj->screenshot_3) ? $game_obj->screenshot_3 : '';
      $game->screen4_url    = !empty($game_obj->screenshot_4) ? $game_obj->screenshot_4 : '';
      $game->tags           = ( !empty($game_obj->tags) ) ? esc_sql($game_obj->tags) : '';

      // Add game to the database
      if ( myarcade_add_fetched_game( $game, $args ) ) {
        $new_games++;
      }
    }
  }

  // Show, how many games have been fetched
  myarcade_fetched_message( $new_games, $echo );
}
?>