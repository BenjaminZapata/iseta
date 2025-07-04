#!/bin/bash

echo "Esperando a que la base de datos esté lista..."

until mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e 'SELECT 1;' &> /dev/null
do
  echo "DB aún no responde... esperando 2s"
  sleep 2
done

echo "✅ Base de datos lista, corriendo migraciones..."
php artisan migrate --force || true

echo "🚀 Iniciando Laravel en puerto 8000"
php artisan serve --host=0.0.0.0 --port=8000
