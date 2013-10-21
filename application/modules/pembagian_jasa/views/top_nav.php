<style type="text/css">
	.top-nav ul li a {
		height: 57px;
	}
</style>
<div class="top-nav">
	<ul>
		<li>
			<a href="<?php echo site_url('pembagian_jasa/pembagian_jasa'); ?>" <?php echo $top_nav_selected == "Pembagian Jasa" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe022;"></div>Pembagian Jasa
			</a>
		</li>
		<!--li>
			<a href="<?php echo site_url('pembagian_jasa/pembagian_jasa_askes'); ?>" <?php echo $top_nav_selected == "Pembagian Jasa Askes" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe022;"></div>Pembagian Jasa Askes
			</a>
		</li-->
		<li>
			<a href="<?php echo site_url('pembagian_jasa/browse'); ?>" <?php echo $top_nav_selected == "Browse" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe14b;"></div>Daftar Pemb. Jasa
			</a>
		</li>
		<li>
			<a href="<?php echo site_url('pembagian_jasa/pos_kebersamaan'); ?>" <?php echo $top_nav_selected == "Pos Kebersamaan" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe14b;"></div>Pos Kebersamaan
			</a>
		</li>
		<li>
			<a href="<?php echo site_url('pembagian_jasa/pemda'); ?>" <?php echo $top_nav_selected == "Insentif" ? "class=\"selected\"" : ""; ?>>
				<div class="fs1" aria-hidden="true" data-icon="&#xe14b;"></div>Insentif
			</a>
		</li>
	</ul>
	<div class="clearfix"></div>
</div>