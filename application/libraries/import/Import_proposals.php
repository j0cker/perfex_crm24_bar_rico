<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/import/App_import.php');

class Import_proposals extends App_import
{
    //campo a ignorar
    protected $notImportableFields = ['id'];

    //campos requeridos
    protected $requiredFields = ['description', 'rate'];

    public function __construct()
    {
        $this->addItemsGuidelines();

        parent::__construct();
    }

    public function perform()
    {
        $this->initialize();

        $databaseFields      = $this->getImportableDatabaseFields();
        $totalDatabaseFields = count($databaseFields);

        //contenido en arreglos de la información en el CSV.
        //print_r($this->getRows());
        
        //columnas totales
        $cols = 0;

        $order_increment = 0;

        echo "hola";

        $insert['subject'] = 0; //insert
        $insert['content'] = "{proposal_items}";
        $insert['addedfrom'] = 1;
        //$insert['datecreated'] = null; //insert
        $insert['total'] = 0.00;
        $insert['subtotal'] = 0.00;
        $insert['total_tax'] = 0.00;
        $insert['adjustment'] = 0.00;
        $insert['discount_percent'] = 0.00;
        $insert['discount_total'] = 0.00; 
        $insert['discount_type'] = "";
        $insert['show_quantity_as'] = 1;
        $insert['currency'] = 3;
        //$insert['open_till'] = null; //insert
        //$insert['date'] = null; //insert
        $insert['rel_id'] = 0;
        $insert['rel_type'] = "customer";
        $insert['assigned'] = 0;
        $insert['hash'] = "";
        $insert['proposal_to'] = "Clientes en General";
        $insert['country'] = 0;
        $insert['zip'] = "";
        $insert['state'] = "";
        $insert['city'] = "";
        $insert['address'] = "";
        $insert['email'] = "info@boogapp.mx";
        $insert['phone'] = "";
        $insert['allow_comments'] = 1;
        $insert['status'] = 3;
        $insert['estimate_id'] = null;
        $insert['invoice_id'] = null;
        $insert['date_converted'] = null;
        $insert['pipeline_order'] = 0;
        $insert['is_expiry_notified'] = 0;
        $insert['acceptance_firstname'] = null;
        $insert['acceptance_lastname'] = null;
        $insert['acceptance_email'] = null;
        $insert['acceptance_date'] = null;
        $insert['acceptance_ip'] = null;
        $insert['signature'] = null;
        
        
        //foreach obtiene cada renglón
        foreach ($this->getRows() as $rowNumber => $row) {

            $order_increment++;

            if($cols==0){
                //columnas
                $cols = count($row);
                //print_r($row);
            } 

            for ($i = 0; $i<$cols; $i++) {

                /*
                if($i==0){
                   $insert["number"] = (int)str_replace('PRO-', '', $this->checkNullValueAddedByUser($row[$i]));
                }
                */
                if($i==1){
                    //# de comanda
                    $value[0] = $this->checkNullValueAddedByUser($row[$i]);

                }
                if($i==2){
                    //zona del bar
                    $insert["subject"] = $this->checkNullValueAddedByUser($row[$i]);

                }
                if($i==3){

                    //echo $row[$i];

                    $insert["datecreated"] = $this->checkNullValueAddedByUser($row[$i]);
                    $insert["open_till"] = $this->checkNullValueAddedByUser($row[$i]);
                    $insert["date"] = $this->checkNullValueAddedByUser($row[$i]);
                }
                if($i==4){
                    $insert['total'] = str_replace('$', '', $this->checkNullValueAddedByUser($row[$i]));
                }
                if($i==5){
                    $insert['subtotal'] = str_replace('$', '', $this->checkNullValueAddedByUser($row[$i]));
                }
                if($i==6){
                    $insert['total_tax'] = str_replace('$', '', $this->checkNullValueAddedByUser($row[$i]));
                }
                if($i==8){
                    $insert["discount_total"] = $this->checkNullValueAddedByUser($row[$i]);
                }
                if($i==9){
                    $insert["adjustment"] = $this->checkNullValueAddedByUser($row[$i]);
                }
                if($i==10){
                    //tarjeta/efectivo
                    $value[1] = utf8_encode($this->checkNullValueAddedByUser($row[$i]));
                }
                if($i==11){
                    //mesero
                    $value[2] = utf8_encode($this->checkNullValueAddedByUser($row[$i]));
                }
                if($i==12){
                    $orden = explode("-", $this->checkNullValueAddedByUser($row[$i]));
                }
                
            }

            $insert = $this->trimInsertValues($insert);

            //lista para insartar $insert
            
            if (count($insert) > 0) {

                $this->incrementImported();

                if (!$this->isSimulation()) {


                    $this->ci->db->insert(db_prefix().'proposals', $insert);
                    $id = $this->ci->db->insert_id();

                    $i = 0;
                    foreach($value as $valor){
                        if($valor!=""){

                            //# de comanda
                            //tarjeta/efectivo
                            //mesero

                            if($i==0){

                                $insert_customfieldsvalues["fieldid"] = 1;
                                $insert_customfieldsvalues["value"] = $valor;
                                
                            }
                            if($i==1){

                                $insert_customfieldsvalues["fieldid"] = 3;
                                $insert_customfieldsvalues["value"] = $valor;
                                
                            }
                            if($i==2){

                                $insert_customfieldsvalues["fieldid"] = 6;
                                $insert_customfieldsvalues["value"] = $valor;
                                
                            }

                            $insert_customfieldsvalues["relid"] = $id;
                            $insert_customfieldsvalues["fieldto"] = "proposal";

                            $insert_customfieldsvalues = $this->trimInsertValues($insert_customfieldsvalues);

                            $this->ci->db->insert(db_prefix().'customfieldsvalues', $insert_customfieldsvalues);
                            $this->ci->db->insert_id();

                            $i++;

                        }
                    }

                    
                    
                    foreach($orden as $item){

                        if($item!=""){

                            $item2 = explode(" Cantidad: ", $item);
                            $item3 = explode(" Precio: ", str_replace('$ ', '', $item2[1]));
                            $nombre = $item2[0];
                            $cantidad = $item3[0];
                            $precio = $item3[1];
                            
                            $insert_itemable["rel_id"] = $id;
                            $insert_itemable["rel_type"] = "proposal";
                            $insert_itemable["description"] = utf8_encode($nombre);
                            $insert_itemable["qty"] = $cantidad;
                            $insert_itemable["rate"] = $precio;
                            $insert_itemable["unit"] = null;
                            $insert_itemable["item_order"] = $order_increment;
        
                            $this->ci->db->insert(db_prefix().'itemable', $insert_itemable);
                            $this->ci->db->insert_id();
                            
                        }
                    }

                } else {
                    $this->simulationData[$rowNumber] = $this->formatValuesForSimulation($insert);
                }
            }

            if ($this->isSimulation() && $rowNumber >= $this->maxSimulationRows) {
                break;
            }

        }
        
        /*
        foreach ($this->getRows() as $rowNumber => $row) {
            $insert = [];
            for ($i = 0; $i < $totalDatabaseFields; $i++) {
                $row[$i] = $this->checkNullValueAddedByUser($row[$i]);

                if ($databaseFields[$i] == 'description' && $row[$i] == '') {
                    $row[$i] = '/';
                } elseif (startsWith($databaseFields[$i], 'rate') && !is_numeric($row[$i])) {
                    $row[$i] = 0;
                } elseif ($databaseFields[$i] == 'group_id') {
                    $row[$i] = $this->groupValue($row[$i]);
                } elseif ($databaseFields[$i] == 'tax' || $databaseFields[$i] == 'tax2') {
                    $row[$i] = $this->taxValue($row[$i]);
                }

                $insert[$databaseFields[$i]] = $row[$i];
            }

            $insert = $this->trimInsertValues($insert);

            if (count($insert) > 0) {
                $this->incrementImported();

                if (!empty($insert['tax2']) && empty($insert['tax'])) {
                    $insert['tax']  = $insert['tax2'];
                    $insert['tax2'] = 0;
                }

                $id = null;

                if (!$this->isSimulation()) {
                    $this->ci->db->insert(db_prefix().'items', $insert);
                    $id = $this->ci->db->insert_id();
                } else {
                    $this->simulationData[$rowNumber] = $this->formatValuesForSimulation($insert);
                }

                $this->handleCustomFieldsInsert($id, $row, $i, $rowNumber, 'items_pr');
            }

            if ($this->isSimulation() && $rowNumber >= $this->maxSimulationRows) {
                break;
            }
        }

        */
    }

