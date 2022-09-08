<div class="card w-100 banner">
    @forelse (preg_split("/\\r\\n|\\r|\\n/", config('global.SITE_DASHBOARD_SLIDER')) as $item)
        <div class="card-body d-table" style="
            background-image:url('{{ $item }}');
            background-size:cover;
            background-position:center;
            margin-right: -1px;
            height: 100%;">
            @if ($announcement != '')
                <h4 class="bg-yellow p-3 rounded text-center">
                    <i class="nav-icon material-icons icon--list mr-2 text-danger">announcement</i>
                    Announcement
                </h4>
                <div class="p-3 rounded bg-white" style="
                    max-height: 300px;
                    overflow-y: scroll;
                ">
                    {!! $announcement !!}
                </div>
            @endif
        </div>
    @empty
        
    @endforelse
</div>

@section('script')
    <script>
        $(function() {
            $('.banner').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                speed: 3000,
                autoplay: true,
                lazyLoad: 'ondemand',
                arrows: false,
            });
        })
    </script>
@endsection