# Mapa de Estudiantes en PHP

## 1. Descripción y objetivo

Este proyecto es una aplicación web desarrollada exclusivamente con PHP, HTML y CSS, sin frameworks y sin bases de datos MySQL.

Su objetivo es implementar una estructura de datos tipo mapa para gestionar estudiantes, aplicando:

- Programación Orientada a Objetos (POO).
- Herencia entre las clases Persona y Estudiante.
- Encapsulamiento mediante propiedades privadas y métodos públicos.
- Tipos estrictos con declare(strict_types=1);.
- Propiedades, parámetros y valores de retorno tipados.
- Una API que responde en formato JSON mediante peticiones HTTP GET y POST.
- Persistencia del mapa entre peticiones HTTP usando la sesión de PHP.

El mapa se implementa con un arreglo asociativo de PHP:
text
código del estudiante => objeto Estudiante

El código es la clave única, por lo que el sistema rechaza los códigos duplicados.


## 2. Requisitos necesarios

- XAMPP instalado (se recomienda XAMPP con PHP 8.0 o superior).
- Apacheiniciado desde el Panel de Control de XAMPP.
- Un navegador web actualizado.
- Las extensiones de PHP session y json habilitadas. XAMPP las incluye por defecto.
- Opcional para probar la API desde la terminal: curl, Postman o PowerShell.

No se necesita MySQL ni phpMyAdmin.

## 3. Estructura del proyecto

text
mapa_estudiantes/
│
├── clases/
│   ├── Persona.php
│   └── Estudiante.php
│
├── config/
│   ├── MapaEstudiantes.php
│   └── ValidadorEstudiante.php
│
├── api/
│   └── estudiantes.php
│
├── index.php
├── README.md
└── .gitignore

## 4. Instalación y ejecución en XAMPP

### Paso 1: Instalar XAMPP

Descarga e instala XAMPP desde <https://www.apachefriends.org/>.

### Paso 2: Copiar el proyecto a `htdocs`

Copia la carpeta mapa_estudiantes dentro de la carpeta htdocs de XAMPP.

En una instalación típica de Windows, la ruta queda así:

text
C:\xampp\htdocs\mapa_estudiantes


### Paso 3: Iniciar Apache

Abre el Panel de Control de XAMPPy pulsa Start en el módulo Apache.


### Paso 4: Abrir la aplicación

En el navegador entra a:


http://localhost/mapa_estudiantes/

También puedes abrir directamente la API:


http://localhost/mapa_estudiantes/api/estudiantes.php


### Ubicación de los archivos en el entorno local

El proyecto se desarrolló originalmente en:

C:\Users\LENOVO iP5\Documents\Proyecto predeterminado\mapa_estudiantes

Para ejecutarlo con XAMPP debe copiarse a:

C:\xampp\htdocs\mapa_estudiantes

La URL local correspondiente es:

http://localhost/mapa_estudiantes/


## 5. API: peticiones GET y POST

URL base de la API:

http://localhost/mapa_estudiantes/api/estudiantes.php


Todas las respuestas se devuelven en formato JSON con el encabezado:

Content-Type: application/json; charset=utf-8


### 5.1. GET: consultar todos los estudiantes

GET http://localhost/mapa_estudiantes/api/estudiantes.php


Respuesta esperada (`200 OK`):

json
{
    "exito": true,
    "mensaje": "Lista de estudiantes registrados.",
    "total": 1,
    "datos": [
        {
            "nombre": "Samantha Lucia",
            "apellido": "Moreno Zambrano",
            "edad": 20,
            "codigo": "SIS1092867",
            "carrera": "Ingeniería de Sistemas"
        }
    ]
}


### 5.2. GET: consultar un estudiante por código

GET http://localhost/mapa_estudiantes/api/estudiantes.php?codigo=SIS1092867


Respuesta esperada (`200 OK`):

json
{
    "exito": true,
    "mensaje": "Estudiante encontrado.",
    "datos": {
        "nombre": "Samantha Lucia",
        "apellido": "Moreno Zambrano",
        "edad": 20,
        "codigo": "SIS1092867",
        "carrera": "Ingeniería de Sistemas"
    }
}


Si el código no existe, responde `404 Not Found`:

json
{
    "exito": false,
    "mensaje": "No se encontró un estudiante con el código SIS9999999.",
    "datos": null
}


### 5.3. POST: registrar un estudiante

