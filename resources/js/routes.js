const routes = {
    "debugbar.openhandler": {
        "uri": "_debugbar\/open"
    },
    "debugbar.clockwork": {
        "uri": "_debugbar\/clockwork\/{id}"
    },
    "debugbar.telescope": {
        "uri": "_debugbar\/telescope\/{id}"
    },
    "debugbar.assets.css": {
        "uri": "_debugbar\/assets\/stylesheets"
    },
    "debugbar.assets.js": {
        "uri": "_debugbar\/assets\/javascript"
    },
    "debugbar.cache.delete": {
        "uri": "_debugbar\/cache\/{key}\/{tags?}"
    },
    "adminlte.darkmode.toggle": {
        "uri": "adminlte\/darkmode\/toggle"
    },
    "telescope": {
        "uri": "telescope\/{view?}"
    },
    "web.": {
        "uri": "web\/profile\/create-shipping-address"
    },
    "web.sign_in.index": {
        "uri": "web\/sign_in"
    },
    "web.sign_in.store": {
        "uri": "web\/sign_in"
    },
    "web.forget_password.index": {
        "uri": "web\/forget_password"
    },
    "web.forget_password.store": {
        "uri": "web\/forget_password"
    },
    "web.forget_password.reset.index": {
        "uri": "web\/forget_password\/reset"
    },
    "web.forget_password.reset.store": {
        "uri": "web\/forget_password\/reset"
    },
    "web.sign_up.index": {
        "uri": "web\/sign_up"
    },
    "web.sign_up.store": {
        "uri": "web\/sign_up"
    },
    "web.sign_up.verify": {
        "uri": "web\/sign_up\/verify"
    },
    "web.cart.index": {
        "uri": "web\/cart"
    },
    "web.cart.redirect": {
        "uri": "web\/cart\/redirect"
    },
    "web.index": {
        "uri": "web"
    },
    "web.categories.show": {
        "uri": "web\/categories\/{category}"
    },
    "web.items.search": {
        "uri": "web\/items\/search\/results"
    },
    "web.items.index": {
        "uri": "web\/items"
    },
    "web.items.show": {
        "uri": "web\/items\/{itemSlug}"
    },
    "web.create-shipping-address": {
        "uri": "web\/profile\/create-shipping-address"
    },
    "web.update-information": {
        "uri": "web\/profile\/update-information"
    },
    "web.update-password": {
        "uri": "web\/profile\/update-password"
    },
    "web.update-phone-number": {
        "uri": "web\/profile\/update-phone-number"
    },
    "api.web.items.index": {
        "uri": "api\/web\/items"
    },
    "api.web.items.using_filters": {
        "uri": "api\/web\/items\/using_filters"
    },
    "api.web.": {
        "uri": "api\/web\/shipping_methods"
    },
    "api.web.categories.index": {
        "uri": "api\/web\/categories"
    },
    "api.web.categories.subcategories": {
        "uri": "api\/web\/categories\/{category}\/subcategories"
    },
    "api.web.cart.get_items_details": {
        "uri": "api\/web\/cart\/get_items_details"
    },
    "api.web.filters.get_filters": {
        "uri": "api\/web\/filters"
    },
    "api.web.orders.index": {
        "uri": "api\/web\/orders"
    },
    "api.web.orders.create": {
        "uri": "api\/web\/orders\/create"
    },
    "api.web.orders.store": {
        "uri": "api\/web\/orders"
    },
    "api.web.orders.show": {
        "uri": "api\/web\/orders\/{order}"
    },
    "api.web.orders.edit": {
        "uri": "api\/web\/orders\/{order}\/edit"
    },
    "api.web.orders.update": {
        "uri": "api\/web\/orders\/{order}"
    },
    "api.web.orders.destroy": {
        "uri": "api\/web\/orders\/{order}"
    },
    "api.web.payment_accounts.index": {
        "uri": "api\/web\/{user}\/payment_accounts"
    },
    "api.web.payment_accounts.create": {
        "uri": "api\/web\/{user}\/payment_accounts\/create"
    },
    "api.web.payment_accounts.store": {
        "uri": "api\/web\/{user}\/payment_accounts"
    },
    "api.web.payment_accounts.show": {
        "uri": "api\/web\/{user}\/payment_accounts\/{payment_account}"
    },
    "api.web.payment_accounts.edit": {
        "uri": "api\/web\/{user}\/payment_accounts\/{payment_account}\/edit"
    },
    "api.web.payment_accounts.update": {
        "uri": "api\/web\/{user}\/payment_accounts\/{payment_account}"
    },
    "api.web.payment_accounts.destroy": {
        "uri": "api\/web\/{user}\/payment_accounts\/{payment_account}"
    },
    "login": {
        "uri": "login"
    },
    "logout": {
        "uri": "logout"
    },
    "password.confirm": {
        "uri": "password\/confirm"
    },
    "printer": {
        "uri": "accounting\/printer\/print_receipt\/{sale}"
    },
    "dashboard.index": {
        "uri": "dashboard"
    },
    "sales.index": {
        "uri": "sales"
    },
    "sales.create": {
        "uri": "sales\/create"
    },
    "sales.store": {
        "uri": "sales"
    },
    "sales.show": {
        "uri": "sales\/{sale}"
    },
    "sales.edit": {
        "uri": "sales\/{sale}\/edit"
    },
    "sales.update": {
        "uri": "sales\/{sale}"
    },
    "sales.destroy": {
        "uri": "sales\/{sale}"
    },
    "sales.drafts.index": {
        "uri": "sales\/drafts\/index"
    },
    "sales.drafts.create": {
        "uri": "sales\/drafts\/create"
    },
    "sales.drafts.create.service": {
        "uri": "sales\/drafts\/create_service"
    },
    "sales.drafts.clone": {
        "uri": "sales\/drafts\/{sale}\/clone"
    },
    "sales.drafts.to_invoice": {
        "uri": "sales\/drafts\/{sale}\/to_invoice"
    },
    "accounts.index": {
        "uri": "accounts"
    },
    "accounts.create": {
        "uri": "accounts\/create"
    },
    "accounts.store": {
        "uri": "accounts"
    },
    "accounts.show": {
        "uri": "accounts\/{account}"
    },
    "accounts.edit": {
        "uri": "accounts\/{account}\/edit"
    },
    "accounts.update": {
        "uri": "accounts\/{account}"
    },
    "accounts.destroy": {
        "uri": "accounts\/{account}"
    },
    "delivery_men.index": {
        "uri": "delivery_men"
    },
    "delivery_men.create": {
        "uri": "delivery_men\/create"
    },
    "delivery_men.store": {
        "uri": "delivery_men"
    },
    "delivery_men.show": {
        "uri": "delivery_men\/{delivery_man}"
    },
    "delivery_men.edit": {
        "uri": "delivery_men\/{delivery_man}\/edit"
    },
    "delivery_men.update": {
        "uri": "delivery_men\/{delivery_man}"
    },
    "delivery_men.destroy": {
        "uri": "delivery_men\/{delivery_man}"
    },
    "accounts.reports.index": {
        "uri": "accounts\/reports\/index"
    },
    "accounts.show.item": {
        "uri": "accounts\/{account}\/stock\/{item}"
    },
    "accounts.show.identity": {
        "uri": "accounts\/{account}\/identity\/{identity}"
    },
    "vouchers.index": {
        "uri": "vouchers"
    },
    "vouchers.create": {
        "uri": "vouchers\/create"
    },
    "vouchers.store": {
        "uri": "vouchers"
    },
    "vouchers.show": {
        "uri": "vouchers\/{voucher}"
    },
    "vouchers.edit": {
        "uri": "vouchers\/{voucher}\/edit"
    },
    "vouchers.update": {
        "uri": "vouchers\/{voucher}"
    },
    "vouchers.destroy": {
        "uri": "vouchers\/{voucher}"
    },
    "vouchers.create.supplier": {
        "uri": "vouchers\/manual\/create-supplier"
    },
    "entities.index": {
        "uri": "entities"
    },
    "entities.create": {
        "uri": "entities\/create"
    },
    "entities.store": {
        "uri": "entities"
    },
    "entities.show": {
        "uri": "entities\/{entity}"
    },
    "entities.edit": {
        "uri": "entities\/{entity}\/edit"
    },
    "entities.update": {
        "uri": "entities\/{entity}"
    },
    "entities.destroy": {
        "uri": "entities\/{entity}"
    },
    "entities.user": {
        "uri": "entities\/user\/{account}\/{user}"
    },
    "financial_statements.index": {
        "uri": "financial_statements"
    },
    "financial_statements.trial_balance": {
        "uri": "financial_statements\/trial_balance"
    },
    "items.index": {
        "uri": "items"
    },
    "items.create": {
        "uri": "items\/create"
    },
    "items.store": {
        "uri": "items"
    },
    "items.show": {
        "uri": "items\/{item}"
    },
    "items.edit": {
        "uri": "items\/{item}\/edit"
    },
    "items.update": {
        "uri": "items\/{item}"
    },
    "items.destroy": {
        "uri": "items\/{item}"
    },
    "items.transactions": {
        "uri": "items\/{item}\/transactions"
    },
    "items.serials": {
        "uri": "items\/{item}\/view_serials"
    },
    "items.clone": {
        "uri": "items\/{item}\/clone"
    },
    "purchases.index": {
        "uri": "purchases"
    },
    "purchases.create": {
        "uri": "purchases\/create"
    },
    "purchases.store": {
        "uri": "purchases"
    },
    "purchases.show": {
        "uri": "purchases\/{purchase}"
    },
    "purchases.edit": {
        "uri": "purchases\/{purchase}\/edit"
    },
    "purchases.update": {
        "uri": "purchases\/{purchase}"
    },
    "purchases.destroy": {
        "uri": "purchases\/{purchase}"
    },
    "purchases.drafts": {
        "uri": "purchases\/view\/drafts"
    },
    "inventory.index": {
        "uri": "inventory"
    },
    "inventory.create": {
        "uri": "inventory\/create"
    },
    "inventory.adjustments.index": {
        "uri": "inventory\/adjustments"
    },
    "inventory.adjustments.create": {
        "uri": "inventory\/adjustments\/create"
    },
    "daily.index": {
        "uri": "daily"
    },
    "daily.reseller.closing_accounts.index": {
        "uri": "daily\/reseller\/closing_accounts"
    },
    "daily.reseller.closing_accounts.create": {
        "uri": "daily\/reseller\/closing_accounts\/create"
    },
    "daily.reseller.accounts_transactions.index": {
        "uri": "daily\/reseller\/accounts_transactions"
    },
    "daily.reseller.accounts_transactions.create": {
        "uri": "daily\/reseller\/accounts_transactions\/create"
    },
    "daily.reseller.accounts_transactions.confirm": {
        "uri": "daily\/reseller\/accounts_transactions\/{transaction}\/delete_transaction"
    },
    "filterindex": {
        "uri": "filters"
    },
    "filtercreate": {
        "uri": "filters\/create"
    },
    "filtershow": {
        "uri": "filters\/{filter}"
    },
    "filteredit": {
        "uri": "filters\/{filter}\/edit"
    },
    "accounting.vouchers.datatable": {
        "uri": "accounting\/datatable\/vouchers"
    },
    "accounting.filters.datatable": {
        "uri": "accounting\/datatable\/filters"
    },
    "accounting.filter.values.datatable": {
        "uri": "accounting\/datatable\/{filter}\/filter_values"
    },
    "accounting.identities.datatable": {
        "uri": "accounting\/datatable\/identities"
    },
    "accounting.managers.datatable": {
        "uri": "accounting\/datatable\/managers"
    },
    "accounting.beginning.datatable": {
        "uri": "accounting\/datatable\/beginning_inventories"
    },
    "accounting.items.index": {
        "uri": "accounting\/items"
    },
    "accounting.items.create": {
        "uri": "accounting\/items\/create"
    },
    "accounting.items.store": {
        "uri": "accounting\/items"
    },
    "accounting.items.show": {
        "uri": "accounting\/items\/{item}"
    },
    "accounting.items.edit": {
        "uri": "accounting\/items\/{item}\/edit"
    },
    "accounting.items.update": {
        "uri": "accounting\/items\/{item}"
    },
    "accounting.items.destroy": {
        "uri": "accounting\/items\/{item}"
    },
    "accounting.kits.index": {
        "uri": "accounting\/kits"
    },
    "accounting.kits.create": {
        "uri": "accounting\/kits\/create"
    },
    "accounting.kits.store": {
        "uri": "accounting\/kits"
    },
    "accounting.kits.show": {
        "uri": "accounting\/kits\/{kit}"
    },
    "accounting.kits.edit": {
        "uri": "accounting\/kits\/{kit}\/edit"
    },
    "accounting.kits.update": {
        "uri": "accounting\/kits\/{kit}"
    },
    "accounting.kits.destroy": {
        "uri": "accounting\/kits\/{kit}"
    },
    "accounting.warranty_subscriptions.index": {
        "uri": "accounting\/warranty_subscriptions"
    },
    "accounting.warranty_subscriptions.create": {
        "uri": "accounting\/warranty_subscriptions\/create"
    },
    "accounting.warranty_subscriptions.store": {
        "uri": "accounting\/warranty_subscriptions"
    },
    "accounting.warranty_subscriptions.show": {
        "uri": "accounting\/warranty_subscriptions\/{warranty_subscription}"
    },
    "accounting.warranty_subscriptions.edit": {
        "uri": "accounting\/warranty_subscriptions\/{warranty_subscription}\/edit"
    },
    "accounting.warranty_subscriptions.update": {
        "uri": "accounting\/warranty_subscriptions\/{warranty_subscription}"
    },
    "accounting.warranty_subscriptions.destroy": {
        "uri": "accounting\/warranty_subscriptions\/{warranty_subscription}"
    },
    "accounting.categories.index": {
        "uri": "accounting\/categories"
    },
    "accounting.categories.create": {
        "uri": "accounting\/categories\/create"
    },
    "accounting.categories.store": {
        "uri": "accounting\/categories"
    },
    "accounting.categories.show": {
        "uri": "accounting\/categories\/{category}"
    },
    "accounting.categories.edit": {
        "uri": "accounting\/categories\/{category}\/edit"
    },
    "accounting.categories.update": {
        "uri": "accounting\/categories\/{category}"
    },
    "accounting.categories.destroy": {
        "uri": "accounting\/categories\/{category}"
    },
    "accounting.filters.index": {
        "uri": "accounting\/filters"
    },
    "accounting.filters.create": {
        "uri": "accounting\/filters\/create"
    },
    "accounting.filters.store": {
        "uri": "accounting\/filters"
    },
    "accounting.filters.show": {
        "uri": "accounting\/filters\/{filter}"
    },
    "accounting.filters.edit": {
        "uri": "accounting\/filters\/{filter}\/edit"
    },
    "accounting.filters.update": {
        "uri": "accounting\/filters\/{filter}"
    },
    "accounting.filters.destroy": {
        "uri": "accounting\/filters\/{filter}"
    },
    "accounting.filter_values.index": {
        "uri": "accounting\/filter_values"
    },
    "accounting.filter_values.create": {
        "uri": "accounting\/filter_values\/create"
    },
    "accounting.filter_values.store": {
        "uri": "accounting\/filter_values"
    },
    "accounting.filter_values.show": {
        "uri": "accounting\/filter_values\/{filter_value}"
    },
    "accounting.filter_values.edit": {
        "uri": "accounting\/filter_values\/{filter_value}\/edit"
    },
    "accounting.filter_values.update": {
        "uri": "accounting\/filter_values\/{filter_value}"
    },
    "accounting.filter_values.destroy": {
        "uri": "accounting\/filter_values\/{filter_value}"
    },
    "accounting.managers.index": {
        "uri": "accounting\/managers"
    },
    "accounting.managers.create": {
        "uri": "accounting\/managers\/create"
    },
    "accounting.managers.store": {
        "uri": "accounting\/managers"
    },
    "accounting.managers.show": {
        "uri": "accounting\/managers\/{manager}"
    },
    "accounting.managers.edit": {
        "uri": "accounting\/managers\/{manager}\/edit"
    },
    "accounting.managers.update": {
        "uri": "accounting\/managers\/{manager}"
    },
    "accounting.managers.destroy": {
        "uri": "accounting\/managers\/{manager}"
    },
    "accounting.identities.index": {
        "uri": "accounting\/identities"
    },
    "accounting.identities.create": {
        "uri": "accounting\/identities\/create"
    },
    "accounting.identities.store": {
        "uri": "accounting\/identities"
    },
    "accounting.identities.show": {
        "uri": "accounting\/identities\/{identity}"
    },
    "accounting.identities.edit": {
        "uri": "accounting\/identities\/{identity}\/edit"
    },
    "accounting.identities.update": {
        "uri": "accounting\/identities\/{identity}"
    },
    "accounting.identities.destroy": {
        "uri": "accounting\/identities\/{identity}"
    },
    "accounting.items.barcode": {
        "uri": "accounting\/items\/view\/barcode"
    },
    "accounting.items.upload_attachments": {
        "uri": "accounting\/items\/{item}\/attachments"
    },
    "accounting.items.show_item_barcode": {
        "uri": "accounting\/items\/view\/show_item_barcode"
    },
    "accounting.items.serial_activities": {
        "uri": "accounting\/items\/view\/serial_activities"
    },
    "accounting.items.show_serial_activities": {
        "uri": "accounting\/items\/view\/show_serial_activities"
    },
    "accounting.items.clone": {
        "uri": "accounting\/items\/{item}\/clone"
    },
    "accounting.items.transactions": {
        "uri": "accounting\/items\/{item}\/transactions"
    },
    "accounting.items.transactions_datatable": {
        "uri": "accounting\/items\/{item}\/transactions_datatable"
    },
    "accounting.items.view_serials": {
        "uri": "accounting\/items\/{item}\/view_serials"
    },
    "accounting.items.helper.validate_barcode": {
        "uri": "accounting\/items\/helper\/validate_barcode"
    },
    "accounting.items.helper.activate_items": {
        "uri": "accounting\/items\/helper\/activate_items"
    },
    "accounting.items.helper.query_find_items": {
        "uri": "accounting\/items\/helper\/query_find_items"
    },
    "accounting.items.helper.query_validate_purchase_serial": {
        "uri": "accounting\/items\/helper\/query_validate_return_purchase_serial"
    },
    "accounting.kits.helper.get_kit_amounts": {
        "uri": "accounting\/kits\/helper\/get_kit_amounts\/{kit}"
    },
    "accounting.categories.": {
        "uri": "accounting\/categories\/{category}\/clone"
    },
    "accounting.": {
        "uri": "accounting\/roles_permissions"
    },
    "accounting.gateways.load_manager_gateway": {
        "uri": "accounting\/gateways\/get_gateways_like_to_manager_name"
    },
    "accounting.reseller_daily.account_close": {
        "uri": "accounting\/reseller_daily\/account_close"
    },
    "accounting.reseller_daily.account_close_store": {
        "uri": "accounting\/reseller_daily\/account_close"
    },
    "accounting.reseller_daily.account_close_list": {
        "uri": "accounting\/reseller_daily\/account_close_list"
    },
    "accounting.reseller_daily.transfer_list": {
        "uri": "accounting\/reseller_daily\/transfer_list"
    },
    "accounting.reseller_daily.transfer_amounts": {
        "uri": "accounting\/reseller_daily\/transfer_amounts"
    },
    "accounting.reseller_daily.transfer_amounts_store": {
        "uri": "accounting\/reseller_daily\/transfer_amounts"
    },
    "accounting.reseller_daily.confirm_transaction": {
        "uri": "accounting\/reseller_daily\/{transaction}\/confirm_transaction"
    },
    "accounting.reseller_daily.delete_transaction": {
        "uri": "accounting\/reseller_daily\/{transaction}\/delete_transaction"
    },
    "accounting.printer.": {
        "uri": "accounting\/printer\/printers"
    },
    "accounting.printer.a4": {
        "uri": "accounting\/printer\/print_a4\/{invoice}"
    },
    "accounting.printer.voucher": {
        "uri": "accounting\/printer\/voucher\/{voucher}"
    },
    "accounting.public-invoice.show": {
        "uri": "accounting\/public-invoice\/{invoicePublicIdElementsHash}"
    },
    "store.orders.index": {
        "uri": "store\/orders"
    },
    "store.orders.show": {
        "uri": "store\/orders\/{order}"
    },
    "store.orders.accept-order-as-manager": {
        "uri": "store\/orders\/{order}\/accept-order-as-manager"
    },
    "store.orders.view-payment": {
        "uri": "store\/orders\/{order}\/view-payment"
    },
    "store.orders.view-shipping": {
        "uri": "store\/orders\/{order}\/view-shipping"
    },
    "store.orders.activites": {
        "uri": "store\/orders\/{order}\/activities"
    },
    "store.orders.customer-data": {
        "uri": "store\/orders\/{order}\/customer-data"
    },
    "store.shipping.index": {
        "uri": "store\/shipping"
    },
    "store.shipping.create": {
        "uri": "store\/shipping\/create"
    },
    "store.shipping.store": {
        "uri": "store\/shipping"
    },
    "store.shipping.show": {
        "uri": "store\/shipping\/{shipping}"
    },
    "store.shipping.edit": {
        "uri": "store\/shipping\/{shipping}\/edit"
    },
    "store.shipping.update": {
        "uri": "store\/shipping\/{shipping}"
    },
    "store.shipping.destroy": {
        "uri": "store\/shipping\/{shipping}"
    },
    "store.shipping.sign-transactions-to-delivery-man": {
        "uri": "store\/shipping\/sign-transactions-to-delivery-man"
    },
    "store.shipping.activate-sign-transactions-to-delivery-man": {
        "uri": "store\/shipping\/activate-sign-transactions-to-delivery-man"
    },
    "store.shipping.delivery_men.store": {
        "uri": "store\/shipping\/{shipping}\/delivery_men"
    },
    "store.shipping.view_transactions": {
        "uri": "store\/shipping\/{shipping}\/view-transactions"
    },
    "store.shipping.fetch_transactions": {
        "uri": "store\/shipping\/{shipping}\/fetch_transactions"
    },
    "store.shipping.download": {
        "uri": "store\/shipping\/{shipping}\/{transaction}\/download"
    },
    "store.shipping.create_transaction": {
        "uri": "store\/shipping\/{shipping}\/create-transaction"
    },
    "store.shipping.create_order_transaction": {
        "uri": "store\/shipping\/{shipping}\/{order}\/create-order-transaction"
    },
    "store.shipping.store_transaction": {
        "uri": "store\/shipping\/{shipping}\/store-transaction"
    },
    "store.shipping.delivery_men.update": {
        "uri": "store\/shipping\/{shipping}\/delivery_men\/{deliveryMan}"
    },
    "api.v2.": {
        "uri": "api\/v2\/items\/search"
    },
    "api.orders.index": {
        "uri": "api\/orders"
    },
    "api.orders.create": {
        "uri": "api\/orders\/create"
    },
    "api.orders.store": {
        "uri": "api\/orders"
    },
    "api.orders.show": {
        "uri": "api\/orders\/{order}"
    },
    "api.orders.edit": {
        "uri": "api\/orders\/{order}\/edit"
    },
    "api.orders.update": {
        "uri": "api\/orders\/{order}"
    },
    "api.orders.destroy": {
        "uri": "api\/orders\/{order}"
    },
    "api.orders.": {
        "uri": "api\/orders\/{order}\/activate-sign-to-delivery-man"
    },
    "api.notifications.transactions.issued": {
        "uri": "api\/notifications\/transactions\/issued"
    },
    "api.notifications.orders.pending": {
        "uri": "api\/notifications\/orders\/pending"
    },
    "api.notifications.orders.paid": {
        "uri": "api\/notifications\/orders\/paid"
    },
    "api.vouchers.index": {
        "uri": "api\/vouchers"
    },
    "api.vouchers.create": {
        "uri": "api\/vouchers\/create"
    },
    "api.vouchers.store": {
        "uri": "api\/vouchers"
    },
    "api.vouchers.show": {
        "uri": "api\/vouchers\/{voucher}"
    },
    "api.vouchers.edit": {
        "uri": "api\/vouchers\/{voucher}\/edit"
    },
    "api.vouchers.update": {
        "uri": "api\/vouchers\/{voucher}"
    },
    "api.vouchers.destroy": {
        "uri": "api\/vouchers\/{voucher}"
    },
    "api.sales.index": {
        "uri": "api\/sales"
    },
    "api.sales.create": {
        "uri": "api\/sales\/create"
    },
    "api.sales.store": {
        "uri": "api\/sales"
    },
    "api.sales.show": {
        "uri": "api\/sales\/{sale}"
    },
    "api.sales.edit": {
        "uri": "api\/sales\/{sale}\/edit"
    },
    "api.sales.store.return": {
        "uri": "api\/sales\/{sale}"
    },
    "api.sales.destroy": {
        "uri": "api\/sales\/{sale}"
    },
    "api.sales.store.draft": {
        "uri": "api\/sales\/draft"
    },
    "api.accounts.index": {
        "uri": "api\/accounts"
    },
    "api.accounts.store": {
        "uri": "api\/accounts"
    },
    "api.accounts.show": {
        "uri": "api\/accounts\/{account}"
    },
    "api.accounts.update": {
        "uri": "api\/accounts\/{account}"
    },
    "api.accounts.destroy": {
        "uri": "api\/accounts\/{account}"
    },
    "api.accounts.report": {
        "uri": "api\/accounts\/{account}\/reports"
    },
    "api.accounts.transactions": {
        "uri": "api\/accounts\/{account}\/transactions"
    },
    "api.financial_statements.trial_balance": {
        "uri": "api\/financial_statements\/trial_balance"
    },
    "api.entities.index": {
        "uri": "api\/entities"
    },
    "api.entities.create": {
        "uri": "api\/entities\/create"
    },
    "api.entities.store": {
        "uri": "api\/entities"
    },
    "api.entities.show": {
        "uri": "api\/entities\/{entity}"
    },
    "api.entities.edit": {
        "uri": "api\/entities\/{entity}\/edit"
    },
    "api.entities.update": {
        "uri": "api\/entities\/{entity}"
    },
    "api.entities.destroy": {
        "uri": "api\/entities\/{entity}"
    },
    "api.entities.transactions": {
        "uri": "api\/entities\/{account}\/transactions"
    },
    "api.items.index": {
        "uri": "api\/items"
    },
    "api.items.create": {
        "uri": "api\/items\/create"
    },
    "api.items.store": {
        "uri": "api\/items"
    },
    "api.items.show": {
        "uri": "api\/items\/{item}"
    },
    "api.items.edit": {
        "uri": "api\/items\/{item}\/edit"
    },
    "api.items.update": {
        "uri": "api\/items\/{item}"
    },
    "api.items.destroy": {
        "uri": "api\/items\/{item}"
    },
    "api.items.add_images": {
        "uri": "api\/items\/add_images"
    },
    "api.items.validations.sales_serial": {
        "uri": "api\/items\/validations\/sales_serial"
    },
    "api.items.validations.return_sales_serial": {
        "uri": "api\/items\/validations\/return_sales_serial"
    },
    "api.items.validations.return_purchases_serial": {
        "uri": "api\/items\/validations\/return_purchases_serial"
    },
    "api.items.validations.purchases_serial": {
        "uri": "api\/items\/validations\/purchases_serial"
    },
    "api.items.validations.unique_barcode": {
        "uri": "api\/items\/validations\/unique_barcode"
    },
    "api.items.query.search": {
        "uri": "api\/items\/query\/search"
    },
    "api.items.transactions": {
        "uri": "api\/items\/{item}\/transactions"
    },
    "api.purchases.index": {
        "uri": "api\/purchases"
    },
    "api.purchases.create": {
        "uri": "api\/purchases\/create"
    },
    "api.purchases.store": {
        "uri": "api\/purchases"
    },
    "api.purchases.show": {
        "uri": "api\/purchases\/{purchase}"
    },
    "api.purchases.edit": {
        "uri": "api\/purchases\/{purchase}\/edit"
    },
    "api.purchases.store.return": {
        "uri": "api\/purchases\/{purchase}"
    },
    "api.purchases.destroy": {
        "uri": "api\/purchases\/{purchase}"
    },
    "api.purchases.pending_dropbox_purchases": {
        "uri": "api\/purchases\/fetch\/pending_dropbox_purchases"
    },
    "api.purchases.store.draft": {
        "uri": "api\/purchases\/draft"
    },
    "api.inventory.beginning.store": {
        "uri": "api\/inventory\/beginning"
    },
    "api.inventory.adjustments.store": {
        "uri": "api\/inventory\/adjustments"
    },
    "api.inventory.adjustments.index": {
        "uri": "api\/inventory\/adjustments"
    },
    "api.daily.reseller.closing_account.store": {
        "uri": "api\/daily\/reseller\/closing_accounts"
    },
    "api.daily.reseller.accounts_transactions.store": {
        "uri": "api\/daily\/reseller\/accounts_transactions"
    },
    "api.items.serials": {
        "uri": "api\/items\/{item}\/view_serials"
    },
    "api.items.clone": {
        "uri": "api\/items\/{item}\/clone"
    },
    "api.filters.store": {
        "uri": "api\/filters"
    },
    "api.filters.update": {
        "uri": "api\/filters\/{filter}\/update"
    }
};

