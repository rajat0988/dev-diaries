@extends('layouts.app')

@section('content')
@vite('resources/css/showQuestions.css')

<div class="questions-index-container">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Admin Dashboard</h1>
            <p class="page-subtitle">Overview of system activity</p>
        </div>
        <div class="header-actions">
           <a href="{{ route('admin.reported') }}" class="btn-primary" style="background-color: #4b5563;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Reported Items
           </a>
           <a href="{{ route('admin.users') }}" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                User Management
           </a>
        </div>
    </div>

    <div class="content-layout">
        <div class="questions-main" style="grid-column: 1 / -1;">
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Stats Cards -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 transition-all hover:scale-105">
                    <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Total Users</h3>
                    <p class="text-4xl font-bold dark:text-white mt-2">{{ $stats['users_count'] }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 transition-all hover:scale-105">
                    <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Pending Approvals</h3>
                    <p class="text-4xl font-bold {{ $stats['pending_users_count'] > 0 ? 'text-orange-600' : 'text-gray-400' }} mt-2">{{ $stats['pending_users_count'] }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 transition-all hover:scale-105">
                    <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Reported Questions</h3>
                    <p class="text-4xl font-bold {{ $stats['reported_questions_count'] > 0 ? 'text-red-600' : 'text-gray-400' }} mt-2">{{ $stats['reported_questions_count'] }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 transition-all hover:scale-105">
                    <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Reported Replies</h3>
                    <p class="text-4xl font-bold {{ $stats['reported_replies_count'] > 0 ? 'text-red-600' : 'text-gray-400' }} mt-2">{{ $stats['reported_replies_count'] }}</p>
                </div>
            </div>

            <div class="recent-section">
                <div class="section-header">
                    <h2 class="section-title">Quick Actions</h2>
                </div>
                <div class="flex flex-wrap gap-4 mt-6">
                    <a href="{{ route('admin.users') }}" class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Manage Pending Users
                    </a>
                    <a href="{{ route('admin.reported') }}" class="btn-primary" style="background-color: #dc2626;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Review Reported Content
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
