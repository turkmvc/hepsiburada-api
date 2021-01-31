<?php
namespace turkmvc\Hepsiburada;

use Illuminate\Support\Facades\Facade;

class Ups extends Facade{

    protected static function getFacadeAccessor() { 
      
        return 'googlecategory';

    }

}
