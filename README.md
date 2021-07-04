# Nette sandbox
- Při prvním spuštění se je nutné připojit do containeru a spustit `composer install -o`
- Součástí Dockeru je i DB server, běžící na portu 3306, uživatel: root/maeZfu8rR3w4QfN7

## Příkazy:
**Zapnutí containeru/apliakce:** ve složce docker spustit příkaz `docker-compose up -d --build`  
**Vypnutí containeru/aplikace:** ve složce docker spustit příkaz `docker-compose down`  
**Připojení do containeru:** `docker exec -it nette-sandbox bash`  
