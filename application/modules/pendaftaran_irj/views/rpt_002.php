<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Jumah Kunjungan Per Cara Bayar</title>
		<style>
		<!--table
		@page {
			margin: .75in .71in .75in .71in;
		}
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
				text-align: left;
				vertical-align: middle;
				border: none;
				white-space: normal;
			}
			.xl81 {
				text-align: left;
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
		<table border="0" cellpadding="0" cellspacing="0" width="620" style="border-collapse: collapse; table-layout: fixed; width: 484pt;">
			<col width="68">
			<col width="222">
			<col width="110">
			<col width="110">
			<col width="110">
			<tr height="80" style="height: 60.0pt">
				<td colspan="5" class="xl76" width="552" style="border-left: none; width: 416pt">
					<strong>
					RSUD DR. SOEDARSO<br>
					Jl. Dr. Soedarso No. 1 Telp. (0561) 737701<br>
					Pontianak, Kalimantan Barat
					</strong>
				</td>
			</tr>
			<tr height=20 style='height:15.0pt'>
				<td height=20 colspan=5 style='height:15.0pt;mso-ignore:colspan'></td>
			</tr>
			<tr height="25" style="height: 18.75pt">
				<td colspan="5" height="25" class="xl91" style="height: 18.75pt">JUMLAH KUNJUNGAN PER CARA BAYAR</td>
			</tr>
			<tr height="20" style="height: 15.0pt">
				<td height="20" colspan="5" style="height: 15.0pt; mso-ignore: colspan"></td>
			</tr>
			<tr height="20" style="height: 15.0pt">
				<td colspan="2" height="20" class="xl92" style="border-right: .5pt solid black; height: 15.0pt">Cara Bayar</td>
				<td class="xl73" style="border-left: none">L</td>
				<td class="xl73" style="border-left: none">P</td>
				<td class="xl73" style="border-left: none">TOTAL</td>
			</tr>
			<?php
				foreach ($data as $row) {
			?>
			<tr height="20" style="height:15.0pt">
				<td colspan="2" height="20" class="xl81" style="border-right: .5pt solid black; height: 15.0pt; padding-left: 2px;"><?php echo $row->cara_bayar; ?></td>
				<td class="xl66" style="border-top: none; border-left: none; padding-right: 2px; text-align: right;"><?php echo $row->l; ?></td>
				<td class="xl66" style="border-top: none; border-left: none; padding-right: 2px; text-align: right;"><?php echo $row->p; ?></td>
				<td class="xl66" style="border-top: none; border-left: none; padding-right: 2px; text-align: right;"><?php echo $row->total; ?></td>
			</tr>
			<?php
				}
			?>
		</table>
	</body>
</html>
