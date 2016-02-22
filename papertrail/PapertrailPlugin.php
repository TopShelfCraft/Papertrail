<?php
namespace Craft;

/*

With thanks to...
Brad Bell re: Plugin config (http://craftcms.stackexchange.com/a/2000/689)
Troy Davis re: Send UDP remote syslog message from PHP (https://gist.github.com/troy/2220679)

*/

/**
 * Papertrail: PapertrailPlugin
 *
 * @author    Michael Rog <michael@michaelrog.com>
 * @copyright Copyright (c) 2016, Michael Rog
 * @see       http://topshelfcraft.com
 * @package   craft.plugins.papertrail
 * @since     1.0
 */
class PapertrailPlugin extends BasePlugin
{


	public function getName()
	{
		return 'Papertrail';
	}


	/**
	 * Return the plugin developer's name
	 *
	 * @return string
	 */
	public function getDeveloper()
	{
		return 'Top Shelf Craft (Michael Rog)';
	}


	/**
	 * Return the plugin developer's URL
	 *
	 * @return string
	 */
	public function getDeveloperUrl()
	{
		return 'http://topshelfcraft.com';
	}


	/**
	 * Return the plugin's Documentation URL
	 *
	 * @return string
	 */
	public function getDocumentationUrl()
	{
		return 'https://github.com/TopShelfCraft/Papertrail';
	}


	/**
	 * Return the plugin's current version
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return '1.0.0';
	}


	/**
	 * Return the plugin's db schema version
	 *
	 * @return string|null
	 */
	public function getSchemaVersion()
	{
		return '1.0.0.0';
	}


	/**
	 * Return the plugin's db schema version
	 *
	 * @return string
	 */
	public function getReleaseFeedUrl()
	{
		return "https://github.com/TopShelfCraft/Papertrail/raw/master/releases.json";
	}


	/**
	 * @return bool
	 */
	public function hasCpSection()
	{
		return false;
	}


	/**
	 * Make sure requirements are met before installation.
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function onBeforeInstall()
	{
		return true;
	}


	/**
	 * @param string $msg
	 * @param string $level
	 * @param bool $force
	 *
	 * @return mixed
	 */
	public static function log($msg, $level = LogLevel::Profile, $force = false)
	{

		if (is_string($msg))
		{
			$msg = "\n" . $msg . "\n\n";
		}
		else
		{
			$msg = "\n" . print_r($msg, true) . "\n\n";
		}

		parent::log($msg, $level, $force);

	}


}