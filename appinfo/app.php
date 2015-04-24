<?php

//require_once 'mycore_version/versions.php';
OC::$CLASSPATH['OCA\MyCoRe_Version\Storage'] = 'mycore_version/lib/versions.php';
OC::$CLASSPATH['OCA\MyCoRe_Version\Hooks'] = 'mycore_version/lib/hooks.php';
OC::$CLASSPATH['OCA\MyCoRe_Version\Capabilities'] = 'mycore_version/lib/capabilities.php';

OCP\Util::addscript('mycore_version', 'versions');
OCP\Util::addStyle('mycore_version', 'versions');

\OCA\MyCoRe_Version\Hooks::connectHooks();
