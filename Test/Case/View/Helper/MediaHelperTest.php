<?php
/**
 * Media Helper Test Case File
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
 * @package       Media.Test.Case.View.Helper
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

if (!defined('MEDIA')) {
	define('MEDIA', TMP . 'tests' . DS);
} elseif (MEDIA != TMP . 'tests' . DS) {
	trigger_error('MEDIA constant already defined and not pointing to tests directory.', E_USER_ERROR);
}

App::uses('ClassRegistry', 'Utility');
App::uses('View', 'View');
App::uses('MediaHelper', 'Media.View/Helper');

require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DS . 'Config' . DS . 'bootstrap.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . DS . 'Fixture' . DS . 'TestData.php';

/**
 * Mock Media Helper
 *
 * @package       Media.Test.Case.View.Helper
 */
class MockMediaHelper extends MediaHelper {

	public function versions() {
		return $this->_versions;
	}

	public function directories() {
		return $this->_directories;
	}

}

/**
 * Media Helper Test Case Class
 *
 * @package       Media.Test.Case.View.Helper
 */
class MediaHelperTest extends CakeTestCase {

/**
 * Media helper instance
 *
 * @var MediaHelper
 */
	public $Media = null;

	public function setUp() {
		parent::setUp();

		$this->_config = Configure::read('Media');

		$this->TmpFolder = new Folder(TMP . 'tests' . DS, true);
		$this->TmpFolder->create($this->TmpFolder->pwd() . 'static');
		$this->TmpFolder->create($this->TmpFolder->pwd() . 'static' . DS . 'img');
		$this->TmpFolder->create($this->TmpFolder->pwd() . 'filter');
		$this->TmpFolder->create($this->TmpFolder->pwd() . 'filter' . DS . 's' . DS . 'static' . DS . 'img');
		$this->TmpFolder->create($this->TmpFolder->pwd() . 'transfer');
		$this->TmpFolder->create($this->TmpFolder->pwd() . 'transfer' . DS . 'img');
		$this->TmpFolder->create($this->TmpFolder->pwd() . 'filter' . DS . 's' . DS . 'transfer' . DS . 'img');
		$this->TmpFolder->create($this->TmpFolder->pwd() . 'theme');
		$this->TmpFolder->create($this->TmpFolder->pwd() . 'theme' . DS . 'blanko');
		$this->TmpFolder->create($this->TmpFolder->pwd() . 'theme' . DS . 'blanko' . DS . 'img' . DS);

		$this->TestData = new TestData();

		$this->file0 = $this->TestData->getFile(array(
			'image-png.png' => $this->TmpFolder->pwd() . 'static/img/image-png.png'));
		$this->file1 = $this->TestData->getFile(array(
			'image-png.png' => $this->TmpFolder->pwd() . 'filter/s/static/img/image-png.png'));
		$this->file2 = $this->TestData->getFile(array(
			'image-png.png' => $this->TmpFolder->pwd() . 'filter/s/static/img/dot.ted.name.png'));
		$this->file3 = $this->TestData->getFile(array(
			'image-png.png' => $this->TmpFolder->pwd() . 'transfer/img/image-png-x.png'));
		$this->file4 = $this->TestData->getFile(array(
			'image-png.png' => $this->TmpFolder->pwd() . 'filter/s/transfer/img/image-png-x.png'));
		$this->file5 = $this->TestData->getFile(array(
			'image-png.png' => $this->TmpFolder->pwd() . 'theme/blanko/img/image-blanko.png'));

		$settings = array(
			$this->TmpFolder->pwd() . 'static' . DS => 'media/static/',
			$this->TmpFolder->pwd() . 'filter' . DS => 'media/filter/',
			$this->TmpFolder->pwd() . 'transfer' . DS => false,
			$this->TmpFolder->pwd() . 'theme' . DS  => 'media/theme/'
		);
		$this->View = new View(null);
		$this->Media = new MediaHelper($this->View, $settings);
	}

