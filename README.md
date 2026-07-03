# SaibaPlus — Guia de Instalação e Configuração

Plataforma de cursos online com front-end estático e API REST em Laravel + MySQL.

---

## Requisitos

- Windows 10 ou superior
- Laragon (inclui Apache, MySQL e PHP)
- Navegador moderno (Chrome, Firefox, Edge)

---

## 1. Instalar o Laragon

1. Acesse **https://laragon.org/download** e baixe a versão **Full**
2. Execute o instalador e siga os passos (próximo → próximo → instalar)
3. Ao abrir o Laragon pela primeira vez, clique em **Start All**
4. Os dois indicadores devem ficar **verdes**: Apache e MySQL

> O Laragon instala junto o PHP, o Composer e o MySQL — não é necessário instalar nada separadamente.

---

## 2. Obter os arquivos do projeto

Copie a pasta do projeto para dentro de `C:\laragon\www\`.

A estrutura final deve ser:

```
C:\laragon\www\
├── routes\          ← front-end (HTML, CSS, JS)
│   ├── index.html
│   ├── cursos.html
│   ├── detalhe.html
│   ├── cadastro.html
│   ├── login.html
│   ├── matricula.html
│   ├── css\
│   │   └── style.css
│   └── js\
│       ├── app.js
│       └── components.js
└── SaibaPlus\       ← back-end Laravel
    ├── app\
    ├── routes\
    ├── .env
    └── ...
