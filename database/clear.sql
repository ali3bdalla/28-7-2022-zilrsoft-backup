TRUNCATE `invoices`;
TRUNCATE `invoice_items`;
TRUNCATE `invoice_payments`;
TRUNCATE `item_expenses`;
TRUNCATE `item_histories`;
TRUNCATE `item_serials`;
TRUNCATE `kit_data`;
TRUNCATE `kit_items`;
TRUNCATE `purchase_invoices`;
TRUNCATE `sale_invoices`;
TRUNCATE `serial_histories`;

UPDATE `items` SET items.available_qty = 0;
UPDATE `items` SET items.cost = 0;

UPDATE items SET items.creator_id = 1;
UPDATE categories SET categories.creator_id = 1;
UPDATE category_filters SET category_filters.creator_id = 1;
UPDATE item_serials SET item_serials.creator_id = 1;
UPDATE filters SET filters.creator_id = 1;
UPDATE filter_values SET filter_values.creator_id = 1;
UPDATE kit_items SET kit_items.creator_id = 1;
