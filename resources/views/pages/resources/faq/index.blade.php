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
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="s" autocomplete="off" placeholder="keyword here..." value="{{ isset($_GET['s']) ? $_GET['s'] : '' }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                    <div class="input-group-append">
                        <a href="/faqs" class="btn btn-danger" type="submit">Reset</a>
                    </div>
                </div>
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
                                    </ol>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            $('.collapse').collapse()
        })
    </script>
@endsection