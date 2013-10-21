<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {

		var oTable = $('#komponen_tarif_askes').dataTable( {
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('master/komponen_tarif_askes/load_data?kelompok_pelayanan_askes_id='.$kelompok_pelayanan_askes->id); ?>",
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 4 ] }
								  ],
			"aaSorting"			: [[ 3, "asc" ]],
			"aoColumns"			: [
									  { sWidth: 'null' },
									  { sWidth: '120px' },
									  { sWidth: '120px' },
									  { sWidth: '120px' },
									  { sWidth: '120px' },
									  { sWidth: '120px' },
									  { sWidth: '120px' },
									  { sWidth: '120px' },
									  { sWidth: '120px' },
									  { sWidth: '90px' }
								  ]
		});
		
		$('#komponen_tarif_askes').on('click', '.delete-row', function() {
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
						url: '<?php echo site_url('master/komponen_tarif_askes/delete'); ?>',
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
		
		$("#tarif_pelayanan").on("click", ".order_up", function(event){
			var id = $(this).data('id');
			$.ajax({
				type: 'get',
				url: '<?php echo site_url('master/tarif_pelayanan/order_up'); ?>',
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

		$("#tarif_pelayanan").on("click", ".order_down", function(event){
			var id = $(this).data('id');
			$.ajax({
				type: 'get',
				url: '<?php echo site_url('master/tarif_pelayanan/order_down'); ?>',
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
	#tarif_pelayanan thead th {
		text-align: center;
	}
	#tarif_pelayanan td:nth-child(2),
	#tarif_pelayanan td:nth-child(3) {
		text-align: right;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget" style="border: none; margin-bottom: 10px;">
				<ul class="breadcrumb-beauty">
					<li><a href="<?php echo site_url('master/kelompok_pelayanan_askes'); ?>">Kelompok Pelayanan Askes</a></li>
					<li><a href="#"><?php echo $kelompok_pelayanan_askes->nama; ?></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">Komponen Tarif Askes</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="form-actions no-margin">
					<a id="tambah" class="btn btn-info bottom-margin pull-right" href="<?php echo site_url('master/komponen_tarif_askes/add?kelompok_pelayanan_askes_id='.$kelompok_pelayanan_askes->id); ?>" data-original-title="">Tambah</a>
					<div class="clearfix"></div>
				</div>
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="komponen_tarif_askes">
							<thead>
								<tr>
									<th rowspan="2" style="text-align: center; valign: middle;">Jenis Pelayanan</th>
									<th>Tarif</th>
									<th colspan="6">Presentasi Komponen Tarif</th>
									<th rowspan="2">Ordering</th>
									<th rowspan="2">Action</th>
								</tr>
								<tr>
									<th>PT Askes</th>
									<th>BMHP (%)</th>
									<th>Sarana (%)</th>
									<th>Yan (%)</th>
									<th>Medik (%)</th>
									<th>Anest (%)</th>
									<th>Total (%)</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="10" class="dataTables_empty">Loading data from server</td>
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
