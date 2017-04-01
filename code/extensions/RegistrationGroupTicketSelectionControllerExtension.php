<?php

class RegistrationGroupTicketSelectionControllerExtension extends Extension{

	public static $allowed_actions = array(
		'group'
	);

	public function group($request) {
		$controller = $this->owner;
		$registration = $controller->registration;
		$selection = $controller->selection;
		$record = new Page(array(
			'ID' => -1,
			'Title' => $controller->Title,
			'ParentID' => $controller->ParentID,
			'URLSegment' => Controller::join_links($controller->URLSegment, 'group'),
			'BackURL' => $controller->BackURL,
			'NextURL' => $controller->NextURL
		));
		$controller = new RegistrationGroupController($record, $registration, $selection);
		$controller->extend('updateRegistrationGroupController', $controller, $record, $registration, $selection);
		return $controller;
	}

}