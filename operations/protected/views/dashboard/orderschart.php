 
<script type="text/javascript">
        $(function() {
		
		$(function () {
        $('#yearcontainer').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''//chart 
            },
            xAxis: {
                categories: [<?php foreach(array_keys($data) as $x){	echo "'".$x."',";	}?>]
            },
            yAxis: {
                min: 1,
                title: {
                    text: 'orders'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:1f}</b></td></tr>',
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
                name: 'Orders',
                data: [<?php foreach($data as $y){	echo $y.',';	}?>]
    
            }, ]
        });
    });
        });
    </script>