# 🚀 Quick Setup: Database Sessions with External MySQL

## What Changed?

Your configuration now uses:

```properties
SESSION_DRIVER=database  # Sessions stored in external MySQL
CACHE_STORE=file         # Cache remains fast (local files)
```

---

## ⚠️ IMPORTANT: Create Sessions Table

Before the app works with `SESSION_DRIVER=database`, you MUST create the sessions table.

### Run These Commands:

```powershell
# 1. Generate the session table migration
php artisan session:table

# 2. Run the migration
php artisan migrate

# 3. Clear and recache config
php artisan config:clear
php artisan config:cache
```

---

## 📋 What This Does

The `session:table` command creates a migration file that looks like this:

```php
Schema::create('sessions', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->longText('payload');
    $table->integer('last_activity')->index();
});
```

---

## ✅ Verify It Works

### Test 1: Check Sessions Table

```sql
-- In MySQL
SHOW TABLES LIKE 'sessions';
DESCRIBE sessions;
```

### Test 2: Test the Application

```powershell
php artisan serve
# Visit http://localhost:8000
# Log in to create a session
```

### Test 3: Check Session is Stored in DB

```sql
-- In MySQL
SELECT id, user_id, last_activity FROM sessions;
```

---

## 📊 Performance Expectations

With hybrid approach (session=DB, cache=file):

| Metric                  | Value                  |
| ----------------------- | ---------------------- |
| **Queries per request** | 4-6 (+1 for session)   |
| **Latency per request** | 90-130ms               |
| **DB connections**      | 1-2                    |
| **Cache latency**       | 1-2ms (file) ⚡        |
| **Session latency**     | 10-30ms (network + DB) |

**Total overhead:** +10-20ms compared to file sessions - totally acceptable!

---

## 🔧 Optional: Session Cleanup Configuration

Add to `config/session.php` to optimize garbage collection:

```php
'lottery' => [1, 1000],  // 0.1% chance to cleanup old sessions per request
```

This reduces DB writes while keeping sessions clean.

---

## 🎯 Benefits of This Setup

1. ✅ **Sessions persist** - Survive app restarts
2. ✅ **Multi-server ready** - Can scale horizontally later
3. ✅ **Fast cache** - File cache is blazing fast
4. ✅ **Low overhead** - Only +10-20ms per request
5. ✅ **Saves DB load** - Cache doesn't hit DB
6. ✅ **Perfect for 0.3 CPU / 0.3 GB RAM**

---

## 🐛 Troubleshooting

### Error: "SQLSTATE[42S02]: Base table or view not found: 1146 Table 'sessions' doesn't exist"

**Solution:**

```powershell
php artisan session:table
php artisan migrate
```

### Sessions not persisting?

**Check:**

1. `SESSION_DRIVER=database` in `.env`
2. Sessions table exists: `SHOW TABLES LIKE 'sessions';`
3. Config cached: `php artisan config:cache`

### High DB load?

If session queries are too many:

1. Switch back to file: `SESSION_DRIVER=file`
2. Or use Redis: `SESSION_DRIVER=redis` (requires Redis server)

---

**You're all set! Your app now uses the optimal hybrid approach.** 🎉
