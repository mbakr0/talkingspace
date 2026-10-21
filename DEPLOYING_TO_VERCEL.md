# Deploying TalkingSpace

TalkingSpace is a legacy PHP discussion forum with custom routing, sessions, avatar uploads, and a MySQL database. This Vercel setup is a starting point, not proof of a production-ready deployment.

## Required setup

1. Provision a hosted MySQL database and import the project's schema/data. Confirm table names and columns match the app's queries.
2. Configure DB_HOST, DB_USER, DB_PASS, and DB_NAME as environment variables. Use a least-privilege database account.
3. Set BASE_URI to the canonical public URL with a trailing slash, for example https://your-domain.example/.
4. Deploy and test the home page, registration, login/logout, topic creation, replies, invalid IDs, and duplicate usernames/emails.

## Production limitations to resolve

- Vercel's filesystem is ephemeral. Uploaded avatars saved under images/avatars will not be reliably persistent; use external object storage before relying on user uploads.
- Test sessions across multiple requests and instances. File-backed PHP sessions may not be reliable in a serverless deployment; use a supported shared session store if needed.
- Review the historical Git history for previously committed database credentials. Rotate any credential that was ever committed, even if the current file uses environment variables.
- Use HTTPS and confirm the database provider's TLS requirements.
- The repository uses a community PHP runtime. Check build/function logs and run end-to-end tests; do not treat a successful build as a security or runtime audit.