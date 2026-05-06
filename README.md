# Inab_portfolio

This is about Inab Computer Enterprise, a digital brand responsible for helping businesses with creative designs and websites for their use.

## MySQL-backed admin CMS

The portfolio now uses a MySQL database for admin-managed content and user accounts. Admins can create, read, update, and delete:

- Services shown on the homepage
- Portfolio projects shown on the homepage
- Admin/editor users

### Database setup

1. Create a MySQL database named `inab_portfolio`.
2. Import `database.sql`, or let the PHP app create the tables automatically when it first connects with a user that has `CREATE TABLE` privileges.
3. Configure these environment variables for your server if the defaults do not match your MySQL setup:

| Variable | Default |
| --- | --- |
| `DB_HOST` | `127.0.0.1` |
| `DB_PORT` | `3306` |
| `DB_NAME` | `inab_portfolio` |
| `DB_USER` | `root` |
| `DB_PASS` | empty string |

The first run seeds a default administrator if no admin exists:

- Username: `admin`
- Password: `admin123`

Visit `admin/` after logging in. Change the seeded password immediately after your first login from the **Users** section in `admin/users.php`.
