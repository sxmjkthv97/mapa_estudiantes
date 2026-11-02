<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/MapaEstudiantes.php';
require_once __DIR__ . '/../config/ValidadorEstudiante.php';

header('Content-Type: application/json; charset=utf-8');

function responder(int $codigoHttp, array $respuesta): void
{
    http_response_code($codigoHttp);
    echo json_encode(
        $respuesta,
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE
    );
    exit;
}

$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$mapa = new MapaEstudiantes();

if ($metodo === 'GET') {
    if (array_key_exists('codigo', $_GET)) {
        $codigoRecibido = $_GET['codigo'];

        if (!is_string($codigoRecibido) || trim($codigoRecibido) === '') {
            responder(400, [
                'exito' => false,
                'mensaje' => 'El parámetro codigo debe ser un texto no vacío.',
                'datos' => null,
            ]);
        }

        $codigo = strtoupper(trim($codigoRecibido));
        $estudiante = $mapa->obtener($codigo);

        if ($estudiante === null) {
            responder(404, [
                'exito' => false,
                'mensaje' => 'No se encontró un estudiante con el código ' . $codigo . '.',
                'datos' => null,
            ]);
        }

        responder(200, [
            'exito' => true,
            'mensaje' => 'Estudiante encontrado.',
            'datos' => $estudiante->toArray(),
        ]);
    }

    $estudiantes = [];

    foreach ($mapa->listar() as $estudiante) {
        $estudiantes[] = $estudiante->toArray();
    }

    responder(200, [
        'exito' => true,
        'mensaje' => 'Lista de estudiantes registrados.',
        'total' => count($estudiantes),
        'datos' => $estudiantes,
    ]);
}

if ($metodo === 'POST') {
    $contenido = file_get_contents('php://input');
    $tipoContenido = $_SERVER['CONTENT_TYPE'] ?? '';

    if (stripos($tipoContenido, 'application/json') !== false) {
        $json = json_decode($contenido === false ? '' : $contenido, true);

        if (!is_array($json)) {
            responder(400, [
                'exito' => false,
                'mensaje' => 'El cuerpo de la petición debe ser un JSON válido.',
                'errores' => ['No se pudo interpretar el JSON enviado.'],
            ]);
        }

        $datos = $json;
    } else {
        $datos = $_POST;
    }

    $resultado = ValidadorEstudiante::validar($datos);

    if (!$resultado['valido']) {
        responder(400, [
            'exito' => false,
            'mensaje' => 'Los datos enviados no son válidos.',
            'errores' => $resultado['errores'],
        ]);
    }

    $datosValidados = $resultado['datos'];

    $estudiante = new Estudiante(
        $datosValidados['nombre'],
        $datosValidados['apellido'],
        $datosValidados['edad'],
        $datosValidados['codigo'],
        $datosValidados['carrera']
    );

    if (!$mapa->agregar($estudiante)) {
        responder(409, [
            'exito' => false,
            'mensaje' => 'Ya existe un estudiante con el código ' . $datosValidados['codigo'] . '.',
            'errores' => ['El código debe ser único.'],
        ]);
    }

    responder(201, [
        'exito' => true,
        'mensaje' => 'Estudiante registrado correctamente.',
        'datos' => $estudiante->toArray(),
    ]);
}

header('Allow: GET, POST');

responder(405, [
    'exito' => false,
    'mensaje' => 'Método no permitido. Utilice GET o POST.',
    'datos' => null,
]);
