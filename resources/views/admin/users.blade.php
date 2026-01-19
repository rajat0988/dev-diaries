@extends('layouts.app')

@section('content')
@vite('resources/css/showQuestions.css')

<div class="questions-index-container">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">User Management</h1>
            <p class="page-subtitle">Manage student approvals and bulk imports</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn-primary" style="background-color: #4b5563;">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
        </div>
    </div>

    <div class="content-layout">
        <!-- Import Section -->
        <div class="recent-section">
            <div class="section-header border-b-0 pb-0 mb-4">
                <h2 class="section-title m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Import Students
                </h2>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-6 border border-gray-100 dark:border-gray-700">
                <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                    Bulk register students using a CSV file. Format: <code class="bg-gray-200 dark:bg-gray-900 px-2 py-1 rounded font-mono text-orange-600 dark:text-orange-400 text-sm">Name, Email, Password</code>. These users will be instantly auto-approved.
                </p>

                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-4 items-stretch md:items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Choose File (.csv or .txt)</label>
                        <input type="file" name="csv_file" accept=".csv,.txt" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 dark:file:bg-gray-600 dark:file:text-white transition-all cursor-pointer bg-white dark:bg-gray-900 rounded-lg border border-gray-300 dark:border-gray-600 p-1" required>
                    </div>
                    <button type="submit" class="btn-primary whitespace-nowrap" style="background-color: #ea580c; border: none; color: white;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Upload & Process
                    </button>
                </form>
                @error('csv_file')
                    <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Pending Approvals Section -->
        <div class="recent-section">
            <div class="section-header flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <h2 class="section-title m-0 p-0 border-b-0">Pending Approvals</h2>
                    @if($pendingUsers->total() > 0)
                        <span class="ml-2 bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-200 px-3 py-1 rounded-full text-xs font-black uppercase tracking-tighter">
                            {{ $pendingUsers->total() }} Action Needed
                        </span>
                    @endif
                </div>
            </div>

            @if($pendingUsers->count() > 0)
                <div class="grid grid-cols-1 gap-4">
                    @foreach($pendingUsers as $user)
                        <div class="p-5 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4 hover:border-orange-300 dark:hover:border-orange-700 transition-colors">
                            <div class="flex items-center gap-4 w-full md:w-auto">
                                <div class="h-12 w-12 bg-orange-100 dark:bg-orange-900/50 rounded-full flex items-center justify-center text-orange-600 dark:text-orange-400 font-black text-xl border-2 border-orange-200 dark:border-orange-800">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900 dark:text-white leading-tight">{{ $user->name }}</h3>
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3 text-sm">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1">
                                            {{ $user->email }}
                                        </span>
                                        <span class="hidden sm:inline text-gray-300 dark:text-gray-600"></span>
                                        <span class="text-gray-400 dark:text-gray-500 font-medium">
                                            Joined {{ $user->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-3 w-full md:w-auto mt-2 md:mt-0">
                                <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" class="flex-1 md:flex-none">
                                    @csrf
                                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 rounded-lg text-white font-bold shadow-sm transition-transform active:scale-95 flex items-center justify-center gap-2" style="background-color: #16a34a;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" onsubmit="return confirm('Reject and delete {{ $user->name }}?');" class="flex-1 md:flex-none">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 rounded-lg text-white font-bold shadow-sm transition-transform active:scale-95 flex items-center justify-center gap-2" style="background-color: #dc2626;">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $pendingUsers->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-gray-50 dark:bg-gray-800/30 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                    <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-gray-900 dark:text-white text-xl font-bold mb-1">All Caught Up</p>
                    <p class="text-gray-500 dark:text-gray-400">There are no pending user approvals at this time.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
