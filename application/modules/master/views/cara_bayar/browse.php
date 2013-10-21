<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#cara_bayar').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('master/cara_bayar/load_data'); ?>",
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 3 ] }
								  ],
			"aoColumns"			: [
									  { sWidth: 'null' },
									  { sWidth: '150px' },
									  { sWidth: '100px' },
									  { sWidth: '45px' }
								  ]
		});
		
		$("#tambah").click(function() {
			$.ajax({
				type		: "GET",
				url			: "<?php echo site_url('master/cara_bayar/get_data'); ?>",
				data		: "cara_bayar_id=0",
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
						
						$("#title_modal").text("Tambah Cara Pembayaran");

						$("#id").val(data.id);
						$("#nama").val(data.nama);
						
						var options = '';
						var keyval;
						options += '<option value="0" selected="selected">- Pilih Jenis Cara Pembayaran -</option>';
						for (var i = 0; i < data.jenis_cara_bayar_list.length; i++) {
							keyval = data.jenis_cara_bayar_list[i].split('|');
							if (keyval[1] === data.jenis_cara_bayar) {
								options += '<option value="' + keyval[1] + '" selected="selected">' + keyval[0] + '</option>';
							}
							else {
								options += '<option value="' + keyval[1] + '">' + keyval[0] + '</option>';
							}
						}
						$("#jenis_cara_bayar").html(options);
						
						switch (data.jenis) {
							case "Kelompok":
								$('input:radio[name=jenis]')[0].checked = true;
								break;
							case "Rincian":
								$('input:radio[name=jenis]')[1].checked = true;
								break;
						}
						
						var option_parent = '';
						option_parent += '<option value="0" selected="selected">Root</option>';
						for (var i = 0; i < data.parent_list.length; i++) {
							if (data.parent_list[i].id === data.parent_id) {
								option_parent += '<option value="' + data.parent_list[i].id + '" selected="selected">' + data.parent_list[i].nama + '</option>';
							}
							else {
								option_parent += '<option value="' + data.parent_list[i].id + '">' + data.parent_list[i].nama + '</option>';
							}
						}
						$("#parent_id").html(option_parent);
						
						$('#cara_bayar_modal').modal('show');
					}
				}
			});
		});
		
		$('#cara_bayar').on('click', '.edit-row', function() {
			var id = $(this).data('id');
			$.ajax({
				type		: "GET",
				url			: "<?php echo site_url('master/cara_bayar/get_data'); ?>",
				data		: "cara_bayar_id=" + id,
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
						
						$("#title_modal").text("Tambah Cara Pembayaran");

						$("#id").val(data.id);
						$("#nama").val(data.nama);
						
						var options = '';
						var keyval;
						options += '<option value="0" selected="selected">- Pilih Jenis Cara Pembayaran -</option>';
						for (var i = 0; i < data.jenis_cara_bayar_list.length; i++) {
							keyval = data.jenis_cara_bayar_list[i].split('|');
							if (keyval[1] === data.jenis_cara_bayar) {
								options += '<option value="' + keyval[1] + '" selected="selected">' + keyval[0] + '</option>';
							}
							else {
								options += '<option value="' + keyval[1] + '">' + keyval[0] + '</option>';
							}
						}
						$("#jenis_cara_bayar").html(options);
						
						switch (data.jenis) {
							case "Kelompok":
								$('input:radio[name=jenis]')[0].checked = true;
								break;
							case "Rincian":
								$('input:radio[name=jenis]')[1].checked = true;
								break;
						}
						
						var option_parent = '';
						option_parent += '<option value="0" selected="selected">Root</option>';
						for (var i = 0; i < data.parent_list.length; i++) {
							if (data.parent_list[i].id === data.parent_id) {
								option_parent += '<option value="' + data.parent_list[i].id + '" selected="selected">' + data.parent_list[i].nama + '</option>';
							}
							else {
								option_parent += '<option value="' + data.parent_list[i].id + '">' + data.parent_list[i].nama + '</option>';
							}
						}
						$("#parent_id").html(option_parent);
						
						$('#cara_bayar_modal').modal('show');
					}
				}
			});
			return false;
		});
		
		$('#cara_bayar').on('click', '.delete-row', function() {
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
			alertify.confirm("Hapus record tersebut?", function (e) {
				if (e) {
					$.ajax({
						type: 'get',
						url: '<?php echo site_url('master/cara_bayar/delete'); ?>',
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
		
		$("#cara_bayar").on("click", ".order_up", function(event){
			var id = $(this).data('id');
			$.ajax({
				type: 'get',
				url: '<?php echo site_url('master/cara_bayar/order_up'); ?>',
				data: 'id=' + id,
				success: function() {
					oTable.fnDraw();
					alertify.success("Ordering berhasil disimpan.");
				},
				error: function() {
					alertify.error("Penghapusan record gagal!");
				}
			});
			return false;
		});

		$("#cara_bayar").on("click", ".order_down", function(event){
			var id = $(this).data('id');
			$.ajax({
				type: 'get',
				url: '<?php echo site_url('master/cara_bayar/order_down'); ?>',
				data: 'id=' + id,
				success: function() {
					oTable.fnDraw();
					alertify.success("Ordering berhasil disimpan.");
				},
				error: function() {
					alertify.error("Penghapusan record gagal!");
				}
			});
			return false;
		});
		
		var caraBayarApp = {
			initCaraBayarModal: function () {
				$("#cara_bayar_form").validate({
					rules: {
						nama: { required: true, minlength: 1 }
					},
					messages: {
						nama: "Nama Cara Pembayaran diperlukan."
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
						caraBayarApp.addCaraBayar($(form));
						return false;
					}

				});
			},
			addCaraBayar: function (form) {
				var url = "<?php echo site_url('master/cara_bayar/simpan'); ?>";
				var postData = form.serialize();
				$.post(url, postData, function (data) {
					if (data.toLowerCase() === "ok") {
						oTable.fnDraw();
						alertify.success("Record telah di simpan!");
						return;
					}
				});
				$('#cara_bayar_modal').modal('hide');
			}
		};
		
		caraBayarApp.initCaraBayarModal();
		
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
	span.gi {
		color: #CCCCCC;
		font-weight: bold;
		vertical-align: top;
	}
	#cara_bayar_processing {
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
					<div class="title">Cara Pembayaran</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="form-actions no-margin">
					<button id="tambah" class="btn btn-info bottom-margin pull-right" type="button">Tambah</button>
					<div class="clearfix"></div>
				</div>
				<div class="widget-body">
				
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="cara_bayar">
							<thead>
								<tr>
									<th>Cara Pembayaran</th>
									<th>Jenis Pembayaran</th>
									<th>Ordering</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="4" class="dataTables_empty">Loading data from server</td>
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
<form class="form-horizontal no-margin" id="cara_bayar_form" name="cara_bayar_form" method="post" action="">
	<div class="modal fade" id="cara_bayar_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="title_modal">Tambah/Edit Cara Pembayaran</h4>
		</div>
		<div class="modal-body">
			<div class="modal-body">
				<div class="row-fluid">
					<div class="span12">

						<div class="control-group">
							<label class="control-label" for="nama">Nama Cara Pembayaran</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="nama" name="nama" maxlength="60" placeholder="Nama Cara Pembayaran" value="" autocomplete="off" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="jenis_cara_bayar">Jenis Cara Pembayaran</label>
							<div class="controls controls-row">
								<select class="m-wrap medium" id="jenis_cara_bayar" name="jenis_cara_bayar" size="1" tabindex="2">
								</select>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="jenis1">Jenis</label>
							<div class="controls">
								<label class="radio inline">
									<input type="radio" id="jenis1" name="jenis" value="Kelompok"  />Kelompok
								</label>
								<label class="radio inline">
									<input type="radio" id="jenis2" name="jenis" value="Rincian"  />Rincian
								</label>  
							</div>
						 </div>

						<div class="control-group">
							<label class="control-label" for="parent_id">Parent</label>
							<div class="controls">
								<select class="m-wrap medium" id="parent_id" name="parent_id" size="1" tabindex="3">
								</select>
							</div>
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