@extends('layouts.app-resources')

@section('title', 'FAQs')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>FAQs</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">  
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($faqs as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->category }}</td>
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