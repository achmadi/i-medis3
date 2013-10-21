<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#unit_kerja_pegawai').dataTable( {
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('master/unit_kerja_pegawai/load_data'); ?>",
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 1 ] }
								  ],
			"aoColumns"			: [
									  { sWidth: 'null' },
									  { sWidth: '45px' }
								  ],
			"bStateSave": true
		});
		
		$('#unit_kerja_pegawai').on('click', '.delete-row', function() {
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
						url: '<?php echo site_url('master/unit_kerja_pegawai/delete'); ?>',
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
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">Unit Kerja Pegawai</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="form-actions no-margin">
					<a id="tambah" class="btn btn-info bottom-margin pull-right" href="<?php echo site_url('master/unit_kerja_pegawai/add'); ?>" data-original-title="">Tambah</a>
					<div class="clearfix"></div>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="unit_kerja_pegawai">
							<thead>
								<tr>
									<th>Nama Unit Kerja Pegawai</th>
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
