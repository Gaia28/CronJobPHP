# CronJob com PHP

Projeto prático para estudar automacao de tarefas com `cron` em um contexto real e simples.

A ideia central e usar PHP para:
- consumir uma API publica de liturgia;
- montar um conteudo de e-mail dinamico;
- enviar esse e-mail automaticamente diáriamente em horarios definidos.

## Proposta educacional

Este repositorio foi pensado como um laboratorio de aprendizado. Mais do que "funcionar", ele ajuda a entender conceitos importantes de back-end e operacao:

- **Automacao com cron**: como agendar scripts para rodar sem intervencao manual.
- **Integracao com API externa**: como buscar dados HTTP com `cURL`.
- **Envio de e-mail SMTP**: configuracao de credenciais e envio com `PHPMailer`.
- **Boas praticas basicas de ambiente**: uso de variaveis de ambiente com `phpdotenv`.
- **Fluxo de deploy simples**: execução em servidor caseiro via SSH.

Em resumo, e um projeto pequeno com alto valor didatico para quem quer sair da teoria e praticar automacao no dia a dia.

## Como o projeto funciona

O arquivo `src/SendEmail.php` executa este fluxo:

1. Carrega dependencias do Composer.
2. Le variaveis de ambiente do `.env`.
3. Faz uma requisicao para `https://liturgia.up.railway.app/v2/`.
4. Extrai o salmo do JSON retornado.
5. Monta um e-mail HTML com os dados.
6. Envia o e-mail via SMTP.

## Requisitos

- PHP 8.0+ (recomendado)
- Composer
- Extensao `curl` habilitada no PHP
- Conta SMTP valida para envio dos e-mails

## Instalacao

1. Acesse seu servidor via SSH (ou use sua maquina local).
2. Clone o repositorio:

```bash
git clone https://github.com/Gaia28/CronJobPHP.git
cd CronJobPHP
```

3. Instale as dependencias:

```bash
composer install
```

## Configuracao do ambiente

Crie um arquivo `.env` na raiz do projeto com base no exemplo abaixo:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.seuprovedor.com
MAIL_PORT=587
MAIL_USERNAME=seu_usuario
MAIL_PASSWORD=sua_senha
MAIL_FROM_ADDRESS=seu-email@dominio.com
MAIL_FROM_NAME=Seu Nome
```

Observacoes:
- `MAIL_SCHEME` geralmente sera `tls` (porta `587`) ou `ssl` (porta `465`).
- O destinatario atual esta fixo no codigo (`src/SendEmail.php`).

## Execucao manual

Antes de automatizar, teste o script manualmente:

```bash
php src/SendEmail.php
```

Se tudo estiver correto, o e-mail sera enviado com o salmo do dia.

## Agendamento com cron

Edite o crontab do usuario:

```bash
crontab -e
```

Exemplo para executar todos os dias as 07:00:

```cron
0 7 * * * /usr/bin/php /caminho/absoluto/CronJobPHP/src/SendEmail.php >> /caminho/absoluto/CronJobPHP/cron.log 2>&1
```

Dicas:
- Use caminho absoluto para o executavel do PHP e para o script.
- Redirecione logs para facilitar diagnostico de erros.

## Estrutura do projeto

```text
CronJobPHP/
|- src/
|  |- SendEmail.php
|- vendor/
|- composer.json
|- README.md
```

## Possiveis evolucoes

- Tornar destinatario configuravel via `.env`.
- Adicionar tratamento de falhas de rede e retries.
- Registrar logs estruturados (arquivo diario).
- Cobrir regras de negocio com testes.
- Permitir outros tipos de notificacao (Telegram, WhatsApp, etc.).

