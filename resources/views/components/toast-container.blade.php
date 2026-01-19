<div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for Laravel session flash messages and display them as toasts
    @if(session('success'))
        showToast(@json(session('success')), 'success');
    @endif
    
    @if(session('error'))
        showToast(@json(session('error')), 'error');
    @endif
    
    @if(session('warning'))
        showToast(@json(session('warning')), 'warning');
    @endif
    
    @if(session('info'))
        showToast(@json(session('info')), 'info');
    @endif

    // Check for validation errors and display them as toasts
    @if($errors->any())
        @foreach($errors->all() as $error)
            showToast(@json($error), 'error');
        @endforeach
    @endif
});

function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 transition-all transform duration-500 opacity-0 translate-y-2 border-l-4`;
    
    // Set colors and icons based on type
    let icon = '';
    let borderColor = '';
    
    switch(type) {
        case 'success':
            borderColor = 'border-green-500';
            icon = `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
            </div>`;
            break;
        case 'error':
            borderColor = 'border-red-500';
            icon = `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                </svg>
            </div>`;
            break;
        case 'warning':
            borderColor = 'border-orange-500';
            icon = `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                </svg>
            </div>`;
            break;
        default: // info
            borderColor = 'border-blue-500';
            icon = `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
            </div>`;
    }

    toast.classList.add(borderColor);
    
    toast.innerHTML = `
        ${icon}
        <div class="ml-3 text-sm font-normal break-words w-48">${message}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    `;

    // Close button functionality
    toast.querySelector('button').addEventListener('click', function() {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 500);
    });

    container.appendChild(toast);
    
    // Animate in
    requestAnimationFrame(() => {
        toast.classList.remove('opacity-0', 'translate-y-2');
    });

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if (toast.isConnected) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px)';
            setTimeout(() => toast.remove(), 500);
        }
    }, 5000);
}
</script>
