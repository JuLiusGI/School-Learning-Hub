<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reports
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <form method="GET" action="{{ route('reports.index') }}" class="flex items-end gap-4">
                            <div>
                                <x-input-label for="report_type" value="Report Type" />
                                <select id="report_type" name="report_type" class="mt-1 block w-64 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All types</option>
                                    @foreach ($reportTypes as $value => $label)
                                        <option value="{{ $value }}" @selected($filters['report_type'] == $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-primary-button>
                                Filter
                            </x-primary-button>
                        </form>
                        <a href="{{ route('reports.create') }}">
                            <x-primary-button>
                                Generate Report
                            </x-primary-button>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2 text-left">Type</th>
                                    <th class="px-4 py-2 text-left">Section</th>
                                    <th class="px-4 py-2 text-left">School Year</th>
                                    <th class="px-4 py-2 text-left">Generated</th>
                                    <th class="px-4 py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $reportTypes[$report->report_type] ?? $report->report_type }}</td>
                                        <td class="px-4 py-2">{{ $report->section?->name }} ({{ $report->section?->grade?->level }})</td>
                                        <td class="px-4 py-2">{{ $report->schoolYear?->name }}</td>
                                        <td class="px-4 py-2">{{ $report->generated_at?->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-2 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('reports.show', $report) }}">
                                                    <x-secondary-button>
                                                        View
                                                    </x-secondary-button>
                                                </a>
                                                <form method="POST" action="{{ route('reports.destroy', $report) }}">
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
