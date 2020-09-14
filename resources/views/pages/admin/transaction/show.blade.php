@extends('layouts.app')

@section('title', 'View '.strtoupper($transaction->trans_type))

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row"> 
                <div class="col-md-6 mb-4">
                    <a href="/transaction/{{ $trans_page }}/{{ $transaction->project->company_id }}" class="btn btn-default"><i class="align-middle font-weight-bolder material-icons text-md">arrow_back_ios</i> Back</a>
                    <a href="/transaction/create/{{ $transaction->trans_type }}/{{ $transaction->project->company_id }}" class="btn btn-default"><i class="align-middle font-weight-bolder material-icons text-md">add</i> Add New</a>
                    <a href="/transaction/reset/{{ $transaction->id }}" class="btn btn-default {{ $perms['can_reset'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">autorenew</i> Renew Edit Limit</a>
                </div>
                <div class="col-md-6 text-right mb-4">
                    <a href="/transaction/edit/{{ $transaction->id }}" class="btn btn-primary {{ $perms['can_edit'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">edit</i> Edit</a>
                    <a href="#_" class="btn btn-danger {{ $perms['can_cancel'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-cancel"><i class="align-middle font-weight-bolder material-icons text-md">delete</i> Cancel</a>
                    {{-- <a href="#" class="btn btn-success {{ $perms['can_issue'] ? '' : 'd-none' }} px-5" data-toggle="modal" data-target="#modal-issue"><i class="align-middle font-weight-bolder material-icons text-md">check</i> Issued</a> --}}
                </div>
                
                @if ($perms['can_cancel'])
                    <div class="modal fade" id="modal-cancel" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">{{ __('messages.cancel_prompt') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <form action="/transaction/cancel/{{ $transaction->id }}" method="post">
                                        @csrf
                                        @method('put')
                                        <textarea name="cancellation_reason" class="form-control @error('cancellation_reason') is-invalid @enderror" rows="3" placeholder="Cancellation Reason" required></textarea>
                                        @include('errors.inline', ['message' => $errors->first('cancellation_reason')])
                                        <input type="submit" class="btn btn-danger mt-2" value="Cancel Now">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <div class="pb-2 mb-4 border-bottom">
                        <h1 class="d-inline-block mr-3">
                            <input type="text" value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}" class="input--label" readonly>
                            <a href="#_" class="btn btn-default vlign--top jsCopy" data-toggle="tooltip" data-placement="top" title="Copy to clipboard"><i class="align-middle font-weight-bolder material-icons text-md">content_copy</i></a>
                        </h1>
                        <h6 class="d-inline-block">
                            <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--xxs mr-1">
                            {{ $transaction->project->company->name }}
                        </h6>
                    </div>
                    <div>
                        <div class="row mb-3">
                            <div class="col-md-9">
                                <label for="">Particulars</label>
                                <h5>{{ $trans_page == 'prpo' ? $transaction->particulars->name : $transaction->particulars_custom }}</h5>
                            </div>
                            <div class="col-md-3">
                                <label for="">Amount</label>
                            <h5>{{ $transaction->currency }} {{ number_format($transaction->amount, 2, '.', ',') }}</h5>
                            </div>
                        </div>
                        <div class="row mb-3">    
                            <div class="col-md-9">
                                <label for="">Payee Name</label>
                                <h5>{{ $transaction->payee }}</h5>
                            </div>                
                            <div class="col-md-3">
                                <label for="">Project</label>
                                <h5>{{ $transaction->project->project }}</h5>
                            </div>         
                        </div>
                        <div class="row mb-3">                
                            <div class="col-md-9">
                                <label for="">Purpose</label>
                                <h6>{{ $transaction->purpose }}</h6>
                            </div>   
                            <div class="col-md-3">
                                <label for="">For Deposit?</label>
                                <h5>{{ $transaction->is_deposit ? 'Yes' : 'No' }}</h5>
                            </div> 
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="">Requested by</label>
                                <h5>{{ $transaction->requested->name }}</h5>
                            </div>
                            <div class="col-md-3">
                                <label for="">Prepared by</label>
                                <h5>{{ $transaction->owner->name }}</h5>
                            </div>                        
                            <div class="col-md-3">
                                <label for="">Due Date</label>
                                <h5>{{ $transaction->due_at }}</h5>
                            </div>
                            <div class="col-md-3">
                                <label for="">Status</label>
                                <h5>{{ $transaction->status->name }}</h5>
                            </div>              
                        </div>
                    </div>
                    @if ($transaction->status_id == 3)
                        <div class="pb-2 mt-5 mb-4 border-bottom">
                            <h3 class="d-inline-block mr-3">Cancellation Reason</h3>
                            <code>{{ $transaction->cancellation_number }}</code>
                        </div>
                        <div>{{ $transaction->cancellation_reason }}</div>
                    @endif
                </div>
                <div class="col-sm-4">
                    <div class="pb-2 text-right"><h1>History</h1></div>
                    <table class="table table-striped table-bordered table-sm small">
                        <tbody>
                            @foreach ($logs as $item)
                                <tr>
                                    <td>
                                        <a href="#_" data-toggle="modal" data-target="#modal-{{ $item->id }}">
                                            @if ($item->description == 'created')
                                                <i class="align-middle font-weight-bolder material-icons text-md">add</i>
                                            @else
                                                <i class="align-middle font-weight-bolder material-icons text-md">edit</i>
                                            @endif
                                        </a>
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
                                                                    <thead class="bg-gradient-gray">
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
                                                                        <thead class="bg-gradient-gray">
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
                    <div class="text-center">
                        <div class="d-inline-block">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>        
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()

            $('.jsCopy').click(function() {
                var copyText = $('.input--label')
                copyText.select()
                document.execCommand("copy")
                document.getSelection().removeAllRanges()

                $(this).attr('data-original-title', "Copied")
                    .tooltip('show')
            })

            $('.jsCopy').mouseleave(function() {
                $(this).attr("data-original-title","Copy to clipboard")
            })
        })
    </script>
@endsection