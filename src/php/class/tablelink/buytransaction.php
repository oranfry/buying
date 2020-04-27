<?php
namespace tablelink;

class buytransaction extends \Tablelink
{
    public function __construct()
    {
        $this->tables = ['buy', 'transaction'];
        $this->middle_table = 'tablelink_buy_transaction';
        $this->ids = ['buy', 'transaction'];
        $this->type = 'onemany';
    }
}
