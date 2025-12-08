<div class="comment">

    {{-- USER INFO --}}
    <div class="d-flex align-items-center gap-3">

        @if($comment->user->profile_image)
            <img src="{{ asset($comment->user->profile_image) }}" class="avatar-img">
        @else
            <div class="avatar">{{ strtoupper(substr($comment->user->name,0,1)) }}</div>
        @endif

        <div>
            <strong>{{ $comment->user->name }}</strong><br>
            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
        </div>
    </div>

    {{-- STAR RATING --}}
    @if($comment->given_rating)
        <div class="mt-2 text-warning" style="font-size: 18px;">
            {{ str_repeat('★', $comment->given_rating) }}
        </div>
    @endif

    {{-- COMMENT TEXT --}}
    <p class="mt-2">{{ $comment->content }}</p>

    {{-- ACTIONS: LIKE / DISLIKE / REPLY / EDIT / DELETE --}}
    <div class="d-flex gap-3 mt-2 align-items-center">

        {{-- LIKE --}}
        <form method="POST" action="{{ route('comments.like', $comment->id) }}">
            @csrf
            <button class="vote-btn">👍 {{ $comment->likes }}</button>
        </form>

        {{-- DISLIKE --}}
        <form method="POST" action="{{ route('comments.dislike', $comment->id) }}">
            @csrf
            <button class="vote-btn">👎 {{ $comment->dislikes }}</button>
        </form>

        @auth

            {{-- REPLY BUTTON --}}
            <button class="reply-toggle" onclick="toggleReply({{ $comment->id }})">
                Reply
            </button>

            {{-- EDIT BUTTON (only owner) --}}
            @if(auth()->id() === $comment->user_id)
                <button class="btn btn-link p-0 ms-2" style="font-size: 14px;"
                        onclick="toggleEdit({{ $comment->id }})">
                    Edit
                </button>

                {{-- DELETE --}}
                <form method="POST" class="d-inline ms-1"
                      onsubmit="return confirm('Delete this comment?');"
                      action="{{ route('comments.destroy', $comment->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-link text-danger p-0" style="font-size:14px;">
                        Delete
                    </button>
                </form>
            @endif

        @endauth

    </div>

    {{-- EDIT FORM --}}
    @auth
    @if(auth()->id() === $comment->user_id)
        <form id="edit-{{ $comment->id }}" method="POST"
              class="reply-box mt-2"
              style="max-height:0;"
              action="{{ route('comments.update', $comment->id) }}">
            @csrf
            @method('PATCH')

            <textarea name="content" class="form-control mb-2" required>{{ $comment->content }}</textarea>

            {{-- RATING SELECT (Top-level comment only) --}}
            @if(!$comment->parent_id)
                <div class="mb-2">
                    <label class="form-label mb-1" style="font-size:14px;">Edit Rating</label>
                    <select name="rating" class="form-select form-select-sm" style="max-width: 140px;">
                        <option value="">No rating</option>
                        @for($i=1;$i<=5;$i++)
                            <option value="{{ $i }}" {{ $comment->given_rating==$i?'selected':'' }}>
                                {{ $i }} ★
                            </option>
                        @endfor
                    </select>
                </div>
            @endif

            <button class="btn btn-success btn-sm">Save</button>
        </form>
    @endif
    @endauth

    {{-- REPLY FORM --}}
    @auth
        <form id="reply-{{ $comment->id }}" class="reply-box mt-2 reply-area"
              style="max-height:0;"
              action="{{ route('comments.store', $recipe->id) }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <textarea name="content" class="form-control mb-2" required></textarea>
            <button class="btn btn-success btn-sm">Send</button>
        </form>
    @endauth

    {{-- REPLIES --}}
    @foreach($comment->replies as $reply)
        <div class="mt-3 ms-5 border-start ps-3">

            <div class="d-flex align-items-center gap-2">

                @if($reply->user->profile_image)
                    <img src="{{ asset($reply->user->profile_image) }}" class="avatar-img-small">
                @else
                    <div class="avatar small">{{ strtoupper(substr($reply->user->name,0,1)) }}</div>
                @endif

                <div>
                    <strong>{{ $reply->user->name }}</strong><br>
                    <small>{{ $reply->created_at->diffForHumans() }}</small>
                </div>

            </div>

            <p class="mt-2">{{ $reply->content }}</p>

        </div>
    @endforeach

</div>
