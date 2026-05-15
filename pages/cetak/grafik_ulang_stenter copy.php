<!DOCTYPE html>
<html>
<head>

    <title>Grafik Stop Mesin</title>

    <meta charset="utf-8">

    <script src="../../dist/js/jquery-3.5.1.min.js"></script>

    <script src="../../dist/js/highchart/highcharts.js"></script>
    <script src="../../dist/js/highchart/exporting.js"></script>

    <style>

        body{
            font-family: Arial, sans-serif;
            margin:20px;
            background:#f5f5f5;
        }

        .wrapper{
            display:flex;
            flex-direction:column;
            gap:30px;
        }

        .row-item{
            display:flex;
            gap:20px;
            align-items:flex-start;
        }

        /* KIRI */
        .chart-section{
            flex:2;
            background:#fff;
            border-radius:10px;
            padding:15px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        /* KANAN */
        .table-section{
            flex:1;
            background:#fff;
            border-radius:10px;
            padding:15px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th{
            background:#f0f0f0;
            font-weight:bold;
        }

        table th,
        table td{
            border:1px solid #ccc;
            padding:10px;
        }

        table td:last-child{
            text-align:right;
        }

        .table-title{
            font-size:18px;
            font-weight:bold;
            margin-bottom:10px;
        }

        @media(max-width: 768px){

            .row-item{
                flex-direction:column;
            }

        }

    </style>

</head>
<body>

<div class="wrapper" id="wrapperData"></div>

<script>

$(document).ready(function(){

    // AMBIL PARAMETER URL
    const urlParams = new URLSearchParams(window.location.search);

    const tgl_awal  = urlParams.get('awal');
    const tgl_akhir = urlParams.get('akhir');

    $.ajax({

        url: '../ajax/inspectStenter/ajax_grafik_stenter.php',

        type: 'GET',

        dataType: 'json',

        data: {
            tgl_awal : tgl_awal,
            tgl_akhir : tgl_akhir
        },

        success: function(res){

            let mesinData = {};

            // GROUP DATA PER MESIN
            res.forEach(function(item){

                let mesin = item.no_mesin;
                let dept  = item.dept;
                let total = parseInt(item.total_stop);

                if(!mesinData[mesin]){
                    mesinData[mesin] = [];
                }

                mesinData[mesin].push({
                    dept: dept,
                    total: total
                });

            });

            // RESET
            $('#wrapperData').html('');

            // LOOP TIAP MESIN
            Object.entries(mesinData).forEach(function([mesin, items], index){

                const chartId = `chart_${index}`;
                const tableId = `table_${index}`;

                // APPEND ROW FLEX
                $('#wrapperData').append(`
                    <div class="row-item">

                        <!-- CHART -->
                        <div class="chart-section">

                            <div id="${chartId}" style="height:400px;"></div>

                        </div>

                        <!-- TABLE -->
                        <div class="table-section">

                            <div class="table-title">
                                Rekap Mesin ${mesin}
                            </div>

                            <table>

                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Total Stop</th>
                                    </tr>
                                </thead>

                                <tbody id="${tableId}"></tbody>

                            </table>

                        </div>

                    </div>
                `);

                let categories = [];
                let tableRows = '';

                let chartData = [];

                items.forEach(function(val){

                    categories.push(val.dept);

                    chartData.push(val.total);

                    tableRows += `
                        <tr>
                            <td>${val.dept}</td>
                            <td>${val.total}</td>
                        </tr>
                    `;

                });

                // LOAD TABLE
                document.getElementById(tableId).innerHTML = tableRows;

                // HIGHCHART
                Highcharts.chart(chartId, {

                    chart: {
                        type: 'column'
                    },

                    title: {
                        text: `Grafik Stop Mesin ${mesin}`
                    },

                    credits: {
                        enabled: false
                    },

                    exporting: {
                        enabled: true
                    },

                    xAxis: {

                        categories: categories,

                        title: {
                            text: 'Department'
                        }

                    },

                    yAxis: {

                        min: 0,

                        title: {
                            text: 'Jumlah Stop'
                        }

                    },

                    tooltip: {
                        shared: true
                    },

                    plotOptions: {

                        column: {

                            borderRadius: 5,

                            dataLabels: {
                                enabled: true
                            }

                        }

                    },

                    series: [{

                        name: 'Jumlah Stop',

                        data: chartData

                    }]

                });

            });

        },

        error: function(xhr){

            console.log(xhr.responseText);

            alert('Gagal mengambil data');

        }

    });

});

</script>

</body>
</html>