@extends('layouts.app')

@section('title', 'Create Report Template')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Report Template</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/report-template" method="post">
                @csrf
                <div class="alert alert-default-success rounded d-none" role="alert">
                    <h4 class="alert-heading">Tutorial</h4>
                    <hr>
                    <p>...</p>
                </div>
                <div class="card">
                    <div class="card-body row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Template Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                @include('errors.inline', ['message' => $errors->first('name')])
                            </div>

                            <div class="form-group">
                                <label for=""><a href="/report-column" target="_blank">Available Columns</a></label>
                                <select class="multipleSelect" multiple>
                                    @foreach ($columns as $item)
                                        <option value="{{ $item->id }}"
                                            data-label="{{ $item->label }}"
                                            data-desc="{{ $item->description }}">
                                            {{ $item->label }} [{{ $item->name }}]
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="columnsSelected" name="columns" required>
                            </div>
                        </div>
                        <div class="table-responsive col-md-9">
                            <table class="table columnLabels">
                                <thead>
                                    <tr>
                                        <th style="width: 50px"></th>
                                        <th class="text-center font-weight-normal" style="width: 250px">Display Name</th>
                                        <th class="text-center font-weight-normal">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <a href="/report-template">Cancel</a>
                <input type="submit" class="btn btn-primary float-right" value="Save">
            </form>
        </div>
    </section>
@endsection

@section('script')
<script type="text/javascript">
    $(function() {
        $('.multipleSelect').fastselect()

        var columns = []

        $('.multipleSelect').change(function () {
            columnLength = $(this).val().length

            if (columnLength > columns.length) {     
                lastInput = $('.fstControls > .fstChoiceItem:eq('+(columnLength-1)+')').data('value')
                labelInput = $('.multipleSelect option[value="'+lastInput+'"]').data('label')
                descInput = $('.multipleSelect option[value="'+lastInput+'"]').data('desc')
                $('.columnLabels tbody').append(
                    '<tr>' +
                        '<td class="border-0 py-2 vlign--middle">' + '<i class="material-icons icon--list cursor-pointer text-gray">drag_indicator</i>' + '</td>' +
                        '<td id="label-'+lastInput+'" class="border-0 py-2">' +
                            '<input type="text" name="column_label[]" value="'+labelInput+'" ' +
                                'class="form-control form-control-sm text-center"' +
                                // 'class="form-control form-control-sm text-center tooltipable"' +
                                // 'style="min-width: 100px"' +
                                // 'data-toggle="tooltip" data-placement="top" title="'+descInput+'"' +
                            'required>' +
                            '<input type="hidden" name="column_id[]" value="'+lastInput+'" required>' +
                        '</td>' +
                        '<td class="border-0 py-2 vlign--middle">' +
                            descInput +
                        '</td>' +
                    '</tr>'
                )
            } else if ($(this).val().length < columns.length) {
                columnRemoved = _.difference(columns,$(this).val())[0]
                $('#label-'+columnRemoved).parent().remove()
            }

            columnSelected = $('.fstControls > .fstChoiceItem').map(function() { return $(this).data('value').toString(); }).get()
            $('#columnsSelected').val(columnSelected)

            columns = $(this).val()
        })

        // $('body').tooltip({
        //     selector: '.tooltipable'
        // })        
    })

    $('.columnLabels > tbody').sortable({
        placeholder: "bg-light"
    }).disableSelection()
</script>
@endsection