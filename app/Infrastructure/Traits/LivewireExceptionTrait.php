<?php

namespace App\Infrastructure\Traits;

trait LivewireExceptionTrait
{
    public function exception($e, $stopPropagation)
    {
        if (! ($e instanceof \Illuminate\Validation\ValidationException)) {
            report($e);
            $this->dispatch('sweet-alert', ['type' => 'error', 'title' => 'มีบางอย่างผิดพลาด']);
        }
        $stopPropagation();
    }
}
