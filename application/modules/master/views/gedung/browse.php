<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#gedung').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('master/gedung/load_data'); ?>",
			"aaSorting"			: [[ 0, "asc" ]],
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 1 ] },
									  { "bSortable": false, "aTargets": [ 2 ] }
								  ],
			"aoColumns"			: [
									  { sWidth: 'null' },
									  { sWidth: '200px' },
									  { sWidth: '107px' }
								  ],
			"bStateSave": true
		});
		
		$("#tambah").click(function() {
			$.ajax({
				type		: "GET",
				url			: "<?php echo site_url('master/gedung/get_data'); ?>",
				data		: "gedung_id=0",
				dataType	: "json",
				cache		: false,
				beforeSend: function() {
					//$('#loading_kabupaten').show();
					//$('#loading_kabupaten').css("display", "inline");
				},
				success: function() {
					//$('#loading_kabupaten').hide();
				},
				complete: function(response, $status) {
					if ($status === "success" || $status === "notmodified") {
						var data = response.responseJSON.data;
						
						$("#title_modal").text("Tambah Gedung");

						$("#id").val(data.id);
						$("#nama").val(data.nama);
						$("#bagian").val(data.bagian);

						var kelas = '';
						var gedung_kelas_id = 0;
						var checked = '';
						var found;
						for (var i = 0; i < data.kelas_list.length; i++) {
							found = false;
							for (var j = 0; j < data.kelass.length; j++) {
								if (data.kelass[j].kelas_id === data.kelas_list[i].id) {
									found = true;
									gedung_kelas_id = data.kelass[j].id;
									checked = 'checked="checked"';
									break;
								}
							}
							if (!found) {
								gedung_kelas_id = "gedung_kelas_id_" + i;
								checked = '';
							}
							kelas += '<label class="checkbox"><input type="checkbox" name="kelas_id[]" value="' + data.kelas_list[i].id + '|' + gedung_kelas_id + '" ' + checked + ' />' + data.kelas_list[i].nama + '</label>';
						}
						$("#kelas").html(kelas);
						
						$('#gedung_modal').modal('show');
					}
				}
			});
		});
		
		$('#gedung').on('click', '.edit-row', function() {
			var id = $(this).data('id');
			$('#gedung_modal').removeData('show');
			$.ajax({
				type		: "GET",
				url			: "<?php echo site_url('master/gedung/get_data'); ?>",
				data		: "gedung_id=" + id,
				dataType	: "json",
				cache		: false,
				beforeSend: function() {
					//$('#loading_kabupaten').show();
					//$('#loading_kabupaten').css("display", "inline");
				},
				success: function() {
					//$('#loading_kabupaten').hide();
				},
				complete: function(response, $status) {
					if ($status === "success" || $status === "notmodified") {
						var data = response.responseJSON.data;
						
						$("#title_modal").text("Edit Gedung");

						$("#id").val(data.id);
						$("#nama").val(data.nama);
						$("#bagian").val(data.bagian);

						var kelas = '';
						var gedung_kelas_id = 0;
						var checked = '';
						var found;
						for (var i = 0; i < data.kelas_list.length; i++) {
							found = false;
							for (var j = 0; j < data.kelass.length; j++) {
								if (data.kelass[j].kelas_id === data.kelas_list[i].id) {
									found = true;
									gedung_kelas_id = data.kelass[j].id;
									checked = 'checked="checked"';
									break;
								}
							}
							if (!found) {
								gedung_kelas_id = "gedung_kelas_id_" + i;
								checked = '';
							}
							kelas += '<label class="checkbox"><input type="checkbox" name="kelas_id[]" value="' + data.kelas_list[i].id + '|' + gedung_kelas_id + '" ' + checked + ' />' + data.kelas_list[i].nama + '</label>';
						}
						$("#kelas").html(kelas);
						
						$('#gedung_modal').modal('show');
					}
				}
			});
			return false;
		});
		
		$('#gedung').on('click', '.delete-row', function() {
			var id = $(this).attr('id');
			alertify.set({
				labels: {
					ok: "OK",
					cancel: "Batal"
				},
				delay: 5000,
				buttonReverse: false,
				buttonFocus: "ok"
			});
			alertify.confirm("Menghapus Gedung juga akan menghapus ruangan dan bednya, Anda yakin untuk menghapus record tersebut?", function (e) {
				if (e) {
					$.ajax({
						type: 'get',
						url: '<?php echo site_url('master/gedung/delete'); ?>',
						data: 'id=' + id,
						success: function() {
							oTable.fnDraw();
							alertify.success("Record telah di hapus dari database!");
						},
						error: function() {
							alertify.error("Penghapusan record gagal!");
						}
					});
				}
			});
			return false;
		});
		
		var gedungApp = {
			initGedungModal: function () {
				$("#gedung_form").validate({
					rules: {
						nama: { required: true, minlength: 1 }
					},
					messages: {
						nama: "Nama Gedung/Bangsal diperlukan."
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
						gedungApp.addGedung($(form));
						return false;
					}

				});
			},
			addGedung: function (form) {
				var url = "<?php echo site_url('master/gedung/simpan'); ?>";
				var postData = form.serialize();
				$.post(url, postData, function (data) {
					if (data.toLowerCase() === "ok") {
						oTable.fnDraw();
						alertify.success("Record telah di simpan!");
						return;
					}
				});
				$('#gedung_modal').modal('hide');
			}
		};
		
		gedungApp.initGedungModal();
		
	});
</script>
<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	.form-actions {
		border-top: 0;
		border-bottom: 1px solid #E5E5E5;
		margin: 0;
		padding: 5px 10px 5px;
	}
	#gedung_processing {
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
					<div class="title">Gedung/Bangsal</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="form-actions no-margin">
					<button id="tambah" class="btn btn-info bottom-margin pull-right" type="button">Tambah</button>
					<div class="clearfix"></div>
				</div>
				<div class="widget-body">
				
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="gedung">
							<thead>
								<tr>
									<th>Nama Gedung</th>
									<th>Kelas</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="3" class="dataTables_empty">Loading data from server</td>
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
<form class="form-horizontal no-margin" id="gedung_form" name="gedung_form" method="post" action="">
	<div class="modal fade" id="gedung_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="title_modal">Tambah/Edit Gedung</h4>
		</div>
		<div class="modal-body">
			<div class="modal-body">
				<div class="row-fluid">
					<div class="span12">

						<div class="control-group">
							<label class="control-label" for="nama">Nama Gedung</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="nama" name="nama" maxlength="30" placeholder="Nama Gedung" value="" autocomplete="off" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="bagian">Bagian</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="bagian" name="bagian" maxlength="30" placeholder="Bagian" value="" autocomplete="off" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="kelas_id">Kelas</label>
							<div id="kelas" class="controls"></div>
						</div>

					</div>
				</div>
			</div>
			<div id="validationSummary" class="validation-summary">
				<ul></ul>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
			<button type="reset" class="btn" data-dismiss="modal">Batal</button>
		</div>
	</div>
	<div>
		<input type="hidden" id="id" name="id" value="" />
	</div>
</form>