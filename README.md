A partir de mis aventuras relatadas en este post de [Reddit](https://www.reddit.com/r/programacion/comments/16xwixq/api_correo_argentino/), y por pedido popular, aquí está mi humilde implementación de la API de Correo Argentino.

Esta es la integración de la API de PAQ.AR MiCorreo implementada en PHP a partir de la documentación publicada aquí:

[PAQ.AR MiCorreo](https://www.correoargentino.com.ar/MiCorreo/public/mi-correo)

[Documentación PDF](https://www.correoargentino.com.ar/MiCorreo/public/img/pag/apiMiCorreo.pdf)

No está implementada la API en su totalidad, solamente la funcionalidad de obtener costos de envío. El archivo **index.php** muestra un ejemplo completo.

Puede ser necesario desactivar el modo test si no obtienen resultados (creo que nunca lo vi funcionando):

`$ca->getApi()->setTestMode(false);`

De más está decir que no tengo nada que ver con Correo Argentino, sólo soy un sufrido programador tal como vos que llegaste aquí buscando una luz en la oscuridad... 😁
