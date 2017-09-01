<?php
/**
 * Displays the manage scores page on backend
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

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

/**
 * Manage Scores
 *
 * @version 5.13.0
 * @access  public
 * @return  void
 */
function myarcade_manage_scores() {
  global $wpdb;

  myarcade_header();

  /* Begin Pagination */
  $count = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix.'myarcadescores');

  if ( $count ) {
    // This is the number of results displayed per page
    $page_rows = 50;

    // This tells us the page number of our last page
    $last = ceil($count / $page_rows);

    // This makes sure the page number isn't below one, or more than our maximum pages
    $pagenum = 1;

    if ( isset($_GET['pagenum']) ) {
      $pagenum = $_GET['pagenum'];
    }

    if ($pagenum < 1)  {
      $pagenum = 1;
    }
    elseif ($pagenum > $last)  {
      $pagenum = $last;
    }

    // This sets the range to display in our query
    $range = 'limit ' .($pagenum - 1) * $page_rows .',' .$page_rows;

    // Calculate counts for next and previous
    if ( $pagenum != $last) {
      $next = $pagenum + 1;
    }

    if ($pagenum > 1) {
      $previous = $pagenum - 1;
    }

    // Generate from .. to
    $from = 1 + ($pagenum - 1) * $page_rows;

    if ($pagenum < $last) {
      $to = $from + $page_rows - 1;
    }
    else {
      $to = $count;
    }

    $from_to = $from.' - '.$to;
    /* End Paginagion */

    $scores = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.'myarcadescores'." ORDER by id DESC {$range}" );
    ?>
    <h2><?php _e("Manage Scores", 'myarcadeplugin'); ?></h2>
    <br />
    <?php myarcade_premium_message(); ?>
    <!-- Print pagination -->
    <div class="tablenav" style="float: left;">
      <div class="tablenav-pages">
        <span class="displaying-num">Displaying <?php echo $from_to; ?> of <?php echo $count; ?></span>
        <?php if ($pagenum > 1) : ?>
          <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-scores&pagenum=1'>First</a>
          <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-scores&pagenum=<?php echo $previous; ?>'>Previous</a>
        <?php endif; ?>
        <span class='page-numbers current'><?php echo $pagenum; ?></span>
        <?php if ($pagenum != $last) : ?>
          <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-scores&pagenum=<?php echo $next; ?>'>Next</a>
          <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-scores&pagenum=<?php echo $last; ?>'>Last</a>
        <?php endif; ?>
      </div>
    </div>

    <table class="widefat fixed">
      <thead>
      <tr>
        <th scope="col" width="100">Image</th>
        <th scope="col">Game</th>
        <th scope="col">User</th>
        <th scope="col">Date</th>
        <th scope="col">Score</th>
        <th scope="col">Action</th>
      </tr>
      </thead>
      <tbody>
        <?php foreach ( $scores as $score ) : ?>
          <?php
          $user = get_user_by('id', $score->user_id);

          $post_id = $wpdb->get_var( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'mabp_game_tag' AND meta_value = '{$score->game_tag}'" );

          if (! $post_id ) {
            ?>
            <tr id="scorerow_<?php echo $score->id; ?>">
              <td colspan="6">
                <p class="mabp_error">
                  <?php printf( __("No WordPress post found for this score. Score ID: %s", 'myarcadeplugin'), $score->id); ?>
                </p>
              </td>
            </tr>
            <?php
            continue;
          }

          $edit_url = MYARCADE_URL.'/core/editscore.php?scoreid='.$score->id;
          $edit ='<a href="'.$edit_url.'&keepThis=true&TB_iframe=true&height=300&width=500" class="button-secondary thickbox edit" title="'.__("Edit Score", 'myarcadeplugin').'">'.__("Edit", 'myarcadeplugin').'</a>';
          $delete = "<button class=\"button-secondary\" onclick = \"jQuery('#score_$score->id').html('<div class=\'gload\'> </div>');jQuery.post('".admin_url('admin-ajax.php')."',{action:'myarcade_handler',gameid: false, scoreid: '$score->id',func:'delete_score'},function(){jQuery('#scorerow_$score->id').fadeOut('slow');});\" >".__("Delete", 'myarcadeplugin')."</button>";
          ?>
          <tr id="scorerow_<?php echo $score->id; ?>">
            <td><img src="<?php echo get_post_meta($post_id, 'mabp_thumbnail_url', true); ?>" width="50" height="50" alt="" /></td>
            <td><a href="<?php echo get_permalink($post_id); ?>" title="" target="_blank"><?php echo get_the_title($post_id); ?></a></td>
            <td><?php echo $user->display_name; ?></td>
            <td><?php echo $score->date; ?></td>
            <td id="scoreval_<?php echo $score->id; ?>"><?php echo $score->score; ?></td>
            <td><?php echo $edit; ?> <?php echo $delete; ?><span id="score_<?php echo $score->id; ?>"></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php
  }
  else {
    echo '<p>'.__('No scores available', 'myarcadeplugin').'</p>';
  }

  myarcade_footer();
}
?>