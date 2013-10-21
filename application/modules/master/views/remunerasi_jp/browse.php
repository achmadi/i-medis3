<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {

		var oTable = $('#remunerasi_jp').dataTable( {
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('master/remunerasi_jp/load_data'); ?>",
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 4 ] }
								  ],
			"aoColumns"			: [
									  { sWidth: '20%' },
									  { sWidth: '20%' },
									  { sWidth: '20%' },
									  { sWidth: '20%' },
									  { sWidth: '20%' }
								  ]
		});
		
		$('#remunerasi_jp').on('click', '.delete-row', function() {
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
						url: '<?php echo site_url('master/remunerasi_jp/delete'); ?>',
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
			<div class="widget" style="border: none; margin-bottom: 10px;">
				<ul class="breadcrumb-beauty">
					<li><a href="<?php echo site_url('master/jenis_pelayanan'); ?>">Jenis Pelayanan</a></li>
					<li><a href="#"><?php echo $jenis_pelayanan->nama; ?></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">Remunerasi Jasa Pelayanan</div>
					<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
				</div>
				<div class="form-actions no-margin">
					<a id="tambah" class="btn btn-info bottom-margin pull-right" href="<?php echo site_url('master/remunerasi_jp/add?jenis_pelayanan_id='.$jenis_pelayanan->id); ?>" data-original-title="">Tambah</a>
					<div class="clearfix"></div>
				</div>
				<div class="widget-body">
					
					<?php
						//message, error, notice
						$notification = $this->session->flashdata('notification');
						$notification_type = $notification['type'];
						$notification_title = ucfirst($notification['type']);;
						$notification_message = $notification['message'];
						if ($notification_message) {
					?>
					<div class="alert alert-block alert-<?php echo $notification_type; ?> fade in">
						<button data-dismiss="alert" class="close" type="button">×</button>
						<h4 class="alert-heading"><?php echo $notification_title; ?>!</h4>
						<p><?php echo $notification_message; ?></p>
					</div>
					<?php
						}
					?>
					
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="remunerasi_jp">
							<thead>
								<tr>
									<th>Pemda</th>
									<th>Jasa yang dibagikan</th>
									<th>Manajemen</th>
									<th>Sisa</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="5" class="dataTables_empty">Loading data from server</td>
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
