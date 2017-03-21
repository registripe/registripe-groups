<?php

class EventAttendeeGroupExtension extends DataExtension{

	private static $has_one = array(
		'Group' => 'RegistrationGroup'
	);

}
