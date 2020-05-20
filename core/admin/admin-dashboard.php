<?php
/**
 * Displays the MyArcade Dashboard page on backend
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

/**
 * Dashboard
 *
 * @version 5.30.0
 * @access  public
 * @return  void
 */
function myarcade_show_stats() {
  global $wpdb;

  myarcade_header();
  ?>
  <div id="icon-index" class="icon32"><br /></div>
  <h2><?php _e("Dashboard"); ?></h2>

  <?php
  if ( "unknown" == get_option( 'myarcade_allow_tracking' ) ) {
    myarcade_tracking_message();
  }

  // Get Settings
  $general = get_option('myarcade_general');

  $unpublished_games  = intval($wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . 'myarcadegames' . " WHERE status = 'new'"));

  // Get published posts
  $total_posts = wp_count_posts();
  ?>
  <div class="dash-left metabox-holder">
    <div class="postbox">
      <div class="statsico"></div>
      <h3 class="hndle"><span><?php _e('MyArcadePlugin Info', 'myarcadeplugin') ?></span></h3>
      <div class="preloader-container">
        <div class="insider" id="boxy">
          <ul>
            <li><?php _e('Total Live Games / Posts', 'myarcadeplugin'); ?>: <a href="edit.php?post_status=publish&post_type=post"><strong><?php echo $total_posts->publish; ?></strong></a></li>
            <li><?php _e('Total Scheduled Games / Posts', 'myarcadeplugin'); ?>: <a href="edit.php?post_status=pending&post_type=post"><strong><?php echo $total_posts->future; ?></strong></a></li>
            <li><?php _e('Total Draft Games / Posts', 'myarcadeplugin'); ?>: <a href="edit.php?post_status=draft&post_type=post"><strong><?php echo $total_posts->draft; ?></strong></a></li>

            <li>&nbsp;</li>

            <li><?php _e('Unpublished Games', 'myarcadeplugin'); ?>: <strong><?php echo $unpublished_games; ?></strong></li>
            <li>
              <?php _e('Post Status', 'myarcadeplugin'); ?>: <strong><?php echo $general['status']; ?></strong>
              <?php if ( $general['status'] == 'future') : ?>
               , <strong><?php echo $general['schedule']; ?></strong> <?php _e('minutes schedule', 'myarcadeplugin'); ?>.
              <?php endif; ?>
            </li>

            <li>&nbsp;</li>

            <li><?php _e('Download Games', 'myarcadeplugin'); ?>: <strong><?php if ($general['down_games']) { _e('Yes', 'myarcadeplugin'); } else { _e('No', 'myarcadeplugin'); } ?></strong></li>
            <li><?php _e('Download Screenshots', 'myarcadeplugin'); ?>: <strong><?php if ($general['down_screens']) { _e('Yes', 'myarcadeplugin'); } else { _e('No', 'myarcadeplugin'); } ?></strong></li>

            <li>&nbsp;</li>

            <li><?php _e('Product Support', 'myarcadeplugin'); ?>:  <a href="http://myarcadeplugin.com/support/" target="_new"><?php _e('Forum', 'myarcadeplugin'); ?></a></li>
          </ul>

          <div class="clear"> </div>
        </div>
      </div>
    </div><!-- postbox end -->

  </div><!-- end dash-left -->

  <div class="dash-right metabox-holder">

  <div class="postbox">
    <div class="newsico"></div>
      <h3 class="hndle" id="poststuff"><span><?php _e('Lastest MyArcadePlugin News', 'myarcadeplugin') ?></span></h3>
      <div class="preloader-container">
        <div class="insider" id="boxy">
        <?php
           wp_widget_rss_output('https://myarcadeplugin.com/feed', array('items' => 5, 'show_author' => 0, 'show_date' => 1, 'show_summary' => 0));
        ?>
        </div> <!-- inside end -->
      </div>
    </div> <!-- postbox end -->

    <div class="postbox">
      <div class="facebookico"></div>
        <h3 class="hndle" id="poststuff"><span><?php _e('Be Our Friend!', 'myarcadeplugin') ?></span></h3>
        <div class="preloader-container">
          <div class="insider" id="boxy">
            <p style="text-align:center"><strong><?php _e('If you like MyArcadePlugin, become our friend on Facebook', 'myarcadeplugin'); ?></strong></p>
            <p style="text-align:center;">
              <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FMyArcadePlugin%2F&tabs&width=500&height=250&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=240110659370021" width="500" height="250" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
            </p>
          </div> <!-- inside end -->
        </div>
      </div> <!-- postbox end -->
  </div><!-- end dash-right -->

  <div class="clear"></div>

  <strong>MyArcadePlugin Lite v<?php echo MYARCADE_VERSION;?></strong> | <strong><a href="http://myarcadeplugin.com/" target="_blank">MyArcadePlugin.com</a> </strong>
  <?php
  myarcade_footer();
}
?>