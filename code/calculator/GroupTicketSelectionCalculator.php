<?php

namespace EventRegistration\Calculator;

class GroupTicketSelectionCalculator extends AbstractCalculator {

	protected $selectionGroup;

	public function __construct(\RegistrationGroupTicketSelection $selectionGroup) {
		$this->selectionGroup = $selectionGroup;
	}

	public function calculate($value) {
		$calculator = new SelectionsCalculator($this->selectionGroup->TicketSelections());
		return $calculator->calculate($value);
	}

}