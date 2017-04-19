<?php

class RegistrationGroupTicketSelection extends TicketSelection {

	private static $has_one = array(
		'Group' => 'RegistrationGroup'
	);

	private static $has_many = array(
		'TicketSelections' => 'TicketSelection'
	);

	private static $select_controller_action = "group";

	private static $row_template = "RegistrationGroupTicketSelection_row";

	/**
	 * Creates a ticket selection data object.
	 */
	public function createSelection($ticket) {
		$class = $ticket->stat('selection_type');
		$selection = $class::create();
		$selection->GroupID = $this->owner->ID;
		$selection->TicketID = $ticket->ID;
		$selection->write();
		$this->owner->TicketSelections()->add($selection);
		return $selection;
	}

	public function RenderRow($baselink = "") {
		// Don't render empty groups
		if ($this->owner->TicketSelections()->count() === 0) {
			return null;
		}
		return $this->renderWith($this->stat("row_template"), array(
			"BaseLink" => $baselink."/selection/".$this->ID."/group"
		));
	}

}
