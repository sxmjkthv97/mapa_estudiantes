<?php

declare(strict_types=1);

class ValidadorEstudiante
{
    public static function validar(array $datos): array
    {
        $errores = [];

        $nombre = self::obtenerTexto($datos, 'nombre');
        $apellido = self::obtenerTexto($datos, 'apellido');
        $codigo = strtoupper(self::obtenerTexto($datos, 'codigo'));
        $carrera = self::obtenerTexto($datos, 'carrera');

        if ($nombre === '') {
            $errores[] = 'El nombre es obligatorio.';
        } elseif (strlen($nombre) > 60) {
            $errores[] = 'El nombre no debe superar los 60 caracteres.';
        }

        if ($apellido === '') {
            $errores[] = 'El apellido es obligatorio.';
        } elseif (strlen($apellido) > 60) {
            $errores[] = 'El apellido no debe superar los 60 caracteres.';
        }

        if ($codigo === '') {
            $errores[] = 'El código es obligatorio.';
        } elseif (!preg_match('/^[A-Z0-9-]{3,20}$/', $codigo)) {
            $errores[] = 'El código debe tener entre 3 y 20 caracteres y solo puede contener letras, números y guiones.';
        }

        if ($carrera === '') {
            $errores[] = 'La carrera es obligatoria.';
        } elseif (strlen($carrera) > 80) {
            $errores[] = 'La carrera no debe superar los 80 caracteres.';
        }

        $edad = 0;
        $edadRecibida = $datos['edad'] ?? null;

        if (
            is_bool($edadRecibida)
            || (!is_int($edadRecibida) && !is_string($edadRecibida))
            || (is_string($edadRecibida) && trim($edadRecibida) === '')
        ) {
            $errores[] = 'La edad es obligatoria y debe ser un número entero.';
        } else {
            $edadLimpia = is_string($edadRecibida) ? trim($edadRecibida) : $edadRecibida;
            $edadValidada = filter_var($edadLimpia, FILTER_VALIDATE_INT);

            if ($edadValidada === false) {
                $errores[] = 'La edad debe ser un número entero.';
            } elseif ($edadValidada < 1 || $edadValidada > 120) {
                $errores[] = 'La edad debe estar entre 1 y 120 años.';
            } else {
                $edad = (int) $edadValidada;
            }
        }

        return [
            'valido' => count($errores) === 0,
            'errores' => $errores,
            'datos' => [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'edad' => $edad,
                'codigo' => $codigo,
                'carrera' => $carrera,
            ],
        ];
    }

    private static function obtenerTexto(array $datos, string $campo): string
    {
        if (!isset($datos[$campo]) || !is_string($datos[$campo])) {
            return '';
        }

        return trim($datos[$campo]);
    }
}
