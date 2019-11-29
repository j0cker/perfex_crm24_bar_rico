<?php

/*
proposal pdf se apoya de sales_helper application/helpers
*/

defined('BASEPATH') or exit('No direct script access allowed');
$dimensions = $pdf->getPageDimensions();


$dimensions['wk'] = 100;
$dimensions['rm'] = 20;
$dimensions['lm'] = 20;


/*
default values
210.00014444444
10.00125
10.00125
*/

$pdf_logo_url = pdf_logo_url();
$pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', '', $pdf_logo_url, 0, 1, false, true, 'C', true);

//renglon
$pdf->ln(4);
// Get Y position for the separation
$y = $pdf->getY();

$proposal_info = '<div style="color:#424242;">';
    $proposal_info .= format_organization_info();
$proposal_info .= '</div>';

$pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', ($swap == '0' ? $y : ''), $proposal_info, 0, 0, false, true, ($swap == '1' ? 'L' : 'J'), true);

$rowcount = max([$pdf->getNumLines($proposal_info, 80)]);

//renglon
$pdf->ln(6);

/*
$client_details .= '<br /><b>' . $dimensions['wk'] . '</b>';
$client_details .= '<br /><b>' . $dimensions['rm'] . '</b>';
$client_details .= '<br /><b>' . $dimensions['lm'] . '</b>';
*/

$client_details .= '<div style="color:#424242;">';
    $client_details .= 'Para: ' . format_proposal_info($proposal, 'pdf');
$client_details .= '</div>';

$pdf->writeHTMLCell(($dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm'])), '', '', ($swap == '1' ? $y : ''), $client_details, 0, 1, false, true, ($swap == '1' ? 'L' : 'J'), true);

//renglon
$pdf->ln(6);

//

$open_till = 'Creado el: ' . _d($proposal->datecreated) . '<br />';



/* The items table*/
$items = get_items_table_data($proposal, 'proposal', 'pdf')
        ->set_headings('estimate');

        

//$items_html = $items->table();
//background-color:#f0f0f0;

$items_html = "";
$items_html .= '<table style="width: 250px;" cellpadding="3">';
$items_html .= '<tr><td style="width: 100px;">Item</td><td style="width: 75px;">Qty</td><td style="width: 75px;">Precio</td></tr>';
foreach ($proposal->items as $item) {

    $items_html .= '<tr><td style="width: 100px; font-size: 12px;">' .substr($item["description"],0,10). '</td><td style="width: 75px;">'.$item["qty"].'</td><td style="width: 75px;">'.$item["rate"].'</td></tr>';
}
$items_html .= '</table>';

/*
$var_info = print_r($proposal->items,true);
$items_html = $var_info;
*/

$items_html .= '<br /><br />';
$items_html .= '';
$items_html .= '<table cellpadding="6" style="font-size:' . ($font_size + 4) . 'px">';

$items_html .= '
<tr>
    <td align="left" width="20%"><strong>' . _l('estimate_subtotal') . '</strong>: ' . app_format_money($proposal->subtotal, $proposal->currency_name) . '</td>
</tr>';

if (is_sale_discount_applied($proposal)) {
    $items_html .= '
    <tr>
        <td align="left" width="20%"><strong>' . _l('estimate_discount');
    if (is_sale_discount($proposal, 'percent')) {
        $items_html .= '(' . app_format_number($proposal->discount_percent, true) . '%)';
    }
    $items_html .= '</strong>';
    $items_html .= '</td></tr>';
    $items_html .= '<tr><td align="left" width="10%">-' . app_format_money($proposal->discount_total, $proposal->currency_name) . '</td>
    </tr>';
}

foreach ($items->taxes() as $tax) {
    $items_html .= '<tr>
    <td align="left" width="20%"><strong>' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)' . '</strong>: ' . app_format_money($tax['total_tax'], $proposal->currency_name) . '</td>
</tr>';
}

if ((int)$proposal->adjustment != 0) {
    $items_html .= '<tr>
    <td align="left" width="20%"><strong>' . _l('estimate_adjustment') . '</strong>: ' . app_format_money($proposal->adjustment, $proposal->currency_name) . '</td>
</tr>';
}
$items_html .= '
<tr style="background-color:#f0f0f0;">
    <td align="left" width="20%"><strong>' . _l('estimate_total') . '</strong>: ' . app_format_money($proposal->total, $proposal->currency_name) . '</td>
</tr>';
$items_html .= '</table>';

if (get_option('total_to_words_enabled') == 1) {
    $items_html .= '<br /><br /><br />';
    $items_html .= '<strong style="text-align:left;">' . _l('num_word') . ': ' . $CI->numberword->convert($proposal->total, $proposal->currency_name) . '</strong>';
}

$proposal->content = str_replace('{proposal_items}', $items_html, $proposal->content);

// Get the proposals css
// Theese lines should aways at the end of the document left side. Dont indent these lines
$html = <<<EOF
<p style="font-size:16px;"># $number
<br /><span style="font-size:10;">$proposal->subject </span>
</p>
$proposal_date
<br />
$open_till
<div style="width: 300px !important;">
$proposal->content
</div>
EOF;

$pdf->writeHTML($html, true, false, true, false, '');
