<?php
/**
 * Displays the import games page on backend
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// No direct access
if( !defined( 'ABSPATH' ) ) {
  die();
}

/**
 * Import games page
 *
 * @version 5.28.0
 * @access  public
 * @return  void
 */
function myarcade_import_games() {
  global $wpdb;

  myarcade_header();

  $general= get_option( 'myarcade_general' );

  // Crete an empty game class
  $game = new stdClass();

  $import_action = sanitize_text_field( filter_input( INPUT_POST, 'impcostgame' ) );
  $import_type   = sanitize_text_field( filter_input( INPUT_POST, 'importtype' ) );

  if ( 'import' == $import_action ) {
    if ( 'embed' == $import_type || 'iframe' == $import_type ) {
      $decoded = esc_url( urldecode( $_POST['importgame'] ) );
      $converted = str_replace( array("\r\n", "\r", "\n"), " ", $decoded);
      $game->swf_url = esc_sql( $converted );
    }
    else {
      $game->swf_url = esc_url( $_POST['importgame'] );
    }

    $game->width = !empty($_POST['gamewidth']) ? sanitize_text_field( $_POST['gamewidth'] ) : '';
    $game->height = !empty($_POST['gameheight']) ? sanitize_text_field( $_POST['gameheight'] ) : '';

    if ( 'ibparcade' == $import_type || 'phpbb' == $import_type ) {
      $game->slug = sanitize_text_field( $_POST['slug'] );
    }
    else {
      $game->slug = preg_replace("/[^a-zA-Z0-9 ]/", "", strtolower( sanitize_text_field( $_POST['gamename'] ) ) );
      $game->slug = str_replace( " ", "-", $game->slug );
    }

    $game->name           = sanitize_text_field( $_POST['gamename'] );
    $game->type           = $import_type;
    $game->uuid           = md5( $game->name.'import' );
    $game->game_tag       = ( !empty($_POST['importgametag'])) ? sanitize_text_field( $_POST['importgametag'] ) : crc32( $game->uuid );
    $game->thumbnail_url  = sanitize_text_field( $_POST['importthumb'] );
    $game->description    = esc_textarea( $_POST['gamedescr'] );
    $game->instructions   = esc_textarea( $_POST['gameinstr'] );
    $game->tags           = esc_sql( $_POST['gametags'] );
    $game->categs         = ( isset($_POST['gamecategs']) ) ? sanitize_text_field( implode(",", $_POST['gamecategs'] ) ) : 'Other';
    $game->created        = gmdate( 'Y-m-d H:i:s', ( time() + (get_option( 'gmt_offset' ) * 3600 ) ) );
    $game->leaderboard_enabled = sanitize_text_field( filter_input( INPUT_POST, 'lbenabled' ) );

    if ( 'low' == filter_input( INPUT_POST, 'highscoretype' ) ) {
      $game->highscore_type = 'ASC';
    }
    else {
      $game->highscore_type = 'DESC';
    }

    $game->status         = 'new';
    $game->screen1_url    = esc_sql( $_POST['importscreen1'] );
    $game->screen2_url    = esc_sql( $_POST['importscreen2'] );
    $game->screen3_url    = esc_sql( $_POST['importscreen3'] );
    $game->screen4_url    = esc_sql( $_POST['importscreen4'] );
    $game->video_url      = isset($_POST['video_url']) ? esc_url( $_POST['video_url'] ) : '';
    $game->score_bridge   = isset($_POST['score_bridge']) ? sanitize_text_field( $_POST['score_bridge'] ) : '';

    // Add game to table
    myarcade_insert_game($game);

    $publis_status = sanitize_text_field( filter_input( INPUT_POST, 'publishstatus' ) );

    // Add the game as blog post
    if ( 'add' != $publis_status ) {
      $gameID = $wpdb->get_var("SELECT id FROM " . $wpdb->prefix . 'myarcadegames' . " WHERE uuid = '$game->uuid'");

      if ( !empty($gameID) ) {
        myarcade_add_games_to_blog( array( 'game_id' => $gameID, 'post_status' => $publis_status, 'echo' => false ) );

        echo '<div class="mabp_info mabp_680"><p>'.sprintf( __("Import of '%s' was succsessful.", 'myarcadeplugin'), $game->name ).'</p></div>';
      }
      else  {
        echo '<div class="mabp_error mabp_680"><p>'.__( "Can't import that game...", 'myarcadeplugin' ).'</p></div>';
      }
    }
    else {
      echo '<div class="mabp_info mabp_680"><p>'. sprintf(__("Game added successfully: %s", 'myarcadeplugin'), $game->name).'</p></div>';
    }
  }

  // Generate the category array
  if ( $general['post_type'] != 'post' && post_type_exists( $general['post_type'] )
    && !empty( $general['custom_category']) && taxonomy_exists($general['custom_category']) ) {

    $taxonomy = $general['custom_category'];
  }
  else {
    $taxonomy = 'category';
  }

  $categories = get_terms( $taxonomy, array('hide_empty' => false) );
  $selected_method = filter_input( INPUT_POST, 'importmethod', FILTER_SANITIZE_STRING, array( "options" => array( "default" => 'importswfdcr') ) );
  ?>

  <div id="myabp_import">
    <h2><?php _e("Import Individual Games", 'myarcadeplugin'); ?></h2>

    <div class="container">
      <div class="block">
        <table class="optiontable" width="100%">
          <tr>
            <td><h3><?php _e("Import Method", 'myarcadeplugin'); ?></h3></td>
          </tr>
          <tr>
            <td>
              <select size="1" name="importmethod" id="importmethod">
                <option value="importswfdcr" <?php selected( "importswfdcr", $selected_method ); ?>><?php _e("Upload / Grab SWF game", 'myarcadeplugin'); ?>&nbsp;</option>
                <option value="importembedif" <?php selected( "importembedif", $selected_method ); ?>><?php _e("Import Embed / Iframe game", 'myarcadeplugin'); ?></option>
                <option value="importibparcade" <?php selected( "importibparcade", $selected_method ); ?>><?php _e("Upload IBPArcade game (PRO)", 'myarcadeplugin'); ?></option>
                <option value="importphpbb" <?php selected( "importphpbb", $selected_method ); ?>><?php _e("Upload ZIP File / PHPBB / Mochi (PRO)", 'myarcadeplugin'); ?></option>
                <option value="importunity" <?php selected( "importunity", $selected_method ); ?>><?php _e("Import Unity game (PRO)", 'myarcadeplugin'); ?></option>
              </select>
              <br />
              <i><?php _e("Choose a desired import method.", 'myarcadeplugin'); ?></i>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <?php myarcade_get_max_post_size_message(); ?>

    <?php require_once( 'import_form.php' ); ?>
  </div><?php // end #myabp_import ?>
  <div class="clear"></div>
  <?php
  myarcade_footer();
}
?>