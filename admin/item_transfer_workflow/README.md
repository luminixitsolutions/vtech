# Item Transfer Workflow

Workflow **after** Admin assigns items to Dispatch Office (existing `distribute-item-store-executive-2.php`).

## Flow
1. **Admin** assigns items to **Dispatch Office** (existing).
2. **Dispatch Officer** transfers items to a **Store** (this module).
3. **Store** can transfer items to **another Store** (this module).

## Setup
1. Run `item_transfer_workflow_tables.sql` in your database.
2. Add **Option 72** to roles that should see the "Item Transfer Workflow" menu (e.g. Dispatch Officer, Store Incharge, Admin).

## Pages
- **Dispatch: Transfer to Store** – Roll 26 or Admin.
- **View Dispatch to Store Transfers** – Roll 26 or Admin.
- **Stock Location Report** (`stock-location-report.php`) – snapshot of qty/serial by store, dispatch officer, and reserved transfer lines (Admin, dispatch, store roles / options 72, 165, 166).
- **Store: Transfer to Another Store** – Roll 27 or Admin (submit button at top and bottom).
- **View Store to Store Transfers** – Roll 27 or Admin.

## Stock
- Dispatch Officer stock = `tbl_distibute_item_details2` (StoreExeId) minus rows in `tbl_dispatch_to_store_transfer_details`.
- Store stock = `tbl_distibute_item_details` minus `tbl_distibute_item_details2` for that BranchId.
