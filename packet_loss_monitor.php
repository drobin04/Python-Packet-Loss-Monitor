<?php
// Set the timezone to Cancun
date_default_timezone_set('America/Cancun');

$database = new SQLite3('results.s3db');

// Calculate time passed since last ping failure
$last_failure_query = $database->query('SELECT MAX(timestamp) FROM packet_loss');
$last_failure_timestamp = $last_failure_query->fetchArray()[0];
$time_passed = time() - strtotime($last_failure_timestamp);

// Calculate the count of failures in the past hour
$current_time = time();
$one_hour_ago = $current_time - 3600;
$past_hour_query = $database->query("SELECT COUNT(*) FROM packet_loss WHERE timestamp >= $one_hour_ago");
$num_failures = $past_hour_query->fetchArray()[0];

// Calculate hours, minutes, and seconds
$hours = floor($time_passed / 3600);
$minutes = floor(($time_passed % 3600) / 60);
$seconds = $time_passed % 60;

// Determine the background color based on the time passed
$background_color = ($time_passed > 3600) ? '#c5f3c1' : '#ffcccb';

// Display the information with the appropriate background color
echo '<html>';
echo '<head><meta http-equiv="refresh" content="4">';
echo '<style>';
echo 'body {';
echo '  background-color: ' . $background_color . ';';
echo '}';
echo '</style>';
echo '</head>';
echo '<body>';
echo "Time passed since last ping failure: $hours hours, $minutes minutes, $seconds seconds<br>";
echo "Number of failures in the past hour: $num_failures";
echo '</body>';
echo '</html>';
?>
