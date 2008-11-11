 <?php
 /**
 * Js Min Medium Adapter File
 *
 * Copyright (c) 2007-2008 David Persson
 *
 * Distributed under the terms of the MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP version 5
 * CakePHP version 1.2
 *
 * @package    media
 * @subpackage media.libs.medium.adapter
 * @author     David Persson <davidpersson@qeweurope.org>
 * @copyright  2007-2008 David Persson <davidpersson@qeweurope.org>
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link       http://github.com/davidpersson/media
 */
/**
 * Js Min Medium Adapter Class
 *
 * @package    media
 * @subpackage media.libs.medium.adapter
 * @link       http://code.google.com/p/jsmin-php/
 */
class JsMinMediumAdapter extends MediumAdapter {

	var $require = array(
							'mimeTypes' => array('application/javascript'),
							'imports' => array(array('type' => 'Vendor', 'name'=> 'JSMin', 'file' => 'jsmin.php')),
							);

	function initialize(&$Medium) {
		if (isset($Medium->contents['raw'])) {
			return true;
		}

		if (!isset($Medium->file)) {
			return false;
		}

		return $Medium->contents['raw'] = file_get_contents($Medium->file);
	}

	function store(&$Medium, $file) {
		return file_put_contents($Medium->contents['raw'], $file);
	}

	function compress(&$Medium) {
		return $Medium->contents['raw'] = trim(JSMin::minify($Medium->contents['raw']));
	}
}
?>