<?php

$installedVersion=OCP\Config::getAppValue('mycore_version', 'installed_version');
// move versions to new directory
if (version_compare($installedVersion, '1.0.4', '<')) {
	\OC_DB::dropTable("mycore_version");
}
