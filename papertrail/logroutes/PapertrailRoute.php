<?php

namespace Craft;

 /**
 * Papertrail: PapertrailRoute
 *
 * @author    Tom Davies <tom@madebykind.com>
 * @copyright Copyright (c) 2016, Michael Rog, Tom Davies
 * @see       http://topshelfcraft.com
 * @package   craft.plugins.papertrail
 * @since     1.1
 */
class PapertrailRoute extends \CLogRoute
{

	/**
	 * @param array $logs
	 */
	public function processLogs($logs)
	{

		foreach ($logs as $log) {

			$msg 	= $log[0];
			$level 	= isset($log[1]) ? $log[1] : '';
			$type   = isset($log[2]) ? $log[2] : '';
			$name   = isset($log[5]) ? $log[5] : '';

			PapertrailHelper::log("[$type.$name] $msg", $level);
		}
	}
}
