@extends('layouts.app')

@section('title', 'Report Column')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Report Column</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="" method="post" class="table-responsive">
                @csrf
                @method('put')                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Label</th>
                            <th>
                                <div class="float-lg-right">
                                    <input type="submit" class="btn btn-xs btn-primary" value="Save Changes">
                                </div>
                                Description
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report_columns as $item)
                            <tr>
                                <td><code class="text-blue">{{ $item->name }}</code></td>
                                <td>
                                    <label for="" class="small">{{ $item->label }}</label>
                                    <input type="hidden" name="id[]" value="{{ $item->id }}">
                                    <input type="text" class="form-control form-control-sm" name="label_2[]" value="{{ $item->label_2 ?: $item->label }}" required>
                                </td>
                                <td>
                                    <label for="" class="small">{{ $item->description }}</label>
                                    <textarea name="description_2[]" class="form-control form-control-sm" rows="1" required>{{ $item->description_2 ?: $item->description }}</textarea>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">{{ __('messages.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right">
                                <input type="submit" class="btn btn-xs btn-primary" value="Save Changes">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </section>
@endsection