# Vendor planning costs

New plans store itemized vendor rates, quantities and subtotals in their snapshot. AI chooses services; PHP resolves prices from the saved vendor data. Missing prices or required quantities are shown as **Quote required** and excluded from the total. Rates are not live quotes or availability guarantees.

## Entering detailed rates

In the vendor's **Dynamic attributes** tab, add one attribute for each billable service (for example mandap, entrance flowers, seating decoration and lighting).

- A currency attribute uses its value as the service price.
- The optional **Planning cost** fields attach a rate to any attribute. Choose fixed service, guest, unit, hour or day. Guest quantities use the event guest count. Unit/hour/day rates require an explicit quantity.
- A generic Price/Package Price is treated as an alternative to detailed components, not added on top of them. Do not enter the same charge twice. Deposits, budgets, discounts, taxes and min/max attributes are not additive service costs.
- For a dish, use its exact menu-item name and a per-guest rate. This overrides the shared question-menu price. Otherwise the saved question-menu rate remains labelled as a configured menu rate.
- Food packages and extras use the selected vendor's saved minimum rate, explicitly labelled as a starting rate with its saved range. An extra with the same name as an included package item is not billed again.

The summary and PDF show vendor names, line items, rate × quantity, subtotals and missing quotations. Saved budget alternatives retain vendor rates and show a separate budget target; they do not claim automatic vendor discounts.

## Existing plans and deployment

Existing plans are historical snapshots. Their totals are not silently rewritten. Regenerate or edit a plan to apply the new costing engine. Old plans without itemized rates show a saved service estimate and an explanation.

No schema migration is needed for these changes. Deploy the PHP/Blade files and rebuilt `public/build` assets together. Run `npm run build` after frontend changes and `php artisan test --compact` for regression checks.

Vendor record replacement requires verified business/service information and rates. Do not use example data as a real business quotation.
