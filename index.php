<?php

declare(strict_types=1);

require_once __DIR__ . '/config/MapaEstudiantes.php';
require_once __DIR__ . '/config/ValidadorEstudiante.php';

function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

$mapa = new MapaEstudiantes();

$mensaje = '';
$tipoMensaje = '';
$errores = [];
$datosFormulario = [
    'nombre' => '',
    'apellido' => '',
    'edad' => '',
    'codigo' => '',
    'carrera' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campoPost = static function (string $campo): string {
        $valor = $_POST[$campo] ?? '';
        return is_string($valor) ? trim($valor) : '';
    };

    $datosFormulario = [
        'nombre' => $campoPost('nombre'),
        'apellido' => $campoPost('apellido'),
        'edad' => $campoPost('edad'),
        'codigo' => $campoPost('codigo'),
        'carrera' => $campoPost('carrera'),
    ];

    $resultado = ValidadorEstudiante::validar($_POST);

    if (!$resultado['valido']) {
        $errores = $resultado['errores'];
        $mensaje = 'No se pudo registrar el estudiante:';
        $tipoMensaje = 'error';
    } else {
        $datos = $resultado['datos'];

        $estudiante = new Estudiante(
            $datos['nombre'],
            $datos['apellido'],
            $datos['edad'],
            $datos['codigo'],
            $datos['carrera']
        );

        if ($mapa->agregar($estudiante)) {
            $mensaje = 'Estudiante registrado correctamente con el código ' . $datos['codigo'] . '.';
            $tipoMensaje = 'exito';
            $datosFormulario = [
                'nombre' => '',
                'apellido' => '',
                'edad' => '',
                'codigo' => '',
                'carrera' => '',
            ];
        } else {
            $errores[] = 'Ya existe un estudiante con el código ' . $datos['codigo'] . '.';
            $mensaje = 'No se pudo registrar el estudiante:';
            $tipoMensaje = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Estudiantes</title>
    <style>
        body {
            margin: 0;
            padding: 24px 16px;
            background-color: #f2f2f2;
            color: #222222;
            font-family: Arial, Helvetica, sans-serif;
        }

        .contenedor {
            max-width: 600px;
            margin: 0 auto;
            padding: 24px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-top: 0;
            text-align: center;
            font-size: 24px;
        }

        h2 {
            font-size: 18px;
        }

        label {
            display: block;
            margin-bottom: 4px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 15px;
        }

        .campo {
            margin-bottom: 12px;
        }

        button {
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            background-color: #1d4ed8;
            color: #ffffff;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #1e3a8a;
        }

        .alerta {
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 4px;
        }

        .alerta p {
            margin: 0;
        }

        .alerta ul {
            margin: 8px 0 0;
            padding-left: 20px;
        }

        .alerta-exito {
            background-color: #dcfce7;
            color: #166534;
        }

        .alerta-error {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .separador {
            margin: 24px 0;
            border: 0;
            border-top: 1px solid #dddddd;
        }

        a {
            color: #1d4ed8;
        }
    </style>
</head>
<body>
    <main class="contenedor">
        <h1>Mapa de Estudiantes</h1>

        <?php if ($mensaje !== ''): ?>
            <div class="alerta <?= $tipoMensaje === 'exito' ? 'alerta-exito' : 'alerta-error' ?>">
                <p><?= e($mensaje) ?></p>
                <?php if (count($errores) > 0): ?>
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <h2>Registrar estudiante</h2>

        <form method="post" action="index.php" autocomplete="off">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    maxlength="60"
                    value="<?= e($datosFormulario['nombre']) ?>"
                    required
                >
            </div>

            <div class="campo">
                <label for="apellido">Apellido</label>
                <input
                    type="text"
                    id="apellido"
                    name="apellido"
                    maxlength="60"
                    value="<?= e($datosFormulario['apellido']) ?>"
                    required
                >
            </div>

            <div class="campo">
                <label for="edad">Edad</label>
                <input
                    type="number"
                    id="edad"
                    name="edad"
                    min="1"
                    max="120"
                    value="<?= e($datosFormulario['edad']) ?>"
                    required
                >
            </div>

            <div class="campo">
                <label for="codigo">Código</label>
                <input
                    type="text"
                    id="codigo"
                    name="codigo"
                    maxlength="20"
                    pattern="[A-Za-z0-9-]{3,20}"
                    title="Entre 3 y 20 caracteres: letras, números y guiones."
                    value="<?= e($datosFormulario['codigo']) ?>"
                    required
                >
            </div>

            <div class="campo">
                <label for="carrera">Carrera</label>
                <input
                    type="text"
                    id="carrera"
                    name="carrera"
                    maxlength="80"
                    value="<?= e($datosFormulario['carrera']) ?>"
                    required
                >
            </div>

            <button type="submit">Registrar estudiante</button>
        </form>

        <hr class="separador">

        <h2>Consultar estudiantes (GET)</h2>

        <p>
            <a href="api/estudiantes.php" target="_blank" rel="noopener">
                Ver todos los estudiantes en formato JSON
            </a>
        </p>

        <form method="get" action="api/estudiantes.php" target="_blank">
            <div class="campo">
                <label for="codigo_busqueda">Selecciona un estudiante</label>
                <select id="codigo_busqueda" name="codigo" required>
                    <?php foreach ($mapa->listar() as $codigo => $estudiante): ?>
                        <option value="<?= e($codigo) ?>">
                            <?= e($codigo) ?> - <?= e($estudiante->getNombre() . ' ' . $estudiante->getApellido()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Consultar</button>
        </form>
    </main>
</body>
</html>
