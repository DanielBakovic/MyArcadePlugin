<?php
/**
 * Arcade Game Feed
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
function myarcade_settings_agf() {

  $agf = get_option( 'myarcade_agf' );
  ?>
  <h2 class="trigger"><?php myarcade_premium_img() ?> <?php _e("Arcade Game Feed (AGF)", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <?php myarcade_premium_message() ?>
            <br />
            <i>
              <?php _e("Arcade Games Feed is a new game distributors which offer quality flash games.", 'myarcadeplugin'); ?> Click <a href="http://arcadegamefeed.com">here</a> to visit the Arcade Game Feed site.
            </i>
            <br /><br />
          </td>
        </tr>
        <tr><td colspan="2"><h3><?php _e("Feed URL", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="agf_url" value="<?php echo $agf['feed']; ?>" />
          </td>
          <td><i><?php _e("Edit this field only if Feed URL has been changed!", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Fetch Games", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="agf_limit" value="<?php echo $agf['limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched at once.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Fetching", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="agf_cron_fetch" value="true" <?php myarcade_checked($agf['cron_fetch'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to fetch games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Fetch Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="agf_cron_fetch_limit" value="<?php echo $agf['cron_fetch_limit']; ?>" />
          </td>
          <td><i><?php _e("How many games should be fetched on every cron trigger?", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="agf_cron_publish" value="true" <?php myarcade_checked($agf['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="agf_cron_publish_limit" value="<?php echo $agf['cron_publish_limit']; ?>" />
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
function myarcade_save_settings_agf() {

  // Do a secuirty check before updating the settings
  $myarcade_nonce = filter_input( INPUT_POST, 'myarcade_save_settings_nonce');
  if ( ! $myarcade_nonce || ! wp_verify_nonce( $myarcade_nonce, 'myarcade_save_settings' ) ) {
    // Security check failed .. don't update settings
    return;
  }

  $agf = array();
  $agf['feed'] = (isset($_POST['agf_url'])) ? esc_sql($_POST['agf_url']) : '';
  $agf['limit'] = (isset($_POST['agf_limit'])) ? intval( $_POST['agf_limit']) : '';
  $agf['cron_fetch'] = (isset($_POST['agf_cron_fetch']) ) ? true : false;
  $agf['cron_fetch_limit'] = (isset($_POST['agf_cron_fetch_limit']) ) ? intval($_POST['agf_cron_fetch_limit']) : 1;
  $agf['cron_publish'] = (isset($_POST['agf_cron_publish']) ) ? true : false;
  $agf['cron_publish_limit'] = (isset($_POST['agf_cron_publish_limit']) ) ? intval($_POST['agf_cron_publish_limit']) : 1;
    // Update settings
    update_option('myarcade_agf', $agf);
}

/**
 * Diesplay feed options on the fetch games page
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_fetch_options_agf() {

}

/**
 * Fetch FlashGameDistribution games
 *
 * @version 5.0.0
 * @access  public
 * @param   array  $args Fetching parameters
 * @return  void
 */
function myarcade_feed_agf( $args = array() ) {
  myarcade_premium_message();
}
?>