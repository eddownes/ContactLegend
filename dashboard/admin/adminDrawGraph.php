<?php 
include('../library/function.php');
#$query = "select count(*) as total from users where `dCreatedDate` >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)AND MONTH(now()) GROUP BY  Month(`dCreatedDate`)";

$where = "`dCreatedDate` >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND MONTH(now())";
$groupby = "GROUP BY  Month(`dCreatedDate`)";
$users = getAnyData('count(*) as total', 'users', $where, null,$groupby);
$total = count($users);
for($i=0;$i<$total;$i++){
    $data.= $users[$i]['total'].",";
}

$data = rtrim($data,',');

?>
<script src="<?php echo $base_url;?>js/jquery.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto;"></div>
<script type="text/javascript">
jQuery(function() {

    var f = new Date();
    var month = new Array();
    month[0] = "January";
    month[1] = "February";
    month[2] = "March";
    month[3] = "April";
    month[4] = "May";
    month[5] = "June";
    month[6] = "July";
    month[7] = "August";
    month[8] = "September";
    month[9] = "October";
    month[10] = "November";
    month[11] = "December";

    var firstmonth = month[f.getMonth()]; 
    var secondmonth = month[f.getMonth()-1]; 
    var thirdmonth = month[f.getMonth()-2]; 

    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'New Users Since '+thirdmonth
        },
        subtitle: {
            //text: 'Source: WorldClimate.com'
        },
        xAxis: {
            categories: [
                thirdmonth,
                secondmonth,
                firstmonth,
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Data'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Total Users',
            data: [<?php echo $data;?>]

        }]
    });
});
</script>