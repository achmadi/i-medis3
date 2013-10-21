<script type="text/javascript">
	$(document).ready(function () {
		
		var oTable = $('#pendaftaran').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('pendaftaran_irj/load_data/'.$browse); ?>?tanggal=".$tanggal_current,
			"sScrollX"			: "100%",
			"sScrollXInner"		: "216%",
			"bScrollCollapse"	: true,
			"aoColumns"			: [
									  { sWidth: '5%' },
									  { sWidth: '79px' },
									  { sWidth: '77px' },
									  { sWidth: 'null' },
									  { sWidth: '88px' },
									  { sWidth: '250px' },
									  { sWidth: '88px' },
									  { sWidth: '80px' },
									  { sWidth: '150px' },
									  { sWidth: '150px' },
									  { sWidth: '150px' },
									  { sWidth: '150px' },
									  { sWidth: '150px' },
									  { sWidth: '200px' },
									  { sWidth: null }
								  ],
			"fnInitComplete"	: function () {
				new FixedColumns(oTable, {
					"iLeftColumns": 1,
					"iLeftWidth": 150,
					"iRightColumns": 1,
					"iRightWidth": 150
				});
			}

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
						url: '<?php echo site_url('pendaftaran_irj/delete'); ?>',
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
						url: '<?php echo site_url('pendaftaran_irj/batal'); ?>',
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
					<div class="title"><?php echo $title; ?></div>
					<div class="tools pull-right">
						<div class="btn-group">
							<?php
								if (strtotime($tanggal_current) > strtotime($tanggal_awal)) {
									$disabled_prev = false;
								}
								else {
									$disabled_prev = true;
								}
							?>
							<a id="prev-date" class="btn btn-small <?php echo $disabled_prev ? "" : "btn-info"; ?>" data-original-title="" <?php echo $disabled_prev ? "disabled=\"disabled\"" : ""; ?>>
								<i class="icon-chevron-left <?php echo $disabled_prev ? "" : "icon-white"; ?>" data-original-title="Prev"></i> Tanggal Sebelumnya
							</a>
							<?php
								if (strtotime($tanggal_current) < strtotime($tanggal_akhir)) {
									$disabled_next = false;
								}
								else {
									$disabled_next = true;
								}
							?>
							<a id="next-date" class="btn btn-small <?php echo $disabled_next ? "" : "btn-info"; ?>" data-original-title="" <?php echo $disabled_next ? "disabled=\"disabled\"" : ""; ?>>
								Tanggal Berikutnya <i class="icon-chevron-right <?php echo $disabled_next ? "" : "icon-white"; ?>" data-original-title="Next"></i>
							</a>
						</div>
					</div>
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
									<th>Poliklinik</th>
									<th>Dokter</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="15" class="dataTables_empty">Loading data from server</td>
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