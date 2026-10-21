# Deploying TalkingSpace on Vercel

## Before you deploy

TalkingSpace is a legacy PHP application with custom routing/classes and a database. The included Vercel entry point forwards requests to the repository's existing `index.php`.

1. Create a hosted MySQL database and import the project's schema/dump. Confirm its tables and columns match the application queries.
2. Import this GitHub repository into Vercel.
3. Add the database environment variables expected by the app's database configuration. Confirm the configuration reads `DB_HOST`, `DB_USER`, `DB_PASS`, and `DB_NAME`; if it does not, adapt that config to read them before deployment.
4. Deploy and test the front page, registration, login/logout, posting topics/replies, and any profile image uploads.

## Limitations to check

- This is a legacy PHP app; review compatibility with the selected PHP runtime and replace removed PHP features if required.
- Vercel's filesystem is not persistent. Use external object storage for profile images/uploads.
- Test sessions and all database writes after deployment.
- Do not commit database credentials.
- This configuration is a starting point; verify Vercel build and function logs and test the app before sharing it publicly.
