<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BackupController extends Controller
{
    public function index()
    {
        return view('pages.backup.index');
    }

public function backup()
{
    $db = env('DB_DATABASE');
    $user = env('DB_USERNAME');
    $pass = env('DB_PASSWORD');
    $filename = 'backup'.'.sql';

$backupPath = storage_path('app/backup');

if (!file_exists($backupPath)) {
    mkdir($backupPath, 0777, true);
}

// FIX PATH WINDOWS
$path = str_replace('/', '\\', $backupPath . '\\' . $filename);

$mysqldump = 'C:\xampp\mysql\bin\mysqldump.exe';

$command = "cmd /c \"$mysqldump\" -u$user $db > \"$path\" 2>&1";

exec($command, $output, $result);

// dd([
//     "COMMAND" => $command,
//     "RESULT" => $result,
//     "OUTPUT" => implode("\n", $output)
// ]);
    if ($result !== 0 || filesize($path) == 0) {
        return back()->with('error', 'Backup gagal! Periksa konfigurasi mysqldump.');
    }

    return response()->download($path)->deleteFileAfterSend(true);
}


public function restore(Request $request)
{
    $request->validate([
        'backup_file' => 'required|file|mimes:sql,txt'
    ]);

    // Simpan file upload ke folder storage/app/tmp/
    $file = $request->file('backup_file');
    $tempPath = storage_path('app/tmp');
    if (!file_exists($tempPath)) mkdir($tempPath, 0777, true);

    $sqlPath = $tempPath . '/' . $file->getClientOriginalName();
    $file->move($tempPath, $file->getClientOriginalName());

    // Ambil env
    $mysql = 'C:\xampp\mysql\bin\mysql.exe'; // sesuaikan jika pakai Laragon/Wamp
    $db    = env('DB_DATABASE');
    $user  = env('DB_USERNAME');
    $pass  = env('DB_PASSWORD');
    $host  = env('DB_HOST', '127.0.0.1');
    $port  = env('DB_PORT', 3306);

    // Jika password kosong, JANGAN pakai -p
    if ($pass == '' || $pass == null) {
        $command = 'cmd /c "' . $mysql . '" -h ' . $host . ' -P ' . $port . ' -u ' . $user . ' ' . $db . ' < "' . $sqlPath . '"';
    } else {
        $command = 'cmd /c "' . $mysql . '" -h ' . $host . ' -P ' . $port . ' -u ' . $user . ' -p' . $pass . ' ' . $db . ' < "' . $sqlPath . '"';
    }

    // Jalankan command
    exec($command, $output, $result);

    // Hapus file temp
    if (file_exists($sqlPath)) unlink($sqlPath);

    if ($result !== 0) {
        return back()->with('error', 'Restore gagal. Periksa konfigurasi atau isi file SQL.');
    }

    return back()->with('success', 'Restore database berhasil!');
}

}
