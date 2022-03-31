@extends('layouts.app')

@section('title', 'Generate '.strtoupper($trans_type))

@section('content')
    <?php $ua = (new \App\Helpers\UAHelper)->get(); ?>
    <?php $non = config('global.ua_none'); ?>
    <?php $own = config('global.ua_own'); ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-6 mb-2">
                    <h1>
                        <img src="/storage/public/images/companies/{{ $company->logo }}" alt="" class="thumb--xs mr-2">
                        {{ $company->name }}
                    </h1>
                </div>
                <div class="col-lg-6 text-lg-right mb-2">
                    <h1>Generate {{ strtoupper($trans_type) }}</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/transaction/create" method="post" class="jsPreventMultiple" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="trans_type" value="{{ $trans_type }}">

                <div class="form-row mb-3">
                    <div class="col-sm-5 col-lg-7 mb-2">
                        <label for="">Project</label>
                        <select name="project_id" class="form-control @error('project_id') is-invalid @enderror">
                            @foreach ($projects as $item)
                                <option value="{{ $item->id }}" {{ $item->id == old('project_id') ? 'selected' : (isset($_GET['project_id']) && $item->id == $_GET['project_id'] ? 'selected' : (strtolower($item->project) == "none" ? 'selected' : '')) }}>{{ $item->project }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('project_id')])
                    </div>
                    <div class="col-4 col-sm-2 col-lg-1 mb-2">
                        <label for="">Currency</label>
                        <select name="currency" class="form-control @error('currency') is-invalid @enderror">
                            @foreach (config('global.currency') as $key => $item)
                                <option value="{{ config('global.currency_label')[$key] }}" {{ isset($_GET['currency']) && config('global.currency_label')[$key] == $_GET['currency'] ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('currency')])
                    </div>
                    <div class="col-8 col-sm-5 col-lg-4 mb-2">
                        <label for="">Amount</label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" step="0.01" value="{{ old('amount') ?: (isset($_GET['amount']) ? $_GET['amount'] : '') }}" required>
                        @include('errors.inline', ['message' => $errors->first('amount')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-sm-6 col-lg-8 mb-2">
                        <label for="">Purpose</label>
                        <textarea name="purpose" rows="1" class="form-control @error('purpose') is-invalid @enderror" required>{{ old('purpose') ?: (isset($_GET['purpose']) ? $_GET['purpose'] : '') }}</textarea>
                        @include('errors.inline', ['message' => $errors->first('purpose')])
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-2">
                        <label for="">Payee Name</label>
                        <input type="text" class="form-control @error('payee') is-invalid @enderror" name="payee" value="{{ old('payee') ?: (isset($_GET['payee']) ? $_GET['payee'] : '') }}" required>
                        @include('errors.inline', ['message' => $errors->first('payee')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-sm-6 col-lg-8 mb-2">
                        <label for="">Note</label>
                        <input type="text" class="form-control @error('note_content') is-invalid @enderror" name="note_content" value="{{ old('note_content') ?: (isset($_GET['note_content']) ? $_GET['note_content'] : '') }}">
                        @include('errors.inline', ['message' => $errors->first('note_content')])
                    </div>
                    <div class="col-sm-6 col-lg-4 mb-2">
                        <label for="">Due Date</label>
                        <input type="date" class="form-control @error('due_at') is-invalid @enderror" name="due_at" value="{{ old('due_at') ?: (isset($_GET['due_at']) ? $_GET['due_at'] : '') }}" required>
                        @include('errors.inline', ['message' => $errors->first('due_at')])
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-sm-4 col-lg-4 mb-2">
                        <label for="">Requested by</label>
                        <select name="requested_id" class="form-control @error('requested_id') is-invalid @enderror">
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}" {{ isset($_GET['requested_id']) && $_GET['requested_id'] == $item->id ? 'selected' : ($item->id == Auth::user()->id ? 'selected' : '') }}>{{ $item->name }}</option>                                        
                            @endforeach
                        </select>
                        @include('errors.inline', ['message' => $errors->first('requested_id')])
                    </div>
                    <div class="col-sm-4 col-lg-4 mb-2">
                        <label for="">Prepared by</label>
                        <h5>{{ Auth::user()->name }}</h5>
                    </div>
                    {{-- ALLOw ALL CATEGORIES --}}
                    {{-- <div class="col-sm-4 col-lg-4 mb-2 {{ $trans_type == 'po' ? '' : 'd-none' }}"> --}}
                    {{-- <div class="col-sm-4 col-lg-4 mb-2">
                        <label for="">Statement of Account/Invoice/Form</label>
                        <input type="file" name="soa" class="soa form_control" {{ $trans_type == 'po' ? 'required' : '' }}>
                    </div> --}}
                </div>
                <div class="form-row mb-3 {{ $ua['trans_toggle_conf'] == $non ? 'd-none' : '' }}">
                    <div class="col-sm-12 col-lg-4 mb-2">             
                        <label for="">Is Confidential?</label>
                        <select name="is_confidential" class="form-control">
                            <option value="0" selected>No</option>
                            <option value="1">Yes</option>
                        </select>     
                    </div>
                    <div class="col-lg-4 pt-3">
                        <code>Setting to "Yes" will make the transaction visible only to users with level higher than the creator.</code>                
                    </div>
                </div>
                <div class="form-row mb-3 {{ $ua['trans_toggle_conf_own'] == $non ? 'd-none' : '' }}">
                    <div class="col-sm-12 col-lg-4 mb-2">             
                        <label for="">Is Confidential (Own)?</label>
                        <select name="is_confidential_own" class="form-control">
                            <option value="0" selected>No</option>
                            <option value="1">Yes</option>
                        </select>                                                            
                    </div>
                    <div class="col-lg-4 pt-3">
                        <code>Setting to "Yes" will make the transaction visible only to creator.</code>                 
                    </div>
                </div>
                <div class="form-row mb-3">
                    {{-- <div class="col-md-2">
                        <label for="">For Deposit?</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('is_deposit') is-invalid @enderror" name="is_deposit" id="is_deposit" value="1" {{ old('is_deposit') == 'on' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_deposit">Yes</label>
                        </div>
                        @include('errors.inline', ['message' => $errors->first('is_deposit')])
                    </div> --}}
                    <div class="col-12 jsReplicate mt-5 pt-5">
                        <h4 class="text-center">Statement of Account/Invoice/Form</h4>
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
                                    <tr class="jsReplicate_template_item">
                                        <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                                        <td><input type="text" name="attachment_description[]" class="form-control" value="{{ old('attachment_description.0') }}" required></td>
                                        <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                    </tr>
                                    @if (old('attachment_description'))
                                        @foreach (old('attachment_description') as $key => $item)
                                            @if ($key > 0)
                                                <tr class="jsReplicate_template_item">
                                                    <td><input type="file" name="file[]" class="form-control overflow-hidden" required></td>
                                                    <td><input type="text" name="attachment_description[]" class="form-control" value="{{ old('attachment_description.'.$key) }}" required></td>
                                                    <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                        </div>
                    </div>

                    <div class="card col-md-12 mt-4">
                        <div class="card-header font-weight-bold">
                            Select Transaction Category
                        </div>
                        <div class="card-body pb-1 row">                            
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[0] }}" class="trans-category vlign--baseline-middle m-auto outline-0" checked>
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
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[1] }}" class="trans-category vlign--baseline-middle m-auto outline-0" {{ isset($_GET['is_deposit']) && $_GET['is_deposit'] == 1 ? 'checked' : '' }}>
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
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[2] }}" class="trans-category vlign--baseline-middle m-auto outline-0" {{ isset($_GET['is_bills']) && $_GET['is_bills'] == 1 ? 'checked' : '' }}>
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
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[3] }}" class="trans-category vlign--baseline-middle m-auto outline-0" {{ isset($_GET['is_hr']) && $_GET['is_hr'] == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[3] }}</h6>
                                        <p class="d-none">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[4] }}" class="trans-category vlign--baseline-middle m-auto outline-0" {{ isset($_GET['is_reimbursement']) && $_GET['is_reimbursement'] == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[4] }}</h6>
                                        <p class="d-none">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="callout py-1 mx-1 row">
                                    <div class="col-2">
                                        <input type="radio" name="trans_category" value="{{ config('global.trans_category')[5] }}" class="trans-category vlign--baseline-middle m-auto outline-0" {{ isset($_GET['is_bank']) && $_GET['is_bank'] == 1 ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-10 mt-2">
                                        <h6 class="font-weight-bold">{{ config('global.trans_category_label')[5] }}</h6>
                                        <p class="d-none">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="my-4">
                            <a href="/transaction/{{ $trans_page }}/{{ $trans_company }}" class="mr-3">Cancel</a>
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
            //     if ('{{ $trans_type }}' != 'po') {
            //         if ($(this).val() == 'bp') {
            //             $('.soa').parent().removeClass('d-none')
            //             $('.soa').removeAttr('required')
            //         } else {
            //             $('.soa').removeAttr('required')
            //         }
            //     }
            // })

            if ("{{ isset($_GET['is_bills']) && $_GET['is_bills'] == 1 }}" == 1) {
                $('.soa').parent().removeClass('d-none')
                $('.soa').removeAttr('required')
            }

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