@extends('layouts.app')

@section('title', 'COA Tagging')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>COA Tagging</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>List</th>
                        <th class="text-right"><a href="/coa-tagging/create">Create</a></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <p class="font-weight-bold">All</p>
                            @forelse ($coanull as $coatagging)
                                <div class="pl-5 py-1">
                                    {{ $coatagging->name }}
                                    
                                    <div class="float-none float-sm-right">
                                        <a href="/coa-tagging/{{ $coatagging->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                        <form action="/coa-tagging/{{ $coatagging->id }}" method="post" class="d-inline-block">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="pl-5 py-1">{{ __('messages.empty') }}</div>
                            @endforelse
                        </td>
                    </tr>
                    @forelse ($companies as $item)
                        <tr>
                            <td colspan="2">
                                <p class="font-weight-bold">{{ $item->name }}</p>
                                @forelse ($item->coataggings as $coatagging)
                                    <div class="pl-5 py-1">
                                        {{ $coatagging->name }}
                                        
                                        <div class="float-none float-sm-right">
                                            <a href="/coa-tagging/{{ $coatagging->id }}/edit" class="btn btn-link btn-sm">Edit</a>
                                            <form action="/coa-tagging/{{ $coatagging->id }}" method="post" class="d-inline-block">
                                                @csrf
                                                @method('delete')
                                                <input type="submit" class="btn btn-link btn-sm" value="Delete" onclick="return confirm('Are you sure?')">
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="pl-5 py-1">{{ __('messages.empty') }}</div>
                                @endforelse
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">{{ __('messages.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection