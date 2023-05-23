#!/usr/bin/env bash

set -eu

os-depend-sed-in-place() {
    if [ "$(uname -s)" == 'Darwin' ]; then
        sed -i '' "$@";
    else
        sed --in-place "$@";
    fi
}

setupEnvs() {
    [ ! -f ./.env ] && cp ./docker/.env.dist ./.env

    COMPOSE_FILE_ENV="COMPOSE_FILE='docker/docker-compose.local.yml'";

    if [ "$(grep 'COMPOSE_FILE' ./.env)" != '' ]; then
        os-depend-sed-in-place -E "s|^.*COMPOSE_FILE=.*$|$COMPOSE_FILE_ENV|" ./.env;
    else
        echo "$COMPOSE_FILE_ENV" >> ./.env;
    fi

    [ ! -f ./docker/backend/.env ] && cp ./docker/backend/.env.dist ./docker/backend/.env
    [ ! -f ./docker/mysql/.env ] && cp ./docker/mysql/.env.dist ./docker/mysql/.env

    echo 'Envs set up!';
}

install() {
    docker-compose build

    runBackend composer install --no-scripts --prefer-dist

    docker-compose up --detach --force-recreate --remove-orphans backend nginx mysql mailhog

    runBackend ./bin/doctrine migrations:migrate --no-interaction

    docker-compose up --detach --force-recreate queue

    echo "Done!"
}

up() {
    docker-compose up -d --force-recreate --remove-orphans
}

down() {
    docker-compose down --remove-orphans
}

update() {
    docker-compose pull
    docker-compose build --pull
    runBackend composer update
}

build() {
    docker-compose build
    runBackend composer install --no-scripts --prefer-dist
    docker-compose up -d --force-recreate --remove-orphans
}

runBackend() {
    docker-compose run --rm backend-cli "$@"
}

logs() {
    docker-compose logs "$@"
}

COMMAND=$1
case $COMMAND in
    install | i)
        setupEnvs
        install
        ;;
    up | u)
        setupEnvs;
        up;
        ;;
    down | d)
        setupEnvs;
        down;
        ;;
    update | upd)
        setupEnvs;
        update;
        ;;
    build | b)
        setupEnvs;
        build;
        ;;
    run-backend | rb)
        setupEnvs;

        ARGS_WITHOUT_FIRST="${@:2}"
        runBackend $ARGS_WITHOUT_FIRST;
        ;;
    tinker)
        setupEnvs;

        runBackend php artisan tinker;
        ;;
    stan)
        setupEnvs;

        runBackend vendor/bin/phpstan analyse -c phpstan.neon --ansi --memory-limit=256M;
        ;;
    fixer-check | fc)
        setupEnvs;

        runBackend vendor/bin/php-cs-fixer --config=.php-cs-fixer.php fix --dry-run --diff --ansi -v;
        ;;
    fixer-fix | ff)
        setupEnvs;

        runBackend vendor/bin/php-cs-fixer --config=.php-cs-fixer.php fix --ansi -v;
        ;;
    sniffer-check | sc)
        setupEnvs;

        runBackend ./vendor/bin/phpcs -p;
        ;;
    sniffer-fix | sf)
        setupEnvs;

        runBackend ./vendor/bin/phpcbf -p;
        ;;
    test-unit | tu)
        setupEnvs;

        runBackend ./vendor/bin/phpunit --colors=always --testsuite Unit;
        ;;
    check | c)
        setupEnvs;

        # todo: параллельный запуск
        runBackend vendor/bin/phpstan analyse -c phpstan.neon --ansi;
        runBackend vendor/bin/php-cs-fixer --config=.php-cs-fixer.php fix --dry-run --diff --ansi -v;
        runBackend ./vendor/bin/phpcs -p;
        runBackend ./vendor/bin/phpunit --colors=always --testsuite Unit;
        ;;
    fix | f)
        setupEnvs;

        runBackend vendor/bin/php-cs-fixer --config=.php-cs-fixer.php fix --ansi -v;
        runBackend ./vendor/bin/phpcbf -p;
        ;;
    logs | l)
        setupEnvs;

        ARGS_WITHOUT_FIRST="${@:2}"
        logs $ARGS_WITHOUT_FIRST;
        ;;
    hooks-install | hi)
        printf '#!/usr/bin/env sh\n\n./manage.bash check;\n' > .git/hooks/pre-commit;

        chmod +x .git/hooks/pre-commit;
        ;;
    setup-envs | se)
        setupEnvs;
        ;;
    *)
        echo 'Unknown command. Available:
            install[i],
            up[u],
            down[d],
            update[upd],
            build[b],
            run-backend[rb],
            stan,
            fixer-check[fc],
            fixer-fix[ff],
            sniffer-check[sc],
            sniffer-fix[sf],
            test-unit[tu],
            logs[l],
            check[c],
            fix[f],
            hooks-install[hi],
            setup-envs[se].'
        ;;
esac
