<?php

namespace Unipay\CustomCrud\Controllers;

use App\helpers\Number;
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

                if($column['type'] == 'btc'){
                    $text = number_format($r[$column['name']],8);
                }elseif($column['type'] == 'datetime'){
                    $length = $column['typelength'];
                    $datetime = substr($r[$column['name']],0,$length);
                    $text = date("Y-M-d H:i:s",(int)$datetime);
                }else{
                    $text = $r[$column['name']];
                }
                $response['data'][$k][] = [
                    "<td>" . $text . "</td>",
                ];
            }
        }

        if($this->items){
            return response()->json(array_merge($this->items,$response));
        }

        return response()->json($response);
    }
}
