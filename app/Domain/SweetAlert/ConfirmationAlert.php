<?php

namespace App\Domain\SweetAlert;

use Illuminate\Contracts\Support\Arrayable;

class ConfirmationAlert implements Arrayable
{
    /**
     * @return void
     */
    public function __construct(
        private string $title,
        private string $text,
        private string $type,
        private string $confirmButtonText,
        private string $confirmedAction,
        private ?array $data = null,
    ) {}

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the value of text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the value of confirmButtonText
     */
    public function getConfirmButtonText()
    {
        return $this->confirmButtonText;
    }

    /**
     * Get the value of icon
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the value of confirmedAction
     */
    public function getConfirmedAction()
    {
        return $this->confirmedAction;
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }

    public function toArray()
    {
        return [
            'title' => $this->getTitle(),
            'text' => $this->getText(),
            'icon' => $this->getType(),
            'confirm_button_text' => $this->getConfirmButtonText(),
            'confirmed_action' => $this->getConfirmedAction(),
            'data' => $this->getData(),
        ];
    }
}
