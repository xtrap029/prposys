@extends('layouts.app')

@section('title', 'Edit Liquidated '.strtoupper($transaction->trans_type))

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
            <form action="" method="post" enctype="multipart/form-data">
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
                                <td><input type="date" class="form-control" name="date[]" value="{{ $transaction->liquidation[0]->date }}" required></td>
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
                                <td colspan="2"><input type="number" class="form-control" name="amount[]" step="0.01" value="{{ $transaction->liquidation[0]->amount }}" required></td>
                            </tr>
                            @foreach ($transaction->liquidation as $key => $item)
                                @if ($key > 0)
                                    <tr class="jsReplicate_template_item">
                                        <td><input type="date" class="form-control" name="date[]" value="{{ $item->date }}" required></td>
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
                                        <td><input type="number" class="form-control" name="amount[]" step="0.01" value="{{ $item->amount }}" required></td>
                                        <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                    </div>
                </div>
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
                                <td>
                                    <a href="/storage/public/attachments/liquidation/{{ $transaction->attachments[0]->file }}" target="_blank">
                                        <i class="material-icons mr-2 align-bottom align-text-bottom">attachment</i>
                                    </a>
                                    <input type="file" name="file_old[]" class="form-control w-75 d-inline-block">
                                    <input type="hidden" name="attachment_id_old[]" value="{{ $transaction->attachments[0]->id }}">
                                </td>
                                <td colspan="2"><input type="text" name="attachment_description_old[]" class="form-control" value="{{ $transaction->attachments[0]->description }}" required></td>
                            </tr>
                            @foreach ($transaction->attachments as $key => $item)
                                @if ($key > 0)
                                    <tr class="jsReplicate_template_item">
                                        <td>
                                            <a href="/storage/public/attachments/liquidation/{{ $item->file }}" target="_blank">
                                                <i class="material-icons mr-2 align-bottom align-text-bottom">attachment</i>
                                            </a>
                                            <input type="file" name="file_old[]" class="form-control w-75 d-inline-block">
                                            <input type="hidden" name="attachment_id_old[]" value="{{ $item->id }}">
                                        </td>
                                        <td><input type="text" name="attachment_description_old[]" class="form-control" value="{{ $item->description }}" required></td>
                                        <td><button type="button" class="btn btn-danger jsReplicate_remove"><i class="nav-icon material-icons icon--list">delete</i></button></td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary jsReplicate_add"><i class="nav-icon material-icons icon--list">add_box</i> Add More</button>
                    </div>
                </div>
                <div class="text-center mt-5 py-5 border-top">
                    <a href="/transaction-liquidation/{{ $trans_page_url }}/{{ $transaction->project->company_id }}" class="mr-3">Cancel</a>
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </form>

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

            <table class="d-none">
                <tbody class="jsReplicate_template">
                    <tr class="jsReplicate_template_item">
                        <td><input type="file" name="file[]" class="form-control" required></td>
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

        })
    </script>
@endsection