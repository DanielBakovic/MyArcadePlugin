<?php
/**
 * Displays the manage games page on backend
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @copyright (c) 2015, Daniel Bakovic
 * @license http://myarcadeplugin.com
 * @package MyArcadePlugin/Core/Admin
 */

/*
 * Copyright @ Daniel Bakovic - contact@myarcadeplugin.com
 * Do not modify! Do not sell! Do not distribute! -
 * Check our license Terms!
 */

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

/**
 * Manage Games
 *
 * @version 5.0.0
 * @access  public
 * @return  void
 */
function myarcade_manage_games() {
  global $wpdb, $myarcade_distributors;

  myarcade_header();
  ?>
  <div id="icon-options-general" class="icon32"><br /></div>
  <h2><?php _e("Manage Games", 'myarcadeplugin'); ?></h2>
  <br />
  <script type="text/javascript">
    function checkSeachForm() {
      if ( document.searchForm.q.value === "") {
        alert("<?php _e("Search term was not entered!", 'myarcadeplugin'); ?>");
        document.searchForm.q.focus();
        return false;
      }
    }
  </script>
  <?php

  $feedcategories = get_option('myarcade_categories');

  // Init needed vars
  $action = $results = $keyword = '';

  $game_type    = isset($_POST['distr']) ? $_POST['distr'] : 'all';
  $leaderboard  = isset($_POST['leaderboard']) ? $_POST['leaderboard'] : false;
  //$coins        = isset($_POST['coins']) ? $_POST['coins'] : false;
  $status       = isset($_POST['status']) ? $_POST['status'] : 'all';
  $search       = empty($_POST['q']) ? false : $_POST['q'];
  $order        = isset($_POST['order']) ? $_POST['order'] : 'ASC';
  $orderby      = isset($_POST['orderby']) ? $_POST['orderby'] : 'id';
  $cat          = isset($_POST['category']) ? $_POST['category'] : 'all';
  $games        = isset($_POST['games']) ? $_POST['games'] : '50';
  $offset       = isset($_POST['offset']) ? $_POST['offset'] : '0';

  if ( isset($_POST['action']) ) {
    $action = $_POST['action'];
  }

  if ( $action == 'search' ) {
    $keyword = esc_sql($search);

    $query_array = array();

    if ($search) {
      $query_array[] = "(name LIKE '%".$search."%' OR description LIKE '%".$search."%')";
    }

    if ( $game_type != 'all' ) {
      $query_array[] = "game_type = '".$game_type."'";
    }

    if ( $leaderboard ) {
      $query_array[] = "leaderboard_enabled = '1'";
    }

    /*if ( $coins ) {
      $query_array[] = "coins_enabled = '1'";
    }*/

    if ( $status != 'all' ) {
      $query_array[] = "status = '".$status."'";
    }

    if ( $cat != 'all' ) {
      foreach ($feedcategories as $category) {
        if ($category['Slug'] == $cat) {
          $query_array[] = "categories LIKE '%".$category['Name']."%'";
          break;
        }
      }
    }

    $count = count($query_array);
    $query_string = '';

    if ( $count > 1) {
      for($i=0; $i < $count; $i++) {
        $query_string .= $query_array[$i];
        if ( $i < ($count - 1) ) {
          $query_string .= ' AND ';
        }
      }
    }
    else {
      $query_string = $query_array[0];
    }

    if ( !empty($query_string) ) {
      $query_string = " WHERE ".$query_string;
    }

    // Generate the query
    $query = "SELECT * FROM " . $wpdb->prefix . 'myarcadegames' . $query_string." ORDER BY ".$orderby." ".$order." limit ".$offset.",".$games;

    $query_count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . 'myarcadegames' . $query_string);

    $results = $wpdb->get_results($query);

    if (!$results) {
      echo '<div class="mabp_error" style="width:685px">'.__("Nothing found!", 'myarcadeplugin').'</strong></div>';
    }
  }
  ?>
  <form method="post" action="" class="myarcade_form" name="searchForm">
    <input type="hidden" name="action" value="search" />
    <div class="myarcade_border grey" style="width:680px">
      <?php _e("Search for", 'myarcadeplugin'); ?>
      <input type="text" size="40" name="q" value="<?php echo $keyword; ?>" />

      <p class="myarcade_hr">&nbsp;</p>

      <div class="myarcade_border white" style="width:300px;float:left;height:30px;">
        <?php _e("Type", 'myarcadeplugin'); ?>:
        <select name="distr" id="distr">
          <option value="all" <?php myarcade_selected($game_type, 'all'); ?>>All</option>
          <option value="embed" <?php myarcade_selected($game_type, 'embed'); ?>>Embed</option>
          <option value="iframe" <?php myarcade_selected($game_type, 'iframe'); ?>>Iframe</option>
          <option value="custom" <?php myarcade_selected($game_type, 'custom'); ?>>Custom SWF</option>
          <?php foreach ($myarcade_distributors as $slug => $name) : ?>
          <option value="<?php echo $slug; ?>" <?php myarcade_selected($game_type, $slug); ?>><?php echo $name; ?></option>
          <?php endforeach; ?>
          <option value="ibparcade" <?php myarcade_selected($game_type, 'ibparcade'); ?>>- PRO - IBPArcade</option>
          <option value="phpbb" <?php myarcade_selected($game_type, 'phpbb'); ?>>- PRO - PHPBB / ZIP</option>
          <option value="dcr" <?php myarcade_selected($game_type, 'dcr'); ?>>- PRO - DCR</option>
          <option value="unity" <?php myarcade_selected($game_type, 'unity'); ?>>- PRO - Unity</option>
        </select>
      </div>

      <div class="myarcade_border white" style="width:300px;float:left;margin-left:20px;height:25px;padding-top: 15px">
        <?php _e('Leaderboard Enabled', 'myarcadeplugin'); ?>:
        <input type="checkbox" name="leaderboard" value="1" <?php myarcade_checked($leaderboard, '1'); ?> />&nbsp;&nbsp;&nbsp;
      </div>

      <div class="clear"> </div>

      <div class="myarcade_border white" style="width:300px;height:30px;float:left;">
        <?php _e("Game Status", 'myarcadeplugin'); ?>:
        <select name="status" id="status">
          <option value="all" <?php myarcade_selected($status, 'all'); ?>>All</option>
          <option value="new" <?php myarcade_selected($status, 'new'); ?>>New</option>
          <option value="published" <?php myarcade_selected($status, 'published'); ?>>Published</option>
          <option value="deleted" <?php myarcade_selected($status, 'deleted'); ?>>Deleted</option>
        </select>
      </div>

      <div class="myarcade_border white" style="width:300px;height:30px;float:left;margin-left:20px;">
        <?php _e("Order", 'myarcadeplugin'); ?>:
        <select name="order" id="order">
          <option value="ASC" <?php myarcade_selected($order, 'ASC'); ?>>ASC</option>
          <option value="DESC" <?php myarcade_selected($order, 'DESC'); ?>>DESC</option>
        </select>
        <?php _e("by", 'myarcadeplugin');?>:
        <select name="orderby" id="orderby">
          <option value="id" <?php myarcade_selected($orderby, 'id'); ?>>ID</option>
          <option value="name" <?php myarcade_selected($orderby, 'name'); ?>>Name</option>
          <option value="slug" <?php myarcade_selected($orderby, 'slug'); ?>>Slug</option>
          <option value="game_type" <?php myarcade_selected($orderby, 'game_type'); ?>>Game Type</option>
          <option value="status" <?php myarcade_selected($orderby, 'status'); ?>>Status</option>
        </select>
      </div>

      <div class="clear"> </div>

      <div class="myarcade_border white" style="width:300px;height:30px;float:left;">
        <?php _e("Game Category", 'myarcadeplugin'); ?>:
        <select name="category" id="category">
          <option value="all" <?php myarcade_selected($cat, 'all'); ?>>All</option>
          <?php
            foreach ( $feedcategories as $category) {
              ?><option value="<?php echo $category['Slug']; ?>" <?php myarcade_selected($cat, $category['Slug']); ?>><?php echo $category['Name']; ?></option><?php
            }
          ?>
        </select>
      </div>

      <div class="myarcade_border white" style="width:300px;height:30px;float:left;margin-left:20px;">
        <?php _e("Display", 'myarcadeplugin'); ?>
        <input type="text" size="3" name="games" value="<?php echo $games; ?>" />
        <?php _e("games from offset", 'myarcadeplugin'); ?>
        <input type="text" size="3" name="offset" value="<?php echo $offset; ?>" />
      </div>

      <div class="clear"> </div>

      <input class="button-primary" id="submit" type="submit" name="submit" value="Search" />
    </div>
  </form>

  <?php
  if ( $results ) {
    echo '<div class="mabp_info" style="width:685px">'.sprintf(__("Results found: <strong>%s</strong>. Displaying results from <strong>%s</strong> to <strong>%s</strong>.", 'myarcadeplugin'), $query_count, $offset, $games + $offset).'</div>';

    foreach ($results as $game) {
      myarcade_show_game($game);
    }
  }
  else {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . 'myarcadegames');

    if ( $count ) {

      /* Begin Pagination */

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

      // Last feeded games
      $results = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 'myarcadegames' . " ORDER BY ID DESC $range");

      if ($results) {
        echo '<h3>'.__("Browser Your Game Catalog", 'myarcadeplugin').'</h3>';
        ?>
        <!-- Print pagination -->
        <div class="tablenav" style="float: left;">
          <div class="tablenav-pages">
            <span class="displaying-num">Displaying <?php echo $from_to; ?> of <?php echo $count; ?></span>
          <?php if ($pagenum > 1) : ?>
              <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-games&pagenum=1'>First</a>
              <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-games&pagenum=<?php echo $previous; ?>'>Previous</a>
            <?php endif; ?>
            <span class='page-numbers current'><?php echo $pagenum; ?></span>
            <?php if ($pagenum != $last) : ?>
              <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-games&pagenum=<?php echo $next; ?>'>Next</a>
              <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-games&pagenum=<?php echo $last; ?>'>Last</a>
            <?php endif; ?>
          </div>
        </div>

        <?php
        foreach ($results as $game) {
          myarcade_show_game($game);
        }
        ?>

        <!-- Print pagination -->
        <div class="tablenav" style="float: left;">
          <div class="tablenav-pages">
            <span class="displaying-num">Displaying <?php echo $from_to; ?> of <?php echo $count; ?></span>
          <?php if ($pagenum > 1) : ?>
              <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-games&pagenum=1'>First</a>
              <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-games&pagenum=<?php echo $previous; ?>'>Previous</a>
            <?php endif; ?>
            <span class='page-numbers current'><?php echo $pagenum; ?></span>
            <?php if ($pagenum != $last) : ?>
              <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-games&pagenum=<?php echo $next; ?>'>Next</a>
              <a class='page-numbers' href='<?php echo $_SERVER['PHP_SELF'];?>?page=myarcade-manage-games&pagenum=<?php echo $last; ?>'>Last</a>
            <?php endif; ?>
          </div>
        </div>

        <div style="clear:both;"></div>
        <?php
      }
    }
    else {
      _e("No games found", 'myarcadeplugin');
    }

    $results = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 'myarcadegames' . " WHERE status = 'deleted' ORDER BY created DESC limit 10");

    if ($results) {
      echo '<h3>'.__("10 Last Deleted Games", 'myarcadeplugin').'</h3>';
      foreach ($results as $game) {
        myarcade_show_game($game);
      }
      ?>
      <div style="clear:both;"></div>
      <?php
    }
  }

  ?>
  <script type="text/javascript">
    function thickboxResize() {
      var boundHeight = 800; // minimum height
      //var boundWidth = 750; // minimum width

      //var viewportWidth = (self.innerWidth || (document.documentElement.clientWidth || (document.body.clientWidth || 0)));
      var viewportHeight = (self.innerHeight || (document.documentElement.clientHeight || (document.body.clientHeight || 0)));

      jQuery('a.thickbox').each(function(){
        var text = jQuery(this).attr("href");

        if ( viewportHeight < boundHeight  /*|| viewportHeight < boundWidth*/)
        {
          // adjust the height
          text = text.replace(/height=[0-9]*/,'height=' + Math.round(viewportHeight * .8));
          // adjust the width
          //text = text.replace(/width=[0-9]*/,'width=' + Math.round(viewportWidth * .8));
        }
        else
        {
          // constrain the height by defined bounds
          text = text.replace(/height=[0-9]*/,'height=' + boundHeight);
          // constrain the width by defined bounds
          //text = text.replace(/width=[0-9]*/,'width=' + boundWidth);
        }

        jQuery(this).attr("href", text);
      });
    }

    jQuery(window).bind('load', thickboxResize );
    jQuery(window).bind('resize', thickboxResize );
  </script>
  <?php
  myarcade_footer();
}
?>