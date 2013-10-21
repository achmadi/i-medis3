<script type="text/javascript">
	$(document).ready(function () {
		
		$('#pasien').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('pendaftaran_irj/load_data_daftar_pasien'); ?>",
			"aoColumns"			: [
									  { sWidth: '12%' },
									  { sWidth: '28%' },
									  { sWidth: '10%' },
									  { sWidth: '37%' },
									  { sWidth: '13%' }
								  ],

		});

		$('#pasien').on('click', '.edit-row', function() {
			var pasien_id = $(this).data('id');
			$.getJSON("<?php echo site_url('master/pasien/get_data'); ?>?pasien_id=" + pasien_id, function(json) {
				$("#pasien_title_dlg").text("Edit Pasien");
				
				$("#no_medrec").val(json.data.no_medrec);
				$("#nama").val(json.data.nama);
				switch (parseInt(json.data.jenis_kelamin)) {
					case 1:
						$('input:radio[name=jenis_kelamin]')[0].checked = true;
						break;
					case 2:
						$('input:radio[name=jenis_kelamin]')[1].checked = true;
						break;
				}
				$("#alamat_jalan").val(json.data.alamat_jalan);
				
				var option_provinsi = '';
				option_provinsi += '<option value="0" selected="selected">- Pilih Provinsi -</option>';
				for (var i = 0; i < json.provinsi_list.length; i++) {
					if (json.data.provinsi_id === json.provinsi_list[i].id) {
						option_provinsi += '<option value="' + json.provinsi_list[i].id + '" selected="selected">' + json.provinsi_list[i].nama + '</option>';
					}
					else {
						option_provinsi += '<option value="' + json.provinsi_list[i].id + '">' + json.provinsi_list[i].nama + '</option>';
					}
				}
				$("#provinsi_id").html(option_provinsi);
				
				$("#loading_kabupaten").show();
				$.getJSON("<?php echo site_url('master/pasien/get_data_kabupaten'); ?>?provinsi_id=" + json.data.provinsi_id, function(json_kabupaten) {
					var option_kabupaten = '';
					option_kabupaten += '<option value="0" selected="selected">- Pilih Kabupaten -</option>';
					if (json_kabupaten.success) {
						for (var i = 0; i < json_kabupaten.data.length; i++) {
							if (json.data.kabupaten_id === json_kabupaten.data[i].id) {
								option_kabupaten += '<option value="' + json_kabupaten.data[i].id + '" selected="selected">' + json_kabupaten.data[i].nama + '</option>';
							}
							else {
								option_kabupaten += '<option value="' + json_kabupaten.data[i].id + '">' + json_kabupaten.data[i].nama + '</option>';
							}
						}
						$("#kabupaten_id").html(option_kabupaten);
					}
					else {
						$("#kabupaten_id").html(option_kabupaten);
						$("#loading_kabupaten").hide();
					}
				})
				.fail(function() {
					$("#loading_kabupaten").hide();
				})
				.always(function() {
					$("#loading_kabupaten").hide();
				});
				
				$("#loading_kecamatan").show();
				$.getJSON("<?php echo site_url('master/pasien/get_data_kecamatan'); ?>?kabupaten_id=" + json.data.kabupaten_id, function(json_kecamatan) {
					var option_kecamatan = '';
					option_kecamatan += '<option value="0" selected="selected">- Pilih Kecamatan -</option>';
					if (json_kecamatan.success) {
						for (var i = 0; i < json_kecamatan.data.length; i++) {
							if (json.data.kecamatan_id === json_kecamatan.data[i].id) {
								option_kecamatan += '<option value="' + json_kecamatan.data[i].id + '" selected="selected">' + json_kecamatan.data[i].nama + '</option>';
							}
							else {
								option_kecamatan += '<option value="' + json_kecamatan.data[i].id + '">' + json_kecamatan.data[i].nama + '</option>';
							}
						}
						$("#kecamatan_id").html(option_kecamatan);
					}
					else {
						$("#kecamatan_id").html(option_kecamatan);
						$("#loading_kecamatan").hide();
					}
				})
				.fail(function() {
					$("#loading_kecamatan").hide();
				})
				.always(function() {
					$("#loading_kecamatan").hide();
				});
				
				$("#loading_kelurahan").show();
				$.getJSON("<?php echo site_url('master/pasien/get_data_kelurahan'); ?>?kecamatan_id=" + json.data.kecamatan_id, function(json_kelurahan) {
					var option_kelurahan = '';
					option_kelurahan += '<option value="0" selected="selected">- Pilih Kelurahan -</option>';
					if (json_kelurahan.success) {
						for (var i = 0; i < json_kelurahan.data.length; i++) {
							if (json.data.kelurahan_id === json_kelurahan.data[i].id) {
								option_kelurahan += '<option value="' + json_kelurahan.data[i].id + '" selected="selected">' + json_kelurahan.data[i].nama + '</option>';
							}
							else {
								option_kelurahan += '<option value="' + json_kelurahan.data[i].id + '">' + json_kelurahan.data[i].nama + '</option>';
							}
						}
						$("#kelurahan_id").html(option_kelurahan);
					}
					else {
						$("#kelurahan_id").html(option_kelurahan);
						$("#loading_kelurahan").hide();
					}
				})
				.fail(function() {
					$("#loading_kelurahan").hide();
				})
				.always(function() {
					$("#loading_kelurahan").hide();
				});
				
				$("#kodepos").val(json.data.kodepos);
				$("#telepon").val(json.data.telepon);
				$("#tempat_lahir").val(json.data.tempat_lahir);
				$("#tanggal_lahir").val(json.data.tanggal_lahir);
				var sTanggalLahir = "";
				if (json.data.tanggal_lahir.length > 0) {
					var t = json.data.tanggal_lahir.split(/[- :]/);
					var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
					sTanggalLahir = $.datepicker.formatDate('dd/mm/yy', d)
				}
				$("#disp_tanggal_lahir").val(sTanggalLahir);
				
				var option_golongan_darah = '';
				option_golongan_darah += '<option value="0" selected="selected">- Pilih Golongan Darah -</option>';
				for (var i = 0; i < json.golongan_darah_list.length; i++) {
					if (json.data.golongan_darah === json.golongan_darah_list[i]) {
						option_golongan_darah += '<option value="' + json.golongan_darah_list[i] + '" selected="selected">' + json.golongan_darah_list[i] + '</option>';
					}
					else {
						option_golongan_darah += '<option value="' + json.golongan_darah_list[i] + '">' + json.golongan_darah_list[i] + '</option>';
					}
				}
				$("#golongan_darah").html(option_golongan_darah);
				
				var option_agama = '';
				option_agama += '<option value="0" selected="selected">- Pilih Agama -</option>';
				for (var i = 0; i < json.agama_list.length; i++) {
					if (json.data.agama_id === json.agama_list[i].id) {
						option_agama += '<option value="' + json.agama_list[i].id + '" selected="selected">' + json.agama_list[i].nama + '</option>';
					}
					else {
						option_agama += '<option value="' + json.agama_list[i].id + '">' + json.agama_list[i].nama + '</option>';
					}
				}
				$("#agama_id").html(option_agama);
				
				var option_pendidikan = '';
				option_pendidikan += '<option value="0" selected="selected">- Pilih Pendidikan -</option>';
				for (var i = 0; i < json.pendidikan_list.length; i++) {
					if (json.data.pendidikan_id === json.pendidikan_list[i].id) {
						option_pendidikan += '<option value="' + json.pendidikan_list[i].id + '" selected="selected">' + json.pendidikan_list[i].nama + '</option>';
					}
					else {
						option_pendidikan += '<option value="' + json.pendidikan_list[i].id + '">' + json.pendidikan_list[i].nama + '</option>';
					}
				}
				$("#pendidikan_id").html(option_pendidikan);
				
				var option_pekerjaan = '';
				option_pekerjaan += '<option value="0" selected="selected">- Pilih Pekerjaan -</option>';
				for (var i = 0; i < json.pekerjaan_list.length; i++) {
					if (json.data.pekerjaan_id === json.pekerjaan_list[i].id) {
						option_pekerjaan += '<option value="' + json.pekerjaan_list[i].id + '" selected="selected">' + json.pekerjaan_list[i].nama + '</option>';
					}
					else {
						option_pekerjaan += '<option value="' + json.pekerjaan_list[i].id + '">' + json.pekerjaan_list[i].nama + '</option>';
					}
				}
				$("#pekerjaan_id").html(option_pekerjaan);
				
				var option_status_kawin = '';
				option_status_kawin += '<option value="0" selected="selected">- Pilih Status Kawin -</option>';
				for (var i = 0; i < json.status_kawin_list.length; i++) {
					if (json.data.status_kawin === (i + 1)) {
						option_status_kawin += '<option value="' + (i + 1) + '" selected="selected">' + json.status_kawin_list[i] + '</option>';
					}
					else {
						option_status_kawin += '<option value="' + (i + 1) + '">' + json.status_kawin_list[i] + '</option>';
					}
				}
				$("#status_kawin").html(option_status_kawin);
				
				$("#nama_keluarga").val(json.data.nama_keluarga);
				$("#nama_pasangan").val(json.data.nama_pasangan);
				$("#nama_orang_tua").val(json.data.nama_orang_tua);
				
				var option_pendidikan_ot = '';
				option_pendidikan_ot += '<option value="0" selected="selected">- Pilih Pendidikan -</option>';
				for (var i = 0; i < json.pendidikan_list.length; i++) {
					if (json.data.pendidikan_orang_tua_id === json.pendidikan_list[i].id) {
						option_pendidikan_ot += '<option value="' + json.pendidikan_list[i].id + '" selected="selected">' + json.pendidikan_list[i].nama + '</option>';
					}
					else {
						option_pendidikan_ot += '<option value="' + json.pendidikan_list[i].id + '">' + json.pendidikan_list[i].nama + '</option>';
					}
				}
				$("#pendidikan_orang_tua_id").html(option_pendidikan_ot);
				
				var option_pekerjaan_ot = '';
				option_pekerjaan_ot += '<option value="0" selected="selected">- Pilih Pekerjaan -</option>';
				for (var i = 0; i < json.pekerjaan_list.length; i++) {
					if (json.data.pekerjaan_orang_tua_id === json.pekerjaan_list[i].id) {
						option_pekerjaan_ot += '<option value="' + json.pekerjaan_list[i].id + '" selected="selected">' + json.pekerjaan_list[i].nama + '</option>';
					}
					else {
						option_pekerjaan_ot += '<option value="' + json.pekerjaan_list[i].id + '">' + json.pekerjaan_list[i].nama + '</option>';
					}
				}
				$("#pekerjaan_orang_tua_id").html(option_pekerjaan_ot);
				
				$("#id").val(json.data.id);
				
				$('#pasien_modal').modal('show');
			});
			return false;
		});
		
		var pasienApp = {
			initPasienModal: function () {
				$("#pasien_form").validate({
					rules: {
						nama: { required: true, minlength: 1 },
						kelas_id: { required: true, min: 1 }
					},
					messages: {
						nama: "Nama Ruangan diperlukan.",
						kelas_id: "Kelas diperlukan"
					},
					highlight: function(element) {
						$(element).closest('.control-group').removeClass('success').addClass('error');
					},
					success: function(element) {
						element
						.text('').addClass('valid')
						.closest('.control-group').removeClass('error').addClass('success');
					},
					submitHandler: function (form) {
						pasienApp.addPasien($(form));
						return false;
					}

				});
			},
			addPasien: function (form) {
				var url = "<?php echo site_url('master/pasien/simpan'); ?>";
				var postData = form.serialize();
				$.post(url, postData, function (data) {
					if (data.toLowerCase() === "ok") {
						oTable.fnDraw();
						alertify.success("Record telah di simpan!");
						return;
					}
				});
				$('#pasien_modal').modal('hide');
			}
		};
		
		pasienApp.initPasienModal();
		
	});

