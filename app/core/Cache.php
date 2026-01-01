<?php

class Cache {
    private static $cachePath = __DIR__ . '/../cache';

    /**
     * Set data into cache.
     *
     * @param string $key Unique identifier for the cache entry.
     * @param mixed $data The data to be cached.
     * @param int $ttl Time-to-live in seconds. Default is 1 hour (3600s).
     * @return bool
     */
    public static function set($key, $data, $ttl = 3600) {
        $filePath = self::getFilePath($key);
        $expiration = time() + $ttl;
        
        $payload = serialize([
            'expires' => $expiration,
            'data' => $data
        ]);

        if (!is_dir(self::$cachePath)) {
            mkdir(self::$cachePath, 0775, true);
        }

        return file_put_contents($filePath, $payload) !== false;
    }

    /**
     * Get data from cache.
     *
     * @param string $key Unique identifier for the cache entry.
     * @return mixed Cached data or null if not found or expired.
     */
    public static function get($key) {
        $filePath = self::getFilePath($key);

        if (!file_exists($filePath)) {
            return null;
        }

        $payload = unserialize(file_get_contents($filePath));

        if ($payload === false || !isset($payload['expires']) || !isset($payload['data'])) {
            self::delete($key); // Corrupted file
            return null;
        }

        if (time() > $payload['expires']) {
            self::delete($key); // Expired
            return null;
        }

        return $payload['data'];
    }

    /**
     * Check if a cache key exists and is not expired.
     *
     * @param string $key
     * @return bool
     */
    public static function has($key) {
        return self::get($key) !== null;
    }

    /**
     * Delete a cache entry.
     *
     * @param string $key
     * @return bool
     */
    public static function delete($key) {
        $filePath = self::getFilePath($key);
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Clear the entire cache.
     *
     * @return bool
     */
    public static function clear() {
        $files = glob(self::$cachePath . '/*.cache');
        $success = true;
        foreach ($files as $file) {
            if (unlink($file) === false) {
                $success = false;
            }
        }
        return $success;
    }

    /**
     * Generates a file path for a given cache key.
     *
     * @param string $key
     * @return string
     */
    private static function getFilePath($key) {

        return self::$cachePath . '/' . md5($key) . '.cache';
    }
}
