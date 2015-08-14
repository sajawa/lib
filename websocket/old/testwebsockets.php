#!/usr/bin/php
<?php
require_once('./websockets.php');

class echoServer extends WebSocketServer {
	//protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
	/*
	protected function process ($user, $message) {
	          $this->send($user,$message);
	} */
	protected function process ($users, $message) {
	          error_log("[echoServer.process]".$message);
			  foreach($users as $user) {
			        $this->send($user,$message);
			  }
	}
	protected function connected ($user) {
	         
	     error_log("[echoServer.connect] connected ..".($user->id));
		// Do nothing: This is just an echo server, there's no need to track the user.
		// However, if we did care about the users, we would probably have a cookie to
		// parse at this step, would be looking them up in permanent storage, etc.
	}
	protected function closed ($user) {
		error_log("[echoServer.closed] closed ..".($user->id));
		// Do nothing: This is where cleanup would go, in case the user had any sort of
		// open files or other objects associated with them. This runs after the socket
		// has been closed, so there is no need to clean up the socket itself here.
	}
}

$echo = new echoServer("0.0.0.0","33333");
try {
	$echo->run();
}
catch (Exception $e) {
	$echo->stdout($e->getMessage());
}

?>