// ===== Toggle Password =====
    function togglePwd() {
            const input = document.getElementById('pwd');
            const show = document.getElementById('eye-show');
            const hide = document.getElementById('eye-hide');
            if (input.type === 'password') {
                input.type = 'text';
                show.classList.add('hidden');
                hide.classList.remove('hidden');
            } else {
                input.type = 'password';
                show.classList.remove('hidden');
                hide.classList.add('hidden');
            }
        }

// ===== Toggle Password =====
function togglePwd() {
    const input = document.getElementById('pwd');
    const show = document.getElementById('eye-show');
    const hide = document.getElementById('eye-hide');
    if (input.type === 'password') {
        input.type = 'text';
        show.classList.add('hidden');
        hide.classList.remove('hidden');
    } else {
        input.type = 'password';
        show.classList.remove('hidden');
        hide.classList.add('hidden');
    }
}

    // ===== Toast Error =====
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toastMsg');
        
        // Show toast if there is a message
        if (toast && toastMsg && toastMsg.textContent.trim() !== '') {
            toast.classList.remove('opacity-0', 'pointer-events-none');
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 4000);
        }
    });

    // ===== Loader =====
    window.addEventListener('load', function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.style.transition = 'opacity 0.5s ease';
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }
    });