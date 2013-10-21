<?php
	switch ($top_nav_selected) {
		case "Pendaftaran":
?>
<div class="sub-nav">
	<ul>
		<li><a href="" class="heading">Pendaftaran IGD</a></li>
	</ul>
</div>
<?php
			break;
		case "Browse":
?>
<div class="sub-nav">
	<ul>
		<li><a href="" class="heading">Daftar Kunjungan Rawat Jalan</a></li>
		<li><a href="<?php echo site_url('pendaftaran_igd/browse/1'); ?>" <?php echo $sub_nav_selected == "Browse1" ? "class=\"selected\"" : ""; ?>>Daftar Kunjungan</a></li>
		<li><a href="<?php echo site_url('pendaftaran_igd/browse/2'); ?>" <?php echo $sub_nav_selected == "Browse2" ? "class=\"selected\"" : ""; ?>>Daftar Kunjungan yang Dibatalkan</a></li>
	</ul>
</div>
<?php
			break;
		case "Laporan":
?>
<div class="sub-nav">
	<ul>
		<li><a href="" class="heading">Laporan Rawat Jalan</a></li>
		<li class="dropdown">
			<a id="drop2" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" data-original-title="">
				<?php
					switch ($laporan) {
						case 'browse_001':
							echo "Kunjungan Per Cara Bayar";
							break;
						case 'browse_002':
							echo "Kunjungan Per Wilayah";
							break;
						case 'browse_003':
							echo "Kunjungan Per Cara Kunjungan";
							break;
						case 'browse_004':
							echo "Buku Pasien Register";
							break;
						case 'browse_005':
							echo "Kartu Rekam Medis";
							break;
						case 'browse_006':
							echo "Bank Nomor Rekam Medik";
							break;
					}
				?>
				<b class="caret"> </b>
			</a>
			<ul class="dropdown-menu">
				<li>
					<a tabindex="-1" href="<?php echo site_url('pendaftaran_irj/laporan/browse_001'); ?>" data-original-title="">Laporan Jumlah Kunjungan Per Kelompok Pasien</a>
				</li>
				<li>
					<a tabindex="-1" href="<?php echo site_url('pendaftaran_irj/laporan/browse_002'); ?>" data-original-title="">Laporan Jumlah Kunjungan Per Wilayah</a>
				</li>
				<li>
					<a tabindex="-1" href="<?php echo site_url('pendaftaran_irj/laporan/browse_003'); ?>" data-original-title="">Laporan Jumlah Kunjungan Per Cara Kunjungan</a>
				</li>
				<li>
					<a tabindex="-1" href="<?php echo site_url('pendaftaran_irj/laporan/browse_004'); ?>" data-original-title="">Laporan Buku Register</a>
				</li>
				<li>
					<a tabindex="-1" href="<?php echo site_url('pendaftaran_irj/laporan/browse_005'); ?>" data-original-title="">Laporan detail indeks kunjungan per Pendidikan, Pekerjaan</a>
				</li>
				<li>
					<a tabindex="-1" href="<?php echo site_url('pendaftaran_irj/laporan/browse_006'); ?>" data-original-title="">Laporan pasien rujukan per perujuk</a>
				</li>
				<li>
					<a tabindex="-1" href="<?php echo site_url('pendaftaran_irj/laporan/browse_006'); ?>" data-original-title="">Laporan pendaftaran yang dibatalkan</a>
				</li>
			</ul>
		</li>
	</ul>
</div>
<?php
			break;
	}
?>