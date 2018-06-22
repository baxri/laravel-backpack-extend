<?php

namespace Unipay\CustomCrud\Controllers;

use App\Http\Requests\Request;
use App\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Unipay\CustomCrud\Traits\AjaxTable;
use Unipay\CustomCrud\Traits\Columns;
use Unipay\CustomCrud\MyCrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class CustomCrudController extends CrudController
{
    use AjaxTable, Columns;

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

    public function export(Request $request)
    {
        $table_name = $this->crud->model->getTable();

        $filename = $table_name.'.csv';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $result = $this->crud->query->getQuery()->orderBy('id');
            $result->chunk(500, function ($users) use ($handle) {
                foreach ($users as $user) {
                    fputcsv($handle, (array)$user);
                }
            });
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$filename,
        ]);
        return $response;
    }

}
