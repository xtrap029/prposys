@extends('layouts.app-resources')

@section('title', 'Files')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Files</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">  
            <form class="col-12 mt-3 mt-md-0 mb-3">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="s" autocomplete="off" placeholder="keyword here..." value="{{ isset($_GET['s']) ? $_GET['s'] : '' }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                    <div class="input-group-append">
                        <a href="/files" class="btn btn-danger" type="submit">Reset</a>
                    </div>
                </div>
                @if (isset($_GET['s']) && $_GET['s'] != '')
                    <div class="text-center">
                        search result for <code>{{ $_GET['s'] }}</code>
                    </div>
                @endif
            </form>
            @if (count($files) > 0)
                <div class="row mt-5">
                    <div class="col-md-2 nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach ($files as $key => $item)
                            <a data-drive="{{ $item->drive }}" data-description="{{ $item->description }}" class="drivePreviewItem nav-link cursor-pointer border mb-2 py-2 {{ $key == 0 ? 'active' : '' }}" data-toggle="pill" role="tab">
                                <i class="nav-icon material-icons icon--list mr-3">folder</i>
                                {{ $item->name }}
                            </a>
                        @endforeach
                    </div>
                    <div class="drivePreview col-md-10 border rounded p-4 text-center">
                        <img src="/images/loading.gif" class="drivePreviewLoading thumb--sm m-auto" style="display: none;">
                        <div class="drivePreviewContainer text-left">
                            <h6>{{ $files[0]->description }}</h6>
                            <hr>
                            <iframe src="https://drive.google.com/embeddedfolderview?id={{ $files[0]->drive }}#list" style="width:100%; height: 50vh; border:0;"></iframe>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(function() {
            $('.drivePreviewItem').click(function() {
                $('.drivePreviewContainer').fadeOut(300)
                $('.drivePreviewLoading').delay(300).fadeIn('slow')
                $('#v-pills-tab').css('pointer-events', 'none')
                
                $('.drivePreview iframe').attr('src', 'https://drive.google.com/embeddedfolderview?id='+$(this).data('drive')+'#list')

                desc = $(this).data('description')

                setTimeout(function() {
                    $('.drivePreview h6').text(desc)
                    
                    $('.drivePreviewLoading').fadeOut(300)
                    $('.drivePreviewContainer').delay(300).fadeIn('slow')
                    $('#v-pills-tab').css('pointer-events', 'all')
                }, 1000)

            })
        })
    </script>
@endsection