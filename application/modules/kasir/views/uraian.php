<script type="text/javascript">
	$(document).ready(function() {
		
	});
</script>
<div class="row-fluid">
	<div class="span12 form-horizontal no-margin">
		<input type="hidden" id="counter" value="<?php echo $counter; ?>" />
		<?php
			foreach ($data as $index => $row) {
		?>
		<div class="control-group">
			<label class="control-label" for="pegawai_id_<?php echo $index; ?>"><?php echo $row['nama']; ?></label>
			<div class="controls controls-row">
				<?php
					if ($row['tarif_langsung']) {
				?>
				<?php
					$first = array();
					$dokter = new stdClass();
					$dokter->id = 0;
					$dokter->nama = "- Pilih Dokter -";
					$first[] = $dokter;
					$pegawai_list = array_merge($first, $pegawai_list);
				?>
				<select class="uraian_dokter span8" id="pegawai_id_<?php echo $index; ?>" name="pegawai_id[]">
					<?php
						foreach ($pegawai_list as $val) {
							if ($row['pegawai_id'] == $val->id) {
								echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
							} else {
								echo "<option value=\"{$val->id}\">{$val->nama}</option>";
							}
						}
					?>
				</select>
				<?php
					}
				?>
				<table class="table table-striped table-bordered no-margin">
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
							<td style="text-align: right;"><?php echo number_format($row['jumlah_proporsi'], 2, ',', '.'); ?></td>
							<td style="text-align: right;"><?php echo number_format($row['jumlah_langsung'], 2, ',', '.'); ?></td>
							<td style="text-align: right;"><?php echo number_format($row['jumlah_balancing'], 2, ',', '.'); ?></td>
							<td style="text-align: right;"><?php echo number_format($row['jumlah_kebersamaan'], 2, ',', '.'); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php
			}
		?>
	</div>
</div>