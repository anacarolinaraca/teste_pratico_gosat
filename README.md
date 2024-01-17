# Gosat
## Tecnologias Utilizadas

- PHP/Laravel

## Como usar 
1. Clone o repositório: 
```
git clone git@github.com:anacarolinaraca/teste_pratico_gosat.git
```

2. Acesse o diretório do projeto:
```
cd /teste_pratico_gosat
```

3. Execute o docker com o banco mysql:
```
docker-compose up -d
```
4. Instale/configure as dependências do projeto:
```
composer install
```
```
php artisan key:generate
```
```
php artisan migrate
```

5. Rode a aplicação utilizando:
```
php artisan serve
```

6. Importe o arquivo **Gosat.postman_collection.json**, que está na raiz do projeto, no Postman para obter a documentação das rotas.
