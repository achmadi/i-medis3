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
                        $url = site_url('master/stock_obat/add');
                        $title = "Tambah Stok Obat";
                    } else {
                        $url = site_url('master/stock_obat/edit/'.$data->stock_obat_id);
                        $title = "Edit Stock Obat";
                    }
                ?>
                <form class="form-horizontal no-margin" id="stock_obat_form" name="stock_obat_form" method="post" action="<?php echo $url; ?>">
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
                                            <select id="master_obat_id" name="master_obat_id">
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
                                            <?php echo form_error('master_obat_id'); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="stock_obat_min">Stok Obat Minimum</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('stock_obat_min', $data->stock_obat_min);
                                            ?>
                                            <input class="span12" type="text" id="stock_obat_min" name="stock_obat_min" maxlength="50" placeholder="Stok Obat Minimum" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="stock_obat_active">Aktif</label>
                                        <div class="controls controls-row">
                                            <?php
                                                $value = set_value('stock_obat_active', $data->stock_obat_active);
                                                //echo $value;
                                            ?>
                                            <label class="radio inline">
                                                <input type="radio" id="stock_obat_active1" name="stock_obat_active" value="0" <?php echo $value == 0 ? "checked=\"checked\"" : ""; ?>>Tidak Aktif
                                            </label>
                                            <label class="radio inline">
                                                <input type="radio" id="stock_obat_active2" name="stock_obat_active" value="1" <?php echo $value == 1 ? "checked=\"checked\"" : ""; ?>>Aktif
                                            </label>
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
                                $value = set_value('stock_obat_id', $data->stock_obat_id);
                        ?>
                        <input type="hidden" id="stock_obat_id" name="stock_obat_id" value="<?php echo $value; ?>" />
                </form>
            </div>
        </div>
    </div>
</div>