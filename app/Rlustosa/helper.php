<?php

use App\Rlustosa\GenericImageUpload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('isActiveRoute')) {

    function isActiveRoute($route, $output = 'active')
    {
        if (is_array($route)) {
            foreach ($route as $rte) {

                if (route_is($rte)) {
                    return $output;
                }
            }
        } else {

            if (route_is($route)) {
                return $output;
            }
        }
    }
}

if (!function_exists('getCurrency')) {

    function getCurrency($value, $precision = 2)
    {
        return number_format($value, $precision, ',', '.');
    }
}

if (!function_exists('isValidSearchField')) {

    function isValidSearchField($field, $order, Model $model)
    {
        $fields = $model->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($model->getTable());

        $validOrder = $order == 'ASC' || $order == 'DESC';
        $validField = in_array($field, $fields);

        return $validOrder && $validField;
    }
}

if (!function_exists('setCurrency')) {

    function setCurrency($value)
    {
        return str_replace(',', '.', str_replace('.', '', $value));
    }
}

if (!function_exists('roundCurrency')) {

    function roundCurrency($value)
    {
        return number_format($value, 2, '.', '');
    }
}

if (!function_exists('userHasAnyLevel')) {

    function userHasAnyLevel($type = [], $user = null): bool
    {

        if ($user === null) {

            $user = Auth::user();
        }

        if (empty($type)) {

            return false;
        } else {

            if (!is_array($type)) {

                $type = [$type];
            }
        }

        return $user->userTypes
            ->whereIn('type_id', $type)
            ->count() ? true : false;
    }
}

if (!function_exists('mask')) {

    function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}

if (!function_exists('userAvatarCrop')) {

    function userAvatarCrop($path, $width = 300)
    {
        if (empty($path)) {
            return asset('default-user-avatar/' . $width);
        } else {
            return asset('images/' . $width . '/' . $path);
        }
    }
}

if (!function_exists('formatDocumentNumber')) {

    function formatDocumentNumber($value): ?string
    {

        $value = preg_replace('/[^0-9]/', '', $value);

        if (strlen($value) == 14) {
            $mask = '##.###.###/####-##';
            $value = mask($value, $mask);
        } elseif (strlen($value) == 11) {

            $mask = '###.###.###-##';
            $value = mask($value, $mask);
        } else {

            $value = null;
        }

        return $value;
    }
}

if (!function_exists('uploadBase64')) {

    function uploadBase64($dataEncoded, $folder, $filename = null)
    {
        $partials = explode(",", $dataEncoded);
        $encodedFile = $partials[1];
        $decodedFile = base64_decode($encodedFile);

        $fileName = 'files/' . $folder . '/' . Carbon::now('America/Fortaleza')->format('YmdHis') . '_' .
            Str::random(4) . '_' .
            rand(10, 99) . rand(10, 99) . rand(10, 99) . '_' .
            Str::random(4) .
            $filename;


        $filePath = storage_path() . '/' . $fileName;

        File::put($filePath, $decodedFile);
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        $filesize = filesize($filePath);

        return [
            'file' => $fileName,
            'filename' => $fileName['filename'],
            'extension' => $ext,
            'size' => $filesize,
        ];
    }
}

if (!function_exists('upload')) {
    function upload($file, $folder)
    {

        $nome_arquivo = Carbon::now('America/Fortaleza')->format('YmdHis') . '_' .
            Str::random(4) . '_' .
            rand(100, 999) . rand(100, 999) . rand(100, 999) . '_' .
            Str::random(4) . '.' .
            $file->guessClientExtension();

        $path = $file->storeAs(
            'files/' . $folder, $nome_arquivo
        );

        return [
            'file' => $path,
            'filename' => $file->getClientOriginalName(),
            'extension' => $file->guessClientExtension(),
            'size' => $file->getClientSize(),
        ];
    }
}

if (!function_exists("randHash")) {

    function randHash($len = 32)
    {
        return substr(md5(openssl_random_pseudo_bytes(20)), -$len);
    }
}

