<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="proposals-reports" class="hide">
   <table class="table table-proposals-report scroll-responsive">
      <thead>
         <tr>
            <th>ID</th>
            <th># de Comanda</th>
            <th><?php echo _l('proposal_subject'); ?></th>
            <th><?php echo _l('proposal_open_till'); ?></th>
            <th><?php echo _l('estimate_dt_table_heading_amount'); ?></th>
            <th><?php echo _l('report_invoice_amount_with_tax'); ?></th>
            <th><?php echo _l('report_invoice_total_tax'); ?></th>
            <?php foreach($proposal_taxes as $tax){ ?>
            <th><?php echo $tax['taxname']; ?> <small><?php echo $tax['taxrate']; ?>%</small></th>
            <?php } ?>
            <th><?php echo _l('estimate_discount'); ?></th>
            <th><?php echo _l('estimate_adjustment'); ?></th>
            <th><?php echo "Tipo de Pago"; ?></th>
            <th><?php echo "Mesero"; ?></th>
            <th><?php echo "Orden"; ?></th>
         </tr>
      </thead>
      <tbody></tbody>
      <tfoot>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td class="subtotal"></td>
         <td class="total"></td>
         <td class="total_tax"></td>
         <?php foreach($proposal_taxes as $key => $tax){ ?>
         <td class="total_tax_single_<?php echo $key; ?>"></td>
         <?php } ?>
         <td class="discount"></td>
         <td class="adjustment"></td>
         <th><?php echo "Tipo de Pago"; ?></th>
         <th><?php echo "Mesero"; ?></th>
         <th><?php echo "Orden"; ?></th>
      </tfoot>
   </table>
</div>
