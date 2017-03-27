<?php

class EventAttendeeGroupExtension extends DataExtension{

	private static $has_one = array(
		'Group' => 'RegistrationGroup'
	);

	public function updateFrontEndFields(FieldList $fields) {
		$fields->removeByName(array("GroupID"));
	}

}