if (!function_exists("formatsUserCreationData")) {

    function formatsUserCreationData($creationData)
    {
        return '
                                                    <i class="fa fa-list-ul" data-toggle="tooltip" data-placement="bottom" title="' . implode('<br>', $creationData) . '"></i>

                                                    <!--small>
                                                        <em>
                                                            ' . implode('<br>', $creationData) . '
                                                        </em>
                                                    </small-->
        ';
    }
}

if (!function_exists("uploadWithCrop")) {

    function uploadWithCrop($fieldName, $module): ?string
    {

        if (request()->file($fieldName)) {

            return GenericImageUpload::store(request()->file($fieldName), $module);
        } else {

            return null;
        }
    }
}

if (!function_exists("uploadGalleryWithCrop")) {

    function uploadGalleryWithCrop($fieldName, $module, $parentId): ?array
    {
        $files = request()->file($fieldName);
        if (count($files)) {
            $images = array();
            foreach ($files as $file) {

                $images[] = GenericImageUpload::storeGallery($file, $module, $parentId);
            }
            return $images;
        } else {

            return null;
        }
    }
}

if (!function_exists("uploadWithoutCrop")) {

    function uploadWithoutCrop($fieldName, $module): ?string
    {

        if (request()->file($fieldName)) {

            return GenericImageUpload::store(request()->file($fieldName), $module, true);
        } else {

            return null;
        }
    }
}

if (!function_exists("booleanInTd")) {

    function booleanInTd($value): ?string
    {

        $label = ((boolean)$value) ? 'Sim' : 'Não';
        return '<span class="label label-' . ($value ? 'primary' : 'label-warning') . '">' . $label . '</span>';
    }
}

if (!function_exists("onlyNumbers")) {

    function onlyNumbers($string): ?string
    {

        return preg_replace('/[^0-9]/', '', $string);
    }
}

if (!function_exists("linkToImageInTd")) {

    function linkToImageInTd($id, $image, $folder, $showCrop = true): string
    {

        if (!empty($image)) {
            $link = '
            <a href="' . asset($image) . '" target="_blank">
                <img src="' . asset($image) . '" width="80">
            </a>
            <br/>';
            if ($showCrop) {

                $link .= '<a href="' . route($folder . '.imageCrop', [$id]) . '">
                <i class="fa fa-crop"></i>
                Recortar
            </a>';
            }

            return $link;
        } else {

            return '';
        }
    }
}

if (!function_exists('route_is')) {
    /**
     * Check if route(s) is the current route.
     *
     * @param array|string $routes
     *
     * @return bool
     */
    function route_is($routes)
    {
        if (!is_array($routes)) {
            $routes = [$routes];
        }

        /** @var Illuminate\Routing\Router $router */
        $router = app('router');

        return call_user_func_array([$router, 'is'], $routes);
    }
}

if (!function_exists('uploadUserImage')) {
    function uploadUserImage($image, $name)
    {

        $decodedFile = base64_decode($image);

        $ext = explode(".", $name);

        if (count($ext) == 2) {

            $ext = $ext[1];
        } else {
            $ext = 'png';
        }

        $folder = 'users';

        $fileName = 'images/' . $folder . '/Original/' . Carbon::now('America/Fortaleza')->format('YmdHis') . '_' .
            Str::random(4) . '_' .
            rand(10, 99) . rand(10, 99) . rand(10, 99) . '_' .
            Str::random(4) . '.' .
            $ext;

        $filePath = storage_path('app/') . $fileName;
        File::put($filePath, $decodedFile);
        $filesize = filesize($filePath);

        $img = Image::make($filePath);
        $img->resize(config('upload.users.width'), config('upload.users.height'), function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $payload = (string)$img->encode();
        Storage::put(
            str_replace('/Original', '', $fileName),
            $payload
        );

        return [
            'file' => str_replace('images/', '', str_replace('/Original', '', $fileName)),
            //'filename' => $file['upload']['filename'],
            'extension' => $ext,
            'size' => $filesize,
        ];

    }
}
