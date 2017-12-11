<?php

namespace Unipay\CustomCrud;


use Backpack\CRUD\CrudPanel;
use Unipay\CustomCrud\Traits\Columns;
use Unipay\CustomCrud\Traits\Filters;

class MyCrudPanel extends  CrudPanel
{
    use Columns,Filters;

    private function getRelationModel($relationString)
    {
        $result = array_reduce(explode('.', $relationString), function ($obj, $method) {
            return $obj->$method()->getRelated();
        }, $this->model);

        return get_class($result);
    }

}
