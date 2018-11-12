<?PHP
$server = '192.168.1.7';	//change PS4 IP Adress here
$port = 987;

if(!($sock = socket_create(AF_INET, SOCK_DGRAM, 0)))
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);

    die("Couldn't create socket: [$errorcode] $errormsg \n");
}

socket_set_option($sock, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));
socket_set_option($sock, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));

//echo "Socket created \n";

//Communication loop
//while(1)
//{
    //Take some input to send
    //echo 'Enter a message to send : ';
    //$input = fgets(STDIN);

    $input = "SRCH * HTTP/1.1
device - discovery - protocol - version:00020020
";

    //Send the message to the server
    if( ! socket_sendto($sock, $input , strlen($input) , 0 , $server , $port))
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);

        die("Could not send data: [$errorcode] $errormsg \n");
    }

    //Now receive reply from server and print it
    if(socket_recv ( $sock , $reply , 2045 , MSG_WAITALL ) === FALSE)
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);

        if($errorcode == 11)
                echo "<b><font color=\"red\">PS4 Off, WOL not available</font></b>";
        else
                die("Could not receive data: [$errorcode] $errormsg \n");
    }

//    echo "Reply : $reply";

      $status = substr($reply, 9,3);
//      echo $status;
      if($status == 200)
        echo "<b><font color=\"green\">PS4 On</font></b>";
      else if($status == 620)
        echo "<b>PS4 Standby</b>";

//}
?>