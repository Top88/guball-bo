<?php
namespace App\Domain\UserManagement;

enum ChangeSideEnum:string {
    case INCREMENT = 'increment';
    case DECREMENT = 'decrement';
}