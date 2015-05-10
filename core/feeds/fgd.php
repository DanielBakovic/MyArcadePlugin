<?php
/**
 * FlashGameDistribution
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @copyright (c) 2015, Daniel Bakovic
 * @license http://myarcadeplugin.com
 * @package MyArcadePlugin/Core/Fetch
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
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_settings_fgd() {
  $fgd = get_option( 'myarcade_fgd' );
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
              <?php _e("FlashGameDistribution has over 10.000 games that you can add to your site with ease.", 'myarcadeplugin'); ?> Click <a href="http://flashgamedistribution.com">here</a> to visit the FlashGameDistribution site.
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
 * Handle distributor settings update
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_fgd() {
  // Do a secuirty check before updating the settings
  $myarcade_nonce = filter_input( INPUT_POST, 'myarcade_save_settings_nonce');
  if ( ! $myarcade_nonce || ! wp_verify_nonce( $myarcade_nonce, 'myarcade_save_settings' ) ) {
    // Security check failed .. don't update settings
    return;
  }

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
 * Diesplay feed options on the fetch games page
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_options_fgd() {

}

/**
 * Fetch FlashGameDistribution games
 *
 * @version 5.0.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_fgd( $args = array() ) {
  myarcade_premium_message();
}
?>