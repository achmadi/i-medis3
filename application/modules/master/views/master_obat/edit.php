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
</style>
<div class="left-sidebar">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget">
                <?php
                    if ($is_new) {
                        $url = site_url('master/master_obat/add');
                        $title = "Tambah Obat";
                    } else {
                        $url = site_url('master/master_obat/edit/'.$data->master_obat_id);
                        $title = "Edit Obat";
                    }
                ?>
                <form class="form-horizontal no-margin" id="master_obat_form" name="master_obat_form" method="post" action="<?php echo $url; ?>">
                    <div class="widget-header">
                        <div class="title"><?php echo $title; ?></div>
                        <span class="tools"><a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a></span>
                    </div>
                    <div class="widget-body">
                        <div class="container-fluid">
                            <div class="row-fluid">

                                <div class="span6">

                                    <div class="control-group">
                                        <label class="control-label" for="master_obat_kode">Kode Obat</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('master_obat_kode', $data->master_obat_kode);
                                            ?>
                                            <input class="span4" type="text" id="master_obat_kode" name="master_obat_kode" maxlength="10" placeholder="Kode Obat" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="master_obat_nama">Nama Obat</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('master_obat_nama', $data->master_obat_nama);
                                            ?>
                                            <input class="span12" type="text" id="master_obat_nama" name="master_obat_nama" maxlength="100" placeholder="Nama Obat" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="jenis_obat_ref_id">Jenis Obat</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('jenis_obat_ref_id', $data->jenis_obat_ref_id);
                                                $first = array();
                                                $jenis_obat = new stdClass();
                                                $jenis_obat->jenis_obat_ref_id = 0;
                                                $jenis_obat->jenis_obat_ref_nama = "- Pilih Jenis Obat -";
                                                $first[] = $jenis_obat;
                                                $jenis_obat_list = array_merge($first, $jenis_obat_list);
                                            ?>
                                            <select id="jenis_obat_ref_id" name="jenis_obat_ref_id">
                                                <?php
                                                    foreach ($jenis_obat_list as $val) {
                                                        if ($value == $val->jenis_obat_ref_id) {
                                                            echo "<option value=\"{$val->jenis_obat_ref_id}\" selected=\"selected\">{$val->jenis_obat_ref_nama}</option>";
                                                        } else {
                                                            echo "<option value=\"{$val->jenis_obat_ref_id}\">{$val->jenis_obat_ref_nama}</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <?php echo form_error('jenis_obat_ref_id'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="sub_jenis_obat_ref_id">Sub Jenis Obat</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('sub_jenis_obat_ref_id', $data->sub_jenis_obat_ref_id);
                                                $first = array();
                                                $subjenis_obat = new stdClass();
                                                $subjenis_obat->sub_jenis_obat_ref_id = 0;
                                                $subjenis_obat->sub_jenis_obat_ref_nama = "- Pilih Sub Jenis Obat -";
                                                $first[] = $subjenis_obat;
                                                $subjenis_obat_list = array_merge($first, $subjenis_obat_list);
                                            ?>
                                            <select id="sub_jenis_obat_ref_id" name="sub_jenis_obat_ref_id">
                                                <?php
                                                    foreach ($subjenis_obat_list as $val) {
                                                        if ($value == $val->sub_jenis_obat_ref_id) {
                                                            echo "<option value=\"{$val->sub_jenis_obat_ref_id}\" selected=\"selected\">{$val->sub_jenis_obat_ref_nama}</option>";
                                                        } else {
                                                            echo "<option value=\"{$val->sub_jenis_obat_ref_id}\">{$val->sub_jenis_obat_ref_nama}</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <?php echo form_error('sub_jenis_obat_ref_id'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="golongan_obat_ref_id">Golongan Obat</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('golongan_obat_ref_id', $data->golongan_obat_ref_id);
                                                $first = array();
                                                $gol_obat = new stdClass();
                                                $gol_obat->golongan_obat_ref_id = 0;
                                                $gol_obat->golongan_obat_ref_nama = "- Pilih Golongan Obat -";
                                                $first[] = $gol_obat;
                                                $gol_obat_list = array_merge($first, $gol_obat_list);
                                            ?>
                                            <select id="golongan_obat_ref_id" name="golongan_obat_ref_id">
                                                <?php
                                                    foreach ($gol_obat_list as $val) {
                                                        if ($value == $val->golongan_obat_ref_id) {
                                                            echo "<option value=\"{$val->golongan_obat_ref_id}\" selected=\"selected\">{$val->golongan_obat_ref_nama}</option>";
                                                        } else {
                                                            echo "<option value=\"{$val->golongan_obat_ref_id}\">{$val->golongan_obat_ref_nama}</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <?php echo form_error('golongan_obat_ref_id'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="satuan_kecil_ref_id">Satuan Kecil</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('satuan_kecil_ref_id', $data->satuan_kecil_ref_id);
                                                $first = array();
                                                $sm_unit = new stdClass();
                                                $sm_unit->satuan_kecil_ref_id = 0;
                                                $sm_unit->satuan_kecil_ref_nama = "- Pilih Satuan Kecil -";
                                                $first[] = $sm_unit;
                                                $sm_unit_list = array_merge($first, $sm_unit_list);
                                            ?>
                                            <select id="satuan_kecil_ref_id" name="satuan_kecil_ref_id">
                                                <?php
                                                    foreach ($sm_unit_list as $val) {
                                                        if ($value == $val->satuan_kecil_ref_id) {
                                                            echo "<option value=\"{$val->satuan_kecil_ref_id}\" selected=\"selected\">{$val->satuan_kecil_ref_nama}</option>";
                                                        } else {
                                                            echo "<option value=\"{$val->satuan_kecil_ref_id}\">{$val->satuan_kecil_ref_nama}</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <?php echo form_error('satuan_kecil_ref_id'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="satuan_besar_ref_id">Satuan Besar</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('satuan_besar_ref_id', $data->satuan_besar_ref_id);
                                                $first = array();
                                                $lm_unit = new stdClass();
                                                $lm_unit->satuan_besar_ref_id = 0;
                                                $lm_unit->satuan_besar_ref_nama = "- Pilih Satuan Besar -";
                                                $first[] = $lm_unit;
                                                $lm_unit_list = array_merge($first, $lm_unit_list);
                                            ?>
                                            <select id="satuan_besar_ref_id" name="satuan_besar_ref_id">
                                                <?php
                                                    foreach ($lm_unit_list as $val) {
                                                        if ($value == $val->satuan_besar_ref_id) {
                                                            echo "<option value=\"{$val->satuan_besar_ref_id}\" selected=\"selected\">{$val->satuan_besar_ref_nama}</option>";
                                                        } else {
                                                            echo "<option value=\"{$val->satuan_besar_ref_id}\">{$val->satuan_besar_ref_nama}</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <?php echo form_error('satuan_besar_ref_id'); ?>
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
                                $value = set_value('master_obat_id', $data->master_obat_id);
                        ?>
                        <input type="hidden" id="master_obat_id" name="master_obat_id" value="<?php echo $value; ?>" />
                </form>
            </div>
        </div>
    </div>
</div>