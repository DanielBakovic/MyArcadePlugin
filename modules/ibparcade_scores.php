<?php
/**
 * IBPArcade score submitting module for MyArcadePlugin Pro
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 */

// --- SCORES --- //
$act     = isset($_GET['act']) ? strtolower($_GET['act']) : false;
$autocom = isset($_GET['autocom']) ? strtolower($_GET['autocom']) : false;
$do      = isset($_GET['do']) ? strtolower($_GET['do']) : false;

if ( ( $act == 'arcade' ) || ( $autocom == 'arcade' ) ) {
	global $wpdb;

	if ( defined( 'MYARCADE_DEBUG' ) && MYARCADE_DEBUG ) {
		myarcade_log_score( 'IBP/PHPBB Game Submitted Data: ' . "\n" . 'POST: ' . print_r( $_POST, true ) ."\n". 'GET: ' . print_r( $_GET, true ) );
	}

	switch ( $do ) {

		case 'verifyscore':
			// v3 and v32 score verification
			MyArcade_Session::set( 'time', time() );
			MyArcade_Session::set( 'rand1', rand(1, 100) );
			MyArcade_Session::set( 'rand2', rand(1, 100) );

			echo '&randchar=', MyArcade_Session::get( 'rand1' ) ,'&randchar2=', MyArcade_Session::get( 'rand2' ) ,'&savescore=1&blah=OK';

			// End here for now..
			die();
		break;

		case 'savescore':
			// v3 and v32 score
			if ( defined( 'MYARCADE_DEBUG' ) && MYARCADE_DEBUG ) {
				myarcade_log_score( 'IBP/PHP Save Score' );
			}

			$gname = filter_input( INPUT_POST, 'gname' );
			$game  = myarcade_get_ibp_data( $gname );

			if ( ! $game || empty( $game['post_id'] ) || empty( $game['game_tag'] ) || empty( $game['score_order'] ) ) {
				// There is something wrong.
				header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
				die();
			}

			// Save only when session is started
			if ( MyArcade_Session::get( 'time' ) ) {
				// Check the session ( Allowed delay 10 sec for testing)
				$score  = (float) ( empty( filter_input( INPUT_POST, 'score' ) ) ? filter_input( INPUT_POST, 'gscore' ) : filter_input( INPUT_POST, 'score' ) );
				$secure = $score * MyArcade_Session::get( 'rand1' ) ^ MyArcade_Session::get( 'rand2' );

				$user_id = get_current_user_id();

				// Check if this is the same user.
				if ( $user_id == MyArcade_Session::get( 'uuid' ) ) {
					if ( $secure == filter_input( INPUT_POST, 'enscore' ) ) {
						// Collect needed information.
						$score_array = array(
							'session'   => MyArcade_Session::get( 'time' ),
							'date'      => date('Y-m-d'),
							'datatype'  => 'number',
							'game_tag'  => $game['game_tag'],
							'user_id'   => $user_id,
							'score'     => $score,
							'sortorder' => $game['score_order'],
						);

						myarcade_handle_score( $score_array );
					}
				} // end user id == session user id
			}

			$redirect = get_permalink( $game['post_id'] );
			header( 'Location: ' . $redirect );
			die();
		break;

		case 'newscore': {
			// -- v2 and phpBB-Amod scores -- //
			if ( defined( 'MYARCADE_DEBUG' ) && MYARCADE_DEBUG ) {
				myarcade_log_score( 'IBP/PHPBB New Score' );
			}

			$gname = ( empty( filter_input( INPUT_POST, 'game_name' ) ) ? filter_input( INPUT_POST, 'gname' ) : filter_input( INPUT_POST, 'game_name' ) );
			$game  = myarcade_get_ibp_data( $gname );

			if ( ! $game || empty( $game['post_id'] ) || empty( $game['game_tag'] ) || empty( $game['score_order'] ) ) {
				// There is something wrong.
				header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
				die();
			}

			// User has to be logged in.
			$user_id = get_current_user_id();

			// Check if this is the same user.
			if ( $user_id ) {
				$score = (float) ( empty( filter_input( INPUT_POST, 'score' ) ) ? filter_input( INPUT_POST, 'gscore' ) : filter_input( INPUT_POST, 'score' ) );

				if ( empty( MyArcade_Session::get('time') ) ) {
					$session = time();
				} else {
					$session = MyArcade_Session::get('time');
				}

				// Collect needed information.
				$score_array = array(
					'session'   => $session,
					'date'      => date('Y-m-d'),
					'datatype'  => 'number',
					'game_tag'  => $game['game_tag'],
					'user_id'   => $user_id,
					'score'     => $score,
					'sortorder' => $game['score_order'],
				);

				myarcade_handle_score( $score_array );
			} // end if user_ID

			$redirect = get_permalink( $game['post_id'] );
			header( 'Location: '.$redirect );
			die();
		}
		break;
	}
}

/**
 * Get IBPArcade game data
 */
function myarcade_get_ibp_data( $gname ) {
	global $wpdb;

	$post_id = $wpdb->get_var( $wpdb->prepare(
		"SELECT p.ID FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS m
			 ON m.post_id = p.ID
				WHERE m.meta_key = 'mabp_game_slug'
				 AND  m.meta_value = %s", $gname )
	 );

	if ( $post_id ) {
		$score_order  = get_post_meta( $post_id,'mabp_score_order', true );

		if ( ! $score_order ) {
			$score_order = 'DESC';
		}

		$game_tag = get_post_meta( $post_id, 'mabp_game_tag', true );
	} else {
		$game = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}myarcadegames WHERE slug = %s LIMIT 1", $gname ) );

		if ( ! $game ) {
			return false;
		}

		$post_id = $game->postid;
		$score_order = $game->highscore_type;
		$game_tag = $game->game_tag;
	}

	if ( ! $score_order || 'high' == $score_order ) {
			$score_order = "DESC";
	}
	elseif ( 'low' == $score_order ) {
		$score_order = "ASC";
	}

	return compact( 'post_id', 'score_order', 'game_tag' );
}
