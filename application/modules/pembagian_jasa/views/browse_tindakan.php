<script type="text/javascript">
	$(document).ready(function () {
		var oTable = $('#tindakan').dataTable({
			"sPaginationType": "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('pembagian_jasa/pembagian_jasa/load_data_tindakan?unit_id='.$unit_id); ?>",
			"aoColumns"			: [
									  { sWidth: 'null' },
									  { sWidth: 'null' }
								  ],

		});
		oTable.fnFilter(<?php echo $unit_id; ?>, 1 );
		$("#jenis_pelayanan_id").change(function() {
			oTable.fnFilter($("#jenis_pelayanan_id").val(), 1 );
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
	<div class="row-fluid" style="border-top: 0; border-bottom: 1px solid #E5E5E5;">
		<div class="span12">
			<div class="form-actions" style="padding-left: 5px;">
				<div class="form-horizontal no-margin span6">
					<div class="control-group">
						<label class="control-label" for="jenis_pelayanan_id">Jenis Pelayanan</label>
						<div class="controls controls-row">
							<select id="jenis_pelayanan_id" name="jenis_pelayanan_id">
								<?php
									foreach ($unit_list as $index => $val) {
										if ($unit_id == $val->id) {
											echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
										} else {
											echo "<option value=\"{$val->id}\">{$val->nama}</option>";
										}
									}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				
				<div class="widget-body">
					
					<div id="dt_example" class="example_alt_pagination">
						<table class="table table-striped table-condensed table-striped table-hover table-bordered pull-left" id="tindakan">
							<thead>
								<tr>
									<th>Nama Pelayanan</th>
									<th>Unit ID</th>
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