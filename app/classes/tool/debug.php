<?php

namespace tool;
class Debug extends \FrontBase {
	public static function log($data) {
		if (! empty ( $data )) {
			$mLog = new \model\Debuglog ();
			$mLog->save ( $data );
		}
	}
}
?>