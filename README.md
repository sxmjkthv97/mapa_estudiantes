# Mapa de Estudiantes en PHP

## 1. Descripción y objetivo

Este proyecto es una aplicación web desarrollada **exclusivamente con PHP, HTML y CSS**, sin frameworks y sin bases de datos MySQL.

Su objetivo es implementar una **estructura de datos tipo mapa** para gestionar estudiantes, aplicando:

- Programación Orientada a Objetos (POO).
- Herencia entre las clases `Persona` y `Estudiante`.
- Encapsulamiento mediante propiedades privadas y métodos públicos.
- Tipos estrictos con `declare(strict_types=1);`.
- Propiedades, parámetros y valores de retorno tipados.
- Una API que responde en formato JSON mediante peticiones HTTP `GET` y `POST`.
- Persistencia del mapa entre peticiones HTTP usando la sesión de PHP.

El mapa se implementa con un arreglo asociativo de PHP:

```text
código del estudiante => objeto Estudiante
```

El código es la clave única, por lo que el sistema rechaza los códigos duplicados.

---

## 2. Requisitos necesarios

- **XAMPP** instalado (se recomienda XAMPP con PHP 8.0 o superior).
- **Apache** iniciado desde el Panel de Control de XAMPP.
- Un navegador web actualizado.
- Las extensiones de PHP `session` y `json` habilitadas. XAMPP las incluye por defecto.
- Opcional para probar la API desde la terminal: `curl`, Postman o PowerShell.

No se necesita MySQL ni phpMyAdmin.

> El proyecto fue probado con **XAMPP para Windows, PHP 8.2.12 y Apache 2.4.58**.

---

## 3. Estructura del proyecto

```text
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
```

### ¿Qué contiene cada archivo?

| Archivo | Función |
|---|---|
| `clases/Persona.php` | Clase base con los atributos privados `nombre`, `apellido` y `edad`. |
| `clases/Estudiante.php` | Hereda de `Persona` y agrega `codigo` y `carrera`. Incluye `toArray()` para responder en JSON. |
| `config/MapaEstudiantes.php` | Implementa el mapa, guarda los registros en la sesión y registra automáticamente a la estudiante inicial. |
| `config/ValidadorEstudiante.php` | Valida y normaliza los datos recibidos. Se reutiliza en el formulario y en la API. |
| `api/estudiantes.php` | API JSON para las peticiones `GET` y `POST`. |
| `index.php` | Formulario web, mensajes de resultado y consultas GET. |
| `README.md` | Documentación del proyecto. |

---

## 4. Instalación y ejecución en XAMPP

### Paso 1: Instalar XAMPP

Descarga e instala XAMPP desde <https://www.apachefriends.org/>.

### Paso 2: Copiar el proyecto a `htdocs`

Copia la carpeta `mapa_estudiantes` dentro de la carpeta `htdocs` de XAMPP.

En una instalación típica de Windows, la ruta queda así:

```text
C:\xampp\htdocs\mapa_estudiantes
```

### Paso 3: Iniciar Apache

Abre el **Panel de Control de XAMPP** y pulsa **Start** en el módulo **Apache**.

No es necesario iniciar MySQL.

### Paso 4: Abrir la aplicación

En el navegador entra a:

```text
http://localhost/mapa_estudiantes/
```

También puedes abrir directamente la API:

```text
http://localhost/mapa_estudiantes/api/estudiantes.php
```

### Ubicación de los archivos en el entorno local

El proyecto se desarrolló originalmente en:

```text
C:\Users\LENOVO iP5\Documents\Proyecto predeterminado\mapa_estudiantes
```

Para ejecutarlo con XAMPP debe copiarse a:

```text
C:\xampp\htdocs\mapa_estudiantes
```

La URL local correspondiente es:

```text
http://localhost/mapa_estudiantes/
```

---

## 5. Registro inicial obligatorio

La aplicación registra automáticamente a la estudiante que realiza la actividad:

| Campo | Valor |
|---|---|
| Nombre | Samantha Lucia |
| Apellido | Moreno Zambrano |
| Edad | 20 |
| Código | SIS1092867 |
| Carrera | Ingeniería de Sistemas |

