"""Buat docs/05-routes.md dari output `php artisan route:list --except-vendor --json`."""
import json, sys, collections

routes = json.load(open(sys.argv[1]))
groups = collections.defaultdict(list)
for r in routes:
    uri = r['uri'].lstrip('/')
    parts = uri.split('/')
    key = parts[0] or '/'
    if key in ('master', 'transaction', 'report', 'finance', 'export', 'import') and len(parts) > 1:
        key += '/' + parts[1].split('{')[0]
    groups[key].append(r)


def access(mw):
    roles = [m.split('RoleMiddleware:')[1] for m in mw if 'RoleMiddleware:' in m]
    if roles:
        return ' + '.join(r.replace('|', ', ') for r in roles)  # beberapa grup role = semuanya harus lolos
    if any('Authenticate' in m for m in mw):
        return 'login'
    return 'publik'


out = [
    "# Routes", "",
    "Dihasilkan otomatis. Jangan edit tabel ini dengan tangan. Setelah mengubah file di `routes/`, jalankan:", "",
    "```bash",
    "php artisan route:list --except-vendor --json > /tmp/routes.json",
    "python3 docs/tools/gen_routes.py /tmp/routes.json > docs/05-routes.md",
    "```", "",
    "`routes/web.php` memuat file per modul dari `routes/admin/`, `routes/agent/`, `routes/transaction/`, dan `routes/landingpage/`. "
    "Kolom Akses berisi role Spatie; `a + b` berarti route ada di dua grup role sekaligus dan user harus lolos keduanya.", "",
    f"Total {len(routes)} route.", "",
]
for key in sorted(groups):
    out += [f"## `{key}`", "", "| Method | URI | Name | Controller@method | Akses |", "|---|---|---|---|---|"]
    for r in sorted(groups[key], key=lambda r: (r['uri'], r['method'])):
        action = r['action'].replace('App\\Http\\Controllers\\', '')
        out.append(f"| {r['method'].replace('|HEAD', '')} | `{r['uri']}` | {r['name'] or ''} | `{action}` | {access(r['middleware'])} |")
    out.append("")
print("\n".join(out))
