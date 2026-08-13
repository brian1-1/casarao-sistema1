# 📤 Comandos para Fazer Push no GitHub

## 1️⃣ Adicione as mudanças

```bash
cd ~/Documentos/projeto-oficina-restaurante/casarao-sistema1
git add .
```

## 2️⃣ Verifique o status (opcional)

```bash
git status
```

Deveria aparecer os arquivos modificados (como `.env`, migrations, etc)

## 3️⃣ Commit com mensagem descritiva

```bash
git commit -m "feat: setup completo do projeto com MariaDB e migrations

- Adicionado .env com configurações do banco
- Composer dependencies instaladas e atualizadas
- MariaDB configurado como banco de dados
- Todas as migrations executadas com sucesso
- Projeto rodando em http://localhost:8000"
```

## 4️⃣ Faça o Push

```bash
git push origin main
```

Se der erro de autenticação, tente:

```bash
git push origin main --force
```

## 5️⃣ Atualize o README no GitHub

Se quiser adicionar o texto de setup ao README.md existente:

```bash
# Edite o arquivo README.md e adicione o conteúdo de atualização
# Depois faça commit e push novamente

git add README.md
git commit -m "docs: adicionar instruções de setup local"
git push origin main
```

---

## ⚠️ Importante: Gitignore

Certifique-se de que seu `.gitignore` tem:

```
/vendor
/node_modules
.env
.env.local
/storage/logs/*
/storage/app/*
```

Isso garante que:
- ✅ `vendor/` não sobe (dependencies)
- ✅ `.env` não sobe (segurança)
- ✅ Logs não sobem

---

## 🔍 Verificar Histórico (opcional)

```bash
git log --oneline
```

Para ver todos os commits do seu repositório.

---

## 💡 Resumo Rápido

```bash
cd ~/Documentos/projeto-oficina-restaurante/casarao-sistema1
git add .
git commit -m "feat: setup completo com MariaDB e migrations"
git push origin main
```

Pronto! Seu projeto está atualizado no GitHub! 🚀
