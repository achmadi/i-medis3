<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#bed').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('master/bed/load_data?gedung_id='.$gedung->id.'&ruangan_id='.$ruangan->id); ?>",
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 2 ] }
								  ],
			"aoColumns"			: [
									  { sWidth: 'null' },
									  { sWidth: '150px' },
									  { sWidth: '46px' }
								  ]
		});
		
		$("#tambah").click(function() {
			$.ajax({
				type		: "GET",
				url			: "<?php echo site_url('master/bed/get_data'); ?>",
				data		: "bed_id=0&ruangan_id=<?php echo $ruangan->id; ?>&gedung_id=<?php echo $gedung->id; ?>",
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
						
						$("#title_modal").text("Tambah Bed");

						$("#id").val(data.id);
						$("#nama").val(data.nama);
						$("#gedung_id").val(data.gedung_id);
						$("#ruangan_id").val(data.ruangan_id);

						var options = '';
						options += '<option value="0" selected="selected">- Pilih Kelas -</option>';
						for (var i = 0; i < data.kelas_list.length; i++) {
							if (data.kelas_list[i].kelas_id === data.kelas_id) {
								options += '<option value="' + data.kelas_list[i].kelas_id + '" selected="selected">' + data.kelas_list[i].kelas + '</option>';
							}
							else {
								options += '<option value="' + data.kelas_list[i].kelas_id + '">' + data.kelas_list[i].kelas + '</option>';
							}
						}
						$("#kelas_id").html(options);
						
						$('#bed_modal').modal('show');
					}
				}
			});
		});
		
		$('#bed').on('click', '.edit-row', function() {
			var id = $(this).data('id');
			$.ajax({
				type		: "GET",
				url			: "<?php echo site_url('master/bed/get_data'); ?>",
				data		: "bed_id=" + id + "ruangan_id=<?php echo $ruangan->id; ?>&gedung_id=<?php echo $gedung->id; ?>",
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
						$("#gedung_id").val(data.gedung_id);
						$("#ruangan_id").val(data.ruangan_id);

						var options = '';
						options += '<option value="0" selected="selected">- Pilih Kelas -</option>';
						for (var i = 0; i < data.kelas_list.length; i++) {
							if (data.kelas_list[i].kelas_id === data.kelas_id) {
								options += '<option value="' + data.kelas_list[i].kelas_id + '" selected="selected">' + data.kelas_list[i].kelas + '</option>';
							}
							else {
								options += '<option value="' + data.kelas_list[i].kelas_id + '">' + data.kelas_list[i].kelas + '</option>';
							}
						}
						$("#kelas_id").html(options);
						
						$('#bed_modal').modal('show');
					}
				}
			});
			return false;
		});
		
		$('#bed').on('click', '.delete-row', function() {
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
						url: '<?php echo site_url('master/bed/delete'); ?>',
						data: 'id=' + id,
						success: function() {
							oTable.fnDraw();
							alertify.success("Record telah di hapus dari database!");
						},
						error: function() {
							alertify.error("Penghapusan record gagal!");
						}
					});
				} else {
					//
				}
			});
			return false;
		});
		
		var bedApp = {
			initBedModal: function () {
				$("#bed_form").validate({
					rules: {
						nama: { required: true, minlength: 1 },
						kelas_id: { required: true, min: 1 }
					},
					messages: {
						nama: "Nama Bed diperlukan.",
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
						bedApp.addBed($(form));
						return false;
					}

				});
			},
			addBed: function (form) {
				var url = "<?php echo site_url('master/bed/simpan'); ?>";
				var postData = form.serialize();
				$.post(url, postData, function (data) {
					if (data.toLowerCase() === "ok") {
						oTable.fnDraw();
						alertify.success("Record telah di simpan!");
						return;
					}
				});
				$('#bed_modal').modal('hide');
			}
		};
		
		bedApp.initBedModal();
		
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

	.stylish-lists .dl-horizontal dt {
		text-align: left;
		width: 65px;
	}
	.stylish-lists .dl-horizontal dd {
		margin-left: 65px;
	}
	#bed_processing {
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
			<div class="widget" style="border: none; margin-bottom: 10px;">
				<ul class="breadcrumb-beauty">
					<li><a href="<?php echo site_url('master/gedung'); ?>">Gedung <?php echo $gedung->nama.(empty($gedung->deskripsi) ? "" : " (".$gedung->deskripsi.")"); ?></a></li>
					<li><a href="<?php echo site_url('master/ruangan?gedung_id='.$gedung->id.'&ruangan_id='.$ruangan->id); ?>">Ruangan <?php echo $ruangan->nama; ?></a></li>
					<li><a href="<?php echo site_url('master/bed'); ?>">Bed</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<?php
						$title = "Bed";
					?>
					<div class="title"><?php echo $title; ?></div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="form-actions no-margin">
					<button id="tambah" class="btn btn-info bottom-margin pull-right" type="button">Tambah</button>
					<div class="clearfix"></div>
				</div>
				<div class="widget-body">
				
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="bed">
							<thead>
								<tr>
									<th>Nama/Nomor Bed</th>
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
<form class="form-horizontal no-margin" id="bed_form" name="bed_form" method="post" action="">
	<div class="modal fade" id="bed_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="title_modal">Tambah/Edit Bed</h4>
		</div>
		<div class="modal-body">
			<div class="modal-body">
				<div class="row-fluid">
					<div class="span12">

						<div class="control-group">
							<label class="control-label" for="nama">Nama Bed</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="nama" name="nama" maxlength="30" placeholder="Nama Bed" value="" autocomplete="off" />
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="kelas_id">Kelas</label>
							<div class="controls controls-row">
								<select id="kelas_id" name="kelas_id"></select>
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
		<input type="hidden" id="gedung_id" name="gedung_id" value="<?php echo $gedung->id; ?>" />
		<input type="hidden" id="ruangan_id" name="ruangan_id" value="<?php echo $ruangan->id; ?>" />
	</div>
</form>