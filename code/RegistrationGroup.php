<?php

class RegistrationGroup extends DataObject
{

	private static $db = array(
		'Name' => 'Varchar'
	);

	private static $has_one = array(
		'Ticket' => 'EventTicket'
	);

	private static $has_many = array(
		'Attendees' => 'EventAttendee'
	);

}
