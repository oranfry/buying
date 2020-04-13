<?php
namespace linetype;

class buy extends \Linetype
{
    public function __construct()
    {
        $this->table = 'buy';
        $this->label = 'Buy';
        $this->icon = 'basket';
        $this->fields = [
            (object) [
                'name' => 'icon',
                'type' => 'icon',
                'fuse' => "'basket'",
                'derived' => true,
            ],
            (object) [
                'name' => 'date',
                'type' => 'date',
                'fuse' => 't.date',
                'main' => true,
            ],
            (object) [
                'name' => 'shop',
                'type' => 'text',
                'fuse' => 't.shop',
            ],
            (object) [
                'name' => 'branch',
                'type' => 'text',
                'fuse' => 't.branch',
            ],
        ];
        $this->children = [
            (object) [
                'label' => 'transactions',
                'linetype' => 'transaction',
                'parent_link' => 'buytransaction',
                'rel' => 'many',
            ],
            (object) [
                'label' => 'stocktransfers',
                'linetype' => 'stocktransfer',
                'parent_link' => 'buystocktransfer',
                'rel' => 'many',
            ]
        ];
        $this->unfuse_fields = [
            't.date' => ':date',
            't.shop' => ':shop',
            't.branch' => ':branch',
        ];
    }

    public function astext($line, $child_sets)
    {
        $skumetas = get_sku_meta();

        $printout = '';
        $printout .= str_pad("Buy", 42, " ", STR_PAD_BOTH) . "\n";
        $printout .= "\n";
        $printout .= str_pad("{$line->date}", 42, " ", STR_PAD_BOTH) . "\n";

        $printout .= "\n\n";

        $subtotal = '0.00';

        foreach ($child_sets['stocktransfers']->lines as $s => $stocktransfer) {
            $meta = @$skumetas[$stocktransfer->sku];

            $printout .= '[  ]  ' . (@$meta->title ?: $stocktransfer->sku) . "\n";

            $qty_line = "      {$stocktransfer->amount} " . (@$meta->unit ?: '') . " ";

            $printout .= $qty_line;
            $printout .= str_pad(' $' . number_format($stocktransfer->price, 2), 42 - strlen($qty_line), '.', STR_PAD_LEFT);
            $subtotal = bcadd($subtotal, $stocktransfer->price, 2);

            $printout .= "\n\n";
        }

        if (isset($child_sets['transactions']) and property_exists($child_sets['transactions'], 'summary')) {
            $summary = $child_sets['transactions']->summary;
            $printout .=  "\n";

            $total = bcmul('-1', $summary->amount, 2);

            $gst = bcmul('-1', @$summary->gst ?: '0.00', 2);

            if ($subtotal != $total) {
                $printout .= "Subtotal " . str_pad('$' . $subtotal, 42 - 9, ' ', STR_PAD_LEFT) . "\n";

                if (@$summary->gst) {
                    $printout .= "GST " . str_pad('$' . $gst, 42 - 4, ' ', STR_PAD_LEFT) . "\n";
                }
                $printout .= str_repeat("-", 42) . "\n";
            }

            $printout .= "Total " . str_pad('$' . $total, 42 - 6, ' ', STR_PAD_LEFT) . "\n";

            if ($subtotal == $total && @$summary->gst) {
                $printout .= "\n";
                $printout .= str_pad('(Includes GST of $' . $gst . ")", 42, ' ', STR_PAD_BOTH);
                $printout .= "\n";
            }
        }

        return $printout;
    }
}
