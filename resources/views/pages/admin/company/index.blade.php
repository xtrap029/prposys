@extends('layouts.app')

@section('title', 'Company')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Company</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-responsive-sm">
                <thead>
                    <tr>
                        <th colspan="2" style="min-width: 350px;">
                            <form class="form-inline">
                                <input type="text" class="form-control form-control-sm" name="s" value="{{ isset($_GET['s']) ? $_GET['s'] : '' }}" placeholder="Search">
                                <button type="submit" class="btn btn-primary ml-2 btn-sm">Search</button>
                                <a href="/company" class="btn btn-default ml-2 btn-sm">Clear</a>
                            </form>
                        </th>
                        <th class="text-nowrap text-center">Bill Option</th>
                        <th class="text-nowrap text-center">Auto-gen. PO SPV</th>
                        <th class="text-right"><a href="/company/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($companies as $item)
                        <tr>
                            <td><img src="storage/public/images/companies/{{ $item->logo }}" alt="" class="thumb thumb--xs"></td>
                            <td class="align-middle text-nowrap">
                                {{ $item->name }}
                                <div class="text-info">{{ $item->code }} / {{ $item->qb_code }} / {{ $item->qb_no }}</div>
                            </td>
                            <td class="align-middle">
                                <a href="/company/{{ $item->id }}/bill-option?page={{ $companies->currentPage() }}" class="text-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" {{ $item->bill_option == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label"></label>
                                    </div>
                                </a>
                            </td>                       
                            <td class="align-middle">
                                <a href="/company/{{ $item->id }}/auto-gen/po?page={{ $companies->currentPage() }}" class="text-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" {{ $item->auto_gen_po == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label"></label>
                                    </div>
                                </a>
                            </td>
                            <td class="align-middle text-right text-nowrap">
                                <a href="/company-project/{{ $item->id }}" class="btn btn-link btn-sm">Projects</a>
                                <a href="/company/{{ $item->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                <form action="/company/{{ $item->id }}" method="post" class="d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-0"></td>
                            <td class="align-middle text-nowrap pt-0 border-0">
                                @foreach (config('global.trans_category') as $key => $category)
                                    @if (in_array($category, explode(',', $item->categories)))
                                        <span class="badge badge-pill py-1 px-2 mb-3 small bg-gray">{{ config('global.trans_category_label')[$key] }}</span>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="overflow-auto position-relative text-center mt-3">
                <div class="d-inline-block">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection