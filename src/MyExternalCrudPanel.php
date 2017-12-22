<?php

namespace Unipay\CustomCrud;


use Backpack\CRUD\CrudPanel;
use Exception;
use Unipay\CustomCrud\Traits\Columns;
use Unipay\CustomCrud\Traits\Filters;
use Unipay\CustomCrud\Traits\Query;

class MyExternalCrudPanel extends  CrudPanel
{
    use Columns,Filters,Query;

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

    public function addCount( $label = null ){

        if($label == null){
            $label = ucfirst($this->entity_name_plural);
        }

        $this->addTotal([
            'label' => $label,
            'aggregate' => 'count',
        ]);
    }

    public function addSum( $field, $label = null ){

        if($label == null){
            $label = ucfirst($field);
        }

        $this->addTotal([
            'label' => $label,
            'aggregate' => 'sum',
            'name' => $field
//            'type' => 'model_function',
//            'function_name' => 'getAmountTotalView',
        ]);
    }

    public function setTopTabs($src = null){

        $tabs = include resource_path() . '/tabs/' . $src . '.php';

        if(!is_array($tabs)){
            throw new Exception('Please add menu array');
        }

        $this->topTabsList = $tabs;
    }

}
