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
 * Attribute 'value'
 *
 * @author Patrick Broens <patrick@patrickbroens.nl>
 * @package TYPO3
 * @subpackage form
 */
class tx_form_domain_model_attributes_value extends tx_form_domain_model_attributes_abstract implements tx_form_domain_model_attributes_interface {

	/**
	 * Constructor
	 *
	 * @param string $value Attribute value
	 * @param integer $elementId The ID of the element
	 * @return void
	 */
	public function __construct($value, $elementId) {
		parent::__construct($value, $elementId);
	}

	/**
	 * Sets the attribute 'value'.
	 * Used with the elements input, option, button
	 * The element or attribute definition itself gives case information.
	 *
	 * button: This attribute assigns the initial value to the button.
	 *
	 * input: This attribute specifies the initial value of the control. It is
	 * optional except when the type attribute has the value
	 * "radio" or "checkbox".
	 *
	 * option: This attribute specifies the initial value of the control.
	 * If this attribute is not set, the initial value is set to the contents
	 * of the OPTION element.
	 *
	 * @return string Attribute value
	 */
	public function getValue() {
		$attribute = (string) $this->value;

		return $attribute;
	}
}
?>