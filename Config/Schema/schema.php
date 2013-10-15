<?php
/**
 * Media Schema File
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
 * @package       Media.Config.Schema
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Media Schema Class
 *
 * @package       Media.Config.Schema
 */
class MediaSchema extends CakeSchema {

/**
 * before
 *
 * @param array $event
 * @return boolean
 */
    public function before($event = array()) {
		return true;
	}

/**
 * after
 *
 * @param array $event
 */
    public function after($event = array()) {
	}

/**
 * attachments
 *
 * @var array
 */
    public $attachments = array(
		'id'          => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary', 'extra' => 'auto_increment', 'length' => 10),
		'model'       => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'dirname'     => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 255),
		'basename'    => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
		'checksum'    => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
		'group'       => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 255),
		'alternative' => array('type' => 'string', 'null' => true, 'default' => NULL,'length' => 50),
		'created'     => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified'    => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes'     => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
}
?>
