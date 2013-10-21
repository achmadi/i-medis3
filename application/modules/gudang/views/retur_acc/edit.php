<script type="text/javascript">
	$(document).ready(function() {
           var master_obat_id = $("#master_obat_id").val();
		if (master_obat_id > 0) {
                    $.ajax({
                            type: "GET",
                            url: "<?php echo site_url('gudang/retur_acc/get_satuan') ?>",
                            data: "master_obat_id=" + master_obat_id,
                            cache: false,
                            beforeSend: function() {
                                    $('#loading_satuan_besar_label').show();
                                    $('#loading_satuan_besar_label').css("display", "inline");
                                    $('#loading_satuan_kecil_label').show();
                                    $('#loading_satuan_kecil_label').css("display", "inline");
                            },
                            success: function() {
                                    $('#loading_satuan_besar_label').hide();
                                    $('#loading_satuan_kecil_label').hide();
                            },
                            complete: function($response, $status) {
                                    if ($status == "success" || $status == "notmodified") {
                                            labels = $response.responseText.split("-");
                                                        
                                            $("#qty_satuan_besar_label").val(labels[1]);
                                            $("#qty_satuan_kecil_label").val(labels[3]);
                                            $("#satuan_besar_ref_id").val(labels[0]);
                                            $("#satuan_kecil_ref_id").val(labels[2]);
                                    }
                            }
                    });
                }
			
		$("#master_obat_id").change(function() {
			var master_obat_id = $("#master_obat_id").val();
                        var labels;
			if (master_obat_id) {
				$.ajax({
					type: "GET",
					url: "<?php echo site_url('gudang/retur_acc/get_satuan') ?>",
					data: "master_obat_id=" + master_obat_id, 
					cache: false,
					beforeSend: function() {
						$('#loading_satuan_besar_label').hide();
                                                $('#loading_satuan_besar_label').css("display", "inline");
                                                $('#loading_satuan_kecil_label').show();
                                                $('#loading_satuan_kecil_label').css("display", "inline");
					},
					success: function() {
						$('#loading_satuan_besar_label').hide();
                                                $('#loading_satuan_kecil_label').hide();
					},
					complete: function($response, $status) {
						if ($status == "success" || $status == "notmodified") {
                                                        labels = $response.responseText.split("-");
                                                        
							$("#qty_satuan_besar_label").val(labels[1]);
                                                        $("#qty_satuan_kecil_label").val(labels[3]);
                                                        $("#satuan_besar_ref_id").val(labels[0]);
                                                        $("#satuan_kecil_ref_id").val(labels[2]);
						}
					}
				});
			}
		}); 
                
                function getISODate(d){
                    var s = function(a,b){return(1e15+a+"").slice(-b)};

                    if (typeof d === 'undefined'){
                            d = new Date();
                    };

                    return d.getFullYear() + '-' +
                            s(d.getMonth()+1,2) + '-' +
                            s(d.getDate(),2);
                }
                
                var disp_tanggal = $('#disp_exp_date').datepicker({
			format: 'dd/mm/yyyy'
		}).
		on('changeDate', function(ev) {
			var date = new Date(ev.date)
			date_str = getISODate(date);
			$('#exp_date').val(date_str);
			disp_tanggal.hide();
		}).data('datepicker');
        });
