<?php

use AJT\Toggl\TogglClient;

/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function filter_handler( $data, $postarr ) {
	if($data['post_type'] === 'valu_toggl_day' && isset($_POST[ 'fields' ])){
		$data['post_title'] = $_POST[ 'fields' ][ 'field_546613dab92d4' ];
	}
	
	return $data;
}

add_filter( 'wp_insert_post_data', 'filter_handler', '99', 2 );

/**
 * Save acf metadata when post is saved
 * @param type $post_id
 * @return type
 */
function my_acf_save_post( $post_id ) {

	// bail early if no ACF data
	if ( empty( $_POST[ 'fields' ] ) || empty( $_POST[ 'fields' ][ 'field_546304dab92d4' ] ) || empty( $_POST[ 'fields' ][ 'field_546304dab93c8' ] ) || empty($_POST[ 'fields' ][ 'field_546613dab92d4' ]) ) {
		return;
	}

	// array of field values
	$fields = $_POST[ 'fields' ];

	if ( !is_valid_time( $fields[ 'field_546304dab92d4' ] ) || !is_valid_time( $fields[ 'field_546304dab93c8' ] ) ) {
		return;
	}

	// if you want to see what is happening, add debug => true to the factory call
	$toggl_client = TogglClient::factory( array( 'api_key' => $GLOBALS[ 'Toggl_Helper' ]->toggl_api_key, 'debug' => false ) );
	
	$today	 = $_POST[ 'fields' ][ 'field_546613dab92d4' ];	
	//calculate duration of the toggl time entries 
	$time_entries = get_time_entries( $toggl_client, $today );
	if ( !empty( $time_entries ) ) {	
		$time_entries_duration = 0;
		foreach ( $time_entries as $entry ) {
			$time_entries_duration += $entry[ 'duration' ];
		}
	}else {
		$time_entries_duration = 0;
	}
	
	$break_time	 = (float)$_POST[ 'fields' ][ 'field_654304dab93c8' ];
	if ( is_float( $break_time )) {
		$workday_in_seconds = get_workday_in_seconds( $today ) - ($break_time*60*60); //Lets minus the breaks
	}else {
		$workday_in_seconds = get_workday_in_seconds( $today );
	}
	$other_works = $workday_in_seconds - $time_entries_duration;

	$_POST[ 'fields' ][ 'field_546304ceb92d3' ] = gmdate( "H:i:s", $workday_in_seconds );

	if ( $GLOBALS[ 'Toggl_Helper' ]->other_works_id > 0 && $other_works > 0 && is_numeric( $other_works ) ) {
		$toggl_client->CreateTimeEntry( array( 'time_entry' => array(
				'description'	 => $GLOBALS[ 'Toggl_Helper' ]->description,
				'pid'			 => $GLOBALS[ 'Toggl_Helper' ]->other_works_id,
				'created_with'	 => 'Wordpress-plugin',
				'duration'		 => (int) $other_works,
				'start'			 => $today.'T17:00:00+00:00' ) ) );
	}
	
	$total = (7.5*60*60)-$workday_in_seconds;
	$pre = '-';
	if ($total < 0) {
		$total = -1*$total;
		$pre = '+';
	}
	$_POST[ 'fields' ][ 'field_546304dab94d3' ] =  $pre.gmdate( "H:i:s", $total );

}

add_action( 'acf/save_post', 'my_acf_save_post', 1 );

function get_workday_in_seconds( $today ) {
	//get acf-field
	$start_time_acf	 = $_POST[ 'fields' ][ 'field_546304dab92d4' ];
	$end_time_acf	 = $_POST[ 'fields' ][ 'field_546304dab93c8' ];

	//convert times into right format
	$start_time	 = new DateTime( $today . 'T' . $start_time_acf . ':00+00:00' );
	$end_time	 = new DateTime( $today . 'T' . $end_time_acf . ':00+00:00' );
	//calculate how long workday lasts
	$workday_in_seconds = abs( $start_time->getTimestamp() - $end_time->getTimestamp() );
	return $workday_in_seconds;
}

/**
 * 
 * @param type $toggl_client
 * @return string
 */
function get_time_entries( $toggl_client, $today ) {
	$today_start	 = $today . 'T00:00:00+00:00';
	$today_end		 = $today . 'T23:59:59+00:00';
	$time_entries	 = $toggl_client->GetTimeEntries( array( "start_date" => $today_start, "end_date" => $today_end ) );
	if ( !empty( $time_entries ) ) {
		return $time_entries;
	}

	return '';
}

/**
 * Checks whether $time is valid time (hh:mm) or not
 * 
 * @param type $time
 * @return boolean
 */
function is_valid_time( $time ) {
	if ( strlen( $time ) !== 5 ) {
		return false;
	} elseif ( strcmp( substr( $time, 2, 1 ), ':' ) !== 0 ) {
		return false;
	}
	$hour_minute = explode( ':', $time );
	if ( is_numeric( $hour_minute[ 0 ] ) && is_numeric( $hour_minute[ 1 ] ) ) {
		return true;
	}
	return false;
}
