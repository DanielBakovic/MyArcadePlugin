<?php
/**
 * GameFeed by Talkarcades
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
 * @version 5.26.0
 * @access  public
 * @return  void
 */
function myarcade_settings_gamefeed() {
  $gamefeed = myarcade_get_settings( 'gamefeed' );
  $defaults = myarcade_default_settings_gamefeed();
  $gamefeed = wp_parse_args( $gamefeed, $defaults );
  ?>
  <h2 class="trigger"><?php  myarcade_premium_span(); _e("GameFeed by TalkArcades", 'myarcadeplugin'); ?></h2>
  <div class="toggle_container">
    <div class="block">
      <?php myarcade_premium_message(); ?>
      <table class="optiontable" width="100%" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2">
            <i>
              <?php printf( __( "%s distributes Flash games.", 'myarcadeplugin' ), '<a href="http://www.talkarcades.com" target="_blank">TalkArcades</a>' ); ?>
              <?php _e( "You need a free account on TalkArcades to be able to use the GameFeed AutoPublisher. Create a new account.", 'myarcadeplugin'); ?>
            </i>
            <br /><br />
          </td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("GameFeed File Name", 'myarcadeplugin'); ?></h3></td></tr>
        <tr>
          <td>
            <input type="text" size="40"  name="gamefeed_feedname" value="<?php echo $gamefeed['feedname']; ?>" />
          </td>
          <td><i><?php _e("Paste the file name of your GameFeed Autopublisher.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Game Status", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <select size="1" name="gamefeed_status" id="gamefeed_status">
              <option value="publish" <?php myarcade_selected($gamefeed['status'], 'publish'); ?> ><?php _e("Publish", 'myarcadeplugin'); ?></option>
              <option value="draft" <?php myarcade_selected($gamefeed['status'], 'draft'); ?> ><?php _e("Draft", 'myarcadeplugin'); ?></option>
              <option value="add" <?php myarcade_selected($gamefeed['status'], 'add'); ?> ><?php _e("Add To Database (don't publish)", 'myarcadeplugin'); ?></option>
            </select>
          </td>
          <td><i><?php _e("Select a status for games added through AutoPublish from TalkArcades website.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h3><?php _e("Automated Game Publishing", 'myarcadeplugin'); ?></h3></td></tr>

        <tr>
          <td>
            <input type="checkbox" name="gamefeed_cron_publish" value="true" <?php myarcade_checked($gamefeed['cron_publish'], true); ?> /><label class="opt">&nbsp;<?php _e("Yes", 'myarcadeplugin'); ?></label>
          </td>
          <td><i><?php _e("Enable this if you want to publish games automatically. Go to 'General Settings' to select a cron interval. This will only work if you have unpublished TalkArcades Games in your database.", 'myarcadeplugin'); ?></i></td>
        </tr>

        <tr><td colspan="2"><h4><?php _e("Publish Games", 'myarcadeplugin'); ?></h4></td></tr>

        <tr>
          <td>
            <input type="text" size="40"  name="gamefeed_cron_publish_limit" value="<?php echo $gamefeed['cron_publish_limit']; ?>" />
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
 * @version 5.26.0
 * @since   5.19.0
 * @access  public
 * @return  array Default settings
 */
function myarcade_default_settings_gamefeed() {
  return array(
    'feedname'      => '',
    'status'        => 'publish',
    'cron_publish'  => false,
    'cron_publish_limit' => '1',
  );
}

/**
 * Handle distributor settings update
 *
 * @version 5.26.0
 * @access  public
 * @return  void
 */
function myarcade_save_settings_gamefeed() {

  myarcade_check_settings_nonce();

  // GameFeed Settings
  $gamefeed = array();
  $gamefeed['feedname'] = esc_sql( filter_input( INPUT_POST, 'gamefeed_feedname' ) );
  $gamefeed['status'] = esc_sql( filter_input( INPUT_POST, 'gamefeed_status' ) );
  $gamefeed['cron_publish'] = (isset($_POST['gamefeed_cron_publish']) ) ? true : false;
  $gamefeed['cron_publish_limit']  = (isset($_POST['gamefeed_cron_publish_limit']) ) ? intval($_POST['gamefeed_cron_publish_limit']) : 1;
  // Update Settings
  update_option( 'myarcade_gamefeed', $gamefeed );
}
?>