# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Repository layout

This is a monorepo for **CRMEB** (开源商城系统, v5.6.x). Three independently-built sub-projects sit side by side and talk to each other over HTTP:

- `crmeb/` — PHP backend (ThinkPHP 6, PHP 7.1–7.4). Contains four apps (`adminapi`, `api`, `kefuapi`, `outapi`) plus the shared `crmeb\` library. This is the only sub-project with a composer root and the `think` CLI.
- `template/admin/` — Vue 2 + Element UI admin SPA (vue-cli 3). Built output is served from `crmeb/public/admin/`.
- `template/uni-app/` — UniApp multi-end frontend (H5 / 小程序 / APP). Must be built with HBuilderX — there is no meaningful npm script here.
- `docker-compose/` — optional containerized dev environment (nginx / php / mysql / redis / workerman).
- `readme/` — images/docs only; `安装必读.docx` and `readme/安装说明.md` describe bare-metal install.

## Common commands

All `php think …` commands run from `crmeb/`. All `npm` admin commands run from `template/admin/`.

### Backend (crmeb/)

```bash
composer install                         # install PHP deps (uses aliyun mirror)
php think queue:listen --queue           # message queue worker (managed by Supervisor in prod)
php think workerman start --d            # long-connection server (chat + admin channels)
php think timer start --d                # scheduled task runner
php think migrate:run                    # run topthink/think-migration migrations
php think util <name>                    # custom utility commands under crmeb\command\Util
```

On Windows, `crmeb/workerman.bat` starts chat/admin/timer/channel workerman processes together.

Console commands are registered in `crmeb/config/console.php`: `workerman`, `timer`, `util`, `npm`.

### Admin (template/admin/)

```bash
npm run dev       # or `npm run serve` — runs src/libs/start.js then vue-cli serve --mode=dev
npm run build     # production build (output typically copied to crmeb/public/admin/)
npm run eslint    # lint .js/.vue/.ts under src with --fix
npm run prettier  # format everything
```

Node 14–22 required (`engines` in package.json). A pre-commit gitHook runs `vue-cli-service lint` via lint-staged.

### UniApp (template/uni-app/)

No build scripts in package.json. Open in HBuilderX and run/publish per target (H5, 微信小程序, App). Config lives in `manifest.json` and `pages.json`.

## Backend architecture

ThinkPHP 6 multi-app mode (`config/app.php` → `auto_multi_app = true`). URL prefix selects the app: `/adminapi/...`, `/api/...`, `/kefuapi/...`, `/outapi/...`. The `admin_prefix` config controls the admin path; `route/route.php` falls through to serving the SPA HTML for admin/kefu/app/home paths.

Each app directory (`crmeb/app/<appname>/`) follows the same shape: `controller/`, `route/`, `middleware/`, `validate/`, `config/`, plus its own `*ExceptionHandle.php` and `provider.php`. Adminapi controllers live under `controller/v1/<module>/`.

**Layered architecture — always flow controller → service → dao → model:**

- `app/controller` — thin HTTP handlers, input validation via `app/<appname>/validate`.
- `app/services/<module>/` — business logic. Extend `BaseServices`. This is where orchestration, transactions, and cross-module calls belong.
- `app/dao/<module>/` — data-access objects. Extend `BaseDao`. Every query should go through a Dao, not a Model directly.
- `app/model/<module>/` — thin ORM models (ThinkPHP `think\Model`), mainly for relations and accessors.
- `app/jobs/` — queue job handlers (`php think queue:listen` dispatches these: orders, refunds, posters, live, translate, etc.).

`crmeb/` (the namespace, at `crmeb/crmeb/`) is the framework-agnostic shared library: `basic/`, `services/` (CacheService, FormBuilder, HttpService, pay/sms/upload/easywechat/workerman adapters), `traits/`, `utils/`, `exceptions/`, `interfaces/`. Prefer adding reusable infra here; put shopping-domain code under `app/services/`.

Autoload (`composer.json`): `app\` → `crmeb/app`, `crmeb\` → `crmeb/crmeb`, PSR-0 fallback to `crmeb/extend/`.

Key integrations wired through `crmeb\services\`: WeChat (overtrue/easywechat v3), Alipay (easysdk), multiple cloud storage (阿里云/腾讯云/七牛/京东/华为/AWS S3), multiple SMS providers, form-builder (xaboy/form-builder 2.0.17), JWT auth (firebase/php-jwt), snowflake IDs, phpspreadsheet for imports/exports.

## Admin architecture

Vue 2 + Vuex + Vue Router + Element UI 2.15. Source under `template/admin/src/`:

- `api/` — axios request modules, one file per backend module.
- `pages/` — route views; mirrors adminapi controller modules.
- `layout/`, `components/`, `plugin/` — shared UI.
- `libs/start.js` — pre-`serve` hook (runs before dev server starts).
- `setting.js` — API base URL and global config.
- `vue.config.js` — webpack/dev-server config, including dev proxy to the PHP backend.

The admin talks to `/adminapi/...`. Auth is JWT; the token flows through an axios interceptor.

**Code generation:** `config/app.php` sets `admin_template_path` to `template/admin/src/` and `crud_make = true`. The backend CRUD tool (`crmeb\services\crud`, adminapi `route/crud.php`) generates both PHP (controller/service/dao/model/validate) and Vue (pages + api) files into those paths. If you edit generator templates, changes land in both sides.

## Runtime requirements

- PHP 7.1–7.4 with `ext-json`, `ext-curl`, `ext-bcmath`, `ext-mbstring`; optionally `redis`, `fileinfo`.
- MySQL 5.7–8.0 (InnoDB), Redis optional (falls back to file cache).
- Production needs Supervisor to keep `queue:listen`, `workerman start`, and `timer start` alive.
- PHP functions that MUST NOT be disabled: `proc_open`, `pcntl_signal`, `pcntl_signal_dispatch`, `pcntl_fork`, `pcntl_wait`, `pcntl_alarm`.
