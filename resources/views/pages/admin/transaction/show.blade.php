@extends('layouts.app')

@section('title', 'View '.strtoupper($transaction->trans_type))

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="form-row"> 
                <div class="col-md-6 mb-4">
                    <a href="#" class="btn btn-default"><i class="align-middle font-weight-bolder material-icons text-md">arrow_back_ios</i> Back</a>
                    <a href="#" class="btn btn-dark"><i class="align-middle font-weight-bolder material-icons text-md">autorenew</i> Renew Edit Limit</a>
                </div>
                <div class="col-md-6 text-right mb-4">
                    <a href="#" class="btn btn-primary"><i class="align-middle font-weight-bolder material-icons text-md">edit</i> Edit</a>
                    <a href="#" class="btn btn-danger"><i class="align-middle font-weight-bolder material-icons text-md">delete</i> Cancel</a>
                    <a href="#" class="btn btn-success px-5"><i class="align-middle font-weight-bolder material-icons text-md">check</i> Issued</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <div class="pb-2 mb-4 border-bottom">
                        <h1 class="d-inline-block mr-3">{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}</h1>
                        <h6 class="d-inline-block">
                            <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--xxs mr-1">
                            {{ $transaction->project->company->name }}
                        </h6>
                    </div>
                    <div>
                        <div class="form-row mb-3">
                            <div class="col-md-9">
                                <label for="">Particulars</label>
                                <h5>{{ $transaction->particulars->name }}</h5>
                            </div>
                            <div class="col-md-3">
                                <label for="">Amount</label>
                                <h5>{{ $transaction->currency }} {{ $transaction->amount }}</h5>
                            </div>
                        </div>
                        <div class="form-row mb-3">    
                            <div class="col-md-9">
                                <label for="">Payee Name</label>
                                <h5>{{ $transaction->payee }}</h5>
                            </div>                
                            <div class="col-md-3">
                                <label for="">Project</label>
                                <h5>{{ $transaction->project->project }}</h5>
                            </div>         
                        </div>
                        <div class="form-row mb-3">                
                            <div class="col-md-11">
                                <label for="">Purpose</label>
                                <h6>{{ $transaction->purpose }}</h6>
                            </div>   
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-4">
                                <label for="">Requested by</label>
                                <h5>{{ $transaction->requested->name }}</h5>
                            </div>
                            <div class="col-md-4">
                                <label for="">Prepared by</label>
                                <h5>{{ $transaction->owner->name }}</h5>
                            </div>                        
                            <div class="col-md-3 offset-md-1">
                                <label for="">Due Date</label>
                                <h5>{{ $transaction->due_at }}</h5>
                            </div>                
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="pb-2 text-right"><h1>History</h1></div>
                    <table class="table table-striped table-bordered table-sm small">
                        <tbody>
                            @foreach ($logs as $item)
                                <tr>
                                    <td>
                                        <a href="#_" data-toggle="modal" data-target="#modal-{{ $item->id }}">{{ strtoupper($item->description) }}</a>
                                        <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">{{ ucfirst($item->description) }} {{ Carbon::parse($item->created_at)->diffForHumans() }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @switch($item->description)
                                                            @case('created')
                                                                <table class="table table-sm table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Value</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($item->changes['attributes'] as $key => $attribute)
                                                                            <tr>
                                                                                <td class="font-weight-bold">{{ ucwords($key) }}</td>
                                                                                <td>{{ $attribute }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                @break
                                                            @case('updated')
                                                                    <table class="table table-sm table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th>From</th>
                                                                                <th>To</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($item->changes['old'] as $key => $attribute)
                                                                                <tr>
                                                                                    <td class="font-weight-bold">{{ ucwords($key) }}</td>
                                                                                    <td>{{ $attribute }}</td>
                                                                                    <td>{{ $item->changes['attributes'][$key] }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                @break
                                                            @default                                                    
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->causer->name }}</td>
                                    <td class="text-right">{{ Carbon::parse($item->created_at)->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>        
        </div>
    </section>
@endsection