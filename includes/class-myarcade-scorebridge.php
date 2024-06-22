<?php
/**
 * MyArcade Score API bridge
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @package MyArcade/Score
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MyArcade ScoreBridge
 */
class MyArcade_ScoreBridge {

	/**
	 * Submitted data by the game $_POST.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->initialize_data();
	}

	/**
	 * Initialize required data
	 *
	 * @access private
	 * @return void
	 */
	private function initialize_data() {

		$this->data = array(
			'game_id' => 0,
			'user_id' => 0,
			'event'   => '',
			'session' => '',
		);
	}

	/**
	 * Check if we have a valid bridge call
	 */
	public function handle() {

		// Check if this is an API call.
		if ( ! $this->is_valid_call() ) {
			return;
		}

		// Proceed only if the user is logged in.
		if ( ! get_current_user_id() ) {
			return;
		}

		// Exit if required data are missing.
		if ( ! $this->get_data() ) {
			return;
		}

		// Validate submitted data.
		if ( ! $this->is_valid_data() ) {
			return;
		}

		// Now we can handle the event.
		$this->handle_event();
	}

	/**
	 * Check if we have a score bridge call
	 *
	 * @access private
	 * @return boolean
	 */
	private function is_valid_call() {

		$action = filter_input( INPUT_GET, 'action' );
		$do     = filter_input( INPUT_GET, 'do' );

		if ( 'MyArcade' === $action && 'ScoreBridge' === $do ) {
			return true;
		}

		return false;
	}

	/**
	 * Collect submitted data by the game
	 *
	 * @access private
	 * @return bool
	 */
	private function get_data() {

		$this->data['game_id'] = filter_input( INPUT_POST, 'game_id', FILTER_VALIDATE_INT );
		$this->data['user_id'] = filter_input( INPUT_POST, 'user_id', FILTER_VALIDATE_INT );
		$this->data['event']   = filter_input( INPUT_POST, 'event' );
		$this->data['session'] = filter_input( INPUT_POST, 'session' );

		// We need all data to proceed.
		foreach ( $this->data as $key => $value ) {
			if ( ! $value ) {
				// A value can't be 0 or empty.
				return false;
			}
		}

		return true;
	}

	/**
	 * Validate data
	 *
	 * @access public
	 * @return boolean
	 */
	private function is_valid_data() {

		$user_id = get_current_user_id();

		// Check the user ID.
		if ( $user_id === $this->data['user_id'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Handle score / achievement submissions
	 *
	 * @access private
	 * @return void
	 */
	private function handle_event() {

		switch ( $this->data['event'] ) {

			case 'score':
				$score = filter_input( INPUT_POST, 'score' );

				if ( $score ) {
					$sort_order = get_post_meta( $this->data['game_id'], 'mabp_score_order', true );

					if ( ! $sort_order ) {
						$sort_order = 'DESC';
					}

					$score_array = array(
						'session'   => $this->data['session'],
						'date'      => date('Y-m-d'),
						'datatype'  => 'number',
						'game_tag'  => get_post_meta( $this->data['game_id'], 'mabp_game_tag', true ),
						'user_id'   => get_current_user_id(),
						'score'     => $score,
						'sortorder' => $sort_order,
					);

					myarcade_handle_score( $score_array );
				}
				break;

			case 'achievement':
				$achievement_icon = filter_input( INPUT_POST, 'icon', FILTER_SANITIZE_URL );
				$local_icon       = '';

				if ( $achievement_icon ) {
					$file_info = pathinfo( $achievement_icon );

					if ( ! function_exists( 'myarcade_get_file' ) ) {
						require_once MyArcade()->plugin_path() . '/core/file.php';
					}

					$file_temp           = myarcade_get_file( $achievement_icon );
					$upload_dir_specific = myarcade_get_folder_path( $file_info['filename'], 'custom' );
					$file_name           = wp_unique_filename( $upload_dir_specific['thumbsdir'], $file_info['basename'] );
					$result              = file_put_contents( $upload_dir_specific['thumbsdir'] . $file_name, $file_temp['response'] );

					if ( $result ) {
						$local_icon = $upload_dir_specific['thumbsurl'] . $file_name;
					}
				}

				$achievement = array(
					'date'        => date('Y-m-d'),
					'game_tag'    => get_post_meta( $this->data['game_id'], 'mabp_game_tag', true ),
					'user_id'     => get_current_user_id(),
					'score'       => filter_input( INPUT_POST, 'score' ),
					'name'        => filter_input( INPUT_POST, 'title' ),
					'description' => filter_input( INPUT_POST, 'description' ),
					'thumbnail'   => $local_icon,
				);

				myarcade_handle_achievement( $achievement );
				break;

			default:
				// Unknown event.
				break;
		}

		// Stop here.
		die();
	}
}
