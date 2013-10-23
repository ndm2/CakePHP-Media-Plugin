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

App::uses('ClassRegistry', 'Utility');
App::uses('View', 'View');
App::uses('MediaHelper', 'Media.View/Helper');

require_once dirname(dirname(dirname(__FILE__))) . DS . 'constants.php';
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DS . 'Config' . DS . 'bootstrap.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . DS . 'Fixture' . DS . 'TestData.php';

/**
 * Mock Media Helper
 *
 * @package       Media.Test.Case.View.Helper
 */
class MockMediaHelper extends MediaHelper {

	public function paths() {
		return $this->_paths;
	}

}

/**
 * Media Helper Test Case Class
 *
 * @package       Media.Test.Case.View.Helper
 */
class MediaHelperTest extends CakeTestCase {

/**
 * @var TestData
 */
	public $Data;

	public $file0;
	public $file1;
	public $file2;
	public $file3;
	public $file4;
	public $file5;

/**
 * Media helper instance
 *
 * @var MediaHelper
 */
	public $Media;

	public $View;

	public function setUp() {
		parent::setUp();

		$this->Data = new TestData();
		$this->Data->Folder->create($this->Data->settings['filter'] . 's' . DS . 'static' . DS . 'img');
		$this->Data->Folder->create($this->Data->settings['filter'] . 's' . DS . 'transfer' . DS . 'img');
		$this->Data->Folder->create($this->Data->settings['base'] . 'theme' . DS . 'blanko' . DS . 'img' . DS);

		$this->file0 = $this->Data->getFile(array(
			'image-png.png' => $this->Data->settings['static'] . 'img/image-png.png'
		));
		$this->file1 = $this->Data->getFile(array(
			'image-png.png' => $this->Data->settings['filter'] . 's/static/img/image-png.png'
		));
		$this->file2 = $this->Data->getFile(array(
			'image-png.png' => $this->Data->settings['filter'] . 's/static/img/dot.ted.name.png'
		));
		$this->file3 = $this->Data->getFile(array(
			'image-png.png' => $this->Data->settings['transfer'] . 'img/image-png-x.png'
		));
		$this->file4 = $this->Data->getFile(array(
			'image-png.png' => $this->Data->settings['filter'] . 's/transfer/img/image-png-x.png'
		));
		$this->file5 = $this->Data->getFile(array(
			'image-png.png' => $this->Data->settings['base'] . 'theme/blanko/img/image-blanko.png'
		));

		$settings = array(
			$this->Data->settings['static'] => 'media/static/',
			$this->Data->settings['filter'] => 'media/filter/',
			$this->Data->settings['transfer'] => false,
			$this->Data->settings['base'] . 'theme' . DS  => 'media/theme/'
		);
		$this->View = new View(null);
		$this->Media = new MediaHelper($this->View, $settings);
		$this->Media->request = new CakeRequest(null, false);
		$this->Media->request->base = '';
		$this->Media->request->here = $this->Media->request->webroot = '/';
	}

	public function tearDown() {
		parent::tearDown();

		$this->Data->cleanUp();
		ClassRegistry::flush();
	}

	public function testConstructWithCustomPaths() {
		$settings = array(
			$this->Data->settings['static']               => 'media/static/',
			$this->Data->settings['base'] . 'theme' . DS  => 'media/theme/'
		);
		$MediaHelper = new MockMediaHelper($this->View, $settings);

		$result = $MediaHelper->paths();
		$expected = array(
			$this->Data->settings['static']               => MEDIA_STATIC_URL,
			MEDIA_TRANSFER                                => MEDIA_TRANSFER_URL,
			MEDIA_FILTER                                  => MEDIA_FILTER_URL,
			$this->Data->settings['base'] . 'theme' . DS  => 'media/theme/'
		);
		$this->assertEqual($result, $expected);
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

		$result = $this->Media->url($this->Data->settings['filter'] . 's/transfer/img/image-png-x.png');
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

		$result = $this->Media->webroot($this->Data->settings['filter'] . 's/transfer/img/image-png-x.png');
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

		$result = $this->Media->file($this->Data->settings['filter'] . 's/transfer/img/image-png-x.png');
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
