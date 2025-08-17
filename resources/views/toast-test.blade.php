@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Toast Notification Test</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Test Toast Notifications</h2>
            </div>
            <div class="card-content">
                <p class="mb-4">Click the buttons below to test different types of toast notifications:</p>
                
                <div class="space-y-4">
                    <button id="success-toast" class="button-primary w-full">
                        Show Success Toast
                    </button>
                    
                    <button id="error-toast" class="button-primary w-full">
                        Show Error Toast
                    </button>
                    
                    <button id="warning-toast" class="button-primary w-full">
                        Show Warning Toast
                    </button>
                    
                    <button id="info-toast" class="button-primary w-full">
                        Show Info Toast
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Test Results</h2>
            </div>
            <div class="card-content">
                <div id="test-results">
                    <p>Toast system status: <span id="status" class="font-bold">Checking...</span></p>
                    <p class="mt-4">Try voting on a question or reply to test the toast notifications in the voting system.</p>
                    <a href="{{ route('questions.index') }}" class="button-primary mt-4 inline-block">
                        Go to Questions Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if toast system is available
    const statusElement = document.getElementById('status');
    if (typeof window.showToast !== 'undefined') {
        statusElement.textContent = 'Available';
        statusElement.className = 'font-bold text-green-600';
    } else {
        statusElement.textContent = 'Not Available';
        statusElement.className = 'font-bold text-red-600';
    }
    
    // Set up test buttons
    document.getElementById('success-toast').addEventListener('click', function() {
        if (typeof window.showToast !== 'undefined') {
            window.showToast('This is a success message!', 'success');
        }
    });
    
    document.getElementById('error-toast').addEventListener('click', function() {
        if (typeof window.showToast !== 'undefined') {
            window.showToast('This is an error message!', 'error');
        }
    });
    
    document.getElementById('warning-toast').addEventListener('click', function() {
        if (typeof window.showToast !== 'undefined') {
            window.showToast('This is a warning message!', 'warning');
        }
    });
    
    document.getElementById('info-toast').addEventListener('click', function() {
        if (typeof window.showToast !== 'undefined') {
            window.showToast('This is an info message!', 'info');
        }
    });
});
</script>
@endsection