</script>
<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	.fg-toolbar {
		height: 35px;
	}
	table {
		max-width: none;
	}
	.table {
		margin-bottom: 0;
	}
	.table th {
		text-align: center;
	}
	.dataTables_scrollBody {
		margin-bottom: 5px;
	}
	.table thead th, .table thead td {
		text-align: center;
		vertical-align: middle;
	}
	.dashboard-wrapper .left-sidebar .widget .widget-body #dt_example .dataTables_scroll {
		clear: both;
	}
	
	#pasien_modal .modal-body {
        max-height: 800px;
    }
	
	#pasien_modal {
		width: 900px;
		margin-left: -450px;
		margin-right: -450px;
	}
	.form-horizontal .control-group {
		margin-bottom: 2px;
	}
	.form-horizontal .control-label {
		width: 150px;
	}
	.form-horizontal .controls {
		margin-left: 160px;
	}
	#pasien_processing {
		position:absolute;
		top: 50%;
		left: 50%;
		width:20em;
		height:2em;
		margin-top: -10em; /*set to a negative number 1/2 of your height*/
		margin-left: -10em; /*set to a negative number 1/2 of your width*/
		border: 1px solid #ccc;
		background-color: #f3f3f3;
		text-align: center;
		padding-top: 0.5em;
		padding-bottom: 0.5em;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">Daftar Pasien</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="pasien">
							<thead>
								<tr>
									<th>No. Medrec</th>
									<th>Nama</th>
									<th>Jenis Kelamin</th>
									<th>Alamat</th>
									<th>Tanggal Lahir</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="5" class="dataTables_empty">Loading data from server</td>
								</tr>
							</tbody>
						</table>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<form class="form-horizontal no-margin" id="pasien_form" name="pasien_form" method="post" action="">
	<div class="modal hide fade" id="pasien_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="pasien_title_dlg">Tambah/Edit Agama</h4>
		</div>
		<div class="modal-body">
			<div class="modal-body">
				<div class="row-fluid">
					<div class="span6">

						<div class="control-group">
							<label class="control-label" for="no_medrec">No. Medrec</label>
							<div class="controls controls-row">
								<input class="span6" type="text" id="no_medrec" name="no_medrec" maxlength="20" placeholder="No. Medrec" value="" autocomplete="off" readonly="readonly" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="nama">Nama</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="nama" name="nama" maxlength="30" placeholder="Nama" value="" autocomplete="off" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="jenis_kelamin1">Jenis Kelamin</label>
							<div class="controls controls-row">
								<label class="radio inline">
									<input type="radio" id="jenis_kelamin1" name="jenis_kelamin" value="1" />Laki-laki
								</label>
								<label class="radio inline">
									<input type="radio" id="jenis_kelamin2" name="jenis_kelamin" value="2" />Perempuan
								</label>
							</div>
						</div>
									
						<div class="control-group">
							<label class="control-label" for="alamat_jalan">Alamat</label>
							<div class="controls controls-row">
								<textarea class="span12" id="alamat_jalan" name="alamat_jalan" placeholder="Jalan"></textarea>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="provinsi_id">&nbsp;</label>
							<div class="controls controls-row">
								<div class="span5">
									<label>Provinsi</label>
								</div>
								<div class="span7">
									<select class="span12" id="provinsi_id" name="provinsi_id">
									</select>
									<img id="loading_provinsi" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
								</div>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="kabupaten_id">&nbsp;</label>
							<div class="controls controls-row">
								<div class="span5">
									<label>Kabupaten/Kota</label>
								</div>
								<div class="span7">
									<select class="span12" id="kabupaten_id" name="kabupaten_id">
										<option value="0">- Pilih Kabupaten/Kota -</option>
									</select>
									<img id="loading_kabupaten" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
								</div>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="kecamatan_id">&nbsp;</label>
							<div class="controls controls-row">
								<div class="span5">
									<label>Kecamatan</label>
								</div>
								<div class="span7">
									<select class="span12" id="kecamatan_id" name="kecamatan_id">
										<option value="0">- Pilih Kecamatan -</option>
									</select>
									<img id="loading_kecamatan" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
								</div>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="kelurahan_id">&nbsp;</label>
							<div class="controls controls-row">
								<div class="span5">
									<label>Kelurahan/Desa</label>
								</div>
								<div class="span7">
									<select class="span12" id="kelurahan_id" name="kelurahan_id">
										<option value="0">- Pilih Kelurahan/Desa -</option>
									</select>
									<img id="loading_kelurahan" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
								</div>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="kodepos">&nbsp;</label>
							<div class="controls controls-row">
								<div class="span5">
									<label>Kodepos</label>
								</div>
								<div class="span7">
									<input class="span12" type="text" id="kodepos" name="kodepos" placeholder="Kodepos" value="" autocomplte="off" />
								</div>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="telepon">Telepon</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="telepon" name="telepon" placeholder="Telepon" value="" autocomplte="off" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="tempat_lahir">Tempat Lahir</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" value="" autocomplte="off" />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="disp_tanggal_lahir">Tanggal Lahir</label>
							<div class="controls controls-row">
								<input type="hidden" id="tanggal_lahir" name="tanggal_lahir" value="" />
								<input class="span6" type="text" id="disp_tanggal_lahir" name="disp_tanggal_lahir" placeholder="" value="" />
							</div>
						</div>

					</div>
					
					<div class="span6">
						
						<div class="control-group">
							<label class="control-label" for="golongan_darah">Golongan Darah</label>
							<div class="controls controls-row">
								<select id="golongan_darah" name="golongan_darah">
								</select>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="agama_id">Agama</label>
							<div class="controls controls-row">
								<select id="agama_id" name="agama_id">
								</select>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="pendidikan_id">Pendidikan</label>
							<div class="controls controls-row">
								<select id="pendidikan_id" name="pendidikan_id">
								</select>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="pekerjaan_id">Pekerjaan</label>
							<div class="controls controls-row">
								<select id="pekerjaan_id" name="pekerjaan_id">
								</select>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="status_kawin">Status</label>
							<div class="controls controls-row">
								<select id="status_kawin" name="status_kawin" class="span12">
								</select>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="nama_keluarga">Nama Keluarga</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="nama_keluarga" name="nama_keluarga" placeholder="" maxlength="60" value="" autocomplete="off" />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="nama_pasangan">Nama Suami/Istri</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="nama_pasangan" name="nama_pasangan" placeholder="" maxlength="60" value="" autocomplete="off" />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="nama_orang_tua">Nama Ayah/Ibu</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="nama_orang_tua" name="nama_orang_tua" placeholder="" maxlength="60" value="" autocomplete="off" />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="pendidikan_orang_tua_id">Pendidikan Ayah/Ibu/Suami/Istri</label>
							<div class="controls controls-row">
								<select id="pendidikan_orang_tua_id" name="pendidikan_orang_tua_id">
								</select>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="pekerjaan_orang_tua_id">Pekerjaan Ayah</label>
							<div class="controls controls-row">
								<select id="pekerjaan_orang_tua_id" name="pekerjaan_orang_tua_id">
								</select>
							</div>
						</div>
						
					</div>
					
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
			<button type="reset" class="btn" data-dismiss="modal">Batal</button>
		</div>
	</div>
</form>