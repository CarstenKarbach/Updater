<?php

use Updater\UpdaterRegistry;

require_once __DIR__.'/../vendor/autoload.php';

$updaters = UpdaterRegistry::getUpdaters();

echo "Checking for updates ...\n\n";

foreach($updaters as $updater){
	$updateDescription = $updater->getUpdates();
	echo get_class($updater).":\n";
	if(empty($updateDescription)){
		echo "\tup-to-date\n";
	}
	else{
		echo "\t".$updateDescription."\n";
	}
	echo "\n";
}


?>