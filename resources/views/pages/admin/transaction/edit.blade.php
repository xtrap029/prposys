@extends('layouts.app')

@section('title', 'Edit '.strtoupper($transaction->trans_type))

@section('content')
    <?php $config_confidential = 0; ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-6 mb-2">
                    <h1>
                        <img src="/storage/public/images/companies/{{ $transaction->project->company->logo }}" alt="" class="thumb--xs mr-2">
                        {{ $transaction->project->company->name }}
                    </h1>
                </div>
                <div class="col-lg-6 text-lg-right mb-2">
                    <h1>Edit {{ strtoupper($transaction->trans_type) }}</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/transaction/edit/{{ $transaction->id }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="form-row mb-3">
                    {{-- <div class="col-md-6">
                        <label for="">Particulars</label>
                        @if ($trans_page == 'prpo')
                            <select name="particulars_id" class="form-control @error('particulars_id') is-invalid @enderror">
                                @foreach ($particulars as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $transaction->particulars_id ? 'selected' : '' }}>{{ $item->name }}</option>                                        
                                @endforeach
                            </select>
                            @include('errors.inline', ['message' => $errors->first('particulars_id')])
                        @else
                            <input type="text" class="form-control @error('particulars_custom') is-invalid @enderror" name="particulars_custom" value="{{ $transaction->particulars_custom }}" required>
                            @include('errors.inline', ['message' => $errors->first('particulars_custom')])
                        @endif
                    </div> --}}
                    <div class="col-lg-3 mb-2">
                        <label for="">Transaction Category</label>
                        <select name="trans_category" class="trans-category form-control @error('trans_category') is-invalid @enderror">
                            <option 
                                value="{{ config('global.trans_category')[0] }}"
                            >
                                {{ config('global.trans_category_label')[0] }}
                            </option>
                            <option 
                                value="{{ config('global.trans_category')[1] }}"
                                {{ $transaction->is_deposit == 1 ? 'selected' : '' }}
                            >
                                {{ config('global.trans_category_label')[1] }}
                            </option>
                            <option 
                                value="{{ config('global.trans_category')[2] }}"
                                {{ $transaction->is_bills == 1 ? 'selected' : '' }}
                            >
                                {{ config('global.trans_category_label')[2] }}
                            </option>
                            <option 
                                value="{{ config('global.trans_category')[3] }}"
                                {{ $transaction->is_hr == 1 ? 'selected' : '' }}
                            >
                                {{ config('global.trans_category_label')[3] }}
                            </option>
                            <option 
                                value="{{ config('global.trans_category')[4] }}"
                                {{ $transaction->is_reimbursement == 1 ? 'selected' : '' }}
                            >
                                {{ config('global.trans_category_label')[4] }}
                            </option>
                            <option 
                                value="{{ config('global.trans_category')[5] }}"
                                {{ $transaction->is_bank == 1 ? 'selected' : '' }}
                            >
                                {{ config('global.trans_category_label')[5] }}
                            </option>
                        </select>
                        @include('errors.inline', ['message' => $errors->first('trans_category')])
                    </div>
                    <div class="col-sm-5 col-lg-4 mb-2">
                        <label for="">Project</label>
                        <select name="project_id" class="form-control @error('project_id') is-invalid @enderror">
                            @foreach ($projects as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $transaction->project_id ? 'selected' : '' }}>{{ $item->project }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('project_id')])
                    </div>
                    <div class="col-4 col-sm-2 col-lg-1 mb-2">
                        <label for="">Currency</label>
                        <select name="currency" class="form-control @error('currency') is-invalid @enderror">
                            @foreach (config('global.currency') as $key => $item)
                                <option value="{{ config('global.currency_label')[$key] }}" {{ config('global.currency_label')[$key] == $transaction->currency ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('currency')])
                    </div>
                    <div class="col-8 col-sm-5 col-lg-4 mb-2">
                        <label for="">Amount</label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror {{ $config_confidential ? 'd-none' : '' }}" step="0.01" name="amount" value="{{ $transaction->amount }}" required>
                        @include('errors.inline', ['message' => $errors->first('amount')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-sm-6 col-lg-4 mb-2">
                        <label for="">Purpose</label>
                        <select name="purpose_option_id" id="purposeOption" class="form-control @error('purpose_option_id') is-invalid @enderror">
                            @foreach ($purpose_options as $item)
                                @if (in_array($company->id, explode(',', $item->companies))) 
                                    <option value="{{ $item->id }}" {{ $item->id == $transaction->purpose_option_id ? 'selected' : '' }} data-description="{{ $item->description }}">{{ $item->code.' - '.$item->name }}</option>                                        
                                @endif
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('purpose_option_id')])
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-2">
                        <label for="">Purpose Details</label>
                        <textarea name="purpose" id="purposeDescription" rows="1" class="form-control @error('purpose') is-invalid @enderror {{ $config_confidential ? 'd-none' : '' }}" required>{{ $transaction->purpose }}</textarea>
                        @include('errors.inline', ['message' => $errors->first('purpose')])
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-2">
                        <label for="">Payee Name</label>
                        <select name="vendor_id" class="form-control @error('vendor_id') is-invalid @enderror">
                            @foreach ($vendors as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $transaction->vendor_id ? 'selected' : '' }}>{{ $item->name }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('payee')])
                    </div>
                    {{-- <div class="col-sm-6 col-lg-4 mb-2">
                        <label for="">Payee Name</label>
                        <input type="text" class="form-control @error('payee') is-invalid @enderror" name="payee" value="{{ $transaction->payee }}" required>
                        @include('errors.inline', ['message' => $errors->first('payee')])
                    </div> --}}
                </div>
                <div class="form-row mb-3">
                    {{-- <div class="col-sm-4 col-lg-4 mb-2">
                        <label for="">Cost Type</label>
                        <select name="cost_type_id" class="form-control @error('cost_type_id') is-invalid @enderror">
                            <option value="">No Cost Control No.</option>
                            @foreach ($cost_types as $item)
                                <option value="{{ $item->id }}" {{ $transaction->cost_type_id == $item->id ? 'selected' : '' }}>{{ $company->qb_code.'.'.$company->qb_no.$item->control_no.'.00*.'.config('global.cost_control_v').' - '.$item->name }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('cost_type_id')])
                    </div> --}}
                    <div class="col-sm-4 col-lg-4 mb-2">
                        <label for="">Cost Control No.</label>
                        <input type="text" class="form-control" name="cost_control_no" value="{{ $transaction->cost_control_no }}">
                        @include('errors.inline', ['message' => $errors->first('cost_control_no')])
                    </div>
                    <div class="col-sm-4 col-lg-4 mb-2">
                        <label for="">Due Date</label>
                        <input type="text" class="form-control" value="{{ $transaction->due_at }}" readonly>
                    </div>
                    <div class="col-sm-4 col-lg-4 mb-2">
                        <label for="">Requested by</label>
                        <input type="text" class="form-control" value="{{ $transaction->requested->name }}" readonly>
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-sm-4 col-lg-4 mb-2">
                        <label for="">Prepared by</label>
                        <h5>{{ $transaction->owner->name }}</h5>
                    </div>
                </div>
                <div class="form-row mb-3 d-none">
                    <div class="col-sm-4 col-lg-4 mb-2">
                        <label for="">Bill/Statement No.</label>
                        <input type="text" id="bill_statement_no" class="form-control @error('bill_statement_no') is-invalid @enderror" name="bill_statement_no" value="{{ $transaction->bill_statement_no }}">
                        @include('errors.inline', ['message' => $errors->first('bill_statement_no')])
                    </div>
                </div>
                {{-- <div class="form-row mb-3">
                    <div class="col-sm-4 col-lg-4 mb-2">
                        <label for="">Statement of Account / Billing / Quotation</label>
                        @if ($transaction->soa)
                            <a href="/storage/public/attachments/soa/{{ $transaction->soa }}" target="_blank" class="vlign--top ml-1">
                                <i class="material-icons mr-2 align-bottom">attachment</i>
                            </a>
                        @endif
                        <input type="file" name="soa" class="soa form_control" {{ $transaction->trans_type == 'po' && !$transaction->soa ? 'required' : '' }}>
                    </div>
                </div> --}}
                <div class="form-row mb-3">
                    {{-- <div class="col-md-2">
                        <label for="">For Deposit?</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('is_deposit') is-invalid @enderror" name="is_deposit" id="is_deposit" value="1" {{ $transaction->is_deposit == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_deposit">Yes</label>
                        </div>
                        @include('errors.inline', ['message' => $errors->first('is_deposit')])
                    </div> --}}
                    <div class="col-md-12 jsReplicate mt-5 pt-5">
                        <h4 class="text-center">Statement of Account / Billing / Quotation</h4>
                        <div class="text-center mb-3">Attach receipts and documents here. Accepts .jpg, .png and .pdf file types, not more than 5mb each.</div>
                        <div class="table-responsive">
                            <table class="table bg-white" style="min-width: 1000px">
                                <thead>
                                    <tr>
                                        <th class="w-25">File</th>
                                        <th class="w-75">Description</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="jsReplicate_container">
                                    @if (isset($transaction->transaction_soa[0]))
                                        <tr class="jsReplicate_template_item">
                                            <td>
                                                <a href="/storage/public/attachments/soa/{{ $transaction->transaction_soa[0]->file }}" target="_blank">
                                                    <i class="material-icons mr-2 align-bottom align-text-bottom">attachment</i>
                                                </a>
                                                <input type="file" name="file_old[]" class="form-control w-75 d-inline-block overflow-hidden">
                                                <input type="hidden" name="attachment_id_old[]" value="{{ $transaction->transaction_soa[0]->id }}">
                                            </td>
                                            <td><input type="text" name="attachment_description_old[]" class="form-control" value="{{ $transaction->transaction_soa[0]->description }}" required></td>
                                            <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                        </tr>
                                    @endif
                                    @foreach ($transaction->transaction_soa as $key => $item)
                                        @if ($key > 0)
                                            <tr class="jsReplicate_template_item">
                                                <td>
                                                    <a href="/storage/public/attachments/soa/{{ $item->file }}" target="_blank">
                                                        <i class="material-icons mr-2 align-bottom align-text-bottom">attachment</i>
                                                    </a>
                                                    <input type="file" name="file_old[]" class="form-control w-75 d-inline-block overflow-hidden">
                                                    <input type="hidden" name="attachment_id_old[]" value="{{ $item->id }}">
                                                </td>
                                                <td><input type="text" name="attachment_description_old[]" class="form-control" value="{{ $item->description }}" required></td>
                                                <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                        </div>
                    </div>

                    <!-- <div class="card col-md-12 mt-4">
                        <div class="card-header font-weight-bold">
                            Select Transaction Category
                        </div>
                        <div class="card-body pb-1 row">                            
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[0] }}" class="trans-category vlign--baseline-middle m-auto outline-0" {{ $transaction->is_deposit == 0 && $transaction->is_bills == 0 && $transaction->is_hr == 0 && $transaction->is_reimbursement == 0 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[0] }}</h6>
                                        <p class="d-none">Lorem ipsum dolor sit amet, consectetur, et dolore magna aliqua.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[1] }}" class="trans-category vlign--baseline-middle m-auto outline-0"  {{ $transaction->is_deposit == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[1] }}</h6>
                                        <p class="d-none">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[2] }}" class="trans-category vlign--baseline-middle m-auto outline-0"  {{ $transaction->is_bills == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[2] }}</h6>          
                                        <p class="d-none">Excepteur sint non proident, sunt in culpa qui mollit anim id.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[3] }}" class="trans-category vlign--baseline-middle m-auto outline-0"  {{ $transaction->is_hr == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[3] }}</h6>          
                                        <p class="d-none">Excepteur sint non proident, sunt in culpa qui mollit anim id.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[4] }}" class="trans-category vlign--baseline-middle m-auto outline-0"  {{ $transaction->is_reimbursement == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[4] }}</h6>          
                                        <p class="d-none">Excepteur sint non proident, sunt in culpa qui mollit anim id.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[5] }}" class="trans-category vlign--baseline-middle m-auto outline-0"  {{ $transaction->is_bank == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[5] }}</h6>          
                                        <p class="d-none">Excepteur sint non proident, sunt in culpa qui mollit anim id.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-md-12 text-center">
                        <div class="my-4">
                            <a href="/transaction/view/{{ $transaction->id }}" class="mr-3">Cancel</a>
                            <input type="submit" class="btn btn-primary" value="Save">
                        </div>
                    </div>
                </div>
            </form>

            <table class="d-none">
                <tbody class="jsReplicate_template">
                    <tr class="jsReplicate_template_item">
                        <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                        <td><input type="text" name="attachment_description[]" class="form-control" required></td>
                        <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            // $('.trans-category').change(function() {
            //     if ('{{ $transaction->trans_type }}' != 'po') {
            //         if ($(this).val() == 'bp') {
            //             $('.soa').parent().removeClass('d-none')
            //             $('.soa').removeAttr('required')
            //         } else {
            //             $('.soa').removeAttr('required')
            //         }
            //     }
            // })

            $('#purposeOption').change(function() {
                assignPurposeDescription()
            })
            
            function assignPurposeDescription() {
                $('#purposeDescription').val($('#purposeOption').find(':selected').data('description'))
            }
            
            if ("{{ $transaction->is_bills }}" === "1") {
                $('#bill_statement_no').parent().parent().removeClass('d-none')
                $('#bill_statement_no').attr('required', 'true')
            }

            $('.trans-category').change(function() {
                if ($(this).val() == "{{ config('global.trans_category')[2] }}") {
                    $('#bill_statement_no').parent().parent().removeClass('d-none')
                    $('#bill_statement_no').attr('required', 'true')
                } else {
                    $('#bill_statement_no').removeAttr('required')
                    $('#bill_statement_no').parent().parent().addClass('d-none')
                }
            })


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
        })
    </script>
@endsection