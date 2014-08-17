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

define('TYPO3/CMS/Rtehtmlarea/Component/Ajax', ['TYPO3/CMS/Rtehtmlarea/HtmlArea'], function(HTMLArea) {

HTMLArea.Ajax = function (config) {
	Ext.apply(this, config);
};
HTMLArea.Ajax = Ext.extend(HTMLArea.Ajax, {
	/*
	 * Load a Javascript file asynchronously
	 *
	 * @param	string		url: url of the file to load
	 * @param	function	callBack: the callBack function
	 * @param	object		scope: scope of the callbacks
	 *
	 * @return	boolean		true on success of the request submission
	 */
	getJavascriptFile: function (url, callback, scope) {
		var success = false;
		var self = this;
		Ext.Ajax.request({
			method: 'GET',
			url: url,
			callback: callback,
			success: function (response) {
				success = true;
			},
			failure: function (response) {
				self.editor.inhibitKeyboardInput = false;
				self.editor.appendToLog('HTMLArea.Ajax', 'getJavascriptFile', 'Unable to get ' + url + ' . Server reported ' + response.status, 'error');
			},
			scope: scope
		});
		return success;
	},
	/*
	 * Post data to the server
	 *
	 * @param	string		url: url to post data to
	 * @param	object		data: data to be posted
	 * @param	function	callback: function that will handle the response returned by the server
	 * @param	object		scope: scope of the callbacks
	 *
	 * @return	boolean		true on success
	 */
	postData: function (url, data, callback, scope) {
		var success = false;
		var self = this;
		data.charset = this.editor.config.typo3ContentCharset ? this.editor.config.typo3ContentCharset : 'utf-8';
		var params = '';
		Ext.iterate(data, function (parameter, value) {
			params += (params.length ? '&' : '') + parameter + '=' + encodeURIComponent(value);
		});
		params += this.editor.config.RTEtsConfigParams;
		Ext.Ajax.request({
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			url: url,
			params: params,
			callback: Ext.isFunction(callback) ? callback: function (options, success, response) {
				if (!success) {
					self.editor.appendToLog('HTMLArea.Ajax', 'postData', 'Post request to ' + url + ' failed. Server reported ' + response.status, 'error');
				}
			},
			success: function (response) {
				success = true;
			},
			failure: function (response) {
				self.editor.appendToLog('HTMLArea.Ajax', 'postData', 'Unable to post ' + url + ' . Server reported ' + response.status, 'error');
			},
			scope: scope
		});
		return success;
	}
});

});