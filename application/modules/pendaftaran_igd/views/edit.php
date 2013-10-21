<script type="text/javascript">
	
	<?php
		if ($print) {
	?>
		print();
	<?php
		}
	?>
	
	function print() {
		<?php
			if ($print_baru) {
		?>
			window.open("<?php echo site_url('pendaftaran_igd/print_form_001/'.$print_id); ?>", "Print Tracer", "scrollbars=1, height=300, width=300");
		<?php
			}
		?>
	}

	
	function DaysInMonth2(Y, M) {
		with (new Date(Y, M, 1, 12)) {
			setDate(0);
			return getDate();
		}
	}

	function datediff2(date1, date2) {
		var y1 = date1.getFullYear(), m1 = date1.getMonth(), d1 = date1.getDate(),
			y2 = date2.getFullYear(), m2 = date2.getMonth(), d2 = date2.getDate();
		if (d1 < d2) {
			m1--;
			d1 += DaysInMonth2(y2, m2);
		}
		if (m1 < m2) {
			y1--;
			m1 += 12;
		}
		return [y1 - y2, m1 - m2, d1 - d2];
	}
	
	function select_pasien(id) {
		var old_id = $("#pasien_id").val();
		if (old_id != id) {
			$.getJSON("<?php echo site_url('pendaftaran_igd/get_pasien_by_id'); ?>" + "?pasien_id=" + id, function(json) {
				$("#pasien_id").val(json.pasien.id);
				$("#no_medrec").val(json.pasien.no_medrec);
				$("#nama").val(json.pasien.nama);
				switch (parseInt(json.pasien.jenis_kelamin)) {
					case 1:
						$('input:radio[name=jenis_kelamin]')[0].checked = true;
						break;
					case 2:
						$('input:radio[name=jenis_kelamin]')[1].checked = true;
						break;
				}
				$("#alamat_jalan").val(json.pasien.alamat_jalan);
				var provinsi_id = json.pasien.provinsi_id;
				if (provinsi_id > 0) {
					$.ajax({
						type: "GET",
						url: "<?php echo site_url('pendaftaran_igd/get_provinsi') ?>",
						data: "provinsi_id=" + provinsi_id, 
						cache: false,
						beforeSend: function() {
							$('#loading_provinsi').show();
							$('#loading_provinsi').css("display", "inline");
						},
						success: function() {
							$('#loading_provinsi').hide();
						},
						complete: function($response, $status) {
							if ($status === "success" || $status === "notmodified") {
								$("#provinsi_id").html($response.responseText);
							}
						}
					});
				}
				var provinsi_id = json.pasien.provinsi_id;
				if (provinsi_id > 0) {
					$.ajax({
						type: "GET",
						url: "<?php echo site_url('pendaftaran_igd/get_kabupaten') ?>",
						data: "provinsi_id=" + provinsi_id + "&kabupaten_id=" + json.pasien.kabupaten_id, 
						cache: false,
						beforeSend: function() {
							$('#loading_kabupaten').show();
							$('#loading_kabupaten').css("display", "inline");
						},
						success: function() {
							$('#loading_kabupaten').hide();
						},
						complete: function($response, $status) {
							if ($status === "success" || $status === "notmodified") {
								$("#kabupaten_id").html($response.responseText);
							}
						}
					});
				}
				var kabupaten_id = json.pasien.kabupaten_id;
				if (kabupaten_id > 0) {
					$.ajax({
						type: "GET",
						url: "<?php echo site_url('pendaftaran_igd/get_kecamatan') ?>",
						data: "kabupaten_id=" + kabupaten_id + "&kecamatan_id=" + json.pasien.kecamatan_id,
						cache: false,
						beforeSend: function() {
							$('#loading_kecamatan').show();
							$('#loading_kecamatan').css("display", "inline");
						},
						success: function() {
							$('#loading_kecamatan').hide();
						},
						complete: function($response, $status) {
							if ($status === "success" || $status === "notmodified") {
								$("#kecamatan_id").html($response.responseText);
							}
						}
					});
				}
				var kecamatan_id = json.pasien.kecamatan_id;
				if (kecamatan_id > 0) {
					$.ajax({
						type: "GET",
						url: "<?php echo site_url('pendaftaran_igd/get_kelurahan') ?>",
						data: "kecamatan_id=" + kecamatan_id + "&kelurahan_id=" + json.pasien.kelurahan_id,
						cache: false,
						beforeSend: function() {
							$('#loading_kelurahan').show();
							$('#loading_kelurahan').css("display", "inline");
						},
						success: function() {
							$('#loading_kelurahan').hide();
						},
						complete: function($response, $status) {
							if ($status === "success" || $status === "notmodified") {
								$("#kelurahan_id").html($response.responseText);
							}
						}
					});
				}
				$("#kodepos").val(json.pasien.kodepos);
				$("#telepon").val(json.pasien.telepon);
				$("#tempat_lahir").val(json.pasien.tempat_lahir);
				$("#tanggal_lahir").val(json.pasien.tanggal_lahir);
				var t = json.pasien.tanggal_lahir.split(/[- :]/);
				var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
				$("#disp_tanggal_lahir").val($.datepicker.formatDate('dd/mm/yy', d));
				
				var tgl = $('#tanggal_lahir').val().split(" ");
				var aDoB = tgl[0].split("-");
				var DoB_year   = aDoB[0];
				var DoB_month  = aDoB[1];
				var DoB_day    = aDoB[2];

				var curDate = new Date();
				
				var calcDate = new Date(DoB_year, DoB_month - 1, DoB_day);
				var dife = datediff2(curDate, calcDate);
				$("#umur_tahun").val(dife[0]);
				$("#umur_bulan").val(dife[1]);
				$("#umur_hari").val(dife[2]);
				
				$('#golongan_darah option[value="' + json.pasien.golongan_darah + '"]').attr('selected', 'selected');
				$('#agama_id option:eq(' + parseInt(json.pasien.agama_id) + ')').attr('selected', 'selected');
				$('#pendidikan_id option:eq(' + parseInt(json.pasien.pendidikan_id) + ')').attr('selected', 'selected');
				$('#pekerjaan_id option:eq(' + parseInt(json.pasien.pekerjaan_id) + ')').attr('selected', 'selected');
				$("#baru").val(0);
				$("#status_pasien").html("Pasien Lama");
				
				$("#generate_medrec").attr("disabled", true);
			});
		}
		$("#pasien_modal").modal("hide");
		return false;
	}
	
	/*
	Date.prototype.dateAdd = function(size, value) {
		value = parseInt(value);
		var incr = 0;
		switch (size) {
			case 'day':
				incr = value * 24;
				this.dateAdd('hour',incr);
				break;
			case 'hour':
				incr = value * 60;
				this.dateAdd('minute',incr);
				break;
			case 'week':
				incr = value * 7;
				this.dateAdd('day',incr);
				break;
			case 'minute':
				incr = value * 60;
				this.dateAdd('second',incr);
				break;
			case 'second':
				incr = value * 1000;
				this.dateAdd('millisecond',incr);
				break;
			case 'month':
				value = value + this.getUTCMonth();
				if (value/12>0) {
					this.dateAdd('year',value/12);
					value = value % 12;
				}
				this.setUTCMonth(value);
				break;
			case 'millisecond':
				this.setTime(this.getTime() + value);
				break;
			case 'year':
				this.setFullYear(this.getUTCFullYear()+value);
				break;
			default:
				throw new Error('Invalid date increment passed');
				break;
		}
	}
	
	var d = new Date();
	alert(d.dateAdd('year', -3));
	*/		
	$(document).ready(function() {
        
        function getISODateTime(d){
			var s = function(a,b){return(1e15+a+"").slice(-b);};

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
			var s = function(a,b){return(1e15+a+"").slice(-b);};

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
		
		var provinsi_id = <?php echo set_value('provinsi_id', $data->provinsi_id); ?>;
		if (provinsi_id > 0) {
			$.ajax({
				type: "GET",
				url: "<?php echo site_url('pendaftaran_igd/get_kabupaten') ?>",
				data: "provinsi_id=" + provinsi_id + "&kabupaten_id=" + <?php echo set_value('kabupaten_id', $data->kabupaten_id); ?>, 
				cache: false,
				beforeSend: function() {
					$('#loading_kabupaten').show();
					$('#loading_kabupaten').css("display", "inline");
				},
				success: function() {
					$('#loading_kabupaten').hide();
				},
				complete: function($response, $status) {
					if ($status === "success" || $status === "notmodified") {
						$("#kabupaten_id").html($response.responseText);
					}
				}
			});
		}
		$("#provinsi_id").change(function() {
			var provinsi_id = $("#provinsi_id").val();
			if (provinsi_id) {
				$.ajax({
					type: "GET",
					url: "<?php echo site_url('pendaftaran_igd/get_kabupaten') ?>",
					data: "provinsi_id=" + provinsi_id, 
					cache: false,
					beforeSend: function() {
						$('#loading_kabupaten').show();
						$('#loading_kabupaten').css("display", "inline");
					},
					success: function() {
						$('#loading_kabupaten').hide();
					},
					complete: function($response, $status) {
						if ($status === "success" || $status === "notmodified") {
							$("#kabupaten_id").html($response.responseText);
							$("#kecamatan_id").html('<option value="0" selected="selected">- Pilih Kecamatan -</option>');
							$("#kelurahan_id").html('<option value="0" selected="selected">- Pilih Kelurahan/Desa -</option>');
						}
					}
				});
			}
		});
		
		var kabupaten_id = <?php echo set_value('kabupaten_id', $data->kabupaten_id); ?>;
		if (kabupaten_id > 0) {
			$.ajax({
				type: "GET",
				url: "<?php echo site_url('pendaftaran_igd/get_kecamatan') ?>",
				data: "kabupaten_id=" + kabupaten_id + "&kecamatan_id=" + <?php echo set_value('kecamatan_id', $data->kecamatan_id); ?>,
				cache: false,
				beforeSend: function() {
					$('#loading_kecamatan').show();
					$('#loading_kecamatan').css("display", "inline");
				},
				success: function() {
					$('#loading_kecamatan').hide();
				},
				complete: function($response, $status) {
					if ($status === "success" || $status === "notmodified") {
						$("#kecamatan_id").html($response.responseText);
					}
				}
			});
		}
		$("#kabupaten_id").change(function() {
			var kabupaten_id = $("#kabupaten_id").val();
			if (kabupaten_id) {
				$.ajax({
					type: "GET",
					url: "<?php echo site_url('pendaftaran_igd/get_kecamatan') ?>",
					data: "kabupaten_id=" + kabupaten_id, 
					cache: false,
					beforeSend: function() {
						$('#loading_kecamatan').show();
						$('#loading_kecamatan').css("display", "inline");
					},
					success: function() {
						$('#loading_kecamatan').hide();
					},
					complete: function($response, $status) {
						if ($status === "success" || $status === "notmodified") {
							$("#kecamatan_id").html($response.responseText);
							$("#kelurahan_id").html('<option value="0" selected="selected">- Pilih Kelurahan/Desa -</option>');
						}
					}
				});
			}
		});
		
		var kecamatan_id = <?php echo set_value('kecamatan_id', $data->kecamatan_id); ?>;
		if (kecamatan_id > 0) {
			$.ajax({
				type: "GET",
				url: "<?php echo site_url('pendaftaran_igd/get_kelurahan') ?>",
				data: "kecamatan_id=" + kecamatan_id + "&kelurahan_id=" + <?php echo set_value('kelurahan_id', $data->kelurahan_id); ?>,
				cache: false,
				beforeSend: function() {
					$('#loading_kelurahan').show();
					$('#loading_kelurahan').css("display", "inline");
				},
				success: function() {
					$('#loading_kelurahan').hide();
				},
				complete: function($response, $status) {
					if ($status === "success" || $status === "notmodified") {
						$("#kelurahan_id").html($response.responseText);
					}
				}
			});
		}
		$("#kecamatan_id").change(function() {
			var kecamatan_id = $("#kecamatan_id").val();
			if (kecamatan_id) {
				$.ajax({
					type: "GET",
					url: "<?php echo site_url('pendaftaran_igd/get_kelurahan') ?>",
					data: "kecamatan_id=" + kecamatan_id, 
					cache: false,
					beforeSend: function() {
						$('#loading_kelurahan').show();
						$('#loading_kelurahan').css("display", "inline");
					},
					success: function() {
						$('#loading_kelurahan').hide();
					},
					complete: function($response, $status) {
						if ($status === "success" || $status === "notmodified") {
							$("#kelurahan_id").html($response.responseText);
						}
					}
				});
			}
		});
		
		function DaysInMonth(Y, M) {
			with (new Date(Y, M, 1, 12)) {
				setDate(0);
				return getDate();
			}
		}
		
		function datediff(date1, date2) {
			var y1 = date1.getFullYear(), m1 = date1.getMonth(), d1 = date1.getDate(),
			    y2 = date2.getFullYear(), m2 = date2.getMonth(), d2 = date2.getDate();
			if (d1 < d2) {
				m1--;
				d1 += DaysInMonth(y2, m2);
			}
			if (m1 < m2) {
				y1--;
				m1 += 12;
			}
			return [y1 - y2, m1 - m2, d1 - d2];
		}
		
		var disp_tanggal_lahir = $('#disp_tanggal_lahir').datepicker({
			format: 'dd/mm/yyyy',
			viewMode: 'years'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date);
			date_str = getISODate(date);
			$('#tanggal_lahir').val(date_str);
			
			var aDoB = $('#tanggal_lahir').val().split("-");
			var DoB_year   = aDoB[0];
			var DoB_month  = aDoB[1];
			var DoB_day    = aDoB[2];
			
			var curDate = new Date();
			
			var calcDate = new Date(DoB_year, DoB_month - 1, DoB_day);
			var dife = datediff(curDate, calcDate);
			$("#umur_tahun").val(dife[0]);
			$("#umur_bulan").val(dife[1]);
			$("#umur_hari").val(dife[2]);
			
			if (ev.viewMode === 'days')
				disp_tanggal_lahir.hide();
		}).data('datepicker');
		
		var jenis_cara_bayar = $("#cara_bayar_id").val().split('|')[1];
		if (parseInt(jenis_cara_bayar) === 2) {
			$("#section_no_asuransi").show();
			$("#section_peserta_asuransi").show();
		}
		else {
			$("#section_no_asuransi").hide();
			$("#section_peserta_asuransi").hide();
		}
		$("#cara_bayar_id").change(function() {
			var jenis_cara_bayar = $("#cara_bayar_id").val().split('|')[1];
			if (parseInt(jenis_cara_bayar) === 2) {
				$("#section_no_asuransi").show();
				$("#section_peserta_asuransi").show();
			}
			else {
				$("#section_no_asuransi").hide();
				$("#section_peserta_asuransi").hide();
			}
		});
		
		var poliklinik_id = $("#poliklinik_id").val();
		if (poliklinik_id > 0) {
			$.ajax({
				type: "GET",
				url: "<?php echo site_url('pendaftaran_igd/get_dokter') ?>",
				data: "poliklinik_id=" + poliklinik_id + "&dokter_id=" + <?php echo set_value('dokter_id', $data->dokter_id); ?>, 
				cache: false,
				beforeSend: function() {
					$('#loading_dokter').show();
					$('#loading_dokter').css("display", "inline");
				},
				success: function() {
					$('#loading_dokter').hide();
				},
				complete: function($response, $status) {
					if ($status === "success" || $status === "notmodified") {
						$("#dokter_id").html($response.responseText);
					}
				}
			});
		}
		$("#poliklinik_id").change(function() {
			var poliklinik_id = $("#poliklinik_id").val();
			$.ajax({
				type: "GET",
				url: "<?php echo site_url('pendaftaran_igd/get_dokter') ?>",
				data: "poliklinik_id=" + poliklinik_id, 
				cache: false,
				beforeSend: function() {
					$('#loading_dokter').show();
					$('#loading_dokter').css("display", "inline");
				},
				success: function() {
					$('#loading_dokter').hide();
				},
				complete: function($response, $status) {
					if ($status === "success" || $status === "notmodified") {
						$("#dokter_id").html($response.responseText);
					}
				}
			});
		});
		
		var oTable = $('#pasien').dataTable({
			"sPaginationType"	: "full_numbers",
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?php echo site_url('pendaftaran_igd/pendaftaran_igd/load_data_pasien'); ?>",
			"aoColumns"			: [
									  { sWidth: '5%' },
									  { sWidth: 'null' },
									  { sWidth: '20%' },
									  { sWidth: '20%' },
									  { sWidth: '20%' }
								  ]
		});
		
		$('#cari_pasien').click(function(e) {
			e.preventDefault();
			var href = $(e.target).attr('href');
			if (href.indexOf('#') === 0) {
				$(href).modal('open');
			} else {
				$.get(href, function(data) {
					$('<div class="modal fade" >' + data + '</div>').modal();
				});
			}
		});
		
		$("#generate_medrec").click(function() {
			$.ajax({
				url			: "<?php echo site_url('pendaftaran_igd/generate_medrec') ?>",
				dataType	: "json",
				cache		: false,
				beforeSend: function() {
					$('#loading_generate_medrec').show();
					$('#loading_generate_medrec').css("display", "inline");
				},
				success: function() {
					$('#loading_generate_medrec').hide();
				},
				complete: function($response, $status) {
					if ($status === "success" || $status === "notmodified") {
						$("#no_medrec").val($response.responseJSON.no_medrec);
						$("#nama").focus();
					}
				}
			});
		});
		
		$("#no_medrec").keypress(function(event) {
			if (event.which === 13) {
				event.preventDefault();
				var no_medrec = $("#no_medrec").val();
				$.getJSON("<?php echo site_url('pendaftaran_igd/get_pasien_by_no_medrec'); ?>" + "/" + no_medrec, function(json) {
					if (json.status === "ok") {
						$("#pasien_id").val(json.pasien.id);
						$("#no_medrec").val(json.pasien.no_medrec);
						$("#nama").val(json.pasien.nama);
						switch (parseInt(json.pasien.jenis_kelamin)) {
							case 1:
								$('input:radio[name=jenis_kelamin]')[0].checked = true;
								break;
							case 2:
								$('input:radio[name=jenis_kelamin]')[1].checked = true;
								break;
						}
						$("#alamat_jalan").val(json.pasien.alamat_jalan);
						var provinsi_id = json.pasien.provinsi_id;
						if (provinsi_id > 0) {
							$.ajax({
								type: "GET",
								url: "<?php echo site_url('pendaftaran_igd/get_provinsi') ?>",
								data: "provinsi_id=" + provinsi_id, 
								cache: false,
								beforeSend: function() {
									$('#loading_provinsi').show();
									$('#loading_provinsi').css("display", "inline");
								},
								success: function() {
									$('#loading_provinsi').hide();
								},
								complete: function($response, $status) {
									if ($status === "success" || $status === "notmodified") {
										$("#provinsi_id").html($response.responseText);
									}
								}
							});
						}
						var provinsi_id = json.pasien.provinsi_id;
						if (provinsi_id > 0) {
							$.ajax({
								type: "GET",
								url: "<?php echo site_url('pendaftaran_igd/get_kabupaten') ?>",
								data: "provinsi_id=" + provinsi_id + "&kabupaten_id=" + json.pasien.kabupaten_id, 
								cache: false,
								beforeSend: function() {
									$('#loading_kabupaten').show();
									$('#loading_kabupaten').css("display", "inline");
								},
								success: function() {
									$('#loading_kabupaten').hide();
								},
								complete: function($response, $status) {
									if ($status === "success" || $status === "notmodified") {
										$("#kabupaten_id").html($response.responseText);
									}
								}
							});
						}
						var kabupaten_id = json.pasien.kabupaten_id;
						if (kabupaten_id > 0) {
							$.ajax({
								type: "GET",
								url: "<?php echo site_url('pendaftaran_igd/get_kecamatan') ?>",
								data: "kabupaten_id=" + kabupaten_id + "&kecamatan_id=" + json.pasien.kecamatan_id,
								cache: false,
								beforeSend: function() {
									$('#loading_kecamatan').show();
									$('#loading_kecamatan').css("display", "inline");
								},
								success: function() {
									$('#loading_kecamatan').hide();
								},
								complete: function($response, $status) {
									if ($status === "success" || $status === "notmodified") {
										$("#kecamatan_id").html($response.responseText);
									}
								}
							});
						}
						var kecamatan_id = json.pasien.kecamatan_id;
						if (kecamatan_id > 0) {
							$.ajax({
								type: "GET",
								url: "<?php echo site_url('pendaftaran_igd/get_kelurahan') ?>",
								data: "kecamatan_id=" + kecamatan_id + "&kelurahan_id=" + json.pasien.kelurahan_id,
								cache: false,
								beforeSend: function() {
									$('#loading_kelurahan').show();
									$('#loading_kelurahan').css("display", "inline");
								},
								success: function() {
									$('#loading_kelurahan').hide();
								},
								complete: function($response, $status) {
									if ($status === "success" || $status === "notmodified") {
										$("#kelurahan_id").html($response.responseText);
									}
								}
							});
						}
						$("#kodepos").val(json.pasien.kodepos);
						$("#telepon").val(json.pasien.telepon);
						$("#tempat_lahir").val(json.pasien.tempat_lahir);
						$("#tanggal_lahir").val(json.pasien.tanggal_lahir);
						var t = json.pasien.tanggal_lahir.split(/[- :]/);
						var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
						$("#disp_tanggal_lahir").val($.datepicker.formatDate('dd/mm/yy', d));

						var tgl = $('#tanggal_lahir').val().split(" ");
						var aDoB = tgl[0].split("-");
						var DoB_year   = aDoB[0];
						var DoB_month  = aDoB[1];
						var DoB_day    = aDoB[2];

						var curDate = new Date();

						var calcDate = new Date(DoB_year, DoB_month - 1, DoB_day);
						var dife = datediff2(curDate, calcDate);
						$("#umur_tahun").val(dife[0]);
						$("#umur_bulan").val(dife[1]);
						$("#umur_hari").val(dife[2]);

						$('#agama_id option:eq(' + parseInt(json.pasien.agama_id) + ')').attr('selected', 'selected');
						$('#pendidikan_id option:eq(' + parseInt(json.pasien.pendidikan_id) + ')').attr('selected', 'selected');
						$('#pekerjaan_id option:eq(' + parseInt(json.pasien.pekerjaan_id) + ')').attr('selected', 'selected');
						$("#baru").val(0);
						$("#status_pasien").html("Pasien Lama");

						$("#generate_medrec").attr("disabled", true);
						}
					else {
						alert("No. Medrec tersebut tidak ditemukan!");
						$("#no_medrec").val('');
						$("#no_medrec").focus();
					}
				});
			}
		});
		
		$("#no_medrec").blur(function(event) {
			var no_medrec = $("#no_medrec").val();
			$.getJSON("<?php echo site_url('pendaftaran_igd/get_pasien_by_no_medrec'); ?>" + "/" + no_medrec, function(json) {
				if (json.status === "ok") {
					$("#pasien_id").val(json.pasien.id);
					$("#no_medrec").val(json.pasien.no_medrec);
					$("#nama").val(json.pasien.nama);
					switch (parseInt(json.pasien.jenis_kelamin)) {
						case 1:
							$('input:radio[name=jenis_kelamin]')[0].checked = true;
							break;
						case 2:
							$('input:radio[name=jenis_kelamin]')[1].checked = true;
							break;
					}
					$("#alamat_jalan").val(json.pasien.alamat_jalan);
					var provinsi_id = json.pasien.provinsi_id;
					if (provinsi_id > 0) {
						$.ajax({
							type: "GET",
							url: "<?php echo site_url('pendaftaran_igd/get_provinsi') ?>",
							data: "provinsi_id=" + provinsi_id, 
							cache: false,
							beforeSend: function() {
								$('#loading_provinsi').show();
								$('#loading_provinsi').css("display", "inline");
							},
							success: function() {
								$('#loading_provinsi').hide();
							},
							complete: function($response, $status) {
								if ($status === "success" || $status === "notmodified") {
									$("#provinsi_id").html($response.responseText);
								}
							}
						});
					}
					var provinsi_id = json.pasien.provinsi_id;
					if (provinsi_id > 0) {
						$.ajax({
							type: "GET",
							url: "<?php echo site_url('pendaftaran_igd/get_kabupaten') ?>",
							data: "provinsi_id=" + provinsi_id + "&kabupaten_id=" + json.pasien.kabupaten_id, 
							cache: false,
							beforeSend: function() {
								$('#loading_kabupaten').show();
								$('#loading_kabupaten').css("display", "inline");
							},
							success: function() {
								$('#loading_kabupaten').hide();
							},
							complete: function($response, $status) {
								if ($status === "success" || $status === "notmodified") {
									$("#kabupaten_id").html($response.responseText);
								}
							}
						});
					}
					var kabupaten_id = json.pasien.kabupaten_id;
					if (kabupaten_id > 0) {
						$.ajax({
							type: "GET",
							url: "<?php echo site_url('pendaftaran_igd/get_kecamatan') ?>",
							data: "kabupaten_id=" + kabupaten_id + "&kecamatan_id=" + json.pasien.kecamatan_id,
							cache: false,
							beforeSend: function() {
								$('#loading_kecamatan').show();
								$('#loading_kecamatan').css("display", "inline");
							},
							success: function() {
								$('#loading_kecamatan').hide();
							},
							complete: function($response, $status) {
								if ($status === "success" || $status === "notmodified") {
									$("#kecamatan_id").html($response.responseText);
								}
							}
						});
					}
					var kecamatan_id = json.pasien.kecamatan_id;
					if (kecamatan_id > 0) {
						$.ajax({
							type: "GET",
							url: "<?php echo site_url('pendaftaran_igd/get_kelurahan') ?>",
							data: "kecamatan_id=" + kecamatan_id + "&kelurahan_id=" + json.pasien.kelurahan_id,
							cache: false,
							beforeSend: function() {
								$('#loading_kelurahan').show();
								$('#loading_kelurahan').css("display", "inline");
							},
							success: function() {
								$('#loading_kelurahan').hide();
							},
							complete: function($response, $status) {
								if ($status === "success" || $status === "notmodified") {
									$("#kelurahan_id").html($response.responseText);
								}
							}
						});
					}
					$("#kodepos").val(json.pasien.kodepos);
					$("#telepon").val(json.pasien.telepon);
					$("#tempat_lahir").val(json.pasien.tempat_lahir);
					$("#tanggal_lahir").val(json.pasien.tanggal_lahir);
					var t = json.pasien.tanggal_lahir.split(/[- :]/);
					var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
					$("#disp_tanggal_lahir").val($.datepicker.formatDate('dd/mm/yy', d));

					var tgl = $('#tanggal_lahir').val().split(" ");
					var aDoB = tgl[0].split("-");
					var DoB_year   = aDoB[0];
					var DoB_month  = aDoB[1];
					var DoB_day    = aDoB[2];

					var curDate = new Date();

					var calcDate = new Date(DoB_year, DoB_month - 1, DoB_day);
					var dife = datediff2(curDate, calcDate);
					$("#umur_tahun").val(dife[0]);
					$("#umur_bulan").val(dife[1]);
					$("#umur_hari").val(dife[2]);

					$('#agama_id option:eq(' + parseInt(json.pasien.agama_id) + ')').attr('selected', 'selected');
					$('#pendidikan_id option:eq(' + parseInt(json.pasien.pendidikan_id) + ')').attr('selected', 'selected');
					$('#pekerjaan_id option:eq(' + parseInt(json.pasien.pekerjaan_id) + ')').attr('selected', 'selected');
					$("#baru").val(0);
					$("#status_pasien").html("Pasien Lama");

					$("#generate_medrec").attr("disabled", true);
					}
				else {
					alert("No. Medrec " + $('#no_medrec').val() + " tersebut tidak ditemukan!");
					$("#no_medrec").val('');
					$("#no_medrec").focus();
				}
			});
		});
		
		$("#cari_pasien_button").on("click", function () {
			$('#pasien_modal').modal('show');
			return false;
		});
		
		$("#test").click(function() {
			var date = new Date();
			date.setDate(date.getDate() - 28);
			date.setMonth(date.getMonth() - 0);
			date.setMonth(date.getMonth() - (41*12));
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
	.form-horizontal .form-actions {
		padding-left: 5px;
	}
	
	#myModal .modal-body {
		max-height: 800px;
		height: 420px;
	}
	#myModal {
		width: 1000px; /* SET THE WIDTH OF THE MODAL */
		margin: 0 0 0 -500px; /* CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
	}
	
	.modal-body #dt_pasien .dataTables_length {
		float: left;
	}
	
	.modal-body #dt_pasien .dataTables_info {
		float: left;
		margin-bottom: 5px;
	}
	
	.modal-body #dt_pasien .dataTables_filter {
		float: right;
	}
	
	.modal-body #dt_pasien .dataTables_paginate {
		float: right;
		margin: 5px 0;
	}
	
	.modal-body #dt_pasien .dataTables_paginate .first, 
	.modal-body #dt_pasien .dataTables_paginate .previous, 
	.modal-body #dt_pasien .dataTables_paginate .next, 
	.modal-body #dt_pasien .dataTables_paginate .paginate_active, 
	.modal-body #dt_pasien .dataTables_paginate .last, 
	.modal-body #dt_pasien .dataTables_paginate .paginate_button {
		background-color: #E6E6E6;
		background-image: -moz-linear-gradient(center top , #F2F2F2, #E6E6E6);
		border-bottom: 1px solid #D9D9D9;
		border-left: 1px solid #D9D9D9;
		border-top: 1px solid #D9D9D9;
		padding: 7px 10px;
	}
	
	#test_modal .modal-body {
		max-height: 800px;
		height: 420px;
	}
	#test_modal {
		width: 1000px; /* SET THE WIDTH OF THE MODAL */
		margin: 0 0 0 -500px; /* CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
	}
	
	#test_modal .dataTables_length {
		float: left;
	}
	
	 #test_modal .dataTables_info {
		float: left;
		margin-bottom: 5px;
	}
	
	#test_modal .dataTables_filter {
		float: right;
	}
	
	#test_modal .dataTables_paginate {
		float: right;
		margin: 5px 0;
	}
	
	#test_modal .dataTables_paginate .first, 
	#test_modal .dataTables_paginate .previous, 
	#test_modal .dataTables_paginate .next, 
	#test_modal .dataTables_paginate .paginate_active, 
	#test_modal .dataTables_paginate .last, 
	#test_modal .dataTables_paginate .paginate_button {
		background-color: #E6E6E6;
		background-image: -moz-linear-gradient(center top , #F2F2F2, #E6E6E6);
		border-bottom: 1px solid #D9D9D9;
		border-left: 1px solid #D9D9D9;
		border-top: 1px solid #D9D9D9;
		padding: 7px 10px;
	}
	
	#pasien_modal .modal-body {
        max-height: 800px;
    }
	
	#pasien_modal {
		width: 800px;
		margin-left: -400px;
		margin-right: -400px;
	}
	
</style>
<div class="left-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<?php
					if ($is_new) {
						$url = site_url('pendaftaran_igd/pendaftaran_igd');
					}
					else {
						$url = site_url('pendaftaran_igd/pendaftaran_igd/edit/'.$data->pendaftaran_id.'?print');
					}
				?>
				<form class="form-horizontal no-margin" id="pendaftaran_form" name="pendaftaran_form" method="post" action="<?php echo $url; ?>">
					<div class="widget-header">
						<div class="title">Pendaftaran IGD</div>
						<span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
					</div>
					<div class="row-fluid" style="border-top: 0; border-bottom: 1px solid #E5E5E5;">
						<div class="span12">
							<div class="form-actions" style="text-align: right;">
								<span id="status_pasien" class="label label-info full-left" style="font-size: 14px; padding: 8px 4px; float: left;"><?php echo ($data->baru ? "Pasien Baru" : "Pasien Lama"); ?></span>
								<button class="btn btn-info" type="submit" id="simpan" name="simpan" value="Simpan">Simpan</button>
								<button class="btn" type="submit" id="batal" name="batal" value="Batal">Batal</button>
							</div>
						</div>
					</div>
					<div class="widget-body">

						<div class="container-fluid">

							<div class="row-fluid">
								<div class="span6">
									<div class="control-group">
										<label class="control-label" for="disp_tanggal">Tanggal Pendaftaran</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('tanggal', $data->tanggal);
												$tanggal = strftime( "%d/%m/%Y", strtotime($value));
											?>
											<input type="hidden" id="tanggal" name="tanggal" value="<?php echo $value; ?>" />
											<input class="span6" type="text" id="disp_tanggal" name="disp_tanggal" data-date-format="mm/dd/yyyy" placeholder="__/__/____" value="<?php echo $tanggal; ?>" />
											<?php echo form_error('tanggal'); ?>
										</div>
									</div>
								</div>
								<div class="span6">
									<div class="control-group">
										<label class="control-label" for="no_register">No. Register</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('no_register', $data->no_register);
											?>
											<input class="span6" type="text" id="no_register" name="no_register" placeholder="No. Register" value="<?php echo $value; ?>" autocomplte="off" />
											<?php echo form_error('no_register'); ?>
										</div>
									</div>
								</div>
							</div>
							<hr />
							<div class="row-fluid">
							
								<div class="span6">
									
									<div class="control-group">
										<div class="controls controls-row">
											<button id="generate_medrec" type="button" class="btn">Generate Medrec</button>
											<img id="loading_generate_medrec" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="no_medrec">No. Medrec</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('no_medrec', $data->no_medrec);
											?>
											<input class="span6" type="text" id="no_medrec" name="no_medrec" placeholder="No. Medrec" value="<?php echo $value; ?>" autocomplte="off" />
											<a id="cari_pasien_button" href="#">
												<span id="toggle-btn" class="add-on btn">Cari...</span>
											</a>
											<?php echo form_error('no_medrec'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="nama">Nama</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama', $data->nama);
											?>
											<input class="span12" type="text" id="nama" name="nama" placeholder="Nama" value="<?php echo $value; ?>" autocomplte="off" />
											<?php echo form_error('nama'); ?>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label" for="jenis_kelamin1">Jenis Kelamin</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('jenis_kelamin', $data->jenis_kelamin);
											?>
											<label class="radio inline">
												<input type="radio" id="jenis_kelamin1" name="jenis_kelamin" value="1" <?php echo $value == 1 ? "checked=\"checked\"" : ""; ?>>Laki-laki
											</label>
											<label class="radio inline">
												<input type="radio" id="jenis_kelamin2" name="jenis_kelamin" value="2" <?php echo $value == 2 ? "checked=\"checked\"" : ""; ?>>Perempuan
											</label>
											<?php echo form_error('jenis_kelamin'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="alamat">Alamat</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('alamat_jalan', $data->alamat_jalan);
											?>
											<textarea class="span12" id="alamat_jalan" name="alamat_jalan" placeholder="Jalan"><?php echo $value; ?></textarea>
											<?php echo form_error('alamat_jalan'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="provinsi_id">&nbsp;</label>
										<div class="controls controls-row">
											<div class="span5">
												<label>Provinsi</label>
											</div>
											<div class="span7">
												<?php
													$value = set_value('provinsi_id', $data->provinsi_id);
													$provinsi = new stdClass();
													$provinsi->id = 0;
													$provinsi->nama = "- Pilih Provinsi -";
													$first = array($provinsi);
													$provinsi_list = array_merge($first, $provinsi_list);
												?>
												<select class="span12" id="provinsi_id" name="provinsi_id">
													<?php
														foreach ($provinsi_list as $val) {
															if ($value == $val->id) {
																echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
															} else {
																echo "<option value=\"{$val->id}\">{$val->nama}</option>";
															}
														}
													?>
												</select>
												<img id="loading_provinsi" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="kabupaten_id">&nbsp;</label>
										<div class="controls controls-row">
											<div class="span5">
												<label>Kabupaten/Kota</label>
											</div>
											<div class="span7">
												<select class="span12" id="kabupaten_id" name="kabupaten_id">
													<option value="0">- Pilih Kabupaten/Kota -</option>
												</select>
												<img id="loading_kabupaten" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="kecamatan_id">&nbsp;</label>
										<div class="controls controls-row">
											<div class="span5">
												<label>Kecamatan</label>
											</div>
											<div class="span7">
												<select class="span12" id="kecamatan_id" name="kecamatan_id">
													<option value="0">- Pilih Kecamatan -</option>
												</select>
												<img id="loading_kecamatan" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="kelurahan_id">&nbsp;</label>
										<div class="controls controls-row">
											<div class="span5">
												<label>Kelurahan/Desa</label>
											</div>
											<div class="span7">
												<select class="span12" id="kelurahan_id" name="kelurahan_id">
													<option value="0">- Pilih Kelurahan/Desa -</option>
												</select>
												<img id="loading_kelurahan" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px; display: none;" />
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="kodepos">&nbsp;</label>
										<div class="controls controls-row">
											<div class="span5">
												<label>Kodepos</label>
											</div>
											<div class="span7">
												<?php
													$value = set_value('kodepos', $data->kodepos);
												?>
												<input class="span12" type="text" id="kodepos" name="kodepos" placeholder="Kodepos" value="<?php echo $value; ?>" autocomplte="off" />
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="telepon">Telepon</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('telepon', $data->telepon);
											?>
											<input class="span12" type="text" id="telepon" name="telepon" placeholder="Telepon" value="<?php echo $value; ?>" autocomplte="off" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="tempat_lahir">Tempat Lahir</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('tempat_lahir', $data->tempat_lahir);
											?>
											<input class="span12" type="text" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" value="<?php echo $value; ?>" autocomplte="off" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="disp_tanggal_lahir">Tanggal Lahir</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('tanggal_lahir', $data->tanggal_lahir);
												if ($value != "")
													$tanggal = strftime( "%d/%m/%Y", strtotime($value));
												else
													$tanggal = "";
											?>
											<input type="hidden" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo $value; ?>">
											<input class="span6" type="text" id="disp_tanggal_lahir" name="disp_tanggal_lahir" placeholder="" value="<?php echo $tanggal; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="name">Umur</label>
										<div class="controls controls-row">
											<div style="float: left; width: 35px;">
												<?php
													$value = set_value('umur_tahun', $data->umur_tahun);
												?>
												<input class="span12" type="text" id="umur_tahun" name="umur_tahun" placeholder="" value="<?php echo $value; ?>" autocomplte="off" />
											</div>
											<div style="float: left; width: 40px;">
												<label style="margin: 5px;">tahun</label>
											</div>
											<div style="float: left; width: 30px;">
												<?php
													$value = set_value('umur_bulan', $data->umur_bulan);
												?>
												<input class="span12" type="text" id="umur_bulan" name="umur_bulan" placeholder="" value="<?php echo $value; ?>" autocomplte="off" />
											</div>
											<div style="float: left; width: 40px;">
												<label style="margin: 5px;">bulan</label>
											</div>
											<div style="float: left; width: 30px;">
												<?php
													$value = set_value('umur_hari', $data->umur_hari);
												?>
												<input class="span12" type="text" id="umur_hari" name="umur_hari" placeholder="" value="<?php echo $value; ?>" autocomplte="off" />
											</div>
											<div style="float: left; width: 40px;">
												<label style="margin: 5px;">hari</label>
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="golongan_darah">Golongan Darah</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('golongan_darah', $data->golongan_darah);
												$first = array();
												$first = array(0 => '- Pilih Golongan Darah -');
												$golongan_darah_list = array_merge($first, $golongan_darah_list);
											?>
											<select id="golongan_darah" name="golongan_darah">
												<?php
													foreach ($golongan_darah_list as $key => $val) {
														if ($value == $key) {
															echo "<option value=\"{$key}\" selected=\"selected\">{$val}</option>";
														}
														else {
															echo "<option value=\"{$key}\">{$val}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="agama_id">Agama</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('agama_id', $data->agama_id);
												$agama = new stdClass();
												$agama->id = 0;
												$agama->nama = "- Pilih Agama -";
												$first = array($agama);
												$agama_list = array_merge($first, $agama_list);
											?>
											<select id="agama_id" name="agama_id">
												<?php
													foreach ($agama_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="pendidikan_id">Pendidikan</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('pendidikan_id', $data->pendidikan_id);
												$pendidikan = new stdClass();
												$pendidikan->id = 0;
												$pendidikan->nama = "- Pilih Pendidikan -";
												$first = array($pendidikan);
												$pendidikan_list = array_merge($first, $pendidikan_list);
											?>
											<select id="pendidikan_id" name="pendidikan_id">
												<?php
													foreach ($pendidikan_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="pekerjaan_id">Pekerjaan</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('pekerjaan_id', $data->pekerjaan_id);
												$pekerjaan = new stdClass();
												$pekerjaan->id = 0;
												$pekerjaan->nama = "- Pilih Pekerjaan -";
												$first = array($pekerjaan);
												$pekerjaan_list = array_merge($first, $pekerjaan_list);
											?>
											<select id="pekerjaan_id" name="pekerjaan_id">
												<?php
													foreach ($pekerjaan_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="status_kawin">Status</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('status_kawin', $data->status_kawin);
												$first = array();
												$first = array(0 => '- Pilih Status -');
												$status_kawin_list = array_merge($first, $status_kawin_list);
											?>
											<select id="status_kawin" name="status_kawin" class="span12">
												<?php
													foreach ($status_kawin_list as $key => $val) {
														if ($value == $key) {
															echo "<option value=\"{$key}\" selected=\"selected\">{$val}</option>";
														} else {
															echo "<option value=\"{$key}\">{$val}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="nama_keluarga">Nama Keluarga</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama_keluarga', $data->nama_keluarga);
											?>
											<input class="span12" type="text" id="nama_keluarga" name="nama_keluarga" placeholder="" maxlength="60" value="<?php echo $value; ?>" autocomplete="off" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="nama_pasangan">Nama Suami/Istri</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama_pasangan', $data->nama_pasangan);
											?>
											<input class="span12" type="text" id="nama_pasangan" name="nama_pasangan" placeholder="" maxlength="60" value="<?php echo $value; ?>" autocomplete="off" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="nama_orang_tua">Nama Ayah/Ibu</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('nama_orang_tua', $data->nama_orang_tua);
											?>
											<input class="span12" type="text" id="nama_orang_tua" name="nama_orang_tua" placeholder="" maxlength="60" value="<?php echo $value; ?>" autocomplete="off" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="pendidikan_orang_tua_id">Pendidikan Ayah/Ibu/Suami/Istri</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('pendidikan_orang_tua_id', $data->pendidikan_orang_tua_id);
												$pendidikan = new stdClass();
												$pendidikan->id = 0;
												$pendidikan->nama = "- Pilih Pendidikan -";
												$first = array($pendidikan);
												$pendidikan_list = array_merge($first, $pendidikan_list);
											?>
											<select id="pendidikan_orang_tua_id" name="pendidikan_orang_tua_id">
												<?php
													foreach ($pendidikan_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="pekerjaan_orang_tua_id">Pekerjaan Ayah</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('pekerjaan_orang_tua_id', $data->pekerjaan_orang_tua_id);
												$pekerjaan = new stdClass();
												$pekerjaan->id = 0;
												$pekerjaan->nama = "- Pilih Pekerjaan -";
												$first = array($pekerjaan);
												$pekerjaan_list = array_merge($first, $pekerjaan_list);
											?>
											<select id="pekerjaan_orang_tua_id" name="pekerjaan_orang_tua_id">
												<?php
													foreach ($pekerjaan_list as $val) {
														if ($value == $val->id) {
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
								
								<div class="span6">
									
									<div class="control-group">
										<label class="control-label" for="rujukan_id">Rujukan</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('rujukan_id', $data->rujukan_id);
												$first = array();
												$rujukan = new stdClass();
												$rujukan->id = 0;
												$rujukan->nama = "- Pilih Rujukan -";
												$first[] = $rujukan;
												$rujukan_list = array_merge($first, $rujukan_list);
											?>
											<select id="rujukan_id" name="rujukan_id" class="span12">
												<?php
													foreach ($rujukan_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
											<?php echo form_error('rujukan_id'); ?>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="cara_bayar_id">Kelompok Pasien</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('cara_bayar_id', $data->cara_bayar_id);
												$first = array();
												$cara_bayar = new stdClass();
												$cara_bayar->id = 0;
												$cara_bayar->nama = "- Pilih Cara Pembayaran -";
												$cara_bayar->jenis_cara_bayar = 0;
												$first[] = $cara_bayar;
												$cara_bayar_list = array_merge($first, $cara_bayar_list);
											?>
											<select id="cara_bayar_id" name="cara_bayar_id" class="span12">
												<?php
													foreach ($cara_bayar_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}|{$val->jenis_cara_bayar}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}|{$val->jenis_cara_bayar}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
											<?php echo form_error('cara_bayar_id'); ?>
										</div>
									</div>
									
									<div id="section_no_asuransi" class="control-group">
										<label class="control-label" for="no_asuransi">No. Asuransi</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('no_asuransi', $data->no_asuransi);
											?>
											<input class="span12" type="text" id="no_asuransi" name="no_asuransi" placeholder="" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div id="section_peserta_asuransi" class="control-group">
										<label class="control-label" for="peserta_asuransi">Peserta Asuransi</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('peserta_asuransi', $data->peserta_asuransi);
											?>
											<input class="span12" type="text" id="peserta_asuransi" name="peserta_asuransi" placeholder="" value="<?php echo $value; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="dokter_id">Dokter</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('dokter_id', $data->dokter_id);
												$first = array();
												$dokter = new stdClass();
												$dokter->id = 0;
												$dokter->nama = "- Pilih Dokter -";
												$first[] = $dokter;
												$dokter_list = array_merge($first, $dokter_list);
											?>
											<select id="dokter_id" name="dokter_id" class="span12">
												<?php
													foreach ($dokter_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
											<?php echo form_error('dokter_id'); ?>
										</div>
									</div>
									
									<br>
									<h5>Penanggung Jawab</h5>
									<hr>
									
									<div class="control-group">
										<label class="control-label" for="pj_nama">Nama</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('pj_nama', $data->pj_nama);
											?>
											<input class="span12" type="text" id="pj_nama" name="pj_nama" placeholder="Nama Penanggung Jawab" maxlength="60" value="<?php echo $value; ?>" autocomplete="off" />
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="pj_hubungan">Hub. dengan pasien</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('pj_hubungan', $data->pj_hubungan);
												$first = array();
												$first = array(0 => '- Pilih Hubungan -');
												$pj_hubungan_list = array_merge($first, $pj_hubungan_list);
											?>
											<select id="pj_hubungan" name="pj_hubungan" class="span12">
												<?php
													foreach ($pj_hubungan_list as $key => $val) {
														if ($value == $key) {
															echo "<option value=\"{$key}\" selected=\"selected\">{$val}</option>";
														} else {
															echo "<option value=\"{$key}\">{$val}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="pj_pekerjaan_id">Pekerjaan</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('pj_pekerjaan_id', $data->pj_pekerjaan_id);
											?>
											<select id="pj_pekerjaan_id" name="pj_pekerjaan_id" class="span12">
												<?php
													foreach ($pekerjaan_list as $val) {
														if ($value == $val->id) {
															echo "<option value=\"{$val->id}\" selected=\"selected\">{$val->nama}</option>";
														} else {
															echo "<option value=\"{$val->id}\">{$val->nama}</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="pj_alamat">Alamat</label>
										<div class="controls controls-row">
											<?php
												$value = set_value('pj_alamat', $data->pj_alamat);
											?>
											<textarea class="span12" id="pj_alamat" name="pj_alamat" placeholder="Alamat"><?php echo $value; ?></textarea>
										</div>
									</div>
									
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
					<div>
						<?php
							$value = set_value('pendaftaran_id', $data->pendaftaran_id);
						?>
						<input type="hidden" id="pendaftaran_id" name="pendaftaran_id" value="<?php echo $value; ?>" />
						<?php
							$value = set_value('pasien_id', $data->pasien_id);
						?>
						<input type="hidden" id="pasien_id" name="pasien_id" value="<?php echo $value; ?>" />
						<input type="hidden" id="baru" name="baru" value="<?php echo $data->baru; ?>" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="pasien_modal" data-remote="<?php echo site_url('pendaftaran_igd/pendaftaran_igd/browse_pasien'); ?>" >
    <div class="modal-header">
       <a class="close" data-dismiss="modal">&times;</a>
       <h4>Daftar Pasien</h4>
    </div>
    <div class="modal-body"></div>
</div>