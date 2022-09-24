<ul class="sidebar-nav" style="text-align:left">
    <li class="sidebar-header">
        Menu Principal
    </li>

    @foreach($menu as $itemmenu)
        @php
            $temFilhos = false;
            $activeMenu = false;
            foreach ($menu as $item) {
                if ($item["menu_id"] == $itemmenu["id"]) {
                    $temFilhos = true;
                }
                if($itemmenu['id'] == $item['menu_id'] && Str::startsWith(Route::current()->uri(),$item['url'])){
                    $activeMenu = true;
                }
            }
        @endphp

        <li class="sidebar-item @if($activeMenu) active @endif">

            @if($temFilhos)
                <a data-bs-target="#menu<?php echo $itemmenu["id"]; ?>" data-bs-toggle="collapse" class="sidebar-link @if(!$activeMenu) collapsed @endif">
                    <i class="align-middle me-2 fas <?php echo $itemmenu["icon"]; ?>"></i> <span class="align-middle"><?php echo $itemmenu["nome"]; ?></span>
                </a>

                <ul id="menu<?php echo $itemmenu["id"]; ?>" class="sidebar-dropdown list-unstyled collapse @if($activeMenu) show @endif" data-bs-parent="#sidebar">
                    @foreach($menu as $itemsubmenu)
                        @if($itemsubmenu['menu_id'] == $itemmenu['id'])
                            <li class="sidebar-item @if($activeMenu) active @endif">
                                <a class="sidebar-link" href="{{ env('APP_URL') }}/<?php echo $itemsubmenu["url"]; ?>"><?php echo $itemsubmenu["nome"]; ?></a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @else
                @if(empty($itemmenu['menu_id']))
                    <li class="sidebar-item">
                        <a class="sidebar-link @if($activeMenu) active @endif" href="{{ env('APP_URL') }}/<?php echo $itemmenu["url"]; ?>"><i class="align-middle me-2 fas fa-fw <?php echo $itemmenu["icon"]; ?>"></i><?php echo $itemmenu["nome"]; ?></a>
                    </li>
                @endif
            @endif
        </li>
    @endforeach

</ul>
