#!/usr/bin/env bash

echo -e "Hello World\r";

docker run --name some-mysql \
-e MYSQL_ROOT_PASSWORD=my-secret-pw \
-d \
mysql:8.0.31-debian

docker run -it \
--network some-network \
--rm \
mysql:8.0.31-debian mysql -hsome-mysql -uexample-user -p

docker run \
--name some-mysql \
-e MYSQL_ROOT_PASSWORD=my-secret-pw \
-d \
mysql:8.0.31-debian --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci

docker run --rm \
--env MYSQL_ROOT_PASSWORD=secret \
--env MYSQL_ROOT_HOST=% \
--env MYSQL_DATABASE=default \
--env MYSQL_USER=default \
--env MYSQL_PASSWORD=secret \
--env MYSQL_ALLOW_EMPTY_PASSWORD=1 \
--publish 3306:3306 \
--name=mysql mysql:8.0.31-debian --character-set-server=utf8mb4  --collation-server=utf8mb4_0900_ai_ci --explicit_defaults_for_timestamp=false --default_time_zone=-04:00

docker run --rm \
--env POSTGRES_DB==default \
--env POSTGRES_USER=default \
--env POSTGRES_PASSWORD=secret \
--publish 5432:5432 \
--name=pgsql postgres:15.1-alpine
