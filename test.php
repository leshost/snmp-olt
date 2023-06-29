<?php
    # Config
    $ip='10.10.0.4'; // IP addres
    $ro='public'; // Community
?>
<html>

<head>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <script src="assets/js/script.js"></script>
</head>

<body>
    <form>
        <input type="button" value="Върни се назад!" onclick="history.back()">
    </form>
    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search by name">

<?php

$time1= date("Y-m-d H:i:s");
$session = new SNMP(SNMP::VERSION_2C, $ip, $ro);

$ifDescr        = $session->walk(".1.3.6.1.2.1.2.2.1.2", TRUE);
$ifAlias        = $session->walk("IF-MIB::ifAlias", TRUE);
$ifSpeed        = $session->walk(".1.3.6.1.2.1.2.2.1.5", TRUE);
$ifAdminStatus  = $session->walk(".1.3.6.1.2.1.2.2.1.7", TRUE);
$ifOperStatus   = $session->walk(".1.3.6.1.2.1.2.2.1.8", TRUE);
$ifInErrors     = $session->walk(".1.3.6.1.2.1.2.2.1.14", TRUE);
$ifOutErrors    = $session->walk(".1.3.6.1.2.1.2.2.1.20", TRUE);
$ONUMAC         = $session->walk("1.3.6.1.4.1.3320.101.10.1.1.3", TRUE);
$ONURxLevel     = $session->walk("1.3.6.1.4.1.3320.101.10.5.1.5", TRUE);
$ONUTemp        = $session->walk("1.3.6.1.4.1.3320.101.10.5.1.2", TRUE);
$ONUDist        = $session->walk("1.3.6.1.4.1.3320.101.10.1.1.27", TRUE);
$ONUVendor      = $session->walk("1.3.6.1.4.1.3320.101.10.1.1.1", TRUE);
$ONUModel       = $session->walk("1.3.6.1.4.1.3320.101.10.1.1.2", TRUE);
$Timeticks      = $session->walk("iso.3.6.1.2.1.2.2.1.9", TRUE);
$sysuptime      = $session->walk("SNMPv2-MIB::sysUpTime.0", TRUE);

//$sysuptime[1] = preg_replace("Timeticks:","",$sysuptime[0]);

print_r($sysuptime);
echo "<br>";

