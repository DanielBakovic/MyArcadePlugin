<?php
/**
 * Count game plays and play duration
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

class MyArcade_Stats_Aggregator {

  /**
   * Init
   *
   * @version 5.30.0
   * @since   5.30.0
   * @access  public
   * @return  void
   */
  public static function init() {
    add_action( 'wp_enqueue_scripts', array( __CLASS__, 'laod_scripts' ) );
    add_action( 'myarcade_display_game', array( __CLASS__, 'track_game_play' ) );
  }

  /**
   * Register/Queue tracking scripts
   *
   * @version 5.30.0
   * @since   5.30.0
   * @access  public
   * @return  void
   */
  public static function laod_scripts() {
    global $post;

    // Load on single pages and only if time on game tracking is active
    if ( ! is_single() || ! is_game() ) {
      return;
    }

    $frontend_script_path = str_replace( array( 'http:', 'https:' ), '', MYARCADE_URL . '/assets/' );

    wp_register_script( 'myarcade-stats-frontend', $frontend_script_path . 'js/myarcade-stats-frontend.js', array( 'jquery' ), MYARCADE_VERSION, true );
    wp_enqueue_script( 'myarcade-stats-frontend' );

    wp_localize_script( 'myarcade-stats-frontend', 'myarcade_stats_i18n', array(
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      'nonce'   => wp_create_nonce( 'myarcade_stats_ajax_nonce' ),
      'slug'    => $post->post_name
    ));
  }

  /**
   * Track a game play
   *
   * @version 5.30.0
   * @since   5.30.0
   * @access  public
   * @return  void
   */
  public static function track_game_play() {
    global $wpdb, $post;

    $who = self::who_is_it();

    // Don't track bots (0) and admins (1). Track only visitors and users
    if ( $who['ID'] > 1 ) {
      $data = array(
        'post_id' => $post->ID,
        'user_id' => $who['user_id'],
        'date'    => gmdate('Y-m-d H:i:s', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) ),
        'duration'=> 0,
      );

      $wpdb->insert( $wpdb->prefix.'myarcade_plays', $data );

      // Get the iserted ID
      $id = $wpdb->insert_id;

      // Track time on game
      self::generate_token( $id, $who );
    }
  }

  /**
   * Generate a token stored as transient
   *
   * @version 5.30.0
   * @since   5.30.0
   * @access  public
   * @param   int $row_id Token for a database table ID
   * @param   array $data
   * @return  void
   */
  public static function generate_token( $row_id, $data ) {

    $data['token'] = $row_id;

    set_transient( 'myarcade_stats_' . $row_id, $data, 60*60*2 );

    // Add token to page source
    ?>
    <script type="text/javascript">var myarcade_stats_token = '<?php echo $row_id; ?>';</script>
    <?php
  }

  /**
   * Get a play token by row id
   *
   * @version 5.30.0
   * @since   5.30.0
   * @static
   * @access  public
   * @param   int $row_id database table ID
   * @return  array
   */
  public static function get_token_data( $row_id ) {
    return get_transient( 'myarcade_stats_' . $row_id );
  }

  /**
   * Detect if the current visitor is an admin, user, visitor or bot
   *
   * @version 5.30.0
   * @since   5.30.0
   * @access  public
   * @return  string admin|user|bot|visitor
   */
  public static function who_is_it() {

    $who = array();

    // ID : 0 => 'bot',
    //      1 => 'admin',
    //      2 => 'user',
    //      3 => 'visitor',

    if ( is_user_logged_in() ) {
      $who['user_id'] = get_current_user_id();

      if ( current_user_can( 'manage_options' ) ) {
        $who['ID'] = 1; //admin
      }
      else {
        $who['ID'] = 2; // user
      }
    }
    else {
      if ( self::is_bot() ) {
        $who['ID'] = 0; // bot
        $who['user_id']   = NULL;
      }
      else {
        $who['ID'] = 3; // visitor
        $who['user_id']   = NULL;
      }
    }

    return $who;
  }

  /**
   * Check if this is a bot
   *
   * @version 5.30.0
   * @since   5.30.0
   * @access  public
   * @return  boolean True if it's a bot
   */
  public static function is_bot() {

    $user_agent = empty( $_SERVER['HTTP_USER_AGENT'] ) ? false : $_SERVER['HTTP_USER_AGENT'];

    if ( ! $user_agent ) {
      // No user agent.. This could be a bot
      return true;
    }

    $bots = array('googlebot','google','msnbot','ia_archiver','lycos','jeeves','scooter','fast-webcrawler','slurp@inktomi','turnitinbot','technorati','yahoo','findexa','findlinks','gaisbo','zyborg','surveybot','bloglines','blogsearch','pubsub','syndic8','userland','gigabot','become.com','baiduspider','360spider','spider','sosospider','yandex');

    $result = false;

    foreach ( $bots as $bot ) {
      if ( stristr( $user_agent, $bot ) !== false ) {
        $result = true;
        break;
      }
    }

    return $result;
  }
} // END Class

MyArcade_Stats_Aggregator::init();