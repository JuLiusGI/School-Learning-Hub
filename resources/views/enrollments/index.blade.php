<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Enrollments
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <div class="text-sm text-gray-600">
                            Assign students to sections per school year.
                        </div>
                        <a href="{{ route('enrollments.create') }}">
                            <x-primary-button>
                                Add Enrollment
                            </x-primary-button>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2 text-left">School Year</th>
                                    <th class="px-4 py-2 text-left">Student</th>
                                    <th class="px-4 py-2 text-left">Section</th>
                                    <th class="px-4 py-2 text-left">Grade</th>
                                    <th class="px-4 py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enrollments as $enrollment)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $enrollment->schoolYear?->name }}</td>
                                        <td class="px-4 py-2">{{ $enrollment->student?->last_name }}, {{ $enrollment->student?->first_name }}</td>
                                        <td class="px-4 py-2">{{ $enrollment->section?->name }}</td>
                                        <td class="px-4 py-2">{{ $enrollment->section?->grade?->level }}</td>
                                        <td class="px-4 py-2 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('enrollments.edit', $enrollment) }}">
                                                    <x-secondary-button>
                                                        Edit
                                                    </x-secondary-button>
                                                </a>
                                                <form method="POST" action="{{ route('enrollments.destroy', $enrollment) }}">
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
