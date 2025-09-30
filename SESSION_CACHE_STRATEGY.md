# 🗄️ Session & Cache Strategy with External MySQL Database

## Your Scenario

-   **App Server:** 0.3 CPU / 0.3 GB RAM
-   **Database:** External 3rd-party MySQL (separate server)
-   **Target:** 100 concurrent users

---

## 📊 Performance Comparison

### Option 1: Both File-based (Current)

```properties
CACHE_STORE=file
SESSION_DRIVER=file
```

**Metrics:**

-   Session read latency: **1-2ms** ⚡ (local disk)
-   Cache read latency: **1-2ms** ⚡ (local disk)
-   DB connections used: **0** (for session/cache)
-   Network calls: **0** (for session/cache)

**Pros:**

-   ✅ Fastest possible
-   ✅ No DB load for sessions/cache
-   ✅ No network overhead
-   ✅ Saves DB connections

**Cons:**

-   ❌ Can't scale horizontally (multi-server)
-   ❌ Sessions lost on app restart
-   ❌ Cache not shared between servers

**Best for:**

-   Single server deployment
-   Maximum performance
-   No horizontal scaling plans

---

### Option 2: Both Database (All-in-DB)

```properties
CACHE_STORE=database
SESSION_DRIVER=database
```

**Metrics:**

-   Session read latency: **10-30ms** 🐢 (network + DB)
-   Cache read latency: **10-30ms** 🐢 (network + DB)
-   DB connections used: **1-3 per request**
-   Network calls: **2-4 per request**

**Pros:**

-   ✅ Multi-server ready
-   ✅ Sessions survive app restart
-   ✅ Centralized state

**Cons:**

-   ❌ 5-15x slower than file cache
-   ❌ More DB load
-   ❌ Uses DB connections
-   ❌ Network latency on every request

**Best for:**

-   Multi-server deployments
-   Load-balanced setups
-   Need session persistence

---

### Option 3: Hybrid (RECOMMENDED) ⭐

```properties
SESSION_DRIVER=database  # Centralized, accessed every request
CACHE_STORE=file         # Fast, accessed frequently
```

**Metrics:**

-   Session read latency: **10-30ms** (network + DB)
-   Cache read latency: **1-2ms** ⚡ (local disk)
-   DB connections used: **1 per request** (for session only)
-   Network calls: **1 per request** (for session only)

**Pros:**

-   ✅ Sessions survive restarts & support multi-server
-   ✅ Fast cache access (no network)
-   ✅ Moderate DB load
-   ✅ Best balance

**Cons:**

-   ⚠️ Cache not shared (usually okay for your use case)
-   ⚠️ One network call per request (acceptable)

**Best for:**

-   Your scenario (0.3 CPU / 0.3 GB RAM / 100 users)
-   May scale horizontally later
-   Want fast cache with persistent sessions

---

### Option 4: Redis/Memcached (If Available)

```properties
SESSION_DRIVER=redis
CACHE_STORE=redis
```

**Metrics:**

-   Session read latency: **1-5ms** ⚡⚡
-   Cache read latency: **1-5ms** ⚡⚡
-   Memory usage: **+50-100MB** for Redis

**Pros:**

-   ✅ Fastest shared cache
-   ✅ Multi-server ready
-   ✅ Advanced features (TTL, atomic ops)

**Cons:**

-   ❌ Requires additional service
-   ❌ Uses extra memory (but you have 0.3GB constraint)
-   ❌ More complex setup

**Best for:**

-   If you have 50-100MB to spare
-   Need high performance + multi-server
-   Database is overloaded

---

## 🎯 Recommendation for Your Setup

### **Use Hybrid Approach:**

```properties
SESSION_DRIVER=database
CACHE_STORE=file
```

### **Why?**

1. **Your cache strategy is already optimized:**

    - Tag counts cached for 10 minutes (infrequent writes)
    - File cache is perfect for this use case
    - Saves network round trips

2. **Sessions benefit from DB:**

    - Only 1 extra network call per request
    - Allows future horizontal scaling
    - Sessions persist across restarts

