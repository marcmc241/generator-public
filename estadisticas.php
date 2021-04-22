<?PHP
	session_start();
	require_once("protege.php");
	require_once("conexion_PDO.php");
?>

<?PHP
   $db = new Conexion();
	$dbTabla="Oxmfclub";
        $consulta = "SELECT
				 	DATE(fecha) AS dates
				 ,	COUNT(IdUser) AS ids
				 FROM
				 	$dbTabla
				 GROUP BY
				 	dates
				 ORDER BY dates DESC";
        $result = $db->prepare($consulta);
        $result->execute();
        $max=0;
        $nvisitas='';
        $vdias='';
	foreach ($result as $dia){
		if ($max<=30) {
			$nvisitas=$dia['ids'].",".$nvisitas;
			$newDate = date("d-m-Y", strtotime($dia['dates']));
            $vdias="'".$newDate."',".$vdias;
            $max++;
		}
		
		}

		$nvisitas=substr($nvisitas, 0, -1);
    $vdias=substr($vdias, 0, -1);

    $dbTabla="Oxmf_productos_vistos";
        $consulta = "SELECT
                    DATE(fecha) AS dates
                 ,  COUNT(IdV) AS ids
                 FROM
                    $dbTabla
                 GROUP BY
                    dates
                 ORDER BY dates DESC";
        $result = $db->prepare($consulta);
        $result->execute();
        $max=0;
        $nvistos='';
        $pvdias='';
    foreach ($result as $dia){
        if ($max<=30) {
            $nvistos=$dia['ids'].",".$nvistos;
            $newDate = date("d-m-Y", strtotime($dia['dates']));
            $pvdias="'".$newDate."',".$pvdias;
            $max++;
        }
        
    }

    $nvistos=substr($nvistos, 0, -1);
    $pvdias=substr($pvdias, 0, -1);



    $dbTabla="Bitlink";
        $consulta = "SELECT
				 	DATE(fechap) AS dates
				 ,	COUNT(Idb) AS ids
				 FROM
				 	$dbTabla
				 GROUP BY
				 	dates
				 ORDER BY dates DESC";
        $result = $db->prepare($consulta);
        $result->execute();
        $max=0;
        $nbitlinks='';
        $bdias='';
	foreach ($result as $dia){
		if ($max<=30) {
			$nbitlinks=$dia['ids'].",".$nbitlinks;
			$newDate = date("d-m-Y", strtotime($dia['dates']));
            $bdias="'".$newDate."',".$bdias;
            $max++;
		}
		
		}

		$nbitlinks=substr($nbitlinks, 0, -1);
    $bdias=substr($bdias, 0, -1);


$dbTabla="Oferta";
        $consulta = "SELECT
				 	DATE(fechap) AS dates
				 ,	COUNT(Ido) AS ids
				 FROM
				 	$dbTabla
				 WHERE
				 	Oferta.estadistica=1
				 GROUP BY
				 	dates
				 ORDER BY dates DESC";
        $result = $db->prepare($consulta);
        $result->execute();
        $max=0;
        $nofertas='';
        $odias='';
	foreach ($result as $dia){
		if ($max<=30) {
			$nofertas=$dia['ids'].",".$nofertas;
			$newDate = date("d-m-Y", strtotime($dia['dates']));
            $odias="'".$newDate."',".$odias;
            $max++;
		}
		
		}

		$nofertas=substr($nofertas, 0, -1);
    $odias=substr($odias, 0, -1);

