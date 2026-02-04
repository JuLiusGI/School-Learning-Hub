<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Student
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('students.update', $student) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="updated_at" value="{{ $student->updated_at?->toDateTimeString() }}">

                        <div>
                            <x-input-label for="lrn" value="LRN" />
                            <x-text-input id="lrn" name="lrn" type="text" class="mt-1 block w-full" value="{{ old('lrn', $student->lrn) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('lrn')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="first_name" value="First Name" />
                                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" value="{{ old('first_name', $student->first_name) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                            </div>
                            <div>
                                <x-input-label for="last_name" value="Last Name" />
                                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" value="{{ old('last_name', $student->last_name) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="gender" value="Gender" />
                                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select gender</option>
                                    <option value="male" @selected(old('gender', $student->gender) === 'male')>Male</option>
                                    <option value="female" @selected(old('gender', $student->gender) === 'female')>Female</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                            </div>
                            <div>
                                <x-input-label for="birthdate" value="Birthdate" />
                                <x-text-input id="birthdate" name="birthdate" type="date" class="mt-1 block w-full" value="{{ old('birthdate', $student->birthdate?->format('Y-m-d')) }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('birthdate')" />
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Update Student
                            </x-primary-button>
                            <a href="{{ route('students.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
