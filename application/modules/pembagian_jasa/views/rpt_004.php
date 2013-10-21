<htm>
<head>
	<title>Insentif Tidak Langsung</title>
</head>
<body>
	<table border="0">
		<body>
			<tr>
				<td colspan="6">INTENSIF TAK LANGSUNG</td>
			</tr>
			<tr>
				<td colspan="6">BULAN: <?php echo $bulan; ?> <?php echo $tahun; ?></td>
			</tr>
			<tr></tr>
			<tr>
				<td>No.</td>
				<td>Rekening</td>
				<td>Nama</td>
				<td>Ruang</td>
				<td>Indeks</td>
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
				<td><?php echo $row->unit; ?></td>
				<td><?php echo $row->indeks; ?></td>
				<td><?php echo $row->jasa_tak_langsung; ?></td>
			</tr>
			<?php
					$no++;
				}
			?>
		</body>
	</table>
</body>
</htm>