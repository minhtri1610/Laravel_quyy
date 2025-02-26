@if(isset($breadcrumbs))
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
        <div class="breadcrumbs-area clearfix">
            <ul class="breadcrumbs pull-left">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if ($breadcrumb['url'])
                        <li><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a></li>
                    @else
                        <li><span>{{ $breadcrumb['title'] }}</span></li>
                    @endif
                @endforeach
            </ul>
        </div>
        </div>
        <div class="col-sm-6 clearfix">
        </div>
    </div>
</div>
@endif