```

---

## 3. Configurar o banco de dados

### 3.1 Abrir o HeidiSQL

No painel do Laragon clique em **Database** → abre o HeidiSQL automaticamente conectado ao MySQL local.

### 3.2 Criar o banco

No painel esquerdo, clique com o botão direito em **→ Criar novo → Banco de dados**

- Nome: `saibaplusapi`
- Collation: `utf8mb4_unicode_ci`

### 3.3 Criar as tabelas

Selecione o banco `saibaplusapi`, abra uma aba de Query (**Ctrl+T**) e execute:

```sql
CREATE TABLE categorias (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nome       VARCHAR(100) NOT NULL,
  icone      VARCHAR(10)  NOT NULL,
  cor        VARCHAR(20)  NOT NULL,
  descricao  TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cursos (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  categoria_id  INT NOT NULL,
  titulo        VARCHAR(200) NOT NULL,
  descricao     TEXT,
  nivel         ENUM('iniciante','intermediario','avancado') NOT NULL,
  carga_horaria VARCHAR(10) NOT NULL,
  instrutor     VARCHAR(150),
  preco         DECIMAL(8,2) NOT NULL DEFAULT 0,
  matriculas    INT NOT NULL DEFAULT 0,
  imagem_url    TEXT,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);

CREATE TABLE usuarios (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  nome           VARCHAR(200) NOT NULL,
  cpf            VARCHAR(14)  UNIQUE NOT NULL,
  email          VARCHAR(200) UNIQUE NOT NULL,
  login          VARCHAR(80)  UNIQUE NOT NULL,
  senha          VARCHAR(255) NOT NULL,
  telefone       VARCHAR(20),
  nascimento     DATE,
  escolaridade   VARCHAR(50),
  area_interesse VARCHAR(50),
  endereco       VARCHAR(300),
  bairro         VARCHAR(100),
  cidade         VARCHAR(100),
  estado         CHAR(2),
  cep            VARCHAR(10),
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tokens (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  token      VARCHAR(64) UNIQUE NOT NULL,
  expires_at TIMESTAMP NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
```

### 3.4 Popular as categorias

Na mesma aba de Query execute:

```sql
INSERT INTO categorias (nome, icone, cor, descricao) VALUES
('Programação',       '💻', '#6366F1', 'Desenvolvimento web, mobile, back-end e muito mais.'),
('Design',            '🎨', '#EC4899', 'UI/UX, branding, motion graphics e design gráfico.'),
('Administração',     '📊', '#10B981', 'Gestão, finanças, empreendedorismo e liderança.'),
('Idiomas',           '🌐', '#F59E0B', 'Inglês, espanhol, francês, japonês e outros idiomas.'),
('Marketing Digital', '📣', '#EF4444', 'SEO, mídias sociais, tráfego pago e copywriting.'),
('Dados & IA',        '🤖', '#3B82F6', 'Data science, machine learning, SQL e visualização.');
```

### 3.5 Popular os cursos

Execute o INSERT completo dos cursos disponível no arquivo `cursos_seed.sql` incluído no projeto.

---

## 4. Configurar o Laravel

Abra o **terminal do Laragon** (menu Laragon → Terminal) e execute em sequência:

```bash
cd C:\laragon\www\SaibaPlus

composer install

cp .env.example .env

php artisan key:generate
```

### 4.1 Configurar o .env

Abra o arquivo `C:\laragon\www\SaibaPlus\.env` em qualquer editor de texto e ajuste as linhas:

```env
APP_URL=http://localhost:8080/SaibaPlus/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saibaplusapi
DB_USERNAME=root
DB_PASSWORD=
```

> A senha do MySQL no Laragon é vazia por padrão.
só avisando, caso o mysql já estiver sido instalado com uma senha no mesmo porte, essa senha deverá ser colocada no DB_PASSWORD.
(quando estava fazendo o site, eu percebi que o mysql que eu estava usando era de um projeto anterior e a senha se manteve. na minha maquina era 991316257)
---

## 5. Verificar a API

Com o Laragon rodando, abra no navegador:

```
http://localhost:8080/SaibaPlus/public/api/categorias
```

Se retornar um JSON com as 6 categorias, a API está funcionando corretamente.
> O porte que eu utilizei foi 8080; o apache também deve estar nesse porte. eu mudei porquê eu não consegui parar uma conexão que já estava ativa no porte padrão 80.
> Caso o port esteja diferente, mude o port do apache nas configurações do laragon (engrenagem no canto superior direito), vá para 'services & ports' e mude o porte do apache.

> não sei se é excentricamente essencial o port ser esse, mas você pode tentar conectar em outros para ver qual funciona. o laravel também rodava no 8080 quando apresentei.

---

## 6. Acessar o front-end

Abra no navegador:

```
http://localhost:8080/routes/index.html
```

---

## Inicialização do dia a dia

Para usar o sistema em qualquer momento após a instalação:

1. Abrir o Laragon
2. Clicar em **Start All**
3. Acessar `http://localhost:8080/routes/index.html`

Não é necessário repetir nenhuma etapa de instalação ou configuração.

## importante: Faça o serve do laravel
1. No laragon, clique em 'Terminal' para abrir o terminal imbutido do laragon.
2. Quanddo o Cmder abrir e o endereço do www estiver na tela, rode `cd SaibaPlus` para entrar no diretório do back-end.
3. Quando estiver dentro do diretório, rode `php artisan serve` para iniciar o servidor. O padrão é inicializar no porte `8000` mas você pode adicionar `--port=` no final para definir qualquer port caso precise.
Após o último comando, o front-end conseguirá construir os cards de curso e as categorias direto do banco de dados se ele já estiver populado. 
---

## Solução de problemas

| Sintoma | Causa provável | Solução |
|---|---|---|
| Página não abre | Apache não está rodando | Verificar se o Apache está ativo no Laragon |
| API retorna erro 500 | Chave do Laravel ausente | Rodar `php artisan key:generate` no terminal |
| API retorna erro de banco | .env mal configurado | Revisar DB_DATABASE, DB_USERNAME e DB_PASSWORD |
| Categorias não aparecem | Tabelas não criadas ou vazias | Repetir os passos 3.3 e 3.4 |
| "Failed to fetch" no front | API fora do ar ou URL errada | Verificar se a URL base em `js/app.js` bate com a porta do Apache |

---

## Estrutura da API

| Endpoint | Método | Descrição |
|---|---|---|
| `/api/categorias` | GET | Lista todas as categorias |
| `/api/cursos?categoria_id=X` | GET | Lista cursos de uma categoria |
| `/api/cursos/{id}` | GET | Detalhes de um curso |
| `/api/usuarios` | POST | Cadastro de novo aluno |
| `/api/auth/login` | POST | Autenticação |
| `/api/auth/logout` | POST | Encerramento de sessão |

---

## Tecnologias utilizadas

| Camada | Tecnologia |
|---|---|
| Front-end | HTML5, CSS3, JavaScript puro |
| Back-end | PHP 8 + Laravel 11 |
| Banco de dados | MySQL 8 |
| Servidor | Apache (via Laragon) |
| Estilização | CSS Grid, Flexbox, variáveis CSS |
