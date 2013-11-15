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

App::uses('Set', 'Utility');

require_once dirname(dirname(dirname(__FILE__))) . DS . 'constants.php';
require_once dirname(__FILE__) . DS . 'BehaviorTestBase.php';

/**
 * Meta Behavior Test Case Class
 *
 * @package       Media.Test.Case.Model.Behavior
 */
class MetaBehaviorTest extends BaseBehaviorTest {

	public $record1File;

	public function setUp() {
		parent::setUp();

		$this->behaviorSettings = array(
			'Coupler' => array(
				'baseDirectory' => $this->Data->settings['base']
			),
			'Meta' => array(
				'level' => 1
			)
		);

		$this->record1File = $this->Data->getFile(array(
			'image-png.png' => $this->Data->settings['static'] . 'img/image-png.png'
		));

	}

	public function testSetup() {
		$Model = ClassRegistry::init('TheVoid');
		$Model->Behaviors->load('Media.Meta');

		$Model = ClassRegistry::init('Song');
		$Model->Behaviors->load('Media.Meta');
	}

	public function testSave() {
		$Model = ClassRegistry::init('Song');
		$Model->Behaviors->load('Media.Meta', $this->behaviorSettings['Meta']);

		$data = array('Song' => array('file' => $this->record1File));
		$this->assertTrue(!!$Model->save($data));
		$Model->Behaviors->detach('Media.Meta');

		$id = $Model->getLastInsertID();
		$result = $Model->findById($id);
		$Model->delete($id);
		$this->assertEqual($result['Song']['checksum'], md5_file($this->record1File));
	}

	public function testFind() {
		$Model = ClassRegistry::init('Song');
		$Model->Behaviors->load('Media.Coupler', $this->behaviorSettings['Coupler']);
		$Model->Behaviors->load('Media.Meta', $this->behaviorSettings['Meta']);
		$result = $Model->find('all');
		$this->assertEqual(count($result), 4);

		/* Virtual */
		$result = $Model->findById(1);
		$this->assertTrue(Set::matches('/Song/size', $result));
		$this->assertTrue(Set::matches('/Song/mime_type',$result));
	}

	public function testRegenerate() {
		$Model = ClassRegistry::init('Song');
		$Model->Behaviors->load('Media.Meta', $this->behaviorSettings['Meta']);

		$data = array('Song' => array('file' => $this->record1File));
		$this->assertTrue(!!$Model->save($data));
		$Model->Behaviors->unload('Media.Meta');

		$id = $Model->getLastInsertID();
		$result = $Model->findById($id);
		$checksum = $result['Song']['checksum'];
		$this->assertEqual($result['Song']['checksum'], md5_file($this->record1File));


		$Model->Behaviors->load('Media.Meta', $this->behaviorSettings['Meta']);

		$file = $this->Data->getFile(
			array('image-jpg.jpg' => $this->Data->settings['transfer'] . 'ta.jpg')
		);

		$data = array('Song' => array('id' => $id, 'file' => $file));
		$this->assertTrue(!!$Model->save($data));
		$Model->Behaviors->unload('Media.Meta');

		$result = $Model->findById($id);
		$this->assertNotEquals($result['Song']['checksum'], $checksum);
		$this->assertEqual($result['Song']['checksum'], md5_file($file));
	}

}
