<?php

namespace App\Traits;

use App\Domain\Football\Match\PredictionResult;

trait PredictionResultBadge
{
    public static function toBadge(?string $value): string
    {
        $text = "<span class=':class'>:text</span>";
        switch (PredictionResult::tryFrom($value)) {
            case PredictionResult::WIN:
                $text = str_replace(':text', 'W', $text);
                $text = str_replace(':class', 'dot-w', $text);
                break;
            case PredictionResult::HALF:
                $text = str_replace(':text', 'H', $text);
                $text = str_replace(':class', 'dot-h', $text);
                break;
            case PredictionResult::DRAW:
                $text = str_replace(':text', 'D', $text);
                $text = str_replace(':class', 'dot-d', $text);
                break;
            case PredictionResult::LOSE:
                $text = str_replace(':text', 'L', $text);
                $text = str_replace(':class', 'dot-l', $text);
                break;
            default:
                $text = '
                <span class="btn btn-md btn-danger">รอผล ?</span>
                ';
                break;
        }

        return $text;
    }
}
