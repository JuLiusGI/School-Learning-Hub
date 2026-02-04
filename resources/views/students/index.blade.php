<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Students
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <div class="text-sm text-gray-600">
                            Manage student records.
                        </div>
                        <a href="{{ route('students.create') }}">
                            <x-primary-button>
                                Add Student
                            </x-primary-button>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2 text-left">LRN</th>
                                    <th class="px-4 py-2 text-left">Last Name</th>
                                    <th class="px-4 py-2 text-left">First Name</th>
                                    <th class="px-4 py-2 text-left">Gender</th>
                                    <th class="px-4 py-2 text-left">Birthdate</th>
                                    <th class="px-4 py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $student->lrn }}</td>
                                        <td class="px-4 py-2">{{ $student->last_name }}</td>
                                        <td class="px-4 py-2">{{ $student->first_name }}</td>
                                        <td class="px-4 py-2 capitalize">{{ $student->gender }}</td>
                                        <td class="px-4 py-2">{{ $student->birthdate?->format('Y-m-d') }}</td>
                                        <td class="px-4 py-2 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('students.edit', $student) }}">
                                                    <x-secondary-button>
                                                        Edit
                                                    </x-secondary-button>
                                                </a>
                                                <form method="POST" action="{{ route('students.destroy', $student) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button>
                                                        Delete
                                                    </x-danger-button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
