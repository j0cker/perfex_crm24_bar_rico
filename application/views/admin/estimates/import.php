<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">

            <?php echo $this->import->downloadSampleFormHtml(); ?>
            <?php echo $this->import->maxInputVarsWarningHtml(); ?>

            <?php if(!$this->import->isSimulation()) { ?>

              <?php echo $this->import->importGuidelinesInfoHtml(); ?>

              <div class="table-responsive no-dt">
                  <table class="table table-hover table-bordered">
                      <thead>
                          <tr>
                              <th class="bold database_field_otros_ingresos">Otros Ingresos #</th>
                              <th class="bold database_field_cliente">Cliente</th>
                              <th class="bold database_field_fecha_de_creacion">Fecha de Creación</th>
                              <th class="bold database_field_importe_con_impuesto">Importe</th>
                              <th class="bold database_field_importe_con_impuesto">Importe con impuesto</th>
                              <th class="bold database_field_impuesto_total">Impuesto total</th>
                              <th class="bold database_field_iva">IVA 16.00%</th>
                              <th class="bold database_field_descuento">Descuento</th>
                              <th class="bold database_field_number_ajuste">Ajuste</th>
                              <th class="bold database_field_zona_del_bar">Zona del Bar</th>
                              <th class="bold database_field_tipo_de_pago">Tipo de Pago</th>
                              <th class="bold database_field_orden">Orden</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>Sample Data</td>
                              <td>Sample Data</td>
                              <td>Sample Data</td>
                              <td>Sample Data</td>
                              <td>Sample Data</td>
                              <td>Sample Data</td>
                              <td>Sample Data</td>
                              <td>Sample Data</td>
                              <td>Sample Data</td>
                              <td>Sample Data</td>
                              <td>Sample Data</td>
                              <td>Sample Data</td>
                          </tr>
                      </tbody>
                  </table>
              </div>

            <?php } else { ?>

              <?php echo $this->import->simulationDataInfo(); ?>
              <?php echo $this->import->createSampleTableHtml(true); ?>

            <?php } ?>
            <div class="row">
              <div class="col-md-4">
                <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'import_form')) ;?>
                <?php echo form_hidden('items_import','true'); ?>
                <?php echo render_input('file_csv','choose_csv_file','','file'); ?>
                <div class="form-group">
                  <button type="button" class="btn btn-info import btn-import-submit"><?php echo _l('import'); ?></button>
                  <button type="button" class="btn btn-info simulate btn-import-submit"><?php echo _l('simulate_import'); ?></button>
                </div>
                <?php echo form_close(); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url('assets/plugins/jquery-validation/additional-methods.min.js'); ?>"></script>
<script>
  $(function(){
   appValidateForm($('#import_form'),{file_csv:{required:true,extension: "csv"}});
 });
</script>
</body>
</html>
