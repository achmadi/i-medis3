<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#pendaftaran').dataTable({
			"bJQueryUI"			: true,
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('pendaftaran_igd/load_data/'.$browse); ?>",
			"sScrollX"			: "100%",
			"sScrollXInner"		: " 2160px",
			"bScrollCollapse"	: true,
			"fnInitComplete"	: function () {
				new FixedColumns(oTable, {
					"iLeftColumns": 1,
					"iLeftWidth": 150,
					"iRightColumns": 1,
					"iRightWidth": 150
				});
			},
			"aoColumns"			: [
									  { sWidth: '138px' },
									  { sWidth: '79px' },
									  { sWidth: '77px' },
									  { sWidth: '250px' },
									  { sWidth: '88px' },
									  { sWidth: '250px' },
									  { sWidth: '88px' },
									  { sWidth: '80px' },
									  { sWidth: '150px' },
									  { sWidth: '150px' },
									  { sWidth: '150px' },
									  { sWidth: '150px' },
									  { sWidth: '200px' },
									  { sWidth: null }
								  ]
		});
		
		$('#pendaftaran').on('click', '.delete-row', function() {
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
						url: '<?php echo site_url('pendaftaran_igd/delete'); ?>',
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
		
		$('#pendaftaran').on('click', '.batal-row', function() {
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
			alertify.confirm("Batalkan record tersebut?", function (e) {
				if (e) {
					$.ajax({
						type: 'get',
						url: '<?php echo site_url('pendaftaran_igd/batal'); ?>',
						data: 'id=' + id,
						success: function() {
							oTable.fnDraw();
							alertify.success("Record telah dibatalkan dari database!");
						},
						error: function() {
							alertify.error("Pembatalan record gagal!");
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
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title"><?php echo $title; ?></div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="pendaftaran">
							<thead>
								<tr>
									<th>Tanggal/Jam</th>
									<th>No. Register</th>
									<th>No. Medrec</th>
									<th>Nama</th>
									<th>Jenis Kelamin</th>
									<th>Alamat</th>
									<th>Tanggal Lahir</th>
									<th>Agama</th>
									<th>Pendidikan</th>
									<th>Pekerjaan</th>
									<th>Rujukan</th>
									<th>Cara Pembayaran</th>
									<th>Dokter</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="14" class="dataTables_empty">Loading data from server</td>
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