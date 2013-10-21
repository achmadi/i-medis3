<htm>
<head>
	<title>Insentif Pemda</title>
</head>
<body>
	<table border="0">
		<body>
			<tr>
				<td colspan="3">INTENSIF PEMDA</td>
			</tr>
			<tr>
				<td colspan="3">BULAN: <?php echo $bulan; ?> <?php echo $tahun; ?></td>
			</tr>
			<tr></tr>
			<tr>
				<td>No.</td>
				<td>Tanggal</td>
				<td>Jumlah</td>
			</tr>
			<?php
				$no = 1;
				foreach ($data as $row) {
			?>
			<tr>
				<td><?php echo $no; ?></td>
				<?php
					$tanggal = strftime( "%d-%m-%Y", strtotime($row->tanggal));
				?>
				<td><?php echo $tanggal; ?></td>
				<td><?php echo $row->jumlah; ?></td>
			</tr>
			<?php
					$no++;
				}
			?>
		</body>
	</table>
</body>
</htm>