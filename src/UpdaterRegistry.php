<?php

namespace Updater;

/**
 * Helper to get Updater instances
 * @author Carsten
 *
 */
class UpdaterRegistry{
	
	/**
	 * Get all available and active updaters.
	 * @return \Updater\PHPUpdater[]
	 */
	public static function getUpdaters(){
		return [new PHPUpdater()];
	}
	
}

?>