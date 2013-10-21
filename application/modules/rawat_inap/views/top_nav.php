<style type="text/css">
	.top-nav ul li a {
		height: 57px;
	}
</style>
<div class="top-nav">
	<ul>
		<li>
			<a href="<?php echo site_url('rawat_inap/rawat_inap'); ?>" <?php echo $top_nav_selected == "Rawat Inap" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe022;"></div>Bed
			</a>
		</li>
		<li>
			<a href="<?php echo site_url('rawat_inap/browse_konfirmasi'); ?>" <?php echo $top_nav_selected == "Konfirmasi" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe022;"></div>Konfirmasi
			</a>
		</li>
		<li>
			<a href="<?php echo site_url('rawat_inap/laporan'); ?>" <?php echo $top_nav_selected == "Laporan" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe14b;"></div>Laporan
			</a>
		</li>
	</ul>
	<div class="clearfix"></div>
</div>