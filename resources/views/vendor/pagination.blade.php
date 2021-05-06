<div class="card-footer clearfix">
    <ul class="pagination pagination-sm m-0 " style="justify-content: center;">
        @if (!$paginator->onFirstPage())

            <li class="page-item">
                <a href="{{ $paginator->previousPageUrl() }}" class="page-link">&lsaquo;</a>
            </li>

        @endif
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
            @endif
            @if (is_array($element) && count($element)>1)
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <a class="page-link" rel="nofollow" href="javascript:void(0)">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif


        @endforeach

        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                    &rsaquo;
                </a>
            </li>
        @endif
    </ul>
</div>
