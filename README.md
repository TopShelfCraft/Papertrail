# Papertrail
A CraftCMS service for publishing Papertrail logs from templates and plugins

by Michael Rog  
[http://topshelfcraft.com](http://topshelfcraft.com)



### TL;DR.

[Papertrail](https://papertrailapp.com/) is a useful service that allows you to aggregate, tail, search, and respond to your server logs via a convenient web app.

In addition to helping you monitor your system logs &mdash; e.g. PHP, Apache, Nginx, MySQL... you know, the `stdout` stuff &mdash; Papertrail also accepts custom log events, which you can submit via your own custom components.

This plugin hooks CraftCMS up to Papertrail so that you can easily publish log items to your live Papertrail feed.

   
* * *


## Installing & configuring Papertrail

Create a free account at [papertrailapp.com](https://papertrailapp.com/).

Papertrail will provide you with a Host URL and Port number that is specific to your account. Add those values to a `papertrail.php` file in your `craft/config` directory:

```
<?php

/**
 * Papertrail Configuration
 */

return array(

	// Default
	'*' => array(
		'papertrailHost' => 'logsX.papertrailapp.com',
		'papertrailPort' => 00000,
		'appendLevel' => false,
		'prependLevel' => true,
	),

);
```

The plugin's configuration supports [Multi-Environment Configs](https://craftcms.com/docs/multi-environment-configs) just like Craft's own _general_ and _database_ config files.

In addition to supplying the [required] Host URL and port number, you can also set the `appendLevel` and `prependLevel` properties to control whether the severity level (e.g. "info" or "warning") should be appended and/or prepended to the log event text. _(Even if you choose not to include the severity level in the message, you can still search and filter log events by severity level in the Papertrail app.)_


## Publishing log entries

With the plugin installed and configured, you can invoke Papertrail's `log` method from your template or component, to send a log event to the Papertrail app.

The `log` method looks for four parameters:

##### 1) Message (required)

The text of the log message.

##### 2) Severity level

A [description](https://en.wikipedia.org/wiki/Syslog#Severity_level) of how critical the message is. The values are based on the [Syslog Protocol severity codes](https://tools.ietf.org/html/rfc5424#section-6.2.1), but also include the severity names that [Craft defines internally](https://github.com/pixelandtonic/Craft-Release/blob/master/app/enums/LogLevel.php)

Possible values for the severity level (and their corresponding numeric codes) are:

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
	
Default: `info`
	
##### 3) Component

An arbitrary name for the component. This usually names the Plugin, service, or template from which a log entry originates, but you choose to organize logs however you want.

Default: `papertrail`.

##### 4) System

An arbitrary name for the system. This usually names the site or project from which a log entry originates, but you choose to organize logs however you want.

Default: `craft`.

#####


## Using Papertrail for template debugging

Perhaps you find yourself frequently using the `{{ dump() }}` function in your templates. Wouldn't it be nice if you could see that info in realtime _without_ gunking up your web page with a block of inline debugging info?

It's easy &mdash; Instead of using `dump()` to print your debugging info to the screen, use the logging variable to display it in your live log feed instead:

```
{% do craft.papertrail.log(message, severityLevel, component, system) %}
```

For example: 

```
{% do craft.papertrail.log('O noes! An error occurred!', 'warning', 'checkoutTemplate', 'MyCommerceSite') %}
```

If you provide a non-string variable in place of the message, the system will automatically convert the variable to a debugging representation using `print_r`:

```
{% do craft.papertrail.log(
	{ 'currentUser': currentUser, 'entry': entry },
	'debug',
	'checkoutTemplate',
	'MyCommerceSite')
%}
```

## Using Papertrail for plugin development

Bouncing back and forth between Craft's logs, plugin logs, and PHP error logs &mdash; a common activity during plugin development &mdash; can be annoying and time-consuming. Wouldn't it be nice if all those logs were aggregated in one window, immediately searchable, and displayed in real-time?

It's easy &mdash; Hook your Craft component into Papertrail using the Service or Helper class:

```
craft()->papertrail->log($msg, $level, $component, $system);
```

or...

```
PapertrailHelper::log($msg, $level, $component, $system);
```

For example, you can override your plugin's `log()` method to use Papertrail instead of (or in addition to) Craft's native text file debugging.

```
/**
 * @param string $msg
 * @param string $level
 * @param bool $force
 *
 * @return mixed
 */
public static function log($msg, $level = LogLevel::Profile, $force = false)
{

	PapertrailHelper::log(
		$msg,
		$level,
		this::class,
		craft()->getSiteName()
	);
	// parent::log($msg, $level, $force);

}
```

If you provide a non-string variable in place of the message, the system will automatically convert the variable to a debugging representation using `print_r`.


### What are the plugin system requirements?

Craft 2.5+ and PHP 5.4+


### I found a bug.

Are you sure?


### Yeah... I did.  :-/

Oh. Well, okay. Please open a GitHub Issue, submit a PR to the `dev` branch, or just email me to let me know.




* * *

#### Contributors:  
  
  - Plugin development: [Michael Rog](http://michaelrog.com) / @michaelrog