$dbTabla="Oferta";
        $consulta = "SELECT
				 	MONTH(fechap) AS dates
				 ,	COUNT(Ido) AS ids
				 FROM
				 	$dbTabla
				 WHERE
				 	Oferta.estadistica=1
				 GROUP BY
				 	dates
				 ORDER BY fechap DESC";
        $result = $db->prepare($consulta);
        $result->execute();
        $max=0;
        $nofertas2='';
        $odias2='';
	foreach ($result as $mes){
		if ($max<=24) {
			$nofertas2=$mes['ids'].",".$nofertas2;
			$dateObj   = DateTime::createFromFormat('!m', $mes['dates']);
			$newDate = $dateObj->format('F');
            $odias2="'".$newDate."',".$odias2;
            $max++;
		}
		
		}

		$nofertas2=substr($nofertas2, 0, -1);
    $odias2=substr($odias2, 0, -1);
    
    //productos más vistos oxmf.club
    $mes1='';
    $mes2='';
    $mes3='';
    $visit1='';
    $visit2='';
    $visit3='';
    $productos='';
    $meses='';
    $visit='';
    $m1=0;
    $m2=0;
    $m3=0;
     $maxmonth=date("Y-m-01 00:00:00", strtotime( date( "Y-m-01 00:00:00", strtotime( date("Y-m-01 00:00:00") ) ) . "-3 month" ) );//complete SQL date: day 01 of 3 months ago
     $dbTabla="Oxmf_productos_vistos, Producto";
        $consulta = "SELECT
                    MONTH(fecha) AS dates
                 ,   nombrep
                 ,  COUNT(producto) AS visitas
                 FROM
                    $dbTabla
                WHERE
                Producto.Idp=Oxmf_productos_vistos.producto AND Oxmf_productos_vistos.fecha>:fe
                 GROUP BY
                    dates, producto
                 ORDER BY producto, fecha DESC";
        $result = $db->prepare($consulta);
        $result->execute(array(":fe" => $maxmonth));//set maximum date
        $max=0;
        $prevprod="";
        $mes1=date("n");
        $mes2=date("n", strtotime( date( "Y-m-01 00:00:00", strtotime( date("Y-m-01 00:00:00") ) ) . "-1 month" ) );//mes anterior
        $mes3=date("n", strtotime( date( "Y-m-01 00:00:00", strtotime( date("Y-m-01 00:00:00") ) ) . "-2 month" ) );//dos meses antes
        //print($mes1."  ".$mes2."  ".$mes3);
    foreach ($result as $prod){
            
            if ($prod['nombrep']!=$prevprod) {//if different product
                //Check if all months of previous product have been filled, if not, add an empty element to array
                if ($m1!=1) {
                $visit1="0,".$visit1;
                }
                if ($m2!=1) {
                $visit2="0,".$visit2;
                }
                if ($m3!=1) {
                $visit3="0,".$visit3;
                }
                
                $productos="'".$prod['nombrep']."',".$productos;
                $prevprod=$prod['nombrep'];
            }
            
            if ($prod['dates']==$mes1) {
                $visit1=$prod['visitas'].",".$visit1;
                $m1=1;
            }
            else if ($prod['dates']==$mes2) {
                $visit2=$prod['visitas'].",".$visit2;
                $m2=1;
            }
            else if ($prod['dates']==$mes3) {
                $visit3=$prod['visitas'].",".$visit3;
                $m3=1;
            }
    }
    //FOR LAST PRODUCT: Check if all months of product have been filled, if not, add an empty element to array
                if ($m1!=1) {
                $visit1="0,".$visit1;
                }
                if ($m2!=1) {
                $visit2="0,".$visit2;
                }
                if ($m3!=1) {
                $visit3="0,".$visit3;
                }
    $dateObj   = DateTime::createFromFormat('!m', $mes1);
    $newDate = $dateObj->format('F');
    $mes1=$newDate;
    $dateObj   = DateTime::createFromFormat('!m', $mes2);
    $newDate = $dateObj->format('F');
    $mes2=$newDate;
    $dateObj   = DateTime::createFromFormat('!m', $mes3);
    $newDate = $dateObj->format('F');
    $mes3=$newDate;

    $productos=substr($productos, 0, -1);
    $meses=substr($meses, 0, -1);
    $visit=substr($visit, 0, -1);
$db=NULL;
	?>

<!DOCTYPE html>
<html>
<head>
	<title>Generator 3000</title>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<!--Copyright 2016-17, Marc Masip-->
<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
<script src="js/scripts.js"></script>
<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<link href="css/estils.css" rel="stylesheet" />
<script type="text/javascript">

$(function () { 
    var myChart = Highcharts.chart('container', {
        chart: {
        	backgroundColor: 'rgba(0,0,0,0)',
            type: 'line'

        },
        title: {
            text: 'Número de visitas a oxmf.club'
        },
        subtitle: {
            text: 'últimos 30 días'
        },
        xAxis: {
            categories: [<?PHP print($vdias); ?>]
        },
        yAxis: {
            title: {
                text: 'nº visitas'
            }
        },
         plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            //enableMouseTracking: false
        }
   		},
        series: [{
        name: 'nº visitas',
        color: '#f6ca48',
        data: [<?PHP print($nvisitas); ?>]
    }]
    });
});

