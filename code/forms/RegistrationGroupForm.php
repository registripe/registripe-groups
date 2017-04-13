<?php

class RegistrationGroupForm extends Form {

	public function __construct($controller, $name = "RegistrationGroupForm") {

		$fields = singleton("RegistrationGroup")
			->getFrontEndFields();
		//hide the ticketd field by default (can be re-introduced using setAllowedTickets)
		$fields->push(
			new HiddenField("TicketID")
		);
		//store the group id for editing
		$fields->push(
			new HiddenField("ID")
		);
		$actions = new FieldList();
		//default required fields are configurable
		$required = RegistrationGroup::config()->required_fields;
		if(!$required){
			$required = array();
		}
		$validator = new RequiredFields($required);
		parent::__construct($controller, $name, $fields, $actions, $validator);
		$this->extend("updateForm", $this);
	}

}