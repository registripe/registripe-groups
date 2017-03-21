<?php

class RegistrationGroupController extends Page_Controller{

	public static $allowed_actions = array(
		'add',
		'edit',
		'delete',
		'GroupForm'
	);

	protected $registration;

	public function __construct($record, EventRegistration $registration){
		parent::__construct($record);
		$this->registration = $registration;
	}

	public function index($request) {
		return $this->add($request);
	}

	public function getCurrentRegistration() {
		return $this->registration;
	}

	public function getTickets() {
		return $this->registration->Event()->getAvailableTickets()
			->filter("ForGroup", false);
	}

	/**
	 * Add action renders the add group form.
	 * @param HTTPRequest $request
	 * @return array
	 */
	public function add($request) {
		$tickets = $this->getTickets();
		$form = $this->GroupForm();
		// check tickets are actually available
		if (!$tickets->count()) {
			return $this->redirect($this->BackURL);
		}
		$group = $this->createGroup();
		// ticket selection in url
		$ticket = $tickets->byID((int)$request->param('ID'));
		// if($ticket && !$ticket->exists()){
		// 	$attendee->TicketID = $ticket->ID;
		// 	$form->setAllowedTickets(
		// 		$this->registration->Event()->getAvailableTickets()
		// 	);
		// }
		$form->loadDataFrom($group);
		if($ticket) {
			$form->loadDataFrom(array(
				"TicketID" => $ticket->ID
			));
		}else{
			// $form->setAllowedTickets(
			// 	$this->registration->Event()->getAvailableTickets()
			// );
		}
		
		$data = new ArrayData(array(
			"FirstAttendeeLink" => false,
			'Tickets' => $tickets
		));
		$template = self::config()->ticket_select_template;
		$content = $this->renderWith($template, $data);

		//automatically populate from previous attendee
		$this->extend("onAdd", $form, $this->registration);
		return array(
			'Title' => $ticket ? $ticket->Title : null,
			'Content' => $content,
			'Form' => $form
		);
	}

	/**
	 * Create the form for adding/editing records
	 * @return EventGroupForm
	 */
	public function GroupForm() {
		$form = new RegistrationGroupForm($this, "GroupForm");
		$this->extend("updateGroupForm", $form, $this->registration);
		// $form->addCancelLink($this->BackURL);
		return $form;
	}

	// add attendees
	// TODO: capture overall group details
	// TODO: capture each attendee's details

	/**
	 * Helper for creating new group on registration.
	 */
	protected function createGroup() {
		$group = RegistrationGroup::create();
		$group->RegistrationID = $this->registration->ID;
		return $group;
	}

}
