@extends('layouts.app-people')

@section('title', 'User Access Level')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Access Level</h1>
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
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ua_levels as $item)
                            <tr>
                                <td><code class="text-blue">{{ $item->code }}</code></td>
                                <td>
                                    <label for="" class="small">{{ $item->name }}</label>
                                    <input type="hidden" name="id[]" value="{{ $item->id }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="name[]" value="{{ $item->name }}" required>
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