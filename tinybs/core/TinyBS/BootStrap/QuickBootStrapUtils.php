<?php

namespace TinyBS\BootStrap;

class QuickBootStrapUtils {
	const PERSISTENCE_FILE = 'bootStrap.php';
	const PERSISTENCE_TEMPLATE = '<?php return unserialize(\'%s\');';
	
	static public function persistent(BootStrap $youngCore){
		$pSerialize = serialize($youngCore);
		$pStore = TINYBSROOT.DS.'tinybs'.DS.'caches'.DS.self::PERSISTENCE_FILE;
		$pHandle = fopen($pStore, "w");
		$pString = sprintf(self::PERSISTENCE_TEMPLATE, $pSerialize);
		fwrite($pHandle, $pString);
		fclose($pHandle);
	}
	
	static public function restore(){
		$pStore = TINYBSROOT.DS.'tinybs'.DS.'caches'.DS.self::PERSISTENCE_FILE;
		if(($pFile=stream_resolve_include_path($pStore))===false)
			return null;
		return require $pStore;
	}
}