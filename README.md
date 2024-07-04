DUTYFLOW ::

Dutyflow es una app para el manejo del hogar, se pueden crear tareas, editarlas, borrarlasy marcarlas como completadas. La idea de la app es que las tareas se repitan cada ciertos intervalos. como tambien compartir tareas en un hogar compartido por usuarios.

Para correrla se debe instalar el backend usando:: 
composer install 
php artisan migrate 
php artisan db:seed 
capaz: php artisan key:generate

En el frontend::

npm install 
npm run dev