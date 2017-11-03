<?php


/**
 * Papertrail: PapertrailService
 *
 * @author    Michael Rog <michael@michaelrog.com>
 * @copyright Copyright (c) 2016, Michael Rog
 * @see       http://topshelfcraft.com
 * @package   craft.plugins.papertrail
 * @since     1.0
 */


return array(

	'papertrailHost' => false, // e.g. logN.papertrailapp.com
	'papertrailPort' => false, // e.g. 123456
	'appendLevel' => false,
	'prependLevel' => true,
	'enableLogRoute' => false, // enable automatic logging of Craft errors to papertrail, not just template errors
	'maxSeverity' => 7, // Don't log errors with a severity higher than this

);
