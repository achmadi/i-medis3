<script type="text/javascript">
	$(document).ready(function () {
		$('#pasien').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('kasir/load_data_pasien?unit='.$instalasi); ?>",
			"aoColumnDefs"		: [
									  { "bSortable": false, "aTargets": [ 3 ] }
								  ],
			"aoColumns"			: [
									  { sWidth: '90px' },
									  { sWidth: 'null' },
									  { sWidth: '77px' },
									  { sWidth: 'null' }
								  ]
		});
	});
</script>
<style type="text/css">
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	#pasien_processing {
		position:absolute;
		top: 50%;
		left: 50%;
		width:20em;
		height:2em;
		/*margin-top: -10em;*/ /*set to a negative number 1/2 of your height*/
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
				<div class="widget-body">
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="pasien">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>No. Register</th>
									<th>No. Medrec</th>
									<th>Nama</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="4" class="dataTables_empty">Loading data from server</td>
								</tr>
							</tbody>
						</table>
						<div class="clearfix"></div>
					</div
				</div>
			</div>
		</div>
	</div>
</div>