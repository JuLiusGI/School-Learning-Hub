<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Report
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-lg font-semibold">
                                {{ $payload['report_type'] === 'assessment-summary' ? 'Assessment Summary' : 'Student Progress' }}
                            </div>
                            <div class="text-sm text-gray-600">
                                Section: {{ $payload['section']['name'] ?? '' }} ({{ $payload['section']['grade'] ?? '' }}) ·
                                School Year: {{ $payload['school_year']['name'] ?? '' }}
                            </div>
                            <div class="text-sm text-gray-600">
                                Generated: {{ $payload['generated_at'] ?? $report->generated_at?->format('Y-m-d H:i') }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('reports.index') }}">
                                <x-secondary-button>
                                    Back
                                </x-secondary-button>
                            </a>
                            <x-primary-button type="button" onclick="window.print()">
                                Print
                            </x-primary-button>
                        </div>
                    </div>

                    @if (($payload['report_type'] ?? '') === 'assessment-summary')
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b">
                                        <th class="px-4 py-2 text-left">Assessment</th>
                                        <th class="px-4 py-2 text-left">Lesson</th>
                                        <th class="px-4 py-2 text-left">Subject</th>
                                        <th class="px-4 py-2 text-left">Type</th>
                                        <th class="px-4 py-2 text-left">Date</th>
                                        <th class="px-4 py-2 text-left">Max</th>
                                        <th class="px-4 py-2 text-left">Avg</th>
                                        <th class="px-4 py-2 text-left">High</th>
                                        <th class="px-4 py-2 text-left">Low</th>
                                        <th class="px-4 py-2 text-left">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (($payload['summary'] ?? []) as $row)
                                        <tr class="border-b">
                                            <td class="px-4 py-2">{{ $row['title'] ?? '' }}</td>
                                            <td class="px-4 py-2">{{ $row['lesson_title'] ?? '' }}</td>
                                            <td class="px-4 py-2">{{ $row['subject'] ?? '' }}</td>
                                            <td class="px-4 py-2">{{ $row['type'] ?? '' }}</td>
                                            <td class="px-4 py-2">{{ $row['date_given'] ?? '' }}</td>
                                            <td class="px-4 py-2">{{ $row['max_score'] ?? '' }}</td>
                                            <td class="px-4 py-2">{{ $row['average_score'] ?? '-' }}</td>
                                            <td class="px-4 py-2">{{ $row['highest_score'] ?? '-' }}</td>
                                            <td class="px-4 py-2">{{ $row['lowest_score'] ?? '-' }}</td>
                                            <td class="px-4 py-2">{{ $row['scores_count'] ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b">
                                        <th class="px-4 py-2 text-left">Student</th>
                                        <th class="px-4 py-2 text-left">Assessments Taken</th>
                                        <th class="px-4 py-2 text-left">Total Score</th>
                                        <th class="px-4 py-2 text-left">Total Max</th>
                                        <th class="px-4 py-2 text-left">Average %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (($payload['summary'] ?? []) as $row)
                                        <tr class="border-b">
                                            <td class="px-4 py-2">{{ $row['name'] ?? '' }}</td>
                                            <td class="px-4 py-2">{{ $row['assessments_taken'] ?? 0 }}</td>
                                            <td class="px-4 py-2">{{ $row['total_score'] ?? 0 }}</td>
                                            <td class="px-4 py-2">{{ $row['total_max_score'] ?? 0 }}</td>
                                            <td class="px-4 py-2">
                                                {{ $row['average_percent'] !== null ? $row['average_percent'].'%' : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            nav,
            .py-12 a,
            .py-12 button,
            .shadow-sm {
                box-shadow: none;
            }
        }
    </style>
</x-app-layout>
