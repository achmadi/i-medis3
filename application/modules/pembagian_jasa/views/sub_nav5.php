<div class="sub-nav">
	<ul>
		<li><a href="" class="heading">Insentif</a></li>
		<li><a href="<?php echo site_url('pembagian_jasa/pemda'); ?>" <?php echo $sub_nav_selected == "Pemda" ? "class=\"selected\"" : ""; ?>>Pemda</a></li>
		<li><a href="<?php echo site_url('pembagian_jasa/manajemen'); ?>" <?php echo $sub_nav_selected == "Manajemen" ? "class=\"selected\"" : ""; ?>>Manajemen</a></li>
		<li><a href="<?php echo site_url('pembagian_jasa/langsung'); ?>" <?php echo $sub_nav_selected == "Langsung" ? "class=\"selected\"" : ""; ?>>Insentif Langsung</a></li>
		<li><a href="<?php echo site_url('pembagian_jasa/tidak_langsung'); ?>" <?php echo $sub_nav_selected == "Tidak Langsung" ? "class=\"selected\"" : ""; ?>>Insentif Tidak Langsung</a></li>
	</ul>
</div>