Se puede enviar el cuerpo en formato JSON:


POST http://localhost/mapa_estudiantes/api/estudiantes.php
Content-Type: application/json

Cuerpo de ejemplo:

json
{
    "nombre": "Carlos",
    "apellido": "Perez",
    "edad": 21,
    "codigo": "SIS1000001",
    "carrera": "Ingenieria de Sistemas"
}


Respuesta esperada (`201 Created`):

json
{
    "exito": true,
    "mensaje": "Estudiante registrado correctamente.",
    "datos": {
        "nombre": "Carlos",
        "apellido": "Perez",
        "edad": 21,
        "codigo": "SIS1000001",
        "carrera": "Ingenieria de Sistemas"
    }
}


También se aceptan datos enviados como formulario (`application/x-www-form-urlencoded`), por ejemplo desde Postman o curl.

### 5.4. Códigos HTTP utilizados

| Situación | Código HTTP |
|---|---|
| Consulta correcta de todos los estudiantes | `200 OK` |
| Consulta correcta de un estudiante | `200 OK` |
| Estudiante no encontrado | `404 Not Found` |
| Datos inválidos o incompletos | `400 Bad Request` |
| Estudiante registrado correctamente | `201 Created` |
| Código duplicado | `409 Conflict` |
| Método diferente de GET o POST | `405 Method Not Allowed` |


## 6. Ejemplos de uso

### 6.1. PowerShell (Windows)

Crear una sesión y consultar todos los estudiantes:

powershell
$sesion = New-Object Microsoft.PowerShell.Commands.WebRequestSession
Invoke-RestMethod 
  -Uri "http://localhost/mapa_estudiantes/api/estudiantes.php" 
  -Method Get 
  -WebSession $sesion

Consultar un estudiante por código:
powershell
Invoke-RestMethod `
  -Uri "http://localhost/mapa_estudiantes/api/estudiantes.php?codigo=SIS1092867" `
  -Method Get `
  -WebSession $sesion


Registrar un estudiante con JSON y conservar la sesión:

powershell
$cuerpo = @{
    nombre   = "Carlos"
    apellido = "Perez"
    edad     = 21
    codigo   = "SIS1000001"
    carrera  = "Ingenieria de Sistemas"
} | ConvertTo-Json

Invoke-RestMethod `
  -Uri "http://localhost/mapa_estudiantes/api/estudiantes.php" `
  -Method Post `
  -ContentType "application/json" `
  -Body $cuerpo `
  -WebSession $sesion

Comprobar que el nuevo estudiante se conservó:
powershell
Invoke-RestMethod `
  -Uri "http://localhost/mapa_estudiantes/api/estudiantes.php" `
  -Method Get `
  -WebSession $sesion


> Si utilizas acentos en Windows PowerShell y tienes problemas de codificación, envía el cuerpo como UTF-8 o usa `curl.exe` o Postman.



### 6.3. Probar un código duplicado

Envía dos veces el mismo POST con el código SIS123456789. La segunda vez debe responder:

json
{
    "exito": false,
    "mensaje": "Ya existe un estudiante con el código SIS1000001.",
    "errores": [
        "El código debe ser único."
    ]
}


## 8. Uso del formulario web

1. Abre en el navegador:


   http://localhost/mapa_estudiantes/


2. Completa los campos:
   - Nombre.
   - Apellido.
   - Edad.
   - Código.
   - Carrera.

3. Pulsa **Registrar estudiante**.

4. El formulario envía los datos mediante POST y muestra:
   - Un mensaje verde si el registro fue exitoso.
   - Un mensaje rojo con la lista de errores si los datos son inválidos o el código está duplicado.

5. En la sección **Consultar estudiantes (GET)** puedes:
   - Pulsar el enlace **Ver todos los estudiantes en formato JSON**.
   - Seleccionar un estudiante de la lista y pulsar **Consultar** para abrir la respuesta JSON de ese estudiante.



## 9. Evidencias para el aula virtual

Las capturas solicitadas son:

1. **Captura de GET:** abre `http://localhost/mapa_estudiantes/api/estudiantes.php` o consulta un código y toma una captura donde se vea la respuesta JSON.
2. **Captura de POST:** realiza un `POST` desde Postman, PowerShell o `curl` y toma una captura donde se vean los datos enviados y la respuesta `201` con el mensaje de éxito.