	public function tearDown() {
		parent::tearDown();

		Configure::write('Media', $this->_config);
		$this->TestData->flushFiles();
		$this->TmpFolder->delete();
		ClassRegistry::flush();
	}

	public function testConstruct() {
		$settings = array(
			$this->TmpFolder->pwd() . 'static' . DS => 'media/static/',
			$this->TmpFolder->pwd() . 'theme' . DS  => 'media/theme/'
		);
		Configure::write('Media.filter', array(
			'image'	 => array('s' => array(), 'm' => array()),
			'video' => array('s' => array(), 'xl' => array())
		));
		$Helper = new MockMediaHelper($this->View, $settings);
	}

	public function testUrl() {
		$result = $this->Media->url('img/image-png');
		$this->assertEqual($result, '/media/static/img/image-png.png');

		$result = $this->Media->url('s/static/img/image-png');
		$this->assertEqual($result, '/media/filter/s/static/img/image-png.png');

		$result = $this->Media->url('img/image-png-x');
		$this->assertNull($result);

		$result = $this->Media->url('img/image-png-xyz');
		$this->assertNull($result);

		$result = $this->Media->url('s/transfer/img/image-png-x');
		$this->assertEqual($result, '/media/filter/s/transfer/img/image-png-x.png');

		$result = $this->Media->url($this->TmpFolder->pwd() . 'filter/s/transfer/img/image-png-x.png');
		$this->assertEqual($result, '/media/filter/s/transfer/img/image-png-x.png');
	}

	public function testWebroot() {
		$result = $this->Media->webroot('img/image-png');
		$this->assertEqual($result, '/media/static/img/image-png.png');

		$result = $this->Media->webroot('s/static/img/image-png');
		$this->assertEqual($result, '/media/filter/s/static/img/image-png.png');

		$result = $this->Media->webroot('img/image-png-x');
		$this->assertNull($result);

		$result = $this->Media->webroot('img/image-png-xyz');
		$this->assertNull($result);

		$result = $this->Media->webroot('s/transfer/img/image-png-x');
		$this->assertEqual($result, '/media/filter/s/transfer/img/image-png-x.png');

		$result = $this->Media->webroot($this->TmpFolder->pwd() . 'filter/s/transfer/img/image-png-x.png');
		$this->assertEqual($result, '/media/filter/s/transfer/img/image-png-x.png');
	}

	public function testFile() {
		$result = $this->Media->file('static/img/not-existant.jpg');
		$this->assertFalse($result);

		$result = $this->Media->file('img/image-png');
		$this->assertEqual($result, $this->file0);

		$result = $this->Media->file('s/static/img/image-png');
		$this->assertEqual($result, $this->file1);

		$result = $this->Media->file('s/static/img/dot.ted.name');
		$this->assertEqual($result, $this->file2);

		$result = $this->Media->file('img/image-png-x');
		$this->assertEqual($result, $this->file3);

		$result = $this->Media->file('s/transfer/img/image-png-x');
		$this->assertEqual($result, $this->file4);

		$result = $this->Media->file($this->TmpFolder->pwd() . 'filter/s/transfer/img/image-png-x.png');
		$this->assertEqual($result, $this->file4);

		$result = $this->Media->file('blanko/img/image-blanko');
		$this->assertEqual($result, $this->file5);
	}

	public function testName() {
		$this->assertEqual($this->Media->name('img/image-png.png'), 'image');
		$this->assertNull($this->Media->name('static/img/not-existant.jpg'));
	}

	public function testMimeType() {
		$this->assertEqual($this->Media->mimeType('img/image-png.png'), 'image/png');
		$this->assertNull($this->Media->mimeType('static/img/not-existant.jpg'));
	}

	public function testSize() {
		$this->assertEqual($this->Media->size('img/image-png.png'), 10142);
		$this->assertNull($this->Media->size('static/img/not-existant.jpg'));
	}
}
