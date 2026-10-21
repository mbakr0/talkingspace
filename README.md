# Talkingspace

**A lightweight discussion community built with PHP and MySQL.** Talkingspace lets members publish categorized topics, search discussions, and share threaded replies through a responsive, accessible interface.

**Live demo:** https://talkingspace-ten.vercel.app/  
**Source:** https://github.com/mbakr0/talkingspace

## Highlights

- Responsive community UI with topic cards, category navigation, mobile-friendly forms, and empty states.
- Server-side search across discussion titles, post content, usernames, and categories.
- Account registration and login with password hashing; legacy MD5 hashes are upgraded after a successful login.
- Topic creation and replies with input validation and prepared SQL statements.
- Escaped user-generated content to reduce cross-site scripting (XSS) risk.
- Session-backed CSRF tokens on login, registration, topic creation, replies, and logout.
- PDO database layer with UTF-8 support, TLS certificate verification, and first-run schema initialization.
- Vercel PHP entry-point routing for the existing PHP pages.
- GitHub Actions PHP syntax checks on pushes and pull requests.

## Tech stack

- **Backend:** PHP 8+, PDO
- **Database:** MySQL-compatible database
- **Frontend:** HTML, CSS, Bootstrap, vanilla JavaScript
- **Deployment:** Vercel PHP runtime
- **Automation:** GitHub Actions

## Core functionality

| Area | Details |
| --- | --- |
| Accounts | Register, log in, log out, hashed passwords, optional avatar |
| Discussions | Create topics, select categories, browse latest topics |
| Replies | Read replies and add a reply while signed in |
| Discovery | Search by title, content, author username, and category |
| Security | Prepared statements, HTML escaping, server-side validation, secure session-cookie flags |
| Operations | Environment-based configuration and automatic creation of missing core tables |

## Project structure

```text
api/index.php                 Vercel PHP entry point and route allowlist
config/config.php             Environment-based configuration
core/init.php                 Session setup, helpers, and class autoloading
helpers/                      Redirect, formatting, and database helpers
libraries/Database.php        PDO connection and schema initialization
libraries/Topic.php           Topic/reply queries and search
libraries/User.php            Registration, authentication, avatar handling
templates/                    Shared layout and page templates
templates/css/custom.css      Responsive design system and UI styles
.github/workflows/php-lint.yml PHP syntax validation
vercel.json                   Vercel runtime and routing configuration
```

## Local development

1. Install PHP 8+ with PDO MySQL enabled and a MySQL-compatible database.
2. Clone this repository.
3. Configure the database using environment variables:

   ```bash
   export DB_HOST="127.0.0.1"
   export DB_USER="your_database_user"
   export DB_PASS="your_database_password"
   export DB_NAME="talkingspace"
   export BASE_URI="http://localhost:8000/"
   ```

4. Start the built-in PHP server from the repository root:

   ```bash
   php -S localhost:8000
   ```

5. Open http://localhost:8000.

On the first database connection, Talkingspace creates the core `users`, `categories`, `topics`, and `replies` tables if they do not exist, then seeds starter categories if needed. The configured database user therefore needs the required table-creation and insert privileges. For managed production databases, prefer applying schema changes through a controlled migration process and restrict database privileges after initialization.

## Deployment configuration

Use `.env.example` as a reference for the environment variable names. It is a template only; the application does not automatically load it as a dotenv file. Set actual values in your shell or hosting provider, and do not commit credentials.

Set these variables in the deployment environment; do not commit credentials:

- `DB_HOST` — hosted database hostname
- `DB_USER` — database username
- `DB_PASS` — database password
- `DB_NAME` — database/schema name
- `BASE_URI` — canonical public base URL, including trailing slash
- `DB_SSL_CA` — optional readable CA certificate path if your provider requires a custom CA bundle

Use a hosted MySQL-compatible database reachable from Vercel. The database must permit TLS connections and the configured account must have the privileges required by the application.

### Serverless deployment considerations

Vercel's filesystem is not durable storage. Avatar uploads currently use the local `images/avatars` directory, so production uploads should be moved to object storage (for example, S3-compatible storage) before relying on them. Likewise, confirm session persistence across serverless invocations in the chosen deployment; for production traffic, use a shared session store if the runtime's default file sessions are not reliable across instances.

## Quality checks

The GitHub Actions workflow runs `php -l` against PHP source files on every push and pull request. For local checks:

```bash
find . -name '*.php' -not -path './.git/*' -print0 | xargs -0 -n1 php -l
```

## Potential next steps

- Move avatar uploads to durable object storage.
- Add pagination and rate limiting for larger communities.
- Introduce versioned database migrations and integration tests.
- Add moderation tools and member profile pages.
