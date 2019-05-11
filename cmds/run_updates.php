<?php

use Updater\UpdaterRegistry;

require_once __DIR__.'/../vendor/autoload.php';

$updaters = UpdaterRegistry::getUpdaters();

$separator = "**************************************************";

foreach($updaters as $updater){
	$updateDescription = $updater->getUpdates();
	
	echo get_class($updater).":\n";
	
	if(empty($updateDescription)){
		echo "\t"."up-to-date\n";
	}
	else{
		echo "\t"."Updating: ".$updateDescription."\n\n";
		list($success, $report) = $updater->runUpdate();
		$description = "Successfully updated";
		if(! $success){
			$description = "Error during Update";
		}
		
		echo "\t".$description.", Report:\n".$separator."\n".$report."\n".$separator."\n\n";
	}
}


?>