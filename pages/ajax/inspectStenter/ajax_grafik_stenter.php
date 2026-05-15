<?php
include "../../../koneksi.php";

$tgl_awal  = $_GET['tgl_awal'];
$tgl_akhir = $_GET['tgl_akhir'];

$query = " SELECT
                x.no_mesin,
                x.dept,
                SUM(x.total_stop) AS total_stop
            FROM (

                SELECT
                    CONCAT(SUBSTRING(LTRIM(RTRIM(no_mc)), LEN(LTRIM(RTRIM(no_mc))) - 5 + 1, 2),
                    SUBSTRING(LTRIM(RTRIM(no_mc)), LEN(LTRIM(RTRIM(no_mc))) - 2 + 1, 2)) AS no_mesin,
                    f1.nama AS dept,
                    t.jml_stop1 AS total_stop
                FROM db_qc.tbl_lap_stenter s
                LEFT JOIN db_qc.tbl_mesin_stop_stenter t
                    ON t.id_lap_stenter = s.id
                LEFT JOIN db_qc.filter_dept f1
                    ON f1.id = t.dept_mesin_stop1
                WHERE s.tanggal_buat BETWEEN '$tgl_awal' AND '$tgl_akhir'

                UNION ALL

                SELECT
                    CONCAT(SUBSTRING(LTRIM(RTRIM(no_mc)), LEN(LTRIM(RTRIM(no_mc))) - 5 + 1, 2),
                    SUBSTRING(LTRIM(RTRIM(no_mc)), LEN(LTRIM(RTRIM(no_mc))) - 2 + 1, 2)) AS no_mesin,
                    f2.nama AS dept,
                    t.jml_stop2 AS total_stop
                FROM db_qc.tbl_lap_stenter s
                LEFT JOIN db_qc.tbl_mesin_stop_stenter t
                    ON t.id_lap_stenter = s.id
                LEFT JOIN db_qc.filter_dept f2
                    ON f2.id = t.dept_mesin_stop2
                WHERE s.tanggal_buat BETWEEN '$tgl_awal' AND '$tgl_akhir'

                UNION ALL

                SELECT
                    CONCAT(SUBSTRING(LTRIM(RTRIM(no_mc)), LEN(LTRIM(RTRIM(no_mc))) - 5 + 1, 2),
                    SUBSTRING(LTRIM(RTRIM(no_mc)), LEN(LTRIM(RTRIM(no_mc))) - 2 + 1, 2)) AS no_mesin,
                    f3.nama AS dept,
                    t.jml_stop3 AS total_stop
                FROM db_qc.tbl_lap_stenter s
                LEFT JOIN db_qc.tbl_mesin_stop_stenter t
                    ON t.id_lap_stenter = s.id
                LEFT JOIN db_qc.filter_dept f3
                    ON f3.id = t.dept_mesin_stop3
                WHERE s.tanggal_buat BETWEEN '$tgl_awal' AND '$tgl_akhir'

                UNION ALL

                SELECT
                    CONCAT(SUBSTRING(LTRIM(RTRIM(no_mc)), LEN(LTRIM(RTRIM(no_mc))) - 5 + 1, 2),
                    SUBSTRING(LTRIM(RTRIM(no_mc)), LEN(LTRIM(RTRIM(no_mc))) - 2 + 1, 2)) AS no_mesin,
                    f4.nama AS dept,
                    t.jml_stop4 AS total_stop
                FROM db_qc.tbl_lap_stenter s
                LEFT JOIN db_qc.tbl_mesin_stop_stenter t
                    ON t.id_lap_stenter = s.id
                LEFT JOIN db_qc.filter_dept f4
                    ON f4.id = t.dept_mesin_stop4
                WHERE s.tanggal_buat BETWEEN '$tgl_awal' AND '$tgl_akhir'

            ) x
            WHERE 
             x.dept IS NOT NULL
            AND x.dept <> ''
            AND x.total_stop IS NOT NULL
            AND x.total_stop > 0
            GROUP BY x.no_mesin, x.dept
            ORDER BY total_stop DESC";

$result = sqlsrv_query($con_db_qc_sqlsrv, $query);

if ($result === false) {
    die(json_encode(array("error" => sqlsrv_errors())));
}

$data = [];
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>