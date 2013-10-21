<div class="sub-nav">
	<ul>
		<li><a href="" class="heading">Rawat Inap</a></li>
		<li><a<?php echo $sub_nav_selected == "Bed" ? " class=\"selected\"" : ""; ?> href="<?php echo site_url('rawat_inap'); ?>">Bed</a></li>
		<li><a<?php echo $sub_nav_selected == "Pasien" ? " class=\"selected\"" : ""; ?> href="<?php echo site_url('rawat_inap/pasien'); ?>">Pasien</a></li>
	</ul>
</div>