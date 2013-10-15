<?php
/**
 * Model Test Models
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
 * @package       Media.Test.Case.Model
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class Movie extends CakeTestModel {
	var $name = 'Movie';
	var $useTable = 'movies';
	var $hasMany = array('Actor');
}

class Actor extends CakeTestModel {
	var $name = 'Actor';
	var $useTable = 'actors';
	var $belongsTo = array('Movie');
}

class Unicorn extends CakeTestModel {
	var $name = 'Unicorn';
	var $useTable = false;
	var $makeVersionArgs = array();
	var $returnMakeVersion = true;

	function makeVersion() {
		$this->makeVersionArgs[] = func_get_args();
		return $this->returnMakeVersion;
	}
}

class Pirate extends CakeTestModel {
	var $name = 'Pirate';
	var $useTable = 'pirates';
}
?>