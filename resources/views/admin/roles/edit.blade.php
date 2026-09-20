@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex items-center gap-3">
        <a
            href="{{ route('admin.roles.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Roles
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="mb-4">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Role</h1>
            <p class="mt-1 text-sm text-gray-500">Update the role name and sync its permissions.</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                @csrf
                @method('PATCH')

                <!-- Role name -->
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Role Name
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $role->name) }}"
                        required
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400
                            focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500
                            @error('name') border-red-400 bg-red-50 @enderror"
                    >
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permissions -->
                <div class="mb-6">
                    <fieldset>
                        <legend class="block text-sm font-medium text-gray-700 mb-2">Permissions</legend>
                        @error('permissions')
                            <p class="mb-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        @if($permissions->isEmpty())
                            <p class="text-sm text-gray-500">No permissions defined yet.</p>
                        @else
                            <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                                @foreach($permissions as $permission)
                                    <label
                                        for="permission_{{ $permission->id }}"
                                        class="flex items-center gap-3 rounded-lg border border-gray-200 px-4 py-2.5 cursor-pointer hover:bg-gray-50 transition"
                                    >
                                        <input
                                            type="checkbox"
                                            id="permission_{{ $permission->id }}"
                                            name="permissions[]"
                                            value="{{ $permission->name }}"
                                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                        >
                                        <span class="text-sm text-gray-800 font-medium">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </fieldset>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white
                            hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                    >
                        Update Role
                    </button>
                    <a
                        href="{{ route('admin.roles.index') }}"
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
