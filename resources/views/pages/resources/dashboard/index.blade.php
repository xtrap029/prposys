@extends('layouts.app-resources')

@section('content')
<section class="content-header pb-0">
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg--tecc">
                        <h3 class="widget-user-username">{{ $user->name }}</h3>
                        <h6 class="widget-user-desc">{{ $user->role->name }} {{ $user->is_smt ? ' - SMT' : '' }}</h6>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="/storage/public/images/users/{{ $user->avatar }}" alt="User Avatar">
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row">
                            <div class="col-sm-6 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">Email</h5>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="description-block">
                                    <h5 class="description-header">Hired</h5>
                                    <span>  
                                        @if ($user->e_hire_date)
                                            {{ Carbon::parse($user->e_hire_date)->diffInDays(Carbon::now()) >= 1 ? Carbon::parse($user->e_hire_date)->format('Y-m-d') : Carbon::parse($user->e_hire_date)->diffForHumans() }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-8 d-flex">
                @include('layouts.sections.slick')
            </div>
            <div class="col-12">
                <p>
                    <a href="/forms" class="btn btn-dark rounded-pill p-4 mr-1" style="line-height: 15px;">
                        Forms
                    </a>
                    @foreach ($forms as $item)
                        <button type="button" class="btn btn-default rounded-pill p-3 mr-1" data-toggle="modal" data-target="#modal-{{ $item->id }}" style="line-height: 15px;">
                            {{ $item->name }}
                            <br><code>{{ $item->category }}</code>
                        </button>
                    @endforeach

                    @foreach ($forms as $item)
                        <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">{{ $item->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        {!! $item->description !!}

                                        <a class="btn btn-default d-block mt-3" href="/storage/public/attachments/form/{{ $item->attachment }}" target="_blank">
                                            <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                                            <span class="text-dark">Download Attachment</span>
                                        </a>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <i class="small">Last Updated: {{ $item->updated_at }}</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </p>
            </div>
            <div class="col-md-6">
                <div class="card card-widget">
                    <div class="card-header pb-2 bg-dark">
                        <h3 class="card-title">
                            Newest FAQs
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table m-0">
                            @foreach ($faqs_recent as $item)
                                <tr>
                                    <td>
                                        <a href="#_" data-toggle="modal" data-target="#modal-{{ $item->id }}">{{ $item->title }}</a>
                                        <br><code>{{ $item->category }}</code>
                                    </td>
                                    <td class="text-right">{{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->created_at->format('Y-m-d') : $item->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </table>

                        @foreach ($faqs_recent as $item)
                            <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">{{ $item->title }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {!! $item->description !!}
                                        </div>
                                        <div class="modal-footer border-0">
                                            <i class="small">Last Updated: {{ $item->updated_at }}</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>                
            </div>
            <div class="col-md-6">
                <div class="card card-widget">
                    <div class="card-header pb-2 bg-dark">
                        <h3 class="card-title">
                            Updated FAQs
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table m-0">
                            @foreach ($faqs_updated as $item)
                                <tr>
                                    <td>
                                        <a href="#_" data-toggle="modal" data-target="#modal-{{ $item->id }}">{{ $item->title }}</a>
                                        <br><code>{{ $item->category }}</code>
                                    </td>
                                    <td class="text-right">{{ Carbon::parse($item->updated_at)->diffInDays(Carbon::now()) >= 1 ? $item->updated_at->format('Y-m-d') : $item->updated_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </table>

                        @foreach ($faqs_updated as $item)
                            <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">{{ $item->title }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {!! $item->description !!}
                                        </div>
                                        <div class="modal-footer border-0">
                                            <i class="small">Last Updated: {{ $item->updated_at }}</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>                
            </div>
        </div>
    </div>
</section>
@endsection