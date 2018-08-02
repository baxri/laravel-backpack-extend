<button id="routeButton{{$entry->getKey() . $button->name }}" type="button" class="btn btn-xs btn-{{ $button->style }}"
        data-toggle="modal" data-target="#openModal{{$entry->getKey() . $button->name }}">
    {{ $button->name }}
</button>

<div class="modal fade" id="openModal{{$entry->getKey() . $button->name }}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$button->popup_title}}</h5>
            </div>
            <div class="modal-body">
                {{$button->popup_description}}
                @if($button->comment)
                    <label for="routeButtonComment{{$entry->getKey() . $button->name }}" class="col-form-label">Message:</label>
                    <textarea class="form-control" id="routeButtonComment{{$entry->getKey() . $button->name }}"></textarea>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"
                        onclick="make_request_to_route_button('{{$button->route}}','{{$entry->getKey()}}', '{{$button->name}}')"
                        data-dismiss="modal">Yes
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function make_request_to_route_button(url, key, button) {

        $("#routeButton" + key + button).text("Wait");
        $("#routeButton" + key + button).attr("disabled", "disabled");

        var comment = $("#routeButtonComment" + key + button).val();
        console.log(comment);
        $.ajax({
            type: "POST",
            url: url,
            data: {
                key: key,
                comment: comment,
            },
            success: function (data) {

                new PNotify({
                    title: "Success",
                    text: "Operation Finished Successfully",
                    type: "success"
                });

                var ajax_table = $("#crudTable").DataTable();
                var current_url = ajax_table.ajax.url();
                ajax_table.ajax.url(current_url).load();

                $("#routeButton" + key + button).text(button);
                $("#routeButton" + key + button).removeAttr("disabled");
            },
            error: function(result) {
                // Show an alert with the result
                new PNotify({
                    title: "Error",
                    text: "Operation is not Finished",
                    type: "warning"
                });

                $("#routeButton" + key + button).text(button);
                $("#routeButton" + key + button).removeAttr("disabled");
            }
        });
    }
</script>