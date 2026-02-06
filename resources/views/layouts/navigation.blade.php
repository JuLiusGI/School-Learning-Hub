<nav class="bg-[#fefefe] border-b border-[#f5e6cc]">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-[#1a4731]" />
                    </a>
                </div>

                @php
                    $user = Auth::user();
                    $canManageAcademics = $user?->isAdmin() || $user?->isHeadTeacher();
                @endphp

                <div class="space-x-8 -my-px ms-10 flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if ($canManageAcademics)
                        <x-nav-link :href="route('school-years.index')" :active="request()->routeIs('school-years.*')">
                            {{ __('School Years') }}
                        </x-nav-link>
                        <x-nav-link :href="route('grades.index')" :active="request()->routeIs('grades.*')">
                            {{ __('Grades') }}
                        </x-nav-link>
                        <x-nav-link :href="route('subjects.index')" :active="request()->routeIs('subjects.*')">
                            {{ __('Subjects') }}
                        </x-nav-link>
                        <x-nav-link :href="route('competencies.index')" :active="request()->routeIs('competencies.*')">
                            {{ __('Competencies') }}
                        </x-nav-link>
                    @endif
                    <x-nav-link :href="route('lessons.index')" :active="request()->routeIs('lessons.*')">
                        {{ __('Lessons') }}
                    </x-nav-link>
                    <x-nav-link :href="route('lesson-resources.index')" :active="request()->routeIs('lesson-resources.*')">
                        {{ __('Resources') }}
                    </x-nav-link>
                    <x-nav-link :href="route('assessments.index')" :active="request()->routeIs('assessments.*')">
                        {{ __('Assessments') }}
                    </x-nav-link>
                    <x-nav-link :href="route('assessment-items.index')" :active="request()->routeIs('assessment-items.*')">
                        {{ __('Assessment Items') }}
                    </x-nav-link>
                    <x-nav-link :href="route('scores.index')" :active="request()->routeIs('scores.*')">
                        {{ __('Scores') }}
                    </x-nav-link>
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        {{ __('Reports') }}
                    </x-nav-link>
                    <x-nav-link :href="route('enrollments.index')" :active="request()->routeIs('enrollments.*')">
                        {{ __('Enrollments') }}
                    </x-nav-link>
                    @if ($canManageAcademics)
                        <x-nav-link :href="route('sections.index')" :active="request()->routeIs('sections.*')">
                            {{ __('Sections') }}
                        </x-nav-link>
                        <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                            {{ __('Students') }}
                        </x-nav-link>
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('Users') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="flex items-center ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[#1a4731] bg-[#fefefe] hover:text-[#143726] focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" data-offline="false">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
