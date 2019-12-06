<?php

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

$dimensions['wk'] = 100;
$dimensions['rm'] = 20;
$dimensions['lm'] = 20;

$info_right_column = '';
$info_left_column  = '';

/*
if (get_option('show_status_on_pdf_ei') == 1) {
    $info_right_column .= '<br /><span style="color:rgb(' . estimate_status_color_pdf($status) . ');text-transform:uppercase;">' . format_estimate_status($status, '', false) . '</span>';
}
*/

/* Add logo*/
$info_left_column .= pdf_logo_url();

// Write top left logo and right column info/text
//pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);
 
$pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', '', $info_left_column, 0, 1, false, true, 'C', true);
$pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', '', $info_right_column, 0, 1, false, true, 'C', true);

$pdf->ln(2);

$organization_info = '<div style="text-align: left; color:#424242;">';
    $organization_info .= format_organization_info();
$organization_info .= '</div><br />';

// Estimate to
$estimate_info .= '<div style="text-align: left; color:#424242;">';
$estimate_info .= 'Para: '.format_customer_info($estimate, 'estimate', 'billing').'<br />';
$estimate_info .= format_estimate_info($estimate, 'pdf');

// ship to to
if ($estimate->include_shipping == 1 && $estimate->show_shipping_on_estimate == 1) {
    $estimate_info .= '<b>' . _l('ship_to') . '</b>';
    $estimate_info .= format_customer_info($estimate, 'estimate', 'shipping');
}

//$estimate_info .= '<br />' . _l('estimate_data_date') . ': ' . _d($estimate->date) . '<br />';

$estimate_info .= '<br /><b style="color:#4e4e4e;"># ' . $estimate_number . '</b><br />';


if (!empty($estimate->reference_no)) {
    $estimate_info .= '' . $estimate->reference_no . '<br /><br />';
}


if (!empty($estimate->datecreated)) {
    $estimate_info .= 'Creado el: ' . _d($estimate->datecreated) . '<br />';
}

if ($estimate->sale_agent != 0 && get_option('show_sale_agent_on_estimates') == 1) {
    $estimate_info .= _l('sale_agent_string') . ': ' . get_staff_full_name($estimate->sale_agent) . '<br />';
}

if ($estimate->project_id != 0 && get_option('show_project_on_estimate') == 1) {
    $estimate_info .= _l('project') . ': ' . get_project_name_by_id($estimate->project_id) . '<br />';
}

foreach ($pdf_custom_fields as $field) {
    $value = get_custom_field_value($estimate->id, $field['id'], 'estimate');
    if ($value == '') {
        continue;
    }
    $estimate_info .= $field['name'] . ': ' . $value . '<br />';
}

$left_info  = $swap == '1' ? $estimate_info : $organization_info;
$right_info = $swap == '1' ? $organization_info : $estimate_info;

//pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', '', $left_info, 0, 1, false, true, 'C', true);
$pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', '', $right_info, 0, 1, false, true, 'C', true);


$estimate_info .= '</div>';

// The Table
$pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 1));

// The items table
$items = get_items_table_data($estimate, 'estimate', 'pdf');

//$tblhtml = $items->table();
//$pdf->writeHTML($tblhtml, true, false, false, false, '');

$tbltotal = "";
$tbltotal .= '<table style="width: 250px;" cellpadding="3">';
$tbltotal .= '<tr><td style="width: 100px;">Art.</td><td style="width: 75px;">Cant.</td><td style="width: 75px;">Precio</td></tr>';
$tbltotal .= '';

foreach ($estimate->items as $item) {

    $tbltotal .= '<tr><td style="width: 100px; font-size: 12px;">' .substr($item["description"],0,10). '</td><td style="width: 75px;">'.$item["qty"].'</td><td style="width: 75px;">'.$item["rate"].'</td></tr>';
}

$tbltotal .= '</table>';

$pdf->Ln(8);
$tbltotal .= '<br /><br />';
$tbltotal .= '<table style="width: 250px;" cellpadding="6" style="font-size:' . ($font_size + 4) . 'px">';
$tbltotal .= '
<tr>
    <td align="left" width="16%"><strong>' . _l('estimate_subtotal') . ':</strong></td>
    <td align="left" width="15%">' . app_format_money($estimate->subtotal, $estimate->currency_name) . '</td>
</tr>';

if (is_sale_discount_applied($estimate)) {
    $tbltotal .= '
    <tr>
        <td align="left" width="16%"><strong>' . _l('estimate_discount') . ': ';
    if (is_sale_discount($estimate, 'percent')) {
        $tbltotal .= '(' . app_format_number($estimate->discount_percent, true) . '%)';
    }
    $tbltotal .= '</strong>';
    $tbltotal .= '</td>';
    $tbltotal .= '<td align="left" width="15%">-' . app_format_money($estimate->discount_total, $estimate->currency_name) . '</td>
    </tr>';
}

foreach ($items->taxes() as $tax) {
    $tbltotal .= '<tr>
    <td align="left" width="16%"><strong>' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)' . ': </strong></td>
    <td align="left" width="15%">' . app_format_money($tax['total_tax'], $estimate->currency_name) . '</td>
</tr>';
}

if ((int)$estimate->adjustment != 0) {
    $tbltotal .= '<tr>
    <td align="left" width="16%"><strong>' . _l('estimate_adjustment') . ': </strong></td>
    <td align="left" width="15%">' . app_format_money($estimate->adjustment, $estimate->currency_name) . '</td>
</tr>';
}

$tbltotal .= '
<tr style="background-color:#f0f0f0;">
    <td align="left" width="16%"><strong>' . _l('estimate_total') . ': </strong></td>
    <td align="left" width="15%">' . app_format_money($estimate->total, $estimate->currency_name) . '</td>
</tr>';

$tbltotal .= '</table>';

$pdf->writeHTML($tbltotal, true, false, false, false, '');

if (get_option('total_to_words_enabled') == 1) {
    // Set the font bold
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('num_word') . ': ' . $CI->numberword->convert($estimate->total, $estimate->currency_name), 0, 1, 'C', 0, '', 0);
    // Set the font again to normal like the rest of the pdf
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(4);
}

if (!empty($estimate->clientnote)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('estimate_note'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(2);
    $pdf->writeHTMLCell('', '', '', '', $estimate->clientnote, 0, 1, false, true, 'L', true);
}

if (!empty($estimate->terms)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('terms_and_conditions'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(2);
    $pdf->writeHTMLCell('', '', '', '', $estimate->terms, 0, 1, false, true, 'L', true);
}
