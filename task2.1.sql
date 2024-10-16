SELECT price
FROM price_history
WHERE product_id = 1 AND created_at <= '2024-03-05'
ORDER BY created_at DESC
LIMIT 1;