<button id="routeButton{{$entry->getKey() . $button->name }}" type="button" class="btn btn-xs btn-{{ $button->style }}" data-toggle="modal" data-target="#openModal{{$entry->getKey() . $button->name }}">
    {{ $button->name }}
</button>

<div class="modal fade" id="openModal{{$entry->getKey() . $button->name }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$button->popup_title}}</h5>
            </div>
            <div class="modal-body">
                {{$button->popup_description}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="make_request_to_route_button('{{$entry->getKey()}}', '{{$button->name}}')" data-dismiss="modal">Yes</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function make_request_to_route_button(key, button){

        var url = '{{$button->route}}';
        $("#routeButton" + key + button).text("Wait");
        $("#routeButton" + key + button).attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: url,
            data: {
                key: key
            },
            success: function(data){
                console.log(key);
                console.log(button);
                console.log(data);
                $("#routeButton" + key + button).text(button);
                $("#routeButton" + key + button).removeAttr("disabled");
            }
        });
    }
</script>