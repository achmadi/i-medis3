<script type="text/javascript">
	$(document).ready(function() {

	});
</script>
<div class="row-fluid">
	<div class="span12 form-horizontal no-margin">
		
		<div class="control-group" style="margin-bottom: 0;">
			<label class="control-label">Tanggal</label>
			<div class="controls controls-row">
				<label class="span12" style="padding-top: 5px;"><?php echo $data->tanggal; ?></label>
			</div>
		</div>
		<div class="control-group" style="margin-bottom: 0;">
			<label class="control-label">No. Register</label>
			<div class="controls controls-row">
				<label class="span12" style="padding-top: 5px;"><?php echo $data->no_register; ?></label>
			</div>
		</div>
		<div class="control-group" style="margin-bottom: 0;">
			<label class="control-label">No. Medrec</label>
			<div class="controls controls-row">
				<label class="span12" style="padding-top: 5px;"><?php echo $data->no_medrec; ?></label>
			</div>
		</div>
		<div class="control-group" style="margin-bottom: 0;">
			<label class="control-label">Nama</label>
			<div class="controls controls-row">
				<label class="span12" style="padding-top: 5px;"><?php echo $data->nama; ?></label>
			</div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<div id="accordion1" class="accordion no-margin">
			<?php
				foreach ($data->details as $index => $pembagian_jasa_detail) {
			?>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse<?php echo $index; ?>" data-original-title="">
						<i class="icon-user"></i><?php echo $pembagian_jasa_detail->nama_tarif_pelayanan; ?>
					</a>
				</div>
				<div id="collapse<?php echo $index; ?>" class="accordion-body collapse" style="height: 0px;">
					<div class="accordion-inner">
						<div class="row-fluid">
							<div class="span6 form-horizontal no-margin">
								<div class="control-group" style="margin-bottom: 0;">
									<label class="control-label">Nama Pelayanan</label>
									<div class="controls controls-row">
										<label class="span12" style="padding-top: 5px;"></label>
									</div>
								</div>
								<div class="control-group" style="margin-bottom: 0;">
									<label class="control-label">Dokter</label>
									<div class="controls controls-row">
										<label class="span12" style="padding-top: 5px;"></label>
									</div>
								</div>
								<div class="control-group" style="margin-bottom: 0;">
									<label class="control-label">Harga/Unit</label>
									<div class="controls controls-row">
										<label class="span12" style="padding-top: 5px;"></label>
									</div>
								</div>
								<div class="control-group" style="margin-bottom: 0;">
									<label class="control-label">Quantity</label>
									<div class="controls controls-row">
										<label class="span12" style="padding-top: 5px;"></label>
									</div>
								</div>
								<div class="control-group" style="margin-bottom: 0;">
									<label class="control-label">Jumlah</label>
									<div class="controls controls-row">
										<label class="span12" style="padding-top: 5px;"></label>
									</div>
								</div>
							</div>
							<div class="span6 form-horizontal no-margin">
								<div class="control-group" style="margin-bottom: 0;">
									<label class="control-label">dokter</label>
									<div class="controls controls-row">
										<tabel>
											<thead>
												<tr>
													<th>Proporsi</th>
													<th>Langsung</th>
													<th>Balancing</th>
													<th>Kebersamaan</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
											</tbody>	
										</tabel>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
				}
			?>
		</div>
	</div>
</div>