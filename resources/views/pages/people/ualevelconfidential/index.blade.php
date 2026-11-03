@extends('layouts.app-people')

@section('title', 'Confidential Visibility')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Confidential Visibility</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <p class="text-gray">
                Set which user levels can view a level's <b>Confidential</b> transactions. The owner and requester can always view their transaction.
                If no level is checked, the level's confidential transactions are visible to all levels.
                <b>Confidential (Own)</b> transactions are not affected and remain visible to the owner and requester only.
            </p>
            <div style="
                overflow-x:auto;
                max-height: 80vh;
                overflow-y: auto;
            ">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="border"
                                style="width: 250px;
                                    min-width: 250px;
                                    position: sticky;
                                    left: 0;
                                    top: 0;
                                    background-color: white;
                                    z-index: 2"
                            >Owner Level <small class="text-gray d-block">Viewable by &rarr;</small></th>
                            @foreach ($ua_levels as $key => $level)
                                <?php $opacity = (100/$ua_levels->count()) * ($ua_levels->count()-$key); ?>
                                <th class="text-center p-1 vlign--middle border" style="
                                    min-width: 120px;
                                    position: sticky;
                                    top: 0;
                                    background-color: rgb(135 135 135 / {{$opacity}}%);
                                ">{{ $level->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ua_levels as $key => $level)
                            <?php $viewers = $ua_level_confidentials->where('ua_level_id', $level->id)->pluck('viewer_ua_level_id')->all(); ?>
                            <tr>
                                <td class="text--auto-expand" label="{{ $level->name }}">
                                    <form action="/ua-level-confidential" method="post" id="form{{ $key }}">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="level" value="{{ $level->id }}">
                                        @if ($can_manage)
                                            <input type="submit" class="btn btn-xs btn-primary vlign--top mr-2" value="Save">
                                        @endif
                                        {{ $level->name }}
                                        @if (count($viewers) == 0)
                                            <span class="badge badge-secondary ml-1">All levels</span>
                                        @endif
                                    </form>
                                </td>
                                @foreach ($ua_levels as $viewer)
                                    <td class="p-1 text-center vlign--middle" style="min-width: 120px;">
                                        <input type="checkbox"
                                            form="form{{ $key }}"
                                            name="viewer[]"
                                            value="{{ $viewer->id }}"
                                            {{ in_array($viewer->id, $viewers) ? 'checked' : '' }}
                                            {{ $can_manage ? '' : 'disabled' }}
                                        >
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('style')
    <style>
        .text--auto-expand {
            width: 250px;
            min-width: 250px;
            position: sticky;
            left: 0;
            background-color: white;
            white-space: nowrap;
            overflow: hidden;
            max-width: 250px;
            text-overflow: ellipsis;
        }
        .text--auto-expand:hover {
            max-width: unset;
        }
    </style>
@endsection
