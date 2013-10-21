<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Bank Nomor Rekam Medis</title>
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
				white-space: nowrap;
			}
			.xl66 {
				border: .5pt solid windowtext;
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
			.xl76 {
				text-align: center;
				vertical-align: middle;
				border: 1.0pt solid windowtext;
				white-space: normal;
			}
			.xl81 {
				text-align: center;
				border-top: .5pt solid windowtext;
				border-right: none;
				border-bottom: .5pt solid windowtext;
				border-left: .5pt solid windowtext;
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
		<table border="0" cellpadding="0" cellspacing="0" width="679" style="border-collapse: collapse; table-layout: fixed; width: 510pt;">
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
				<td colspan="12" class="xl98" width="753" style="border-left: none; width: 565pt">
					<strong>
					RSUD DR. SOEDARSO<br>
					Jl. Dr. Soedarso No. 1 Telp. (0561) 737701<br>
					Pontianak, Kalimantan Barat
					</strong>
				</td>
			</tr>
			<tr height=40 style='height:30.0pt;mso-xlrowspan:2'>
				<td height=40 colspan=7 style='height:30.0pt;mso-ignore:colspan'></td>
			</tr>
			<tr height=25 style='height:18.75pt'>
				<td colspan=7 height=25 class=xl91 style='height:18.75pt'><a name="Print_Area">BANK NOMOR REKAM MEDIK</a></td>
			</tr>
			<tr height=20 style='height:15.0pt'>
				<td height=20 colspan=7 style='height:15.0pt;mso-ignore:colspan'></td>
			</tr>
			<tr height="20" style="height: 15.0pt">
				<td class="xl73" style="height: 15.0pt">No. RM</td>
				<td class="xl73" style="border-left: none">Nama Pasien</td>
				<td class="xl73" style="border-left: none">Tgl. Lahir</td>
				<td class="xl73" style="border-left: none">Usia</td>
				<td class="xl73" style="border-left: none">Jenis Kelamin</td>
				<td class="xl73" style="border-left: none">Alamat</td>
				<td class="xl73" style="border-left: none">Kelompok Pasien</td>
			</tr>
			<?php
				foreach ($data as $row) {
			?>
			<tr style="height: 15px;">
				<td class="xl66" style="height:15.0pt;border-top:none"><?php echo $row->no_medrec; ?></td>
				<td class="xl66" style="border-top:none;border-left:none"><?php echo $row->nama; ?></td>
				<td class="xl66" style="border-top:none;border-left:none">&nbsp;</td>
				<td class="xl66" style="border-top:none;border-left:none">&nbsp;</td>
				<td class="xl66" style="border-top:none;border-left:none">&nbsp;</td>
				<td class="xl66" style="border-top:none;border-left:none">&nbsp;</td>
				<td class="xl66" style="border-top:none;border-left:none">&nbsp;</td>
			</tr>
			<?php
				}
			?>
		</table>
	</body>
</html>
