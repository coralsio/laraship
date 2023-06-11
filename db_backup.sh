[ ! -f .env ] || export $(grep -v '^#' .env | xargs)

if [ ! -z "$1" ]; then
  dumpName="$1"
else
  dumpName="${DB_DATABASE}_$(date +%d%b).sql"
fi

dumpCommand="mysqldump -h ${DB_HOST} -u ${DB_USERNAME} -p${DB_PASSWORD} --skip-lock-tables $DB_DATABASE"

echo "start db backup to: ${dumpName}"

$($dumpCommand >"${dumpName}")

echo "gzip the dump: ${dumpName}"

gzip "${dumpName}"

echo "gzip is ready ${dumpName}.gz"

echo 'done'
