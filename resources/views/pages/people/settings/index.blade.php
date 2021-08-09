@extends('layouts.app-people')

@section('title', 'Settings')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Settings</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="/people-settings" method="post" enctype="multipart/form-data">
                @csrf
                @foreach ($settings as $item)
                    @switch($item->type)
                            @case('SESSION_LIFETIME')
                                <div class="form-row mb-3">
                                    <div class="col-sm-6 col-md-4">
                                        <label for="">Auto Logout if Inactive</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 align-self-end">
                                        <label for="">Minutes</label>
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4">
                                        <input type="number" class="form-control @error('SESSION_LIFETIME') is-invalid @enderror" name="SESSION_LIFETIME" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('SESSION_LIFETIME')])
                                    </div>
                                </div>
                            @break
                            @case('FOOTER_LABEL')
                                <div class="form-row mb-3">
                                    <div class="col-md-8">
                                        <label for="">Footer Text</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control @error('FOOTER_LABEL') is-invalid @enderror" name="FOOTER_LABEL" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('FOOTER_LABEL')])
                                    </div>
                                </div>
                            @break
                            @case('SITE_LOGO')
                                <hr>
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Sequence Icon
                                            <div class="small">Image with 500kb max size</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 mb-2 align-self-end">
                                        <img src="/storage/public/images/site settings/{{ $item->value }}" class="img-size-32" alt="">
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <input type="file" name="SITE_LOGO" class="form-control-file @error('SITE_LOGO') is-invalid @enderror d-inline-block mt-2">
                                        @include('errors.inline', ['message' => $errors->first('SITE_LOGO')])
                                    </div>
                                </div>
                            @break
                            @case('SITE_COLOR')
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-8">
                                        <label for="">
                                            Sequence Color
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <select name="SITE_COLOR" class="form-control">
                                            @foreach (config('global.site_colors') as $item2)
                                                <option class="option--{{ $item2 }}" {{ $item->value == $item2 ? 'selected' : '' }} value="{{ $item2 }}">{{ $item2 }}</option>                        
                                            @endforeach
                                        </select>
                                        @include('errors.inline', ['message' => $errors->first('SITE_COLOR')])
                                    </div>
                                </div>
                            @break
                            @case('SITE_LOGO_LEAVES')
                                <hr>
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Leaves Icon
                                            <div class="small">Image with 500kb max size</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 mb-2 align-self-end">
                                        <img src="/storage/public/images/site settings/{{ $item->value }}" class="img-size-32" alt="">
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <input type="file" name="SITE_LOGO_LEAVES" class="form-control-file @error('SITE_LOGO_LEAVES') is-invalid @enderror d-inline-block mt-2">
                                        @include('errors.inline', ['message' => $errors->first('SITE_LOGO_LEAVES')])
                                    </div>
                                </div>
                            @break
                            @case('SITE_COLOR_LEAVES')
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-8">
                                        <label for="">
                                            Leaves Color
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <select name="SITE_COLOR_LEAVES" class="form-control">
                                            @foreach (config('global.site_colors') as $item2)
                                                <option class="option--{{ $item2 }}" {{ $item->value == $item2 ? 'selected' : '' }} value="{{ $item2 }}">{{ $item2 }}</option>                        
                                            @endforeach
                                        </select>
                                        @include('errors.inline', ['message' => $errors->first('SITE_COLOR_LEAVES')])
                                    </div>
                                </div>
                            @break
                            @case('SITE_LOGO_PEOPLE')
                                <hr>
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            People Icon
                                            <div class="small">Image with 500kb max size</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 mb-2 align-self-end">
                                        <img src="/storage/public/images/site settings/{{ $item->value }}" class="img-size-32" alt="">
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <input type="file" name="SITE_LOGO_PEOPLE" class="form-control-file @error('SITE_LOGO_PEOPLE') is-invalid @enderror d-inline-block mt-2">
                                        @include('errors.inline', ['message' => $errors->first('SITE_LOGO_PEOPLE')])
                                    </div>
                                </div>
                            @break
                            @case('SITE_COLOR_PEOPLE')
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-8">
                                        <label for="">
                                            People Color
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <select name="SITE_COLOR_PEOPLE" class="form-control">
                                            @foreach (config('global.site_colors') as $item2)
                                                <option class="option--{{ $item2 }}" {{ $item->value == $item2 ? 'selected' : '' }} value="{{ $item2 }}">{{ $item2 }}</option>                        
                                            @endforeach
                                        </select>
                                        @include('errors.inline', ['message' => $errors->first('SITE_COLOR_PEOPLE')])
                                    </div>
                                </div>
                            @break                         
                            @default
                    @endswitch            
                @endforeach
                <div class="py-5 text-right">
                    <input type="submit" class="btn btn-primary" value="Save"> 
                </div>
            </form>
        </div>
    </section>
@endsection