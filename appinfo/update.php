<?php

$installedVersion=OCP\Config::getAppValue('mycore_versions', 'installed_version');
// move versions to new directory
if (version_compare($installedVersion, '1.0.4', '<')) {
	\OC_DB::dropTable("mycore_versions");
}
