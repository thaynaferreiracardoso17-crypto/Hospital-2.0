# Hospital Vida Plena — Trabalho Final (2º DS)

Site com PHP + MySQL, HTML, CSS e JavaScript.

## Rodar localmente
1. Importe `database.sql` no MySQL (phpMyAdmin ou `mysql -u root < database.sql`).
2. Ajuste `config/config.php` (host, usuário, senha do banco).
3. Crie o administrador: `php criar_admin.php "Seu Nome" voce@email.com SenhaForte123`
4. Inicie: `php -S localhost:8000` e abra http://localhost:8000

## Publicar
Use uma hospedagem com PHP + MySQL e HTTPS. Envie os arquivos, importe o `database.sql`, edite `config/config.php`, rode o `criar_admin.php` (ou insira o admin) e **apague** o `criar_admin.php` do servidor.

## Onde está cada requisito
- Páginas (>5): index, especialidades, medicos, sobre, cadastro, login, agendar, minhas-consultas, admin/*
- Responsivo: `assets/css/style.css` (menu hambúrguer em telas pequenas)
- Cadastro + login: `cadastro.php`, `login.php` (áreas restritas: agendar, minhas-consultas, admin)
- CRUD admin: `admin/usuarios.php`, `admin/medicos.php`, `admin/consultas.php`
- SQL seguro: PDO com consultas parametrizadas (`config/config.php`)
- Senha com hash: `password_hash` / `password_verify`
- Validação no servidor: `includes/functions.php` (`validar_usuario`, `validar_consulta`)
- XSS: toda saída passa por `e()` (htmlspecialchars); CSRF com token nos formulários
- Geolocalização: `assets/js/geo.js` (Geolocation API + Nominatim/OpenStreetMap, preenche o endereço no cadastro)
- HTTPS: `.htaccess` redireciona para HTTPS (Apache)