3. **Network latency is acceptable:**
    - Modern external MySQL databases (RDS, PlanetScale) have <20ms latency
    - One session read per request is manageable
    - Your DB is external anyway (not competing for RAM)

---

## 📝 Configuration Changes

### Update `.env`:

```properties
# Hybrid approach (recommended)
SESSION_DRIVER=database
CACHE_STORE=file
```

### Ensure session table exists:

```bash
php artisan session:table
php artisan migrate
```

The migration creates a `sessions` table:

```sql
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);
```

---

## 📊 Expected Performance Impact

### Current (both file):

-   **Queries per request:** 3-5
-   **Latency per request:** 80-120ms
-   **DB connections:** 1

### With Hybrid (session=DB, cache=file):

-   **Queries per request:** 4-6 (+1 for session)
-   **Latency per request:** 90-130ms (+10-20ms for session read)
-   **DB connections:** 1-2

### Impact:

-   ⬆️ Latency: **+10-20ms** (acceptable)
-   ⬆️ DB load: **+1 query per request** (minimal)
-   ✅ Multi-server ready
-   ✅ Cache remains fast

---

## 🔍 When to Reconsider

### Switch to all-DB (SESSION + CACHE both database) if:

-   You're running **multiple app server instances**
-   You need **shared cache** between servers
-   Your external DB has **very fast network** (<5ms latency)

### Switch to Redis if:

-   Your DB is **overloaded** with session queries
-   You can spare **50-100MB RAM** for Redis
-   You need **advanced cache features** (atomic counters, pub/sub)

### Keep all-file if:

-   You're **definitely single-server** forever
-   You want **absolute minimum latency**
-   You don't care about session persistence

---

## ⚙️ Optimization for Database Sessions

If using `SESSION_DRIVER=database`, add to `config/session.php`:

```php
// Optimize session table name and connection
'connection' => env('SESSION_CONNECTION', null), // Use default DB connection
'table' => env('SESSION_TABLE', 'sessions'),

// Session lottery for garbage collection
// [1, 100] means 1% chance of cleanup per request
'lottery' => [1, 100],  // Adjust to [1, 1000] if you want less frequent cleanup
```

Add to `.env`:

```properties
SESSION_LIFETIME=120  # 2 hours (already set)
```

---

## 🧪 Testing the Difference

### Test File vs Database Performance:

```bash
# Test with file cache
php artisan tinker
>>> $start = microtime(true);
>>> cache()->remember('test', 60, fn() => 'value');
>>> echo (microtime(true) - $start) * 1000 . ' ms';
# Expected: 1-3ms

# Test with database cache (temporarily change .env)
# CACHE_STORE=database
>>> php artisan config:clear
>>> $start = microtime(true);
>>> cache()->remember('test', 60, fn() => 'value');
>>> echo (microtime(true) - $start) * 1000 . ' ms';
# Expected: 10-30ms (with external DB)
```

---

## 📋 Action Plan

### Step 1: Decide Your Strategy

**For most cases (RECOMMENDED):**

```properties
SESSION_DRIVER=database
CACHE_STORE=file
```

### Step 2: Update Configuration

```bash
# Update .env file
# SESSION_DRIVER=database
# CACHE_STORE=file

# Create sessions table if using database sessions
php artisan session:table
php artisan migrate

# Clear and recache config
php artisan config:clear
php artisan config:cache
```

### Step 3: Test

```bash
# Test the application
php artisan serve

# Load test
ab -n 1000 -c 100 http://localhost:8000/
```

### Step 4: Monitor

Watch these metrics:

-   Average response time (should be < 150ms)
-   DB query count per request (should be 4-6)
-   Memory usage (should be < 250MB)

---

## 🎯 Final Recommendation

**Use the Hybrid approach:**

```properties
SESSION_DRIVER=database
CACHE_STORE=file
```

This gives you:

-   ✅ Fast cache access (1-2ms)
-   ✅ Persistent sessions
-   ✅ Multi-server capability
-   ✅ Low DB load
-   ✅ Only +10-20ms latency per request

**Perfect balance for your 0.3 CPU / 0.3 GB RAM constraint with 100 concurrent users!**
