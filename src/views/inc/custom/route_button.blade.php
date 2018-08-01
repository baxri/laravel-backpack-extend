<button type="button" class="btn btn-secondary">Hello</button>
<script type="text/javascript">
    function make_request_to_get_total_info( url ){
        $.ajax({
            type: "POST",
            url: url,
            data: {},
            success: function(data){
                var panel = $("#totals-panel");
                panel.html("");
                $.each( data, function( key, value ) {
                    panel.append("<p style='color: gray; font-weight: bold; font-size: 15px; padding-top: 8px; float: left; margin-right: 10px;'>" + value.label + ": " + value.value + "</p>");
                });
            }
        });
    }
</script>