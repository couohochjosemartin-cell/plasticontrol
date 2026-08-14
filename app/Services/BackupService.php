<?php

namespace App\Services;

use RuntimeException;
use Symfony\Component\Process\Process;

class BackupService
{
    private string $mysqldumpPath =
        'C:\wamp64\bin\mysql\mysql8.3.0\bin\mysqldump.exe';

    public function crearRespaldo(): string
    {
        $nombreArchivo = sprintf(
            'plasticontrol_%s.sql',
            now()->format('Y-m-d_H-i-s')
        );

        $directorio = storage_path(
            'app/backups'
        );

        if (! is_dir($directorio)) {
            mkdir(
                $directorio,
                0755,
                true
            );
        }

        $rutaArchivo = $directorio
            . DIRECTORY_SEPARATOR
            . $nombreArchivo;

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host', '127.0.0.1');
        $port = config('database.connections.mysql.port', '3306');

        $comando = [
    $this->mysqldumpPath,
    '--host=' . $host,
    '--port=' . $port,
    '--protocol=TCP',
    '--user=' . $username,
];

if ($password !== null && $password !== '') {
    $comando[] = '--password=' . $password;
}

$comando[] = '--single-transaction';
$comando[] = '--routines';
$comando[] = '--triggers';
$comando[] = '--events';
$comando[] = '--default-character-set=utf8mb4';
$comando[] = $database;

        $process = new Process(
    $comando,
    null,
    [
        'SystemRoot' => getenv('SystemRoot')
            ?: 'C:\Windows',

        'WINDIR' => getenv('WINDIR')
            ?: 'C:\Windows',

        'PATH' => getenv('PATH') ?: '',
    ]
);

        $process->setTimeout(120);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                'No fue posible crear el respaldo: '
                . $process->getErrorOutput()
            );
        }

        file_put_contents(
            $rutaArchivo,
            $process->getOutput()
        );

        if (
            ! is_file($rutaArchivo)
            || filesize($rutaArchivo) === 0
        ) {
            throw new RuntimeException(
                'El respaldo se generó vacío.'
            );
        }

        return $rutaArchivo;
    }
}