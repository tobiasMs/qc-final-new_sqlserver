<!DOCTYPE html>
<html>
<head>

    <title>Grafik Stop Mesin</title>

    <meta charset="utf-8">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="../../dist/js/highchart/highcharts.js"></script>
    <script src="../../dist/js/highchart/exporting.js"></script>

    <style>

        body{
            font-family: Arial, sans-serif;
            margin:20px;
            background:#f5f5f5;
        }

        /* BUTTON */
        .btn{
            padding:10px 20px;
            border:none;
            border-radius:5px;
            color:white;
            cursor:pointer;
            font-weight:bold;
        }

        .btn-primary{
            background:#007bff;
        }

        .btn-success{
            background:#28a745;
        }

        /* SUMMARY */
        .row-item{
            display:flex;
            gap:20px;
            align-items:flex-start;
            margin-bottom:25px;
        }

        .chart-section{
            flex:2;
            background:#fff;
            border-radius:10px;
            padding:15px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        .table-section{
            flex:1;
            background:#fff;
            border-radius:10px;
            padding:15px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
        }

        .table-title{
            font-size:18px;
            font-weight:bold;
            margin-bottom:15px;
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

        /* SUMMARY MESIN */
        .summary-mesin-container{
            background:#fff;
            border-radius:10px;
            padding:20px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
            margin-bottom:30px;
        }

        .summary-mesin-title{
            font-size:20px;
            font-weight:bold;
            margin-bottom:20px;
        }

        #summaryMesinChart{
            width:100%;
            height:500px;
        }

        /* DETAIL */
        .wrapper{
            display:flex;
            flex-direction:column;
            gap:30px;
        }

        @media(max-width:768px){

            .row-item{
                flex-direction:column;
            }

        }

    </style>

</head>
<body>

<!-- BUTTON -->
<div style="margin-bottom:20px;">

    <button id="btnDetail" class="btn btn-primary">
        Lihat Detail
    </button>

    <button id="btnSummary"
        class="btn btn-success"
        style="display:none;">
        Kembali Summary
    </button>

</div>

<!-- ===================== -->
<!-- SUMMARY VIEW -->
<!-- ===================== -->

<div id="summaryView">

    <!-- SUMMARY UTAMA -->
    <div class="row-item">

        <!-- CHART -->
        <div class="chart-section">

            <div id="summaryChart" style="height:500px;"></div>

        </div>

        <!-- TABLE -->
        <div class="table-section">

            <div class="table-title">
                Rekap Jumlah Stop
            </div>

            <table>

                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Total Stop</th>
                    </tr>
                </thead>

                <tbody id="summaryTable"></tbody>

            </table>

        </div>

    </div>

    <!-- SUMMARY MESIN -->
    <div class="summary-mesin-container">

        <div class="summary-mesin-title">
            Summary Total Stop Per Mesin
        </div>

        <div id="summaryMesinChart"></div>

    </div>

</div>

<!-- ===================== -->
<!-- DETAIL VIEW -->
<!-- ===================== -->

<div id="detailView" style="display:none;">

    <div class="wrapper" id="wrapperData"></div>

</div>

<script>

$(document).ready(function(){

    // PARAMETER URL
    const urlParams = new URLSearchParams(window.location.search);

    const tgl_awal  = urlParams.get('awal');
    const tgl_akhir = urlParams.get('akhir');

    // LOAD DATA
    $.ajax({

        url: '../ajax/inspectStenter/ajax_grafik_stenter.php',

        type: 'GET',

        dataType: 'json',

        data: {
            tgl_awal : tgl_awal,
            tgl_akhir : tgl_akhir
        },

        success: function(res){

            buildSummary(res);

            buildDetail(res);

        },

        error: function(xhr){

            console.log(xhr.responseText);

            alert('Gagal mengambil data');

        }

    });

    // DETAIL BUTTON
    $('#btnDetail').click(function(){

        $('#summaryView').hide();

        $('#detailView').show();

        $('#btnDetail').hide();

        $('#btnSummary').show();

    });

    // SUMMARY BUTTON
    $('#btnSummary').click(function(){

        $('#summaryView').show();

        $('#detailView').hide();

        $('#btnDetail').show();

        $('#btnSummary').hide();

    });

});


// =====================================
// SUMMARY
// =====================================

function buildSummary(res){

    let deptTotal = {};

    let mesinTotal = {};

    res.forEach(function(item){

        let dept  = item.dept;
        let mesin = item.no_mesin;
        let total = parseInt(item.total_stop);

        // TOTAL DEPT
        if(!deptTotal[dept]){
            deptTotal[dept] = 0;
        }

        deptTotal[dept] += total;

        // TOTAL MESIN
        if(!mesinTotal[mesin]){
            mesinTotal[mesin] = 0;
        }

        mesinTotal[mesin] += total;

    });

    // =========================
    // SUMMARY TABLE + CHART
    // =========================

    let categories = [];

    let dataChart = [];

    let rows = '';

    for(let dept in deptTotal){

        categories.push(dept);

        dataChart.push(deptTotal[dept]);

        rows += `
            <tr>
                <td>${dept}</td>
                <td>${deptTotal[dept]}</td>
            </tr>
        `;

    }

    $('#summaryTable').html(rows);

    // HIGHCHART SUMMARY
    Highcharts.chart('summaryChart', {

        chart: {
            type: 'column'
        },

        title: {
            text: 'Grafik Jumlah Stop Mesin Per Department'
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

            data: dataChart

        }]

    });

    // =========================
    // SUMMARY MESIN CHART
    // =========================

    let mesinCategories = [];
    let mesinChartData = [];

    for(let mesin in mesinTotal){
        mesinCategories.push(mesin);
        mesinChartData.push(mesinTotal[mesin]);
    }

    Highcharts.chart('summaryMesinChart', {

        chart: {
            type: 'bar'
        },

        title: {
            text: 'Summary Total Stop Per Mesin'
        },

        credits: {
            enabled: false
        },

        exporting: {
            enabled: true
        },

        xAxis: {

            categories: mesinCategories,

            title: {
                text: 'Mesin'
            }

        },

        yAxis: {

            min: 0,

            title: {
                text: 'Total Stop'
            }

        },

        plotOptions: {

            bar: {

                dataLabels: {
                    enabled: true
                }

            }

        },

        series: [{

            name: 'Total Stop',

            data: mesinChartData

        }]

    });

}



// =====================================
// DETAIL
// =====================================

function buildDetail(res){

    let mesinData = {};

    // GROUP DATA
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

    $('#wrapperData').html('');

    // LOOP MESIN
    for(let mesin in mesinData){

        $('#wrapperData').append(`

            <div class="row-item">

                <!-- CHART -->
                <div class="chart-section">

                    <div id="chart_dept_${mesin}" style="height:350px;"></div>

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

                        <tbody id="table_${mesin}"></tbody>

                    </table>

                </div>

            </div>

        `);

        let categories = [];

        let chartData = [];

        let tableRows = '';

        // LOOP DETAIL
        mesinData[mesin].forEach(function(val){

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
        $(`#table_${mesin}`).html(tableRows);

        // =========================
        // CHART DEPT
        // =========================

        Highcharts.chart(`chart_dept_${mesin}`, {

            chart: {
                type: 'column'
            },

            title: {
                text: `Stop Per Department - ${mesin}`
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

    }

}

</script>

</body>
</html>