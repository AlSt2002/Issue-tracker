@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">

        <div class="bg-white shadow rounded p-6">

            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold">{{ $issue->title }}</h1>

                    <p class="text-sm text-gray-500">
                        #{{ $issue->id }}
                    </p>
                </div>

                <div class="flex gap-2">

                    <!-- EDIT ISSUE -->
                    <a href="{{ route('issues.edit', $issue) }}"
                       style="background:red;color:white;padding:10px;border-radius:6px;">
                        EDIT ISSUE
                    </a>

                    <!-- DELETE ISSUE -->
                    <form action="{{ route('issues.destroy', $issue) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this issue?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                style="background:#dc2626;color:white;padding:10px 12px;border-radius:6px;">
                            DELETE ISSUE
                        </button>
                    </form>

                </div>
            </div>

            @if(!empty($issue->description))
                <p class="mt-4 whitespace-pre-line">{{ $issue->description }}</p>
            @endif
        </div>

        {{-- COMMENTS --}}
        <div class="mt-6">
            <h2 class="text-xl font-bold">
                Comments ({{ $issue->comments->count() }})
            </h2>

            <div id="comments-list" class="mt-4 space-y-4">
                @forelse($issue->comments as $comment)
                    <div class="bg-white shadow rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-semibold">
                                    {{ $comment->author_name ?? 'Unknown' }}
                                </span>

                                <span class="text-sm text-gray-500">
                                    · {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <p class="mt-2">{{ $comment->body }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">No comments yet.</p>
                @endforelse
            </div>

            {{-- COMMENT FORM --}}
            @auth
                <form id="comment-form"
                      action="{{ route('issues.comments.store', $issue) }}"
                      method="POST"
                      class="mt-6">

                    @csrf

                    <textarea name="body"
                              rows="4"
                              class="w-full border rounded p-2"
                              placeholder="Write a comment..."></textarea>

                    <div class="mt-2 flex justify-end">
                        <button type="submit"
                                style="background-color:#2563eb;color:white;padding:10px 16px;border-radius:6px;display:inline-block;">
                            Post comment
                        </button>
                    </div>
                </form>
            @endauth
        </div>
    </div>

    {{-- AJAX SCRIPT --}}
    <script>
        document.getElementById('comment-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;

            const response = await fetch(form.action, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new FormData(form)
            });

            if (!response.ok) {
                console.warn('AJAX comment POST returned status', response.status, response.statusText);
            }

            const contentType = response.headers.get('content-type');

            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error("NOT JSON RESPONSE:", text);
                return;
            }

            const data = await response.json();
            const comment = data.data;

            document.getElementById('comments-list').insertAdjacentHTML('afterbegin', `
                <div class="bg-white shadow rounded p-4">
                    <div class="flex justify-between">
                        <span class="font-semibold">${comment.author_name}</span>
                        <span class="text-gray-500 text-sm">${comment.created_at}</span>
                    </div>
                    <p class="mt-2">${comment.body}</p>
                </div>
            `);

            form.reset();
        });
    </script>

@endsection
