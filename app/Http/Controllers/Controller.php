<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param bool $status
     * @param false $msg
     */
    public function putFlashMessage(bool $status, $msg = false)
    {
        if ($status) {
            session()->flash('success', $msg ?? 'Successfully');
        } else {
            session()->flash('error', $msg ??'Something went wrong');
        }

    }
}
