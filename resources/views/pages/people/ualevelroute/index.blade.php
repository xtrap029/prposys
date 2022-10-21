@extends('layouts.app-people')

@section('title', 'User Access Level Route')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Access Level Route</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="" method="post">
                @csrf
                @method('put')                
                <input type="submit" class="btn btn-block btn-primary mb-3" value="Save Changes">
                <div style="
                    overflow-x:auto;
                    max-height: 80vh;
                    height: 80vh;
                    overflow-y: scroll;
                ">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="border"
                                    style="width: 250px;
                                        min-width: 250px;
                                        position: sticky;
                                        left: 0;
                                        background-color: white;
                                        z-index: 2"
                                >Routes</th>
                                @foreach ($ua_levels as $key => $level)
                                    <?php $opacity = (100/$ua_levels->count()) * ($ua_levels->count()-$key); ?>
                                    @if ($level->id != config('global.ua_inactive'))
                                        <th class="text-center p-1 vlign--middle border" style="
                                            min-width: 150px;
                                            position: sticky;
                                            top: 0;
                                            background-color: rgb(135 135 135 / {{$opacity}}%);
                                        ">{{ $level->name }}</th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ua_routes as $route)
                                <tr class="{{ in_array($route->code, config('global.hidden_route_option')) ? 'd-none' : '' }}">
                                    <td 
                                        style="width: 250px;
                                            min-width: 250px;
                                            position: sticky;
                                            left: 0;
                                            background-color: white;"
                                    >{{ $route->name }}</td>
                                    @foreach ($ua_levels as $level)
                                        @if ($level->id != config('global.ua_inactive'))
                                            <td class="p-1 vlign--middle" style="min-width: 150px;">
                                                <input type="hidden" name="route[]" value="{{ $route->id }}">
                                                <input type="hidden" name="level[]" value="{{ $level->id }}">
                                                <?php
                                                    $selected = '';
                                                    $column = $ua_level_routes->where('ua_route_id', $route->id)->where('ua_level_id', $level->id);
                                                    if ($column->count() > 0) $selected = $column->first()->ua_route_option_id;
                                                ?>  
                                                <select name="option[]" class="form-control form-control-sm m-auto">
                                                    @foreach ($ua_options as $option)
                                                        @if (!$route->is_yesno || in_array($option->id, config('global.is_yesno_id')))
                                                            <option value="{{ $option->id }}" {{ $selected == $option->id ? 'selected' : '' }}>
                                                                @if ($route->is_yesno && $option->id == config('global.is_yesno_id')[0])
                                                                    Yes
                                                                @elseif ($route->is_yesno && $option->id == config('global.is_yesno_id')[1])
                                                                    No
                                                                @else
                                                                    {{ $option->name }}
                                                                @endif
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </section>
@endsection