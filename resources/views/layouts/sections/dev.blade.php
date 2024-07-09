@if (env('APP_LOC') == 'dev')
    <div class="alert alert-warning mb-0 text-center" role="alert">
        You are currently accessing the <strong>Staging Site</strong>.
    </div>      
@endif