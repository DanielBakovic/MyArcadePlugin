<?php
/**
 * Softgames
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

/**
 * Save options function
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_softgames() {

  myarcade_check_settings_nonce();

  $settings = array();
  $settings['feed'] = esc_sql( filter_input( INPUT_POST, 'softgames_url' ) );
  $settings['category'] = filter_input( INPUT_POST, 'softgames_category' );
  $settings['publisher_id'] = filter_input( INPUT_POST, 'softgames_publisher_id' );
  $settings['thumbnail'] = filter_input( INPUT_POST, 'softgames_thumbnail' );
  $settings['language'] = filter_input( INPUT_POST, 'softgames_language' );
  $settings['cron_publish'] = filter_input( INPUT_POST, 'softgames_cron_publish', FILTER_VALIDATE_BOOLEAN );
  $settings['cron_publish_limit'] = filter_input( INPUT_POST, 'softgames_cron_publish_limit', FILTER_VALIDATE_INT, array( "options" => array( "default" => 1) ) );

  // Update settings
  update_option( 'myarcade_softgames', $settings );
}

/**
 * Display distributor settings on admin page
 *
 * @version 5.28.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_settings_softgames() {
  $softgames = myarcade_get_settings( 'softgames' );
  ?>
  <h2 class="trigger"><?php _e("Softgames", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php printf( __( "%s distributes HTML5 games.", 'myarcadeplugin' ), '<a href="http://softgames.de" target="_blank">Softgames</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="softgames_url" value="<?php echo $softgames['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Publisher ID", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="softgames_publisher_id" value="<?php echo $softgames['publisher_id']; ?>" />
          </td>
          <td><i><?php _e("Enter your Publisher ID if available.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Category", 'myarcadeplugin'); ?></h3></td></tr>

        <?php
        $softgames_categories = array(
          'all' => __("All games", 'myarcadeplugin' ),
          "Action" => "Action",
          "Arcade" => "Arcade",
          "Board" => "Board Games",
          "Brain Teaser" => "Brain Teaser",
          "Card" => "Card Games",
          "Classic" => "Classic",
          "Games for Girls" => "Games for Girls",
          "Halloween" => "Halloween",
          "Jump &#39;N&#39; Run" => "Jump 'N' Run",
          "Puzzle" => "Puzzles",
          "Racing" => "Racing",
          "Skill" => "Skill",
          "Sport" => "Sports",
          "Strategy" => "Strategy",
          "Word"  => "Word Games",
        );
        ?>
        <tr>
          <td>
            <select size="1" name="softgames_category" id="softgames_category">
              <?php foreach ( $softgames_categories as $key => $value ) : ?>
               <option value="<?php echo $key; ?>" <?php myarcade_selected( $softgames['category'], $key ); ?>><?php echo $value; ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td><i><?php _e("Select which games you would like to fetch.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Thumbnail Size", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="softgames_thumbnail" id="softgames_thumbnail">
              <option value="thumb" <?php myarcade_selected($softgames['thumbnail'], 'thumb'); ?> ><?php _e("60x60", 'myarcadeplugin'); ?></option>
              <option value="thumbBig" <?php myarcade_selected($softgames['thumbnail'], 'thumbBig'); ?> ><?php _e("120x120", 'myarcadeplugin'); ?></option>
              <option value="teaser" <?php myarcade_selected($softgames['thumbnail'], 'teaser'); ?> ><?php _e("300x180", 'myarcadeplugin'); ?></option>
              <option value="image620" <?php myarcade_selected($softgames['thumbnail'], 'image620'); ?> ><?php _e("520x520", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a thumbnail size.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Language", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="softgames_language" id="softgames_language">
              <option value="en" <?php myarcade_selected($softgames['language'], 'en'); ?> ><?php _e("English", 'myarcadeplugin'); ?></option>
              <option value="fr" <?php myarcade_selected($softgames['language'], 'fr'); ?> ><?php _e("French", 'myarcadeplugin'); ?></option>
              <option value="de" <?php myarcade_selected($softgames['language'], 'de'); ?> ><?php _e("German", 'myarcadeplugin'); ?></option>
              <option value="it" <?php myarcade_selected($softgames['language'], 'it'); ?> ><?php _e("Italian", 'myarcadeplugin'); ?></option>
              <option value="pl" <?php myarcade_selected($softgames['language'], 'pl'); ?> ><?php _e("Polish", 'myarcadeplugin'); ?></option>
              <option value="pt" <?php myarcade_selected($softgames['language'], 'pt'); ?> ><?php _e("Portuguese", 'myarcadeplugin'); ?></option>
              <option value="ru" <?php myarcade_selected($softgames['language'], 'ru'); ?> ><?php _e("Russian", 'myarcadeplugin'); ?></option>
              <option value="es" <?php myarcade_selected($softgames['language'], 'es'); ?> ><?php _e("Spanish", 'myarcadeplugin'); ?></option>
              <option value="tr" <?php myarcade_selected($softgames['language'], 'tr'); ?> ><?php _e("Turkish", 'myarcadeplugin'); ?></option>
              <option value="th" <?php myarcade_selected($softgames['language'], 'th'); ?> ><?php _e("Thai", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a game language.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="softgames_cron_publish" value="true" <?php myarcade_checked($softgames['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="softgames_cron_publish_limit" value="<?php echo $softgames['cron_publish_limit']; ?>" />
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
 * Load default distributor settings
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  array Default settings
 */
