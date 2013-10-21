<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#pendidikan').dataTable( {
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('master/pendidikan/load_data'); ?>",
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 1 ] }
								  ],
			"aoColumns"			: [
									  { sWidth: 'null' },
									  { sWidth: '45px' }
								  ],
			"bStateSave": true
		});
		
		$("#tambah_button").on("click", function () {
			$.getJSON("<?php echo site_url('master/pendidikan/get_data'); ?>?pendidikan_id=0", function(json) {
				$("#title_modal").text("Tambah Pendidikan");
				
				$("#nama").val(json.data.nama)
				
				$("#id").val(json.data.id)
				
				$('#pendidikan_modal').modal('show');
			});
			return false;
		});
		
		$('#pendidikan').on('click', '.edit-row', function() {
			var pendidikan_id = $(this).data('id');
			$.getJSON("<?php echo site_url('master/pendidikan/get_data'); ?>?pendidikan_id=" + pendidikan_id, function(json) {
				$("#title_modal").text("Edit Pendidikan");
				
				$("#nama").val(json.data.nama)
				
				$("#id").val(json.data.id)
				
				$('#pendidikan_modal').modal('show');
			});
			return false;
		});
		
		$('#pendidikan').on('click', '.delete-row', function() {
			var id = $(this).data('id');
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
						url: '<?php echo site_url('master/pendidikan/delete'); ?>',
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
		
		var pendidikanApp = {
			initPendidikanModal: function () {
				$("#pendidikan_form").validate({
					rules: {
						nama: { required: true, minlength: 1 }
					},
					messages: {
						nama: "Nama Pendidikan diperlukan."
					},
					errorContainer: "#validationSummary",
					errorLabelContainer: "#validationSummary ul",
					wrapper: "li",
					submitHandler: function (form) {
						pendidikanApp.addPendidikan($(form));
					}
				});
			},
			addPendidikan: function (form) {
				var url = "<?php echo site_url('master/pendidikan/simpan'); ?>";
				var postData = form.serialize();
				$.post(url, postData, function (data) {
					if (data.toLowerCase() === "ok") {
						oTable.fnDraw();
						alertify.success("Record telah di simpan!");
						return;
					}
					//alert("Something went wrong. Please retry!");
				});
				$('#pendidikan_modal').modal('hide');
			}
		};
		
		pendidikanApp.initPendidikanModal();
		
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
	.form-horizontal .control-group {
		margin-bottom: 2px;
	}
	#pendidikan_processing {
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
					<div class="title">Pendidikan</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="form-actions no-margin">
					<a id="tambah_button" href="#" class="btn btn-info bottom-margin pull-right" data-original-title="">Tambah</a>
					<div class="clearfix"></div>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="pendidikan">
							<thead>
								<tr>
									<th>Nama Pendidikan</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="2" class="dataTables_empty">Loading data from server</td>
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
<form class="form-horizontal no-margin" id="pendidikan_form" name="pendidikan_form" method="post" action="">
	<div class="modal fade" id="pendidikan_modal">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h4 id="title_modal">Tambah/Edit Agama</h4>
		</div>
		<div class="modal-body">
			<div class="modal-body">
				<div class="row-fluid">
					<div class="span12">

						<div class="control-group">
							<label class="control-label" for="nama">Nama Pendidikan</label>
							<div class="controls controls-row">
								<input class="span12" type="text" id="nama" name="nama" maxlength="30" placeholder="Nama Pendidikan" value="" autocomplete="off" />
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