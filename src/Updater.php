<?php 

namespace Updater;

/**
 * One class per software package
 * @author Carsten
 *
 */
abstract class Updater{

	/**
	 * @return boolean true if updates can be installed, false if everything is up to date
	 */
	public function isUpdateAvailable(){
		return empty($this->getUpdates());
	}
	
	/**
	 * @return string description of needed updates
	 */
	public abstract function getUpdates();
	
	/**
	 * Install updates
	 * 
	 * @return array [$success, $report]
	 */
	public abstract function runUpdate();

}

?>