function myarcade_default_settings_softgames() {
  return array(
    'feed'          => 'http://kirk.softgames.de/categories/latest-games.json/',
    'publisher_id'  => '',
    'category'      => 'all',
    'thumbnail'     => 'thumbBig',
    'language'      => 'en',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
  );
}

/**
 * Fetch games
 *
 * @version 5.27.1
 * @since   5.19.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_softgames( $args = array() ) {
  global $wpdb;

  $defaults = array(
    'echo'     => false,
    'settings' => array(),
  );

  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $new_games = 0;
  $add_game = false;

  $softgames = myarcade_get_settings( 'softgames' );

  $feedcategories = get_option( 'myarcade_categories' );

  // Init settings var's
  if ( ! empty($settings) ) {
    $settings = array_merge( $softgames, $settings );
  }
  else {
    $settings = $softgames;
  }

  if ( empty( $settings['publisher_id'] ) ) {
    // Use our default affiliate credentials
    $settings['publisher_id'] = 'myarcadeplugin';
  }

  // Generate Feed URL
  $settings['feed'] = add_query_arg( array( "p" => $settings['publisher_id'] ), trim( $settings['feed'] ) );
  $settings['feed'] = add_query_arg( array( "locale" => $settings['language'] ), trim( $settings['feed'] ) );

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  // Fetch games
  $json_games = myarcade_fetch_games( array( 'url' => $settings['feed'], 'service' => 'json', 'echo' => $echo ) );

  //====================================
  if ( !empty($json_games->channel->games ) ) {
    foreach ( $json_games->channel->games as $game_obj ) {

      $game = new stdClass();
      $game->uuid     = crc32( $game_obj->title ) . '_softgames';
      // Generate a game tag for this game
      $game->game_tag = md5( $game_obj->title . 'softgames' );

      $add_game   = false;

      // Transform some categories
      $categories_string = 'Other';

      if ( preg_match('[Action|Jump]', $game_obj->type ) ) {
        $categories_string = 'Action';
      }
      elseif ( preg_match('[Arcade]',  $game_obj->type ) ) {
        $categories_string = 'Arcade';
      }
      elseif ( preg_match('[Board|Card|Classic]', $game_obj->type ) ) {
        $categories_string = 'Board Game';
      }
      elseif ( preg_match('[Girls]', $game_obj->type ) ) {
        $categories_string = 'Customize';
      }
      elseif ( preg_match('[Puzzles|Word]', $game_obj->type ) ) {
        $categories_string = 'Puzzles';
      }
      elseif ( preg_match('[Sports]', $game_obj->type ) ) {
        $categories_string = 'Sports';
      }
      elseif ( preg_match('[Racing]', $game_obj->type ) ) {
        $categories_string = 'Driving';
      }
      else {
        $categories_string = 'Other';
      }

      foreach ( $feedcategories as $feedcat ) {
        if ( ($feedcat['Name'] == $categories_string) && ($feedcat['Status'] == 'checked') ) {
          $add_game = true;
          break;
        }
      }

      if ( ! $add_game ) {
        continue;
      }

      // We need valid game dimensions
      // Init dimensions for a portrait game
      $game->width = 588;
      $game->height = 800;

      // Now check game dimensions and overwrite values if required
      if ( isset( $game_obj->iframeMaxResolution ) ) {
        $dimensions = explode( 'x', $game_obj->iframeMaxResolution );

        if ( ! empty($dimensions) ) {
          $game->width = esc_sql( $dimensions[0] );
          $game->height = esc_sql( $dimensions[1] );
        }
      }
      elseif ( isset( $game_obj->landscape) && $game_obj->landscape ) {
        $game->width = 800;
        $game->height = 588;
      }

      $game_url_parts = explode( '?p', $game_obj->link );

      $game_url = $game_url_parts[0];

      $language = $settings['language'];
      if ( isset( $game_obj->descriptions[0]->$language ) ) {
        $description = $game_obj->descriptions[0]->$language;
      }
      else {
        $description = '';
      }

      $game->type        = 'softgames';
      $game->name        = esc_sql($game_obj->title);
      $game->slug        = myarcade_make_slug($game_obj->title);
      $game->description = esc_sql($description);
      $game->categs      = $categories_string;
      $game->swf_url     = esc_sql( myarcade_maybe_ssl( $game_url ) );
      $game->screen1_url = ! empty($game_obj->screenshots->screenshoturl_1) ? myarcade_maybe_ssl( $game_obj->screenshots->screenshoturl_1 ) : '';
      $game->screen2_url = ! empty($game_obj->screenshots->screenshoturl_2) ? myarcade_maybe_ssl( $game_obj->screenshots->screenshoturl_2 ) : '';
      $game->screen3_url = ! empty($game_obj->screenshots->screenshoturl_3) ? myarcade_maybe_ssl( $game_obj->screenshots->screenshoturl_3 ) : '';

      $thumb_size = $settings['thumbnail'];
      if ( ! empty( $game_obj->$thumb_size ) ) {
        $game->thumbnail_url = esc_sql( myarcade_maybe_ssl( $game_obj->$thumb_size ) );
      }
      else {
        $game->thumbnail_url = esc_sql( myarcade_maybe_ssl( $game_obj->thumbBig ) );
      }

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
 * Return game embed method
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  string Embed Method
 */
function myarcade_embedtype_softgames() {
  return 'iframe';
}

/**
 * Return if games can be downloaded by this distirbutor
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  bool True if games can be downloaded
 */
function myarcade_can_download_softgames() {
  return false;
}
?>