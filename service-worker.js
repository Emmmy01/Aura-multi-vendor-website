const CACHE_NAME = 'aura-cache-v1';
const urlsToCache = [
  '/Aura_Mutivendor/index.php',
  '/Aura_Mutivendor/style.css',
  '/Aura_Mutivendor/script.js',
  '/Aura_Mutivendor/icons/Gray_and_Black_Simple_Studio_Logo__1_-removebg-preview.png'
];

// INSTALL — Cache files
self.addEventListener('install', (e) => {
  console.log('✅ Service Worker installed.');
  e.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(urlsToCache);
    })
  );
});

// ACTIVATE — Clean up old caches
self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys().then((keyList) =>
      Promise.all(
        keyList.map((key) => {
          if (key !== CACHE_NAME) {
            console.log('🧹 Removing old cache:', key);
            return caches.delete(key);
          }
        })
      )
    )
  );
});

// FETCH — Serve from cache first, then network
self.addEventListener('fetch', (e) => {
  e.respondWith(
    caches.match(e.request).then((response) => {
      return response || fetch(e.request);
    })
  );
});
