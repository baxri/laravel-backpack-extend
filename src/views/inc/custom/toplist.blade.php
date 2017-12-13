<ul class="nav nav-tabs">
    @foreach($crud->topTabsList as $element)

        @if(!empty($element['list']))
            <li role="presentation" class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="{{$element['route']}}" role="button"
                   aria-haspopup="true"
                   aria-expanded="false">
                    {{$element['label']}} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach($element['list'] as $list)

                        <li role="presentation"
                            class="<?php echo ($crud->getRoute() . "/" . $element['route'] === Request::path()) ? "active" : "" ?>">
                            <a href="{{$list['route']}}" role="button">
                                {{$list['label']}}
                            </a>
                        </li>

                    @endforeach
                </ul>
            </li>
        @else
            <li role="presentation"
                class="<?php echo ($crud->getRoute() . "/" . $element['route'] === Request::path()) ? "active" : "" ?>">
                <a href="{{$element['route']}}" role="button">
                    {{$element['label']}}
                </a>
            </li>
        @endif

    @endforeach
</ul>