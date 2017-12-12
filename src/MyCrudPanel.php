<?php

namespace Unipay\CustomCrud;


use Backpack\CRUD\CrudPanel;
use Unipay\CustomCrud\Traits\Columns;
use Unipay\CustomCrud\Traits\Filters;

class MyCrudPanel extends  CrudPanel
{
    use Columns,Filters;

    // Total information
    public $totals = [];

    public function enableServerSideExport()
    {
        $this->addFilter([
            'type' => 'export',
            'name' => 'exel_export',
            'label'=> 'Exel Export'
        ]);
    }

    private function getRelationModel($relationString)
    {
        $result = array_reduce(explode('.', $relationString), function ($obj, $method) {
            return $obj->$method()->getRelated();
        }, $this->model);

        return get_class($result);
    }

    public function addTotal( $field ){
        $this->totals[] = $field;
    }

    public function getTotals(){
        return $this->totals;
    }

    public function addCount(){
        $this->addTotal([
            'aggregate' => 'count',
            'label' => ucfirst($this->entity_name_plural).' count',
        ]);
    }

    public function addSum( $field ){
        $this->addTotal([
            'aggregate' => 'sum',
            'label' => ucfirst($field),
            'name' => $field
//            'type' => 'model_function',
//            'function_name' => 'getAmountTotalView',
        ]);
    }

}
