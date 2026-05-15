<?PHP

use GuzzleHttp\Psr7\Query;

ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
set_time_limit(0);
?>
<?php
$Awal = isset($_POST['awal']) ? $_POST['awal'] : '';
$Akhir = isset($_POST['akhir']) ? $_POST['akhir'] : '';
$shift = isset($_POST['shift']) ? $_POST['shift'] : '';
$jamA = isset($_POST['jam_awal']) ? $_POST['jam_awal'] : '';
$jamAr = isset($_POST['jam_akhir']) ? $_POST['jam_akhir'] : '';
$demand = isset($_POST['demand']) ? $_POST['demand'] : '';
if (strlen($jamA) == 5) {
	$start_date = $Awal . ' ' . $jamA.':00';
} else {
	$start_date = $Awal . ' 0' . $jamA.':00';
}
if (strlen($jamAr) == 5) {
	$stop_date = $Akhir . ' ' . $jamAr.':00';
} else {
	$stop_date = $Akhir . ' 0' . $jamAr.':00';
}
// $Digit = isset($_POST['DIGIT']) ? $_POST['DIGIT'] : '';

?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <style>
        .table-ringkasan {
            border-color: black !important; 
        }

        .table-ringkasan th,
        .table-ringkasan td {
            border-color: black !important;
        }

        .text-middle{
            vertical-align:middle !important;
        }
    </style>
</head>

