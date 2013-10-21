<htm>
<head>
	<title>Insentif Langsung</title>
</head>
<body>
	<table border="0">
		<body>
			<tr>
				<td colspan="6">INTENSIF LANGSUNG</td>
			</tr>
			<tr>
				<td colspan="6">BULAN: <?php echo $bulan; ?> <?php echo $tahun; ?></td>
			</tr>
			<tr></tr>
			<tr>
				<td>No.</td>
				<td>No. Rekening</td>
				<td>Nama</td>
				<td>Jumlah</td>
			</tr>
			<?php
				$no = 1;
				foreach ($data as $row) {
			?>
			<tr>
				<td><?php echo $no; ?></td>
				<td><?php echo $row->no_rekening; ?></td>
				<td><?php echo $row->nama; ?></td>
				<td><?php echo $row->langsung; ?></td>
			</tr>
			<?php
					$no++;
				}
			?>
		</body>
	</table>
</body>
</htm>