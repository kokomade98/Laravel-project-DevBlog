<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

        public static function response_json(
        string $message,
        int $http_response_code = 202,
        $data = null
    ): JsonResponse {
        $is_no_data = !(bool)$data;

        if ($is_no_data) {
            return response()->json([
                'message' => $message
            ], $http_response_code);
        }

        return response()->json([
            'message' => $message,
            'info' => $data
        ], $http_response_code);
    }

    public static function response_json_error(
        string $message
    ): JsonResponse {
        return self::response_json($message);
    }

    public static function request_validator(
        Request $request,
        array $rules,
        string $error_msg
    ) {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => $error_msg,
                'error' => $validator->errors(),
            ], 422);
        }

        return true;
    }

    public static function set_img(
        object $upload_img_file,
        string $img_file_name,
        string $folder_name
    ): string {
        $extension = $upload_img_file->extension();                                        /* 확장자 얻기 */
        $storage_path = "{$folder_name}/{$img_file_name}.{$extension}";

        Storage::putFileAs('public', $upload_img_file, $storage_path);                     /* 파일 저장 후 경로 반환 */

        return $storage_path;
    }

    public static function get_img(
        string $storage_path
    ) {
        $data = Storage::get('public/' . $storage_path);
        $type = pathinfo('storage/' . $storage_path, PATHINFO_EXTENSION);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
