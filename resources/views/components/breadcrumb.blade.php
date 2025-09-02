<nav class="breadcrumb">
    @foreach ($items as $item)
    @if ($item['url'])
    <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
    @else
    <span>{{ $item['label'] }}</span>
    @endif
    @endforeach
</nav>