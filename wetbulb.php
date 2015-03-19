<?php
namespace SimpleNWS;

require_once 'simple-nws/SimpleNWS.php';



function calcwetbulb($Edifference,$Twguess,$Ctemp,$MBpressure,$E2,$previoussign,$incr)

			{
	
				while (abs($Edifference) > 0.005) 

				{

					$Ewguess = 6.112 * exp((17.67 * $Twguess) / ($Twguess + 243.5));

					$Eguess = $Ewguess - $MBpressure * ($Ctemp - $Twguess) * 0.00066 * (1 + (0.00115 * $Twguess));

					$Edifference = $E2 - $Eguess;

					

					if ($Edifference == 0)

					{

						break;

					} else {

						if ($Edifference < 0)

						{

							$cursign = -1;

							if ($cursign != $previoussign)

							{

								$previoussign = $cursign;

								$incr = $incr/10;

							} else {

								$incr = $incr;

							}

						} else {

							$cursign = 1;

							if ($cursign != $previoussign)

							{

								$previoussign = $cursign;

								$incr = $incr/10;

							} else {

								$incr = $incr;

							}

						}

					}

					

					$Twguess = $Twguess + $incr * $previoussign;

					

				}

				$wetbulb = $Twguess;

				return $wetbulb;

			}
			
			

?><!DOCTYPE html>
<html lang="en">
    <head>
    
    <style>
	#flot-tooltip {
        font-size: 12px;
        font-family: Verdana, Arial, sans-serif;
        position: absolute;
        display: none;
        border: 2px solid;
        padding: 2px;
        background-color: #FFF;
        opacity: 0.8;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        -khtml-border-radius: 5px;
        border-radius: 5px;
    }
	
	</style>
    <script language="javascript" type="text/javascript" src="flot/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="flot/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="flot/jquery.flot.crosshair.js"></script>
    <script language="javascript" type="text/javascript" src="flot/jquery.flot.threshold.js"></script>
    <script language="javascript" type="text/javascript" src="flot/jquery.flot.time.js"></script>
        <meta charset="utf-8" />
      </head>
    <body>
    
    
      
      
<?php


$location = array('clear lake oaks',
				  'ukiah',
				  'alexander valley south',
				  'geyserville',
				  'cloverdale',
				  'hopland',
				  'dry creek',
				  'talmage',
				  'redwood valley',
				  'potter valley',
				  'kelseyville',
				  'lakeport',
				  'upper lake',
				  'hidden valley lake',
				  'middletown',
				  'knights valley',
				  'calistoga',
				  'pope valley',
				  'st helena',
				  'rutherford',
				  'yountville',
				  'napa',
				  'east of napa airport',
				  'napa carneros',
				  'sonoma carneros',
				  'fulton',
				  'windsor',
				  'forrestville',
				  'occidental',
				  'sebastopol',
				  'willits');
				  
	$c=0;
foreach ($location as $loc2link) {

	echo "<a href='?loc=".$c."'>".ucwords($loc2link)."</a> | ";
	$c++;
	
}
				  
$gps = array('39.02639,-122.67083',
				  '39.1503,-123.207',
				  '38.67050,-122.82920',
				  '38.71766,-122.90302',
				  '38.80226,-122.99915',
				  '38.980763,-123.090134',
				  '38.665675,-122.934952',
				  '39.111948,-123.173904',
				  '39.295517,-123.212357',
				  '39.314113,-123.10524',
				  '39.012965,-122.84294',
				  '39.049235,-122.92259',
				  '39.151545,-122.90542',
				  '38.81662258718444,-122.57034301757812',
				  '38.74436042212731,-122.574462890625',
				  '38.64030503762011,-122.69393920898438',
				  '38.5818216608345,-122.56004333496094',
				  '38.62751587276655,-122.39524841308594',
				  '38.49597214776305,-122.44880676269531',
				  '38.45699979236794,-122.4045181274414',
				  '38.4026721574666,-122.35919952392578',
				  '38.31005976976798,-122.26959228515625',
				  '38.21786843861598,-122.24315643310547',
				  '38.232432742316284,-122.33070373535156',
				  '38.23890483016111,-122.41962432861328',
				  '38.48576074014118,-122.7773666381836',
				  '38.54808151442664,-122.83435821533203',
				  '38.46990331863387,-122.87967681884766',
				  '38.40993613837642,-122.94731140136719',
				  '38.40078878398125,-122.8103256225586',
				  '39.43266297656767,-123.3383560180664');

