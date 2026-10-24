import test from 'node:test';
import assert from 'node:assert/strict';
import { initStoreAnalytics } from '../../resources/js/store-analytics.js';

test('tracks page views, store clicks, and both order paths without blocking navigation', () => {
    const handlers = new Map();
    const requests = [];
    const document = {
        querySelector: () => ({ content: 'csrf-token' }),
        addEventListener: (name, handler) => handlers.set(name, handler),
        removeEventListener: () => {},
    };
    const fetch = (url, options) => { requests.push({ url, options }); return Promise.resolve(); };

    initStoreAnalytics({ document, window: { location: { pathname: '/boutique' } }, fetch });
    for (const event of ['whatsapp_order_click', 'web_checkout_click']) {
        const button = {
            closest: selector => selector === 'a,button,[role="button"],input[type="button"],input[type="submit"]' ? button : null,
            getAttribute: name => name === 'data-store-analytics' ? event : null,
        };
        handlers.get('click')({ target: button });
    }

    assert.deepEqual(requests.map(({ options }) => JSON.parse(options.body).event), [
        'page_view', 'store_click', 'whatsapp_order_click', 'store_click', 'web_checkout_click',
    ]);
    assert.ok(requests.every(({ url, options }) => url === '/api/analytics/events'
        && options.method === 'POST'
        && options.keepalive === true
        && options.credentials === 'same-origin'
        && options.headers['X-CSRF-TOKEN'] === 'csrf-token'
        && JSON.parse(options.body).path === '/boutique'));
});
