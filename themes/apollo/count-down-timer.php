<?php global $apollo_options;
// if(isset($apollo_options["display_countdown"])) :
	$launch = strtotime($apollo_options["launchdate"]);
	$now = time();
	$countdown = apollo_return_date($now, $launch);
?>
    <ul class="timer">
        <li class="days">
            <strong><?php echo $countdown["days"]; ?></strong>
            <small>Days</small>
        </li>
        <li class="hours">
            <strong><?php echo $countdown["hours"]; ?></strong>
            <small>Hours</small>
        </li>
        <li class="minutes">
            <strong><?php echo $countdown["minutes"]; ?></strong>
            <small>Minutes</small>
        </li>
        <li class="seconds">
            <strong><?php echo $countdown["seconds"]; ?></strong>
            <small>Seconds</small>
        </li>
    </ul>
<?php// endif; ?>