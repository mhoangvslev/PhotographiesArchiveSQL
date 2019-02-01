echo "Dumping schema to schema.sql"
sudo pg_dump --username=postgres --password  --schema-only --file="schemas.sql" Photographies

echo "Dumping data to donnees.sql"
sudo pg_dump --username=postgres --password -c --inserts --file="donnees.sql" --format=plain Photographies
