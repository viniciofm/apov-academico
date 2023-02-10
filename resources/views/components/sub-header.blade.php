<div class="container-fluid">
    <div class="header">
        <h1 class="header-title">
            {{ $headerTitle ?? (`Gerenciamento de $module`) }}
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">In&iacute;cio</a></li>
                @foreach($links as $link)
                    <li class="breadcrumb-item">
                        @if($link[0])
                            <a href="{{ $link[0] }}">
                                <span class="m-nav__link-text">{{ $link[1] }}</span>
                            </a>
                        @else
                            <span class="breadcrumb-item active">{{ $link[1] }}</span>
                        @endif
                    </li>
                @endforeach
                <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
            </ol>
        </nav>
    </div>
</div>
