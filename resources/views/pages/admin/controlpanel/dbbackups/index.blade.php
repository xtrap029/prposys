@extends('layouts.app')

@section('title', 'Database')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Database</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <table class="table table-striped table-responsive-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Backup Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($db as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ date_format($item->created_at, 'F jS Y - g:ia')  }}</td>
                            <td class="text-right">                                
                                <a href="/storage/public/db-backups/{{ $item->name }}" target="_blank">
                                    <i class="align-middle font-weight-bolder material-icons text-md">download</i> Download
                                </a>
                                <span class="small ml-2">({{ number_format(File::size('storage/public/db-backups/'.$item->name)/(1<<20),2)." MB" }})</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection