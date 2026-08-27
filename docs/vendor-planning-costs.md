# Vendor planning costs

New plans store itemized vendor rates, quantities and subtotals in their snapshot. AI chooses services; PHP resolves prices from the saved vendor data. Missing prices or required quantities are shown as **Quote required** and excluded from the total. Rates are not live quotes or availability guarantees.

## Entering detailed rates

In the vendor's **Dynamic attributes** tab, add one attribute for each billable service (for example mandap, entrance flowers, seating decoration and lighting).

- A currency attribute uses its value as the service price.
- The optional **Planning cost** fields attach a rate to any attribute. Choose fixed service, guest, unit, hour or day. Guest quantities use the event guest count. Unit/hour/day rates require an explicit quantity.
- A generic Price/Package Price is treated as an alternative to detailed components, not added on top of them. Do not enter the same charge twice. Deposits, budgets, discounts, taxes and min/max attributes are not additive service costs.
- For a dish, use its exact menu-item name and a per-guest rate. This overrides the shared question-menu price. Otherwise the saved question-menu rate remains labelled as a configured menu rate.
- Food packages and extras use the selected vendor's saved minimum rate, explicitly labelled as a starting rate with its saved range. An extra with the same name as an included package item is not billed again.

The summary and PDF show vendor names, line items, rate × quantity, subtotals and missing quotations. New suggestions swap one provider for a comparable vendor and use that replacement's saved rates. Service items, units and quantities must match. They do not manufacture percentage discounts or infer better quality from a higher price. Unchanged services retain their original saved prices.

Up to three lower-cost and three higher-cost alternatives are saved, with duplicate totals omitted. Known availability conflicts, incomplete prices and incompatible service items are excluded. If no suitable alternative exists, fewer cards (or an explicit empty state) are shown. The summary's **Refresh priced alternatives** action rebuilds suggestions from current vendor records without changing the original plan. Legacy duplicate budget-target cards are hidden; legacy aggregate-only plans require regeneration before meaningful comparison.

## Existing plans and deployment

Existing plans are historical snapshots. Their totals are not silently rewritten. Regenerate or edit a plan to apply the new costing engine. Old plans without itemized rates show a saved service estimate and an explanation.

No schema migration is needed for these changes. Deploy the PHP/Blade files and rebuilt `public/build` assets together. Run `npm run build` after frontend changes and `php artisan test --compact` for regression checks.

Vendor record replacement requires verified business/service information and rates. Do not use example data as a real business quotation.

## Availability and replacement plans

Vendor Details now includes a Service availability section. Store dates as `YYYY-MM-DD`, service areas as exact city/area names, local service start/end times, and a reason for temporary unavailability. If Available dates is populated, only those dates are eligible; Unavailable/booked dates always take priority. A blank schedule is **Needs confirmation**, never a confirmed booking. Overnight hours check the event start time only; duration and final booking must be confirmed directly.

The summary checks current vendor records for activity, availability flags, date, area, capacity and start time without rewriting historical prices. Vendors with known conflicts are excluded from new plans. Missing providers show explanations and suitable alternatives. Question location labels use their configured vendor-value mapping. Existing availability/capacity/location attributes are also supported.

Select a replacement in **Vendor availability**, then **Generate new plan with selected vendors**. This requires an active subscription and ownership of the plan; it creates a separate plan, preserves the requirements and rechecks eligibility and current saved prices. Select one replacement per service category in each request. A catering replacement must offer the selected dishes/package and extras. Unconfirmed alternatives are clearly labelled and still need direct vendor confirmation. The original plan is retained.

Guest submissions now normalize the unused food-package `"null"` value, save answers in the session, and redirect to user login/registration. Validation failures display their errors and restore answers and the current question. Login/registration continues through the existing subscription flow before generation.
