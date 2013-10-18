<?php
/**
 * Meta Behavior Test Case File
 *
 * Copyright (c) 2007-2012 David Persson
 *
 * Distributed under the terms of the MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP 5
 * CakePHP 2
 *
 * @copyright     2007-2012 David Persson <davidpersson@gmx.de>
 * @link          http://github.com/davidpersson/media
 * @package       Media.Test.Case.Model.Behavior
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

require_once dirname(__FILE__) . DS . 'BehaviorTestBase.php';

/**
 * Meta Behavior Test Case Class
 *
 * @package       Media.Test.Case.Model.Behavior
 */
class MetaBehaviorTest extends BaseBehaviorTest {

	public function setUp() {
		parent::setUp();

		$this->_behaviorSettings['Coupler'] = array(
			'baseDirectory' => $this->Folder->pwd()
		);
		$this->_behaviorSettings['Meta'] = array(
			'level' => 1
		);
	}

	public function testSetup() {
		$Model = ClassRegistry::init('TheVoid');
		$Model->Behaviors->load('Media.Meta');

		$Model = ClassRegistry::init('Song');
		$Model->Behaviors->load('Media.Meta');
	}

	public function testSave() {
		$Model = ClassRegistry::init('Song');
		$Model->Behaviors->load('Media.Meta', $this->_behaviorSettings['Meta']);

		$data = array('Song' => array('file' => $this->file0));
		$result = $Model->save($data);
		$Model->Behaviors->detach('Media.Meta');

		$id = $Model->getLastInsertID();
		$result = $Model->findById($id);
		$Model->delete($id);
		$this->assertEqual($result['Song']['checksum'], md5_file($this->file0));
	}

	public function testFind() {
		$Model = ClassRegistry::init('Song');
		$Model->Behaviors->load('Media.Coupler', $this->_behaviorSettings['Coupler']);
		$Model->Behaviors->load('Media.Meta', $this->_behaviorSettings['Meta']);
		$result = $Model->find('all');
		$this->assertEqual(count($result), 3);

		/* Virtual */
		$result = $Model->findById(1);
		$this->assertTrue(Set::matches('/Song/size', $result));
		$this->assertTrue(Set::matches('/Song/mime_type',$result));
	}

}