</script>
<style type="text/css">
    .dashboard-wrapper {
        margin-bottom: 10px;
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
    .unit-label{
        background-color: #FAFAFA !important;
        border: 0 solid #CCCCCC !important;
    }

    input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"] {
        font-size: 12px;
    }
    label, input, button, select, textarea {
        font-size: 12px;
    }
    .datepicker {
        background: white;
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
</style>
<div class="left-sidebar">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget">
                <?php
                    if ($is_new) {
                        $url = site_url('gudang/retur_acc/add');
                        $title = "Tambah Penerimaan";
                        $extraId = "master_obat_id";
                        $extraAttr = "";
                        $extraHid = "";
                    } else {
                        $url = site_url('gudang/retur_acc/edit/'.$data->gud_penerimaan_id);
                        $title = "Edit Penerimaan";
                        $extraId = "master_obat_id_disp";
                        $extraAttr = "disabled";
                        $value = set_value('master_obat_id', $data->master_obat_id);
                        $extraHid = "<input type='hidden' id='master_obat_id' name='master_obat_id' value=$value>";
                    }
                ?>
                <form class="form-horizontal no-margin" id="retur_acc_form" name="retur_acc_form" method="post" action="<?php echo $url; ?>">
                    <div class="widget-header">
                        <div class="title"><?php echo $title; ?></div>
                        <span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
                    </div>
                    <div class="widget-body">
                        <div class="container-fluid">
                            <div class="row-fluid">

                                <div class="span6">

                                    <div class="control-group">
                                        <label class="control-label" for="master_obat_id">Nama Obat</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('master_obat_id', $data->master_obat_id);
                                                $first = array();
                                                $master_obat = new stdClass();
                                                $master_obat->master_obat_id = 0;
                                                $master_obat->master_obat_nama = "- Pilih Obat -";
                                                $first[] = $master_obat;
                                                $master_obat_list = array_merge($first, $master_obat_list);
                                            ?>
                                            <select id="<?php echo $extraId; ?>" name="<?php echo $extraId; ?>" <?php echo $extraAttr; ?>>
                                                <?php
                                                    foreach ($master_obat_list as $val) {
                                                        if ($value == $val->master_obat_id) {
                                                            echo "<option value=\"{$val->master_obat_id}\" selected=\"selected\">{$val->master_obat_nama}</option>";
                                                        } else {
                                                            echo "<option value=\"{$val->master_obat_id}\">{$val->master_obat_nama}</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <?php 
                                                echo $extraHid;
                                                echo form_error('master_obat_id'); 
                                            ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="qty_satuan_besar">Jumlah Satuan Besar</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('qty_satuan_besar', $data->qty_satuan_besar);
                                            ?>
                                            <input class="span6" type="text" id="qty_satuan_besar" name="qty_satuan_besar" maxlength="50" placeholder="Jumlah Satuan Besar" value="<?php echo $value; ?>">
                                            <img id="loading_satuan_besar_label" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px 5px 5px -170px; display: none;" />
                                            <input class="span3 unit-label" type="text" id="qty_satuan_besar_label" name="qty_satuan_besar_label" maxlength="50" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="qty_satuan_kecil">Jumlah Satuan Kecil</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('qty_satuan_kecil', $data->qty_satuan_kecil);
                                            ?>
                                            <input class="span6" type="text" id="qty_satuan_kecil" name="qty_satuan_kecil" maxlength="50" placeholder="Jumlah Satuan Kecil" value="<?php echo $value; ?>">
                                            <img id="loading_satuan_kecil_label" alt="loading" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="position: absolute; margin: 5px 5px 5px -170px; display: none;" />
                                            <input class="span3 unit-label" type="text" id="qty_satuan_kecil_label" name="qty_satuan_kecil_label" maxlength="50" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="vendor_id">Vendor</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('vendor_id', $data->vendor_id);
                                                $first = array();
                                                $vendor = new stdClass();
                                                $vendor->vendor_id = 0;
                                                $vendor->vendor_nama = "- Pilih Vendor -";
                                                $first[] = $vendor;
                                                $vendor_list = array_merge($first, $vendor_list);
                                            ?>
                                            <select id="vendor_id" name="vendor_id">
                                                <?php
                                                    foreach ($vendor_list as $val) {
                                                        if ($value == $val->vendor_id) {
                                                            echo "<option value=\"{$val->vendor_id}\" selected=\"selected\">{$val->vendor_nama}</option>";
                                                        } else {
                                                            echo "<option value=\"{$val->vendor_id}\">{$val->vendor_nama}</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <?php echo form_error('vendor_id'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="sdana_id">Sumber Dana</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('sdana_id', $data->sdana_id);
                                                $first = array();
                                                $sumber_dana = new stdClass();
                                                $sumber_dana->sdana_id = 0;
                                                $sumber_dana->sdana_nama = "- Pilih Sumber Dana -";
                                                $first[] = $sumber_dana;
                                                $sumber_dana_list = array_merge($first, $sumber_dana_list);
                                            ?>
                                            <select id="sdana_id" name="sdana_id">
                                                <?php
                                                    foreach ($sumber_dana_list as $val) {
                                                        if ($value == $val->sdana_id) {
                                                            echo "<option value=\"{$val->sdana_id}\" selected=\"selected\">{$val->sdana_nama}</option>";
                                                        } else {
                                                            echo "<option value=\"{$val->sdana_id}\">{$val->sdana_nama}</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <?php echo form_error('sdana_id'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="harga_obat_sb">Harga Obat Satuan Besar</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('harga_obat_sb', $data->harga_obat_sb);
                                            ?>
                                            <input class="span12" type="text" id="harga_obat_sb" name="harga_obat_sb" maxlength="50" placeholder="Harga Obat Satuan Besar" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="harga_obat_sk">Harga Obat Satuan Kecil</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('harga_obat_sk', $data->harga_obat_sk);
                                            ?>
                                            <input class="span12" type="text" id="harga_obat_sk" name="harga_obat_sk" maxlength="50" placeholder="Harga Obat Satuan Kecil" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="exp_date">Tanggal Kadaluarsa</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value_date = set_value('exp_date', $data->exp_date);
                                                $tanggal = strftime( "%d/%m/%Y", strtotime($value_date));
                                            ?>
                                            <input class="span6" type="text" id="disp_exp_date" nama="disp_exp_date" data-date-format="mm/dd/yyyy" placeholder="__/__/____" value="<?php echo $tanggal; ?>" />
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="form-actions" style="text-align: right;">
                                <button class="btn btn-info" type="submit" name="simpan" value="Simpan">Simpan</button>
                                <button class="btn" type="submit" name="batal" value="Batal">Batal</button>
                            </div>
                        </div>
                    </div>
                        <?php
                                $value_pid = set_value('gud_penerimaan_id', $data->gud_penerimaan_id);
                                $value_sbid = set_value('satuan_besar_ref_id', $data->satuan_besar_ref_id);
                                $value_skid = set_value('satuan_kecil_ref_id', $data->satuan_kecil_ref_id);
                        ?>
                        <input type="hidden" id="gud_penerimaan_id" name="gud_penerimaan_id" value="<?php echo $value_pid; ?>" />
                        <input type="hidden" id="satuan_besar_ref_id" name="satuan_besar_ref_id" value="<?php echo $value_sbid; ?>" />
                        <input type="hidden" id="satuan_kecil_ref_id" name="satuan_kecil_ref_id" value="<?php echo $value_skid; ?>" />
                        <input type="hidden" id="exp_date" name="exp_date" value="<?php echo $value_date; ?>" />
                </form>
            </div>
        </div>
    </div>
</div>