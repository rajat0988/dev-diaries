<div id="toast-container"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for Laravel session flash messages and display them as toasts
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
    
    @if(session('warning'))
        showToast('{{ session('warning') }}', 'warning');
    @endif
    
    @if(session('info'))
        showToast('{{ session('info') }}', 'info');
    @endif
});
</script>