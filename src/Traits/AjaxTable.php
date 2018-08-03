<?php

namespace Unipay\CustomCrud\Traits;

use App\Backpack\DataTable;
use Maatwebsite\Excel\Facades\Excel;
use App\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait AjaxTable
{
    public function search()
    {
        $request_type = isset($_GET['request_type']) ? $_GET['request_type'] : 'list';

        if ($request_type == 'total') {

            $totals = $this->crud->getTotals();
            $table_name = $this->crud->model->getTable();

            if (!isset($this->crud->totalQuery)) {
                $this->crud->totalQuery = $this->crud->query;
            }

            foreach ($totals as $key => $total) {
                if (isset($total['aggregate']) && $total['aggregate'] == 'sum') {
                    $value = $this->crud->totalQuery->sum($table_name . '.' . $total['name']);
                } else {
                    $value = $this->crud->totalQuery->count();
                }

                if (isset($total['function'])) {
                    $function = $total['function'];
                    //$value = $this->crud->model->$function($value);
                    $value = $total['function']($value);
                    $totals[$key]['value'] = $value;
                } else {
                    $totals[$key]['value'] = $value;
                }
            }

            return response()->json($totals);
        }

        $this->crud->hasAccessOrFail('list');

        $totalRows = $filteredRows = $this->crud->count();

        // if a search term was present
        if ($this->request->input('search') && $this->request->input('search')['value']) {
            // filter the results accordingly
            $this->crud->applySearchTerm($this->request->input('search')['value']);
            // recalculate the number of filtered rows
            $filteredRows = $this->crud->count();
        }

        // start the results according to the datatables pagination
        if ($this->request->input('start')) {
            $this->crud->skip($this->request->input('start'));
        }

        // limit the number of results according to the datatables pagination
        if ($this->request->input('length')) {
            $this->crud->take($this->request->input('length'));
        }

        // overwrite any order set in the setup() method with the datatables order
        if ($this->request->input('order')) {
            $column_number = $this->request->input('order')[0]['column'];
            if ($this->crud->details_row) {
                $column_number = $column_number - 1;
            }
            $column_direction = $this->request->input('order')[0]['dir'];
            $column = $this->crud->findColumnById($column_number);

            if ($column['tableColumn']) {
                $this->crud->orderBy($column['name'], $column_direction);
            }
        }


        $entries = $this->crud->getEntries();

        return $this->crud->getEntriesAsJsonForDatatables($entries, $totalRows, $filteredRows);
    }
}
