<div class="modal fade" id="modal-notes-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-0">
                <div class="card direct-chat direct-chat-primary mb-0">
                    <div class="card-body">
                        <div class="direct-chat-messages">
                            @forelse ($item->notes as $notes)
                                @if ($notes->user->id == Auth::user()->id)
                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-right">{{ $notes->user->name }}</span>
                                            <span class="direct-chat-timestamp float-left">{{ $notes->created_at->diffForHumans() }}</span>
                                        </div>
                                        <img class="direct-chat-img" src="/storage/public/images/users/{{ $notes->user->avatar }}" alt="message user image">
                                        <div class="direct-chat-text">{{ $notes->content }}</div>
                                    </div>
                                @else
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">{{ $notes->user->name }}</span>
                                            <span class="direct-chat-timestamp float-right">{{ $notes->created_at->diffForHumans() }}</span>
                                        </div>
                                        <img class="direct-chat-img" src="/storage/public/images/users/{{ $notes->user->avatar }}" alt="message user image">
                                        <div class="direct-chat-text">{{ $notes->content }}</div>
                                    </div>
                                @endif    
                            @empty
                                {{ __('messages.empty') }}                                      
                            @endforelse
                        </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>