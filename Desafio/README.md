API em Laravel
Laravel é uma escolha popular para a construção de APIs devido à sua arquitetura elegante, robustez e recursos integrados que simplificam o desenvolvimento. A seguir, apresentamos os principais pontos que destacam a utilização do Laravel para o desenvolvimento de APIs, e que podem ser utilizados na documentação do seu projeto:

Por que usar Laravel para APIs?
Estrutura modular: Laravel utiliza o padrão MVC (Model-View-Controller), permitindo organizar o código de forma modular e escalável.
Roteamento poderoso: Oferece um sistema de rotas fácil de configurar para definir os endpoints da API.
Middleware: Controle o acesso e manipule requisições HTTP com middlewares personalizados ou padrões, como autenticação e verificação de CORS.
Eloquent ORM: Facilita a manipulação de dados no banco de forma intuitiva, reduzindo a necessidade de SQL manual.
Autenticação JWT ou Sanctum: Fornece segurança robusta para APIs RESTful com integração fácil de tokens JWT ou Laravel Sanctum.
Validação de dados: Permite validação flexível de entradas de usuários por meio de métodos simples.
Suporte para JSON: Laravel retorna respostas JSON nativamente, tornando-o ideal para APIs RESTful.
Testes integrados: Inclui ferramentas para testes automatizados de endpoints, garantindo a qualidade do código.
Recursos do Laravel Utilizados na API
Upload de Arquivos com Validação:
O método upload processa uploads de arquivos com validação para garantir segurança e conformidade com os tipos permitidos (CSV ou XLSX).

Verificação de Unicidade com Hash:
Antes de armazenar o arquivo, a API gera um hash SHA-256 para verificar duplicidade, evitando uploads redundantes.

Persistência no Banco:
Dados do arquivo enviado são extraídos e salvos no banco usando Eloquent ORM, vinculando cada registro ao upload correspondente.

Consulta e Histórico de Uploads:
Endpoints específicos permitem consultar e filtrar o histórico de uploads, retornando dados em formato JSON paginado.

Busca Avançada:
Permite a pesquisa de conteúdos de arquivos processados por atributos específicos, como símbolos ou datas, com respostas rápidas e eficientes.

Tecnologias Integradas
Banco de Dados: Suporte para MySQL, PostgreSQL, SQLite, entre outros.
Armazenamento de Arquivos: Configuração para armazenar arquivos localmente ou em serviços na nuvem, como AWS S3 ou Google Cloud.
Excel/CSV Parsing: Bibliotecas como maatwebsite/excel para manipular arquivos XLSX e CSV com facilidade.
Exemplo de Resposta de Endpoint
Endpoint: /api/upload
Método: POST
Exemplo de Resposta:
json
Copiar código
{
    "message": "Arquivo carregado e processado com sucesso.",
    "data": {
        "file_name": "exemplo.csv",
        "upload_date": "2024-12-07",
        "hash": "b2a5c8f7d90a14bd2d6c3...",
        "records_processed": 100
    }
}
Essas informações ilustram as principais funcionalidades da API e podem ser adicionadas à sua documentação para que usuários e desenvolvedores entendam melhor o projeto e como interagir com ele. Se precisar de algo mais detalhado, me avise!

O que foi Adicionado
Cálculo do Hash

O hash é calculado utilizando hash('sha256', file_get_contents($file)), garantindo que o conteúdo do arquivo seja usado para gerar um identificador único.
Verificação de Duplicidade

Antes de processar o upload, verifica se o hash do arquivo já existe no banco de dados:  


$existingUpload = Upload::where('hash', $fileHash)->first();
Caso o hash já exista, uma resposta JSON é retornada com os detalhes do upload existente e o código HTTP 409.


Se o arquivo for único, ele é salvo no banco e processado.
Resposta de Exemplo
Caso o arquivo seja duplicado:
json
Copiar código
{
    "message": "Arquivo já enviado anteriormente.",
    "upload_details": {
        "id": 5,
        "file_name": "meu-arquivo.csv",
        "upload_date": "2024-12-07T13:00:00.000Z",
        "hash": "d2d2d0a..."
    }
}
Caso o upload seja bem-sucedido:
json
Copiar código
{
    "message": "Arquivo carregado e processado com sucesso.",
    "upload_id": 6
}
Se precisar de mais alguma modificação, é só me chamar! 🚀