    public function formatFieldNameForHeading($field)
    {
        $this->ci->load->model('currencies_model');

        if (strtolower($field) == 'group_id') {
            return 'Group';
        } elseif (startsWith($field, 'rate')) {
            $str = 'Rate - ';
            // Base currency
            if ($field == 'rate') {
                $str .= $this->ci->currencies_model->get_base_currency()->name;
            } else {
                $str .= $this->ci->currencies_model->get(strafter($field, 'rate_currency_'))->name;
            }

            return $str;
        }

        return parent::formatFieldNameForHeading($field);
    }

    protected function failureRedirectURL()
    {
        return admin_url('estimates/import');
    }

    private function addItemsGuidelines()
    {
    }

    private function formatValuesForSimulation($values)
    {
        foreach ($values as $column => $val) {
            if ($column == 'group_id' && !empty($val) && is_numeric($val)) {
                $group = $this->getGroupBy('id', $val);
                if ($group) {
                    $values[$column] = $group->name;
                }
            } elseif (($column == 'tax' || $column == 'tax2') && !empty($val) && is_numeric($val)) {
                $tax = $this->getTaxBy('id', $val);
                if ($tax) {
                    $values[$column] = $tax->name . ' (' . $tax->taxrate . '%)';
                }
            }
        }

        return $values;
    }

    private function getTaxBy($field, $idOrName)
    {
        $this->ci->db->where($field, $idOrName);

        return $this->ci->db->get(db_prefix().'taxes')->row();
    }

    private function getGroupBy($field, $idOrName)
    {
        $this->ci->db->where($field, $idOrName);

        return $this->ci->db->get(db_prefix().'items_groups')->row();
    }

    private function taxValue($value)
    {
        if ($value != '') {
            if (!is_numeric($value)) {
                $tax   = $this->getTaxBy('name', $value);
                $value = $tax ? $tax->id : 0;
            }
        } else {
            $value = 0;
        }

        return $value;
    }

    private function groupValue($value)
    {
        if ($value != '') {
            if (!is_numeric($value)) {
                $group = $this->getGroupBy('name', $value);
                $value = $group ? $group->id : 0;
            }
        } else {
            $value = 0;
        }

        return $value;
    }
}
