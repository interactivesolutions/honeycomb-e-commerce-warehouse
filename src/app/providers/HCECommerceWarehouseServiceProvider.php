<?php

namespace interactivesolutions\honeycombecommercewarehouse\app\providers;

use interactivesolutions\honeycombcore\providers\HCBaseServiceProvider;

class HCECommerceWarehouseServiceProvider extends HCBaseServiceProvider
{
    protected $homeDirectory = __DIR__;

    protected $commands = [];

    protected $namespace = 'interactivesolutions\honeycombecommercewarehouse\app\http\controllers';

    public $serviceProviderNameSpace = 'HCECommerceWarehouse';
}





