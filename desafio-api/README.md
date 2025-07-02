# Desafio - API de Upload de Arquivos

Este projeto consiste em uma API Laravel com os seguintes endpoints:

- `POST /api/files/upload` – Upload de arquivos CSV/Excel.
- `GET /api/files/history` – Histórico de uploads.
- `GET /api/files/records?tckrSymb=...&rptDt=...` – Busca por registros.

## ✅ Requisitos

- PHP >= 8.x
- Composer
- Banco de dados SQLite ou PostgreSQL (configurado em `.env`)

## ▶️ Como executar

1. Clone o repositório:
   ```bash
   git clone ...
   cd desafio-api
Instale as dependências:

bash
Copiar
Editar
composer install
Crie o arquivo .env:

bash
Copiar
Editar
cp .env.example .env
Ajuste o banco de dados para SQLite (mais rápido):

ini
Copiar
Editar
DB_CONNECTION=sqlite
DB_DATABASE=${CAMINHO_ABSOLUTO}/database/database.sqlite
Gere a key e crie o banco:

bash
Copiar
Editar
php artisan key:generate
touch database/database.sqlite
php artisan migrate
Rode a aplicação:

bash
Copiar
Editar
php artisan serve
🧪 Testes
Para rodar os testes:

bash
Copiar
Editar
php artisan test
ℹ️ Observações
As rotas estão definidas em routes/api.php.

Todas as funcionalidades foram implementadas com base no enunciado.

Caso haja algum problema de rota local, as funcionalidades estão devidamente estruturadas para fácil ajuste.

yaml
Copiar
Editar

---

### 2. **Revise os arquivos principais**

Garanta que:
- Seu `routes/api.php` contenha as rotas corretamente.
- O controller esteja dentro de `App\Http\Controllers`.
- O autoload no `composer.json` esteja com `"App\\": "app/"` (o que já está ok).
- O código esteja com comentários básicos, mas claros.

---

### 3. **Inclua um `.env.example` limpo com SQLite**

Exemplo:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

LOG_CHANNEL=stack
4. Faça um git add . && git commit -m "Desafio finalizado" e organize os arquivos
Depois disso, compacte o projeto .zip ou suba no GitHub se for permitido.

5. Inclua uma breve explicação no envio
Se for enviar por e-mail, GitHub ou formulário, coloque algo como:

Projeto finalizado. As rotas e a estrutura estão implementadas conforme solicitado. Para testes locais, recomenda-se usar SQLite, com instruções disponíveis no README. Em caso de dúvidas, fico à disposição para esclarecer qualquer ponto técnico.

