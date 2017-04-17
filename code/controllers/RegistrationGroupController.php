<?php

class RegistrationGroupController extends Page_Controller{

	public static $allowed_actions = array(
		'add',
		'edit',
		'delete',
		'GroupForm',
		'selectTicket',
		'selection'
	);

	private static $url_handlers = array(
		'selection/$ID' => 'selection',
	);

	protected $registration;
	protected $selection;

	public function __construct($record, EventRegistration $registration, TicketSelection $selection){
		parent::__construct($record);
		$this->registration = $registration;
		$this->selection = $selection;
	}

	public function index($request) {
		return $this->add($request);
	}

	public function getCurrentRegistration() {
		return $this->registration;
	}

	public function getAvailableTickets() {
		return $this->registration->Event()
			->getAvailableTickets()
			->filter("ClassName:not", "RegistrationGroupEventTicket");
	}

	public function selectTicket($request) {
		// TODO: CSRF security
		$tickets = $this->getAvailableTickets();
		$selections = $this->selection->TicketSelections();
		$ticket = $tickets->byID($request->param('ID'));
		if (!$ticket || !$ticket->exists()) {
			return $this->owner->redirectBack();
		}		
		if($request->postVar("action_add") !== null) {
			$selection = $ticket->createSelection($ticket)->write();
			$selections->add($selection);
		}
		if($request->postVar("action_subtract") !== null) {
			$selections->sort("ID", "DESC")
				->find("TicketID", $ticket->ID)->delete();
		}
		return $this->owner->redirectBack();
	}

	public function SelectedCount($ticketID) {
		return $this->selection->TicketSelections()
			->filter("TicketID", $ticketID)
			->count();
	}

	/**
	 * Add action renders the add group form.
	 * @param HTTPRequest $request
	 * @return array
	 */
	public function add($request) {
		$tickets = $this->getAvailableTickets();
		// check tickets are actually available
		if (!$tickets->count()) {
			return $this->redirect($this->BackURL);
		}
		$form = $this->GroupForm();
		$group = $this->findOrMakeGroup();
		$form->loadDataFrom($group);
		// ticket selection in url
		$ticket = $this->selection->Ticket();
		if($ticket) {
			$form->loadDataFrom(array(
				"TicketID" => $ticket->ID
			));
		}
		$data = new ArrayData(array(
			'Ticket' => $ticket,
			'Tickets' => $tickets,
			// ensure Ticket seleciton next link doesn't show // TODO: do without
			'FirstSelectionLink' => false
		));
		$template = self::config()->ticket_select_template;
		$content = $this->owner->Content.$this->renderWith($template, $data);
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
		$form->Actions()->push(AnchorField::create("cancellink", "Back", $this->BackURL));
		$form->Actions()->push(FormAction::create("save", "Next"));
		$this->extend("updateGroupForm", $form, $this->registration, $this->selection);
		return $form;
	}

	public function save($data, $form) {
		$group = $this->findOrMakeGroup();
		$form->saveInto($group);
		$group->write();
		$this->selection->GroupID = $group->ID;
		$this->selection->write();

		// redirect to first of group's attendees
		return $this->redirect($this->nextLink());
	}

	public function findOrMakeGroup($forcewrite = true) {
		$group = $this->selection->Group();
		if (!$group) {
			$group = $this->createGroup();
		}
		return $group;
	}

	/**
	 * Helper for creating new group on registration.
	 */
	protected function createGroup() {
		$group = RegistrationGroup::create();
		$group->RegistrationID = $this->registration->ID;
		$group->TicketID = $this->selection->TicketID;
		return $group;
	}

	public function FirstSelectionLink() {
		$selection = $this->selection->TicketSelections()->first();
		if (!$selection) {
			return null;
		}
		return $this->Link('selection/'.$selection->ID);
	}

	public function nextLink() {
		$selections = $this->selection->TicketSelections();
		if ($selections->count()) {
			return $this->FirstSelectionLink();
		}
		return $this->NextURL;
	}

	public function selection($request) {
		$registration = $this->registration;
		$id = $request->param('ID');
		if(!$registration || !is_numeric($id)) {
			return $this->redirect($this->Link());
		}
		$selections = $this->selection->TicketSelections();
		$selection = $selections->byID($id);
		if(!$selection) {
			return $this->redirect($this->Link());
		}
		$pager = ListPager::create($selections, $selection);
		$backurl = ($prev = $pager->prev()) ? $this->owner->Link($this->selectionSegment($prev)) : $this->Link();
		$nexturl = ($next = $pager->next()) ? $this->owner->Link($this->selectionSegment($next)) : $this->NextURL;
		$record = new Page(array(
			'ID' => -1,
			'Title' => $selection->Ticket()->Title,
			'ParentID' => $this->ParentID,
			'URLSegment' => Controller::join_links($this->URLSegment, 'selection', $selection->ID),
			'BackURL' => $backurl,
			'NextURL' => $nexturl,
			'Content' => $pager->renderWith("TicketPageIndicator", array(
				'Title' => $this->selection->Ticket()->Title
			))
		));
		$controller = new TicketSelectionController($record, $registration, $selection);
		$this->extend("updateTicketSelectionController", $controller, $record, $registration);
		return $controller;
	}

	protected function selectionSegment($selection) {
		return Controller::join_links('selection', $selection->ID);
	}

}