//echo '<td>'.$sysuptime.'</td>';

		asort($ifDescr);
        foreach ($ifDescr as $key => $value) 
        {
            $iface[$key]['IfId']=$key;
            $value=explode(' ', $value);
            $value=end($value);
            $value=trim($value);
            $value = str_replace("\"", "", $value);
            $iface[$key]['IfDescr']=$value;
        }
        
        foreach ($ifAlias as $key => $value) 
        {
            $iface[$key]['IfId']=$key;
            $value=explode(' ', $value);
            $value=end($value);
            $value=trim($value);
            $value = str_replace("\"", "", $value);
            $iface[$key]['ifAlias']=$value;
        }
	
        foreach ($Timeticks as $key => $value) 
        {
            //        $iface[$key]['Timeticks']=$value;
            $value=explode('  ', $value);
            $value=end($value);
            //	$value = str_replace("Timeticks:","",$value[0]);
            //	$value = str_replace("", "", $value);

            $value = explode(')', $value);
            $value = trim(end($value));

            $iface[$key]['Timeticks']=$value;
        }

	    foreach ($ifSpeed as $key => $value) 
        {
            $value=explode(':', $value);
            $value=end($value);
            $value=trim($value);
            $iface[$key]['IfSpeed']=$value;
        }

        foreach ($ifAdminStatus as $key => $value) 
        {
            $value=explode(':', $value);
            $value=end($value);
            $value=trim($value);
            $iface[$key]['IfAdminStatus']=$value;
        }

        foreach ($ifOperStatus as $key => $value) 
        {
            $value=explode(':', $value);
            $value=end($value);
            $value=trim($value);
            $iface[$key]['IfOperStatus']=$value;
        }

        foreach ($ifInErrors as $key => $value) 
        {
            $value=explode(':', $value);
            $value=end($value);
            $value=trim($value);
            $iface[$key]['IfInErrors']=$value;
        }

        foreach ($ifOutErrors as $key => $value) 
        {
            $value=explode(':', $value);
            $value=end($value);
            $value=trim($value);
            $iface[$key]['IfOutErrors']=$value;
        }

        foreach ($ONUMAC as $key => $value) 
        {
            $value=explode(':', $value);
            $value=end($value);
            $value=trim($value);
            $value = str_replace (" ", ":", $value);
            $iface[$key]['ONUMAC']=$value;
        }

        foreach ($ONURxLevel as $key => $value) 
        {
            $value=explode(':', $value);
            $value=end($value);
            $value=trim($value);
            $iface[$key]['ONURxLevel']=$value;
        }

        foreach ($ONUTemp as $key => $value) 
        {
            $value=explode(':', $value);
            $value=end($value);
            $value=trim($value);
            $value = $value/256;
            $value = round($value, 2);
            $iface[$key]['ONUTemp']=$value;
        }

        foreach ($ONUDist as $key => $value) 
        {
            $value=explode(':', $value);
            $value=end($value);
            $value=trim($value);
            $iface[$key]['ONUDist']=$value;
        }

        foreach ($ONUVendor as $key => $value) 
        {
            $value=explode(':', $value);
            $value=end($value);
            $value=trim($value);
            $value = str_replace("\"", "", $value);
            $iface[$key]['ONUVendor']=$value;
        }

        foreach ($ONUModel as $key => $value) 
        {
            $value=explode(':', $value);
            $value=end($value);
            $value=trim($value);
            $value = str_replace("\"", "", $value);
            $iface[$key]['ONUModel']=$value;
        }

	echo "Генерирано на: $time1.<h2></h2>";
	echo "<table id='myTable'>";
	echo "<th onclick='sortTable(0)'>Интерфейс</th>";
	echo "<th onclick='sortTable(1)'>Потребител</th>";
        echo "<th onclick='sortTable(2)'>Статус</th>";
        echo "<th onclick='sortTable(3)'>Онлайн</th>";
        echo "<th onclick='sortTable(4)'>MAC адрес</th>";
        echo "<th onclick='sortTable(5)'>Сила сигнал</th>";
        echo "<th onclick='sortTable(6)'>Температура</th>";
        echo "<th onclick='sortTable(7)'>Разстояние в метри</th>";
	echo "</tr>";
	
    foreach ($iface as $key)
    {
        $date=date("Y-m-d H:i:s");
        // $IfId=$equipment_id.'_'.$key['IfId'];
        // $Timeticks=$equipment_id.'  '.$key['IfId'];
        $IfDescr=$key['IfDescr'];
        $ifAlias=$key['ifAlias'];
        $IfSpeed=$key['IfSpeed'];
	    $Timeticks=$key['Timeticks'];
        $IfAdminStatus=$key['IfAdminStatus'];
        $IfOperStatus=$key['IfOperStatus'];
        $IfInErrors=$key['IfInErrors'];
        $IfOutErrors=$key['IfOutErrors'];
        
        $ONUMAC=NULL;
        if(isset( $key['ONUMAC'])) 
        {
            $ONUMAC=$key['ONUMAC'];
        }

        $ONURxLevel=NULL;
        if(isset( $key['ONURxLevel'])) 
        {
            $ONURxLevel=$key['ONURxLevel']/10;
        }

        $ONUTemp=NULL;
        if(isset( $key['ONUTemp']))
        {
            $ONUTemp=$key['ONUTemp'];
        }

        $ONUDist=NULL;
        if(isset( $key['ONUDist']))
        {
            $ONUDist=$key['ONUDist'];
        }

        // $ONUVendor=NULL;if(isset( $key['ONUVendor'])){
        // $ONUVendor=$key['ONUVendor'];}else{}
        // if(isset( $key['ONUModel'])){
        // $ONUModel=$key['ONUModel'];}else{$ONUModel=NULL;}
        // $ONUVendorModel=$ONUVendor.'/'.$ONUModel;
        // echo '<td>IfId: '.$IfId.'</td>';
        // echo '<br>';

        echo '<td>'.$IfDescr.'</td>';
        echo '<td>'.$ifAlias.'</td>';
        
        // echo '<td>IfSpeed: '.$IfSpeed.'</td>';
        // echo '<td>IfAdminStatus: '.$IfAdminStatus.'</td>';
		
        $statusColor	= stripos( $IfOperStatus, 'up' ) === false ? 'red' : 'green';
        echo '<td style="background-color:'.$statusColor.'">&nbsp;&nbsp;&nbsp;&nbsp;</td>';
        
        // echo '<td>IfInErrors: '.$IfInErrors.'</td>';
        // echo '<td>IfOutErrors: '.$IfOutErrors.'</td>';
        // echo '</br>';
	    
        echo '<td>'.$Timeticks.'</td>';

        $epon=stripos($IfDescr, 'pon');
        $eponslash=stripos($IfDescr, '/');
        $eponcolon=stripos($IfDescr, ':');
        
        if($epon !== false and $eponslash!== false and $eponcolon !== false)
        {
            echo '<td>MAC: '.$ONUMAC.'</td>';
            echo '<td>Сигнал: '.$ONURxLevel.'</td>';
            echo '<td>Температура: '.$ONUTemp.'</td>';
            echo '<td>Растояние: '.$ONUDist.'</td>';
        }

        // echo '<td>ONUVendor: '.$ONUVendor.'</td>';
        // echo '<td>ONUModel: '.$ONUModel.'</td>'; }
        // echo '</br>';
        
        echo '</tr>';
    }

	echo '</table>';
?>
</body>
</html>