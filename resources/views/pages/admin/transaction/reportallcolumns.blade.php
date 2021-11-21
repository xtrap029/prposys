@if (count(array_intersect($column_codes, [
    'CURR',
    'AMT',
    'AMT_CURR',
    'AMT_PAY',
    'AMT_PAY_CURR',
    'AMT_OR_PAY',
    'AMT_OR_PAY_CURR',
    'AMT_BAL',
    'AMT_BAL_CURR',
    'CURR_AMT_BAL',
    'AMT_BAL_LABEL',
    'CURR_AMT_BAL_RATE',
    'AMT_BAL_UNSIGNED',
    'AMT_BAL_UNSIGNED_CURR',
])) > 0)
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Currency</label>
        <select name="currency" class="form-control form-control-sm">
            <option value="">All</option>
            @foreach (config('global.currency') as $item)
                <option value="{{ $item }}" {{ app('request')->input('currency') == $item ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 my-1">
        <label for="">Amount</label>
        <select name="bal" class="form-control form-control-sm">
            <option value="">All</option>
            <option value="0" {{ app('request')->input('bal') != "" && app('request')->input('bal') == 0 ? 'selected' : '' }}>No Return</option>
            <option value="1" {{ app('request')->input('bal') == '1' ? 'selected' : '' }}>( + ) For Reimbursement</option>
            <option value="-1" {{ app('request')->input('bal') == '-1' ? 'selected' : '' }}>( - ) Return Money</option>
        </select>
    </div>
@endif

@if (count(array_intersect($column_codes, [
    'DEPO_TYPE',
    'DEPO_REF',
    'DEPO_DATE',
])) > 0)
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Deposit Type</label>
        <select name="depo_type" class="form-control form-control-sm">
            <option value="">All</option>
            @foreach (config('global.deposit_type') as $item)
                <option value="{{ $item }}" {{ app('request')->input('depo_type') == $item ? 'selected' : '' }}>{{ ucfirst(strtolower($item)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Depo. From</label>
        <input type="date" name="depo_from" class="form-control form-control-sm" value="{{ !empty($_GET['depo_from']) ? $_GET['depo_from'] : '' }}">
    </div>
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Depo. To</label>
        <input type="date" name="depo_to" class="form-control form-control-sm" value="{{ !empty($_GET['depo_to']) ? $_GET['depo_to'] : '' }}">
    </div>
@endif

@if (count(array_intersect($column_codes, [
    'DATE_DUE',
])) > 0)
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Due From</label>
        <input type="date" name="due_from" class="form-control form-control-sm" value="{{ !empty($_GET['due_from']) ? $_GET['due_from'] : '' }}">
    </div>
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Due To</label>
        <input type="date" name="due_to" class="form-control form-control-sm" value="{{ !empty($_GET['due_to']) ? $_GET['due_to'] : '' }}">
    </div>
@endif

@if (count(array_intersect($column_codes, [
    'DATE_REL',
    'USER_REL',
])) > 0)
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Released From</label>
        <input type="date" name="rel_from" class="form-control form-control-sm" value="{{ !empty($_GET['rel_from']) ? $_GET['rel_from'] : '' }}">
    </div>
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Released To</label>
        <input type="date" name="rel_to" class="form-control form-control-sm" value="{{ !empty($_GET['rel_to']) ? $_GET['rel_to'] : '' }}">
    </div>
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Released By</label>
        <select name="user_rel" class="form-control form-control-sm">
            <option value="">All</option>
            @foreach ($releasers as $item)
                <option value="{{ $item->id }}" {{ app('request')->input('user_rel') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
@endif

@if (count(array_intersect($column_codes, [
    'BANK',
    'BANK_BRANCH',
    'BANK_BANK_BRANCH',
])) > 0)
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Bank</label>
        <select name="bank" class="form-control form-control-sm">
            <option value="">All</option>
            @foreach ($banks as $item)
                <optgroup label="{{ $item->name }}">
                    @foreach ($item->bankbranches as $branch)
                        <option value="{{ $branch->id }}" {{ app('request')->input('bank') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>
@endif

@if (count(array_intersect($column_codes, [
    'USER_UPDATE',
    'USER_CREATE',
    'USER_REQ',
    'DATE_UPDATE',
    'DATE_CREATE',
])) > 0)
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Requested By</label>
        <select name="user_req" class="form-control form-control-sm">
            <option value="">All</option>
            @foreach ($users as $item)
                <option value="{{ $item->id }}" {{ app('request')->input('user_req') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
            <optgroup label="Inactive" class="bg-gray-light">
                @foreach ($users_inactive as $item)
                    <option value="{{ $item->id }}" {{ app('request')->input('user_req') == $item->id ? 'selected' : '' }} class="bg-gray-light">{{ $item->name }}</option>
                @endforeach
            </optgroup>
        </select>
    </div>
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Prepared By</label>
        <select name="user_prep" class="form-control form-control-sm">
            <option value="">All</option>
            @foreach ($users as $item)
                <option value="{{ $item->id }}" {{ app('request')->input('user_prep') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
            <optgroup label="Inactive" class="bg-gray-light">
                @foreach ($users_inactive as $item)
                    <option value="{{ $item->id }}" {{ app('request')->input('user_prep') == $item->id ? 'selected' : '' }} class="bg-gray-light">{{ $item->name }}</option>
                @endforeach
            </optgroup>
        </select>
    </div>
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Updated By</label>
        <select name="user_updated" class="form-control form-control-sm">
            <option value="">All</option>
            @foreach ($users as $item)
                <option value="{{ $item->id }}" {{ app('request')->input('user_updated') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
            <optgroup label="Inactive" class="bg-gray-light">
                @foreach ($users_inactive as $item)
                    <option value="{{ $item->id }}" {{ app('request')->input('user_updated') == $item->id ? 'selected' : '' }} class="bg-gray-light">{{ $item->name }}</option>
                @endforeach
            </optgroup>
        </select>
    </div>
@endif

@if (count(array_intersect($column_codes, [
    'USER_FORM_APPROVER',
])) > 0)
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Form Approved By</label>
        <select name="user_approver_form" class="form-control form-control-sm">
            <option value="">All</option>
            @foreach ($users as $item)
                <option value="{{ $item->id }}" {{ app('request')->input('user_approver_form') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
            <optgroup label="Inactive" class="bg-gray-light">
                @foreach ($users_inactive as $item)
                    <option value="{{ $item->id }}" {{ app('request')->input('user_approver_form') == $item->id ? 'selected' : '' }} class="bg-gray-light">{{ $item->name }}</option>
                @endforeach
            </optgroup>
        </select>
    </div>
@endif

@if (count(array_intersect($column_codes, [
    'FORM_VAT_CODE',
    'FORM_VAT_NAME',
    'FORM_VAT_RATE',
    'FORM_WHT',
    'FORM_VAT_AMT',
    'FORM_WHT_AMT',
    'FORM_PAY_AMT',
])) > 0)
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">VAT Type</label>
        <select name="vat_type" class="form-control form-control-sm">
            <option value="">All</option>
            @foreach ($vat_types as $item)
                <option value="{{ $item->id }}" {{ app('request')->input('vat_type') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
@endif

@if (count(array_intersect($column_codes, [
    'PARTICULARS',
])) > 0)
    <div class="col-sm-6 col-md-2 my-1">
        <label for="">Particulars</label>
        <select name="particulars" class="form-control form-control-sm">
            <option value="">All</option>
            @foreach ($particulars as $item)
                <option value="{{ $item->id }}" {{ app('request')->input('particulars') == $item->id ? 'selected' : '' }}>{{ strtoupper($item->type).' - '.$item->name }}</option>
            @endforeach
        </select>
    </div>
@endif

{{-- PROJ_NAME --}}
{{-- CTRL_NO --}}
{{-- CTRL_TYPE --}}
{{-- PAYEE --}}
{{-- PAYOR --}}
{{-- COA --}}
{{-- CANCEL_NO --}}
{{-- CANCEL_DESC --}}
{{-- USER_LIQ_APPROVER --}}
{{-- FORM_COMP_NAME --}}
{{-- FORM_S/C --}}
{{-- EXP_TYPE_DESC --}}
{{-- EXP_TYPE_NAME --}}