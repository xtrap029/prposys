<div class="modal fade" id="modal-notes" tabindex="-1" role="dialog" aria-hidden="true">
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
                            @forelse ($transaction->notes as $item)
                                @if ($item->user->id == Auth::user()->id)
                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-right">{{ $item->user->name }}</span>
                                            <span class="direct-chat-timestamp float-left">{{ $item->created_at->diffForHumans() }}</span>
                                        </div>
                                        <img class="direct-chat-img" src="/storage/public/images/users/{{ $item->user->avatar }}" alt="message user image">
                                        <div class="direct-chat-text">
                                            <div class="notes-content">
                                                {{ $item->content }}
                                                <span class="d-block text-right">
                                                    <a href="#" class="mx-1 editNote">
                                                        <i class="align-middle font-weight-bolder material-icons text-md text-white-50">edit</i>
                                                    </a>
                                                    <a href="/transaction/delete_note/{{ $transaction->id }}/{{ $item->id }}" onclick="return confirm('Are you sure?')" class="mx-1">
                                                        <i class="align-middle font-weight-bolder material-icons text-md text-white-50">delete</i>
                                                    </a>
                                                </span>
                                            </div>
                                            <form action="/transaction/edit_note/{{ $transaction->id }}/{{ $item->id }}" method="post" class="notes-content-edit my-2 d-none">
                                                @csrf
                                                @method('put')
                                                <textarea name="note" rows="2" class="form-control">{{ $item->content }}</textarea>
                                                <input type="submit" class="btn btn-xs btn-default mt-2" value="Save">
                                                <button type="button" class="btn btn-xs btn-default mt-2 cancelNote">Cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">{{ $item->user->name }}</span>
                                            <span class="direct-chat-timestamp float-right">{{ $item->created_at->diffForHumans() }}</span>
                                        </div>
                                        <img class="direct-chat-img" src="/storage/public/images/users/{{ $item->user->avatar }}" alt="message user image">
                                        <div class="direct-chat-text">{{ $item->content }}</div>
                                    </div>
                                @endif    
                            @empty
                                {{ __('messages.empty') }}                                      
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer">
                        <form action="/transaction/note/{{ $transaction->id }}" method="post">
                            @csrf
                            @method('put')
                            <div class="input-group">
                                <input type="text" name="content" placeholder="Add note ..." class="form-control" required>
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </span>
                            </div>
                        </form>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>

@section('script2')
<script type="text/javascript">
    $(function() {
        $('.editNote').on('click', function() {
            $(this).parents('.direct-chat-text').find('.notes-content-edit').toggleClass('d-none')
            $(this).parents('.notes-content').toggleClass('d-none')
        })
        $('.cancelNote').on('click', function() {
            $(this).parents('.direct-chat-text').find('.notes-content-edit').addClass('d-none')
            $(this).parents('.direct-chat-text').find('.notes-content').removeClass('d-none')
        })
    })
</script>
@endsection