@extends('layouts.app')

@section('title', 'Maintenance')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Maintenance</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <canvas id="myChart" width="400" height="400"></canvas>
        </div>
    </section>
@endsection