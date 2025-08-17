// Toast Notification System
let toastCounter = 0;

function showToast(message, type = 'info', duration = 5000) {
  // Get the toast container
  const container = document.getElementById('toast-container');
  if (!container) {
    console.error('Toast container not found');
    return;
  }

  // Create toast element
  const toastId = `toast-${toastCounter++}`;
  const toast = document.createElement('div');
  toast.id = toastId;
  toast.className = `toast toast-${type}`;
  
  // Determine icon based on type
  let icon = '';
  switch(type) {
    case 'success':
      icon = `
        <svg class="toast-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
        </svg>
      `;
      break;
    case 'error':
      icon = `
        <svg class="toast-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
        </svg>
      `;
      break;
    default:
      icon = `
        <svg class="toast-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
        </svg>
      `;
  }

  // Set toast content
  toast.innerHTML = `
    <div class="toast-content">
      ${icon}
      <div class="toast-message">${message}</div>
      <button class="toast-close">&times;</button>
    </div>
    <div class="toast-progress">
      <div class="toast-progress-inner"></div>
    </div>
  `;

  // Add toast to container
  container.appendChild(toast);

  // Trigger show animation
  setTimeout(() => {
    toast.classList.add('show');
  }, 10);

  // Get progress bar element
  const progressBar = toast.querySelector('.toast-progress-inner');

  // Set up auto-hide
  let timeoutId;
  if (duration > 0) {
    // Animate progress bar from right to left
    progressBar.style.transition = 'none';
    progressBar.style.transform = 'translateX(0)';
    
    // Trigger reflow
    void progressBar.offsetWidth;
    
    // Start progress animation
    progressBar.style.transition = `transform ${duration}ms linear`;
    progressBar.style.transform = 'translateX(-100%)';
    
    // Set timeout to remove toast
    timeoutId = setTimeout(() => {
      hideToast(toastId);
    }, duration);
  }

  // Set up close button
  const closeButton = toast.querySelector('.toast-close');
  closeButton.addEventListener('click', () => {
    hideToast(toastId);
    if (timeoutId) clearTimeout(timeoutId);
  });

  return toastId;
}

function hideToast(toastId) {
  const toast = document.getElementById(toastId);
  if (toast) {
    toast.classList.add('hide');
    setTimeout(() => {
      if (toast.parentNode) {
        toast.parentNode.removeChild(toast);
      }
    }, 300);
  }
}

// Export for use in modules
window.showToast = showToast;
window.hideToast = hideToast;