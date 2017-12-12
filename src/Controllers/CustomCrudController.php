<?php

namespace Unipay\CustomCrud\Controllers;

use Unipay\CustomCrud\Traits\AjaxTable;
use Unipay\CustomCrud\Traits\Columns;
use Unipay\CustomCrud\MyCrudPanel;
use App\Http\Requests\Request;
use Exception;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class CustomCrudController extends CrudController
{
    use AjaxTable,Columns;

    public function __construct()
    {
        parent::__construct();
        $this->crud = app()->make(MyCrudPanel::class);
    }

    public $orderBy = '1';
    public $orderDir = 'desc';
    public $disableSorts = NULL;
    public $listview = 'ccrud::list';


    public function index()
    {
        $this->crud->setDefaultPageLength(25);
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = ucfirst($this->crud->entity_name_plural);
        $this->data['orderBy'] = $this->orderBy;
        $this->data['orderDir'] = $this->orderDir;
        $this->data['disableSorts'] = $this->disableSorts;

        return view($this->listview, $this->data);
    }

}
