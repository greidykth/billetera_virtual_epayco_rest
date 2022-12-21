# Servicio Rest Billetera Virtual ePayco

Servicio rest para una aplicación que simula una billetera virtual

# Seguir los siguientes pasos para correr la aplicación:

1) Clonar proyecto
2) Correr el comando composer install
4) Crear un virtual host para la aplicación, nombre sugerido http://billetera-virtual-rest.test
5) Tomar el archivo .env.example como base para configurar archivo .env
6) Configurar variable de entorno URL_SOAP_SERVICE con el dominio de la aplicación soap
9) En una terminal correr los tests con vendor/bin/phpunit
10) Usar http://billetera-virtual-rest.test/reset si se desea resetear la base de datos
11) Verificar funcionamiento de endpoints con la collección de postman enviada
