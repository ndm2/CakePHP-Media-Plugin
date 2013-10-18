<?php
/**
 * Actor Fixture File
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
 * @package       Media.Test.Fixture
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Actor Fixture Class
 *
 * @package       Media.Test.Fixture
 */
class ActorFixture extends CakeTestFixture {

	public $name = 'Actor';

	public $fields = array(
		'id'       => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary', 'extra' => 'auto_increment', 'length' => 10),
		'movie_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'name'     => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
		'indexes'  => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

	public $records = array(
		array(
			'id'       => 1,
			'movie_id' => 1,
			'name'     => 'Michael Sheen'
		),
		array(
			'id'       => 2,
			'movie_id' => 1,
			'name'     => 'Frank Langella'
		),
		array(
			'id'       => 3,
			'movie_id' => 2,
			'name'     => 'Nassim Amrabt'
		),
	);

}
