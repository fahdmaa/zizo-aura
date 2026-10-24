const eventNames = new Set(['page_view', 'store_click', 'whatsapp_order_click', 'web_checkout_click']);

export function initStoreAnalytics({ document: doc, window: win, fetch: fetcher }) {
    if (!doc || !win || typeof fetcher !== 'function') return () => {};

    const send = event => {
        try {
            const request = fetcher('/api/analytics/events', {
                method: 'POST',
                credentials: 'same-origin',
                keepalive: true,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': doc.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ event, path: win.location.pathname }),
            });
            request?.catch?.(() => {});
        } catch {}
    };

    const path = win.location.pathname;
    if (path !== '/admin' && !path.startsWith('/admin/')) send('page_view');

    const onClick = event => {
        const target = event.target?.closest?.('a,button,[role="button"],input[type="button"],input[type="submit"]');
        if (!target) return;
        send('store_click');
        const name = target?.getAttribute('data-store-analytics');
        if (eventNames.has(name) && name !== 'store_click') send(name);
    };

    doc.addEventListener('click', onClick, true);
    return () => doc.removeEventListener('click', onClick, true);
}
