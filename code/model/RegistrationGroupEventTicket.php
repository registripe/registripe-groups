<?php

class RegistrationGroupEventTicket extends EventTicket {

	private static $singular_name = "Group Ticket";
	private static $plural_name = "Group Tickets";

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName(array("Price"));
		return $fields;
	}

}