<?php

namespace App\Services;

class ResponseService
{
    public int $status = 201;
    public string $message = 'success';
    public array $data = [];
    public array $errors = [];

    public function status(int $status): ResponseService
    {
        $this->status = $status;
        return $this;
    }


    public function message(string $message): ResponseService
    {
        $this->message = __($message);
        return $this;
    }


    public function data($data): ResponseService
    {
        $this->data = is_array($data) ? $data : [$data];
        return $this;
    }

    public function errors($errors): ResponseService
    {
        $this->errors = is_array($errors) ? $errors : [$errors];
        return $this;
    }


    public function send()
    {
        return response([
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors,
        ], $this->status);
    }
}
