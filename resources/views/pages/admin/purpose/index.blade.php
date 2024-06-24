@extends('layouts.app')

@section('title', 'Purpose')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Purpose</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="4">
                            <div class="form-row">
                                @if (app('request')->input('company'))
                                    <input type="checkbox" id="purposeAll" class="vlign--middle ml-1 mr-2 p-3">
                                @endif
                                <div class="dropdown show pr-2">
                                    <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @if (app('request')->input('company'))
                                            {{ $companies->find(app('request')->input('company'))->code }}
                                        @else
                                            Select Company 
                                        @endif
                                    </a>
                                  
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="/purpose">Select Company</a>
                                        @foreach ($companies as $company)
                                            <a class="dropdown-item" href="?company={{ $company->id }}">{{ $company->code }}</a>
                                        @endforeach
                                    </div>
                                </div>
                                @if (app('request')->input('company'))
                                    <form action="purpose/batch" id="purposeCompanyForm" method="post">
                                        @csrf
                                        <input type="hidden" name="company_id" value="{{ app('request')->input('company') }}">
                                        <input type="submit" class="btn btn-sm btn-primary" value="Save">
                                    </form>
                                @endif
                            </div>
                        </th>
                        <th class="text-right"><a href="/purpose/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purposes as $item)
                        <tr>
                            <td>
                                @if (app('request')->input('company'))
                                    <input type="checkbox" name="purpose[]" form="purposeCompanyForm" value="{{ $item->id }}" class="purposeOption vlign--middle mr-2 p-3" {{ in_array(app('request')->input('company'), explode(',', $item->companies)) ? 'checked' : '' }}>
                                @endif
                                {{ $item->code }}
                            </td>
                            <td class="purpose-shorten">{{ $item->name }}</td>
                            <td class="purpose-shorten" title="{{ $item->description }}">{{ $item->description }}</td>
                            <td>
                                @foreach ($companies as $company)
                                    @if (in_array($company->id, explode(',', $item->companies)))
                                        <span class="badge badge-pill py-1 px-2 mt-2 small bg-gray">{{ $company->code }}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td class="text-right">
                                <a href="/purpose/{{ $item->id }}/edit" class="btn btn-link btn-sm d-inline-block">Edit</a>
                                <form action="/purpose/{{ $item->id }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('style')
    <style>
        .purpose-shorten {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            var purpose = $('#purposeAll')

            verifyChecked()

            purpose.click(() => {
                $('.purposeOption').prop('checked', purpose.is(':checked'))
            })
            
            $('.purposeOption').click(() => {
                verifyChecked()
            })

            function verifyChecked() {
                if ($('.purposeOption:checked').length == 0) {
                    purpose.prop('checked', false)
                } else if ($('.purposeOption').not(':checked').length == 0) {
                    purpose.prop('checked', true)
                }
            }
        })
    </script>
@endsection