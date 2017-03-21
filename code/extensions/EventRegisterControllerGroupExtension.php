<?php

class EventRegisterControllerGroupExtension extends Extension {

	private static $allowed_actions = array(
		'group'
	);

	public function group($request) {
		$forcewrite = $request->isPOST(); // start rego if form is submitting
		$registration = $this->owner->getCurrentRegistration($forcewrite);
		$nexturl = $this->owner->Link('review');
		$backurl = $this->owner->canReview() ?	$nexturl : $this->owner->Link();
		$record = new Page(array(
			'ID' => -1,
			'Title' => $this->owner->Title,
			'ParentID' => $this->owner->ID,
			'URLSegment' => 'register/group',
			'BackURL' => $backurl,
			'NextURL' => $this->owner->Link('review')
		));
		$controller = new RegistrationGroupController($record, $registration);
		$this->owner->extend("updateRegistrationGroupController", $controller, $record, $registration);

		return $controller;
	}

}
