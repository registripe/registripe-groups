<?php

class EventTicketGroupExtension extends DataExtension{

	private static $db = array(
		"ForGroup" => "Boolean"
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->push(CheckboxField::create("ForGroup", "For group"));
	}

}