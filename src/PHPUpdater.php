<?php 

namespace Updater;

class PHPUpdater extends Updater{
	
	/**
	 * Read the latest available PHP version from downloads page
	 * @return boolean|string false on error, otherwise the version as string
	 */
	public function getLatestPHPVersionToDownload(){
		$doc = new \DOMDocument();
		$success = @$doc->loadHTMLFile("https://www.php.net/downloads.php");
		
		if(! $success){
			return false;
		}
		
		$xpath = new \DOMXPath($doc);
		
		$elements = $xpath->query("//h3");
		if($elements === false || empty($elements)){
			return false;
		}
		
		/**
		 * @var \DOMElement $element
		 */
		foreach ($elements as $i => $element) {
			$foundVersion = $element->attributes->getNamedItem('id')->nodeValue;
			if(preg_match("/^v(\d+\.\d+\.\d+)$/", $foundVersion, $matches)){
				return $matches[1];
			}
		}
	}
	
	/**
	 * @return string description of needed updates, empty string if no update is available
	 */
	public function getUpdates(){
		$currentVersion = phpversion();
		
		$latestVersion = $this->getLatestPHPVersionToDownload();
		
		if($latestVersion !== $currentVersion){
			return "PHP Update available ".$currentVersion." -> ".$latestVersion;
		}
		else{
			return "";
		}
	}
	
	/**
	 * Generate shell script to execute.
	 * @param string $downloadURL
	 * @return string
	 */
	public function getShellScriptToInstall($downloadURL){
		$script = "(";
		$script .= "wget ".$downloadURL.";";
		$script .= "make;";
		$script .= "make install;";
		$script .= ") 2>&1";
		return $script;
	}
	
	/**
	 * Install updates
	 * 
	 * @return array [$success, $report]
	 */
	public function runUpdate(){
		$version = $this->getLatestPHPVersionToDownload();
		if($version === false || empty($version)){
			return [false, "Invalid version given |".$version."|"];
		}
		
		$report = "Trying to install PHP version ".$version."\n";
		
		$downloadURL = "https://www.php.net/distributions/php-".$version.".tar.bz2";
		$report .= "Loading file from ".$downloadURL."\n";
		$data = @file_get_contents($downloadURL);
		
		if(empty($data)){
			$report .= "Could not download it. Aborting";
			return [false, $report];
		}
		$report .= "Download successful\n";
		
		$script = $this->getShellScriptToInstall($downloadURL);
		
		$report .= "Running shell script: ".$script."\n";
		
		$output = [];
		$returnVar = 0;
		$lastLine = exec($script, $output, $returnVar);

		$report .= "Output: (".$returnVar.")\n".join("\n", $output)."\n".$lastLine."\n\n";
		
		if($returnVar !== 0){
			$report .= "Shell script returned error code $returnVar . Something went wrong. Aborting ...";
			return [false, $report];
		}
		
		return [true, $report];
	}
	
}

?>