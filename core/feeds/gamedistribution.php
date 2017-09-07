<?php
/**
 * GameDistribution - http://www.gamedistribution.com/games/
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @license http://myarcadeplugin.com
 */

/**
 * Copyright @ Daniel Bakovic - contact@myarcadeplugin.com
 * Do not modify! Do not sell! Do not distribute! -
 * Check our license Terms!
 */

/**
 * Save options
 *
 * @return  void
 */
function myarcade_save_settings_gamedistribution() {

  myarcade_check_settings_nonce();

  $settings = array();
  $settings['feed'] = esc_sql( filter_input( INPUT_POST, 'gamedistribution_url' ) );
  $settings['category'] = filter_input( INPUT_POST, 'gamedistribution_category' );

  $settings['cron_fetch'] = filter_input( INPUT_POST, 'gamedistribution_cron_fetch', FILTER_VALIDATE_BOOLEAN );
  $settings['cron_fetch_limit'] = filter_input( INPUT_POST, 'gamedistribution_cron_fetch_limit', FILTER_VALIDATE_INT, array( "options" => array( "default" => 1) ) );

  $settings['cron_publish'] = filter_input( INPUT_POST, 'gamedistribution_cron_publish', FILTER_VALIDATE_BOOLEAN );
  $settings['cron_publish_limit'] = filter_input( INPUT_POST, 'gamedistribution_cron_publish_limit', FILTER_VALIDATE_INT, array( "options" => array( "default" => 1) ) );

  // Update settings
  update_option( 'myarcade_gamedistribution', $settings );
}

/**
 * Display distributor settings on admin page
 *
 * @return  void
 */
function myarcade_settings_gamedistribution() {
  $gamedistribution = myarcade_get_settings( 'gamedistribution' );
  ?>
  <h2 class="trigger"><?php _e( "GameDistribution", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
               <?php printf( __( "%s distributes Flash and HTML5 games. In order to earn money with GameDistribution's games you will need to join the partner program %shere%s", 'myarcadeplugin' ), '<a href="http://www.gamedistribution.com/" target="_blank">GameDistribution</a>', '<a href="http://www.gamedistribution.com/joinus" target="_blank">', '</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="gamedistribution_url" value="<?php echo $gamedistribution['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Category", 'myarcadeplugin'); ?></h3></td></tr>

        <?php
        $gamedistribution_categories = array(
          'all' => __( "All games", 'myarcadeplugin' ),
          "1 Player" => "1 Player",
          "2 Player" => "2 Player",
          "3D" => "3D",
          "Action" => "Action",
          "Addicting" => "Addicting",
          "Adventure" => "Adventure",
          "Animal" => "Animal",
          "Arcade" => "Arcade",
          "Basketball" => "Basketball",
          "Bejeweled" => "Bejeweled",
          "Bike" => "Bike",
          "Board" => "Board",
          "Bubble Shooter" => "Bubble Shooter",
          "Car" => "Car",
          "Card" => "Card",
          "Cars" => "Cars",
          "Celebrity" => "Celebrity",
          "Cooking" => "Cooking",
          "Creation" => "Creation",
          "Dating" => "Dating",
          "Decoration" => "Decoration",
          "Defense" => "Defense",
          "Difference" => "Difference",
          "Drawing" => "Drawing",
          "Dress Up" => "Dress Up",
          "Educational" => "Educational",
          "Escape" => "Escape",
          "Fashion" => "Fashion",
          "Flight" => "Flight",
          "Flying" => "Flying",
          "Football" => "Football",
          "Fun" => "Fun",
          "Funny" => "Funny",
          "Girls" => "Girls",
          "Halloween" => "Halloween",
          "Hidden Objects" => "Hidden Objects",
          "HTML5" => "HTML5",
          "Jigsaw Puzzle" => "Jigsaw Puzzle",
          "Kids" => "Kids",
          "Mahjong" => "Mahjong",
          "Make Over" => "Make Over",
          "Make up" => "Make up",
          "Management" => "Management",
          "Manicure &amp; Pedicure" => "Manicure &amp; Pedicure",
          "Match-3" => "Match-3",
          "Mathematical" => "Mathematical",
          "Memory" => "Memory",
          "Motorbike" => "Motorbike",
          "Multiplayer" => "Multiplayer",
          "Nail" => "Nail",
          "Parking" => "Parking",
          "Physics" => "Physics",
          "Platform" => "Platform",
          "Point And Click" => "Point And Click",
          "Princess" => "Princess",
          "Puzzle" => "Puzzle",
          "Quiz" => "Quiz",
          "Racing" => "Racing",
          "Running" => "Running",
          "Seasonal" => "Seasonal",
          "Shoot 'Em Up" => "Shoot 'Em Up",
          "Shooter" => "Shooter",
          "Shooting" => "Shooting",
          "Shopping" => "Shopping",
          "Simulation" => "Simulation",
          "Skill" => "Skill",
          "Soccer" => "Soccer",
          "Sports" => "Sports",
          "Strategy" => "Strategy",
          "Sudoku" => "Sudoku",
          "Super Hero" => "Super Hero",
          "Time Management" => "Time Management",
          "Tower Defense" => "Tower Defense",
          "Truck" => "Truck",
          "Uphill Racing" => "Uphill Racing",
          "War" => "War",
          "Wedding" => "Wedding",
        );
        ?>
        <tr>
          <td>
            <select size="1" name="gamedistribution_category" id="gamedistribution_category">
              <?php foreach ( $gamedistribution_categories as $key => $value ) : ?>
               <option value="<?php echo $key; ?>" <?php myarcade_selected( $gamedistribution['category'], $key ); ?>><?php echo $value; ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td><i><?php _e("Select which games you would like to fetch.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="checkbox" name="gamedistribution_cron_fetch" value="true" <?php myarcade_checked( $gamedistribution['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="gamedistribution_cron_fetch_limit" value="<?php echo $gamedistribution['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="gamedistribution_cron_publish" value="true" <?php myarcade_checked($gamedistribution['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="gamedistribution_cron_publish_limit" value="<?php echo $gamedistribution['cron_publish_limit']; ?>" />
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
 * @return  array Default settings
 */
function myarcade_default_settings_gamedistribution() {
  return array(
    'feed'          => 'http://games.gamedistribution.com/All/',
    'limit'         => '40',
    'category'      => 'all',
    'cron_fetch'    => false,
    'cron_fetch_limit' => '1',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
  );
}

/**
 * Generate an options array with submitted fetching parameters
 *
 * @return  array Fetching options
 */
function myarcade_get_fetch_options_gamedistribution() {

  // Get distributor settings
  $settings = myarcade_get_settings( 'gamedistribution' );
  $defaults = myarcade_default_settings_gamedistribution();
  $settings = wp_parse_args( $settings, $defaults );

  $settings['method'] = 'latest';
  $settings['offset'] = 1;

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    $settings['limit']   = filter_input( INPUT_POST, 'limitgamedistribution', FILTER_VALIDATE_INT, array( "options" => array( "default" => 40 ) ) );
    $settings['method']  = filter_input( INPUT_POST, 'fetchmethodgamedistribution', FILTER_SANITIZE_STRING, array( "options" => array( "default" => 'latest') ) );
    $settings['offset']  = filter_input( INPUT_POST, 'offsetgamedistribution', FILTER_SANITIZE_STRING, array( "options" => array( "default" => '1') ) );
  }

  return $settings;
}

/**
 * Display distributor fetch games options
 *
 * @return  void
 */
function myarcade_fetch_settings_gamedistribution() {

  $gamedistribution = myarcade_get_fetch_options_gamedistribution();
  ?>

  <div class="myarcade_border white hide mabp_680" id="gamedistribution">
    <div style="float:left;width:150px;">
      <input type="radio" name="fetchmethodgamedistribution" value="latest" <?php myarcade_checked($gamedistribution['method'], 'latest');?>>
    <label><?php _e("Latest Games", 'myarcadeplugin'); ?></label>
    <br />
    <input type="radio" name="fetchmethodgamedistribution" value="offset" <?php myarcade_checked($gamedistribution['method'], 'offset');?>>
    <label><?php _e("Use Offset", 'myarcadeplugin'); ?></label>
    </div>
    <div class="myarcade_border" style="float:left;padding-top: 5px;background-color: #F9F9F9">
    Fetch <input type="number" name="limitgamedistribution" value="<?php echo $gamedistribution['limit']; ?>" /> games <span id="offsgamedistribution" class="hide">from page <input id="radiooffsgamedistribution" type="number" name="offsetgamedistribution" value="<?php echo $gamedistribution['offset']; ?>" /> </span>
    </div>
    <div class="clear"></div>
  </div>
  <?php
}

/**
 * Retrieve available distributor's categories mapped to MyArcadePlugin categories
 *
 * @return  array Distributor categories
 */
function myarcade_get_categories_gamedistribution() {
  return array(
    "Action"      => true,
    "Adventure"   => true,
    "Arcade"      => true,
    "Board Game"  => "Board,Card,Bejeweled,Mahjong",
    "Casino"      => false,
    "Defense"     => true,
    "Customize"   => "Fashion,Creation,Make Over,Make up,Manicure &amp; Pedicure,Nail",
    "Dress-Up"    => "Celebrity,Dress Up,Girls,Princess",
    "Driving"     => "Bike,Car,Cars,Motorbike,Parking,Racing,Truck,Uphill Racing",
    "Education"   => "Educational,Kids,Mathematical",
    "Fighting"    => false,
    "Jigsaw"      => "Jigsaw Puzzle",
    "Multiplayer" => "2 Player,Multiplayer",
    "Other"       => "3D,Addicting,Animal,Cooking,Dating,Decoration,1 Player,Drawing,Fun,Funny,Halloween,HTML5,Running,Seasonal,Shopping,Skill,Super Hero,Wedding",
    "Puzzles"     => "Difference,Hidden Objects,Match-3,Memory,Point And Click,Puzzle,Sudoku",
    "Rhythm"      => false,
    "Shooting"    => "Shoot 'Em Up,Shooter,Shooting,War",
    "Sports"      => "Basketball,Football,Soccer,Sports",
    "Strategy"    => "Bubble Shooter,Escape,Flight,Flying,Management,Physics,Platform,Quiz,Simulation,Strategy,Time Management,Tower Defense
",
  );
}

/**
 * Fetch games
 *
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_gamedistribution( $args = array() ) {
  global $wpdb;

  $defaults = array(
    'echo'     => false,
    'settings' => array(),
  );

  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $new_games = 0;
  $add_game = false;

  $gamedistribution = myarcade_get_fetch_options_gamedistribution();
  $gamedistribution_categories = myarcade_get_categories_gamedistribution();
  $feedcategories = get_option( 'myarcade_categories' );

  // Init settings var's
  if ( ! empty($settings) ) {
    $settings = array_merge( $gamedistribution, $settings );
  }
  else {
    $settings = $gamedistribution;
  }

  if ( ! isset($settings['method']) ) {
    $settings['method'] = 'latest';
  }

  $feed = add_query_arg( array( "format" => "json" ), trim( $settings['feed'] ) );

  // Check if there is a feed limit. If not, feed all games
  if ( ! empty( $settings['limit'] ) ) {
    $feed = add_query_arg( array( "limit" => $settings['limit'] ), $feed );
  }

  $feed = add_query_arg( array("offset" => $settings['offset'] ), $feed );

  if ( $settings['category'] != 'all' ) {
    $feed = add_query_arg( array("category" => rawurlencode( $settings['category'] ) ), $feed );
  }

  // Include required fetch functions
  require_once( MYARCADE_CORE_DIR . '/fetch.php' );

  // Fetch games
  $json_games = myarcade_fetch_games( array( 'url' => trim( $feed ), 'service' => 'json', 'echo' => $echo ) );

  //====================================
  if ( !empty($json_games ) ) {
    foreach ( $json_games as $game_obj ) {

      $game = new stdClass();
      $game->uuid     = crc32( $game_obj->Title ) . '_gamedistribution';
      // Generate a game tag for this game
      $game->game_tag = md5( $game_obj->Title . 'gamedistribution' );

      $add_game   = false;

      // Map categories
      if ( ! empty( $game_obj->CatTitle ) ) {
        $categories = explode( ',', $game_obj->CatTitle );
        $categories = array_map( 'trim', $categories );
      }
      else {
        $categories = array( 'Other' );
      }

      // Initialize the category string
      $categories_string = 'Other';

      foreach( $categories as $gamecat ) {
        $gamecat = htmlspecialchars_decode( $gamecat );

        foreach ( $feedcategories as $feedcat ) {
          if ( $feedcat['Status'] == 'checked' ) {
            if ( ! empty( $gamedistribution_categories[ $feedcat['Name'] ] ) ) {
              // Set category name to check
              if ( $gamedistribution_categories[ $feedcat['Name'] ] === true ) {
                $cat_name = $feedcat['Name'];
              }
              else {
                $cat_name = $gamedistribution_categories[ $feedcat['Name'] ];
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


      if ( ! $add_game ) {
        continue;
      }

      if ( "5" == $game_obj->GameType ) {
        $game->type = 'gamedistribution';
      }
      else {
        $game->type = "custom";
      }

      $game->name           = esc_sql( $game_obj->Title );
      $game->slug           = myarcade_make_slug( $game_obj->Title );
      $game->description    = esc_sql( $game_obj->Description );
      $game->categs         = $categories_string;
      $game->width          = intval( $game_obj->Width );
      $game->height         = intval( $game_obj->Height );
      $game->swf_url        = esc_sql( $game_obj->ExternalURL );
      $game->thumbnail_url  = esc_sql( $game_obj->ExternalThumbURL );
      $game->tags           = esc_sql( $game_obj->Tags );

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
 * @return  string Embed Method
 */
function myarcade_embedtype_gamedistribution() {
  return 'iframe';
}

/**
 * Return if games can be downloaded by this distirbutor
 *
 * @return  bool True if games can be downloaded
 */
function myarcade_can_download_gamedistribution() {
  return false;
}
?>