<script type="text/javascript">

	$(document).ready(function () {
		
		var oTable = $('#konfirmasi').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('rawat_inap/rawat_inap/load_data_konfirmasi'); ?>",
			"aoColumns"			: [
									  { sWidth: '138px' },
									  { sWidth: '79px' },
									  { sWidth: '77px' },
									  { sWidth: 'null' },
									  { sWidth: '60px' },
									  { sWidth: '90px' },
									  { sWidth: '90px' },
									  { sWidth: '150px' }
								  ],
			"aaSorting"			: [[1, 'asc']]
		});
		
		$('#konfirmasi').on('click', '.konfirmasi-row', function() {
			var id = $(this).data('id');
			if (id) {
				$.getJSON("<?php echo site_url('rawat_inap/get_data_konfirmasi'); ?>?pendaftaran_id=" + id, function(json) {
					$("#pendaftaran_id").val(json.data.pendaftaran_id);
					$("#pasien_id").val(json.data.pasien_id);
					$("#umur_tahun").val(json.data.umur_tahun);
					$("#umur_bulan").val(json.data.umur_bulan);
					$("#umur_hari").val(json.data.umur_hari);
					$("#tanggal").text(json.data.tanggal);
					$("#no_register").text(json.data.no_register);
					$("#no_medrec").text(json.data.no_medrec);
					$("#nama").text(json.data.nama);
					$("#jenis_kelamin").text(json.data.jenis_kelamin);
					$("#disp_cara_masuk").text(json.data.disp_cara_masuk);
					$("#gedung_id").text(json.data.gedung_id);
					$("#gedung").text(json.data.gedung);
					
					var gedung_id = json.data.gedung_id;
					if (gedung_id > 0) {
						$("#loading_ruangan").show();
						$.getJSON("<?php echo site_url('rawat_inap/get_ruangan'); ?>?gedung_id=" + gedung_id, function(ruangan) {
							var option_ruangan = '';
							option_ruangan += '<option value="0" selected="selected">- Pilih Ruangan -</option>';
							for (var i = 0; i < ruangan.ruangan_list.length; i++) {
								if (ruangan.ruangan_list[i].id === json.data.ruangan_id) {
									option_ruangan += '<option value="' + ruangan.ruangan_list[i].id + '" selected="selected">' + ruangan.ruangan_list[i].nama + '</option>';
								}
								else {
									option_ruangan += '<option value="' + ruangan.ruangan_list[i].id + '">' + ruangan.ruangan_list[i].nama + '</option>';
								}
							}
							$("#ruangan_id").html(option_ruangan);
							$("#loading_ruangan").hide();
						});
					}
					
					var ruangan_id = json.data.ruangan_id;
					if (ruangan_id > 0) {
						$("#loading_bed").show();
						$.getJSON("<?php echo site_url('rawat_inap/get_bed'); ?>?ruangan_id=" + ruangan_id, function(bed) {
							var option_bed = '';
							option_bed += '<option value="0" selected="selected">- Pilih Ruangan -</option>';
							for (var i = 0; i < bed.bed_list.length; i++) {
								if (bed.bed_list[i].id === json.data.bed_id) {
									option_bed += '<option value="' + bed.bed_list[i].id + '" selected="selected">' + bed.bed_list[i].nama + '</option>';
								}
								else {
									option_bed += '<option value="' + bed.bed_list[i].id + '">' + bed.bed_list[i].nama + '</option>';
								}
							}
							$("#bed_id").html(option_bed);
							$("#loading_bed").hide();
						});
					}
					
					$("#rujukan_id").val(json.data.rujukan_id);
					$("#cara_bayar_id").val(json.data.cara_bayar_id);
					$("#dokter_id").val(json.data.dokter_id);
					$("#cara_masuk").val(json.data.cara_masuk);
					
					$('#konfirmasi_modal').modal('show');
				});
			}
			return false;
		});
		
		function getISODateTime(d){
			var s = function(a,b){return(1e15+a+"").slice(-b);};

			if (typeof d === 'undefined'){
				d = new Date();
			};

			return d.getFullYear() + '-' +
				s(d.getMonth()+1,2) + '-' +
				s(d.getDate(),2) + ' ' +
				s(d.getHours(),2) + ':' +
				s(d.getMinutes(),2) + ':' +
				s(d.getSeconds(),2);
		}
		
		var disp_tanggal = $('#disp_tanggal').datepicker({
			format: 'dd/mm/yyyy'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date);
			var date_str = getISODateTime(date);
			$('#tanggal').val(date_str);
			disp_tanggal.hide();
		}).data('datepicker');
		
		var konfirmasiApp = {
			initKonfirmasiModal: function () {
				$("#konfirmasi_form").validate({
					rules: {

					},
					messages: {

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
						konfirmasiApp.addKonfirmasi($(form));
						return false;
					}

				});
			},
			addKonfirmasi: function (form) {
				var url = "<?php echo site_url('rawat_inap/konfirmasi'); ?>";
				var postData = form.serialize();
				$.post(url, postData, function (data) {
					if (data.toLowerCase() === "ok") {
						oTable.fnDraw();
						alertify.success("Record telah di simpan!");
						return;
					}
				});
				$('#konfirmasi_modal').modal('hide');
			}
		};
		
		konfirmasiApp.initKonfirmasiModal();
				
	});
