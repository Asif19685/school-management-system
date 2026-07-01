<aside class="w-full md:w-72 bg-slate-900 text-slate-100 md:min-h-screen flex flex-col justify-between">
    <div>
        <div class="px-5 py-4 border-b border-slate-700">
            <a href="{{ route('dashboard') }}" class="text-lg font-semibold tracking-wide block">
                School Management
            </a>
            <p class="text-xs text-slate-400 mt-1">Admin Panel</p>
        </div>

        <nav class="px-3 py-4 space-y-1">

            <a href="{{ route('dashboard') }}"
                class="block rounded px-3 py-2 text-sm {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>

            <div x-data="{ open: {{ request()->is('student-admissions*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between rounded px-3 py-2 text-sm text-slate-200 hover:bg-slate-800 focus:outline-none {{ request()->is('student-admissions*') ? 'bg-slate-800 text-white font-medium' : '' }}">
                    <span class="flex items-center">
                        <i class="bi bi-person-plus me-2"></i> Admissions
                    </span>
                    <i class="bi bi-chevron-down text-xs transition-transform duration-200" :class="open ? 'transform rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-cloak class="pl-6 mt-1 space-y-1" x-transition>
                    <button data-bs-toggle="modal" data-bs-target="#createAdmissionModal"
                        class="w-full text-left block rounded px-3 py-1.5 text-xs text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                        <i class="bi bi-plus-circle me-1.5"></i> Create New
                    </button>

                    <a href="{{ route('admissions.index') }}"
                        class="block rounded px-3 py-1.5 text-xs {{ request()->routeIs('admissions.index') && request()->get('view') !== 'reports' ? 'bg-slate-700 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-sliders me-1.5"></i> Manage Admissions
                    </a>

                    <a href="{{ route('admissions.index') }}?view=reports"
                        class="block rounded px-3 py-1.5 text-xs {{ request()->routeIs('admissions.index') && request()->get('view') === 'reports' ? 'bg-slate-700 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <i class="bi bi-file-earmark-bar-graph me-1.5"></i> Reports
                    </a>
                </div>
            </div>

            </nav>
    </div>

    <div class="px-3 pb-4 space-y-1 border-t border-slate-800 pt-4">
        <a href="{{ route('profile.edit') }}"
            class="block rounded px-3 py-2 text-sm {{ request()->routeIs('profile.edit') ? 'bg-slate-700 text-white' : 'text-slate-200 hover:bg-slate-800' }}">
            <i class="bi bi-person me-2"></i> Profile
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left rounded px-3 py-2 text-sm text-red-400 hover:bg-slate-800 hover:text-red-300 transition-colors">
                <i class="bi bi-box-arrow-right me-2"></i> Log Out
            </button>
        </form>
    </div>
</aside>
