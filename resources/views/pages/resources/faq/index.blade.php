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
            <div id="accordion">
                @foreach ($categories as $item)
                    <div class="card mb-0">
                        <div class="card-header p-0" id="heading{{ $item->random }}">
                            <h5 class="mb-0">
                                <button class="btn btn-link btn-block text-left text-dark text-decoration-none collapsed px-4 py-3" data-toggle="collapse" data-target="#collapse{{ $item->random }}" aria-expanded="true" aria-controls="collapse{{ $item->random }}">
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
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
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