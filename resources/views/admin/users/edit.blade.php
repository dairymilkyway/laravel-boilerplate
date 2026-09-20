@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex items-center gap-3">
        <a
            href="{{ route('admin.users.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Users
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="mb-4">
            <h1 class="text-2xl font-semibold text-gray-900">Edit User</h1>
            <p class="mt-1 text-sm text-gray-500">Update name, email, and role assignments for {{ $user->name }}.</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PATCH')

                <!-- Name -->
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Name
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        autocomplete="name"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400
                            focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500
                            @error('name') border-red-400 bg-red-50 @enderror"
                    >
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        autocomplete="email"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400
                            focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500
                            @error('email') border-red-400 bg-red-50 @enderror"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Roles -->
                <div class="mb-6">
                    <fieldset>
                        <legend class="block text-sm font-medium text-gray-700 mb-2">Roles</legend>
                        @error('roles')
                            <p class="mb-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="space-y-2">
                            @forelse($roles as $role)
                                <label
                                    for="role_{{ $role->id }}"
                                    class="flex items-center gap-3 rounded-lg border border-gray-200 px-4 py-2.5 cursor-pointer hover:bg-gray-50 transition"
                                >
                                    <input
                                        type="checkbox"
                                        id="role_{{ $role->id }}"
                                        name="roles[]"
                                        value="{{ $role->name }}"
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                    >
                                    <span class="text-sm text-gray-800 font-medium">{{ $role->name }}</span>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No roles defined yet.</p>
                            @endforelse
                        </div>
                    </fieldset>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white
                            hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                    >
                        Update User
                    </button>
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700
                            hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