/*
$location = array('clear lake oaks',
				  'ukiah'
				  );
				  
$gps = array('39.02639,-122.67083',
				  '39.1503,-123.207');
*/

$z=0;
$_GET['days'] = 5;
$_GET['minWBtemp'] = 36;

foreach ($gps as $loc) {
	
	if (isset($_GET['loc']) && $_GET['loc']==$z) {

$loc = str_replace(" ","",str_replace(")","",str_replace("(","",$loc)));

$loc = explode(",",$loc);

echo "<h1>".str_replace(" ","",str_replace(")","",str_replace("(","",number_format($loc[0],2)))) ." , " .str_replace(" ","",str_replace(")","",str_replace("(","",number_format($loc[1],2))));

echo " - ".ucwords($location[$z])."</h1><blockquote>";

$alerts = wetbulb($location[$z],str_replace(" ","",str_replace(")","",str_replace("(","",number_format($loc[0],2)))),str_replace(" ","",str_replace(")","",str_replace("(","",number_format($loc[1],2)))),$_GET['minWBtemp'],$_GET['days']);

if (sizeof($alerts)>0) {echo "<h2 style='color:red;'>ALERT! Potential for Frost in the area!</h2>"; /*print_r($alerts);*/} else { echo "<h2>No Forecasted Wet Bulb temperatures below &deg;".$_GET['minWBtemp']."F in the next ".$_GET['days']." day(s)</h2>";}

echo "</blockquote>";

	}



$z++;	
}

