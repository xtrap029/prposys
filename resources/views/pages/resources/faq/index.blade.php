@extends('layouts.app-resources')

@section('title', 'FAQs')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>FAQs</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">  
            <form class="col-12 mt-3 mt-md-0 mb-3">
                <div class="input-group">
                    <input type="text" class="form-control filterSearch_input" name="s" autocomplete="off" placeholder="keyword here..." value="{{ isset($_GET['s']) ? $_GET['s'] : '' }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                    <div class="input-group-append">
                        <a href="/faqs" class="btn btn-danger" type="submit">Reset</a>
                    </div>
                </div>
                <div class="d-none d-md-block position-relative">
                    <div class="card card-search bg-secondary font-weight-normal filterSearch_result" style="display: none">
                        <div class="card-body">
                            <button type="button" class="btn btn-tool float-right filterSearch_close">close</button>
                            <div class="filterSearch_data row small w-100" style="display: none">
                                <table class="table mb-0 p-0"></table>
                            </div>
                            <img src="/images/loading.gif" class="filterSearch_loading thumb--sm m-auto" style="display: block">
                        </div>
                    </div>
                </div>
                <div class="table-modal"></div>
                @if (isset($_GET['s']) && $_GET['s'] != '')
                    <div class="text-center">
                        search result for <code>{{ $_GET['s'] }}</code>
                    </div>
                @endif
            </form>
            <div id="accordion">
                @foreach ($categories as $item)
                    @if (count($item->faqs) > 0)
                        <div class="bg-transparent border-bottom-0 border-left-0 border-right-0 card mb-0 rounded-0">
                            <div class="card-header p-0 border-bottom-0" id="heading{{ $item->random }}">
                                <h5 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-dark text-decoration-none collapsed px-4 py-3 font-weight-bold outline-0" data-toggle="collapse" data-target="#collapse{{ $item->random }}" aria-expanded="true" aria-controls="collapse{{ $item->random }}">
                                        {{ $item->category }}
                                    </button>
                                </h5>
                            </div>
                    
                            <div id="collapse{{ $item->random }}" class="collapse show" aria-labelledby="heading{{ $item->random }}" data-parent="#accordion">
                                <div class="card-body">
                                    <ol>
                                        @foreach ($item->faqs as $faq)
                                            <li>
                                                <h6>
                                                    <a href="#_" data-toggle="modal" data-target="#modal-{{ $faq->id }}">{{ $faq->title }}</a>
                                                </h6>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @foreach ($categories as $item)
                @if (count($item->faqs) > 0)
                    @foreach ($item->faqs as $faq)
                        <div class="modal fade" id="modal-{{ $faq->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">{{ $faq->title }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        {!! $faq->description !!}
                                    </div>
                                    <div class="modal-footer border-0">
                                        <i class="small">Last Updated: {{ $faq->updated_at }}</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            $('.collapse').collapse()

            if ($('.filterSearch_input')[0]){
                cls = '.filterSearch'

                $(cls+'_input').keyup(delay(function() {
                    apiSearch()
                }, 300))

                $(cls+'_close').click(function() {
                    $(cls+'_result').fadeOut('fast')
                })
            }

            function apiSearch() {
                if ($(cls+'_input').val() != ''                                
                    || $('[name=s]').val() != '') {

                    $(cls+'_data').hide()
                    $(cls+'_loading').show()
                    $(cls+'_result').fadeIn('slow')
                    
                    $.ajax({
                        url: '/faqs/api-search',
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'id': '{{ Auth::user()->id }}',
                            's': $('[name=s]').val(),
                        },
                        success: function(res) {
                            result = JSON.parse(res)

                            $(cls+'_loading').hide()
                            $(cls+'_data table').html('')
                            $(cls+'_data').fadeIn('slow')
                            
                            if (result.length > 0) {
                                
                                $.each(result, function(i, item) {
                                    config_confidential = item.is_confidential

                                    $(cls+'_data table').append('<tr class="bg-transparent border-0">'
                                        + '<td class="border-0 py-1"><a href="#_" class="text-white font-weight-bold" data-toggle="modal" data-target="#table-modal-'+item.id+'">'+item.title+'</td>'
                                        + '</tr>'
                                    )

                                    $('.table-modal').append('<div class="modal fade" id="table-modal-'+item.id+'" tabindex="-1" role="dialog" aria-hidden="true">'
                                        + '<div class="modal-dialog modal-md" role="document">'
                                            + '<div class="modal-content">'
                                                + '<div class="modal-header border-0">'
                                                    + '<h5 class="modal-title">'+item.title+'</h5>'
                                                    + '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                                                + '</div>'
                                                + '<div class="modal-body">'+item.description+'</div>'
                                                + '<div class="modal-footer border-0"><i class="small">Last Updated: '+item.updated_at+'</i>'
                                        + '</div></div></div>'
                                    )
                                })
                            } else {
                                $(cls+'_data table').html('<tr class="bg-transparent border-0"><td class="border-0">{{ __("messages.not_found") }}</td></tr>')
                            }
                        },
                        error: function() {
                            $(cls+'_data').fadeOut('fast')
                            $(cls+'_loading').fadeOut('fast')
                            $(cls+'_result').fadeOut('fast')
                        }
                    })
                } else {
                    $(cls+'_result').fadeOut('fast')
                    $(cls+'_loading').fadeOut('fast')
                }
            }

            function delay(callback, ms) {
                var timer = 0
                return function() {
                    var context = this, args = arguments;
                    clearTimeout(timer)
                    timer = setTimeout(function () {
                    callback.apply(context, args)
                    }, ms || 0)
                };
            }
        })
    </script>
@endsection