EVS Admin
===

Para instanciar o projeto:

1. `php bin/console doctrine:database:create`
2. `php bin/console doctrine:schema:create --force`
3. `php bin/console doctrine:fixtures:load --no-interaction`

Para que o frontend e o backend se conversem:

1. Configurar o arquivo `www/js/inicializa.js` com o host do backend;
2. Configurar o arquivo `parameters.yml` com os domínios do frontend.

Para comandos DDL
	
	Verificar comando de execução
1. `php bin/console doctrine:schema:update --dump-sql`

	Executar comando em banco
2. `php bin/console doctrine:schema:update --force` 
