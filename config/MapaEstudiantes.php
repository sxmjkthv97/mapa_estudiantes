<?php

declare(strict_types=1);

require_once __DIR__ . '/../clases/Persona.php';
require_once __DIR__ . '/../clases/Estudiante.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class MapaEstudiantes
{
    private const CLAVE_SESION = 'mapa_estudiantes';
    private const CODIGO_INICIAL = 'SIS1092867';
    private const EDAD_INICIAL = 20;

    private array $estudiantes = [];

    public function __construct()
    {
        if (isset($_SESSION[self::CLAVE_SESION]) && is_array($_SESSION[self::CLAVE_SESION])) {
            $this->estudiantes = $_SESSION[self::CLAVE_SESION];
        }

        if (!isset($this->estudiantes[self::CODIGO_INICIAL])) {
            $this->registrarEstudianteInicial();
        }
    }

    public function agregar(Estudiante $estudiante): bool
    {
        $codigo = $estudiante->getCodigo();

        if (isset($this->estudiantes[$codigo])) {
            return false;
        }

        $this->estudiantes[$codigo] = $estudiante;
        $this->guardarEnSesion();

        return true;
    }

    public function obtener(string $codigo): ?Estudiante
    {
        return $this->estudiantes[$codigo] ?? null;
    }

    public function existe(string $codigo): bool
    {
        return isset($this->estudiantes[$codigo]);
    }

    public function listar(): array
    {
        return $this->estudiantes;
    }

    public function total(): int
    {
        return count($this->estudiantes);
    }

    private function guardarEnSesion(): void
    {
        $_SESSION[self::CLAVE_SESION] = $this->estudiantes;
    }

    private function registrarEstudianteInicial(): void
    {
        $estudiante = new Estudiante(
            'Samantha Lucia',
            'Moreno Zambrano',
            self::EDAD_INICIAL,
            self::CODIGO_INICIAL,
            'Ingeniería de Sistemas'
        );

        $this->estudiantes[$estudiante->getCodigo()] = $estudiante;
        $this->guardarEnSesion();
    }
}
