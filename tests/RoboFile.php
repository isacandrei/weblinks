<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * Download robo.phar from http://robo.li/robo.phar and type in the root of the repo: $ php robo.phar
 * Or do: $ composer update, and afterwards you will be able to execute robo like $ php vendor/bin/robo
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @see        http://robo.li/
 *
 * @since      3.7.0
 */

require_once 'vendor/autoload.php';

if (!defined('JPATH_TESTING_BASE'))
{
	// Base path for tests
	define('JPATH_TESTING_BASE', __DIR__);
}

if (!defined('JPATH_BASE'))
{
	// Base path for Jorobo tasks
	define('JPATH_BASE', __DIR__ . '/..');
}

use Joomla\Testing\Robo\RoboFile\RoboFileBase;

/**
 * Base Robo File for extension testing
 *
 * @package     Weblinks
 * @subpackage  Testing
 *
 * @since       3.7.0
 */
class RoboFile extends RoboFileBase
{
	// Load tasks from composer, see composer.json
	use Joomla\Jorobo\Tasks\loadTasks;

	/**
	 * Function for actual execution of the test suites of this extension
	 *
	 * @param   array  $opts  Array of configuration options:
	 *                        - 'env': set a specific environment to get configuration from
	 *                        - 'debug': executes codeception tasks with extended debug
	 *
	 * @return void
	 *
	 * @since   3.7.0
	 */
	public function runTestSuites(
		$opts = [
		'env' => 'desktop',
		'debug' => false
		])
	{
		$this->runCodeceptionSuite(
			'acceptance',
			'install',
			$opts['debug'],
			$opts['env']
		);
		$this->runCodeceptionSuite(
			'acceptance',
			'administrator',
			$opts['debug'],
			$opts['env']
		);
		$this->runCodeceptionSuite(
			'acceptance',
			'frontend',
			$opts['debug'],
			$opts['env']
		);
	}

	/**
	 * Executes the extension packager for this extension
	 *
	 * @param   array  $params  Additional parameters
	 *
	 * @return  void
	 *
	 * @since   3.7.0
	 */
	public function prepareTestingPackage($params = ['dev' => false])
	{
		// Copy current package
		if (!file_exists('../dist/pkg-weblinks-current.zip'))
		{
			if (!file_exists('../jorobo.ini'))
			{
				$this->_copy('../jorobo.dist.ini', '../jorobo.ini');
			}

			$this->taskBuild($params)
				->run();
		}

		$tmpFolder = JPATH_TESTING_BASE . '/_data';
		$this->_copy('../dist/pkg-weblinks-current.zip', $tmpFolder . "/pkg-weblinks-current.zip");
		$this->say('Extension package released in ' . $tmpFolder);
	}
}
