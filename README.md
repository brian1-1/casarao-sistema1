# O Casarão — Sistema de Gestão de Restaurante (Laravel 11)

Sistema completo de gestão para restaurante, migrado de uma versão PHP/React (armazenamento em JSON)
para **Laravel 11.x + PHP 8.2 + MySQL/MariaDB**. Implementa a **Fase 1** com painéis para
**Cliente, Garçom, Cozinha e Gerente**, autenticação multi-perfil e banco de dados relacional.

> Todas as interfaces e mensagens estão em **Português (Brasil)**.

---

## ✨ Funcionalidades

### Autenticação multi-perfil
- Login seguro com 4 perfis: **Cliente, Garçom, Cozinha, Gerente**.
- Middleware `role` que protege cada rota pelo perfil do usuário.
- Redirecionamento automático para o painel correto após o login.

### Mesa do Cliente
- Cardápio com produtos agrupados por categoria.
- Controle de quantidade com botões **(+)** e **(−)** — o item é removido ao chegar a zero.
- Comanda aberta exibindo **Produto, Quantidade, Preço Unitário, Subtotal e Total**.
- Identificação da mesa **apenas pelo número** (sem "quantidade de pessoas").
- Formas de pagamento: **Pix, Dinheiro, Cartão**.
- **Placeholder padrão** para produtos sem imagem.

### Painel do Garçom
- Lista de todas as mesas com status: 🟢 **Livre**, 🟡 **Ocupada**, 🔴 **Fechada**.
- Mostra número da mesa, status, valor parcial, nº de pedidos e horário de abertura.
- Lista de pedidos **prontos na cozinha** aguardando entrega.
- Atualização automática da tela.

### Painel da Cozinha
- Quadro (kanban) com colunas: **Pedido recebido → Em preparo → Pronto → Entregue**.
- Mostra mesa, horário, itens e observações de cada pedido.
- Botões para avançar o status do pedido.
- **Auto-refresh** (polling) a cada 10 segundos.

### Painel do Gerente
- Dashboard com **faturamento do dia, pedidos do dia e mesas ocupadas/livres**.
- **CRUD completo de Produtos** (nome, descrição, preço, categoria, disponibilidade, imagem).
- **CRUD de Categorias**.
- Estrutura preparada para **upload de imagens** (com placeholder por enquanto).

---

## 🗄️ Estrutura do banco de dados

| Tabela        | Descrição                                                        |
|---------------|------------------------------------------------------------------|
| `roles`       | Perfis de acesso (Cliente, Garçom, Cozinha, Gerente)             |
| `users`       | Usuários do sistema, vinculados a um perfil (`role_id`)          |
| `categories`  | Categorias de produtos                                           |
| `products`    | Produtos do cardápio (preço, imagem, disponibilidade)            |
| `tables`      | Mesas do restaurante (status: livre/ocupada/fechada)            |
| `orders`      | Pedidos enviados à cozinha                                       |
| `order_items` | Itens de cada pedido (quantidade e preço unitário)              |
| `payments`    | Pagamentos das comandas (Pix, Dinheiro, Cartão)                 |

---

## 🚀 Instalação

### Pré-requisitos
- PHP **8.2+** com extensões: `mbstring`, `xml`, `mysql`, `curl`, `bcmath`, `gd`, `intl`
- **Composer** 2.x
- **MySQL 8** ou **MariaDB 10.4+**

### Passo a passo

```bash
# 1. Instalar dependências
composer install

# 2. Configurar o ambiente
cp .env.example .env
php artisan key:generate

# 3. Criar o banco de dados (ajuste usuário/senha conforme seu ambiente)
#    Exemplo (MySQL/MariaDB):
#    CREATE DATABASE restaurante CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 4. Editar o .env com os dados do seu banco:
#    DB_DATABASE=restaurante
#    DB_USERNAME=seu_usuario
#    DB_PASSWORD=sua_senha

# 5. Rodar migrations + seeders (cria tabelas e dados de exemplo)
php artisan migrate --seed

# 6. Criar o link de storage (para imagens enviadas)
php artisan storage:link

# 7. Iniciar o servidor
php artisan serve
```

Acesse: **http://localhost:8000**

---

## 👤 Contas de demonstração

Criadas automaticamente pelo seeder. Senha padrão: **`senha123`**

| Perfil  | E-mail                  | Painel inicial          |
|---------|-------------------------|-------------------------|
| Gerente | gerente@casarao.com     | Dashboard               |
| Garçom  | garcom@casarao.com      | Painel do Garçom        |
| Cozinha | cozinha@casarao.com     | Painel da Cozinha       |
| Cliente | cliente@casarao.com     | Mesas / Cardápio        |

> O **Gerente** tem acesso a todos os painéis (mesas, garçom, cozinha e gestão).

---

## 🧱 Organização do código

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # Login / logout / redirecionamento por perfil
│   │   ├── ClienteController.php       # Cardápio, comanda, pagamento
│   │   ├── GarcomController.php        # Mesas e pedidos prontos
│   │   ├── CozinhaController.php       # Fluxo de pedidos (kanban)
│   │   ├── GerenteController.php       # Dashboard
│   │   └── Gerente/
│   │       ├── ProductController.php   # CRUD de produtos
│   │       └── CategoryController.php  # CRUD de categorias
│   └── Middleware/
│       └── RoleMiddleware.php          # Proteção de rotas por perfil
├── Models/                             # Role, User, Category, Product, Table, Order, OrderItem, Payment
database/
├── migrations/                         # Estrutura das tabelas
└── seeders/                            # Dados de exemplo (perfis, usuários, cardápio, mesas, pedidos)
resources/views/                        # Telas Blade (auth, cliente, garcom, cozinha, gerente)
public/css/app.css                      # Estilos (identidade visual "O Casarão")
public/images/placeholder.svg           # Imagem padrão de produto
```

---

## 🔄 Fluxo de operação

1. **Cliente** seleciona a mesa, monta o pedido e envia para a cozinha → mesa fica **Ocupada**.
2. **Cozinha** recebe o pedido e avança o status: *recebido → em preparo → pronto*.
3. **Garçom** vê os pedidos **prontos** e marca como **entregues**.
4. **Cliente** (ou garçom) encerra a conta escolhendo a forma de pagamento → mesa volta a **Livre**.
5. **Gerente** acompanha o faturamento e gerencia o cardápio.

---

## 🛠️ Tecnologias

- Laravel 11.x · PHP 8.2
- MySQL / MariaDB
- Blade (server-side rendering)
- Tabler Icons + Google Fonts (Inter)

---

## 📌 Observações

- O upload de imagens já está implementado no CRUD de produtos (armazenado em `storage/app/public/products`);
  quando o produto não tem imagem, é exibido o **placeholder padrão**.
- Os painéis de Garçom e Cozinha usam **auto-refresh** simples (recarregamento periódico) para manter os dados atualizados.
