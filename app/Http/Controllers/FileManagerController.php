<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Request;
use App\Models\Firma;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{

    public function fileList($companyId, Request $request)
    {
        $company = Firma::findOrFail($companyId);

        $folderName = $company->folder_name;

        // ako nema path → ROOT nivo
        $path = $request->get('path');

        // ✅ ROOT: prikazi 2 glavna foldera
        if (!$path) {
            return response()->json([
                [
                    'name' => 'Ausgangsrechnungen',
                    'type' => 'Folder',
                    'size' => '-',
                    'date' => '-',
                    'path' => 'izlazne-fakture/' . $folderName,
                    'is_dir' => true
                ],
                [
                    'name' => 'Eingangsrechnungen',
                    'type' => 'Folder',
                    'size' => '-',
                    'date' => '-',
                    'path' => 'ulazne-fakture/' . $folderName,
                    'is_dir' => true
                ]
            ]);
        }

        $fullPath = storage_path('app/public/' . $path);

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        $items = [];

        foreach (scandir($fullPath) as $item) {
            if ($item == '.' || $item == '..') continue;

            $itemPath = $fullPath . '/' . $item;
            $isDir = is_dir($itemPath);

            $items[] = [
                'name' => $item,
                'type' => $isDir ? 'Folder' : 'Fajl',
                'size' => $isDir ? '-' : round(filesize($itemPath)/1024, 2).' KB',
                'date' => date("d.m.Y H:i", filemtime($itemPath)),
                'path' => $path . '/' . $item,
                'is_dir' => $isDir
            ];
        }

        return response()->json($items);
    }

}
