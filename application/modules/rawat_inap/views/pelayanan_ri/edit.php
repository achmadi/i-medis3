<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#tindakan').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('rawat_inap/pelayanan_ri/load_data_tindakan'); ?>",
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 4 ] }
								  ],
			"aoColumns"			: [
									  { sWidth: '20%' },
									  { sWidth: '20%' },
									  { sWidth: '20%' },
									  { sWidth: '20%' },
									  { sWidth: '20%' }
								  ],
		});
		
	});

</script>
<style type="text/css">
	.dashboard-wrapper {
		margin-bottom: 10px;
		padding: 10px;
	}
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	.dashboard-wrapper .left-sidebar .widget {
		margin-bottom: 0;
	}
	.dashboard-wrapper .left-sidebar .widget .widget-header {
		padding: 5px;
	}
	.dashboard-wrapper .left-sidebar .widget .widget-body {
		border-bottom: 0;
	}
	.form-actions {
		margin-bottom: 0;
		margin-top: 0;
		padding: 5px;
	}
	.form-horizontal .control-group {
		margin-bottom: 4px;
	}
	
	input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"] {
		font-size: 12px;
	}
	label, input, button, select, textarea {
		font-size: 12px;
	}
	input[type="text"], 
	input[type="password"], 
	input[type="datetime"], 
	input[type="datetime-local"], 
	input[type="date"], 
	input[type="month"], 
	input[type="time"], 
	input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"] {
		padding: 2px 4px;
	}
	hr {
		margin: 1px 0 5px;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<form class="form-horizontal no-margin">
					<div class="widget-header">
						<div class="title">Ringkasan Pasien Rawat Inap</div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="row-fluid" style="border-top: 0; border-bottom: 1px solid #E5E5E5;">
						<div class="span12">
							<div class="form-actions" style="text-align: right;">
								<button class="btn btn-info" type="submit" id="simpan" name="simpan" value="Simpan">Simpan</button>
								<button class="btn" type="submit" id="batal" name="batal" value="Batal">Batal</button>
							</div>
						</div>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
								
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label">Nama Lengkap</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $data->nama; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Tanggal Lahir</label>
										<div class="controls controls-row">
											<?php
												$tanggal_lahir = strftime( "%d-%m-%Y", strtotime($data->tanggal_lahir));
											?>
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $tanggal_lahir; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Umur</label>
										<div class="controls controls-row">
											<?php
												if ($data->umur_tahun) {
													$umur = $data->umur_tahun." th ".$data->umur_bulan." bl ".$data->umur_hari." hr";
												}
												elseif ($data->umur_bulan) {
													$umur = $data->umur_bulan." bl ".$data->umur_hari." hr";
												}
												elseif ($data->umur_hari) {
													$umur = $data->umur_hari." hr";
												}
											?>
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $umur; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Pendidikan</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $data->pendidikan; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Pekerjaan</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $data->pekerjaan; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Status Perkawinan</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo status_kawin_descr($data->status_kawin); ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Alamat Lengkap</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $data->alamat_jalan; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Ruang Rawat / Kelas</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $data->gedung." / ".$data->kelas; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Bagian</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $data->bagian; ?></label>
										</div>
									</div>
									
								</div>
								
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label">No. Register</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $data->no_register; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">No. Medrec</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $data->no_medrec; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Agama</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $data->agama; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Jenis Kelamin</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo jenis_kelamin_descr($data->jenis_kelamin); ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Penerimaan Pasien Melalui</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo cara_masuk_descr($data->cara_masuk); ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Nama Penanggung Jawab</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo ""; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Hubungan Dengan Pasien</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Alamat Penanggung Jawab</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Tanggal Masuk / Jam</label>
										<div class="controls controls-row">
											<?php
												$tanggal_masuk = strftime( "%d-%m-%Y %H:%M:%S", strtotime($data->tanggal));
											?>
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $tanggal_masuk; ?></label>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Dokter Jaga / IGD</label>
										<div class="controls controls-row">
											<label id="nama" style="padding-top: 5px; color: #000;"><?php echo $data->dokter; ?></label>
										</div>
									</div>
									
								</div>
							</div>
							<hr />
							<div class="row-fluid" style="text-align: right;">
								<button id="tindakan_batal" class="btn" type="button">
									Tindakan Batal <span class="info-label badge badge-important"> 3 </span>
								</button>
							</div>
							<hr style="margin-top: 5px;" />
							<div class="row-fluid">
							
								<div class="span12">
									<div id="dt_example" class="example_alt_pagination">
									<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="tindakan">
										<thead>
											<tr>
												<th>Tanggal/Jam</th>
												<th>Tindakan</th>
												<th>Dokter</th>
												<th>Harga</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td colspan="5" class="dataTables_empty">Loading data from server</td>
											</tr>
										</tbody>
									</table>
									</div>
									
								</div>
							
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<div class="form-actions" style="text-align: right;">
								<button class="btn btn-info" type="submit" id="simpan" name="simpan" value="Simpan">Simpan</button>
								<button class="btn" type="submit" id="batal" name="batal" value="Batal">Batal</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>