<?php

define('NEWLINE', "\n");

/** Define class to export as webservice. */
define('SERVICE_CLASS', Node);

class Node {
	/**
	 * Get the hostname of this node.
	 * @return string Hostname of this node.
	 */
	function getHostname() {
		return trim(`hostname`);
	}

	/**
	 * Get the uptime of this node.
	 * @return float Node uptime in seconds.
	 */
	function getUptime()
	{
		return (float) file_get_contents('/proc/uptime');
	}

	/**
	 * Get the load of this node.
	 * @return float Node load.
	 */
	function getLoad()
	{
		return (float) file_get_contents('/proc/loadavg');
	}

	/**
	 * Get status of memory and swap.
	 * @return array Status of memory and swap.
	 */
	function getMemory()
	{
		$return = array(
			'memory' => array(
				'total'   => 0,
				'used'    => 0,
				'free'    => 0,
				'shared'  => 0,
				'buffers' => 0,
				'cached'  => 0
			),
			'swap' => array(
				'total'   => 0,
				'used'    => 0,
				'free'    => 0
			)
		);

		$rows = explode(NEWLINE, `free`);
		foreach ($rows as $row)
		{
			$parts = preg_split('/\s+/', trim($row));

			if ($parts[0] == 'Mem:')
			{
				$return['memory']['total']   = (int) $parts[1];
				$return['memory']['used']    = (int) $parts[2];
				$return['memory']['free']    = (int) $parts[3];
				$return['memory']['shared']  = (int) $parts[4];
				$return['memory']['buffers'] = (int) $parts[5];
				$return['memory']['cached']  = (int) $parts[6];
			}
			else if ($parts[0] == 'Swap:')
			{
				$return['swap']['total']     = (int) $parts[1];
				$return['swap']['used']      = (int) $parts[2];
				$return['swap']['free']      = (int) $parts[3];
			}
		}

		return $return;
	}

	/**
	 * Get overview of all mounted filesystems.
	 * @return array Filesystem mount details.
	 */
	function getMounts()
	{
		$return = array();

		$rows = explode(NEWLINE, `df`);
		foreach ($rows as $row)
		{
			if (!strlen(trim($row))) continue;

			$parts = preg_split('/\s+/', trim($row));
			
			if ($parts[0] !== 'Filesystem')
			{
				$fs = array(
					"filesystem" => $parts[0],
					"total"       => $parts[1],
					"used"       => $parts[2],
					"free"       => $parts[3],
					"mountpoint" => $parts[5]
				);

				$return[] = $fs;
			}
		}

		return $return;
	}
}

require 'libthomsoft-webservice.php';
