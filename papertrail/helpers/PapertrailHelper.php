<?php
namespace Craft;

/**
 * Papertrail: PapertrailHelper
 *
 * @author    Michael Rog <michael@michaelrog.com>
 * @copyright Copyright (c) 2016, Michael Rog
 * @see       http://topshelfcraft.com
 * @package   craft.plugins.papertrail
 * @since     1.0
 */
class PapertrailHelper
{


	private static $_severityCodes = array(
		'emergency' => 0,
		'alert' => 1,
		'critical' => 2,
		'error' => 3, // LogLevel::Error
		'warning' => 4, // LogLevel::Warning
		'notice' => 5,
		'informational' => 6,
		'info' => 6, // LogLevel::Info
		'debug' => 7,
		'trace' => 7, // LogLevel::Trace
		'profile' => 7, // LogLevel::Profile
	);


	/**
	 * @param string $msg
	 * @param string $level
	 * @param string $component
	 * @param string $system
	 * @param int $facility
	 *
	 * @return bool|int The number of bytes sent, or FALSE if there was an error.
	 */
	public static function log($msg = '', $level = LogLevel::Info, $component = null, $system = null, $facility = 23)
	{

		$component = !empty($component) ? $component : craft()->getSiteName();
		$system = !empty($system) ? $system : CRAFT_ENVIRONMENT;
		$severity = isset(self::$_severityCodes[$level]) ? self::$_severityCodes[$level] : self::$_severityCodes['informational'];

		if (!is_string($msg))
		{
			$msg = print_r($msg, true);
		}

		if (craft()->config->get('prependLevel', 'papertrail') === true)
		{
			$msg = "[{$level}] " . $msg;
		}

		if (craft()->config->get('appendLevel', 'papertrail') === true)
		{
			$msg .= " [{$level}]";
		}

		return self::send_remote_syslog($msg, $component, $system, $facility, $severity);

	}


	/**
	 * @param string $msg
	 * @param string $component
	 * @param string $system
	 * @param int $facility
	 * @param int $severity
	 *
	 * @see https://tools.ietf.org/html/rfc3164#section-4.1.1
	 *
	 * @return bool|int
	 */
	public static function send_remote_syslog($msg = '', $component = 'papertrail', $system = 'craft', $facility = 23, $severity = 6)
	{

		// Fetch and validate host/port config.

		$host = craft()->config->get('papertrailHost', 'papertrail');
		$port = craft()->config->get('papertrailPort', 'papertrail');

		if ( empty($host) || !is_string($host) || empty($port) || !is_int($port) ){
			PapertrailPlugin::log("Papertrail aborted before sending the remote syslog: The host/port config is incomplete.", LogLevel::Error);
			return false;
		}

		// Calculate the Priority value
		// (see https://tools.ietf.org/html/rfc3164#section-4.1.1)

		$pri = (intval($facility) * 8) + intval($severity);

		// Try to send the message

		$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

		$syslog_message = "<{$pri}>" . date('M d H:i:s ') . $system . ' ' . $component . ': ' . $msg;

		$sent = socket_sendto($sock, $syslog_message, strlen($syslog_message), 0, $host, $port);

		socket_close($sock);

		// Return the result

		if ($sent === false)
		{
			PapertrailPlugin::log("Papertrail encountered an error when sending the remote syslog; nothing was sent.", LogLevel::Error);
		}

		return $sent;

	}


}