<body>
<div>
    <div class="row">
        <div class="col-xs-4">		
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Cari Data Laporan Stenter </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-7">
                                <div class="input-group date">
                                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                                    <input name="awal" type="text" class="form-control pull-right" id="datepicker" placeholder="Tanggal Awal"
                                        value="<?php echo $Awal; ?>" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-sm-5 col-md-4">
								<div class="input-group">
									<input type="text" class="form-control timepicker" name="jam_awal"
										placeholder="00:00" value="<?php echo $jamA; ?>" autocomplete="off">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
								</div>
							</div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-7">
                                <div class="input-group date">
                                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                                    <input name="akhir" type="text" class="form-control pull-right" id="datepicker1"
                                        placeholder="Tanggal Akhir" value="<?php echo $Akhir; ?>" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-sm-5 col-md-4">
								<div class="input-group">
									<input type="text" class="form-control timepicker" name="jam_akhir"
										placeholder="00:00" value="<?php echo $jamAr; ?>" autocomplete="off">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
								</div>
							</div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="demand" 
                                    placeholder="Demand" value="<?php echo $demand; ?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8">
                                <select name="shift" id="shift" class="form-control" placeholder="Shift">
                                    <option value="">Pilih Shift</option>
                                    <option value="ALL" <?=$shift=="ALL"?"selected":"" ;?>>ALL</option>
                                    <option value="A" <?=$shift=="A"?"selected":"" ;?>>A</option>
                                    <option value="B" <?=$shift=="B"?"selected":"" ;?>>B</option>
                                    <option value="C" <?=$shift=="C"?"selected":"" ;?>>C</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-success" name="cari"><i class="fa fa-search"></i> Cari Data</button>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xs-8">
            <div class="box box-primary">
                <div class="box-header with-border text-center">
                    <h3 class="box-title "> Laporan Inspect Stenter</h3>
                    <?php if ($Awal != "") { ?>
                        <!-- <div class="pull-right">
                            <a href="pages/cetak/excel-rangkuman-inspeksi-stenter.php?awal=<?php echo $_POST['awal']; ?>&akhir=<?php echo $_POST['akhir']; ?>&dept=<?php echo $_POST['dept']; ?>&shift=<?php echo $_POST['shift']; ?>&gshift=<?php echo $_POST['gshift']; ?>&proses=<?php echo $_POST['proses']; ?>&buyer=<?php echo $_POST['buyer']; ?>&jam_awal=<?php echo $_POST['jam_awal']; ?>&jam_akhir=<?php echo $_POST['jam_akhir']; ?>"
                                class="btn btn-primary <?php if ($_POST['awal'] == "") {
                                    echo "disabled";
                                } ?>" target="_blank">Rangkuman Excel</a>
                        </div> -->
                    <?php } ?>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?php
                    $bgcolor="#4b95d6";
                ?>
                <table class="table table-bordered table-striped" style="width: 100%">
                    <thead class="bg-blue">
                        <tr >
                            <th class="text-middle text-center" rowspan="2">
                                Shift
                            </th>
                            <th class="text-middle text-center" colspan="2">
                                FINAL
                            </th>
                            <th class="text-middle text-center" rowspan="2">
                                Dalam Proses (Oven,<br> Fin1x, Preset, FIN + CP)
                            </th>
                        </tr>
                        <tr valign="center" >
                            <th class="text-middle text-center" >
                                OK
                            </th>
                            <th class="text-middle text-center" >
                                Reject
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($shift == "ALL" || $shift == "") {
                            $stanter_shift = " ";
                        } else {
                            $stanter_shift = " l.shift='$shift' AND ";
                        }
                        $data_stenter=array();
                        $data_stenter_total=array();
                        $query_lap="SELECT
                                l.shift,
                                SUM(CASE
									WHEN l.proses = 'Fin Jadi' AND l.status = 'OK' THEN l.bruto
									ELSE 0
								END) as sts_total_ok,
                                SUM(CASE
									WHEN l.proses = 'Fin Jadi' AND l.status = 'Reject' THEN l.bruto
									ELSE 0
								END) as sts_total_reject,
                                SUM(CASE
									WHEN l.proses <> 'Fin Jadi' THEN l.bruto
									ELSE 0
								END) as dalam_proses
                            FROM
                                db_qc.tbl_lap_stenter l
                            WHERE 
                                $stanter_shift
                                l.tanggal_buat BETWEEN '$start_date' AND '$stop_date'
                            GROUP BY l.shift
                            ORDER BY l.shift";
                        $q_lap_stenter = sqlsrv_query($con_db_qc_sqlsrv, $query_lap);
                        while ($rowSt = sqlsrv_fetch_array($q_lap_stenter,SQLSRV_FETCH_ASSOC)) {
                            $data_stenter[$rowSt['shift']]['sts_total_ok']=$rowSt['sts_total_ok'];
                            $data_stenter[$rowSt['shift']]['sts_total_reject']=$rowSt['sts_total_reject'];
                            $data_stenter[$rowSt['shift']]['dalam_proses']=$rowSt['dalam_proses'];
                            $data_stenter_total['sts_total_ok']=floatval($rowSt['sts_total_ok']) +floatval($data_stenter_total['sts_total_ok']);
                            $data_stenter_total['sts_total_reject']=floatval($rowSt['sts_total_reject']) +floatval($data_stenter_total['sts_total_reject']);
                            $data_stenter_total['dalam_proses']=floatval($rowSt['dalam_proses']) +floatval($data_stenter_total['dalam_proses']);
                        }
                        foreach($data_stenter as $is => $vs){
                            ?>
                            <tr valign="center" >
                                <td align="center">
                                    <?php echo ucfirst($is); ?>
                                </td>
                                <td align="center">
                                    <?php echo number_format($data_stenter[$is]['sts_total_ok'],2); ?>
                                </td>
                                <td align="center">
                                    <?php echo number_format($data_stenter[$is]['sts_total_reject'],2); ?>
                                </td>
                                <td align="center">
                                    <?php echo number_format($data_stenter[$is]['dalam_proses'],2); ?>
                                </td>
                            </tr>
                            <?php
                        }
                        $percent_ok=0;
                        $percent_reject=0;
                        if ($Awal != "") {
                            $tot_sts_stanter=floatval($data_stenter_total['sts_total_ok'])+floatval($data_stenter_total['sts_total_reject']);
                            if($tot_sts_stanter != 0){
                                $percent_ok=((floatval($data_stenter_total['sts_total_ok']) / $tot_sts_stanter) * 100);
                                $percent_reject=((floatval($data_stenter_total['sts_total_reject']) / $tot_sts_stanter) * 100);
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot class="bg-blue">
                        <tr valign="center">
                            <td align="center">Total</td>
                            <td align="center">
                                <?php echo number_format($data_stenter_total['sts_total_ok'], 2); ?>
                            </td>
                            <td align="center">
                                <?php echo number_format($data_stenter_total['sts_total_reject'], 2); ?>
                            </td>
                            <td align="center">
                                <?php echo number_format($data_stenter_total['dalam_proses'], 2); ?>
                            </td>
                        </tr>
                        <tr valign="top" >
                            <td align="center">Persentase</td>
                            <td align="center">
                                <?php echo number_format($percent_ok, 2); ?>
                            </td>
                            <td align="center">
                                <?php echo number_format($percent_reject, 2); ?>
                            </td>
                            <td align="center">&nbsp;</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Report Inspect Stenter</h3><br>
                    <b>Tanggal Inspeksi :
                        <?php echo $start_date; ?> s.d.
                        <?php echo $stop_date; ?>
                        <?php echo $_POST['shift']; ?>
                    </b> <br><br>
                    <div class="pull-right">
                        <a href="pages/cetak/grafik_ulang_stenter.php?awal=<?php echo $_POST['awal']; ?>&akhir=<?php echo $_POST['akhir']; ?>&shift=<?php echo $_POST['gshift']; ?>&order=<?php echo $_POST['order']; ?>" class="btn btn-primary <?php if($_POST['awal']=="") { echo "disabled"; }?>" target="_blank">Cetak</a>
                    </div>
                </div>
                <?php
                $conditions = [];
                $params = [];

                $conditions[] = "tanggal_buat BETWEEN ? AND ?";
                $params[] = $start_date;
                $params[] = $stop_date;

                if ($shift !== 'ALL' && !empty($shift)) {
                    $conditions[] = "shift = ?";
                    $params[] = $shift;
                }

                if (!empty($demand)) {
                    $conditions[] = "nodemand = ?";
                    $params[] = $demand;
                }

                $whereClause = "";
                if (count($conditions) > 0) {
                    $whereClause = " WHERE " . implode(" AND ", $conditions);
                }

                $qryb = "SELECT *,CONVERT(VARCHAR(19),tanggal_buat) tanggal_buat
                        FROM db_qc.tbl_lap_stenter 
                        $whereClause 
                        ORDER BY id DESC";

                // echo "<pre>";
                // print_r($qryb);
                // echo "</pre>";

                $stmt1 = sqlsrv_query($con_db_qc_sqlsrv, $qryb, $params);

                if ($stmt1 === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                if ($stmt1) {
                ?>
                    <div class="box-body">
                        <table id="example3" class="table table-bordered table-hover table-striped display nowrap" width="100%">
                            <thead class="bg-blue">
                                <tr>
                                    <th>No</th>
                                    <th>No KK</th>
                                    <th>No Demand</th>
                                    <th>Langganan</th>
                                    <th>Buyer</th>
                                    <th>No Order</th>
                                    <th>Jenis Kain</th>
                                    <th>Warna</th>
                                    <th>No MC</th>
                                    <th>Bruto</th>
                                    <th>Roll</th>
                                    <th>No Hanger</th>
                                    <th>No Item</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                    <th>No PO</th>
                                    <th>Lebar</th>
                                    <th>Gramasi</th>
                                    <th>Operator</th>
                                    <th>No Warna</th>
                                    <th>Proses</th>
                                    <th>Gerobak</th>
                                    <th>Shift</th>
                                    <th>Tanggal Buat</th>
                                </tr>


                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = sqlsrv_fetch_array($stmt1,SQLSRV_FETCH_ASSOC)) {
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $row['nokk']; ?></td>
                                        <td><?php echo $row['nodemand']; ?></td>
                                        <td><?php echo $row['langganan']; ?></td>
                                        <td><?php echo $row['buyer']; ?></td>
                                        <td><?php echo $row['no_order']; ?></td>
                                        <td><?php echo $row['jenis_kain']; ?></td>
                                        <td><?php echo $row['warna']; ?></td>
                                        <td><?php echo $row['no_mc']; ?></td>
                                        
                                        <td><a href="#" class="bruto-inspect-stenter-editable"
                                                data-type="text"
                                                data-pk="<?= htmlspecialchars($row['id']) ?>"
                                                data-value="<?= htmlspecialchars($row['bruto']) ?>"
                                                data-url="pages/editable/inspect_stenter/editable_bruto.php"
                                                data-title="Pilih ACC Resep">
                                                <?= htmlspecialchars($row['bruto']) ?>
                                            </a>
                                        </td>
                                        <td><a href="#" class="roll-inspect-stenter-editable"
                                                data-type="text"
                                                data-pk="<?= htmlspecialchars($row['id']) ?>"
                                                data-value="<?= htmlspecialchars($row['roll']) ?>"
                                                data-url="pages/editable/inspect_stenter/editable_roll.php"
                                                data-title="Pilih ACC Resep">
                                                <?= htmlspecialchars($row['roll']) ?>
                                            </a>
                                        </td>
                                        <td><?php echo $row['no_hanger']; ?></td>
                                        <td><?php echo $row['no_item']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                        <td><?php echo $row['catatan']; ?></td>
                                        <!-- <td>
                                            <?php if (!empty($row['catatan'])) { ?>
                                                <a data-pk="<?php echo $row['id']; ?>" data-value="<?php echo $row['catatan']; ?>" class="edit-catatan"
                                                    href="javascript:void(0)">
                                                    <?php echo $row['catatan']; ?>
                                                </a>
                                            <?php } else { ?>
                                                <a data-pk="<?php echo $row['id']; ?>" data-value="No Catatan" class="edit-catatan"
                                                    href="javascript:void(0)">
                                                    No Catatan
                                                </a>
                                            <?php } ?>
                                        </td> -->
                                        <td><?php echo $row['no_po']; ?></td>
                                        <td><?php echo $row['lebar']; ?></td>
                                        <td><?php echo $row['gramasi']; ?></td>
                                        <td><?php echo $row['operator']; ?></td>
                                        <td><?php echo $row['no_warna']; ?></td>
                                        <td><?php echo $row['proses']; ?></td>
                                        <td><a href="#" class="gerobak-inspect-stenter-editable"
                                                data-type="text"
                                                data-pk="<?= htmlspecialchars($row['id']) ?>"
                                                data-value="<?= htmlspecialchars($row['gerobak']) ?>"
                                                data-url="pages/editable/inspect_stenter/editable_gerobak.php"
                                                data-title="Pilih ACC Resep">
                                                <?= htmlspecialchars($row['gerobak']) ?>
                                            </a>
                                        </td>
                                        <td><?php echo $row['shift']; ?></td>
                                        <td><?php echo $row['tanggal_buat']; ?></td>
                                    </tr>
                                <?php
                                    $no++;
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                <?php
                } else {
                    echo "Query execution failed: " . sqlserver_errors();
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>

</html>