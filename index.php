<!doctype html> <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>WOL</title>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="floating-labels.css" rel="stylesheet">
  </head>
  <body>

   <form class="form-signin" action="" method="post">
                <div class="form-group"> <?PHP error_reporting(E_ALL); if($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
if(isset($_POST['password'])) {
        if($_POST['target'] == 1)
        {		
				//Change PS4 wake up password here
                if($_POST['password'] == "12345678")
                {
                    setcookie("WOLTarget", "1", time() + (86400 * 365), "/wol"); // 86400 = 1 day

                        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
                        $address = "192.168.3.7";
                        if ($socket == false) {
                                echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
                        } else {
                                //echo "OK.\n";
                        }
                        $result = socket_connect($socket, $address, 987);
                        if ($result == false) {
                                echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
                        } else {
                                //echo "OK.\n";
                        }
						
						//Change "user-credential" to yours at the following part:
                        $in = "WAKEUP * HTTP/1.1
                        client-type:vr
                        auth-type:R
                        model:w
                        app-type:r
                        user-credential:1690000000
                        device-discovery-protocol-version:00020020
                        ";
                        $out = '';
                        echo "<div class=\"alert alert-success alert-dismissible\">
                          <strong>Success!</strong><br>Sent PS4 wakeup request...
                        </div>
                        ";
                        socket_write($socket, $in, strlen($in));
                        //echo "OK.\n";
                        //echo "Closing socket...";
                        socket_close($socket);
                        //echo "OK.\n\n";
                }
                else
                {
                        echo "<div class=\"alert alert-warning alert-dismissible fade show\">
                                <strong>Error!</strong><br>Password incorrect!
                                </div>
                        ";
                }
        }
        else if($_POST['target'] == 2)
        {
				//Change PC wake up password here
                if($_POST['password'] == "12345678")	
                {
                    setcookie("WOLTarget", "2", time() + (86400 * 365), "/wol"); // 86400 = 1 day
                        /////////////////////////////////
                        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
                        $addr = "192.168.1.255";	//Change broadcast address of your LAN
                        $mac = "40-16-7A-AA-9F-A7"; //Change MAC Address of your computer
                        $socket_number = 7;

                        $addr_byte = explode('-', $mac);
                        $hw_addr = '';

                        for ($a=0; $a <6; $a++) $hw_addr .= chr(hexdec($addr_byte[$a]));
                            $msg = chr(255).chr(255).chr(255).chr(255).chr(255).chr(255);
                        for ($a = 1; $a <= 16; $a++) $msg .= $hw_addr;
                        // send it to the broadcast address using UDP
                        $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
                        if ($s == false) {
                        echo "Error creating socket!\n";
                        echo "Error code is '".socket_last_error($s)."' - " . socket_strerror(socket_last_error($s));
                        return FALSE;
                        }
                        else {
                        // setting a broadcast option to socket:
                        $opt_ret = socket_set_option($s, 1, 6, TRUE);
                        if($opt_ret <0) {
                          echo "setsockopt() failed, error: " . strerror($opt_ret) . "\n";
                          }
                        if(socket_sendto($s, $msg, strlen($msg), 0, $addr, $socket_number)) {
                                echo "<div class=\"alert alert-success alert-dismissible\">
                                        <strong>Success!</strong><br>Magic Packet sent successfully!
                                        </div>
                                ";
                          socket_close($s);
                          }
                        else {
                          echo "Magic packet failed!";
                          }
                        }
                }
                else
                {
                        echo "<div class=\"alert alert-warning alert-dismissible fade show\">
                                <strong>Error!</strong><br>Password incorrect!
                                </div>
                        ";
                }
        }
}
?>

                        <div id="pcStatus">PC Status loading...</div>
                        <div id="ps4Status">PS4 Status loading...</div>

                </div>

                <div class="form-group">

                <?php
				//temperature display of Raspberry Pi
					$f = fopen("/sys/class/thermal/thermal_zone0/temp","r");
					$temp = fgets($f);
					echo "SoC temperature: ".round($temp/1000);
					echo "°C <br><br>\n";
					fclose($f);
                ?>

                <label for="target">Target:</label>
                        <select class="form-control" name="target">
                                <option value="1" <?PHP if( (isset($_POST['target']) && $_POST['target']==1) || (isset($_COOKIE["WOLTarget"]) && $_COOKIE["WOLTarget"] == "1") ){echo"selected";}?> >PS4</option>
                                <option value="2" <?PHP if( (isset($_POST['target']) && $_POST['target']==2) || (isset($_COOKIE["WOLTarget"]) && $_COOKIE["WOLTarget"] == "2") ){echo"selected";}?> >Desktop</option>
                        </select>
                </div>
                <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
    </form>


        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
        <script>
        $(document).ready(function(){
                updateStatus();
                setInterval(updateStatus, 10000);
        });

        function updateStatus(){
                $.ajax({
                        url: "./pcStatus.php",
                        type : "GET",
                        success : function(data){
                                document.getElementById("pcStatus").innerHTML = data;
                                $("#pcStatus").fadeIn();
                        }
                });

                $.ajax({
                        url: "./ps4Status.php",
                        type : "GET",
                        success : function(data){
                                document.getElementById("ps4Status").innerHTML = data;
                                $("#ps4Status").fadeIn();
                        }
                });
        }
        </script>
    </body>
</html>
