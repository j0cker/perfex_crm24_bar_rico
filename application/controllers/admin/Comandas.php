<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Comandas extends AdminController
{

    public function __construct()
    {
        parent::__construct();

    }

    public function deleteAll()
    {

        log_activity('[Comandas][deleteAll]');
        
        //custom fields proposal erase
        $this->db->where('fieldto', 'proposal');
        $deleteCustom = $this->db->delete(db_prefix() . 'customfieldsvalues');

        //proposals erase
        $this->db->where('1', '1');
        $deleteProposals = $this->db->delete(db_prefix() . 'proposals');

        //itemable erase
        $this->db->where('rel_type', 'proposal');
        $deleteItemable = $this->db->delete(db_prefix() . 'itemable');

        if($deleteCustom == 1 && $deleteProposals == 1 && $deleteItemable == 1){

            echo "<meta http-equiv='refresh'
            content='5; url=http://rico.boogapp.mx/admin/comandas'>Se borraron todos los registros con respecto a Comandas";

        } else {

            echo "<meta http-equiv='refresh'
            content='5; url=http://rico.boogapp.mx/admin/comandas'>No Se Pudieron borrar todos los registros con respecto a Comandas";
        }

        //fieldto proposal

        /*

        $select = [
            '' . db_prefix() . 'proposals.id as id',
            'subject',
            'proposal_to',
            'date',
            'datecreated',
            'subtotal',
            'total',
            'total_tax',
            'discount_total',
            'adjustment',
            'status',
        ];

        $aColumns     = $select;
        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'invoicepaymentrecords';
        $join         = [
            'JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid',
            'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
            'LEFT JOIN ' . db_prefix() . 'payment_modes ON ' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'invoicepaymentrecords.paymentmode',
        ];

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
            'number',
            'clientid',
            db_prefix() . 'payment_modes.name',
            db_prefix() . 'payment_modes.id as paymentmodeid',
            'paymentmethod',
            'deleted_customer_name',
        ]);

        echo "hola";

        */

    }

}