# Configuración de Base de Datos (`config/`)

Este directorio contiene la configuración y el conector centralizado de acceso a la base de datos MySQL mediante `PDO`.

## Archivos

### [Database.php](file:///home/ruben/Documents/IPN/quinto_sem/Ing_Softw/proyecto/care_sis/config/Database.php)

Clase encargada de gestionar la conexión a la base de datos `seguimiento_medicamentos`.

#### Propiedades
- `private $host = "localhost"`
- `private $db_name = "seguimiento_medicamentos"`
- `private $username = "root"`
- `private $password = ""`
- `public $conn` (`PDO|null`): Instancia de la conexión activa.

#### Contrato de Interfaz (`getConnection()`)
- **Firma**: `public function getConnection()`
- **Entradas**: Ninguna (utiliza las propiedades privadas de configuración).
- **Salidas**: Retorna una instancia `PDO` configurada con codificación `utf8mb4` y modo de errores `PDO::ERRMODE_EXCEPTION`, o `null` si ocurre una excepción `PDOException`.
- **Efectos secundarios**: Abre una conexión con el motor MySQL local; en caso de fallo de conexión, imprime el mensaje de error mediante `echo`.
