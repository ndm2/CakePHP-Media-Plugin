<?php
/**
 * Transfer Validation Test Case File
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
 * @package       Media.Test.Case.Lib
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

App::uses('TransferValidation', 'Media.Lib');
require_once dirname(dirname(dirname(__FILE__))) . DS . 'Fixture' . DS . 'test_data.php';

/**
 * Transfer Validation Test Case Class
 *
 * @package       Media.Test.Case.Lib
 */
class TransferValidationTest extends CakeTestCase {

	public function setUp() {
		$this->TestData = new TestData();
	}

	public function tearDown() {
		$this->TestData->flushFiles();
	}
}
