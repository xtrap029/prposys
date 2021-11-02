@extends('layouts.app')

@section('title', 'View '.strtoupper($transaction->trans_type))
@section('nav_class', 'navbar-dark')

@section('content')
    <?php $ua = (new \App\Helpers\UAHelper)->get(); ?>
    <?php $non = config('global.ua_none'); ?>

    <?php $config_confidential = 0; ?>
    <section class="content-header bg-dark">
        <div class="container-fluid">
            <div class="row"> 
                <div class="col-lg-6">
                    <h1 class="mb-0">
                        <input type="text"
                            value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}"
                            class="input--label text-white" readonly>
                        <a href="#_" class="text-white vlign--top jsCopy" data-toggle="tooltip" data-placement="top" title="Copy to clipboard"><i class="align-middle font-weight-bolder material-icons text-md">content_copy</i></a>
                    </h1>
                    <span class="text-white-50">{{ $transaction->project->company->name }}</span>
                    <div class="mt-2">
                        <span class="badge badge-pill bg-warning p-2">
                            @if ($transaction->is_deposit)
                                {{ config('global.trans_category_label')[1] }}
                            @elseif ($transaction->is_bills)    
                                {{ config('global.trans_category_label')[2] }}
                            @elseif ($transaction->is_hr)    
                                {{ config('global.trans_category_label')[3] }}
                            @elseif ($transaction->is_reimbursement)    
                                {{ config('global.trans_category_label')[4] }}
                            @elseif ($transaction->is_bank)    
                                {{ config('global.trans_category_label')[5] }}
                            @else
                                {{ config('global.trans_category_label')[0] }}    
                            @endif
                        </span>
                        <span class="badge badge-pill bg-purple p-2">{{ $transaction->status->name }}</span>
                    </div>
                </div>

                <div class="col-lg-6 text-right mt-4">
                    <div>
                        <a href="/transaction/{{ $trans_page }}/{{ $transaction->project->company_id }}{{ isset($_GET['page']) ? '?page='.$_GET['page'] : '' }}" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto"><i class="align-middle font-weight-bolder material-icons text-md">arrow_back_ios</i> Back</a>
                        <a href="/transaction/create/{{ $transaction->trans_type }}/{{ $transaction->project->company_id }}" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto {{ $ua['trans_add'] == $non ? 'd-none' : '' }}"><i class="align-middle font-weight-bolder material-icons text-md">add</i> Add New</a>
                        {{-- <a href="/transaction/reset/{{ $transaction->id }}" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto {{ $perms['can_reset'] ? '' : 'd-none' }}" onclick="return confirm('Are you sure?')"><i class="align-middle font-weight-bolder material-icons text-md">autorenew</i> Renew Edit Limit</a> --}}
                        <a href="#_" class="btn btn-sm btn-flat mb-2 btn-light col-12 col-lg-auto {{ $perms['can_manage'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-manage"><i class="align-middle font-weight-bolder material-icons text-md">security</i> Manage</a>
                    </div>
                    <div>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-light col-12 col-lg-auto" data-toggle="modal" data-target="#modal-notes">
                            <i class="align-middle font-weight-bolder material-icons text-md">speaker_notes</i> Notes
                            <span class="badge badge-danger {{ $transaction->notes->count() > 0 ? '' : 'd-none' }}">{{$transaction->notes->count()}}</span>
                        </a>
                        <a href="/transaction-form/create{{ $transaction->is_reimbursement ? '-reimbursement' : '' }}?company={{ $transaction->project->company_id }}&key={{ strtoupper($transaction->trans_type)."-".$transaction->trans_year."-".sprintf('%05d',$transaction->trans_seq) }}" class="btn mb-2 btn-sm btn-flat btn-success col-12 col-lg-auto {{ $perms['can_create'] ? '' : 'd-none' }}">
                            <i class="align-middle font-weight-bolder material-icons text-md">add</i> 
                            @if ($transaction->is_deposit)
                                {{ config('global.trans_category_label_make_form')[1] }}
                            @elseif ($transaction->is_bills)    
                                {{ config('global.trans_category_label_make_form')[2] }}
                            @elseif ($transaction->is_hr)    
                                {{ config('global.trans_category_label_make_form')[3] }}
                            @elseif ($transaction->is_bank)    
                                {{ config('global.trans_category_label_make_form')[5] }}
                            @else
                                {{ config('global.trans_category_label_make_form')[0] }}
                            @endif
                        </a>
                        <a href="/transaction/edit/{{ $transaction->id }}" class="btn mb-2 btn-sm btn-flat btn-primary col-12 col-lg-auto {{ $perms['can_edit'] ? '' : 'd-none' }}"><i class="align-middle font-weight-bolder material-icons text-md">edit</i> Edit</a>
                        <a href="#_" class="btn mb-2 btn-sm btn-flat btn-danger col-12 col-lg-auto {{ $perms['can_cancel'] ? '' : 'd-none' }}" data-toggle="modal" data-target="#modal-cancel"><i class="align-middle font-weight-bolder material-icons text-md">delete</i> Cancel</a>
                    </div>
                </div>
            </div>
            <div class="text-dark">
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

                @if ($perms['can_manage'])
                    <div class="modal fade" id="modal-manage" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">{{ __('messages.manage_prompt') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <form action="/transaction/manage/{{ $transaction->id }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="">Prepared by</label>
                                                <select name="owner_id" class="form-control" required>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}" {{ $user->id == $transaction->owner_id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="">Requested by</label>
                                                <select name="requested_id" class="form-control" required>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}" {{ $user->id == $transaction->requested_id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="">Currency</label>
                                                <select name="currency" class="form-control" required>
                                                    @foreach (config('global.currency') as $key => $item)
                                                        <option value="{{ config('global.currency_label')[$key] }}" {{ config('global.currency_label')[$key] == $transaction->currency ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="">Due Date</label>
                                                <input type="date" class="form-control" name="due_at" value="{{ $transaction->due_at }}" required>
                                            </div>
                                            @if (in_array($transaction->status_id, config('global.form_issued'))
                                                || in_array($transaction->status_id, config('global.liquidations'))
                                                || in_array($transaction->status_id, config('global.liquidation_cleared')))
                                                <div class="col-md-6 form-group">
                                                    <label for="">Released by</label>
                                                    <select name="released_by_id" class="form-control">
                                                        @foreach ($releasing_users as $releasing_user)
                                                            <option value="{{ $releasing_user->id }}" {{ $releasing_user->id == $transaction->released_by_id ? 'selected' : '' }}>{{ $releasing_user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label for="">{{ $transaction->is_deposit ? 'Date Deposited' : 'Release Date' }}</label>
                                                    <input type="date" class="form-control" name="released_at" value="{{ $transaction->released_at }}" required>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-danger mt-2"><i class="align-middle font-weight-bolder material-icons text-md">security</i> Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @include('pages.admin.transaction.notes')
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid pt-3">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    {{-- <div class="col-md-9">
                                        <label for="">Particulars</label>
                                        <h5>{{ $trans_page == 'prpo' ? $transaction->particulars->name : $transaction->particulars_custom }}</h5>
                                    </div> --}}
                                    <tr>
                                        <td class="font-weight-bold text-gray border-0">Requested By</td>
                                        <td class="font-weight-bold border-0">
                                            <img src="/storage/public/images/users/{{ $transaction->requested->avatar }}" class="img-circle img-size-32 mr-2">
                                            {{ $transaction->requested->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Prepared By</td>
                                        <td class="font-weight-bold">
                                            <img src="/storage/public/images/users/{{ $transaction->owner->avatar }}" class="img-circle img-size-32 mr-2">
                                            {{ $transaction->owner->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Project</td>
                                        <td class="font-weight-bold">
                                            <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" class="img-circle img-size-32 mr-2">
                                            {{ $transaction->project->project }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Due By</td>
                                        <td class="font-weight-bold">{{ $transaction->due_at }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Amount</td>
                                        <td class="font-weight-bold">
                                            @if ($config_confidential)
                                                -
                                            @else
                                                {{ $transaction->currency }} {{ number_format($transaction->amount, 2, '.', ',') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold text-gray">Payee Name</td>
                                        <td class="font-weight-bold">{{ $transaction->payee }}</td>
                                    </tr>
                                    @if (Auth::user()->is_smt && $transaction->owner->is_smt)
                                        <tr>
                                            <td class="font-weight-bold text-gray">Is Confidential?</td>
                                            <td class="font-weight-bold">
                                                <a href="/transaction/toggle-visibility/{{ $transaction->id }}" class="mr-1" onclick="return confirm('Toggle visibility?');">
                                                    @if ($transaction->is_confidential == 0)
                                                        <i class="material-icons font-weight-bold text-xl text-gray vlign--middle" title="Visible">toggle_off</i>
                                                    @else
                                                        <i class="material-icons font-weight-bold text-xl text-danger vlign--middle" title="Hidden">toggle_on</i>
                                                    @endif
                                                </a> 
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2">
                                            <span class="font-weight-bold text-gray">Purpose</span>
                                            <p class="mb-0">
                                                @if ($config_confidential)
                                                    -
                                                @else
                                                    {{ $transaction->purpose }}
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                @if ($config_confidential)
                                @else
                                    <a class="btn btn-app p-2 {{ $transaction->soa ? '' : 'd-none' }}" href="/storage/public/attachments/soa/{{ $transaction->soa }}" target="_blank">
                                        <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
                                        <p class="text-dark">SOA</p>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if ($transaction->status_id == 3)
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <code class="float-right">{{ $transaction->cancellation_number }}</code>
                                <h5>Cancellation Reason</h5>
                                <div class="pt-3">{{ $transaction->cancellation_reason }}</div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <h5>History</h5>
                            <table class="table table-striped table-bordered table-sm small my-3">
                                <tbody>
                                    @foreach ($logs as $item)
                                        <tr>
                                            <td>
                                                @if ($config_confidential)
                                                    <span class="text-secondary">
                                                        @if ($item->description == 'created')
                                                            <i class="align-middle font-weight-bolder material-icons text-md">add</i>
                                                        @else
                                                            <i class="align-middle font-weight-bolder material-icons text-md">edit</i>
                                                        @endif
                                                    </span>
                                                @else
                                                    <a href="#_" data-toggle="modal" data-target="#modal-{{ $item->id }}">
                                                        @if ($item->description == 'created')
                                                            <i class="align-middle font-weight-bolder material-icons text-md">add</i>
                                                        @else
                                                            <i class="align-middle font-weight-bolder material-icons text-md">edit</i>
                                                        @endif
                                                    </a>
                                                @endif
                                                <div class="modal fade" id="modal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header border-0">
                                                                <h5 class="modal-title">
                                                                    {{ ucfirst($item->description) }} {{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->created_at->format('Y-m-d') : $item->created_at->diffForHumans() }}
                                                                </h5>
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
                                            <td>{{ $item->log_name }}</td>
                                            <td>{{ $item->causer->name }}</td>
                                            <td class="text-right">{{ Carbon::parse($item->created_at)->diffInDays(Carbon::now()) >= 1 ? $item->created_at->format('Y-m-d') : $item->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                <div class="d-inline-block small">
                                    {{ $logs->links() }}
                                </div>
                            </div>
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