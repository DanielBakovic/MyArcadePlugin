<?php
/**
 * Score Editing Module
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @copyright 2009-2015 Daniel Bakovic
 * @license http://myarcadeplugin.com
 */

// Locate WordPress root folder
$root = dirname( dirname( dirname( dirname( dirname(__FILE__)))));

if ( file_exists($root . '/wp-load.php') ) {
  define('MYARCADE_DOING_ACTION', true);
  require_once($root . '/wp-load.php');
}
else {
  // WordPress not found
  die();
}

// Check user privilege
if ( function_exists('current_user_can') && !current_user_can('manage_options') ) {
  die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title><?php _e("Edit Game", 'myarcadeplugin'); ?></title>

<link rel='stylesheet' href='<?php bloginfo('url'); ?>/wp-admin/css/wp-admin.css' type='text/css' />
<link rel='stylesheet' href='<?php bloginfo('url'); ?>/wp-admin/css/colors-fresh.css' type='text/css' />
<link rel='stylesheet' href='<?php echo MYARCADE_URL; ?>/assets/css/myarcadeplugin.css' type='text/css' />

<script type="text/javascript" src="<?php echo get_option('siteurl')."/".WPINC."/js/jquery/jquery.js"; ?>"></script>

</head>
<body>
  <div class="wrap">
    <div class="edit-score">
      <?php
      global $wpdb;

      if ( isset($_POST['submit']) ) {
        $scoreid = $_POST['scoreid'];
        $score = $_POST['score'];

        // get score
        $old_score = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.'myarcadescores'." WHERE id = '{$scoreid}'");
        // Get highscore
        $highscore = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix.'myarcadehighscores'." WHERE game_tag = '{$old_score->game_tag}' AND user_id = '{$old_score->user_id}' AND score = '{$old_score->score}'");

        if ( $highscore ) {
          // Update highscore
          $wpdb->query("UPDATE ".$wpdb->prefix.'myarcadehighscores'." SET score = '{$score}' WHERE id = '{$highscore->id}'");
        }

        $result = $wpdb->query("UPDATE ".$wpdb->prefix.'myarcadescores'." SET score = '{$score}' WHERE id = '{$scoreid}'");

        if ($result) {
          echo '<div id="message" class="updated fade">'.__("Score has been updated!", 'myarcadeplugin').'</div>';
          ?>
          <script type="text/javascript">
            jQuery(document).ready(function() {
              jQuery("td#scoreval_<?php echo $scoreid; ?>", top.document).html("<?php echo $score; ?>");
            });
          </script>
          <?php
        }
        else {
          echo '<div id="message" class="myerror fade">'.__("Can't update score!", 'myarcadeplugin').'</div>';
        }
      } else {
        if ( !isset($_GET['scoreid']) ) {
          wp_die("Unknown score ID");
        }

        $scoreid = intval( $_GET['scoreid'] );
      }

      $score = $wpdb->get_var("SELECT score FROM ".$wpdb->prefix.'myarcadescores'." WHERE id = {$scoreid}");

      if (!$score) {
        wp_die("No score found");
      }
      ?>
      <form method="post" name="formeditscore" id="formeditscore">
        <input type="hidden" name="scoreid" id="scoreid" value="<?php echo $scoreid; ?>" />
        <br />
        <div class="container">
          <div class="block">
            <table class="optiontable">
              <tr>
                <td>Score</td>
                <td><input type="text" name="score" id="score" value="<?php echo $score; ?>" /></td>
                <td><input class="button-secondary" id="submit" type="submit" name="submit" value="<?php _e("Save Changes", 'myarcadeplugin'); ?>" /></td>
              </tr>
            </table>
          </div>
        </div>
      </form>
    </div>
  </div>
</body>
</html>