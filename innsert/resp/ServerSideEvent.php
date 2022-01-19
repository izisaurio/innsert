<?php

namespace innsert\resp;

use innsert\lib\Request;

/**
 * Innsert PHP MVC Framework
 *
 * Server side request, needs to be extended with loop function
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
abstract class ServerSideEvent
{
	/**
	 * Loop repetition this value seconds
	 *
	 * @access	protected
	 * @var		int
	 */
	protected $time = 30;

	/**
	 * Sends messahe
	 *
	 * @access	protected
	 * @param	mixed	$message	Message, array is encoded to json
	 */
	protected function send($message)
	{
		if (is_array($message)) {
			$message = json_encode($message);
		}
		echo "event: message\ndata: $message\n\n";
		ob_flush();
		flush();
	}

	/**
	 * Sets headers and executes main loop
	 *
	 * @access	public
	 */
	public function run()
	{
		if (session_status() == PHP_SESSION_ACTIVE) {
			session_write_close();
		}
		ignore_user_abort(true);
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		header('Access-Control-Allow-Origin: *');
		$request = Request::defaultInstance();
		$event = $request->server('HTTP_LAST_EVENT_ID', 0);
		if ($event == 0) {
			$event = $request->get('lastEventId', 0);
		}
		$loop = true;
		while ($loop) {
			if (connection_aborted()) {
				exit;
			}
			$loop = $this->loop($event);
			sleep($this->time);
		}
		exit;
	}

	/**
	 * Loop abstracto
	 *
	 * @access	protected
	 * @param	int		$event	Event id (0 when new)
	 * @return	bool
	 */
	abstract protected function loop();
}