La edad no fue suministrada en los datos de la actividad, por lo que se estableció en **20** como valor inicial. Si deseas cambiarla, edita la constante `EDAD_INICIAL` en:

```text
config/MapaEstudiantes.php
```

El registro inicial se crea automáticamente y se comprueba por su código, de modo que **no se duplica** en cada petición HTTP.

---

## 6. API: peticiones GET y POST

URL base de la API:

```text
http://localhost/mapa_estudiantes/api/estudiantes.php
```

Todas las respuestas se devuelven en formato JSON con el encabezado:

```text
Content-Type: application/json; charset=utf-8
```

### 6.1. GET: consultar todos los estudiantes

```text
GET http://localhost/mapa_estudiantes/api/estudiantes.php
```

Respuesta esperada (`200 OK`):

```json
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
```

### 6.2. GET: consultar un estudiante por código

```text
GET http://localhost/mapa_estudiantes/api/estudiantes.php?codigo=SIS1092867
```

Respuesta esperada (`200 OK`):

```json
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
```

Si el código no existe, responde `404 Not Found`:

```json
{
    "exito": false,
    "mensaje": "No se encontró un estudiante con el código SIS9999999.",
    "datos": null
}
```

### 6.3. POST: registrar un estudiante

Se puede enviar el cuerpo en formato JSON:

```text
POST http://localhost/mapa_estudiantes/api/estudiantes.php
Content-Type: application/json
```

Cuerpo de ejemplo:

```json
{
    "nombre": "Carlos",
    "apellido": "Perez",
    "edad": 21,
    "codigo": "SIS1000001",
    "carrera": "Ingenieria de Sistemas"
}
```

Respuesta esperada (`201 Created`):

```json
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
```

También se aceptan datos enviados como formulario (`application/x-www-form-urlencoded`), por ejemplo desde Postman o `curl`.

### 6.4. Códigos HTTP utilizados

| Situación | Código HTTP |
|---|---|
| Consulta correcta de todos los estudiantes | `200 OK` |
| Consulta correcta de un estudiante | `200 OK` |
| Estudiante no encontrado | `404 Not Found` |
| Datos inválidos o incompletos | `400 Bad Request` |
| Estudiante registrado correctamente | `201 Created` |
| Código duplicado | `409 Conflict` |
| Método diferente de GET o POST | `405 Method Not Allowed` |

### 6.5. Importante: conservación de la sesión

El mapa se guarda en la **sesión de PHP**. Para que un estudiante registrado con `POST` aparezca después en un `GET`, las peticiones deben usar la misma cookie de sesión.

- En un navegador esto ocurre automáticamente.
- En Postman, las cookies se conservan por defecto.
- En `curl` debes usar un archivo de cookies con `-c` y `-b`.
- En PowerShell debes reutilizar una `WebSession`.

Si haces el `POST` sin conservar la cookie y luego haces el `GET` sin ella, PHP crea otra sesión y solo verás el registro inicial.

---

## 7. Ejemplos de uso

### 7.1. PowerShell (Windows)

Crear una sesión y consultar todos los estudiantes:

```powershell
$sesion = New-Object Microsoft.PowerShell.Commands.WebRequestSession

Invoke-RestMethod `
  -Uri "http://localhost/mapa_estudiantes/api/estudiantes.php" `
  -Method Get `
  -WebSession $sesion
```

Consultar un estudiante por código:

```powershell
Invoke-RestMethod `
  -Uri "http://localhost/mapa_estudiantes/api/estudiantes.php?codigo=SIS1092867" `
  -Method Get `
  -WebSession $sesion
```

Registrar un estudiante con JSON y conservar la sesión:

```powershell
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
```

Comprobar que el nuevo estudiante se conservó:

```powershell
Invoke-RestMethod `
  -Uri "http://localhost/mapa_estudiantes/api/estudiantes.php" `
  -Method Get `
  -WebSession $sesion
