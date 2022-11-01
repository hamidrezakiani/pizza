<?php

namespace App\Lib;

trait ResponseTemplate
{
    protected $data = [];
    protected $errors = [];
    protected $status = 200;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function response()
    {
        return response()->json(['data' => $this->data,'errors' => $this->errors],$this->status);
    }
}
