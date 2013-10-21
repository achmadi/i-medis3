<?php
	switch ($top_nav_selected) {
		case "Pendaftaran":
?>
<div class="sub-nav">
	<ul>
		<li><a href="" class="heading">Pendaftaran Rawat Inap</a></li>
	</ul>
</div>
<?php
			break;
		case "Browse":
?>
<div class="sub-nav">
	<ul>
		<li><a href="" class="heading">Daftar Kunjungan Rawat Inap</a></li>
		<li><a href="<?php echo site_url('tp2ri/pendaftaran/browse/1'); ?>" <?php echo $sub_nav_selected == "Browse1" ? "class=\"selected\"" : ""; ?>>Daftar Kunjungan</a></li>
		<li><a href="<?php echo site_url('tp2ri/pendaftaran/browse/2'); ?>" <?php echo $sub_nav_selected == "Browse2" ? "class=\"selected\"" : ""; ?>>Daftar Kunjungan yang Dibatalkan</a></li>
	</ul>
</div>
<?php
			break;
	}
?>