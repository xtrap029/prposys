@extends('layouts.app')

@section('title', 'Liquidate '.strtoupper($transaction->trans_type))

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--xs mr-2">
                        {{ $transaction->project->company->name }}
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <h1>Liquidate {{ strtoupper($transaction->trans_type) }}</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <table class="table col-md-6">
                    <tr>
                        <td class="font-weight-bold">Transaction</td>
                        <td>{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Requested by</td>
                        <td>{{ $transaction->requested->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Project</td>
                        <td>{{ $transaction->project->project }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">COA Tagging</td>
                        <td>{{ $transaction->coatagging->name }}</td>
                    </tr>
                </table>
                <div class="col-md-6">
                    <label for="" class="font-weight-bold">Purpose</label>
                    <p>{{ $transaction->purpose }}</p>
                </div>
            </div>
            <form action="" method="post" enctype="multipart/form-data" class="jsPreventMultiple">
                @csrf
                <input type="hidden" name="key" value="{{ strtoupper($transaction->trans_type) }}-{{ $transaction->trans_year }}-{{ sprintf('%05d',$transaction->trans_seq) }}">
                <input type="hidden" name="company" value="{{ $transaction->project->company->id }}">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded" role="alert">
                        @foreach ($errors->all() as $key => $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (!$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr)
                    <div class="jsReplicate mt-5">
                        <h4 class="text-center">Items</h4>
                        <table class="table bg-white">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Location/Route</th>
                                    <th class="text-center">Receipt</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="jsReplicate_container">
                                <tr>
                                    <td><input type="date" class="form-control" name="date[]" value="{{ old('date.0') }}" required></td>
                                    <td>
                                        <select name="expense_type_id[]" class="form-control" required>
                                            @foreach ($expense_types as $item)
                                                <option value="{{ $item->id }}" {{ old('expense_type_id.0') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="description[]" value="{{ old('description.0') }}" required></td>
                                    <td><input type="text" class="form-control" name="location[]" value="{{ old('location.0') }}" required></td>
                                    <td class="text-center">
                                        <select name="receipt[]" class="form-control">
                                            <option value="1" {{ old('receipt.0') == 1 ? 'selected' : '' }}>Y</option>
                                            <option value="0" {{ old('receipt.0') == 0 ? 'selected' : '' }}>N</option>
                                        </select>
                                    </td>
                                    <td colspan="2"><input type="number" class="form-control" name="amount[]" step="0.01" value="{{ old('amount.0') }}" required></td>
                                </tr>
                                @if (old('date'))
                                    @foreach (old('date') as $key => $item)
                                        @if ($key > 0)
                                            <tr class="jsReplicate_template_item">
                                                <td><input type="date" class="form-control" name="date[]" value="{{ old('date.'.$key) }}" required></td>
                                                <td>
                                                    <select name="expense_type_id[]" class="form-control" required>
                                                        @foreach ($expense_types as $expense_type)
                                                            <option value="{{ $expense_type->id }}" {{ old('expense_type_id.'.$key) == $expense_type->id ? 'selected' : '' }}>{{ $expense_type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="description[]" value="{{ old('description.'.$key) }}" required></td>
                                                <td><input type="text" class="form-control" name="location[]" value="{{ old('location.'.$key) }}" required></td>
                                                <td class="text-center">
                                                    <select name="receipt[]" class="form-control">
                                                        <option value="1" {{ old('receipt.'.$key) == 1 ? 'selected' : '' }}>Y</option>
                                                        <option value="0" {{ old('receipt.'.$key) == 0 ? 'selected' : '' }}>N</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" class="form-control" name="amount[]" step="0.01" value="{{ old('amount.'.$key) }}" required></td>
                                                <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                        </div>
                    </div>
                @elseif($transaction->is_deposit)
                    <div class="row mt-5">                                            
                        <div class="col-md-3">
                            <label for="" class="font-weight-bold">Mode</label>
                            <select name="depo_type" class="depo_type form-control @error('depo_type') is-invalid @enderror" required>
                                @foreach (config('global.deposit_type') as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('depo_type')])
                        </div>
                        <div class="col-md-3">
                            <label for="" class="font-weight-bold">Type / Account #</label>
                            <select name="depo_bank_branch_id" class="form-control @error('depo_bank_branch_id') is-invalid @enderror" required>
                                @foreach ($banks as $item)
                                    <optgroup label="{{ $item->name }}">
                                        @foreach ($item->bankbranches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('depo_bank_branch_id')])
                        </div>
                        <div class="col-md-6">
                            <label for="" class="font-weight-bold">Reference Code</label>
                            <input type="text" name="depo_ref" class="depo_ref form-control @error('depo_ref') is-invalid @enderror" required>
                            @include('errors.inline', ['message' => $errors->first('depo_ref')])
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label for="" class="font-weight-bold">Deposited By</label>
                            <select name="liquidation_approver_id" class="form-control @error('liquidation_approver_id') is-invalid @enderror">
                                @foreach ($users as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == Auth::user()->id ? 'selected' : '' }}>{{ $item->name }}</option>                                        
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('requested_id')])
                        </div>
                        <div class="col-md-3">
                            <label for="" class="font-weight-bold">Date Deposited</label>
                            <input type="date" name="depo_date" class="form-control @error('depo_date') is-invalid @enderror" required>
                            @include('errors.inline', ['message' => $errors->first('depo_date')])
                        </div>
                        <div class="col-md-6">
                            <label for="" class="font-weight-bold">Received By</label>
                            <input type="text" name="depo_received_by" class="form-control @error('depo_received_by') is-invalid @enderror" required>
                            @include('errors.inline', ['message' => $errors->first('depo_received_by')])
                        </div>
                    </div>
                @endif
                <div class="jsReplicate mt-5 pt-5">
                    <h4 class="text-center">Attachments</h4>
                    <div class="text-center mb-3">Attach receipts and documents here. Accepts .jpg, .png and .pdf file types, not more than 5mb each.</div>
                    <table class="table bg-white">
                        <thead>
                            <tr>
                                <th class="w-25">File</th>
                                <th class="w-75">Description</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="jsReplicate_container">
                            <tr>
                                <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                                <td colspan="2"><input type="text" name="attachment_description[]" class="form-control" value="{{ old('attachment_description.0') }}" required></td>
                            </tr>
                            @if (old('attachment_description'))
                                @foreach (old('attachment_description') as $key => $item)
                                    @if ($key > 0)
                                        <tr>
                                            <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                                            <td><input type="text" name="attachment_description[]" class="form-control" value="{{ old('attachment_description.'.$key) }}" required></td>
                                            <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                    </div>
                </div>
                <div class="text-center mt-5 py-5 border-top">
                    <a href="/transaction/{{ $trans_page_url }}/{{ $transaction->project->company_id }}" class="mr-3">Cancel</a>
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </form>

            @if (!$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr)
                <table class="d-none">
                    <tbody class="jsReplicate_template">
                        <tr class="jsReplicate_template_item">
                            <td><input type="date" class="form-control" name="date[]" required></td>
                            <td>
                                <select name="expense_type_id[]" class="form-control" required>
                                    @foreach ($expense_types as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control" name="description[]" required></td>
                            <td><input type="text" class="form-control" name="location[]" required></td>
                            <td class="text-center">
                                <select name="receipt[]" class="form-control">
                                    <option value="1">Y</option>
                                    <option value="0">N</option>
                                </select>
                            </td>
                            <td><input type="number" class="form-control" name="amount[]" step="0.01" required></td>
                            <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                        </tr>
                    </tbody>
                </table>
            @endif

            <table class="d-none">
                <tbody class="jsReplicate_template">
                    <tr class="jsReplicate_template_item">
                        <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                        <td><input type="text" name="attachment_description[]" class="form-control" required></td>
                        <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            if ($('.jsReplicate')[0]){
                cls = '.jsReplicate'
                
                $(cls+'_add').click(function() {
                    container = $(this).parents(cls).find(cls+'_container')
                    clsIndex = $(this).parents(cls).index(cls)
                    $(cls+'_template:eq('+clsIndex+')').children().clone().appendTo(container)
                })

                $(document).on('click', cls+'_remove', function() {
                    $(this).parents(cls+'_template_item').remove()
                })
            }

            checkDepo()

            $('.depo_type').on('change', function() {
                checkDepo()
            })

            function checkDepo() {
                if ($('.depo_type').val() == 'CASH') {
                    $('.depo_ref').removeAttr('required')
                } else {
                    $('.depo_ref').prop('required',true)
                }
            }
        })
    </script>
@endsection