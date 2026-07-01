@extends('layouts.app-people')

@section('title', 'Changelog')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1>Changelog</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            @forelse ($changelogs as $item)
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="mb-0">
                            Version {{ $item->version }}
                            @foreach (explode(',', $item->type) as $type)
                                <span class="badge badge-{{ [
                                    'feature' => 'primary',
                                    'fix' => 'danger',
                                    'improvement' => 'info',
                                    'security' => 'warning',
                                ][$type] ?? 'secondary' }} ml-1">{{ ucfirst($type) }}</span>
                            @endforeach
                        </h5>
                        <small class="text-muted ml-auto">{{ $item->release_date->format('F d, Y') }}</small>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0 pl-4">
                            @foreach (explode("\n", $item->description) as $line)
                                @if (trim($line, "- \t"))
                                    <li>{{ ltrim(trim($line), '- ') }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">{{ __('messages.empty') }}</p>
            @endforelse
        </div>
    </section>
@endsection
