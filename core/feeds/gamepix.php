<?php
/**
 * GamePix Feed - https://games.gamepix.com/
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @package MyArcadePlugin/Feeds
 */

// No direct Access.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Display distributor settings on admin page
 */
function myarcade_settings_gamepix() {

	$gamepix = MyArcade()->get_settings( 'gamepix' );

  ?>
	<h2 class="trigger"><?php _e( 'GamePix', 'myarcadeplugin' ); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
							<?php printf( __( '%s distributes HTML5 games.', 'myarcadeplugin' ), '<a href="http://gamepix.com" target="_blank">GamePix</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>

				<tr><td colspan="2"><h3><?php _e( 'Site ID', 'myarcadeplugin' ); ?></h3></td></tr>
				<tr>
					<td>
						<input type="text" size="40"  name="gamepix_site_id" value="<?php echo esc_attr( $gamepix['site_id'] ); ?>" />
					</td>
					<td><i><?php _e( 'Enter your Site ID if available.', 'myarcadeplugin'); ?></i></td>
        </tr>

				<tr><td colspan="2"><h3><?php _e( 'Automated Game Publishing', 'myarcadeplugin' ); ?></h3></td></tr>

        <tr>
          <td>
						<input type="checkbox" name="gamepix_cron_publish" value="true" <?php myarcade_checked( $gamepix['cron_publish'], true ); ?> /><label class="opt">&nbsp;<?php _e( 'Yes', 'myarcadeplugin' ); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

				<tr><td colspan="2"><h4><?php _e( 'Publish Games', 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
						<input type="text" size="40"  name="gamepix_cron_publish_limit" value="<?php echo esc_attr( $gamepix['cron_publish_limit'] ); ?>" />
          </td>
					<td><i><?php _e( 'How many games should be published on every cron trigger?', 'myarcadeplugin' ); ?></i></td>
        </tr>

      </table>
			<input class="button button-primary" id="submit" type="submit" name="submit" value="<?php _e( 'Save Settings', 'myarcadeplugin' ); ?>" />
    </div>
  </div>
  <?php
}

/**
 * Retrieve distributor's default settings
 *
 * @return  array Default settings
 */
function myarcade_default_settings_gamepix() {
  return array(
		'feed'               => 'https://feeds.gamepix.com/v2/json',
    'site_id'       => '20015',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
  );
}

/**
 * Handle distributor settings update
 */
function myarcade_save_settings_gamepix() {

  myarcade_check_settings_nonce();

  $settings = array();
	$settings['site_id']            = ( isset( $_POST['gamepix_site_id'] ) ) ? $_POST['gamepix_site_id'] : '20015';
  $settings['cron_publish'] = (isset($_POST['gamepix_cron_publish']) ) ? true : false;
  $settings['cron_publish_limit'] = (isset($_POST['gamepix_cron_publish_limit']) ) ? intval($_POST['gamepix_cron_publish_limit']) : 1;

	// Update settings.
  update_option('myarcade_gamepix', $settings);
}

/**
 * Generate an options array with submitted fetching parameters
 *
 * @return array Fetching options.
 */
function myarcade_get_fetch_options_gamepix() {

	// Get distributor settings.
	$settings = MyArcade()->get_settings( 'gamepix' );
	$defaults = myarcade_default_settings_gamepix();
	$settings = wp_parse_args( $settings, $defaults );

	$settings['feed']   = $defaults['feed'];
	$settings['limit']  = filter_input( INPUT_POST, 'limitgamepix', FILTER_VALIDATE_INT, array( 'options' => array( 'default' => 96 ) ) );
	$settings['offset'] = filter_input( INPUT_POST, 'offsetgamepix', FILTER_UNSAFE_RAW, array( 'options' => array( 'default' => '1' ) ) );

	return $settings;
}

/**
 * Display distributor fetch games options.
 */
function myarcade_fetch_settings_gamepix() {

	$gamepix = myarcade_get_fetch_options_gamepix();

	ob_start();
	?>
	<select name="limitgamepix">
		<option value="12" <?php esc_attr( selected( $gamepix['limit'], 12 ) ); ?>>12</option>
		<option value="24" <?php esc_attr( selected( $gamepix['limit'], 24 ) ); ?>>24</option>
		<option value="48" <?php esc_attr( selected( $gamepix['limit'], 48 ) ); ?>>48</option>
		<option value="96" <?php esc_attr( selected( $gamepix['limit'], 96 ) ); ?>>96</option>
	</select>
	<?php
	$option_limit = ob_get_clean();
	?>

	<div class="myarcade_border white hide mabp_680" id="gamepix">
		<div class="myarcade_border" style="float:left;padding-top: 5px;background-color: #F9F9F9">
			<?php printf( esc_html__( 'Fetch %s games %sfrom page %s', 'myarcadeplugin' ), $option_limit, '<span id="offsgamepix">', '<input id="radiooffsgamepix" type="number" name="offsetgamepix" value="' . esc_attr( $gamepix['offset'] ) . '" /> </span>' ); ?>
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
function myarcade_get_categories_gamepix() {
  return array(
		'Action'      => 'action,stickman,parkour,robots,tanks,snake,monster,dinosaur,fantasy-flight,runner,battle,zombie,war,gangster,horror',
		'Adventure'   => 'adventure',
		'Arcade'      => 'arcade',
		'Board Game'  => 'board,2048',
		'Casino'      => 'card,casino',
		'Defense'     => false, /* not found */
		'Customize'   => false, /* not found */
		'Dress-Up'    => 'fashion,dress-up',
		'Driving'     => 'motorcycle,racing,car,driving',
		'Education'   => 'kids,math,educational',
		'Fighting'    => 'fighting',
		'Jigsaw'      => false, /* not found */
		'Multiplayer' => 'two-player',
		'Other'       => 'retro,animal,fun,idle,mobile,animal,scary,money,clicker,archery,granny,pixel,cooking,addictive,games-for-girls,io',
		'Puzzles'     => 'trivia,puzzle,memory,match-3,drawing,hyper-casual,casual,jewel,jigsaw-puzzles,hidden-object,tap,classics',
		'Rhythm'      => 'music',
		'Shooting'    => 'shooter,first-person-shooter',
		'Sports'      => 'ball,sports,skateboard,soccer,basketball,bowling,fishing',
		'Strategy'    => 'strategy,building,skill,brain,skibidi-toilet,tetris,management,platformer,simulation',
  );
}

/**
 * Fetch FlashGameDistribution games
 *
 * @param array $args Fetching parameters.
 */
function myarcade_feed_gamepix( $args = array() ) {

  $defaults = array(
    'echo'     => false,
    'settings' => array(),
  );

  $args = wp_parse_args( $args, $defaults );
  extract($args);

  $new_games = 0;
  $add_game = false;

	$gamepix            = myarcade_get_fetch_options_gamepix();
	$gamepix_categories = myarcade_get_categories_gamepix();
  $feedcategories     = get_option('myarcade_categories');

	// Init settings vars.
  if ( !empty($settings) ) {
    $settings = array_merge($gamepix, $settings);
	} else {
    $settings = $gamepix;
  }

  if ( empty( $settings['site_id']) ) {
		// Use our default affiliate credentials.
    $settings['site_id'] = '20015';
  }

	$settings['feed'] = add_query_arg( array( 'sid' => $settings['site_id'] ), trim( $settings['feed'] ) );
	$settings['feed'] = add_query_arg( array( 'pagination' => $settings['limit'] ), $settings['feed'] );
	$settings['feed'] = add_query_arg( array( 'page' => $settings['offset'] ), $settings['feed'] );

	// Include required fetch functions.
	require_once MYARCADE_CORE_DIR . '/fetch.php';

	// Fetch games.
	$json_games = myarcade_fetch_games(
		array(
			'url'     => $settings['feed'],
			'service' => 'json',
			'echo'    => $echo,
		)
	);

	if ( ! empty( $json_games->items ) ) {
		foreach ( $json_games->items as $game_obj ) {

      $game = new stdClass();
      $game->uuid     = $game_obj->id . '_gamepix';

			// Generate a game tag for this game.
      $game->game_tag = md5( $game_obj->id . 'gamepix' );

      $add_game   = false;

			// Transform some categories.
      $categories = explode( ',', $game_obj->category );
      $categories_string = 'Other';

			// Loop trough current game categories.
      foreach( $categories as $gamecat ) {
				// Loop trough MyArcade categories.
        foreach ( $feedcategories as $feedcat ) {
					// Check if MyArcade category is active.
					if ( 'checked' === $feedcat['Status'] ) {
						$cat_name = false;

						// Ceck if Gamepix privides this category?
            if ( ! empty( $gamepix_categories[ $feedcat['Name'] ] ) ) {
							// Check if Gamepix uses the same name or if we have a mapping.
							if ( true === $gamepix_categories[ $feedcat['Name'] ] ) {
                $cat_name = $feedcat['Name'];
							} else {
                $cat_name = $gamepix_categories[ $feedcat['Name'] ];
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

      $game->type          = 'gamepix';
      $game->name          = esc_sql($game_obj->title);
      $game->slug          = myarcade_make_slug($game_obj->title);
      $game->description   = esc_sql($game_obj->description);
      $game->categs        = $categories_string;
      $game->swf_url       = esc_sql( strtok( $game_obj->url, '?' ) );
      $game->width         = esc_sql($game_obj->width);
      $game->height        = esc_sql($game_obj->height);

			$game->thumbnail_url = esc_sql( $game_obj->image );
			$game->screen1_url   = esc_sql( $game_obj->banner_image );

			$game->leaderboard_enabled = 1;
			$game->highscore_type      = 'DESC';

			// Add game to the database.
      if ( myarcade_add_fetched_game( $game, $args ) ) {
        $new_games++;
      }
    }
  }

	// Show, how many games have been fetched.
  myarcade_fetched_message( $new_games, $echo );
}

/**
 * Return game embed method
 *
 * @return  string Embed Method
 */
function myarcade_embedtype_gamepix() {
  return 'iframe';
}

/**
 * Return if games can be downloaded by this distirbutor
 *
 * @return  bool True if games can be downloaded
 */
function myarcade_can_download_gamepix() {
  return false;
}