</script>
<style type="text/css">
	.datepicker {
		z-index: 1151;
	}
	.datepicker {
		background: white;
	}
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	.form-horizontal .control-group {
		margin-bottom: 2px;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<?php $title = "Konfirmasi Pasien Masuk"; ?>
					<div class="title"><?php echo $title; ?></div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="konfirmasi">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>No. Register</th>
									<th>No. Medrec</th>
									<th>Nama</th>
									<th>Jenis Kelamin</th>
									<th>Asal Pasien</th>
									<th>Bed</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="8" class="dataTables_empty">Loading data from server</td>
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
<form class="form-horizontal no-margin" id="konfirmasi_form" name="konfirmasi_form" method="post" action="">
	<div class="modal hide fade" id="konfirmasi_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="title_modal">Konfirmasi Pasien Masuk</h4>
		</div>
		<div class="modal-body">
			<div class="row-fluid">
				<div class="span12">
					
					<div class="control-group">
						<label class="control-label">Tanggal Pendaftaran</label>
						<div class="controls controls-row">
							<label id="tanggal" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">No. Register</label>
						<div class="controls controls-row">
							<label id="no_register" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">No. Medrec</label>
						<div class="controls controls-row">
							<label id="no_medrec" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Nama</label>
						<div class="controls controls-row">
							<label id="nama" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Jenis Kelamin</label>
						<div class="controls controls-row">
							<label id="jenis_kelamin" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Cara Masuk</label>
						<div class="controls controls-row">
							<label id="disp_cara_masuk" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Gedung/Bangsal</label>
						<div class="controls controls-row">
							<label id="gedung" style="padding-top: 5px; color: #000;"></label>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="ruangan_id">Ruangan</label>
						<div class="controls controls-row">
							<select id="ruangan_id" name="ruangan_id"></select>
							<img id="loading_ruangan" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="margin: 5px; display: none;" />
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="bed_id">Bed</label>
						<div class="controls controls-row">
							<select id="bed_id" name="bed_id"></select>
							<img id="loading_bed" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="margin: 5px; display: none;" />
						</div>
					</div>

				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Konfirmasi</button>
			<button type="reset" class="btn" data-dismiss="modal">Batal</button>
		</div>
	</div>
	<div>
		<input type="hidden" id="id" name="id" value="" />
		<input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="" />
		<input type="hidden" id="pasien_id" name="pasien_id" value="" />
		<input type="hidden" id="umur_tahun" name="umur_tahun" value="" />
		<input type="hidden" id="umur_bulan" name="umur_bulan" value="" />
		<input type="hidden" id="umur_hari" name="umur_hari" value="" />
		<input type="hidden" id="gedung_id" name="gedung_id" value="" />
		<input type="hidden" id="rujukan_id" name="rujukan_id" value="" />
		<input type="hidden" id="cara_bayar_id" name="cara_bayar_id" value="" />
		<input type="hidden" id="dokter_id" name="dokter_id" value="" />
		<input type="hidden" id="cara_masuk" name="cara_masuk" value="" />
	</div>
</form>