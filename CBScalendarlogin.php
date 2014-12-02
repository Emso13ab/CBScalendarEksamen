<?php 

include "encryption.php";

/* Get the port for the WWW service. */
//if($_POST){
	$username = $_POST['uname'];
	$password = $_POST['passw'];

	$userCredentials['overallID'] = "logIn";
	$userCredentials['email'] 	  = "Test";
	$userCredentials['password']  = "123";
	$userCredentials['isAdmin']   = "false";


	$service_port = 1314;

	/* Get the IP address for the target host. */
	$address = gethostbyname('localhost');

	/* Create a TCP/IP socket. */
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); 

	$result = socket_connect($socket, $address, $service_port);

	$json = json_encode($userCredentials);

	$in = encrypt_str($json);
	$out = '';

	socket_write($socket, $in, strlen($in));

	socket_set_option($socket,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>2, "usec"=>0));

	$reply = '';
	while(true) {
	    $chunk = @socket_read($socket, 10000);

	    if (strlen($chunk) == 0) {
	        // no more data
	        break;
	    }
	    $reply .= $chunk;
	}

	echo $reply;

	socket_close($socket);
//}

?>