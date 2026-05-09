# Inab_portfolio

This is about Inab Computer Enterprise, a digital brand responsible for helping businesses with creative designs and websites for their use.

## MySQL-backed admin CMS

The portfolio now uses a MySQL database for admin-managed content and user accounts. Admins can create, read, update, and delete:

- Services shown on the homepage
- Portfolio projects shown on the homepage
- Product/service updates shown below the public portfolio grid
- Contact messages submitted from the public form (private to the dashboard)
- Admin/editor users (private to the dashboard and never rendered on the public portfolio)

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
| `WHATSAPP_NUMBER` | empty string; set this to your international WhatsApp number, for example `2348012345678` |

The first run seeds a default administrator if no admin exists:

- Username: `admin`
- Password: `admin123`

Visit `admin/` after logging in. Change the seeded password immediately after your first login from the **Users** section in `admin/users.php`. Use **Services**, **Projects**, and **Updates** to control public portfolio content; **Users** and **Messages** stay private inside the admin dashboard.
