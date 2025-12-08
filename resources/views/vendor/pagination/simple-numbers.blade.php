<style>
.pagination {
    display: flex;
    gap: 14px;
    justify-content: center;
    align-items: center;
    font-size: 22px;
    margin-top: 10px;
}

.pagination a,
.pagination span {
    color: #b0b0b0; /* light gray */
    font-weight: 400;
    text-decoration: none;
    padding: 0 4px;
}

.pagination a:hover {
    color: #77A169; /* green hover */
}

.pagination .active {
    color: #77A169 !important; /* active page */
    font-weight: 700;
    text-decoration: underline;
}

.pagination .separator {
    color: #cfcfcf;
    pointer-events: none;
}
</style>

@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">

            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span>‹</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}">‹</a>
            @endif


            {{-- Page Numbers --}}
            @foreach ($elements as $element)

                @if (is_string($element))
                    <span class="separator">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif

            @endforeach


            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}">›</a>
            @else
                <span>›</span>
            @endif

        </ul>
    </nav>
@endif
