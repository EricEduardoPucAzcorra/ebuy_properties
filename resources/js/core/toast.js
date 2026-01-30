export const Toast = (() => {

    function createToast(id, type) {
        if (document.getElementById(id)) return;

        const icon = getIcon(type);
        const color = getColor(type);

        const toastHTML = `
        <div class="position-fixed top-0 end-0 mt-3 me-3" style="z-index: 2000; width: 22rem;">
          <div id="${id}" class="toast hide shadow rounded-3 border-0" role="alert" aria-live="assertive" aria-atomic="true">

            <div class="toast-header bg-dark text-white rounded-top-3 border-0">
              <span class="badge rounded-circle ${color.badge} d-flex align-items-center justify-content-center me-2" style="width: 22px; height: 22px;">
                <i class="bi ${icon} text-white" style="font-size: 12px;"></i>
              </span>
              <strong class="me-auto">${color.title}</strong>
              <small class="text-muted text-white-50"></small>

              <!-- Botón blanco para que siempre se vea -->
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>

            <div class="toast-body bg-white text-dark rounded-bottom-3 border-0">
              <span class="toast-message"></span>
            </div>

          </div>
        </div>
        `;

        document.body.insertAdjacentHTML('beforeend', toastHTML);
    }

    function getIcon(type) {
        switch (type) {
            case 'success': return 'bi-check-lg';
            case 'error': return 'bi-x-lg';
            case 'warning': return 'bi-exclamation-lg';
            case 'info':
            default: return 'bi-info-lg';
        }
    }

    function getColor(type) {
        switch (type) {
            case 'success': return { badge: 'bg-success', title: ToastTranslations.success };
            case 'error': return { badge: 'bg-danger', title: ToastTranslations.mistake };
            case 'warning': return { badge: 'bg-warning', title: ToastTranslations.warning };
            case 'info':
            default: return { badge: 'bg-info', title: ToastTranslations.information };
        }
    }

    function show({
        id = 'liveToast',
        title = '',
        message = '',
        time = now,
        type = 'info'
    }) {
        createToast(id, type);

        const toastElement = document.getElementById(id);

        if (title) toastElement.querySelector('.me-auto').innerText = title;

        toastElement.querySelector('.toast-message').innerText = message;
        toastElement.querySelector('small').innerText = time;

        const toast = new bootstrap.Toast(toastElement);
        toast.show();
    }

    return { show };
})();
