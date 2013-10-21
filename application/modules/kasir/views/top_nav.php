<style type="text/css">
	.top-nav ul li a {
		height: 57px;
	}
</style>
<div class="top-nav">
	<ul>
		<li>
			<a href="<?php echo site_url('kasir/kasir'); ?>" <?php echo $top_nav_selected == "Kasir" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe022;"></div>Kasir
			</a>
		</li>
		<li>
			<a href="<?php echo site_url('kasir/laporan_kasir_rj'); ?>" <?php echo $top_nav_selected == "Laporan Kasir Rawat Jalan" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe14b;"></div>Laporan Kasir RJ
			</a>
		</li>
		<li>
			<a href="<?php echo site_url('kasir/laporan_kasir_igd'); ?>" <?php echo $top_nav_selected == "Laporan Kasir IGD" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe14b;"></div>Laporan Kasir IGD
			</a>
		</li>
		<li>
			<a href="<?php echo site_url('kasir/laporan_kasir_ri'); ?>" <?php echo $top_nav_selected == "Laporan Kasir Rawat Inap" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe14b;"></div>Laporan Rawat Inap
			</a>
		</li>
	</ul>
	<div class="clearfix"></div>
</div>