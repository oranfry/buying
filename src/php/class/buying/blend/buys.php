<?php
namespace buying\blend;

class buys extends \Blend
{
    public $label = 'Buys';
    public $printable = true;
    public $linetypes = ['buy'];

    public function __construct()
    {
        $this->fields = [
            (object) [
                'name' => 'icon',
                'type' => 'icon',
            ],
            (object) [
                'name' => 'date',
                'type' => 'date',
                'main' => true,
            ],
            (object) [
                'name' => 'shop',
                'type' => 'text',
            ],
            (object) [
                'name' => 'branch',
                'type' => 'text',
                'sacrifice' => true,
            ],
        ];
    }
}