$(function () { 
    var myChart = Highcharts.chart('containerPV', {
        chart: {
            backgroundColor: 'rgba(0,0,0,0)',
            type: 'line'

        },
        title: {
            text: 'Número de porductos vistos en oxmf.club'
        },
        subtitle: {
            text: 'últimos 30 días'
        },
        xAxis: {
            categories: [<?PHP print($pvdias); ?>]
        },
        yAxis: {
            title: {
                text: 'nº prod. vistos'
            }
        },
         plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            //enableMouseTracking: false
        }
        },
        series: [{
        name: 'nº visitas',
        color: '#f6ca48',
        data: [<?PHP print($nvistos); ?>]
    }]
    });
});

$(function () { 
    var myChart = Highcharts.chart('container2', {
        chart: {
        	backgroundColor: 'rgba(0,0,0,0)',
            type: 'line'

        },
        title: {
            text: 'Número de links pasados por oxmf.club'
        },
        subtitle: {
            text: 'últimos 30 días que se ha pasado alguno'
        },
        xAxis: {
            categories: [<?PHP print($bdias); ?>]
        },
        yAxis: {
            title: {
                text: 'nº links'
            }
        },
         plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            //enableMouseTracking: false
        }
   		},
        series: [{
        name: 'nº links',
        color: '#f6ca48',
        data: [<?PHP print($nbitlinks); ?>]
    }]
    });
});

$(function () { 
    var myChart = Highcharts.chart('container3', {
        chart: {
        	backgroundColor: 'rgba(0,0,0,0)',
            type: 'line'

        },
        title: {
            text: 'Número de ofertas generadas por día'
        },
        subtitle: {
            text: 'últimos 30 días'
        },
        xAxis: {
            categories: [<?PHP print($odias); ?>]
        },
        yAxis: {
            title: {
                text: 'nº ofertas'
            }
        },
         plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            //enableMouseTracking: false
        }
   		},
        series: [{
        name: 'nº ofertas',
        color: '#f6ca48',
        data: [<?PHP print($nofertas); ?>]
    }]
    });
});

$(function () { 
    var myChart = Highcharts.chart('container4', {
        chart: {
        	backgroundColor: 'rgba(0,0,0,0)',
            type: 'line'

        },
        title: {
            text: 'Número de ofertas generadas por mes'
        },
        subtitle: {
            text: '(max) últimos 24 meses'
        },
        xAxis: {
            categories: [<?PHP print($odias2); ?>]
        },
        yAxis: {
            title: {
                text: 'nº ofertas'
            }
        },
         plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            //enableMouseTracking: false
        }
   		},
        series: [{
        name: 'nº ofertas',
        color: '#f6ca48',
        data: [<?PHP print($nofertas2); ?>]
    }]
    });
});

/*$(function () { 
    Highcharts.chart('container5', {
    chart: {
        backgroundColor: 'rgba(0,0,0,0)',
        type: 'bar'
    },
    title: {
        text: 'Oxmf.club'
    },
    subtitle: {
        text: 'Núm de visitas de cada producto por mes'
    },
    xAxis: {
        categories: [<?PHP print($productos); ?>],
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'nº visitas',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: ' visitas'
    },
    plotOptions: {
        series: {
            pointPadding: 0
        }
    },
    credits: {
        enabled: false
    },
    series: [{
        name: '<?PHP print($mes1); ?>',
        data: [<?PHP print($visit1); ?>]
    }, {
        name: '<?PHP print($mes2); ?>',
        data: [<?PHP print($visit2); ?>]
    }, {
        name: '<?PHP print($mes3); ?>',
        data: [<?PHP print($visit3); ?>]
    }]
});
});*/

</script>
</head>

<body>
	<?PHP	
	require_once("header.php");
	?>

<section id="">
<br><br>
	<h4 style="text-align: center;">oxmf.club</h4>
</section>
<div id="container" class="card" style="width:90%; height:400px; margin-top: 50px;"></div>
<div id="containerPV" class="card" style="width:90%; height:400px; margin-top: 50px;"></div>
<div id="container2" class="card" style="width:90%; height:400px; margin-top: 50px;"></div>
<br><br><br>
<section>
	<h4 style="text-align: center;">Generator - Ofertas</h4>
</section>
<div id="container3" class="card" style="width:90%; height:400px; margin-top: 50px;"></div>
<div id="container4" class="card" style="width:90%; height:400px; margin-top: 50px;"></div>
<!--<div id="container5" style="width:90%; height:6000px; margin-top: 50px; margin-left: 5%;"></div>-->

<footer>©<?PHP print(date('Y')."-".(date('y')+1));?> MMC All rights reserved. Developed by C, in collaboration with Admins Ofertasxiaomifansclub<br>
<a href="changelog.php">Changelog</a></footer>
</body>

</html>

