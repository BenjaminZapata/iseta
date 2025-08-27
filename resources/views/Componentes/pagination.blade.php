<link rel="stylesheet" href="{{asset('css/Pagination/pagination.css')}}">

@if ($paginator->hasPages())

<ul class="pager">
    @if ($paginator->onFirstPage())
    <li class="disabled btn1"><span>Anterior</span></li>
    @else
    <li class="btn1"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a></li>
    @endif
    @foreach ($elements as $element)
    @if (is_string($element))
    <li class="disabled no-active"><span>{{ $element }}</span></li>
    @endif
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <li class="active my-active"><span>{{ $page }}</span></li>
    @else
    <a href="{{ $url }}">
        <li class="no-active">{{ $page }}</li>
    </a>
    @endif
    @endforeach
    @endif
    @endforeach
    @if ($paginator->hasMorePages())

    <a href="{{ $paginator->nextPageUrl() }}" rel="next">
        <li class="btn2">
            Siguiente
        </li>
    </a>
    @else
    <li class="disabled btn2"><span>Siguiente</span></li>
    @endif
</ul>

@endif