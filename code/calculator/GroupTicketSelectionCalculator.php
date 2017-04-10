<?php

namespace EventRegistration\Calculator;

class GroupTicketSelectionCalculator extends SelectionsCalculator {

	public function __construct(\RegistrationGroupTicketSelection $groupSelection) {
		$this->groupSelection = $groupSelection;
		parent::__construct($groupSelection->TicketSelections());
	}

}
