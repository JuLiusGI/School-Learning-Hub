const queueDbName = 'offline-sync';
const queueStore = 'queue';

const openQueueDb = () => new Promise((resolve, reject) => {
    const request = window.indexedDB.open(queueDbName, 1);
    request.onupgradeneeded = () => {
        const db = request.result;
        if (!db.objectStoreNames.contains(queueStore)) {
            db.createObjectStore(queueStore, { keyPath: 'id', autoIncrement: true });
        }
    };
    request.onsuccess = () => resolve(request.result);
    request.onerror = () => reject(request.error);
});

const withStore = async (mode, callback) => {
    const db = await openQueueDb();
    return new Promise((resolve, reject) => {
        const tx = db.transaction(queueStore, mode);
        const store = tx.objectStore(queueStore);
        const result = callback(store);
        tx.oncomplete = () => resolve(result);
        tx.onerror = () => reject(tx.error);
    });
};

const enqueueRequest = (payload) => withStore('readwrite', (store) => store.add(payload));

const getQueuedRequests = () => withStore('readonly', (store) => new Promise((resolve) => {
    const items = [];
    const cursorRequest = store.openCursor();
    cursorRequest.onsuccess = () => {
        const cursor = cursorRequest.result;
        if (cursor) {
            items.push(cursor.value);
            cursor.continue();
        } else {
            resolve(items);
        }
    };
}));

const deleteQueuedRequest = (id) => withStore('readwrite', (store) => store.delete(id));

const normalizeUrl = (url) => {
    try {
        return new URL(url, window.location.origin).pathname;
    } catch {
        return url;
    }
};

const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const isOnline = () => navigator.onLine;

const showStatus = (message, type) => {
    let banner = document.getElementById('offline-status-banner');
    if (!banner) {
        banner = document.createElement('div');
        banner.id = 'offline-status-banner';
        banner.className = 'fixed top-3 right-3 z-50 px-4 py-2 rounded-md text-sm shadow';
        document.body.appendChild(banner);
    }
    banner.textContent = message;
    banner.classList.toggle('bg-green-600', type === 'success');
    banner.classList.toggle('bg-yellow-600', type === 'warning');
    banner.classList.toggle('bg-red-600', type === 'error');
    banner.classList.toggle('text-white', true);
    setTimeout(() => {
        banner?.remove();
    }, 3000);
};

const serializeForm = (form) => {
    const formData = new FormData(form);
    const data = {};
    for (const [key, value] of formData.entries()) {
        if (data[key]) {
            if (Array.isArray(data[key])) {
                data[key].push(value);
            } else {
                data[key] = [data[key], value];
            }
        } else {
            data[key] = value;
        }
    }
    return data;
};

const hasFileInput = (form) => Array.from(form.querySelectorAll('input[type="file"]')).some((input) => input.files && input.files.length > 0);

const queueFormSubmission = async (form) => {
    const data = serializeForm(form);
    data._token = data._token ?? getCsrfToken();
    const methodOverride = form.querySelector('input[name="_method"]')?.value;
    const method = (methodOverride || form.method || 'POST').toUpperCase();
    const payload = {
        url: normalizeUrl(form.action || window.location.href),
        method,
        data,
        created_at: new Date().toISOString(),
    };
    await enqueueRequest(payload);
    showStatus('Saved offline. Will sync when online.', 'warning');
};

const processQueue = async () => {
    if (!isOnline()) {
        return;
    }
    const queued = await getQueuedRequests();
    if (!queued.length) {
        return;
    }
    const response = await window.fetch('/sync/queue', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getCsrfToken(),
        },
        body: JSON.stringify({ actions: queued }),
    });

    if (response.status === 409) {
        showStatus('Sync conflict detected. Refresh and retry.', 'error');
        return;
    }

    if (!response.ok) {
        showStatus('Sync failed. Will retry.', 'error');
        return;
    }

    const result = await response.json();
    const processedIds = result?.processed_ids ?? [];
    await Promise.all(processedIds.map((id) => deleteQueuedRequest(id)));
    if (processedIds.length) {
        showStatus('Offline changes synced.', 'success');
    }
};

const bindOfflineForms = () => {
    document.querySelectorAll('form').forEach((form) => {
        if (form.dataset.offline === 'false') {
            return;
        }
        form.addEventListener('submit', async (event) => {
            const methodOverride = form.querySelector('input[name="_method"]')?.value;
            const method = (methodOverride || form.method || 'POST').toUpperCase();
            if (method === 'GET') {
                if (!isOnline()) {
                    event.preventDefault();
                    showStatus('Offline. Unable to load data.', 'warning');
                }
                return;
            }
            if (isOnline()) {
                return;
            }
            if (hasFileInput(form)) {
                event.preventDefault();
                showStatus('Offline. File uploads require connection.', 'error');
                return;
            }
            event.preventDefault();
            await queueFormSubmission(form);
        });
    });
};

window.addEventListener('online', () => {
    showStatus('Back online. Syncing changes.', 'success');
    processQueue();
});

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js');
    });
}

window.addEventListener('offline', () => {
    showStatus('Offline mode enabled.', 'warning');
});

document.addEventListener('DOMContentLoaded', () => {
    bindOfflineForms();
    processQueue();
});
