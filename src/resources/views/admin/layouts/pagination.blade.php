@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            {{-- Nút Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">← Trước</span></li>
            @else
                <li class="page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link">← Trước</a>
                </li>
            @endif

            {{-- Các số trang --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a href="{{ $url }}" class="page-link">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Nút Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-link">Sau →</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">Sau →</span></li>
            @endif
        </ul>
    </nav>
@endif
