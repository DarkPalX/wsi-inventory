@php
    $prev = $paginator->currentPage() - 1;
    $next = $paginator->currentPage() + 1;
    $totalPages = $paginator->lastPage();
@endphp

<nav aria-label="Page navigation">
    <ul class="pagination">
        {{-- First Page Button --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">First</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url(1) }}">First</a>
            </li>
        @endif

        {{-- Previous Page Button --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">&laquo; Previous</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($prev) }}">&laquo; Previous</a>
            </li>
        @endif

        {{-- Page Number Buttons --}}
        @foreach ($elements[0] as $page => $url)
            <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
            </li>
        @endforeach

        {{-- Next Page Button --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($next) }}">Next &raquo;</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next &raquo;</span>
            </li>
        @endif

        {{-- Last Page Button --}}
        @if ($paginator->currentPage() == $totalPages)
            <li class="page-item disabled">
                <span class="page-link">Last</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($totalPages) }}">Last</a>
            </li>
        @endif
    </ul>
</nav>
