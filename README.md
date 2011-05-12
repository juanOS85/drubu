DRUBU: Dristribución de rutas de buses
======================================

Tecnologías
-----------

* Sistema opertavito Linux
* [Apache HTTP Server 2.2][1]
* [PostgreSQL 8][2]
* [PHP 5][3]
 * [symfony 1.4][4]
 * [doctrine 1.2][5]

Requerimientos
--------------

Debido al framework de desarrollo symfony 1.4.x, DRUBU funciona con PHP 5.2.4 o
superior

Instalación
-----------

Clonar el respositorio de DRUBU en el directorio Web:

    $ git clone git://github.com/juanchopx2/drubu.git

Crear el archivo `config/databases.yml` con los parametros correspondientes:

    all:
    doctrine:
        class: sfDoctrineDatabase
        param:
            dsn:      DRIVER:host=HOST;dbname=BD
            username: USUARIO
            password: CONTRASENA

NOTA: El usuario del RDBMS ser dueño de la base de datos y debe tener permisos 
para crear bases de datos.

Crear las carpetas `cache` y `log` en la raíz del proyecto y asignarles permisos
de lectura/escritura:

    $ mkdir cache log
    $ php symfony project:permissions

Luego de ser creadas las carpetas, ejecutar la tarea de symfony en la raíz del 
proyecto:

    $ php symfony doctrine:build --all --and-load

[1]: http://httpd.apache.org/
[2]: http://www.postgresql.org/
[3]: http://www.php.net
[4]: http://www.symfony-project.org/
[5]: http://www.doctrine-project.org/projects/orm
