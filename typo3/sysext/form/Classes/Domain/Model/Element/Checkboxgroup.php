<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Patrick Broens (patrick@patrickbroens.nl)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Checkbox group model object
 *
 * @author Patrick Broens <patrick@patrickbroens.nl>
 * @package TYPO3
 * @subpackage form
 */
class tx_form_domain_model_element_checkboxgroup extends tx_form_domain_model_element_fieldset {

	/**
	 * Constructor
	 * Sets the configuration, calls parent constructor, fills the attributes
	 * and adds all child objects
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Add child object to this element
	 *
	 * @param object $element The child object
	 * @return object
	 */
	public function addElement($element) {
		if ($element->acceptsParentName()) {
			$element->setName($this->getName());
			$element->attributes->setValue('name', $this->getName());
			$element->checkFilterAndSetIncomingDataFromRequest();
		}
		$this->elements[] = $element;
		return $this;
	}
}
?>