const route = (routeName, params = [], absolute = true) => {
  const _route = routes[routeName];
  if (_route == null) throw "Requested route doesn't exist";

  let uri = _route.uri;

  const matches = uri.match(/{[\w]+\??}/g) || [];
  const optionals = uri.match(/{[\w]+\?}/g) || [];

  const requiredParametersCount = matches.length - optionals.length;

  if (params instanceof Array) {
    if (params.length < requiredParametersCount) throw "Missing parameters";

    for (let i = 0; i < requiredParametersCount; i++)
      uri = uri.replace(/{[\w]+\??}/, params.shift());

    for (let i = 0; i < params.length; i++)
      uri += (i ? "&" : "?") + params[i] + "=" + params[i];
  } else if (params instanceof Object) {
    let extraParams = matches.reduce((ac, match) => {
      let key = match.substring(1, match.length - 1);
      let isOptional = key.endsWith("?");
      if (params.hasOwnProperty(key.replace("?", ""))) {
        uri = uri.replace(new RegExp(match.replace("?", "\\?"), "g"), params[key.replace("?", "")]);
        delete ac[key.replace("?", "")];
      } else if (isOptional) {
          uri = uri.replace("/" + new RegExp(match, "g"), "");
      }
      return ac;
    }, params);

    Object.keys(extraParams).forEach((key, i) => {
      uri += (i ? "&" : "?") + key + "=" + extraParams[key];
    });
  }

  if (optionals.length > 0) {
    for (let i in optionals) {
      uri = uri.replace("/" + optionals[i], "");
    }
  }

  if (uri.includes("}")) throw "Missing parameters";

  if (absolute && process.env.MIX_APP_URL)
    return process.env.MIX_APP_URL + "/" + uri;
  return "/" + uri;
};

export { route };
