# Rooftop Item Transfer Workflow

Same workflow as admin `item_transfer_workflow`, using **rooftop** stock tables.

## Flow
1. **Admin** assigns items to **Dispatch Office** (`distribute-item-store-executive-2.php`).
2. **Dispatch Officer** transfers items to a **Store** (this module).
3. **Store** can transfer items to **another Store** (this module).

## Setup
1. Run `sql/rooftop_item_transfer_workflow_tables.sql` or `php migrations/create_rooftop_item_transfer_tables.php`.
2. Grant menu options **165** (dispatch→store), **166** (store→store), **72** (legacy), **183** (serial location report) as needed.

## Pages
- **Dispatch: Transfer to Store** — Roll 26 or Admin / option 165.
- **View Dispatch to Store Transfers**
- **Stock Location Report** — qty/serial snapshot (`stock-location-report.php`).
- **Serial No — Location Report** — `report_management/serial-location-report.php`.
- **Store: Transfer to Another Store** — Roll 27 or Admin / option 166.
- **View Store to Store Transfers**

## Stock tables
- Dispatch officer: `tbl_rooftop_distibute_item_details2` minus `tbl_rooftop_dispatch_to_store_transfer_details`.
- Store: `tbl_rooftop_distibute_item_details` minus `tbl_rooftop_distibute_item_details2` for that branch.
- Branches: `tbl_rooftop_branch`.
