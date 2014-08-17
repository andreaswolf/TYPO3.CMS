/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

define('TYPO3/CMS/Rtehtmlarea/Component/ToolbarText', ['TYPO3/CMS/Rtehtmlarea/HtmlArea'], function(HTMLArea) {
/*
 * Ext.ux.Toolbar.HTMLAreaToolbarText extends Ext.Toolbar.TextItem
 */
Ext.ux.Toolbar.HTMLAreaToolbarText = Ext.extend(Ext.Toolbar.TextItem, {
	/*
	 * Constructor
	 */
	initComponent: function () {
		Ext.ux.Toolbar.HTMLAreaToolbarText.superclass.initComponent.call(this);
		this.addListener({
			afterrender: {
				fn: this.initEventListeners,
				single: true
			}
		});
	},
	/*
	 * Initialize listeners
	 */
	initEventListeners: function () {
			// Monitor toolbar updates in order to refresh the state of the button
		this.mon(this.getToolbar(), 'HTMLAreaEventToolbarUpdate', this.onUpdateToolbar, this);
	},
	/*
	 * Get a reference to the editor
	 */
	getEditor: function() {
		return RTEarea[this.ownerCt.editorId].editor;
	},
	/*
	 * Get a reference to the toolbar
	 */
	getToolbar: function() {
		return this.ownerCt;
	},
	/*
	 * Handler invoked when the toolbar is updated
	 */
	onUpdateToolbar: function (mode, selectionEmpty, ancestors, endPointsInSameBlock) {
		this.setDisabled(mode === 'textmode' && !this.textMode);
		if (!this.disabled) {
			this.plugins['onUpdateToolbar'](this, mode, selectionEmpty, ancestors, endPointsInSameBlock);
		}
	}
});

Ext.reg('htmlareatoolbartext', Ext.ux.Toolbar.HTMLAreaToolbarText);

});