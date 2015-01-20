<?php

namespace core;
use core\Config;
use core\Handler;

class Socket {
	private static $_instance; 

	public function run() {
		$config = Config::get('socket');
		$config = array(
			'worker_num' => 4,
			'task_worker_num' => 8,
			'max_request' => 10000,
			'dispatch_mode' => 2,
			'debug_mode'=> 0,
		);
		$serv = new \swoole_server("0.0.0.0", 9501);
		// $serv->addlistener('0.0.0.0', 9502);
		$serv->set($config);
		$serv->config = $config;
		$handler = new Handler();

		$serv->on('Start', array($handler,"start"));

		$serv->on('Connect', 	array($handler,"connect"));
		$serv->on('Receive', 	array($handler,"receive"));
		$serv->on('Close', 		array($handler,"close"));
		$serv->on('Shutdown', 	array($handler,"shutdown"));
		$serv->on('Timer', 		array($handler,"timer"));
		$serv->on('WorkerStart',array($handler,"workStart"));
		$serv->on('WorkerStop', array($handler,"workStop"));
		$serv->on('Task', 		array($handler,"task"));
		$serv->on('Finish', 	array($handler,"finish"));
		$serv->on('WorkerError',array($handler,"workError"));

		$serv->start();
	}
}