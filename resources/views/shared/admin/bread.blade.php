<footer class="main-footer no-print">
    <div>
        {{ (new \App\Helpers\BreadHelper)->footer_label() }}
    </div>
    <ul>
        <li><a href="/">HOME</a> <i class="material-icons">chevron_right</i></li>
        <?php
            $special = null;
            switch (\Route::currentRouteName()) {
                case 'transaction': $special = $company->id; break;                
                default: $special = null; break;
            }
        ?>
        @foreach ((new \App\Helpers\BreadHelper)->get(\Route::currentRouteName(), \Route::getCurrentRoute()->getActionMethod(), $special) as $bread)
            <li><a href="{{ $bread[1] }}">{{ $bread[0] }}</a> <i class="material-icons">chevron_right</i></li>            
        @endforeach
    </ul>
</footer>