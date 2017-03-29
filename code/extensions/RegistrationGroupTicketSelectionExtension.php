<?php

class RegistrationGroupTicketSelectionExtension extends Extension{

	private static $has_one = array(
		'SelectionGroup' => 'RegistrationGroupTicketSelection'
	);

}