```

> Si utilizas acentos en Windows PowerShell y tienes problemas de codificación, envía el cuerpo como UTF-8 o usa `curl.exe` o Postman.

### 7.2. curl (Linux, macOS o Git Bash)

> **Nota para Windows PowerShell:** al usar `curl.exe` con JSON y comillas simples, PowerShell puede quitar las comillas dobles internas y el JSON llega inválido. En Windows es más seguro usar `Invoke-RestMethod` (sección 7.1), Postman, o guardar el JSON en un archivo y enviarlo con `--data-binary "@archivo.json"`.

Consultar todos los estudiantes:

```bash
curl "http://localhost/mapa_estudiantes/api/estudiantes.php"
```

Consultar uno por código:

```bash
curl "http://localhost/mapa_estudiantes/api/estudiantes.php?codigo=SIS1092867"
```

Registrar un estudiante y conservar la cookie de sesión:

```bash
curl -c cookies.txt -b cookies.txt \
  -X POST "http://localhost/mapa_estudiantes/api/estudiantes.php" \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Carlos","apellido":"Perez","edad":21,"codigo":"SIS1000001","carrera":"Ingenieria de Sistemas"}'
```

Comprobar el registro con GET usando la misma cookie:

```bash
curl -c cookies.txt -b cookies.txt \
  "http://localhost/mapa_estudiantes/api/estudiantes.php?codigo=SIS1000001"
```

### 7.3. Enviar datos como formulario

```bash
curl -c cookies.txt -b cookies.txt \
  -X POST "http://localhost/mapa_estudiantes/api/estudiantes.php" \
  -d "nombre=Carlos" \
  -d "apellido=Perez" \
  -d "edad=21" \
  -d "codigo=SIS1000001" \
  -d "carrera=Ingenieria de Sistemas"
```

### 7.4. Probar un código duplicado

Envía dos veces el mismo `POST` con el código `SIS1000001`. La segunda vez debe responder:

```json
{
    "exito": false,
    "mensaje": "Ya existe un estudiante con el código SIS1000001.",
    "errores": [
        "El código debe ser único."
    ]
}
```

Código HTTP: `409 Conflict`.

> **Nota:** en Windows PowerShell, `Invoke-RestMethod` genera un error cuando la respuesta es `4xx` o `5xx`. Para ver el JSON completo del error, usa `curl.exe -i`, Postman o el navegador.

### 7.5. Probar datos inválidos

Ejemplo con datos incompletos:

```bash
curl -X POST "http://localhost/mapa_estudiantes/api/estudiantes.php" \
  -H "Content-Type: application/json" \
  -d '{"nombre":"","apellido":"","edad":0,"codigo":"","carrera":""}'
```

Respuesta esperada (`400 Bad Request`):

```json
{
    "exito": false,
    "mensaje": "Los datos enviados no son válidos.",
    "errores": [
        "El nombre es obligatorio.",
        "El apellido es obligatorio.",
        "El código es obligatorio.",
        "La carrera es obligatoria.",
        "La edad debe estar entre 1 y 120 años."
    ]
}
```

---

## 8. Uso del formulario web

1. Abre en el navegador:

   ```text
   http://localhost/mapa_estudiantes/
   ```

2. Completa los campos:
   - Nombre.
   - Apellido.
   - Edad.
   - Código.
   - Carrera.

3. Pulsa **Registrar estudiante**.

4. El formulario envía los datos mediante `POST` y muestra:
   - Un mensaje verde si el registro fue exitoso.
   - Un mensaje rojo con la lista de errores si los datos son inválidos o el código está duplicado.

5. En la sección **Consultar estudiantes (GET)** puedes:
   - Pulsar el enlace **Ver todos los estudiantes en formato JSON**.
   - Seleccionar un estudiante de la lista y pulsar **Consultar** para abrir la respuesta JSON de ese estudiante.

---

## 9. Pruebas sugeridas

| Prueba | Acción | Resultado esperado |
|---|---|---|
| GET todos | Abrir `api/estudiantes.php` | JSON con la estudiante inicial `SIS1092867` |
| GET por código | Abrir `api/estudiantes.php?codigo=SIS1092867` | JSON con los datos de Samantha Lucia |
| GET inexistente | Consultar un código que no exista | `404` y mensaje de no encontrado |
| POST correcto | Registrar un estudiante nuevo | `201` y mensaje de éxito |
| POST duplicado | Repetir el mismo código | `409` y mensaje de código duplicado |
| Persistencia | Hacer GET después del POST con la misma sesión | El nuevo estudiante aparece en la lista |
| Datos incompletos | Enviar campos vacíos | `400` con errores de validación |
| Edad incorrecta | Enviar edad `0`, `200` o texto | `400` con error de edad |
| Código incorrecto | Enviar código con espacios o símbolos no permitidos | `400` con error de código |
| Método no permitido | Enviar `PUT` o `DELETE` | `405 Method Not Allowed` |
| Formulario | Registrar desde `index.php` | Mensaje de éxito y registro visible mediante GET |

---

## 10. Evidencias para el aula virtual

Las capturas solicitadas son:

1. **Captura de GET:** abre `http://localhost/mapa_estudiantes/api/estudiantes.php` o consulta un código y toma una captura donde se vea la respuesta JSON.
2. **Captura de POST:** realiza un `POST` desde Postman, PowerShell o `curl` y toma una captura donde se vean los datos enviados y la respuesta `201` con el mensaje de éxito.

