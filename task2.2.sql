SELECT product_id, price
FROM price_history AS ph
WHERE created_at = (SELECT MAX(created_at) FROM price_history WHERE product_id = ph.product_id AND created_at <= '2024-03-05')
  AND product_id IN (1, 2);