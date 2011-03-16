<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2011 Andreas Wolf <andreas.wolf@ikt-werk.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


require_once 'vfsStream/vfsStream.php';

/**
 * Testcase for the VFS repository class
 *
 * @package TYPO3
 * @subpackage t3lib
 *
 * @author Andreas Wolf <andreas.wolf@ikt-werk.de>
 */
class t3lib_vfs_repositoryTest extends tx_phpunit_testcase {

	/**
	 * @var t3lib_vfs_Repository
	 */
	private $fixture;

	public function setUp() {
		$this->fixture = new t3lib_vfs_Repository();
	}

	/**
	 * @test
	 */
	public function updateNodeUsesCorrectTablesForObjects() {
		$changedProperties = array('test' => 'test2');
		$fileMock = $this->getMock('t3lib_vfs_File', array(), array(), '', FALSE);
		$fileMock->expects($this->any())->method('getChangedProperties')->will($this->returnValue($changedProperties));
		$folderMock = $this->getMock('t3lib_vfs_Folder', array(), array(), '', FALSE);
		$folderMock->expects($this->any())->method('getChangedProperties')->will($this->returnValue($changedProperties));

		$dbMock = $this->getMock('t3lib_DB', array('exec_UPDATEquery'), array(), '', FALSE);
		$dbMock->expects($this->at(0))->method('exec_UPDATEquery')->with('sys_file');
		$dbMock->expects($this->at(1))->method('exec_UPDATEquery')->with('sys_folder');
		$GLOBALS['TYPO3_DB'] = $dbMock;

		$this->fixture->updateNodeInDatabase($fileMock);
		$this->fixture->updateNodeInDatabase($folderMock);
	}

	/**
	 * @test
	 */
	public function updateNodeUsesCorrectRecordIdentity() {
		$uid = rand(1, 100);
		$changedProperties = array('test' => 'test2');
		$fileMock = $this->getMock('t3lib_vfs_File', array(), array(), '', FALSE);
		$fileMock->expects($this->any())->method('getValue')->with($this->equalTo('uid'))->will($this->returnValue($uid));
		$fileMock->expects($this->any())->method('getChangedProperties')->will($this->returnValue($changedProperties));

		$dbMock = $this->getMock('t3lib_DB', array(), array(), '', FALSE);
		$dbMock->expects($this->once())->method('exec_UPDATEquery')->with('sys_file', $this->stringContains((string)$uid));
		$GLOBALS['TYPO3_DB'] = $dbMock;

		$this->fixture->updateNodeInDatabase($fileMock);
	}

	/**
	 * @test
	 */
	public function updateNodeUpdatesFields() {
		$changedProperties = array('test' => 'test2');
		$fileMock = $this->getMock('t3lib_vfs_File', array(), array(), '', FALSE);
		$fileMock->expects($this->any())->method('getChangedProperties')->will($this->returnValue($changedProperties));

		$dbMock = $this->getMock('t3lib_DB', array(), array(), '', FALSE);
		$dbMock->expects($this->once())->method('exec_UPDATEquery')->with($this->anything(), $this->anything(), $changedProperties);
		$GLOBALS['TYPO3_DB'] = $dbMock;

		$this->fixture->updateNodeInDatabase($fileMock);
	}

	/**
	 * @test
	 */
	public function updateNodeRemovesImmutablePropertiesFromUpdateFields() {
		$changedProperties = array(
			'test' => uniqid(),
			'uid' => uniqid(),
			'crdate' => uniqid(),
			'cruser_id' => uniqid()
		);

		$fileMock = $this->getMock('t3lib_vfs_File', array(), array(), '', FALSE);
		$fileMock->expects($this->any())->method('getChangedProperties')->will($this->returnValue($changedProperties));

		$dbMock = $this->getMock('t3lib_DB', array('exec_UPDATEquery'), array(), '', FALSE);
		$dbMock->expects($this->at(0))->method('exec_UPDATEquery')->will($this->returnCallback(array($this, 'updateNodeRemovesImmutablePropertiesFromUpdateFields_callback')));
		$GLOBALS['TYPO3_DB'] = $dbMock;

		$this->fixture->updateNodeInDatabase($fileMock);
	}

	public function updateNodeRemovesImmutablePropertiesFromUpdateFields_callback($table, $where, $updateFields) {
		$this->assertArrayNotHasKey('uid', $updateFields);
		$this->assertArrayNotHasKey('crdate', $updateFields);
		$this->assertArrayNotHasKey('cruser_id', $updateFields);
	}

	/**
	 * @test
	 */
	public function getFolderNodeTraversesPathFromRootnode() {
		$pathParts = array(
			'subfolder',
			'anothersubfolder'
		);

		$mockedFolder1 = $this->getMock('t3lib_vfs_Folder', array(), array(), '', FALSE);
		$mockedFolder2 = $this->getMock('t3lib_vfs_Folder', array(), array(), '', FALSE);
		$mockedFolder2->expects($this->once())->method('getSubfolder')->with($pathParts[1])->will($this->returnValue($mockedFolder1));
		$mockedRootNode = $this->getMock('t3lib_vfs_RootNode');
		$mockedRootNode->expects($this->once())->method('getSubfolder')->with($pathParts[0])->will($this->returnValue($mockedFolder2));
		t3lib_div::setSingletonInstance('t3lib_vfs_RootNode', $mockedRootNode);

		$folder = $this->fixture->getFolderNode(implode('/', $pathParts));
		$this->assertEquals($mockedFolder1, $folder);
	}

	/**
	 * @test
	 */
	public function getFolderNodeIgnoresLeadingAndTrailingSlash() {
		$pathParts = array(
			'subfolder'
		);

		$mockedFolder = $this->getMock('t3lib_vfs_Folder', array(), array(), '', FALSE);
		$mockedRootNode = $this->getMock('t3lib_vfs_RootNode');
		$mockedRootNode->expects($this->once())->method('getSubfolder')->with($pathParts[0])->will($this->returnValue($mockedFolder));
		t3lib_div::setSingletonInstance('t3lib_vfs_RootNode', $mockedRootNode);

		$folder = $this->fixture->getFolderNode('/' . implode('/', $pathParts) . '/');
		$this->assertEquals($mockedFolder, $folder);
	}

	/**
	 * @test
	 */
	public function getFolderNodeIgnoresEmptyPathParts() {
		$path = '/subfolder//anothersubfolder/';

		$mockedFolder1 = $this->getMock('t3lib_vfs_Folder', array(), array(), '', FALSE);
		$mockedFolder2 = $this->getMock('t3lib_vfs_Folder', array(), array(), '', FALSE);
		$mockedFolder2->expects($this->once())->method('getSubfolder')->with('anothersubfolder')->will($this->returnValue($mockedFolder1));
		$mockedRootNode = $this->getMock('t3lib_vfs_RootNode');
		$mockedRootNode->expects($this->once())->method('getSubfolder')->with('subfolder')->will($this->returnValue($mockedFolder2));
		t3lib_div::setSingletonInstance('t3lib_vfs_RootNode', $mockedRootNode);

		$folder = $this->fixture->getFolderNode($path);
		$this->assertEquals($mockedFolder1, $folder);
	}
}

?>