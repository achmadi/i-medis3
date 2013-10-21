<script type="text/javascript">
	
	function select_pasien(id) {
		var old_id = $("#pasien_id").val();
		if (old_id !== id) {
			$.getJSON("<?php echo site_url('pendaftaran_irj/get_pasien_by_id'); ?>?pasien_id=" + id, function(json) {
				$("#pasien_id").val(json.pasien.id);
				$("#no_medrec").val(json.pasien.no_medrec);
				$("#nama").val(json.pasien.nama);
				$("#alamat").val(json.pasien.alamat_jalan);
			});
		}
		$("#pasien_modal").modal("hide");
		return false;
	}
	
	function select_tindakan(id) {
		var counter = $('#tindakan_counter').val();
		var old_id = $('#tindakan_id_' + counter).val();
		if (old_id !== id) {
			$.ajax({
				type		: "GET",
				url			: "<?php echo site_url('pembagian_jasa/get_tindakan'); ?>",
				data		: "tindakan_id=" + id + "&counter=" + counter,
				dataType	: "json",
				cache		: false,
				beforeSend: function() {
					//$('#loading_dokter').show();
					//$('#loading_dokter').css("display", "inline");
				},
				success: function() {
					//$('#loading_dokter').hide();
				},
				complete: function(json, status) {
					if (status === "success" || status === "notmodified") {
						var tindakan = json.responseJSON.tindakan;
						$('#persen_pemda_' + counter).val(tindakan.pemda);
						$('#persen_dibagikan_' + counter).val(tindakan.dibagikan);
						$('#persen_manajemen_' + counter).val(tindakan.manajemen);
						$('#persen_sisa_' + counter).val(tindakan.sisa);
						
						$("#table_penerima_jp_detail_" + counter).find("tbody").append(tindakan.html);
									
						var old_total = $('#jumlah_' + counter).val();
						$('#tindakan_id_' + counter).val(tindakan.id);
						$('#disp_tindakan_' + counter).val(tindakan.nama);
						$('#jasa_sarana_' + counter).val(tindakan.jasa_sarana);
						$('#jasa_pelayanan_' + counter).val(tindakan.jasa_pelayanan);
						var tarif = parseFloat(tindakan.jasa_sarana) + parseFloat(tindakan.jasa_pelayanan);
						var qty = 1;
						var sum = tarif * qty;
						$('#harga_satuan_' + counter).val(tarif);
						$('#disp_harga_satuan_' + counter).autoNumeric('set', tarif);
						$('#quantity_' + counter).val(qty);
						$('#disp_quantity_' + counter).val(qty);
						$('#jumlah_' + counter).val(sum);
						$('#label_jumlah_' + counter).autoNumeric('set', sum + '');

						var total = (parseFloat($('#total').val()) - parseFloat(old_total)) + parseFloat(sum);
						$('#total').val(total);
						$('#label_total').autoNumeric('set', total + '');

						var jasa_pelayanan = parseFloat(tindakan.jasa_pelayanan);
						
						var pemda = jasa_pelayanan * (parseFloat($('#persen_pemda_' + counter).val())/100);
						var dibagikan = jasa_pelayanan - pemda;
						var manajemen = dibagikan * (parseFloat($('#persen_manajemen_' + counter).val())/100);
						var sisa = dibagikan - manajemen;
						
						$('#insentif_pemda_' + counter).val(pemda);
						$('#insentif_manajemen_' + counter).val(manajemen);
						
						var rows = $("#table_penerima_jp_detail_" + counter + " tbody tr");
						var persen_proporsi = 0;
						var jml_proporsi = 0;
						var persen_langsung = 0;
						var jml_langsung = 0;
						var persen_kebersamaan = 0;
						var jml_kebersamaan = 0;
						
						rows.each(function(index) {
							
							$('#penerima_jp_detail_tanggal_' + counter + '_' + (index + 1)).val($("#tanggal").val());
							$('#penerima_jp_detail_pasien_id_' + counter + '_' + (index + 1)).val($("#pasien_id").val());
							$('#penerima_jp_detail_quantity_' + counter + '_' + (index + 1)).val($('#quantity_' + counter).val());
							
							persen_proporsi = $('#penerima_jp_detail_persen_proporsi_' + counter + '_' + (index + 1)).val();
							jml_proporsi = sisa * (persen_proporsi/100);
							$('#penerima_jp_detail_jumlah_proporsi_' + counter + '_' + (index + 1)).val(jml_proporsi);

							persen_langsung = $('#penerima_jp_detail_persen_langsung_' + counter + '_' + (index + 1)).val();
							jml_langsung = jml_proporsi * (persen_langsung/100);
							$('#penerima_jp_detail_jumlah_langsung_' + counter + '_' + (index + 1)).val(jml_langsung);
							
							persen_kebersamaan = $('#penerima_jp_detail_persen_kebersamaan_' + counter + '_' + (index + 1)).val();
							jml_kebersamaan = jml_proporsi * (persen_kebersamaan/100);
							$('#penerima_jp_detail_jumlah_kebersamaan_' + counter + '_' + (index + 1)).val(jml_kebersamaan);
						});
					}
				}
			});
		}
		$("#tindakan_modal").modal("hide");
		return false;
	}
	
	function isNumber(inputStr) {
		for (var i = 0; i < inputStr.length; i++) {
			var oneChar = inputStr.substring(i, i + 1);
			if (oneChar < "0" || oneChar > "9") {
				return false;
			}
		}
		return true;
	}

	function serializePHP(a) {
		var serial = "";
		var length = 0;
		for (var i in a) {
			if (isNumber(i)) {
				serial += "i:" + i + ";";
			}
			else {
				serial += "s:" + String(i).length + ":\"" + String(i) + "\";";
			}
			switch (typeof a[i]) {
				// Integer: i:value;
				case "number":
					serial += "i:" + a[i] + ";";
					break;
				// String: s:size:value;
				case "string":
					serial += "s:" + String(a[i]).length + ":\"" + a[i] + "\";";
					break;
				// Boolean: b:value; (does not store "true" or "false", does store '1' or '0')
				case "boolean":
					serial += "b:" + (a[i] ? '1' : '0') + ";";
					break;
				// Null: N;
				case "null":
					break;
				// Array: a:size:{key definition;value definition;(repeated per element)}
				// Object: O:strlen(object name):object name:object size:{s:strlen(property name):property name:property definition;(repeated per property)}
				case "object":
					serial += serializePHP(a[i]);
					break;
			}
			length++;
		}
		serial = "a:" + length + ":{" + serial + "}";
		return serial;
	}
	
    $(document).ready(function() {
		
		$('#label_total').autoNumeric('init');
		
		function getISODateTime(d){
			var s = function(a,b) { return(1e15+a+"").slice(-b); };

			if (typeof d === 'undefined'){
				d = new Date();
			};

			return d.getFullYear() + '-' +
				s(d.getMonth()+1,2) + '-' +
				s(d.getDate(),2) + ' ' +
				s(d.getHours(),2) + ':' +
				s(d.getMinutes(),2) + ':' +
				s(d.getSeconds(),2);
		}
	   
	   function getISODate(d){
			var s = function(a,b){ return(1e15+a+"").slice(-b); };

			if (typeof d === 'undefined'){
				d = new Date();
			};

			return d.getFullYear() + '-' +
				s(d.getMonth()+1,2) + '-' +
				s(d.getDate(),2);
		}
	   
		var disp_tanggal = $('#disp_tanggal').datepicker({
			format: 'dd/mm/yyyy'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date);
			date_str = getISODateTime(date);
			$('#tanggal').val(date_str);
			disp_tanggal.hide();
		}).data('datepicker');
		
		$("#button_tambah").click(function() {
			var $tr = $('#row_pembagian_jasa_detail_clone').clone();
			
			var counter = parseInt($("#counter").val());
			counter++;
			$("#counter").val(counter);
			
			$tr.attr('id', '')
			   .attr('style', '');
			
			$tr.find('#clone_pembagian_jasa_detail_id')
				.attr('id', 'pembagian_jasa_detail_id_' + counter)
				.attr('name', 'pembagian_jasa_detail_id[]')
				.attr('value', 'new_pembagian_jasa_detail_id_' + counter);
			
			$tr.find('#clone_tindakan_id')
				.attr('id', 'tindakan_id_' + counter)
				.attr('name', 'tindakan_id[]')
				.attr('value', 'new_tindakan_id_' + counter);
		
			$tr.find('#clone_jasa_sarana')
				.attr('id', 'jasa_sarana_' + counter)
				.attr('name', 'jasa_sarana[]');
			$tr.find('#clone_jasa_pelayanan')
				.attr('id', 'jasa_pelayanan_' + counter)
				.attr('name', 'jasa_pelayanan[]');
			
			$tr.find('#clone_persen_pemda')
				.attr('id', 'persen_pemda_' + counter);
			$tr.find('#clone_persen_dibagikan')
				.attr('id', 'persen_dibagikan_' + counter);
			$tr.find('#clone_persen_manajemen')
				.attr('id', 'persen_manajemen_' + counter);
			$tr.find('#clone_persen_sisa')
				.attr('id', 'persen_sisa_' + counter);
			$tr.find('#clone_insentif_pemda')
				.attr('id', 'insentif_pemda_' + counter)
				.attr('name', 'insentif_pemda[]');
			$tr.find('#clone_insentif_manajemen')
				.attr('id', 'insentif_manajemen_' + counter)
				.attr('name', 'insentif_manajemen[]');
		
			$tr.find('#clone_label_tindakan')
				.attr('id', 'label_tindakan_' + counter);
		
			$tr.find('#clone_tindakan')
				.attr('id', 'div_tindakan_' + counter);
			
			$tr.find('#clone_disp_tindakan')
				.attr('id', 'disp_tindakan_' + counter);
			
			$tr.find('#clone_dokter_id')
				.attr('id', 'dokter_id_' + counter)
				.attr('name', 'dokter_id[]');
		
			$tr.find('#clone_label_dokter')
				.attr('id', 'label_dokter_' + counter);
		
			$tr.find('#clone_disp_dokter')
				.attr('id', 'disp_dokter_' + counter);
			
			//var poliklinik_id = $('#poliklinik_id').val();
			//var dokter_id = $('#master_dokter_id').val();
			$.ajax({
				url: "<?php echo site_url('pembagian_jasa/pembagian_jasa/get_dokter'); ?>",
				//data: "poliklinik_id=" + poliklinik_id + "&dokter_id=" + dokter_id, 
				success: function(html){
					//$("#dokter_id_" + counter).val(dokter_id);
					$("#disp_dokter_" + counter).html(html);
				}
			});
			
			$tr.find('#clone_harga_satuan')
				.attr('id', 'harga_satuan_' + counter)
				.attr('name', 'harga_satuan[]');
		
			$tr.find('#clone_label_harga_satuan')
				.attr('id', 'label_harga_satuan_' + counter);
		
			$tr.find('#clone_disp_harga_satuan')
				.attr('id', 'disp_harga_satuan_' + counter)
				.autoNumeric('init');
			
			$tr.find('#clone_quantity')
				.attr('id', 'quantity_' + counter)
				.attr('name', 'quantity[]');
		
			$tr.find('#clone_label_quantity')
				.attr('id', 'label_quantity_' + counter);
		
			$tr.find('#clone_disp_quantity')
				.attr('id', 'disp_quantity_' + counter);
			
			$tr.find('#clone_jumlah')
				.attr('id', 'jumlah_' + counter)
				.attr('name', 'jumlah[]')
				.attr('value', '0');
			
			$tr.find('#clone_label_jumlah')
				.attr('id', 'label_jumlah_' + counter)
				.autoNumeric('init');
			
			$("#table_pembagian_jasa_detail").find("tbody").append($tr);
			
			var table_penerima_jp_detail = $('#clone_table_penerima_jp_detail').clone();
			table_penerima_jp_detail.attr('id', 'table_penerima_jp_detail_' + counter);
			$("#table_penerima_jp_detail").append(table_penerima_jp_detail);

			var row_1 = $('#clone_row_penerima_jp_detail').clone();
			$("#table_penerima_jp_detail_" + counter).find("tbody").append(row_1);
			
			$("#button_uraian").attr('id', '').addClass('button_uraian');
			$("#button_1").attr('id', '').addClass('button_tambah_simpan').text('Simpan').button();
			$("#button_2").attr('id', '').addClass('button_tambah_batal').text('Batal').button();
			
			$(".button_edit").prop('disabled', true);
			$(".button_hapus").prop('disabled', true);
			
			$("#button_tambah").prop('disabled', true);
			
			return false;
		});
		
		$('#table_pembagian_jasa_detail').on('click', '.button_tambah_simpan', function() {
			$tr = $(this).parent().parent();
					
			var counter = getCounter($tr);
			
			var tindakan = $("#disp_tindakan_" + counter).val();
			$tr.find('#label_tindakan_' + counter).text(tindakan).show();
			$tr.find('#div_tindakan_' + counter).remove();
			
			var dokter_id = $tr.find('#disp_dokter_' + counter).val();
			$tr.find("#dokter_id_" + counter).val(dokter_id);
			var dokter = $("#disp_dokter_" + counter + " option[value='" + dokter_id + "']").text();
			$tr.find('#disp_dokter_' + counter).remove();
			$tr.find('#label_dokter_' + counter).text(dokter).show();
			
			var harga_satuan = $("#disp_harga_satuan_" + counter).val();
			$tr.find('#disp_harga_satuan_' + counter).remove();
			$tr.find('#label_harga_satuan_' + counter).text(harga_satuan).show();
			
			var quantity = $("#disp_quantity_" + counter).val();
			$tr.find('#disp_quantity_' + counter).remove();
			$tr.find('#label_quantity_' + counter).text(quantity).show();
			
			$(".button_tambah_simpan").removeClass('button_tambah_simpan').addClass('button_edit');
			$(".button_edit").text('Edit');
			$(".button_tambah_batal").removeClass('button_tambah_batal').addClass('button_hapus');
			$(".button_hapus").text('Hapus');
			
			$(".button_edit").prop('disabled', false);
			$(".button_hapus").prop('disabled', false);
			
			$("#button_tambah").prop('disabled', false);
			return false;
		});
		
		$('#table_pembagian_jasa_detail').on('click', '.button_tambah_batal', function(event) {
			event.preventDefault;
			
			$tr = $(this).parent().parent();
					
			var counter = getCounter($tr);
			
			var sum = $('#jumlah_' + counter).val();
			
			var total = parseFloat($('#total').val()) - parseFloat(sum);
			$('#total').val(total);
			$('#label_total').autoNumeric('set', total + '');
			
			$(this).parent().parent().remove();
			
			$(".button_edit").prop('disabled', false);
			$(".button_hapus").prop('disabled', false);
			
			$("#button_tambah").prop('disabled', false);
			return false;
		});
		
		$('#table_pembagian_jasa_detail').on('click', '.button_edit', function() {
			$tr = $(this).parent().parent();
							
			var counter = getCounter($tr);
			
			var dokter_id = $tr.find('#dokter_id_' + counter).val();
			$tr.find('#label_dokter_' + counter).hide();
			$tr.children('td').eq(0).append('<select id="disp_dokter_' + counter + '" style="width: 100%;"></select>');
			
			$.ajax({
				url: "<?php echo site_url('master/unit/get_dokter'); ?>",
				data: "dokter_id=" + dokter_id, 
				success: function(html){
					$("#disp_dokter_" + counter).html(html);
				}
			});
			
			$tr.find(".button_edit").removeClass('button_edit').addClass('button_edit_simpan');
			$tr.find(".button_edit_simpan").text('Simpan');
			$tr.find(".button_hapus").removeClass('button_hapus').addClass('button_edit_batal');
			$tr.find(".button_edit_batal").text('Batal');
			
			$(".button_edit").prop('disabled', true);
			$(".button_hapus").prop('disabled', true);
			
			$("#button_tambah").prop('disabled', true);

			return false;
		});
		
		$('#table_pembagian_jasa_detail').on('click', '.button_hapus', function(event) {
			event.preventDefault;
			
			$tr = $(this).parent().parent();
					
			var counter = getCounter($tr);
			
			var sum = $('#jumlah_' + counter).val();
			
			var total = parseFloat($('#total').val()) - parseFloat(sum);
			$('#total').val(total);
			$('#label_total').autoNumeric('set', total + '');
			
			$('#table_penerima_jp_detail_' + counter).remove();
			
			$(this).parent().parent().remove();
			return false;
		});
		
		$('#table_pembagian_jasa_detail').on('click', '.button_edit_simpan', function(event) {
			$tr = $(this).parent().parent();
					
			var counter = getCounter($tr);
			
			var dokter_id = $tr.find('#disp_dokter_' + counter).val();
			$tr.find("#dokter_id_" + counter).val(dokter_id);
			var dokter = $("#disp_dokter_" + counter + " option[value='" + dokter_id + "']").text();
			$tr.find('#disp_dokter_' + counter).remove();
			$tr.find('#label_dokter_' + counter).text(dokter).show();
			
			$(".button_edit_simpan").removeClass('button_edit_simpan').addClass('button_edit');
			$(".button_edit").text('Edit');
			$(".button_edit_batal").removeClass('button_edit_batal').addClass('button_hapus');
			$(".button_hapus").text('Hapus');
			
			$(".button_edit").prop('disabled', false);
			$(".button_hapus").prop('disabled', false);
			
			$("#button_tambah").prop('disabled', false);
			return false;
		});
		
		$('#table_pembagian_jasa_detail').on('click', '.button_edit_batal', function(event) {
		
			$tr = $(this).parent().parent();
					
			var counter = getCounter($tr);
			
			$tr.find('#disp_dokter_' + counter).remove();
			$tr.find('#label_dokter_' + counter).show();
			
			$(".button_edit_simpan").removeClass('button_edit_simpan').addClass('button_edit');
			$(".button_edit").text('Edit');
			$(".button_edit_batal").removeClass('button_edit_batal').addClass('button_hapus');
			$(".button_hapus").text('Hapus');
			
			$(".button_edit").prop('disabled', false);
			$(".button_hapus").prop('disabled', false);
			
			$("#button_tambah").prop('disabled', false);
			return false;
		});
		
		$('#table_pembagian_jasa_detail').on('click', '.button_uraian', function(e) {
			e.preventDefault();
			
			$tr = $(this).parent().parent();
			var counter = getCounter($tr);
			
			var rows = $("#table_penerima_jp_detail_" + counter + " tbody tr");
			var data = new Array();
			rows.each(function(index) {
				data[index] = new Array();
				data[index]['id'] = $('#penerima_jp_detail_id_' + counter + '_' + (index + 1)).val();
				data[index]['tanggal'] = $('#penerima_jp_detail_tanggal_' + counter + '_' + (index + 1)).val();
				data[index]['kelompok_id'] = $('#penerima_jp_detail_kelompok_id_' + counter + '_' + (index + 1)).val();
				data[index]['jenis_kelompok'] = $('#penerima_jp_detail_jenis_kelompok_' + counter + '_' + (index + 1)).val();
				data[index]['nama'] = $('#penerima_jp_detail_nama_' + counter + '_' + (index + 1)).val();
				data[index]['unit_id'] = $('#penerima_jp_detail_unit_id_' + counter + '_' + (index + 1)).val();
				data[index]['pegawai_id'] = $('#penerima_jp_detail_pegawai_id_' + counter + '_' + (index + 1)).val();
				data[index]['pasien_id'] = $('#penerima_jp_detail_pasien_id_' + counter + '_' + (index + 1)).val();
				data[index]['tarif_langsung'] = $('#penerima_jp_detail_quantity_' + counter + '_' + (index + 1)).val();
				data[index]['persen_proporsi'] = $('#penerima_jp_detail_persen_proporsi_' + counter + '_' + (index + 1)).val();
				data[index]['jumlah_proporsi'] = $('#penerima_jp_detail_jumlah_proporsi_' + counter + '_' + (index + 1)).val();
				data[index]['persen_langsung'] = $('#penerima_jp_detail_persen_langsung_' + counter + '_' + (index + 1)).val();
				data[index]['jumlah_langsung'] = $('#penerima_jp_detail_jumlah_langsung_' + counter + '_' + (index + 1)).val();
				data[index]['persen_kebersamaan'] = $('#penerima_jp_detail_persen_kebersamaan_' + counter + '_' + (index + 1)).val();
				data[index]['jumlah_kebersamaan'] = $('#penerima_jp_detail_jumlah_kebersamaan_' + counter + '_' + (index + 1)).val();
			});
			var data_serial = serializePHP(data);
			var url = $(this).data('url') + '?counter=' + counter + '&data=' + escape(data_serial);
			$("#uraian_modal").removeData ('modal');
			$('#uraian_modal')
				.modal({
					remote: url,
					show: true
				});
		});
		
		$("#uraian_save_button").on("click", function () {
			var counter = $(this).parent().parent().find('#counter').val();
			var dokter = $(this).parent().parent().find('.uraian_dokter');
			var id;
			var aId;
			var index;
			dokter.each(function(index) {
				id = $(this).attr('id');
				aId = id.split('_');
				index = parseInt(aId[aId.length - 1]);
				$('#penerima_jp_detail_pegawai_id_' + counter + '_' + (index + 1)).val($(this).val());
			});
			$("#uraian_modal").modal("hide");
		});
		
		function getCounter(tr) {
			var id = tr.find('input').attr('id');
			var aId = id.split('_');
			return parseInt(aId[aId.length - 1]);
		}
		
		$('#table_pembagian_jasa_detail').on("click", ".uraian-row", function () {
			var oModal = $('#uraian-dlg');
			
			$tr = $(this).parent().parent();
					
			var counter = getCounter($tr);
			
			var rows = $("#table_penerima_jp_detail_" + counter + " tr:gt(0)");
			/*
			var html = '';
			var nama = '';
			var jumlah = 0;
			var jenis = 0;
			rows.each(function(index) {
				nama = $('#penerima_jp_detail_nama_' + counter + '_' + index).val();
				jumlah = $('#penerima_jp_detail_jumlah_' + counter + '_' + index).val();
				jenis = $('#penerima_jp_detail_jenis_' + counter + '_' + index).val();;
				html += '<div class="control-group">';
				html += '	<label class="control-label" for="disp_tanggal_keluar">' + nama + '</label>';
				html += '	<div class="controls controls-row">';
				html += '		<input type="hidden" id="tanggal_keluar" name="tanggal_keluar" value="" />';
				html += '		<input class="span4" type="text" id="disp_tanggal_keluar" name="disp_tanggal_keluar" data-date-format="dd/mm/yyyy" value="' + jumlah + '" readonly />';
				if (jenis >= 1 && jenis <= 3) {
					html += '		<select class="span8" id="pegawai_id_' +counter + '_' + index + '" name="pegawai_id"></select>';
				}
				html += '	</div>';
				html += '</div>';
			});
			*/
			var html = '';
			$.ajax({
				url: "<?php echo site_url('pembagian_jasa/pembagian_jasa/get_test'); ?>",
				success: function(dt){
					html = dt;
				}
			});
			$('#remunerasi_body', oModal).html(html);
		    oModal.modal({ show: true });
			return false;
		});
		
		$('#table_pembagian_jasa_detail').on("keyup", ".quantity_row", function (e) {
			$tr = $(this).parent().parent();
			var counter = getCounter($tr);
			var rows = $("#table_penerima_jp_detail_" + counter + " tbody tr");
			
			rows.each(function(index) {
				$('#penerima_jp_detail_quantity_' + counter + '_' + (index + 1)).val($('#disp_quantity_' + counter).val());
			});
		});
		
		$('#table_pembagian_jasa_detail').on("click", ".cari_tindakan", function (e) {
			$tr = $(this).parent().parent();
			var counter = getCounter($tr);
			$('#tindakan_counter').val(counter);
			var unit_id = $('#unit_id').val();
			var url = $('#tindakan_modal').data('remote') + "?unit_id=" + unit_id;
			$('#tindakan_modal')
				.modal({
					remote: url,
					show: true
				});
			return false;
		});
		
		
		if ($('#rawat_jalan').attr('checked')) {
			$('#disp_sd').hide();
			$('#disp_sd_tanggal').hide();
			$('#disp_poliklinik').show();
			$('#disp_kelas').hide();
			$('#disp_ruangan').hide();
		}
		$('#rawat_jalan').click(function() {
			$('#disp_sd').hide();
			$('#disp_sd_tanggal').hide();
			$('#disp_poliklinik').show();
			$('#disp_kelas').hide();
			$('#disp_ruangan').hide();
		});
		
		if ($('#rawat_darurat').attr('checked')) {
			$('#disp_sd').hide();
			$('#disp_sd_tanggal').hide();
			$('#disp_poliklinik').hide();
			$('#disp_kelas').hide();
			$('#disp_ruangan').hide();
		}
		$('#rawat_darurat').click(function() {
			$('#disp_sd').hide();
			$('#disp_sd_tanggal').hide();
			$('#disp_poliklinik').hide();
			$('#disp_kelas').hide();
			$('#disp_ruangan').hide();
		});
		
		if ($('#rawat_inap').attr('checked')) {
			$('#disp_sd').show();
			$('#disp_sd_tanggal').show();
			$('#disp_poliklinik').hide();
			$('#disp_kelas').show();
			$('#disp_ruangan').show();
		}
		$('#rawat_inap').click(function() {
			$('#disp_sd').show();
			$('#disp_sd_tanggal').show();
			$('#disp_poliklinik').hide();
			$('#disp_kelas').show();
			$('#disp_ruangan').show();
		});
		
		$("#cari_pasien_button").on("click", function () {
			$('#pasien_modal').modal('show');
			return false;
		});
		
    });
