<script type="text/javascript">
	$(document).ready(function () {
		$('#tindakan').dataTable({
			"sPaginationType": "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('pembagian_jasa/pembagian_jasa/load_data_tindakan?unit_id='.$unit_id); ?>",
			"aoColumns"			: [
									  { sWidth: 'null' }
								  ]
		});
	});
</script>
<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	span.gi {
		color: #CCCCCC;
		font-weight: bold;
		vertical-align: top;
	}
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="tindakan">
							<thead>
								<tr>
									<th>Nama Pelayanan</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="dataTables_empty">Loading data from server</td>
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