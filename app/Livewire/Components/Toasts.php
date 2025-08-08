<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Toasts extends Component
{
    public function render()
    {
        return <<<'HTML'
        <div>
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="success-toasts" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-deley="3000">
                    <div class="toast-header  bg-success">
                        <strong class="me-auto succes-toasts-subject"></strong>
                        <small class="success-toasts-time"></small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">

                    </div>
                </div>
            </div>
        <div>
        @push('scripts')
            <script>
                const toastEl = document.getElementById('success-toasts');
                const toast = new bootstrap.Toast(toastEl);
                window.addEventListener('show-success-toast', event => {
                    document.querySelector('.succes-toasts-subject').innerText = event.detail[0].subject;
                    document.querySelector('.toast-body').innerText = event.detail[0].message;
                    animateValue(document.querySelector('.success-toasts-time'), 3, 0, 3000);
                    toast.show();
                });
            </script>
        @endpush
        HTML;
    }
}
