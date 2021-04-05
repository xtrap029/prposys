@extends('layouts.app')

@section('title', 'Edit Report Template')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Report Template</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/report-template/{{ $report_template->id }}" method="post">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-body row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">
                                    Template Name
                                    <i class="align-middle material-icons small text-primary"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        data-html="true"
                                        title="Examples: Reimbursement, Return Money, Fund Transfer">help</i>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $report_template->name }}" required>
                                @include('errors.inline', ['message' => $errors->first('name')])
                            </div>
                            <div class="form-group">
                                <label for="">
                                    <a href="/report-column" target="_blank">List of Available Fields</a>
                                    <i class="align-middle material-icons small text-primary"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    data-html="true"
                                    title="Please choose the desired columns of your custom<br> template. You may also remove columns by clicking the X <br>button beside the column's name.">help</i>
                                </label>
                                <select class="multipleSelect" multiple>
                                    @foreach ($columns as $item)
                                        <option value="{{ $item->id }}"
                                            data-label="{{ $item->label }}"
                                            data-desc="{{ $item->description }}"
                                            {{ (in_array($item->id, $selected_columns)) ? 'selected' : '' }}
                                        >
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
                                        <th class="text-center font-weight-normal" style="width: 250px">
                                            Display Name
                                            <i class="align-middle material-icons small text-primary"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                data-html="true"
                                                title="<b>Rename your fields:</b> Just double click on the fields <br>name and rename as desired.<br><br>
                                                    <b>Reorder your columns:</b> Just drag the <i class='material-icons icon--list text-gray'>drag_indicator</i> on the left side of<br>
                                                    a column and move it to the desired position.">help</i>
                                        </th>
                                        <th class="text-center font-weight-normal">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach ($report_template->templatecolumn as $item)
                                        <tr>
                                            <td class="border-0 py-2 vlign--middle">
                                                <i class="material-icons icon--list cursor-pointer text-gray">drag_indicator</i>
                                            </td>
                                            <td id="label-{{ $item->column->id }}" class="border-0 py-2">
                                                <input type="text" name="column_label[]" value="{{ $item->label }}"
                                                    class="form-control form-control-sm text-center"
                                                required>
                                                <input type="hidden" name="column_id[]" value="{{ $item->column->id }}" required>
                                            </td>
                                            <td class="border-0 py-2 vlign--middle">{{ $item->column->description }}</td>
                                        </tr>
                                    @endforeach
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
        $('.multipleSelect').fastselect({
            placeholder: 'Choose your fields here'
        })

        var columns = []
        // var columnNames = '{{ $report_template->selected_name }}'.split(',').reverse()
        var selectedColumns = <?php echo json_encode($selected_columns); ?>.reverse()

        $.each(selectedColumns, function(i,e){
            $('.fstChoiceItem[data-value="'+e+'"]').prependTo(".fstControls")

            if (i == selectedColumns.length - 1) {
                $('.fstChoiceItem:last()').clone().insertBefore( ".fstQueryInput" )
                

            }
        })

        setTimeout(function () {
            $('.fstChoiceItem:last()').attr('data-value', 1000)
            $('.fstChoiceItem:last() .fstChoiceRemove').trigger('click')
            $('.columnLabels > tbody > tr:last()').remove()
        }, 300);

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
        
        $('[data-toggle="tooltip"]').tooltip()

        // $('body').tooltip({
        //     selector: '.tooltipable'
        // })
    })

    $('.columnLabels > tbody').sortable({
        placeholder: "bg-light"
    }).disableSelection()
</script>
@endsection