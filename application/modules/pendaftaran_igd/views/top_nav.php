<style type="text/css">
	.top-nav ul li a {
		height: 57px;
	}
</style>
<div class="top-nav">
	<ul>
		<li>
			<a href="<?php echo site_url('pendaftaran_igd'); ?>" <?php echo $top_nav_selected == "Pendaftaran" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe022;"></div>Pendaftaran IGD
			</a>
		</li>
		<li>
			<a href="<?php echo site_url('pendaftaran_igd/browse/1'); ?>" <?php echo $top_nav_selected == "Browse" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe14b;"></div>Daftar Kunjungan
			</a>
		</li>
		<li>
			<a href="<?php echo site_url('pendaftaran_igd/laporan'); ?>" <?php echo $top_nav_selected == "Laporan" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe14b;"></div>Laporan IGD
			</a>
		</li>
	</ul>
	<div class="clearfix"></div>
</div>