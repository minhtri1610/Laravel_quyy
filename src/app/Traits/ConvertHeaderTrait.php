<?php

namespace App\Traits;

trait ConvertHeaderTrait
{
    public function convertToSnakeHeaders(array $headers): array
    {
        return array_map(function ($header) {
            return $this->toSnake($header);
        }, $headers);
    }

    private function toSnake(string $value): string
    {
        $value = $this->removeVietnameseAccents($value);
        $value = preg_replace('/[^a-zA-Z0-9\s]/u', '', $value); // bỏ ký tự đặc biệt
        $value = preg_replace('/\s+/', '_', trim($value));      // thay khoảng trắng bằng _
        return strtolower($value);
    }

    private function removeVietnameseAccents(string $str): string
    {
        $accents = [
            'a'=>'áàạảãâấầậẩẫăắằặẳẵ',
            'e'=>'éèẹẻẽêếềệểễ',
            'i'=>'íìịỉĩ',
            'o'=>'óòọỏõôốồộổỗơớờợởỡ',
            'u'=>'úùụủũưứừựửữ',
            'y'=>'ýỳỵỷỹ',
            'd'=>'đ',
        ];

        foreach ($accents as $nonAccent => $accented) {
            $str = preg_replace('/['.$accented.']/u', $nonAccent, $str);
            $str = preg_replace('/['.mb_strtoupper($accented).']/u', strtoupper($nonAccent), $str);
        }

        return $str;
    }
}
