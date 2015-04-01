<?php

/* - - - - - - - - - - - - - - - - - - - - -

 Title : PHP Quick Profiler MySQL Class
 Author : Created by Ryan Campbell
 URL : http://particletree.com/features/php-quick-profiler/

 Last Updated : April 22, 2009

 Description : A simple database wrapper that includes
 logging of queries.

- - - - - - - - - - - - - - - - - - - - - */

class DatabaseQuery {

	public $queryCount = 0;
	public $queries = array();



	/*-----------------------------------
	          	DEBUGGING
	------------------------------------*/

	function logQuery($sql, $params, $start) {
		$this->queryCount++;
		$query = array(
				'sql' => $sql,
				'params' => $params,
				'time' => ($this->getTime() - $start)*1000
			);
		array_push($this->queries, $query);
	}

	function getTime() {
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$start = $time;
		return $start;
	}

	public function getReadableTime($time) {
		$ret = $time;
		$formatter = 0;
		$formats = array('ms', 's', 'm');
		if($time >= 1000 && $time < 60000) {
			$formatter = 1;
			$ret = ($time / 1000);
		}
		if($time >= 60000) {
			$formatter = 2;
			$ret = ($time / 1000) / 60;
		}
		$ret = number_format($ret,3,'.','') . ' ' . $formats[$formatter];
		return $ret;
	}


}
