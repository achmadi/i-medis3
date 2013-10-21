<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Print</title>
		<style type="text/css" media="print">
			@page {
				margin: 0;
			}
		</style>
		<style type="text/css" media="screen">
			.toolbar {
				padding: 12px;
				text-align: right;
			}
		</style>
		<style type="text/css" media="print">
			.toolbar {
				display: none;
				visibility: hidden;
			}
		</style>
		<script type="text/javascript">
			function doPrint() {
				window.print();
				window.close();
			}
		</script>
	</head>
	<body>
		<div class="toolbar">
			<a href="#" onclick="doPrint(); return false;">Print</a>
			<hr />
		</div>
<pre>
          KARTU TRACER

No. Antrian : <?php echo $data->no_antrian."\n"; ?>
No. Medrec  : <?php echo $data->no_medrec."\n"; ?>
Nama        : <?php echo $data->nama."\n"; ?>
Cara Bayar  : <?php echo $data->cara_bayar."\n"; ?>
Klinik      : <?php echo $data->poliklinik."\n"; ?>
Dokter      : <?php echo $data->dokter."\n"; ?>
---------------------------------
<?php echo strftime("%d-%m-%Y %H:%M:%S", strtotime($data->tanggal)); ?>
</pre>
	</body>
</html>