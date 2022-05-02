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
            <form action="" method="post" class="table-responsive">
                @csrf
                @method('put')                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="10" class="text-right border-0">
                                <input type="submit" class="btn btn-xs btn-primary" value="Save Changes">
                            </th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>Routes</th>
                            @foreach ($ua_levels as $level)
                                @if ($level->id != config('global.ua_inactive'))
                                    <th class="text-center">{{ $level->name }}</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ua_routes as $route)
                            <tr class="{{ in_array($route->code, config('global.hidden_route_option')) ? 'd-none' : '' }}">
                                <td class="text-nowrap">{{ $route->name }}</td>
                                @foreach ($ua_levels as $level)
                                    @if ($level->id != config('global.ua_inactive'))
                                        <td>
                                            <input type="hidden" name="route[]" value="{{ $route->id }}">
                                            <input type="hidden" name="level[]" value="{{ $level->id }}">
                                            <select name="option[]" class="form-control form-control-sm m-auto">
                                                @foreach ($ua_options as $option)
                                                    <?php
                                                        $selected = '';
                                                        $column = $ua_level_routes->where('ua_route_id', $route->id)->where('ua_level_id', $level->id);
                                                        if ($column->count() > 0) $selected = $column->first()->ua_route_option_id;
                                                    ?>  
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
                    <tfoot>
                        <tr>
                            <td colspan="10" class="text-right">
                                <input type="submit" class="btn btn-xs btn-primary" value="Save Changes">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </section>
@endsection