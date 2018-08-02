<?php

namespace Unipay\CustomCrud;


use Backpack\CRUD\CrudPanel;
use Exception;
use Unipay\CustomCrud\Traits\Columns;
use Unipay\CustomCrud\Traits\Filters;
use Unipay\CustomCrud\Traits\Query;
use Unipay\CustomCrud\Traits\Buttons;

class MyCrudPanel extends CrudPanel
{
//    use Columns,Filters, Buttons;
    use Filters;

    // Total information
    public $totals = [];

    public function enableServerSideExport($entity = '')
    {
        $this->addFilter([
            'type' => 'export',
            'name' => 'exel_export',
            'label' => 'Exel Export',
            'entity' => $entity,
        ]);
    }

    public function getRelationModel($relationString, $length = null, $model = null)
    {
        $result = array_reduce(explode('.', $relationString), function ($obj, $method) {
            return $obj->$method()->getRelated();
        }, $this->model);

        return get_class($result);
    }

    public function addTotal($field)
    {
        $this->totals[] = $field;
    }

    public function getTotals()
    {
        return $this->totals;
    }

    public function addCount($label = null)
    {

        if ($label == null) {
            $label = ucfirst($this->entity_name_plural);
        }

        $this->addTotal([
            'label' => $label,
            'aggregate' => 'count',
        ]);
    }

    public function addSum($field, $label = null)
    {

        if ($label == null) {
            $label = ucfirst($field);
        }

        $this->addTotal([
            'label' => $label,
            'aggregate' => 'sum',
            'name' => $field,
        ]);
    }

    public function addSumMoney($field, $label = null, $currency = 'GEL')
    {
        if ($label == null) {
            $label = ucfirst($field);
        }

        $this->addTotal([
            'label' => $label,
            'aggregate' => 'sum',
            'name' => $field,
            'type' => 'model_function',
            'function' => function($val) use($currency){
                return number_format($val, 2, '.', '').' '.$currency;
            }
        ]);
    }

    public function setTopTabs($src = null)
    {

        $tabs = include resource_path() . '/tabs/' . $src . '.php';

        if (!is_array($tabs)) {
            throw new Exception('Please add menu array');
        }

        $this->topTabsList = $tabs;
    }

}
