<?php

class RegistrationGroupTicketSelection extends TicketSelection {

	private static $has_many = array(
		'TicketSelections' => 'TicketSelection'
	);

	/**
	 * Creates a ticket selection data object.
	 */
	public function createSelection($ticket) {
		$class = Config::inst()->get(get_class($ticket), 'selection_type');
		$selection = $class::create();
		$selection->GroupID = $this->owner->ID;
		$selection->TicketID = $ticket->ID;
		$selection->write();
		$this->owner->TicketSelections()->add($selection);
		return $selection;
	}

}