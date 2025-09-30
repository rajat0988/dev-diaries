# 🚀 Quick Deployment Checklist for Low-Resource Server

## Pre-Deployment (Run these before deploying)

### 1. Database Migration

```bash
php artisan migrate
```

This adds performance indexes for faster queries.

### 2. Clear and Cache

```bash
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Optimize Composer

```bash
composer install --optimize-autoloader --no-dev
```

### 4. Build Assets

```bash
npm run build
# or
npm run production
```

---

## Production .env Settings

```properties
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error

CACHE_STORE=file
SESSION_DRIVER=file
BCRYPT_ROUNDS=10
```

---

## Server Configuration

### PHP (php.ini) - CRITICAL

```ini
opcache.enable=1
opcache.memory_consumption=64
opcache.validate_timestamps=0
memory_limit=256M
realpath_cache_size=4096K
```

### MySQL (my.cnf)

```ini
innodb_buffer_pool_size=128M
max_connections=50
query_cache_size=16M
```

---

## Verify Performance

### Test 1: Check Response Time

```bash
curl -w "@-" -o /dev/null -s http://your-site.com <<'EOF'
    time_namelookup:  %{time_namelookup}\n
       time_connect:  %{time_connect}\n
    time_appconnect:  %{time_appconnect}\n
      time_redirect:  %{time_redirect}\n
 time_starttransfer:  %{time_starttransfer}\n
                    ----------\n
         time_total:  %{time_total}\n
EOF
```

**Target:** < 0.2 seconds

### Test 2: Load Test (100 concurrent users)

```bash
ab -n 1000 -c 100 http://your-site.com/
```

**Target:** 0% failed requests, < 200ms average response time

---

## Monitor These Metrics

| Metric                | Target   | Command                     |
| --------------------- | -------- | --------------------------- |
| Memory Usage          | < 250 MB | `free -m`                   |
| CPU Usage             | < 50%    | `top`                       |
| Active DB Connections | < 10     | `SHOW PROCESSLIST;` (MySQL) |
| Response Time         | < 200ms  | Browser DevTools            |

---

## Troubleshooting

### High Memory?

1. Check for memory leaks: `php artisan tinker >>> memory_get_usage(true);`
2. Reduce `memory_limit` in php.ini to 128M
3. Check MySQL `innodb_buffer_pool_size`

### High CPU?

1. Verify OPcache is enabled: `php -i | grep opcache`
2. Check slow queries: `SET GLOBAL slow_query_log = 'ON';`
3. Reduce BCRYPT_ROUNDS to 8 if needed

### Slow Response?

1. Clear all caches: `php artisan cache:clear`
2. Re-run: `php artisan config:cache`
3. Check database indexes are applied: `SHOW INDEX FROM questions;`

---

## 🎯 Expected Results

With all optimizations:

-   ✅ 0.15-0.25 CPU cores (avg) under 100 users
-   ✅ 180-250 MB RAM (avg) under 100 users
-   ✅ 80-150ms response time (avg)
-   ✅ **Fits comfortably in 0.3 CPU / 0.3 GB RAM!**

---

**Last Updated:** September 30, 2025
