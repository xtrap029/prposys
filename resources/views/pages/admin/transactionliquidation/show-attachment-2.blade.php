<a class="btn btn-app p-2" data-toggle="modal" data-target="#modal-attachments" href="#_">
    <i class="align-middle font-weight-bolder material-icons text-orange">folder</i>
    <p class="text-dark">Attachments</p>
</a>

<div class="modal fade" id="modal-attachments" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Attachments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0">
                @forelse ($transaction->attachments as $item)
                    <p class="border-top pt-3">
                        <a href="/storage/public/attachments/liquidation/{{ $item->file }}" target="_blank" style="vertical-align: sub">
                            @if (pathinfo($item->file, PATHINFO_EXTENSION) == 'pdf')
                                <i class="material-icons mr-2 align-bottom" style="font-size: 40px">picture_as_pdf</i>
                            @else
                                <i class="material-icons mr-2 align-bottom" style="font-size: 40px">insert_photo</i>    
                            @endif
                        </a>
                        {{ $item->description }}
                    </p>
                @empty
                    <p>{{ __('messages.empty') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>