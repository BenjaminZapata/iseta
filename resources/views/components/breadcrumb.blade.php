<nav aria-label="breadcrumb" class="mb-4">
    <ul class="breadcrumb flex items-center gap-2 text-sm text-gray-700">
        @foreach ($breadcrumbs as $index => $breadcrumb)
        <li class="flex items-center">
            @if ($index !== count($breadcrumbs) - 1)
            <a href="{{ $breadcrumb['url'] }}" class="text-blue-600 hover:underline font-medium">
                {{ $breadcrumb['label'] }}
            </a>
            <span class="mx-2 text-gray-400">/</span>
            @else
            <span class="text-gray-900 font-semibold">{{ $breadcrumb['label'] }}</span>
            @endif
        </li>
        @endforeach
    </ul>
</nav>