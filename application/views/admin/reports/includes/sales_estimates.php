<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="estimates-report" class="hide">
<div class="clearfix"></div>
   <table class="table table-estimates-report scroll-responsive">
      <thead>
       <tr>
         <th><?php echo _l('estimate_dt_table_heading_number'); ?></th>
         <th><?php echo _l('estimate_dt_table_heading_client'); ?></th>
         <th><?php echo _l('estimate_dt_table_heading_expirydate'); ?></th>
         <th><?php echo _l('estimate_dt_table_heading_amount'); ?></th>
         <th><?php echo _l('report_invoice_amount_with_tax'); ?></th>
         <th><?php echo _l('report_invoice_total_tax'); ?></th>
         <?php foreach($estimate_taxes as $tax){ ?>
         <th><?php echo $tax['taxname']; ?> <small><?php echo $tax['taxrate']; ?>%</small></th>
         <?php } ?>
         <th><?php echo _l('estimate_discount'); ?></th>
         <th><?php echo _l('estimate_adjustment'); ?></th>
         <th><?php echo _l('reference_no'); ?></th>
         <th><?php echo "Tipo de Pago"; ?></th>
         <th><?php echo "Orden"; ?></th>
      </tr>
   </thead>
   <tbody></tbody>
   <tfoot>
      <tr>
         <td></td>
         <td></td>
         <td></td>
         <td class="subtotal"></td>
         <td class="total"></td>
         <td class="total_tax"></td>
         <?php foreach($estimate_taxes as $key => $tax){ ?>
         <td class="total_tax_single_<?php echo $key; ?>"></td>
         <?php } ?>
         <td class="discount_total"></td>
         <td class="adjustment"></td>
         <td></td>
         <td></td>
         <td></td>
      </tr>
   </tfoot>
</table>
</div>
