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
 * Checkbox model object
 *
 * @author Patrick Broens <patrick@patrickbroens.nl>
 * @package TYPO3
 * @subpackage form
 */
class tx_form_domain_model_element_checkbox extends tx_form_domain_model_element_abstract {

	/**
	 * Allowed attributes for this object
	 *
	 * @var array
	 */
	protected $allowedAttributes = array(
		'accesskey' => '',
		'alt' => '',
		'checked' => '',
		'class' => '',
		'dir' => '',
		'disabled' => '',
		'id' => '',
		'lang' => '',
		'name' => '',
		'style' => '',
		'tabindex' => '',
		'title' => '',
		'type' => 'checkbox',
		'value' => '',
	);

	/**
	 * Mandatory attributes for this object
	 *
	 * @var array
	 */
	protected $mandatoryAttributes = array(
		'name',
		'id'
	);

	/**
	 * True if it accepts the parent name instead of its own
	 * This is necessary for groups
	 *
	 * @var boolean
	 */
	protected $acceptsParentName = TRUE;

	/**
	 * Constructor
	 * Sets the configuration, calls parent constructor and fills the attributes
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Set the value of the checkbox
	 *
	 * If there is submitted data for this field
	 * it will change the checked attribute
	 *
	 * @return object
	 * @author Patrick Broens <patrick@patrickbroens.nl>
	 * @see typo3/sysext/form/model/element/tx_form_domain_model_element#checkFilterAndSetIncomingDataFromRequest()
	 */
	public function checkFilterAndSetIncomingDataFromRequest() {
		if ($this->value === '') {
			$this->value = (string) $this->getElementId();
			$this->setAttribute('value', $this->value);
		}

		if($this->requestHandler->has($this->getName())) {
			$submittedValue = $this->requestHandler->getByMethod($this->getName());
			if(is_array($submittedValue) && in_array($this->value, $submittedValue)) {
				$this->setAttribute('checked', 'checked');
			} elseif($submittedValue === $this->value) {
				$this->setAttribute('checked', 'checked');
			} elseif(is_array($submittedValue) && in_array('on', $submittedValue)) {
				$this->setAttribute('checked', 'checked');
			}
		} elseif($this->requestHandler->hasRequest()) {
			$this->attributes->removeAttribute('checked');
		}
		return $this;
	}

	/**
	 * Set a specific attribute by name and value
	 *
	 * @param string $attribute Name of the attribute
	 * @param mixed $value Value of the attribute
	 * @return object
	 */
	public function setAttribute($attribute, $value) {
		if(array_key_exists($attribute, $this->allowedAttributes)) {
			$this->attributes->addAttribute($attribute, $value);
		}

		if($attribute === 'name') {
			$nameAttribute = $this->attributes->getAttributeObjectByKey('name');
			$nameAttribute->setAddition('[]');
			$this->attributes->setAttribute('name', $nameAttribute);
		}

		return $this;
	}
}
?>