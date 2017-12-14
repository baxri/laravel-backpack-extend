<?php

namespace Unipay\CustomCrud\Controllers;

use App\Gateway\SendWyre;
use App\Http\Requests\Request;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Input;
use Unipay\CustomCrud\MyCrudPanel;

class ExternalCustomCrudController extends CrudController
{
    public $items = NULL;
    public $array = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->crud = app()->make(MyCrudPanel::class);
    }

    public function search()
    {
        $response = [];
        $request = $this->array;
        foreach ($request as $k => $r) {
            foreach ($this->crud->getColumns() as $column) {
                $response['data'][$k][] = [
                    "<td>" . \GuzzleHttp\json_encode($r[$column['name']]) . "</td>",
                ];
            }
        }
        if($this->items){
            return response()->json(array_merge($this->items,$response));
        }
        return response()->json($response);
    }
}
