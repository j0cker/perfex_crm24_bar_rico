<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
  <div class="panel_s mbot10">
   <div class="panel-body _buttons">
    <?php $this->load->view('admin/estimates/estimates_top_stats');
    ?>
    <?php if(has_permission('estimates','','create')){ ?>
     <a href="<?php echo admin_url('estimates/estimate'); ?>" class="btn btn-info pull-left new new-estimate-btn"><?php echo _l('create_new_estimate'); ?></a>
   <?php } ?>
   <?php if(has_permission('estimates','','editar')){ ?>
    <a style="margin-left: 10px;" href="<?php echo admin_url('estimates/import'); ?>" class="btn btn-success pull-left display-block">
        Importar               
    </a>
   <?php } ?>
   <?php if(has_permission('estimates','','eliminar')){ ?>
    <a style="margin-left: 10px;" href="<?php echo admin_url('otrosIngresos/delete/all'); ?>" class="btn btn-danger pull-left display-block">
        Eliminar Todo               
    </a>
   <?php } ?>
   <div class="display-block text-right">
     <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-filter" aria-hidden="true"></i>
      </button>
      <ul class="dropdown-menu width300">
       <li>
        <a href="#" data-cview="all" onclick="dt_custom_view('','.table-estimates',''); return false;">
          <?php echo _l('estimates_list_all'); ?>
        </a>
       </li>
       <li class="divider"></li>
      <div class="clearfix"></div>
      <?php if(count($estimates_years) > 0){ ?>
        <li class="divider"></li>
        <?php foreach($estimates_years as $year){ ?>
          <li class="active">
            <a href="#" data-cview="year_<?php echo $year['year']; ?>" onclick="dt_custom_view(<?php echo $year['year']; ?>,'.table-estimates','year_<?php echo $year['year']; ?>'); return false;"><?php echo $year['year']; ?>
          </a>
        </li>
  <?php } ?>
<?php } ?>
</ul>
</div>
<a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view('.table-estimates','#estimate'); return false;" data-toggle="tooltip" title="<?php echo _l('estimates_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
</div>
</div>
</div>
<div class="row">
  <div class="col-md-12" id="small-table">
    <div class="panel_s">
      <div class="panel-body">
        <!-- if estimateid found in url -->
        <?php echo form_hidden('estimateid',$estimateid); ?>
        <?php $this->load->view('admin/estimates/table_html'); ?>
      </div>
    </div>
  </div>
  <div class="col-md-7 small-table-right-col">
    <div id="estimate" class="hide">
    </div>
  </div>
</div>
</div>
