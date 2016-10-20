<?php
/**
 * FlashGameDistribution
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
function myarcade_settings_fgd() {
  $fgd = myarcade_get_settings( 'fgd' );
  ?>
  <h2 class="trigger"><?php myarcade_premium_img() ?> <?php _e("FlashGameDistribution (FGD)", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <?php myarcade_premium_message() ?>
            <br />
            <i>
              <?php printf( __( "%s distributes Flash games.", 'myarcadeplugin' ), '<a href="http://flashgamedistribution.com" target="_blank">FlashGameDistribution</a>' ); ?>
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="fgdurl" value="<?php echo $fgd['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Fetch Games", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="fgdlimit" value="<?php echo $fgd['limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched at once.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="fgd_cron_fetch" value="true" <?php myarcade_checked($fgd['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="fgd_cron_fetch_limit" value="<?php echo $fgd['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="fgd_cron_publish" value="true" <?php myarcade_checked($fgd['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="fgd_cron_publish_limit" value="<?php echo $fgd['cron_publish_limit']; ?>" />
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
function myarcade_default_settings_fgd() {
  return array(
    'feed'          => 'http://flashgamedistribution.com/feed',
    'cid'           => '',
    'hash'          => '',
    'autopost'      => false,
    'limit'         => '50',
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
 * @version 5.19.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_fgd() {

  myarcade_check_settings_nonce();

  $fgd = array();
  $fgd['feed'] = esc_url_raw( filter_input( INPUT_POST, 'fgdurl') );
  $fgd['limit'] = (isset($_POST['fgdlimit'])) ? intval($_POST['fgdlimit']) : '50';
  $fgd['cron_fetch']          = (isset($_POST['fgd_cron_fetch'])) ? true : false;
  $fgd['cron_fetch_limit']    = (isset($_POST['fgd_cron_fetch_limit']) ) ? intval($_POST['fgd_cron_fetch_limit']) : 1;
  $fgd['cron_publish']        = (isset($_POST['fgd_cron_publish']) ) ? true : false;
  $fgd['cron_publish_limit']  = (isset($_POST['fgd_cron_publish_limit']) ) ? intval($_POST['fgd_cron_publish_limit']) : 1;
  // Update Settings
  update_option('myarcade_fgd', $fgd);
}

/**
 * Display distributor fetch games options
 *
 * @version 5.19.0
 * @since   5.19.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_settings_fgd() {

  $fgd = myarcade_get_fetch_options_fgd();
  ?>
  <div class="myarcade_border white hide mabp_680" id="fgd">
    <div style="float:left;width:150px;">
      <input type="radio" name="fetchmethodfgd" value="latest" <?php myarcade_checked($fgd['method'], 'latest');?>>
    <label><?php _e("Latest Games", 'myarcadeplugin'); ?></label>
    <br />
    <input type="radio" name="fetchmethodfgd" value="offset" <?php myarcade_checked($fgd['method'], 'offset');?>>
    <label><?php _e("Use Offset", 'myarcadeplugin'); ?></label>
    </div>
    <div class="myarcade_border" style="float:left;padding-top: 5px;background-color: #F9F9F9">
    Fetch <input type="number" name="limitfgd" value="<?php echo $fgd['limit']; ?>" /> games <span id="offsfgd" class="hide">from offset <input id="radiooffsfgd" type="number" name="offsetfgd" value="<?php echo $fgd['offset']; ?>" /> </span>
    </div>
    <div class="clear"></div>
    <input type="checkbox" name="gamersafe" id="gamersafe" value="true" <?php myarcade_checked( $fgd['gamersafe'], true) ?> /> <?php _e("GamerSafe (Score Games)", 'myarcadeplugin'); ?>

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
function myarcade_get_fetch_options_fgd() {

  // Get distributor settings
  $settings = myarcade_get_settings( 'fgd' );

  // Set default fetching options
  $settings['method']     = 'latest';
  $settings['offset']     = 1;
  $settings['gamersafe']  = false;

  if ( 'start' == filter_input( INPUT_POST, 'fetch' ) ) {
    // Set submitted fetching options
    $settings['method']     = filter_input( INPUT_POST, 'fetchmethodfgd' );
    $settings['limit']      = filter_input( INPUT_POST, 'limitfgd' );
    $settings['offset']     = filter_input( INPUT_POST, 'offsetfgd' );
    $settings['gamersafe']  = filter_input( INPUT_POST, 'gamersafe', FILTER_VALIDATE_BOOLEAN );
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
function myarcade_get_categories_fgd() {
  return array(
    "Action"      => true,
    "Adventure"   => true,
    "Arcade"      => true,
    "Board Game"  => false,
    "Casino"      => true,
    "Defense"     => true,
    "Customize"   => false,
    "Dress-Up"    => false,
    "Driving"     => true,
    "Education"   => false,
    "Fighting"    => true,
    "Jigsaw"      => false,
    "Multiplayer" => true,
    "Other"       => true,
    "Puzzles"     => true,
    "Rhythm"      => true,
    "Shooting"    => true,
    "Sports"      => true,
    "Strategy"    => true,
  );
}

/**
 * Fetch FlashGameDistribution games
 *
 * @version 5.19.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_fgd( $args = array() ) {
  myarcade_premium_message();
}

/**
 * Return game embed method
 *
 * @version 5.18.0
 * @since   5.18.0
 * @access  public
 * @return  string Embed Method
 */
function myarcade_embedtype_fgd() {
  return 'flash';
}
?>