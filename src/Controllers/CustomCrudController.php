<?php

namespace Unipay\CustomCrud\Controllers;

use App\Http\Requests\Request;
use App\Order;
use Backpack\CRUD\CrudPanel;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Unipay\CustomCrud\Traits\AjaxTable;
use Unipay\CustomCrud\Traits\Columns;
use Unipay\CustomCrud\Traits\Buttons;
use Unipay\CustomCrud\MyCrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Backpack\CRUD\app\Http\Controllers\Operations\Create;
use Backpack\CRUD\app\Http\Controllers\Operations\Delete;
use Backpack\CRUD\app\Http\Controllers\Operations\Update;
use Backpack\CRUD\app\Http\Controllers\Operations\Reorder;
use Backpack\CRUD\app\Http\Controllers\Operations\Revisions;
use Backpack\CRUD\app\Http\Controllers\Operations\SaveActions;
use Backpack\CRUD\app\Http\Controllers\Operations\Show;

class CustomCrudController extends CrudController
{
    use DispatchesJobs, ValidatesRequests;
    use Create, Delete, Reorder, Revisions, SaveActions, Show, Update;

    public $data = [];
    public $request;
    public $orderBy = '1';
    public $orderDir = 'desc';
    public $disableSorts = NULL;
    public $listview = 'ccrud::list';
    /**
     * @var CrudPanel
     */
    public $crud;

    public function __construct()
    {
        if (! $this->crud) {
            $this->crud = app()->make(CrudPanel::class);

            // call the setup function inside this closure to also have the request there
            // this way, developers can use things stored in session (auth variables, etc)
            $this->middleware(function ($request, $next) {
                $this->request = $request;
                $this->crud->request = $request;
                $this->setup();

                return $next($request);
            });
        }
    }

    /**
     * Allow developers to set their configuration options for a CrudPanel.
     */
    public function setup()
    {
    }

    /**
     * Display all rows in the database for this entity.
     *
     * @return Response
     */
    public function index()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = ucfirst($this->crud->entity_name_plural);
        $this->data['orderBy'] = $this->orderBy;
        $this->data['orderDir'] = $this->orderDir;
        $this->data['disableSorts'] = $this->disableSorts;
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->listview, $this->data);
    }

    /**
     * The search function that is called by the data table.
     *
     * @return  JSON Array of cells in HTML form.
     */
    public function search()
    {
        $this->crud->hasAccessOrFail('list');

        $totalRows = $filteredRows = $this->crud->count();
        $startIndex = $this->request->input('start') ?: 0;
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
            $column_direction = $this->request->input('order')[0]['dir'];
            $column = $this->crud->findColumnById($column_number);
            if ($column['tableColumn']) {
                // clear any past orderBy rules
                $this->crud->query->getQuery()->orders = null;
                // apply the current orderBy rules
                $this->crud->orderBy($column['name'], $column_direction);
            }
        }
        $entries = $this->crud->getEntries();

        return $this->crud->getEntriesAsJsonForDatatables($entries, $totalRows, $filteredRows, $startIndex);
    }

    /**
     * Used with AJAX in the list view (datatables) to show extra information about that row that didn't fit in the table.
     * It defaults to showing some dummy text.
     *
     * It's enabled by:
     * - setting: $crud->details_row = true;
     * - adding the details route for the entity; ex: Route::get('page/{id}/details', 'PageCrudController@showDetailsRow');
     * - adding a view with the following name to change what the row actually contains: app/resources/views/vendor/backpack/crud/details_row.blade.php
     */
    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getDetailsRowView(), $this->data);
    }

}
