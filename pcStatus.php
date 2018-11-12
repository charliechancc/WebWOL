<?PHP
// Before using this function, Please enable Remote Desktop Function of your Desktop


$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));
socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));

if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
        try {
                $result = @socket_connect($socket, '192.168.1.10', 3389);	//change PC IP Adress here
                if ($result === false) {
                        echo "<b><font color=\"red\">Remote Desktop Offline</font></b>";
                } else {
                        echo "<b><font color=\"green\">Remote Desktop Ready</font></b>";
                        socket_close($socket);
                }
        } catch (ErrorException $ex) {

        }
}

?>
