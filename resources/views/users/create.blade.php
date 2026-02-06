<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" value="Full Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="password" value="Password" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" value="Confirm Password" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <x-input-label for="role" value="Role" />
                            <select id="role" name="role" class="mt-1 block w-full border-[#1a4731]/30 focus:border-[#1a4731] focus:ring-[#1a4731] rounded-md shadow-sm bg-[#fefefe] text-[#1a4731]" required>
                                <option value="teacher" @selected(old('role') === 'teacher')>Teacher</option>
                                <option value="head_teacher" @selected(old('role') === 'head_teacher')>Head Teacher</option>
                                <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('role')" />
                        </div>

                        <div>
                            <x-input-label for="section_ids" value="Assigned Sections (Teachers only)" />
                            <select id="section_ids" name="section_ids[]" multiple class="mt-1 block w-full border-[#1a4731]/30 focus:border-[#1a4731] focus:ring-[#1a4731] rounded-md shadow-sm bg-[#fefefe] text-[#1a4731]">
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" @selected(in_array($section->id, old('section_ids', [])))>
                                        {{ $section->grade->level }} - {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('section_ids')" />
                        </div>

                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="mt-1 block w-full border-[#1a4731]/30 focus:border-[#1a4731] focus:ring-[#1a4731] rounded-md shadow-sm bg-[#fefefe] text-[#1a4731]" required>
                                <option value="active" @selected(old('status') === 'active')>Active</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Save User
                            </x-primary-button>
                            <a href="{{ route('users.index') }}" class="text-sm text-[#1a4731]/80 hover:text-[#1a4731]">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