Documentação da API de Upload de Arquivos: 

Esta API permite o upload de arquivos CSV e Excel, realiza a verificação de duplicidade usando hash, armazena os arquivos e processa os dados. Também inclui funcionalidades para consultar histórico de uploads e pesquisar conteúdo nos arquivos.

Endpoints
1. Upload de Arquivo
URL: /api/files/upload
Método: POST
Descrição: Faz o upload de arquivos (CSV ou Excel), calcula o hash para verificar duplicidade e processa os dados.
Headers:
Content-Type: multipart/form-data
Parâmetros:
file: O arquivo a ser enviado. Deve ser do tipo .csv ou .xlsx com tamanho máximo de 2MB.
Respostas:
201 Created:
json
Copiar código
{
    "message": "Arquivo carregado e processado com sucesso.",
    "upload_id": 6
}
409 Conflict (Arquivo duplicado):
json
Copiar código
{
    "message": "Arquivo já enviado anteriormente.",
    "upload_details": {
        "id": 5,
        "file_name": "meu-arquivo.csv",
        "upload_date": "2024-12-07T13:00:00.000Z",
        "hash": "d2d2d0a..."
    }
}
422 Unprocessable Entity (Validação falhou):
json
Copiar código
{
    "error": "Validação falhou.",
    "details": {
        "file": [
            "O campo file é obrigatório."
        ]
    }
}
500 Internal Server Error:
json
Copiar código
{
    "error": "Ocorreu um erro ao processar o upload.",
    "details": "Mensagem de erro detalhada."
}
2. Histórico de Uploads
URL: /api/files/history
Método: GET
Descrição: Retorna a lista paginada de uploads armazenados.
Parâmetros (Query):
file_name (opcional): Filtra pelo nome do arquivo.
upload_date (opcional): Filtra pela data do upload no formato YYYY-MM-DD.
page: Número da página para paginação.
Respostas:
200 OK:
json
Copiar código
{
    "current_page": 1,
    "data": [
        {
            "id": 5,
            "file_name": "meu-arquivo.csv",
            "upload_date": "2024-12-07",
            "hash": "d2d2d0a...",
            "file_path": "uploads/meu-arquivo.csv"
        }
    ],
    "last_page": 1,
    "per_page": 10,
    "total": 1
}
3. Pesquisa de Conteúdo
URL: /api/files/search
Método: GET
Descrição: Pesquisa o conteúdo de arquivos processados com base nos dados carregados.
Parâmetros (Query):
TckrSymb (opcional): Filtro pelo símbolo do ticker.
RptDt (opcional): Filtro pela data do relatório no formato YYYY-MM-DD.
page: Número da página para paginação.
Respostas:
200 OK:
json
Copiar código
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "upload_id": 6,
            "RptDt": "2024-12-07",
            "TckrSymb": "ABCD3",
            "MktNm": "Bolsa XYZ",
            "SctyCtgyNm": "Ações",
            "ISIN": "BRABCD3F4567",
            "CrpnNm": "Empresa XYZ"
        }
    ],
    "last_page": 1,
    "per_page": 10,
    "total": 1
}
Estrutura de Banco de Dados
Tabela: uploads
Coluna	Tipo	Descrição
id	INTEGER	Identificador único.
file_name	VARCHAR	Nome do arquivo enviado.
file_path	VARCHAR	Caminho do arquivo armazenado.
upload_date	DATETIME	Data de upload.
hash	VARCHAR	Hash SHA-256 do arquivo.
Tabela: file_data
Coluna	Tipo	Descrição
id	INTEGER	Identificador único.
upload_id	INTEGER	ID do upload (chave estrangeira).
RptDt	DATE	Data do relatório.
TckrSymb	VARCHAR	Símbolo do ticker.
MktNm	VARCHAR	Nome do mercado.
SctyCtgyNm	VARCHAR	Categoria do ativo.
ISIN	VARCHAR	Código ISIN do ativo.
CrpnNm	VARCHAR	Nome da empresa.
# MyllerGomesTavares
