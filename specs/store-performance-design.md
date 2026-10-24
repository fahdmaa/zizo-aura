# Store Performance

## Requirements

- While an admin is viewing the admin app, when they open **Performance**, the system shall show store activity and order totals for a selectable period.
- While a shopper visits a public page, when the page loads, the system shall record one page view and count the visitor once per session in the selected period.
- While a shopper clicks a public link or button, the system shall record one store click without retaining the target URL or label.
- While a shopper chooses an order path, when they click the WhatsApp order link or online checkout button, the system shall record the corresponding choice without delaying navigation.
- While events are collected, the system shall avoid persisting IP addresses, user agents, query strings, or customer details.
- While a date range is selected, GMV shall sum non-cancelled order totals created during that period; orders shall count all orders created during that period.

## Architecture

### Frontend

- Add a Performance item to the admin navigation and a responsive analytics view with a default 30-day range and 7/30/90-day choices.
- Show estimated GMV, order count, page views, unique session visitors, and unique sessions clicking the WhatsApp or online checkout paths.
- Show a daily activity chart and an empty-state note that visitor and click history begins when tracking is enabled.
- Record a public page view and the two order-choice clicks through a same-origin, keepalive event request.

### Backend

- Add `store_analytics_events` with an allow-listed event type, a keyed hash of the Laravel session ID, a sanitized path, and a timestamp. Do not store raw identifiers or request metadata.
- Add a public, throttled `POST /api/analytics/events` endpoint. Accept only known event names and a bounded path; ignore admin traffic.
- Add an admin-authenticated `GET /api/admin/analytics?days=7|30|90` endpoint. Aggregate daily views, distinct visitors, distinct sessions by order choice, and order totals/counts using the selected range.
- Keep the existing dashboard API unchanged. The new endpoint serves the Performance view.

### Security and privacy

- Apply the existing CSRF/session protections and a dedicated rate limit to event ingestion.
- Validate the event type against a fixed allow-list and normalize the path to a relative pathname without query data.
- Derive the visitor key server-side with HMAC using the application key and session ID. Never accept a visitor ID from the browser.
- Protect analytics reports with the existing `admin.auth` middleware and return aggregate values only.

## Metric definitions

- **GMV estimé:** sum of `orders.total` for orders not marked `cancelled`; this is order value, not confirmed cash received.
- **Commandes:** all orders created in the period, including cancelled orders.
- **Pages vues:** recorded public page loads.
- **Clics boutique:** all clicks on public links and buttons, counted as click events.
- **Visiteurs:** distinct session hashes with a recorded page view in the period.
- **Choix WhatsApp / commande web:** distinct session hashes that clicked each order path in the period. These measure intent, not completed WhatsApp sales.
- Visitor and click metrics start at activation; there is no existing first-party historical traffic data to backfill.

## Implementation plan

- [x] Confirm HTTP seams for endpoint, authenticated report, and browser event wiring.
- [x] Add behavioral tests for the approved seams.
- [x] Implement event storage, validation, aggregation, and throttling.
- [x] Add navigation, period controls, metric cards, chart, and loading/error/empty states.
- [x] Wire page views and order-path clicks to the event endpoint.
- [x] Build assets and run the approved test suite.
