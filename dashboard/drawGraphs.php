<?php include('library/function.php');?>

<?php $nCampaignid = $_REQUEST['nCampaignid'];
$where = 'nCampaignid="'.$nCampaignid.'"';
$campaignData = getAnyData('nEmailsSent,nEmailsOpened, nCallsMade, nEmailFollowUps, nMailSent', 'campaigns', $where, null,null);
//pr($campaignData);exit;
?>
<script src="<?php echo $base_url;?>js/jquery.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<?php $data = 40;?>
<script type="text/javascript">
jQuery(function () {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            credits:{
                enabled: false,
            },
            title: {
                text: 'Campaign Summary'
            },
            subtitle: {
                //text: 'Source: WorldClimate.com'
            },
            xAxis: {
                categories: [
                    'Emails Sent',
                    'Emails Opened',
                    'Calls Made',
                    'Email Followups',
                    'Mail Sent',
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
                name: 'Emails',
                data: [<?php echo $campaignData[0]['nEmailsSent'];?>, <?php echo $campaignData[0]['nEmailsOpened'];?>, <?php echo $campaignData[0]['nCallsMade'];?>, <?php echo $campaignData[0]['nEmailFollowUps'];?>, <?php echo $campaignData[0]['nMailSent'];?>]
    
            }]
        });
    });
</script>