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
            <form action="/people-settings" method="post" enctype="multipart/form-data" class="jsPreventMultiple">
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
                            @case('SITE_LOGO_RESOURCES')
                                <hr>
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Resources Icon
                                            <div class="small">Image with 500kb max size</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 mb-2 align-self-end">
                                        <img src="/storage/public/images/site settings/{{ $item->value }}" class="img-size-32" alt="">
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <input type="file" name="SITE_LOGO_RESOURCES" class="form-control-file @error('SITE_LOGO_RESOURCES') is-invalid @enderror d-inline-block mt-2">
                                        @include('errors.inline', ['message' => $errors->first('SITE_LOGO_RESOURCES')])
                                    </div>
                                </div>
                            @break
                            @case('SITE_COLOR_RESOURCES')
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-8">
                                        <label for="">
                                            Resources Color
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <select name="SITE_COLOR_RESOURCES" class="form-control">
                                            @foreach (config('global.site_colors') as $item2)
                                                <option class="option--{{ $item2 }}" {{ $item->value == $item2 ? 'selected' : '' }} value="{{ $item2 }}">{{ $item2 }}</option>                        
                                            @endforeach
                                        </select>
                                        @include('errors.inline', ['message' => $errors->first('SITE_COLOR_RESOURCES')])
                                    </div>
                                </div>
                            @break    
                            @case('SITE_LOGO_TRAVELS')
                                <hr>
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Travels Icon
                                            <div class="small">Image with 500kb max size</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 mb-2 align-self-end">
                                        <img src="/storage/public/images/site settings/{{ $item->value }}" class="img-size-32" alt="">
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <input type="file" name="SITE_LOGO_TRAVELS" class="form-control-file @error('SITE_LOGO_TRAVELS') is-invalid @enderror d-inline-block mt-2">
                                        @include('errors.inline', ['message' => $errors->first('SITE_LOGO_TRAVELS')])
                                    </div>
                                </div>
                            @break
                            @case('SITE_COLOR_TRAVELS')
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-8">
                                        <label for="">
                                            Travels Color
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <select name="SITE_COLOR_TRAVELS" class="form-control">
                                            @foreach (config('global.site_colors') as $item2)
                                                <option class="option--{{ $item2 }}" {{ $item->value == $item2 ? 'selected' : '' }} value="{{ $item2 }}">{{ $item2 }}</option>                        
                                            @endforeach
                                        </select>
                                        @include('errors.inline', ['message' => $errors->first('SITE_COLOR_TRAVELS')])
                                    </div>
                                </div>
                            @break 
                            @case('SITE_LOGO_LOGOUT')
                                <hr>
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Logout Icon
                                            <div class="small">Image with 500kb max size</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 mb-2 align-self-end">
                                        <img src="/storage/public/images/site settings/{{ $item->value }}" class="img-size-32" alt="">
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <input type="file" name="SITE_LOGO_LOGOUT" class="form-control-file @error('SITE_LOGO_LOGOUT') is-invalid @enderror d-inline-block mt-2">
                                        @include('errors.inline', ['message' => $errors->first('SITE_LOGO_LOGOUT')])
                                    </div>
                                </div>
                            @break   
                            @case('SITE_BANNER_LOGIN')
                                <hr>
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Login Banner
                                            <div class="small">Image with 15000kb max size</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 mb-2 align-self-end">
                                        <img src="/storage/public/images/site settings/{{ $item->value }}" class="img-size-64" alt="">
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <input type="file" name="SITE_BANNER_LOGIN" class="form-control-file @error('SITE_BANNER_LOGIN') is-invalid @enderror d-inline-block mt-2">
                                        @include('errors.inline', ['message' => $errors->first('SITE_BANNER_LOGIN')])
                                    </div>
                                </div>
                            @break
                            @case('SITE_BANNER_LOGIN_DRIVEID')
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Login Banner ID
                                            <div class="small">Google Drive link ID (leave blank if manual upload)</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 mb-2 align-self-end">
                                        @if ($item->value != '')                                        
                                            <img src="http://drive.google.com/uc?export=view&id={{ $item->value }}" class="img-size-64" alt="">
                                        @endif
                                    </div>
                                    <div class="col-8 col-sm-3">
                                        <input type="text" name="SITE_BANNER_LOGIN_DRIVEID" class="form-control @error('SITE_BANNER_LOGIN_DRIVEID') is-invalid @enderror d-inline-block mt-2" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('SITE_BANNER_LOGIN_DRIVEID')])
                                    </div>
                                    <div class="col-4 col-sm-1">
                                        <a href="#_" class="btn btn-default btn-block mt-2" data-toggle="modal" data-target="#modal-media">
                                            <i class="nav-icon material-icons icon--list">folder</i> Open
                                        </a>
                                    </div>
                                </div>
                            @break
                            @case('SITE_BANNER_HOME')
                                <hr>
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Home Banner
                                            <div class="small">Image with 15000kb max size</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 mb-2 align-self-end">
                                        <img src="/storage/public/images/site settings/{{ $item->value }}" class="img-size-64" alt="">
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <input type="file" name="SITE_BANNER_HOME" class="form-control-file @error('SITE_BANNER_HOME') is-invalid @enderror d-inline-block mt-2">
                                        @include('errors.inline', ['message' => $errors->first('SITE_BANNER_HOME')])
                                    </div>
                                </div>
                            @break
                            @case('SITE_BANNER_HOME_DRIVEID')
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Home Banner ID
                                            <div class="small">Google Drive link ID (leave blank if manual upload)</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4 text-right pr-md-5 mb-2 align-self-end">
                                        @if ($item->value != '')                                        
                                            <img src="http://drive.google.com/uc?export=view&id={{ $item->value }}" class="img-size-64" alt="">
                                        @endif
                                    </div>
                                    <div class="col-8 col-sm-3">
                                        <input type="text" name="SITE_BANNER_HOME_DRIVEID" class="form-control @error('SITE_BANNER_HOME_DRIVEID') is-invalid @enderror d-inline-block mt-2" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('SITE_BANNER_HOME_DRIVEID')])
                                    </div>
                                    <div class="col-4 col-sm-1">
                                        <a href="#_" class="btn btn-default btn-block mt-2" data-toggle="modal" data-target="#modal-media">
                                            <i class="nav-icon material-icons icon--list">folder</i> Open
                                        </a>
                                    </div>
                                </div>
                            @break
                            @case('SITE_DASHBOARD_SLIDER')
                                <hr>
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Dashboard Slider
                                            <div class="small">
                                                Separate each image url by adding new line
                                                <code class="d-block">
                                                    link.com/image.png<br>
                                                    link.com/image2.jpg<br>
                                                    link.com/image3.png<bt>
                                                </code>
                                            </div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4"></div>
                                    <div class="col-sm-4">
                                        <textarea name="SITE_DASHBOARD_SLIDER"
                                            class="form-control @error('SITE_DASHBOARD_SLIDER') is-invalid @enderror d-inline-block mt-2" 
                                            rows="5" required>{{ $item->value }}</textarea>
                                        @include('errors.inline', ['message' => $errors->first('SITE_DASHBOARD_SLIDER')])
                                    </div>
                                </div>
                            @break      
                            @case('SITE_LOGIN_GREETING')
                                <hr>
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Login Greeting
                                            <div class="small">
                                                Separate each phrase by adding new line
                                                <code class="d-block">
                                                    Hi there!<br>
                                                    Hello!<br>
                                                    Ola!<bt>
                                                </code>
                                            </div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4"></div>
                                    <div class="col-sm-4">
                                        <textarea name="SITE_LOGIN_GREETING"
                                            class="form-control @error('SITE_LOGIN_GREETING') is-invalid @enderror d-inline-block mt-2" 
                                            rows="5" required>{{ $item->value }}</textarea>
                                        @include('errors.inline', ['message' => $errors->first('SITE_LOGIN_GREETING')])
                                    </div>
                                </div>
                            @break
                            @case('MEDIA_SOURCE')
                                <hr>
                                <div class="form-row mb-3">
                                    <div class="col-6 col-sm-4">
                                        <label for="">
                                            Media Source
                                            <div class="small">Shareable google drive folder id</div>
                                        </label>                                        
                                    </div>
                                    <div class="col-6 col-sm-3 col-md-4"></div>
                                    <div class="col-6 col-sm-4">
                                        <input type="text" name="MEDIA_SOURCE" class="form-control @error('MEDIA_SOURCE') is-invalid @enderror d-inline-block mt-2" value="{{ $item->value }}">
                                        @include('errors.inline', ['message' => $errors->first('MEDIA_SOURCE')])
                                    </div>
                                </div>
                            @break                  
                            @default
                    @endswitch            
                @endforeach

                <hr>
                <div class="form-row mb-3">
                    <div class="col-sm-6 col-md-4">
                        <label for="">External Applications</label>
                    </div>
                    <div class="col-6 col-sm-3 col-md-2"></div>
                    <div class="col-12 col-sm-3 col-md-6 jsReplicate">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Icon <small>(~500kb)</small></th>
                                    <th>Path</th>
                                    <th style="width: 100px;"><button type="button" class="btn btn-success btn-block jsReplicate_add">Add</button></th>
                                </tr>
                            </thead>
                            <tbody class="jsReplicate_container">
                                @foreach ($app_externals as $item)
                                    <tr class="jsReplicate_template_item">
                                        <td>
                                            <input type="hidden" name="app_externals_id[]" value="{{ $item->id }}">
                                            <input class="form-control" name="app_externals_name[]" value="{{ $item->name }}" required>
                                        </td>
                                        <td>
                                            <img src="/storage/public/images/app-externals/{{ $item->icon }}" alt="" class="img-size-32 d-inline-block">
                                            <input type="file" name="app_externals_icon[]" class="form-control-file d-inline-block w-75">
                                        </td>
                                        <td>
                                            <input class="form-control" name="app_externals_path[]" value="{{ $item->url }}" required>
                                        </td>
                                        <td><button type="button" class="btn btn-danger btn-block jsReplicate_remove">Remove</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="py-5 text-right">
                    <input type="submit" class="btn btn-primary" value="Save"> 
                </div>
            </form>

            <table class="d-none">
                <tbody class="jsReplicate_template">
                    <tr class="jsReplicate_template_item">
                        <td>
                            <input class="form-control" name="app_externals_name[]" required>
                        </td>
                        <td>
                            <input type="file" name="app_externals_icon[]" class="form-control-file" required>
                        </td>
                        <td>
                            <input class="form-control" name="app_externals_path[]" required>
                        </td>
                        <td><button type="button" class="btn btn-danger btn-block jsReplicate_remove">Remove</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <div class="modal fade" id="modal-media" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Media Source</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-0">
                    <div class="card direct-chat direct-chat-info mb-0">
                        <div class="card-body">
                            <div class="alert alert-default-info rounded" role="alert">
                                <h5>
                                    <span class="text-info">
                                        <i class="nav-icon material-icons icon--list mr-1">help</i>                                        
                                    </span>
                                    How to grab Media ID?
                                </h5>
                                After clicking on on your preferred image, it will redirect you to your Google Drive page. Simply grab the ID from the url it redirected. I.e:
                                <br>
                                <code class="font-weight-bold text-primary">https://drive.google.com/file/d/<span class="text-pink">grab_this_id_from_link</span>/view</code>
                            </div>
                            <iframe src="https://drive.google.com/embeddedfolderview?id={{ $media_source }}#grid" style="width:100%; height: 50vh; border:0;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            if ($('.jsReplicate')[0]){
                cls = '.jsReplicate'
                
                $(cls+'_add').click(function() {
                    container = $(this).parents(cls).find(cls+'_container')
                    clsIndex = $(this).parents(cls).index(cls)
                    $(cls+'_template:eq('+clsIndex+')').children().clone().appendTo(container)
                })

                $(document).on('click', cls+'_remove', function() {
                    $(this).parents(cls+'_template_item').remove()
                })
            }
        })
    </script>
@endsection