<?php
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (!function_exists('load_php_files')) {
    /**
     *
     * @param string $directory
     * @return void
     */
    function load_php_files(string $directory)
    {
        try {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator((string)$directory));
            $file_array = [];
            while ($iterator->valid()) {
                if (!$iterator->isDot() && $iterator->isFile() && $iterator->isReadable() && 'php' === $iterator->current()->getExtension()) {
                    $file_array[] = $iterator->key();
                }

                $iterator->next();
            }

            rsort($file_array);

            foreach ($file_array as $file) {
                require_once $file;
            }
        } catch (Exception $exception) {
            report($exception);
        }
    }

    if (!function_exists('is_json')) {
        /**
         *
         * @param string $string
         * @return string
         */
        function is_json($string)
        {
            return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
        }
    }

    if (!function_exists('create_uid')) {
        function create_uid($id)
        {
            $uid = format_uid($id+1);
            return config('conts.uid_str').$uid;
        }
    }

    if (!function_exists('format_uid')) {
        function format_uid($number, $length = 5)
        {
            return str_pad($number, $length, '0', STR_PAD_LEFT);
        }
    }
}