**No guardes estas capturas dentro del repositorio.** Guárdalas en una carpeta aparte y súbelas directamente al aula virtual.

El archivo `.gitignore` incluye una regla para evitar subir capturas y archivos de cookies por accidente.

---

## 11. Subir el proyecto a GitHub

1. Crea un repositorio nuevo en GitHub llamado, por ejemplo, `mapa_estudiantes`. No marques la opción de agregar README si ya tienes uno local.

2. Abre una terminal en la carpeta del proyecto:

   ```powershell
   cd "C:\Users\LENOVO iP5\Documents\Proyecto predeterminado\mapa_estudiantes"
   ```

3. Inicializa Git y realiza el primer commit:

   ```bash
   git init
   git add .
   git commit -m "Proyecto mapa de estudiantes en PHP"
   git branch -M main
   ```

4. Conecta el repositorio remoto (cambia `TU-USUARIO` por tu usuario de GitHub):

   ```bash
   git remote add origin https://github.com/TU-USUARIO/mapa_estudiantes.git
   ```

5. Sube el proyecto:

   ```bash
   git push -u origin main
   ```

6. Verifica en GitHub que aparezcan todos los archivos y el `README.md`.

---

## 12. Solución de problemas

- **Error 404 al abrir la aplicación:** comprueba que la carpeta esté en `C:\xampp\htdocs\mapa_estudiantes` y que Apache esté iniciado.
- **No aparecen los estudiantes nuevos en un GET:** asegúrate de conservar la cookie de sesión entre las peticiones.
- **El formulario muestra errores de validación:** revisa que todos los campos estén completos, que la edad esté entre 1 y 120 y que el código tenga entre 3 y 20 caracteres (letras, números o guiones).
- **El puerto 80 está ocupado:** cambia el puerto de Apache en XAMPP y usa, por ejemplo, `http://localhost:8080/mapa_estudiantes/`.
- **La página se ve sin estilos:** los estilos están incluidos directamente en `index.php`, por lo que no se necesita ninguna carpeta CSS adicional.
- **Se quiere reiniciar el mapa:** elimina las cookies del sitio o reinicia la sesión del navegador. Al volver a abrir la aplicación se creará de nuevo el mapa con la estudiante inicial.

---

## 13. Cumplimiento de los requisitos

- [x] Clase `Persona` con `nombre`, `apellido` y `edad` privados.
- [x] Clase `Estudiante` con `extends Persona`, `codigo` y `carrera` privados.
- [x] `declare(strict_types=1);` en los archivos PHP.
- [x] Propiedades, parámetros y retornos tipados.
- [x] Mapa implementado con arreglo asociativo `código => Estudiante`.
- [x] Código como clave única y rechazo de duplicados.
- [x] Persistencia del mapa mediante sesión de PHP.
- [x] API `GET` para listar y consultar por código.
- [x] API `POST` para registrar con validaciones.
- [x] Respuestas JSON y códigos HTTP apropiados.
- [x] Formulario HTML con los cinco campos solicitados.
- [x] Registro inicial automático de Samantha Lucia Moreno Zambrano.
- [x] Sin frameworks, sin MySQL y sin funcionalidades ajenas a la actividad.
- [x] Documentación completa en este `README.md`.