</script>
<style type="text/css">
	.dashboard-wrapper {
		margin-bottom: 10px;
		padding: 10px;
	}
	.dashboard-wrapper .left-sidebar {
		margin-right: 0;
	}
	.dashboard-wrapper .left-sidebar .widget {
		margin-bottom: 0;
	}
	.dashboard-wrapper .left-sidebar .widget .widget-header {
		padding: 5px;
	}
	.dashboard-wrapper .left-sidebar .widget .widget-body {
		border-bottom: 0;
	}
	.form-actions {
		margin-bottom: 0;
		margin-top: 0;
		padding: 5px;
	}
	.form-horizontal .control-group {
		margin-bottom: 4px;
	}
	
	input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"] {
		font-size: 12px;
	}
	label, input, button, select, textarea {
		font-size: 12px;
	}
	input[type="text"], 
	input[type="password"], 
	input[type="datetime"], 
	input[type="datetime-local"], 
	input[type="date"], 
	input[type="month"], 
	input[type="time"], 
	input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"] {
		padding: 2px 4px;
	}
	hr {
		margin: 1px 0 5px;
	}
	.datepicker {
		background: white;
	}
	
	#pasien_modal .modal-body {
        max-height: 800px;
    }
	
	#pasien_modal {
		width: 800px;
		margin-left: -400px;
		margin-right: -400px;
	}
	
	/* begin tindakan_modal */
    .dashboard-wrapper #tindakan_modal .modal.fade {
         left: -25%;
          -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
             -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
               -o-transition: opacity 0.3s linear, left 0.3s ease-out;
                  transition: opacity 0.3s linear, left 0.3s ease-out;
    }
    .dashboard-wrapper #tindakan_modal .modal.fade.in {
        left: 50%;
    }
	.dashboard-wrapper #tindakan_modal .modal-body {
        max-height: 500px;
    }
	#tindakan_modal {
		width: 600px;
		margin-left: -300px;
		margin-right: -300px;
	}
	/* end pasien_modal */
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<?php
					if ($is_new) {
						$url = site_url('pembagian_jasa/pembagian_jasa');
					}
					else {
						$url = site_url('pembagian_jasa/pembagian_jasa/edit/'.$data->id);
					}
				?>
				<form class="form-horizontal no-margin" id="pembagian_jasa_form" name="pembagian_jasa_form" method="post" action="<?php echo $url; ?>">
					<div class="widget-header">
						<div class="title">Pembagian Jasa</div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="row-fluid" style="border-top: 0; border-bottom: 1px solid #E5E5E5;">
						<div class="span12">
							<div class="form-actions" style="padding-left: 5px;">
								<div class="span6">
									<div class="control-group">
										<label class="control-label">Unit</label>
										<div class="controls">
											<label class="radio inline">
												<input id="rawat_jalan" name="unit_id" type="radio" checked="" value="1" />Rawat Jalan
											</label>
											<label class="radio inline">
												<input id="rawat_darurat" name="unit_id" type="radio" value="2" />IGD
											</label>
											<label class="radio inline">
												<input id="rawat_inap" name="unit_id" type="radio" value="3" />Rawat Inap
											</label>
										</div>
									</div>
								</div>
								<button class="btn pull-right" type="submit" id="batal" name="batal" value="Batal">Batal</button>
								<button class="btn btn-info pull-right" type="submit" id="simpan" name="simpan" value="Simpan" style="margin-right: 5px;">Simpan</button>
							</div>
						</div>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="disp_tanggal">Tanggal</label>
										<div class="controls controls-row">
											<div class="span5" style="float: left;">
												<?php
													$value = set_value('tanggal', $data->tanggal);
													$tanggal = strftime( "%d/%m/%Y", strtotime($value));
												?>
												<input type="hidden" id="tanggal" name="tanggal" value="<?php echo $value; ?>" />
												<input class="span12" type="text" id="disp_tanggal" name="disp_tanggal" data-date-format="mm/dd/yyyy" placeholder="" value="<?php echo $tanggal; ?>" />
												<?php echo form_error('tanggal'); ?>
											</div>
											<div id="disp_sd" class="span2" style="float: left;">
												<label style="margin: 5px;">s/d</label>
											</div>
											<div id="disp_sd_tanggal" class="span5" style="float: left;">
												<input type="hidden" id="sd_tanggal" name="sd_tanggal" value="<?php echo $value; ?>" />
												<input class="span12" type="text" id="disp_sd_tanggal" name="disp_sd_tanggal" data-date-format="mm/dd/yyyy" placeholder="" value="<?php echo $tanggal; ?>" />
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="no_medrec">No. Medrec</label>
										<div class="controls controls-row">
											<input class="span6" type="text" id="no_medrec" name="no_medrec" placeholder="No. Medrec" value="" autocomplete="off" />
											<a id="cari_pasien_button" href="#">
												<span id="toggle-btn" class="add-on btn">Cari...</span>
											</a>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="nama">Nama</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama', $data->nama);
											?>
											<input class="span12" type="text" id="nama" name="nama" value="<?php echo $value; ?>" placeholder="" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="alamat">Alamat</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('alamat', $data->alamat);
											?>
											<textarea class="span12" id="alamat" name="alamat" value="<?php echo $value; ?>" placeholder=""><?php echo $value; ?></textarea>
										</div>
									</div>
									
									<div id="div_kelompok_pasien" class="control-group">
										<label class="control-label" for="cara_bayar_id">Kelompok Pasien</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('cara_bayar_id', $data->cara_bayar_id);
												$cara_bayar = new stdClass();
												$cara_bayar->id = 0;
												$cara_bayar->nama = "- Pilih Kelompok Pasien -";
												$first = array($cara_bayar);
												$cara_bayar_list = array_merge($first, $cara_bayar_list);
											?>
											<select id="cara_bayar_id" name="cara_bayar_id">
												<?php
													foreach ($cara_bayar_list as $val) {
														if ($val->nama != "Root") {
															if ($value == $val->id) {
																echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
															}
															else {
																echo "<option value=\"{$val->id}\">{$val->nama}</option>";
															}
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div id="disp_poliklinik" class="control-group">
										<label class="control-label" for="poliklinik_id">Poliklinik</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('poliklinik_id', $data->poliklinik_id);
												$poliklinik = new stdClass();
												$poliklinik->id = 0;
												$poliklinik->nama = "- Pilih Poliklinik -";
												$first = array($poliklinik);
												$poliklinik_list = array_merge($first, $poliklinik_list);
											?>
											<select id="poliklinik_id" name="poliklinik_id">
												<?php
													foreach ($poliklinik_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														}
														else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div id="disp_kelas" class="control-group">
										<label class="control-label" for="kelas_id">Kelas</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('kelas_id', $data->kelas_id);
												$kelas = new stdClass();
												$kelas->id = 0;
												$kelas->nama = "- Pilih Kelas -";
												$first = array($kelas);
												$kelas_list = array_merge($first, $kelas_list);
											?>
											<select id="kelas_id" name="kelas_id">
												<?php
													foreach ($kelas_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														}
														else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div id="disp_ruangan" class="control-group">
										<label class="control-label" for="ruangan_id">Ruang</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('gedung_id', $data->gedung_id);
												$gedung = new stdClass();
												$gedung->id = 0;
												$gedung->nama = "- Pilih Ruang -";
												$first = array($gedung);
												$gedung_list = array_merge($first, $gedung_list);
											?>
											<select id="ruangan_id" name="ruangan_id">
												<?php
													foreach ($gedung_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														}
														else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
								</div>
								<div class="span6">
									
									
									
								</div>
							</div>
							
							<hr />
							<div class="row-fluid">
							
								<div class="span12">
									<table id="table_pembagian_jasa_detail" class="table table-condensed table-striped table-bordered table-hover no-margin">
										<thead>
											<tr>
												<th style="width:21%">Nama Pelayanan</th>
												<th style="width:15%">Dokter</th>
												<th style="width:8%">Harga/Unit</th>
												<th style="width:8%">Qty</th>
												<th style="width:8%">Jumlah</th>
												<th style="width:15%">Actions</th>
											</tr>
										</thead>
										<tbody>
											<!--tr>
												<td><input type="text" style="width: 94%;"/></td>
												<td><input type="text" style="width: 94%;" /></td>
												<td><input type="text" style="width: 94%;" /></td>
												<td><input type="text" style="width: 94%;" /></td>
												<td><input type="text" style="width: 94%;" /></td>
												<td></td>
											</tr-->
										</tbody>
									</table>
									<table class="table table-condensed table-striped table-bordered table-hover no-margin" style="border-top: 0;">
										<tbody>
											<tr>
												<td colspan="4" style="text-align: right; width:52%;">T O T A L&nbsp;&nbsp;</td>
												<td style="text-align: right; width:8%">
													<input type="hidden" id="total" name="total" value="<?php echo $data->jumlah; ?>" />
													<label id="label_total" style="text-align: right;"><?php echo $data->jumlah; ?></label>
												</td>
												<td style="width:15%">&nbsp</td>
											</tr>
										</tbody>
									</table>
									<table class="table table-condensed table-striped table-bordered table-hover no-margin" style="border-top: 0;">
										<tbody>
											<tr>
												<td colspan="2" class="hidden-phone">
													<button type="button" id="button_tambah" class="btn btn-primary btn-mini bottom-margin">Tambah</button>
												</td>
											</tr>
										</tbody>
									</table>
									<table style="display: none;">
										<tbody>
											<tr id="row_pembagian_jasa_detail_clone">
												<td>
													<input type="hidden" id="clone_pembagian_jasa_detail_id" />
													<input type="hidden" id="clone_tindakan_id" />
													<input type="hidden" id="clone_jasa_sarana" />
													<input type="hidden" id="clone_jasa_pelayanan" />
													<input type="hidden" id="clone_persen_pemda" />
													<input type="hidden" id="clone_persen_dibagikan" />
													<input type="hidden" id="clone_persen_manajemen" />
													<input type="hidden" id="clone_persen_sisa" />
													<input type="hidden" id="clone_insentif_pemda" />
													<input type="hidden" id="clone_insentif_manajemen" />
													<label id="clone_label_tindakan" style="display: none;"></label>
													<div id="clone_tindakan" class="span12" style="margin: 0;">
														<div class="controls controls-row" style="margin: 0;">
															<input class="span9" type="text" id="clone_disp_tindakan" autocomplete="off" />
															<button type="button" class="cari_tindakan span3 add-non btn" data-url="<?php echo site_url('pembagian_jasa/pembagian_jasa/'); ?>">Cari...</button>
														</div>
													</div>
												</td>
												<td>
													<input type="hidden" id="clone_dokter_id" />
													<label id="clone_label_dokter" style="display: none;"></label>
													<select id="clone_disp_dokter" style="display: block; width: 100%;" ></select>
												</td>
												<td>
													<input type="hidden" id="clone_harga_satuan" />
													<label id="clone_label_harga_satuan" style="display: none; text-align: right;"></label>
													<input type="text" id="clone_disp_harga_satuan" style="display: block; width: 94%; text-align: right;" />
												</td>
												<td>
													<input type="hidden" id="clone_quantity" />
													<label id="clone_label_quantity" style="display: none; text-align: right;"></label>
													<input type="text" class="quantity_row" id="clone_disp_quantity" style="display: block; width: 94%; text-align: right;" />
												</td>
												<td>
													<input type="hidden" id="clone_jumlah" />
													<label id="clone_label_jumlah" style="text-align: right;"></label>
												</td>
												<td style="text-align: center; vertical-align: middle;">
													<button type="button" id="button_uraian" class="btn btn-success btn-mini bottom-margin" data-url="<?php echo site_url('pembagian_jasa/pembagian_jasa/get_uraian'); ?>">Uraian</button>
													<button type="button" id="button_1" class="btn btn-primary btn-mini bottom-margin">Simpan</button>
													<button type="button" id="button_2" class="btn btn-primary btn-mini bottom-margin">Batal</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
						
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<div class="form-actions" style="text-align: right;">
								<button class="btn btn-info" type="submit" id="simpan" name="simpan" value="Simpan">Simpan</button>
								<button class="btn" type="submit" id="batal" name="batal" value="Batal">Batal</button>
							</div>
						</div>
					</div>
					<div id="table_penerima_jp_detail"></div>
					<table id="clone_table_penerima_jp_detail" style="display: none;">
						<thead>
							<tr>	
								<th>id</th>
								<th>tanggal</th>
								<th>kelompok_id</th>
								<th>jenis_kelompok</th>
								<th>nama</th>
								<th>unit_id</th>
								<th>pegawai_id</th>
								<th>pasien_id</th>
								<th>quantity</th>
								<th>proporsi</th>
								<th>langsung</th>
								<th>balancing</th>
								<th>kebersamaan</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot></tfoot>
					</table>
					<?php
						$value = set_value('id', $data->id);
					?>
					<input type="hidden" id="id" name="id" value="<?php echo $value; ?>" />
					<?php
						$value = set_value('pasien_id', $data->pasien_id);
					?>
					<input type="hidden" id="pasien_id" name="pasien_id" value="<?php echo $value; ?>" />
					<input type="hidden" id="transaksi_id" name="transaksi_id" value="" />
					<input type="hidden" id="version" name="version" value="<?php echo $data->version; ?>" />
					<?php
						$counter = count($data->details);
					?>
					<input type="hidden" id="counter" value="<?php echo $counter; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="pasien_modal" data-remote="<?php echo site_url('pembagian_jasa/pembagian_jasa/browse_pasien'); ?>" >
    <div class="modal-header">
       <a class="close" data-dismiss="modal">&times;</a>
       <h4>Daftar Pasien</h4>
    </div>
    <div class="modal-body"></div>
</div>
<div class="modal hide fade" id="tindakan_modal" data-remote="<?php echo site_url('pembagian_jasa/pembagian_jasa/browse_tindakan'); ?>">
	<input type="hidden" id="tindakan_counter" value="0" />
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4>Daftar Pelayanan</h4>
	</div>
	<div class="modal-body"></div>
</div>
<div class="modal fade" id="uraian_modal" data-remote="">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>Rincian Tarif Pelayanan</h3>
	</div>
	<div class="modal-body">
	</div>
	<div class="modal-footer">
		<input type="hidden" id="dokter_value" value="" />
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button id="uraian_save_button" class="btn btn-primary">Save changes</button>
	</div>
</div>