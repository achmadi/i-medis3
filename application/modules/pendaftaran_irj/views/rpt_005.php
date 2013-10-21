<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
		<title>Buku Pasien Register</title>
		<style>
		<!--table
		@page
			{margin:.75in .71in .75in .71in;
			mso-header-margin:.31in;
			mso-footer-margin:.31in;
			mso-horizontal-page-align:center;}
		-->
		</style>
		<style>
			td {
				padding: 0px;
				color: black;
				font-size: 11.0pt;
				font-weight: 400;
				font-style: normal;
				text-decoration: none;
				font-family: Calibri, sans-serif;
				text-align: general;
				vertical-align: bottom;
				border: none;
				//white-space: nowrap;
			}
			.xl66 {
				border: .5pt solid windowtext;
			}
			.xl69 {
				font-weight:700;
				font-family:Calibri, sans-serif;
				text-align:center;
				vertical-align:middle;
				border:.5pt solid windowtext;
			}
			.xl73 {
				font-weight: 700;
				font-family: Calibri, sans-serif;
				text-align: center;
				border: .5pt solid windowtext;
			}
			.xl74 {
				text-align: center;
				vertical-align: middle;
				border: 1.0pt solid windowtext;
			}
			.xl75 {
				font-weight:700;
				font-family:Calibri, sans-serif;
				text-align:center;
				vertical-align:middle;
				border:.5pt solid windowtext;
			}
			.xl76 {
				text-align: left;
				vertical-align: middle;
				border: none;
				white-space: normal;
			}
			.xl81 {
				text-align: center;
				border-top: .5pt solid windowtext;
				border-right: none;
				border-bottom: .5pt solid windowtext;
				border-left: .5pt solid windowtext;
			}
			.xl86 {
				font-weight:700;
				font-family:Calibri, sans-serif;
				text-align:center;
				vertical-align:middle;
				border-top:.5pt solid windowtext;
				border-right:none;
				border-bottom:none;
				border-left:.5pt solid windowtext;
			}
			.xl91 {
				font-size: 14.0pt;
				font-weight: 700;
				font-family: Calibri, sans-serif;
				mso-font-charset: 0;
				text-align: center;
			}
			.xl92 {
				font-weight: 700;
				font-family: Calibri, sans-serif;
				text-align: center;
				border-top: .5pt solid windowtext;
				border-right: none;
				border-bottom: .5pt solid windowtext;
				border-left: .5pt solid windowtext;
			}
			.xl100 {
				font-weight:700;
				font-family:Calibri, sans-serif;
				text-align:center;
				vertical-align:middle;
				border-top:.5pt solid windowtext;
				border-right:.5pt solid windowtext;
				border-bottom:none;
				border-left:.5pt solid windowtext;
			}
			.xl104 {
				font-weight:700;
				font-family:Calibri, sans-serif;
				text-align:center;
				vertical-align:middle;
				border-top:.5pt solid windowtext;
				border-right:.5pt solid windowtext;
				border-bottom:none;
				border-left:.5pt solid windowtext;
				white-space:normal;
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
	<body link="blue" vlink="purple">
		<a class="toolbar" href="#" onclick="window.print();return false;">Print</a>
		<table border="0" cellpadding="0" cellspacing="0" width="826" style="border-collapse: collapse;table-layout:fixed;width:620pt">
			<col width="61">
			<col width="12">
			<col width="117">
			<col width="117">
			<col width="69">
			<col width="49">
			<col width="124">
			<col width="47">
			<col width="47">
			<col width="47">
			<col width="47">
			<col width="89">
			<tr height="80" style="height: 60.0pt">
				<td colspan="11" class="xl76" width="753" style="border-left: none; width: 416pt">
					<strong>
					RSUD DR. SOEDARSO<br>
					Jl. Dr. Soedarso No. 1 Telp. (0561) 737701<br>
					Pontianak, Kalimantan Barat
					</strong>
				</td>
			</tr>
			<tr height="20" style="height:15.0pt">
				<td colspan="12"></td>
			</tr>
			<tr height="25" style="height:18.75pt">
				<td colspan="12" height="25" class="xl91" style="height:18.75pt">BUKU PASIEN REGISTER</td>
			</tr>
			<tr height="20" style="height:15.0pt">
				<td colspan="12"></td>
			</tr>
			<tr height="20" style="height:15.0pt">
				<td rowspan="2" height="40" class="xl100" style="border-bottom:.5pt solid black; height:30.0pt">No. RM</td>
				<td colspan="2" rowspan="2" class="xl86" style="border-right:.5pt solid black; border-bottom:.5pt solid black">Nama Pasien</td>
				<td rowspan="2" class="xl100" style="border-bottom:.5pt solid black">Alamat Lengkap</td>
				<td rowspan="2" class="xl100" style="border-bottom:.5pt solid black">Pekerjaan</td>
				<td rowspan="2" class="xl100" style="border-bottom:.5pt solid black">Agama</td>
				<td rowspan="2" class="xl104" width="124" style="border-bottom:.5pt solid black; width:93pt">Pendidikan<br>Terakhir</td>
				<td colspan="2" class="xl69" style="border-left:none">Umur</td>
				<td colspan="2" class="xl69" style="border-left:none">Pengunjung</td>
				<td rowspan="2" class="xl100" style="border-bottom:.5pt solid black">Cara Bayar</td>
			</tr>
			<tr height="20" style="height:15.0pt">
				<td height="20" class="xl75" style="height:15.0pt;border-top:none;border-left: none">L</td>
				<td class="xl75" style="border-top:none;border-left:none">P</td>
				<td class="xl75" style="border-top:none;border-left:none">Baru</td>
				<td class="xl75" style="border-top:none;border-left:none">Lama</td>
			</tr>
			<?php
				foreach ($data as $row) {
			?>
			<tr height="20">
				<td class="xl66" style="border-top:none"><?php echo $row->no_medrec; ?></td>
				<td colspan="2" class="xl66" style="border-right:.5pt solid black;border-left: none"><?php echo $row->nama; ?></td>
				<td class="xl66" style="border-top:none;border-left:none"><?php echo $row->alamat_jalan; ?></td>
				<td class="xl66" style="border-top:none;border-left:none"><?php echo $row->agama; ?></td>
				<td class="xl66" style="border-top:none;border-left:none"><?php echo $row->pendidikan; ?></td>
				<td class="xl66" style="border-top:none;border-left:none"><?php echo $row->pekerjaan; ?></td>
				<td class="xl66" style="border-top:none;border-left:none"><?php echo $row->jenis_kelamin = 'Laki-laki' ? $row->umur_tahun : ''; ?></td>
				<td class="xl66" style="border-top:none;border-left:none"><?php echo $row->jenis_kelamin = 'Perempuan' ? $row->umur_tahun : ''; ?></td>
				<td class="xl66" style="border-top:none;border-left:none"><?php echo $row->jenis_kunjungan = 'Baru' ? 'V' : ''; ?></td>
				<td class="xl66" style="border-top:none;border-left:none"><?php echo $row->jenis_kunjungan = 'Lama' ? 'V' : ''; ?></td>
				<td class="xl66" style="border-top:none;border-left:none"><?php echo $row->cara_bayar; ?></td>
			</tr>
			<?php
				}
			?>
		</table>
	</body>
</html>
