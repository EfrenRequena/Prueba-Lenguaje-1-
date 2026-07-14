# Prueba-Lenguaje-1-
## Informe de Auditoría de Seguridad

[cite_start]**Responsable:** Estudiante 3 (Oficial de Seguridad) [cite: 22, 25]
[cite_start]**Enfoque de Evaluación:** Árbitro de la integridad y control de acceso bajo el principio de "Zero Trust"[cite: 23].

[cite_start]De conformidad con los requerimientos técnicos exigidos por la cátedra, certifico la siguiente auditoría sobre el repositorio[cite: 25]:

1. [cite_start]**Uso de Criptografía e Integridad de Persistencia:** * Se ha verificado que el modulo de persistencia (Estudiante 2) utiliza de forma correcta algoritmos de hashing criptográfico de nivel industrial mediante `PASSWORD_BCRYPT` para el almacenamiento de contraseñas[cite: 4, 25].
   * [cite_start]Se descartan por completo funciones procedurales de legado (`mysqli_query`), garantizando la correcta implementación de placeholders asimétricos con PDO[cite: 17, 20, 41].

2. [cite_start]**Evaluación de Fugas de Información en Capa de Datos:** * Se realizó la inspección de código de los bloques `catch` diseñados para el manejo de excepciones de infraestructura[cite: 18, 25].
   * [cite_start]Se confirma que son 100% herméticos: las trazas internas del motor `ENGINE=InnoDB` y detalles del servidor se aíslan directamente hacia `error_log`, impidiendo cualquier filtración de datos sensibles hacia el cliente final[cite: 3, 25].

3. [cite_start]**Gobernanza de Acceso mediante Defensa Profunda:** * Se implementó con éxito la re-validación en el Back-end (Capa 2) para mitigar bypasses en la interfaz de usuario, controlando el ciclo de vida de las variables de sesión a través del vector global `$_SESSION`[cite: 24, 26].
