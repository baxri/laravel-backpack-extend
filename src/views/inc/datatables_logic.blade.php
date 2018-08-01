  <!-- DATA TABLES SCRIPT -->

  <script src="{{asset('vendor/adminlte/plugins/datatables/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
  <script src="//cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
  <script src="//cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap.min.js"></script>

  <script>
    var crud = {
      exportButtons: JSON.parse('{!! json_encode($crud->export_buttons) !!}'),
      functionsToRunOnDataTablesDrawEvent: [],
      addFunctionToDataTablesDrawEventQueue: function (functionName) {
          if (this.functionsToRunOnDataTablesDrawEvent.indexOf(functionName) == -1) {
          this.functionsToRunOnDataTablesDrawEvent.push(functionName);
        }
      },
      responsiveToggle: function(dt) {
          $(dt.table().header()).find('th').toggleClass('all');
          dt.responsive.rebuild();
          dt.responsive.recalc();
      },
      executeFunctionByName: function(str, args) {
        var arr = str.split('.');
        var fn = window[ arr[0] ];

        for (var i = 1; i < arr.length; i++)
        { fn = fn[ arr[i] ]; }
        fn.apply(window, args);
      },
      dataTableConfiguration: {

      @if($disableSorts)
      "aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ {{$disableSorts}} ] }
      ],
      @endif
      "order" : [{{$orderBy}},"{{$orderDir}}"],
        responsive: false,
        scrollX: true,
        autoWidth: false,
        pageLength: {{ $crud->getDefaultPageLength() }},
        lengthMenu: @json($crud->getPageLengthMenu()),
        /* Disable initial sort */
        aaSorting: [],
        language: {
              "emptyTable":     "{{ trans('backpack::crud.emptyTable') }}",
              "info":           "{{ trans('backpack::crud.info') }}",
              "infoEmpty":      "{{ trans('backpack::crud.infoEmpty') }}",
              "infoFiltered":   "{{ trans('backpack::crud.infoFiltered') }}",
              "infoPostFix":    "{{ trans('backpack::crud.infoPostFix') }}",
              "thousands":      "{{ trans('backpack::crud.thousands') }}",
              "lengthMenu":     "{{ trans('backpack::crud.lengthMenu') }}",
              "loadingRecords": "{{ trans('backpack::crud.loadingRecords') }}",
              "processing":     "<img src='{{ asset('vendor/backpack/crud/img/ajax-loader.gif') }}' alt='{{ trans('backpack::crud.processing') }}'>",
              "search":         "{{ trans('backpack::crud.search') }}",
              "zeroRecords":    "{{ trans('backpack::crud.zeroRecords') }}",
              "paginate": {
                  "first":      "{{ trans('backpack::crud.paginate.first') }}",
                  "last":       "{{ trans('backpack::crud.paginate.last') }}",
                  "next":       "<span class='hidden-xs hidden-sm'>{{ trans('backpack::crud.paginate.next') }}</span><span class='hidden-md hidden-lg'>></span>",
                  "previous":   "<span class='hidden-xs hidden-sm'>{{ trans('backpack::crud.paginate.previous') }}</span><span class='hidden-md hidden-lg'><</span>"
              },
              "aria": {
                  "sortAscending":  "{{ trans('backpack::crud.aria.sortAscending') }}",
                  "sortDescending": "{{ trans('backpack::crud.aria.sortDescending') }}"
              },
              "buttons": {
                  "copy":   "{{ trans('backpack::crud.export.copy') }}",
                  "excel":  "{{ trans('backpack::crud.export.excel') }}",
                  "csv":    "{{ trans('backpack::crud.export.csv') }}",
                  "pdf":    "{{ trans('backpack::crud.export.pdf') }}",
                  "print":  "{{ trans('backpack::crud.export.print') }}",
                  "colvis": "{{ trans('backpack::crud.export.column_visibility') }}"
              },
          },
          processing: true,
          serverSide: true,
          ajax: {
              "url": "{!! url($crud->route.'/search').'?'.Request::getQueryString() !!}",
              "type": "POST"
          },
          dom:
            "<'row'<'col-sm-6 hidden-xs'l><'col-sm-6 hidden-print'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-2'B><'col-sm-5 hidden-print'p>>",
      }
  }
  </script>

  @include('crud::inc.export_buttons')

  <script type="text/javascript">
    jQuery(document).ready(function($) {

      crud.table = $("#crudTable").DataTable(crud.dataTableConfiguration);

      // override ajax error message
      $.fn.dataTable.ext.errMode = 'none';
      $('#crudTable').on('error.dt', function(e, settings, techNote, message) {
          new PNotify({
              type: "error",
              title: "{{ trans('backpack::crud.ajax_error_title') }}",
              text: "{{ trans('backpack::crud.ajax_error_text') }}"
          });
      });

      // make sure AJAX requests include XSRF token
      $.ajaxPrefilter(function(options, originalOptions, xhr) {
          var token = $('meta[name="csrf_token"]').attr('content');

          if (token) {
                return xhr.setRequestHeader('X-XSRF-TOKEN', token);
          }
      });

      // on DataTable draw event run all functions in the queue
      // (eg. delete and details_row buttons add functions to this queue)
      $('#crudTable').on( 'draw.dt',   function () {
         crud.functionsToRunOnDataTablesDrawEvent.forEach(function(functionName) {
            crud.executeFunctionByName(functionName);
         });
      } ).dataTable();

      // when datatables-colvis (column visibility) is toggled
      // rebuild the datatable using the datatable-responsive plugin
      $('#crudTable').on( 'column-visibility.dt',   function (event) {
         crud.table.responsive.rebuild();
      } ).dataTable();

      // when columns are hidden by reponsive plugin,
      // the table should have the has-hidden-columns class
      crud.table.on( 'responsive-resize', function ( e, datatable, columns ) {
          if (crud.table.responsive.hasHidden()) {
            $("#crudTable").removeClass('has-hidden-columns').addClass('has-hidden-columns');
           } else {
            $("#crudTable").removeClass('has-hidden-columns');
           }
      } );

    });
  </script>

  @include('crud::inc.details_row_logic')