function wetbulb($locationname,$lat,$long,$minWBalert,$futuredays) {
	 
$locationnameSmall = str_replace(" ","",$locationname);
	 
// instantiate the library
$simpleNWS = new SimpleNWS($lat, $long);




try
{
    
	$forecast = $simpleNWS->getForecastForWeek();
	$requestURL = $forecast->getRequestURL();
    $requestParts = explode('?', $requestURL);
    
	$barometer = file_get_contents("http://forecast.weather.gov/MapClick.php?textField1=".$lat."&textField2=".$long);
	
	//echo "http://forecast.weather.gov/MapClick.php?textField1=".$lat."&textField2=".$long ."<br>";
	
	$barometer = explode('<span class="label">Barometer</span>',$barometer);
	$barometer = explode('</li>',$barometer[1]);
	$barometer = explode(' in (',$barometer[0]);
	
	$Press = number_format($barometer[0],2);
	
	if ($Press>0) {
		$Press *= 33.8639;
	}
	else {
		
		echo "<b>PRESSURE NOT RECEIVED using 30in. Hg instead.</b>";	
		//mail("chalupien@gmail.com","http://lupien.me/projects/noaa/example.php CANNOT get Barometer from NOAA",$Press);
		$Press = 33.8639*30;
	}
	
	$tmpC = $forecast->convertToCelsius($forecast->getHourlyRecordedTemperature());
	$RH = $forecast->getHourlyHumidity();
	
	$x=0;
	$RH1 = array();
	foreach ($RH as $y=>$RHval) {
		$RH1[$y] = $RHval;
		$x++;
	}
	
	$x=0;
	
	$toechogood = "";
	
	$alerts = array();
	$temp = array();
	$wetbulb = array();
	$date = array();
	
	
	foreach ($tmpC as $y=>$tmpCval) {
		
		$hourdiff = round((strtotime($time1) - strtotime($time2))/3600, 1);
		$hrDiff = explode("-",$y);
		$to_time = strtotime(date('Y')."-".date('m')."-".date('d')." ".date('H').":".date('i').":".date('s'));
		$from_time = strtotime($hrDiff[0]."-".$hrDiff[1]."-".$hrDiff[2]." ".$hrDiff[3].":00:00");
		$timediff = round(($to_time - $from_time) / 60,2);

		if ($timediff<0 AND $timediff>-(1440*$futuredays)) {
			
			$toechogood .= "<br><br>";

		if ($timediff > -(1440*$futuredays)) {
			$toechogood .= "<h2>";
		}
		
		
		
		
		$toechogood .= date("F d Y g:i a", strtotime($hrDiff[0]."-".$hrDiff[1]."-".$hrDiff[2]." ".$hrDiff[3].":00:00")) . "<br>";
		
		$toechogood .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Forecasted Temp: " . number_format((9/5) * $tmpCval + 32,2) . "&deg;F<br>";
		//echo "RH %  : " . $RH[$y]. "<br>";
	
	$esubs = 6.112 * exp(17.67 * $tmpCval / ($tmpCval + 243.5));
	$E2 = $esubs * ($RH1[$y]/100);
	$E = (6.112 * exp(17.67 * $tmpCval / ($tmpCval + 243.5))) * ($RH1[$y]/100);
	$DewPoint = (9/5) * ((243.5 * log($E/6.112))/(17.67 - log($E/6.112))) + 32;
	$WBc = number_format((9/5) * calcwetbulb(1,0,$tmpCval,$Press,$E,1,10) + 32,2);
	$toechogood .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Forecasted Wet Bulb: ".($WBc<=$minWBalert ? "<i><b style='color:red;'>".$WBc."&deg;F</b></i>" : $WBc."&deg;F<br>");
	
	$date[] = date("U", strtotime($hrDiff[0]."-".$hrDiff[1]."-".$hrDiff[2]." ".$hrDiff[3].":00:00"));
	
	$temp[] = number_format((9/5) * $tmpCval + 32,2);
	
	$wetbulb[] = $WBc;
	
	if ($WBc<=$minWBalert) {
	$alerts[$locationname][date("F d Y g:i a", strtotime($hrDiff[0]."-".$hrDiff[1]."-".$hrDiff[2]." ".$hrDiff[3].":00:00"))] = $WBc."&deg;F:".number_format((9/5) * $tmpCval + 32,2) . "&deg;F";
	}
	
	
	//echo "Dew Point : " . $DewPoint . "<br>";
	
	if ($WBc<=$minWBalert) {/*echo $toechogood . "</h2>";*/} else { $toechogood = ""; }
			
		}
			$x++;
	
	
	
	
		
			
	}
	
	
	
	
	
	
	
	
	/*echo "<br><Br>";
	
	echo 'Hourly Recorded Temperature: ',
            print_r($forecast->getHourlyRecordedTemperature(), true),"\n";
			
			
			
    echo 'Hourly Recorded Temperature in Celsius: ', 
            print_r($forecast->convertToCelsius($forecast->getHourlyRecordedTemperature()), true),"\n";
    echo 'Hourly Apparent Temperature: ', 
            print_r($forecast->getHourlyApparentTemperature(), true),"\n";
    echo 'Daily Maximum Temperature: ',   
            print_r($forecast->getDailyMaximumTemperature(), true),"\n";
    echo 'Daily Minimum Temperature: ',   
            print_r($forecast->getDailyMinimumTemperature(), true),"\n";
    echo 'Hourly Precipitation: ',        
            print_r($forecast->getHourlyPrecipitation(), true),"\n";
    echo 'Hourly Snow Amount: ',          
            print_r($forecast->getHourlySnowAmount(), true),"\n";
    echo 'Hourly Cloud Coverage: ',       
            print_r($forecast->getHourlyCloudCover(), true),"\n";
    echo 'Hourly Humidity: ',             
            print_r($forecast->getHourlyHumidity(), true),"\n";
    echo 'Weather Conditions: ',          
            print_r($forecast->getWeatherConditions(), true),"\n";
    echo 'Time Layouts: ',                
            print_r($forecast->getTimeLayouts(), true),"\n";
			
			*/
}
catch (\Exception $error)
{
    echo $error->getMessage();
	mail("chalupien@gmail.com","http://lupien.me/projects/noaa/example.php CANNOT get NOAA Data",$error->getMessage());
}






//FLOT

?>

     <div id="placeholder<?php echo $locationnameSmall;?>" style="width:800px;height:400px"></div>

    <p id="hoverdata<?php echo $locationnameSmall;?>"></p>

<script type="text/javascript">
var plot<?php echo $locationnameSmall;?>;
$(function () {
    var sin = [], cos = [];
    for (var i = 0; i < 14; i += 0.1) {
        sin.push([i, Math.sin(i)]);
        cos.push([i, Math.cos(i)]);
    }
		
	  var temp<?php echo $locationnameSmall;?> = [<?php 
	  $tempstr = "";
	  for ($x=0; $x<sizeof($date);$x++) {
		//[1203894000000, 40]
		$tempstr .= "[".(intval($date[$x])*1000). "," .number_format($temp[$x],2)."],";
	  }
	  $tempstr = rtrim($tempstr,",");
	  echo $tempstr;
	  ?>];
	
	  var wetbulb<?php echo $locationnameSmall;?> = [<?php 
	  $wetbulbstr = "";
	  for ($x=0; $x<sizeof($date);$x++) {
		//[1203894000000, 40]
		$wetbulbstr .= "[".(intval($date[$x])*1000). "," .number_format($wetbulb[$x],2)."],";
	  }
	  $wetbulbstr = rtrim($wetbulbstr,",");
	  echo $wetbulbstr;
	  ?>];
	
	  
	   // helper for returning the weekends in a period
    function weekendAreas(axes) {
        var markings = [];
        var d = new Date(axes.xaxis.min);
        // go to the first Saturday
        d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
        d.setUTCSeconds(0);
        d.setUTCMinutes(0);
        d.setUTCHours(0);
        var i = d.getTime();
        do {
            // when we don't set yaxis, the rectangle automatically
            // extends to infinity upwards and downwards
            markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
            i += 7 * 24 * 60 * 60 * 1000;
        } while (i < axes.xaxis.max);

        return markings;
    }

	var epochT = (new Date).getTime(); // time right now in js epoch

    plot<?php echo $locationnameSmall;?> = $.plot($("#placeholder<?php echo $locationnameSmall;?>"),
                     	[{
    data: temp<?php echo $locationnameSmall;?>,
	label: "temperature F = -0.00",
	/*threshold: {
        below: <?php echo $_GET['minWBtemp'];?>,
        color: "rgb(200, 20, 30)"
    }*/},
{
    data: wetbulb<?php echo $locationnameSmall;?>,
	label: "wet bulb F = -0.00",
    /*threshold: {
        below: <?php echo $_GET['minWBtemp'];?>,
        color: "rgb(200, 20, 30)"
    }*/}]
	
	, {
                            series: {
        lines: {
            show: true
        },
        points: {
            radius: 3,
            show: true,
            fill: true
        },
        
    },
							xaxis: { mode: "time", 
							timeformat: "%I%p %m/%d",
    tickSize: [8, "hour"],
    twelveHourClock: true,
    min: epochT,
    max: epochT + (86400000*<?php echo $_GET['days'];?>),
    timezone: "browser",
	axisLabel: 'Forecasted Date', 
	 },
        					selection: { mode: "x" },
        					crosshair: { mode: "x" },
							
                            grid: { markings:  [{yaxis: {from: 0, to: 32}, color: "#48d1cc"}], hoverable: true, autoHighlight: false },
                            yaxis: { min: 0, max: 100, ticks: 20, axisLabel: 'Forecasted Temperature F', }
                        });
    var legends = $("#placeholder<?php echo $locationnameSmall;?> .legendLabel");
    legends.each(function () {
        // fix the widths so they don't jump around
        $(this).css('width', $(this).width());
    });

    var updateLegendTimeout = null;
    var latestPosition = null;
    
    function updateLegend() {
        updateLegendTimeout = null;
        
        var pos = latestPosition;
        
        var axes = plot<?php echo $locationnameSmall;?>.getAxes();
        if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max ||
            pos.y < axes.yaxis.min || pos.y > axes.yaxis.max)
            return;

        var i, j, dataset = plot<?php echo $locationnameSmall;?>.getData();
        for (i = 0; i < dataset.length; ++i) {
            var series = dataset[i];

            // find the nearest points, x-wise
            for (j = 0; j < series.data.length; ++j)
                if (series.data[j][0] > pos.x)
                    break;
            
            // now interpolate
            var y, p1 = series.data[j - 1], p2 = series.data[j];
            if (p1 == null)
                y = p2[1];
            else if (p2 == null)
                y = p1[1];
            else
                y = p1[1] + (p2[1] - p1[1]) * (pos.x - p1[0]) / (p2[0] - p1[0]);

            legends.eq(i).text(series.label.replace(/=.*/, "= " + y.toFixed(2)));
        }
    }
    
    $("#placeholder<?php echo $locationnameSmall;?>").bind("plothover",  function (event, pos, item) {
        latestPosition = pos;
        if (!updateLegendTimeout)
            updateLegendTimeout = setTimeout(updateLegend, 50);
    });
});
</script>
        
<?php
//FLOT

	return $alerts;

}

?>
        

    </body>
</html>
