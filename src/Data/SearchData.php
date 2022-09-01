<?php
namespace App\Data;

class SearchData
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * q
     *
     * @var string
     */
    public $q = '';

    /**
     * categories
     *
     * @var array
     */
    public $categories = [];

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var boolean
     */
    public $promo = false;
}
