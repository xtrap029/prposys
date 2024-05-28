@extends('layouts.app')

@section('title', 'Edit Liquidated '.strtoupper($transaction->trans_type))

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
                        <td class="font-weight-bold">Category / Class</td>
                        <td>{{ $transaction->coatagging->name }}</td>
                    </tr>
                </table>
                <div class="col-md-6">
                    <div>
                        <label for="" class="font-weight-bold">Purpose</label>
                        <p>
                            @if ($config_confidential)
                                -
                            @else
                                {{ $transaction->purpose_option_id ? ($transaction->purposeOption->code.' - '.$transaction->purposeOption->name) : '-' }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <label for="" class="font-weight-bold">Purpose Details</label>
                        <p>
                            @if ($config_confidential)
                                -
                            @else
                                {{ $transaction->purpose }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="pageForm" class="jsPreventMultiple">
                @csrf
                @method('put')

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
                <div class="jsReplicate jsMath mt-5 {{ $config_confidential ? 'd-none' : '' }}">
                    <h4 class="text-center">Items</h4>
                    <div class="table-responsive">
                        <table class="table bg-white" style="min-width: 1000px">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Project</th>
                                    <th>
                                        Type
                                        <a href="" class="align-bottom" data-toggle="modal" data-target="#modal-particulars">
                                            <i class="material-icons text-md align-middle pb-1">info</i>
                                        </a>
                                        <div class="modal fade" id="modal-particulars" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">Expense Types</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table font-weight-normal">
                                                            <tbody>
                                                                @foreach ($expense_types as $item)
                                                                    <tr>
                                                                        <td class="w-25 font-weight-bold">{{ $item->name }}</td>
                                                                        <td>{{ $item->notes }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th>Description</th>
                                    <th>Location/Route</th>
                                    <th class="text-center">Receipt</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="jsReplicate_container">
                                <tr>
                                    <td><input type="date" class="form-control" name="date[]" value="{{ $transaction->liquidation[0]->date }}" required></td>
                                    <td>
                                        <select name="project_id[]" class="form-control" required>
                                            @foreach ($projects as $item)
                                                <option value="{{ $item->id }}" {{ $transaction->liquidation[0]->project_id == $item->id ? 'selected' : (strtolower($item->project) == "none" ? 'selected' : '') }}>{{ $item->project }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="expense_type_id[]" class="form-control" required>
                                            @foreach ($expense_types as $item)
                                                <option value="{{ $item->id }}" {{  $transaction->liquidation[0]->expense_type_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="description[]" value="{{  $transaction->liquidation[0]->description }}" required></td>
                                    <td><input type="text" class="form-control" name="location[]" value="{{  $transaction->liquidation[0]->location }}" required></td>
                                    <td class="text-center">
                                        <select name="receipt[]" class="form-control">
                                            <option value="1" {{ $transaction->liquidation[0]->receipt == 1 ? 'selected' : '' }}>Y</option>
                                            <option value="0" {{ $transaction->liquidation[0]->receipt == 0 ? 'selected' : '' }}>N</option>
                                        </select>
                                    </td>
                                    <td colspan="2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ $transaction->currency }}</span>
                                            </div>
                                            <input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount[]" step="0.01" value="{{ $transaction->liquidation[0]->amount }}" required>
                                        </div>  
                                    </td>
                                </tr>
                                @foreach ($transaction->liquidation as $key => $item)
                                    @if ($key > 0)
                                        <tr class="jsReplicate_template_item">
                                            <td><input type="date" class="form-control" name="date[]" value="{{ $item->date }}" required></td>
                                            <td>
                                                <select name="project_id[]" class="form-control" required>
                                                    @foreach ($projects as $project)
                                                        <option value="{{ $project->id }}" {{ $item->project_id == $project->id ? 'selected' : (strtolower($item->project) == "none" ? 'selected' : '') }}>{{ $project->project }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="expense_type_id[]" class="form-control" required>
                                                    @foreach ($expense_types as $expense_type)
                                                        <option value="{{ $expense_type->id }}" {{ $item->expense_type_id == $expense_type->id ? 'selected' : '' }}>{{ $expense_type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control" name="description[]" value="{{ $item->description }}" required></td>
                                            <td><input type="text" class="form-control" name="location[]" value="{{ $item->location }}" required></td>
                                            <td class="text-center">
                                                <select name="receipt[]" class="form-control">
                                                    <option value="1" {{ $item->receipt == 1 ? 'selected' : '' }}>Y</option>
                                                    <option value="0" {{ $item->receipt == 0 ? 'selected' : '' }}>N</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ $transaction->currency }}</span>
                                                    </div>
                                                    <input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount[]" step="0.01" value="{{ $item->amount }}" required>
                                                </div>  
                                            </td>
                                            <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8">
                                        <div class="float-right">
                                            Total Amount: <span class="font-weight-bold jsMath_sum jsMath_alert">0</span> / <span class="font-weight-bold jsMath_validate">{{ number_format($transaction->amount_issued, 2, '.', ',') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                    </div>
                </div>
                <div class="jsReplicate mt-5 pt-5">
                    <h4 class="text-center">Attachments</h4>
                    <div class="text-center mb-3">Attach receipts and documents here. Accepts .jpg, .png and .pdf file types, not more than 5mb each.</div>
                    <div class="table-responsive">
                        <table class="table bg-white" style="min-width: 500px">
                            <thead>
                                <tr>
                                    <th class="w-25">File</th>
                                    <th class="w-75">Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="jsReplicate_container">
                                <tr class="jsReplicate_template_item">
                                    <td class="text-nowrap">
                                        <a href="/storage/public/attachments/liquidation/{{ $transaction->attachments[0]->file }}" target="_blank">
                                            <i class="material-icons mr-2 align-bottom align-text-bottom">attachment</i>
                                        </a>
                                        <input type="file" name="file_old[]" class="form-control w-75 d-inline-block overflow-hidden">
                                        <input type="hidden" name="attachment_id_old[]" value="{{ $transaction->attachments[0]->id }}">
                                    </td>
                                    <td><input type="text" name="attachment_description_old[]" class="form-control" value="{{ $transaction->attachments[0]->description }}" required></td>
                                    <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                </tr>
                                @foreach ($transaction->attachments as $key => $item)
                                    @if ($key > 0)
                                        <tr class="jsReplicate_template_item">
                                            <td>
                                                <a href="/storage/public/attachments/liquidation/{{ $item->file }}" target="_blank">
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
                <div class="mt-5 pt-5">
                    <div class="table-responsive">
                        <table class="table bg-secondary rounded" style="min-width: 1000px">
                            <tbody>
                                <tr>
                                    <td class="w-25">
                                        <div class="mb-1 font-weight-bold">Batch Upload</div>
                                        <input type="file" name="zip" id="inputZip" class="form-control overflow-hidden" accept=".zip">
                                    </td>
                                    <td class="w-75 vlign--middle text-info">{!! __('messages.batch_upload') !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 text-center text-lg-right my-5">
                    <a href="/transaction-liquidation/view/{{ $transaction->id }}" class="mr-3">Cancel</a>
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </form>

            <table class="d-none">
                <tbody class="jsReplicate_template">
                    <tr class="jsReplicate_template_item">
                        <td><input type="date" class="form-control" name="date[]" value="{{ $transaction->due_at }}" required></td>
                        <td>
                            <select name="project_id[]" class="form-control" required>
                                @foreach ($projects as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $transaction->project_id ? 'selected' : '' }}>{{ $item->project }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="expense_type_id[]" class="form-control" required>
                                @foreach ($expense_types as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="description[]" value="{{ $transaction->transaction_description[0]->description }}" required></td>
                        <td><input type="text" class="form-control" name="location[]" required></td>
                        <td class="text-center">
                            <select name="receipt[]" class="form-control">
                                <option value="1">Y</option>
                                <option value="0">N</option>
                            </select>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $transaction->currency }}</span>
                                </div>
                                <input type="number" class="form-control jsMath_amount jsMath_trigger" name="amount[]" step="0.01" required>
                            </div>  
                        </td>
                        <td><button type="button" class="btn btn-danger jsReplicate_remove jsMath_trigger"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                    </tr>
                </tbody>
            </table>

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

            if ($('.jsMath')[0]){
                cls_2 = '.jsMath'

                $(document).on('keyup click', cls_2+'_trigger', function() {
                    jsMatch_calc()
                })

                jsMatch_calc()

                function jsMatch_calc() {
                    sum = 0
                    $(cls_2+'_amount').each(function() {
                        sum += parseFloat($(this).val() != "" ? $(this).val() : 0)
                    })

                    $(cls_2+'_sum').text(sum.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","))

                    if ($(cls_2+'_sum').text() != $(cls_2+'_validate').text()) {
                        $(cls_2+'_alert').addClass('text-danger').removeClass('text-success')
                    } else {
                        $(cls_2+'_alert').removeClass('text-danger').addClass('text-success')
                    }
                }
            }

            $('#pageForm').submit(function() {
                if (!$('#inputZip').val() && $("input[name='attachment_description[]']").length <= 1 && $("input[name='attachment_description_old[]']").length == 0) {
                    alert('{{ __("messages.required_attachment") }}')
                    setTimeout(function() {
                        $('form.jsPreventMultiple [type="submit"]').removeAttr("disabled")
                    }, 1000);
                    return false
                }
            })
        })
    </script>
@endsection