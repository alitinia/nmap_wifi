<?php
$page = $_SERVER['PHP_SELF'];
$sec = "120";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    <title>Daftar Hadir Mahasiswa</title>
    <style>
        p {
            color: black;
            text-align: center;
            font-size: 29px;
            font-family: "Comic Sans MS";
        }

        table {
            border-collapse: collapse;
            text-align: center;
            width: 50%;
        }

        table, td, th {
            border: 1px solid black;
            margin-top: 1px;
            margin-bottom: 200px;
            margin-right:1000px;
            margin-left: 295px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            height: 50px;
            font-size: 17px;
        }
    </style>
</head>

<body>
<p><img src="logo_unj.png" width="10%" height="10%" ></img></p>
<p>Daftar Hadir Mahasiswa<p> <br>


    <?php

    ini_set('max_execution_time', 0);

    function debug($string) {
        for ($i=0;$i<strlen($string);$i++) {
            echo substr($string, $i,1) . ' = ' . $i . ' ' . ord(substr($string, $i,1)) . '<br>';
        }
    }

    function showData($data) {
        echo '<table><tr><th>IP</th><th>MAC</th></tr>';
        for ($i=0;$i<count($data['MAC']);$i++) {
            echo '<tr><td>' .  $data['IP'][$i] 	. '</td><td>' . $data['MAC'][$i] . '</td></tr>';
        }
        echo '</table';
    }

    function update($data) {
        $server = 'localhost';
        $user = 'root';
        $password = '';
        $database = 'db_absenv2';

        $koneksi= mysql_connect($server, $user, $password);
        mysql_select_db($database, $koneksi);

        for ($i=0;$i<count($data['MAC']);$i++) {
            $sql= "INSERT INTO macaddrs(MAC_Addr)
				VALUES('" . $data['MAC'][$i] . "')";

            mysql_query($sql);
        }
    }
    //	echo '<tr><td>' .  $data['IP'][$i] 	. '</td><td>' . $data['MAC'][$i] . '</td></tr>';

    //echo '</table';

    function getMac($string) {
        $data = array();
        $count = 0;
        //string untuk mengekstrak MAC
        $mac = substr($string,strpos($string, 'MAC Address:'));
        //string untuk mengekstrak IP
        $ip = substr($string,strpos($string, 'Nmap scan report for 192')+21);
        while ($count < 14) {
            $data['MAC'][] = substr($mac, 13, 17);
            $data['IP'][] = substr($ip, 0, strpos($ip,'Host')-1);
            //echo 'MAC = '. substr($mac, 13, 17) . '<br> IP =' . substr($ip, 0,strpos($ip,'Host')-1) . '<br>';
            $string = substr($mac,strpos($mac, 'Nmap scan report for 192'));
            $mac = substr($string,strpos($string, 'MAC Address:'));
            $ip = substr($string,strpos($string, 'Nmap scan report for 192')+21);
            $check = substr($mac, 13, 3);
            if (strcmp($check,'ort') == 0) {
                //echo "Keluar";
                break;
            }
            $count++;
        }
        return $data;
    }

    ob_start();
    system('nmap -sP 192.168.0.1/24');
    $res = ob_get_contents();
    ob_clean();
    //echo $res;
    //echo $res;

    $test = "Nmap scan report for 192.168.1.1 Host is up (0.0020s latency). MAC Address: F8:1A:67:FF:88:B4 (Tp-link Technologies) Nmap scan report for 192.168.0.2 Host is up (0.10s latency). MAC Address: B0:E8:92:F7:7E:CC (Seiko Epson) Nmap scan report for 192.168.0.101 Host is up (0.13s latency). MAC Address: 7C:F9:0E:05:96:AC (Samsung Electronics) Nmap scan report for 192.168.0.104 Host is up (0.024s latency). MAC Address: 00:24:21:E6:04:B7 (Micro-star Int'l) Nmap scan report for 192.168.0.106 Host is up (0.21s latency). MAC Address: A0:F3:C1:0B:80:24 (Tp-link Technologies) Nmap scan report for 192.168.0.107 Host is up (0.22s latency). MAC Address: A0:F3:C1:0B:80:24 (Tp-link Technologies) Nmap scan report for 192.168.0.110 Host is up (0.072s latency). MAC Address: 18:CF:5E:27:4C:1E (Liteon Technology) Nmap scan report for 192.168.0.113 Host is up (0.074s latency). MAC Address: 28:E3:47:D1:D9:5F (Liteon Technology) Nmap scan report for 192.168.0.116 Host is up (0.079s latency). MAC Address: 00:1E:65:B4:DE:AC (Intel Corporate) Nmap scan report for 192.168.0.120 Host is up (0.11s latency). MAC Address: 00:18:60:6C:43:0D (SIM Technology Group Shanghai Simcom,) Nmap scan report for 192.168.0.124 Host is up (0.082s latency). MAC Address: 88:53:2E:11:57:83 (Intel Corporate) Nmap scan report for 192.168.0.127 Host is up (0.087s latency). MAC Address: DC:85:DE:8A:B6:E9 (Azurewave Technologies.) Nmap scan report for 192.168.0.133 Host is up (0.088s latency). MAC Address: A0:F3:C1:20:52:75 (Tp-link Technologies) Nmap scan report for 192.168.0.100 Host is up. Nmap done: 256 IP addresses (14 hosts up) scanned in 4.47 seconds ";
    //getMac($test);
    $data = getMac($res);
    //showData($data);
    showData($data);
    update($data);
    ?>
</body>
</html>