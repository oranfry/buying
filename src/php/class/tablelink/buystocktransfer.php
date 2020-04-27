<?php
namespace tablelink;

class buystocktransfer extends \Tablelink
{
    public function __construct()
    {
        $this->tables = ['buy', 'stocktransfer'];
        $this->middle_table = 'tablelink_buy_stocktransfer';
        $this->ids = ['buy', 'stocktransfer'];
        $this->type = 'onemany';
    }
}
