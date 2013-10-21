<script type="text/javascript">
	$(document).ready(function () {
		
		//Tiny Scrollbar
		$('#scrollbar').tinyscrollbar();
		$('#scrollbar-one').tinyscrollbar();
		$('#scrollbar-two').tinyscrollbar();
		$('#scrollbar-three').tinyscrollbar();

	});
</script>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">Quick Access</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="widget-body">
					<div class="row-fluid">
						<div class="metro-nav">
							<div class="metro-nav-block nav-block-blue double">
								<a href="<?php echo site_url('pendaftaran_irj'); ?>" >
									<div class="fs1" aria-hidden="true" data-icon="&#xe096;"></div>
									<div class="brand">Pendaftaran RWJ</div>
								</a>
							</div>
							<div class="metro-nav-block nav-block-red double">
								<a href="<?php echo site_url('pendaftaran_igd'); ?>" >
									<div class="fs1" aria-hidden="true" data-icon="&#xe14a;"></div>
									<div class="brand">Pendaftaran IGD</div>
								</a>
							</div>
							<div class="metro-nav-block nav-block-green">
								<a href="<?php echo site_url('tp2ri/pendaftaran'); ?>" data-original-title="">
									<div class="fs1" data-icon="&#xe00d;" aria-hidden="true"></div>
									<div class="brand">Pendaftaran RWI</div>
								</a>
							</div>
							<div class="metro-nav-block nav-block-red">
								<a href="<?php echo site_url('rawat_inap'); ?>" data-original-title="">
									<div class="fs1" data-icon="&#xe00d;" aria-hidden="true"></div>
									<div class="brand">Penata Jasa</div>
								</a>
							</div>
							<div class="metro-nav-block nav-block-blue double">
								<a href="<?php echo site_url('laboratorium/pendaftaran'); ?>" data-original-title="">
									<div class="fs1" data-icon="&#xe00d;" aria-hidden="true"></div>
									<div class="brand">Laboratorium</div>
								</a>
							</div>
							<div class="metro-nav-block nav-block-green double">
								<a href="<?php echo site_url('radiologi/pendaftaran'); ?>" data-original-title="">
									<div class="fs1" data-icon="&#xe00d;" aria-hidden="true"></div>
									<div class="brand">Radiologi</div>
								</a>
							</div>
							<div class="metro-nav-block nav-block-green double">
								<a href="<?php echo site_url('kasir'); ?>" data-original-title="">
									<div class="fs1" data-icon="&#xe052;" aria-hidden="true"></div>
									<div class="brand">Kasir</div>
								</a>
							</div>
							<div class="metro-nav-block nav-block-red">
								<a href="<?php echo site_url('gudang/penerimaan'); ?>" data-original-title="">
									<div class="fs1" data-icon="&#xe052;" aria-hidden="true"></div>
									<div class="brand">Gudang Farmasi</div>
								</a>
							</div>
							<div class="metro-nav-block nav-block-blue double">
								<a href="<?php echo base_url('medrec'); ?>" data-original-title="">
									<div class="fs1" data-icon="&#xe052;" aria-hidden="true"></div>
									<div class="brand">Medrec</div>
								</a>
							</div>
							<div class="metro-nav-block nav-block-orange double">
								<a href="<?php echo site_url('pembagian_jasa'); ?>" data-original-title="">
									<div class="fs1" data-icon="&#xe00d;" aria-hidden="true"></div>
									<div class="brand">Pembagian Jasa</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="right-sidebar">
	<div class="wrapper">
		<div id="scrollbar-two">
			<div class="scrollbar">
				<div class="track">
					<div class="thumb">
						<div class="end"></div>
					</div>
				</div>
			</div>
			<div class="viewport">
				<div class="overview">
					<div class="featured-articles-container">
						<h5 class="heading-blue">Master</h5>
						<div class="articles">
							<a href="<?php echo site_url('auth/module'); ?>"><span class="label-bullet-blue">&nbsp;</span>Module</a>
							<a href="<?php echo site_url('auth/role'); ?>"><span class="label-bullet-blue">&nbsp;</span>Role</a>
							<a href="<?php echo site_url('auth/user'); ?>"><span class="label-bullet-blue">&nbsp;</span>Users</a>
							<a href="<?php echo site_url('master/pasien'); ?>"><span class="label-bullet-blue">&nbsp;</span>Pasien</a>
							<a href="<?php echo site_url('master/wilayah'); ?>"><span class="label-bullet-blue">&nbsp;</span>Wilayah</a>
							<a href="<?php echo site_url('master/agama'); ?>"><span class="label-bullet-blue">&nbsp;</span>Agama</a>
							<a href="<?php echo site_url('master/pendidikan'); ?>"><span class="label-bullet-blue">&nbsp;</span>Pendidikan</a>
							<a href="<?php echo site_url('master/pekerjaan'); ?>"><span class="label-bullet-blue">&nbsp;</span>Pekerjaan</a>
							<a href="<?php echo site_url('master/rujukan'); ?>"><span class="label-bullet-blue">&nbsp;</span>Rujukan</a>
							<a href="<?php echo site_url('master/cara_bayar'); ?>"><span class="label-bullet-blue">&nbsp;</span>Cara Pembayaran</a>
							<a href="<?php echo site_url('master/unit'); ?>"><span class="label-bullet-blue">&nbsp;</span>Unit</a>
							<a href="<?php echo site_url('master/poliklinik'); ?>"><span class="label-bullet-blue">&nbsp;</span>Poliklinik</a>
							<a href="<?php echo site_url('master/golongan_pegawai'); ?>"><span class="label-bullet-blue">&nbsp;</span>Golongan Pegawai</a>
							<a href="<?php echo site_url('master/kelompok_pegawai'); ?>"><span class="label-bullet-blue">&nbsp;</span>Kelompok Pegawai</a>
							<a href="<?php echo site_url('master/pegawai'); ?>"><span class="label-bullet-blue">&nbsp;</span>Pegawai</a>
							<a href="<?php echo site_url('master/kelas'); ?>"><span class="label-bullet-blue">&nbsp;</span>Kelas</a>
							<a href="<?php echo site_url('master/jenis_pelayanan'); ?>"><span class="label-bullet-blue">&nbsp;</span>Jenis Pelayanan</a>
							<a href="<?php echo site_url('master/kelompok_pelayanan_askes'); ?>"><span class="label-bullet-blue">&nbsp;</span>Kelompok Pelayanan Askes</a>
							<a href="<?php echo site_url('master/komponen_tarif_askes'); ?>"><span class="label-bullet-blue">&nbsp;</span>Komponen Tarif Askes</a>
							<a href="<?php echo site_url('master/gedung'); ?>"><span class="label-bullet-blue">&nbsp;</span>Bed Management</a>
							<div class="articles">
							<a href="<?php echo site_url('master/apotik_ref'); ?>"><span class="label-bullet-blue">&nbsp;</span>Apotek</a>
						</div>
						<div class="articles">
							<a href="<?php echo site_url('master/gol_obat'); ?>"><span class="label-bullet-blue">&nbsp;</span>Golongan Obat</a>
						</div>
						<div class="articles">
							<a href="<?php echo site_url('master/master_obat'); ?>"><span class="label-bullet-blue">&nbsp;</span>Obat</a>
						</div>
						<div class="articles">
							<a href="<?php echo site_url('master/subjenis_obat'); ?>"><span class="label-bullet-blue">&nbsp;</span>Sub Jenis Obat</a>
						</div>
						<div class="articles">
							<a href="<?php echo site_url('master/harga_obat'); ?>"><span class="label-bullet-blue">&nbsp;</span>Harga Obat</a>
						</div>
						<div class="articles">
							<a href="<?php echo site_url('master/jenis_obat'); ?>"><span class="label-bullet-blue">&nbsp;</span>Jenis Obat</a>
						</div>
						<div class="articles">
							<a href="<?php echo site_url('master/stock_obat'); ?>"><span class="label-bullet-blue">&nbsp;</span>Stock Obat</a>
						</div>
						<div class="articles">
							<a href="<?php echo site_url('master/lm_unit'); ?>"><span class="label-bullet-blue">&nbsp;</span>Satuan Besar</a>
						</div>
						<div class="articles">
							<a href="<?php echo site_url('master/sm_unit'); ?>"><span class="label-bullet-blue">&nbsp;</span>Satuan Kecil</a>
						</div>
						<div class="articles">
							<a href="<?php echo site_url('master/sfund'); ?>"><span class="label-bullet-blue">&nbsp;</span>Sumber Dana</a>
						</div>
						<div class="articles">
							<a href="<?php echo site_url('master/vendor'); ?>"><span class="label-bullet-blue">&nbsp;</span>Vendor</a>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>