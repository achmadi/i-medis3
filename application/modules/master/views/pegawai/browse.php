<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#pegawai').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('master/pegawai/load_data'); ?>",
			"sScrollX"			: "100%",
			"sScrollXInner"		: "216%",
			"bScrollCollapse"	: true,
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 15 ] }
								  ],
			"fnInitComplete"	: function () {
				new FixedColumns(oTable, {
					"iLeftColumns": 1,
					"iLeftWidth": 150,
					"iRightColumns": 1,
					"iRightWidth": 58
				});
			},
			"bStateSave": true
		});
		
		$('#pegawai').on('click', '.delete-row', function() {
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
						url: '<?php echo site_url('master/pegawai/delete'); ?>',
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
	#pegawai_processing {
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
<style type="text/css">
	.table thead th, .table thead td {
		text-align: center;
		vertical-align: middle;
	}
	.dashboard-wrapper .left-sidebar .widget .widget-body #dt_example .dataTables_scroll {
		clear: both;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">Pegawai</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="form-actions no-margin">
					<a id="tambah" class="btn btn-info bottom-margin pull-right" href="<?php echo site_url('master/pegawai/add'); ?>" data-original-title="">Tambah</a>
					<div class="clearfix"></div>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="pegawai">
							<thead>
								<tr>
									<th>Nip</th>
									<th>Nama</th>
									<th>No. Rekening</th>
									<th>NPWP</th>
									<th>Jabatan</th>
									<th>Golongan</th>
									<th>Kelompok</th>
									<th>Unit</th>
									<th>Indeks Langsung</th>
									<th>Indeks Basic</th>
									<th>Indeks Posisi</th>
									<th>Indeks Emergency</th>
									<th>Indeks Resiko</th>
									<th>Indeks Pendidikan</th>
									<th>Indeks Masa Kerja</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="16" class="dataTables_empty">Loading data from server</td>
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
