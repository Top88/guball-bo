<?php

namespace App\Infrastructure\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

trait LivewireComponentTrait
{
    /**
     * @return RedirectResponse
     *
     * @throws BindingResolutionException
     */
    public static function redirectBack(string $default = 'index')
    {
        return redirect()->back(fallback: route($default));
    }

    /**
     * @return void
     *
     * @throws BindingResolutionException
     */
    public static function IsAuth()
    {
        if (Auth::check()) {
            self::redirectBack();
        }
    }

    public function showError()
    {
        $this->dispatch('sweet-alert', [
            'type' => 'error',
            'title' => __('website.alert.error'),
            'text' => __('website.alert.error_message'),
        ]);
    }
}
