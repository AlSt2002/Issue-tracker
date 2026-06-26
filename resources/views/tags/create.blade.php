@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">

        <div class="max-w-lg mx-auto bg-white shadow rounded p-6">

            <h1 class="text-2xl font-bold mb-4">Create Tag</h1>

            @if ($errors->any())
                <div class="mb-4 text-red-600">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('tags.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block mb-1">Name</label>
                    <input type="text" name="name"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Color</label>
                    <input type="color" name="color" class="w-16 h-10">
                </div>

                <button type="submit"
                        style="background-color:#2563eb;color:white;padding:10px 16px;border-radius:6px;">
                    Save Tag
                </button>

                <a href="{{ route('tags.index') }}" class="ml-3 text-gray-600">
                    Cancel
                </a>
            </form>

        </div>

    </div>
@endsection
