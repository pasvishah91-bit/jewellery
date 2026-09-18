# TODO — Jewellery Project Audit & Fixes

## Previous Tasks (Completed)
- [x] 1. Create `track_order.php` — order tracking page (Order ID + Phone se track, visual progress timeline)
- [x] 2. Create `invoice.php` — printable bill/invoice page (customer details, items, GST, total, print button)
- [x] 3. Modify `order.php` — add visual tracking progress bar in each order card + "Download Bill" button + Track link + Cancel/Return buttons
- [x] 4. Modify `footer.php` — point "Track Order" link to `track_order.php`
- [x] 5. Update `db_config.php` — add return_requested/returned statuses, cancel_reason/return_reason columns, order_returns table
- [x] 6. Create `order_actions.php` — handle cancel/return actions
- [x] 7. Update `admin/orders.php` — add return approve/reject buttons + returned status option
- [x] 8. Update `admin/style.css` — add return_requested/returned status badge styles
- [x] 9. Update `invoice.php` — add return status color handling
- [x] 10. Test — place an order and verify tracking + bill download works

## Audit & Fix Tasks (Completed)
- [x] 1. Fix `invoice.php` — GST double-charge bug: compute embedded CGST/SGST from inclusive subtotal, grand total = subtotal (no extra 18%)
- [x] 2. Fix `index.php` — remove duplicate nested `.big-card` div
- [x] 3. Fix `order_actions.php` — use `$ret_stmt->error` instead of `$conn->error` for duplicate return detection
- [x] 4. Harden `products_data.php` — guard static product array merges against NULL (prevents `array_merge()` fatal)
- [x] 5. Delete debug/temp files — `_audit_test.php`, `_render_add.html`, `_render_edit.html`, `admin_cookies.txt`
- [x] 6. Re-lint all PHP files + HTTP smoke test to confirm everything works

