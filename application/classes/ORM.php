<?php defined('SYSPATH') OR die();

class ORM extends Kohana_ORM
{
    
    public function find_rand()
    {
        $this->offset(rand(1, $this->reset(false)->count_all()));
        return $this->find();
    }
    
}
