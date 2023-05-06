<?php

namespace buying\linetype;

class buy extends \jars\Linetype
{
    use \simplefields\traits\SimpleFields;

    public function __construct()
    {
        $this->table = 'buy';

        $this->simple_string('date');
        $this->simple_string('shop');
        $this->simple_string('branch');

        $this->children = [
            (object) [
                'property' => 'transactions',
                'linetype' => 'transaction',
                'tablelink' => 'buytransaction',
                'only_parent' => 'buy_id',
            ],
            (object) [
                'property' => 'stocktransfers',
                'linetype' => 'stocktransfer',
                'tablelink' => 'buystocktransfer',
                'only_parent' => 'buy_id',
            ]
        ];
    }
}
