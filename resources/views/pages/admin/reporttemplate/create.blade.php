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
                <div class="form-group">
                    <label for="">Template Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                    @include('errors.inline', ['message' => $errors->first('name')])
                </div>
                <div class="card">
                    <div class="card-body row">
                        <div class="form-group col-md-3">
                            <label for="" class="py-2"><a href="/report-column" target="_blank">Available Columns</a></label>
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
                        <div class="table-responsive col-md-9">
                            <table class="table columnLabels">
                                <thead>
                                    <tr>
                                        <th colspan="99" class="text-center font-weight-normal">Report Columns</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    <tr></tr>
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
                $('.columnLabels tbody tr').append(
                    '<td id="label-'+lastInput+'">' +
                        '<input type="text" name="column_label[]" value="'+labelInput+'" ' +
                            'class="form-control form-control-sm text-center tooltipable"' +
                            'style="min-width: 100px"' +
                            'data-toggle="tooltip" data-placement="top" title="'+descInput+'"' +
                        'required>' +
                    '</td>'
                )
            } else if ($(this).val().length < columns.length) {
                columnRemoved = _.difference(columns,$(this).val())[0]
                $('#label-'+columnRemoved).remove()
            }

            columnSelected = $('.fstControls > .fstChoiceItem').map(function() { return $(this).data('value').toString(); }).get()
            $('#columnsSelected').val(columnSelected)

            columns = $(this).val()
        })

        $('body').tooltip({
            selector: '.tooltipable'
        })
    })
</script>
@endsection