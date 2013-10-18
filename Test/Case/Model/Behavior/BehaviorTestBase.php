<?php
/**
 * Base Behavior Test Case File
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

if (!defined('MEDIA')) {
	define('MEDIA', TMP . 'tests' . DS);
} elseif (MEDIA != TMP . 'tests' . DS) {
	trigger_error('MEDIA constant already defined and not pointing to tests directory.', E_USER_ERROR);
}

App::uses('Model', 'Model');
App::uses('Folder', 'Utility');

require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DS . 'Config' . DS . 'bootstrap.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . DS . 'Fixture' . DS . 'TestData.php';
require_once CORE_TEST_CASES . DS . 'Model' . DS . 'models.php';
require_once dirname(dirname(__FILE__)) . DS . 'models.php';

/**
 * Base Behavior Test Case Class
 *
 * @package       Media.Test.Case.Model.Behavior
 */
abstract class BaseBehaviorTest extends CakeTestCase {

	public $fixtures = array('plugin.media.song', 'core.image');

	protected $_behaviorSettings = array();

	public function start() {
		parent::start();

		if (in_array('plugin.media.song', $this->fixtures)) {
			$this->loadFixtures('Song');
		}
	}

	public function setUp() {
		parent::setUp();

		$this->Folder = new Folder(TMP . 'tests' . DS, true);
		$this->Folder->create($this->Folder->pwd() . 'static' . DS . 'img');
		$this->Folder->create($this->Folder->pwd() . 'static' . DS . 'doc');
		$this->Folder->create($this->Folder->pwd() . 'static' . DS . 'txt');
		$this->Folder->create($this->Folder->pwd() . 'filter');
		$this->Folder->create($this->Folder->pwd() . 'transfer');

		$this->Data = new TestData();
		$this->file0 = $this->Data->getFile(array(
			'image-png.png' => $this->Folder->pwd() . 'static' . DS . 'img' . DS . 'image-png.png'
		));
		$this->file1 = $this->Data->getFile(array(
			'image-jpg.jpg' => $this->Folder->pwd() . 'static' . DS . 'img' . DS . 'image-jpg.jpg'
		));
		$this->file2 = $this->Data->getFile(array(
			'text-plain.txt' => $this->Folder->pwd() . 'static' . DS . 'txt' . DS . 'text-plain.txt'
		));

		$this->_mediaConfig = Configure::read('Media');
	}

	public function tearDown() {
		parent::tearDown();

		$this->Data->flushFiles();
		$this->Folder->delete();
		ClassRegistry::flush();
		Configure::write('Media', $this->_mediaConfig);
	}

	protected function _isWindows()
	{
		return strtolower(substr(PHP_OS, 0, 3)) === 'win';
	}

}
