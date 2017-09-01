<?php
/**
 * Scirra
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
function myarcade_settings_scirra() {
  $scirra = myarcade_get_settings( 'scirra' );
  ?>
  <h2 class="trigger"><?php myarcade_premium_span(); _e("Scirra", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <?php myarcade_premium_message(); ?>
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php printf( __( "%s distributes Flash and HTML5 games.", 'myarcadeplugin' ), '<a href="https://www.scirra.com/arcade" target="_blank">Scirra</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="scirra_url" value="<?php echo $scirra['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Category", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="scirra_category" id="scirra_category">
              <option value="0" <?php myarcade_selected($scirra['category'], '0'); ?> ><?php _e("All Games", 'myarcadeplugin'); ?></option>
              <option value="3" <?php myarcade_selected($scirra['category'], '3'); ?> ><?php _e("Action", 'myarcadeplugin'); ?></option>
              <option value="2" <?php myarcade_selected($scirra['category'], '2'); ?> ><?php _e("Adventure", 'myarcadeplugin'); ?></option>
              <option value="9" <?php myarcade_selected($scirra['category'], '9'); ?> ><?php _e("Fighting", 'myarcadeplugin'); ?></option>
              <option value="10" <?php myarcade_selected($scirra['category'], '10'); ?> ><?php _e("Multiplayer", 'myarcadeplugin'); ?></option>
              <option value="4" <?php myarcade_selected($scirra['category'], '4'); ?> ><?php _e("Music", 'myarcadeplugin'); ?></option>
              <option value="1" <?php myarcade_selected($scirra['category'], '1'); ?> ><?php _e("Puzzle", 'myarcadeplugin'); ?></option>
              <option value="7" <?php myarcade_selected($scirra['category'], '7'); ?> ><?php _e("Racing", 'myarcadeplugin'); ?></option>
              <option value="11" <?php myarcade_selected($scirra['category'], '11'); ?> ><?php _e("RPG", 'myarcadeplugin'); ?></option>
              <option value="8" <?php myarcade_selected($scirra['category'], '8'); ?> ><?php _e("Shooting", 'myarcadeplugin'); ?></option>
              <option value="6" <?php myarcade_selected($scirra['category'], '6'); ?> ><?php _e("Sports", 'myarcadeplugin'); ?></option>
              <option value="5" <?php myarcade_selected($scirra['category'], '5'); ?> ><?php _e("Strategy", 'myarcadeplugin'); ?></option>
              <option value="13" <?php myarcade_selected($scirra['category'], '13'); ?> ><?php _e("Tutorial", 'myarcadeplugin'); ?></option>
              <option value="12" <?php myarcade_selected($scirra['category'], '12'); ?> ><?php _e("Other", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select which games you would like to fetch.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="scirra_cron_fetch" value="true" <?php myarcade_checked($scirra['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="scirra_cron_fetch_limit" value="<?php echo $scirra['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="scirra_cron_publish" value="true" <?php myarcade_checked($scirra['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="scirra_cron_publish_limit" value="<?php echo $scirra['cron_publish_limit']; ?>" />
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
function myarcade_default_settings_scirra() {
  return array(
    'feed'          =>  'https://www.scirra.com/arcade/api/games.json',
    'category'      => 0,
    'cron_fetch'    => false,
    'cron_fetch_limit' => '1',
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
function myarcade_save_settings_scirra() {

  myarcade_check_settings_nonce();

    // Scirra Settings
  $scirra = array();
  if ( isset($_POST['scirra_url'])) $scirra['feed'] = esc_url_raw($_POST['scirra_url']); else $scirra['feed'] = '';

  $scirra['category']            = filter_input( INPUT_POST, 'scirra_category', FILTER_VALIDATE_INT, array( "options" => array( "default" => 0) ) );
  $scirra['cron_fetch']          = (isset($_POST['scirra_cron_fetch'])) ? true : false;
  $scirra['cron_fetch_limit']    = (isset($_POST['scirra_cron_fetch_limit']) ) ? intval($_POST['scirra_cron_fetch_limit']) : 1;
  $scirra['cron_publish']        = (isset($_POST['scirra_cron_publish']) ) ? true : false;
  $scirra['cron_publish_limit']  = (isset($_POST['scirra_cron_publish_limit']) ) ? intval($_POST['scirra_cron_publish_limit']) : 1;
    // Update Settings
    update_option('myarcade_scirra', $scirra);
}

/**
 * Display distributor fetch games options
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_settings_scirra() {

  ?>
  <div class="myarcade_border white hide mabp_680" id="scirra">
    <?php myarcade_premium_message(); ?>
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
function myarcade_get_fetch_options_scirra() {

  // Get distributor settings
  $settings = myarcade_get_settings( 'scirra' );

  $settings['method'] = 'latest';
  $settings['offset'] = 0;
  $settings['limit']  = 100;
  $settings['orderby'] = 'PublishDateDESC';
  $settings['mobile'] = false;

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    $settings['method']  = filter_input( INPUT_POST, 'fetchmethodscirra' );
    $settings['offset']  = filter_input( INPUT_POST, 'offsetscirra' );
    $settings['limit']   = filter_input( INPUT_POST, 'limitscirra' );
    $settings['orderby'] = filter_input( INPUT_POST, 'orderbyscirra' );
    $settings['mobile'] =  filter_input( INPUT_POST, 'mobilescirra', FILTER_VALIDATE_BOOLEAN );
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
function myarcade_get_categories_scirra() {
  return array(
    "Action"      => "3",
    "Adventure"   => "2",
    "Arcade"      => false,
    "Board Game"  => false,
    "Casino"      => false,
    "Defense"     => false,
    "Customize"   => false,
    "Dress-Up"    => false,
    "Driving"     => "7",
    "Education"   => "13",
    "Fighting"    => "9",
    "Jigsaw"      => false,
    "Multiplayer" => "10",
    "Other"       => "12",
    "Puzzles"     => "1",
    "Rhythm"      => "4",
    "Shooting"    => "8",
    "Sports"      => "6",
    "Strategy"    => "5,11",
  );
}

/**
 * Fetch Scirra games
 *
 * @version 5.26.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_scirra( $args = array() ) {

  ?>
  <div class="myarcade_border white mabp_680">
    <?php myarcade_premium_message(); ?>
  </div>
  <?php
}

/**
 * Return game embed method
 *
 * @version 5.18.0
 * @since   5.18.0
 * @access  public
 * @return  string Embed Method
 */
function myarcade_embedtype_scirra() {
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
function myarcade_can_download_scirra() {
  return false;
}
?>