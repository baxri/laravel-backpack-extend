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

                        <?php
                        $route = explode('/', $element['route']);
                        $path = explode('/', Request::path());
                        ?>

                        <li role="presentation"
                            class="<?php echo ( $path[count($path)-1] == $route[count($route)-1] ) ? "active" : "" ?>">
                            <a href="{{url(config('backpack.base.route_prefix') . "/" . $element['route'])}}" role="button">
                                {{$list['label']}}
                            </a>
                        </li>

                    @endforeach
                </ul>
            </li>
        @else

            <?php
                $route = explode('/', $element['route']);
                $path = explode('/', Request::path());
            ?>

            <li role="presentation"
                class="<?php echo ( $path[count($path)-1] == $route[count($route)-1] ) ? "active" : "" ?>">
                <a href="{{url(config('backpack.base.route_prefix') . "/" . $element['route'])}}" role="button">
                    {{$element['label']}}
                </a>
            </li>
        @endif

    @endforeach
</ul>