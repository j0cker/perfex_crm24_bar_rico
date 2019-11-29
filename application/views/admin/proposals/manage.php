<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="_filters _hidden_inputs">
            <?php
               foreach($statuses as $_status){
                $val = '';
                if($_status == $this->input->get('status')){
                  $val = $_status;
                }
                echo form_hidden('proposals_'.$_status,$val);
               }
               foreach($years as $year){
                echo form_hidden('year_'.$year['year'],$year['year']);
               }
               foreach($proposals_sale_agents as $agent){
                echo form_hidden('sale_agent_'.$agent['sale_agent']);
               }
               echo form_hidden('leads_related');
               echo form_hidden('customers_related');
               echo form_hidden('expired');
               ?>
         </div>
         <div class="col-md-12">
            <div class="panel_s mbot10">
               <div class="panel-body _buttons">
                  <?php if(has_permission('proposals','','create')){ ?>
                  <a href="<?php echo admin_url('proposals/proposal'); ?>" class="btn btn-info pull-left display-block">
                  <?php echo _l('new_proposal'); ?>
                  </a>
                  <?php } ?>
                  <?php if(has_permission('proposals','','editar')){ ?>
                  <a style="margin-left: 10px;" href="http://rico.boogapp.mx/admin/proposals/proposal" class="btn btn-success pull-left display-block">
                     Importar               
                  </a>
                  <?php } ?>
                  <?php if(has_permission('proposals','','eliminar')){ ?>
                  <a style="margin-left: 10px;" href="http://rico.boogapp.mx/admin/comandas/delete/all" class="btn btn-danger pull-left display-block">

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
                              <a href="#" data-cview="all" onclick="dt_custom_view('','.table-proposals',''); return false;">
                              <?php echo _l('proposals_list_all'); ?>
                              </a>
                           </li>
                           <?php if(count($years) > 0){ ?>
                           <li class="divider"></li>
                           <?php foreach($years as $year){ ?>
                           <li class="active">
                              <a href="#" data-cview="year_<?php echo $year['year']; ?>" onclick="dt_custom_view(<?php echo $year['year']; ?>,'.table-proposals','year_<?php echo $year['year']; ?>'); return false;"><?php echo $year['year']; ?>
                              </a>
                           </li>
                           <?php } ?>
                           <?php } ?>
                        </ul>
                     </div>
                     <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view('.table-proposals','#proposal'); return false;" data-toggle="tooltip" title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12" id="small-table">
                  <div class="panel_s">
                     <div class="panel-body">
                        <!-- if invoiceid found in url -->
                        <?php echo form_hidden('proposal_id',$proposal_id); ?>
                        <?php
                           $table_data = array(
                              _l('proposal') . ' #',
                              _l('proposal_subject'),
                              _l('proposal_to'),
                              _l('proposal_total'),
                              _l('proposal_date'),
                              _l('proposal_open_till'),
                              _l('tags'),
                              _l('proposal_date_created'),
                              _l('proposal_status'),
                            );

                             $custom_fields = get_custom_fields('proposal',array('show_on_table'=>1));
                             foreach($custom_fields as $field){
                                array_push($table_data,$field['name']);
                             }

                             $table_data = hooks()->apply_filters('proposals_table_columns', $table_data);
                             render_datatable($table_data,'proposals',[],[
                                 'data-last-order-identifier' => 'proposals',
                                 'data-default-order'         => get_table_last_order('proposals'),
                             ]);
                           ?>
                     </div>
                  </div>
               </div>
               <div class="col-md-7 small-table-right-col">
                  <div id="proposal" class="hide">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php $this->load->view('admin/includes/modals/sales_attach_file'); ?>
<script>var hidden_columns = [4,5,6,7];</script>
<?php init_tail(); ?>
<div id="convert_helper"></div>
<script>
   var proposal_id;
   $(function(){
     var Proposals_ServerParams = {};
     $.each($('._hidden_inputs._filters input'),function(){
       Proposals_ServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
     });
     initDataTable('.table-proposals', admin_url+'proposals/table', ['undefined'], ['undefined'], Proposals_ServerParams, [7, 'desc']);
     init_proposal();
   });
</script>
</body>
</html>
