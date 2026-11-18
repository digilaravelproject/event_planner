# 🏛️ ShadiSense — Dynamic Vendor Onboarding & Management Guide
### *Complete Step-by-Step Manual for Adding & Managing Vendors via Admin Portal*

> **Target URL:** `https://shaadisense.com/admin/dynamic-vendors/create`  
> **Target Audience:** Platform Admins, Vendor Managers, Event Coordinators, and Data Entry Teams  
> **No Technical Background Required!**

---

## 📑 Table of Contents
1. [Overview & How the AI Uses Vendor Data](#1-overview--how-the-ai-uses-vendor-data)
2. [Step-by-Step Tour of the 6 Tabs](#2-step-by-step-tour-of-the-6-tabs)
   - [Tab 1: Vendor Details (Identity & Category)](#tab-1-vendor-details)
   - [Tab 2: Dynamic Attributes (The Custom Data Engine)](#tab-2-dynamic-attributes)
   - [Tab 3: Decor & Hall Offerings (Venues & Decorators)](#tab-3-decor--hall-offerings)
   - [Tab 4: Food Packages & Extras (Caterers & Food Counters)](#tab-4-food-packages--extras)
   - [Tab 5: Images (Portfolio & Gallery)](#tab-5-images)
   - [Tab 6: SEO (Descriptions & Search Keywords)](#tab-6-seo)
3. [Master Attributes Cheatsheet (Ready-to-Use Examples)](#3-master-attributes-cheatsheet)
   - [Venue / Banquet Hall Attributes](#a-venue--banquet-hall-attributes)
   - [Decorator & Mandap Stylist Attributes](#b-decorator--mandap-stylist-attributes)
   - [Caterer / Food Vendor Attributes](#c-caterer--food-vendor-attributes)
   - [Photographer & Cinematographer Attributes](#d-photographer--cinematographer-attributes)
   - [DJ, Music & Entertainment Attributes](#e-dj-music--entertainment-attributes)
   - [Bridal Makeup Artist Attributes](#f-bridal-makeup-artist-attributes)
4. [Bulk Upload via Excel Spreadsheet (.xlsx)](#4-bulk-upload-via-excel-spreadsheet-xlsx)
5. [Complete Practical Example: Adding a Luxury Banquet Hall](#5-complete-practical-example-adding-a-luxury-banquet-hall)
6. [Common Mistakes to Avoid (Checklist)](#6-common-mistakes-to-avoid-checklist)

---

## 1. Overview & How the AI Uses Vendor Data

When couples design their wedding on ShadiSense via the **AI Planner** (`/ai-planner`), the AI matches their budget, guest count, city, tradition, and decor taste against the vendors registered here.

```
┌─────────────────────────────────┐       ┌─────────────────────────────────────┐
│  You Enter Vendor Data Here     │ ────> │  AI Planner Matches & Recommends    │
│  - City & Location              │       │  - Filters by City & Guests         │
│  - Capacity & Budget Limits     │       │  - Matches Mandap & Food Taste      │
│  - Food Packages (₹ / plate)    │       │  - Calculates Exact Wedding Budgets │
│  - Photos & High-Res Portfolio  │       │  - Generates Downloadable Plan PDF  │
└─────────────────────────────────┘       └─────────────────────────────────────┘
```

> ⚠️ **Golden Rule:** The more accurate and complete your vendor information is, the better and more frequently the AI will recommend them to couples!

---

## 2. Step-by-Step Tour of the 6 Tabs

When you open `https://shaadisense.com/admin/dynamic-vendors/create`, you will see **6 tabs** at the top of the form:

```
┌─────────────────┬────────────────────┬────────────────────────┬─────────────────────────┬────────┬─────┐
│ 1. Vendor       │ 2. Dynamic         │ 3. Decor & Hall        │ 4. Food Packages        │ 5.     │ 6.  │
│    Details      │    Attributes      │    Offerings           │    & Extras             │ Images │ SEO │
└─────────────────┴────────────────────┴────────────────────────┴─────────────────────────┴────────┴─────┘
```

---

### Tab 1: Vendor Details
This is the basic identity of the business.

| Field | Required? | What to Enter | Real Example |
| :--- | :---: | :--- | :--- |
| **Vendor name \*** | Yes | The official business brand name. | `The Grand Marigold Palace & Banquets` |
| **Category \*** | Yes | Select or type the vendor's primary category. | `Venue`, `Decorator`, `Catering`, `Photographer`, `DJ`, `Makeup`, `Invitation`, `Entertainment` |
| **Status \*** | Yes | Set to **Active** to publish live, or **Draft** if details are pending. | `Active` (Live on platform) |

---

### Tab 2: Dynamic Attributes
This is where you define custom specifications, capacities, pricing rules, and service details.

You can add attributes in **three ways**:
1. Click **+ Add Attribute** to add rows manually.
2. Click **Download Sample Sheet** to get the standard `.xlsx` template.
3. Click **Upload Attribute Sheet** to import hundreds of attributes from Excel instantly!

#### Structure of each Attribute Row:
- **Attribute name \***: The label (e.g. `Service Area`, `Guest Capacity`, `Min Budget Lakhs`).
- **Value type \***:
  - `Text`: For general words (e.g. `Mumbai, Pune, Goa`).
  - `Number`: For pure numbers (e.g. `800` guests).
  - `Currency`: For base starting costs (e.g. `₹25,000`).
  - `Dropdown` / `Radio`: For single selections.
  - `Multi Select` / `Checkbox`: For multiple choices (e.g. `Pure Veg, Jain Food Available`).
  - `Date` / `Time`: For check-in/out times or slots.
- **Planning Cost (Optional):**
  - **Rate (INR):** Billable amount for this specific service.
  - **Charged per:** `Fixed service`, `Guest`, `Unit`, `Hour`, or `Day`.
- **Attribute Images:** Attach high-resolution photos specific to this attribute (e.g. Mandap photos attached directly to `Decoration Type`).

---

### Tab 3: Decor & Hall Offerings
*(Crucial for Venues, Resorts, Banquet Halls, and Decorators)*

This tab allows you to configure **multiple halls, outdoor lawns, or mandap decor packages** under a single vendor!

Click **+ Add Package / Offering** to add an offering card:

| Field | Description & Real Example |
| :--- | :--- |
| **Package / Hall Name \*** | e.g. `Royal Grand AC Ballroom & Crystal Mandap` |
| **Venue Setting Category \*** | Choose from: `Sea-Facing Beachfront`, `Lawn & Poolside`, `Grand AC Ballroom`, `Heritage Resort`. |
| **Min Capacity (Guests)** | Minimum guests this hall accommodates (e.g. `100`). |
| **Max Capacity (Guests)** | Maximum capacity (e.g. `1200`). |
| **Min Budget Range (Lakhs)** | Starting package cost in Lakhs (e.g. `3.5` for ₹3,50,000). |
| **Max Budget Range (Lakhs)** | Premium package cost in Lakhs (e.g. `25.0` for ₹25,00,000). |
| **Supported Service Locations** | Comma-separated cities (e.g. `Mumbai, Navi Mumbai, Thane`). |
| **Supported Traditions** | Comma-separated (e.g. `Maharashtrian Lagna, Muslim Nikah, North Indian Punjabi, Marwari Royal`). |
| **Timing Provisions & Notes** | e.g. `Includes 8 AM - 11 PM slot, central AC, bridal green room, and power backup generator.` |

---

### Tab 4: Food Packages & Extras
*(Crucial for Caterers, Banquets, and Food Contractors)*

This tab has **two powerful sub-sections**:

#### 1. Food Packages (Classic / Deluxe / Elite Feasts)
Click **+ Add Food Package** to create structured menus:
- **Package Name:** e.g. `Royal Mughlai & Rajasthani Banquet`
- **Min Price / Plate (₹):** e.g. `₹ 850`
- **Max Price / Plate (₹):** e.g. `₹ 1,200`
- **Tagline:** e.g. `Lavish feast featuring 4 starters, live bread station, and 3 luxury desserts.`
- **Included Menu Items:** Comma-separated list of dishes:
  `Welcome Mocktails, Paneer Tikka, Veg Spring Rolls, Dal Makhani, Paneer Butter Masala, Kashmiri Pulao, Butter Naan, Gulab Jamun with Ice Cream, Mineral Water`

#### 2. Extra Live Counters & Add-ons
Click **+ Add Extra Counter** for interactive stations:
- **Extra Counter Name:** e.g. `Live Chaat Counter (Pani Puri & Dahi Bhalla)`
- **Min / Max Extra Cost (₹):** e.g. `₹ 90` to `₹ 120`
- **Pricing Unit:** Choose `Per Plate` (₹ per guest) or `Fixed Rental` (flat rate).
- **Icon Class:** `fa-solid fa-utensils`, `fa-solid fa-bowl-rice`, `fa-solid fa-ice-cream`.

---

### Tab 5: Images (Portfolio & Gallery)
- Click **Choose images** and select up to **10-15 high-quality landscape photos**.
- **Accepted Formats:** JPG, PNG, WebP (up to 10 MB each).
- **Recommended Ratio:** Standard 4:3 or 16:9 landscape (e.g. 1200 × 800 px).
- **What to upload:**
  - Wide shots of the entrance, mandap, and banquet lighting.
  - Stage decor, table arrangements, and night lighting.
  - Real bride & groom celebration moments at the venue.

---

### Tab 6: SEO (Descriptions & Search Keywords)
- **Short Description:** 1-2 punchy sentences shown on cards and mobile preview.
  *Example:* `Premier 5-star AC banquet hall in Juhu offering sea-view poolside ceremonies and luxury catering for up to 1,500 guests.`
- **Full Description:** Detailed overview mentioning history, landmark directions, parking slots, and policies.
- **Tags (Comma-separated):** `juhu banquet, sea view wedding, royal mandap, pure veg catering, ac ballroom`
- **Keywords (Comma-separated):** `best wedding hall mumbai, luxury mandap juhu, wedding catering mumbai`

---

## 3. Master Attributes Cheatsheet

Use these ready-to-copy attribute names and values for standard wedding vendor categories:

---

### A. Venue / Banquet Hall Attributes

| Attribute Name (Exact Label) | Value Type | Recommended Sample Value |
| :--- | :--- | :--- |
| `Service Area` | `text` | `Mumbai, Pune, Thane` |
| `Supported Locations` | `text` | `Andheri West, Juhu, Bandra` |
| `Guest Capacity` | `number` | `800` |
| `Min Guest Capacity` | `number` | `150` |
| `Max Guest Capacity` | `number` | `1500` |
| `Min Budget Lakhs` | `number` | `4.5` |
| `Max Budget Lakhs` | `number` | `30.0` |
| `Venue Setting` | `text` | `Grand AC Ballroom, Lawn & Poolside` |
| `Rooms` | `number` | `12` |
| `Check In` | `text` | `10:00 AM` |
| `Check Out` | `text` | `10:00 PM` |
| `Parking` | `text` | `Valet Parking for 250 Cars` |
| `Stage Included` | `text` | `Yes, 40x20 ft Wooden Stage` |
| `Decoration Type` | `text` | `Royal Red & Gold Canopy, Floral Pastel` |

---

### B. Decorator & Mandap Stylist Attributes

| Attribute Name (Exact Label) | Value Type | Recommended Sample Value |
| :--- | :--- | :--- |
| `Service Area` | `text` | `Mumbai, Pune, Goa, Nashik` |
| `Decoration Type` | `text` | `Temple Lotus & White Mogra, Royal Red & Gold Canopy, Arabian Night Floral Glow` |
| `Decor Category` | `text` | `Royal Heritage, Modern Minimalist, Bohemian` |
| `Fresh Flowers` | `text` | `Imported Dutch Roses, Bangalore Marigold, Mogra` |
| `Lighting` | `text` | `Fairy Lights, Crystal Chandeliers, Par Cans` |
| `Smoke Machine` | `text` | `Available on Request` |
| `Min Budget Lakhs` | `number` | `1.5` |
| `Max Budget Lakhs` | `number` | `15.0` |

---

### C. Caterer / Food Vendor Attributes

| Attribute Name (Exact Label) | Value Type | Recommended Sample Value |
| :--- | :--- | :--- |
| `Service Area` | `text` | `Mumbai, Navi Mumbai, Thane` |
| `Food Type` | `text` | `Pure Vegetarian, Jain Food Available, Non-Vegetarian` |
| `Cuisine` | `text` | `North Indian, Gujarati, Marwari, South Indian, Continental` |
| `Minimum Plates` | `number` | `150` |
| `Live Counters` | `text` | `Chaat, Italian Pasta, Live Tandoor, Mocktail Bar` |
| `Price Per Plate (Veg)` | `currency` | `₹ 850` |
| `Price Per Plate (Non-Veg)` | `currency` | `₹ 1,150` |

---

### D. Photographer & Cinematographer Attributes

| Attribute Name (Exact Label) | Value Type | Recommended Sample Value |
| :--- | :--- | :--- |
| `Service Area` | `text` | `Pan India & Destination Weddings` |
| `Photography Styles` | `text` | `Candid, Traditional, Drone Cinematic, Pre-Wedding Shoot` |
| `Drone` | `text` | `4K DJI Drone Included` |
| `Album` | `text` | `2 Premium Leather-Bound 50-Page Photobooks` |
| `Delivery Time` | `text` | `3 to 4 Weeks` |
| `Artist Count` | `number` | `5 Photographers & Cinematographers` |
| `Price` | `currency` | `₹ 1,50,000 per 2-day wedding` |

---

### E. DJ, Music & Entertainment Attributes

| Attribute Name (Exact Label) | Value Type | Recommended Sample Value |
| :--- | :--- | :--- |
| `Service Area` | `text` | `Mumbai, Pune, Goa` |
| `Act Types` | `text` | `Bollywood DJ, Punjabi Dhol Tasha, Shehnai, Sufi Band` |
| `Sound Power` | `text` | `Line Array 10,000 Watts JBL Sound System` |
| `Performance Duration` | `text` | `4 to 6 Hours` |
| `Price` | `currency` | `₹ 45,000 per evening` |

---

### F. Bridal Makeup Artist Attributes

| Attribute Name (Exact Label) | Value Type | Recommended Sample Value |
| :--- | :--- | :--- |
| `Service Area` | `text` | `Mumbai, Studio & On-Location Venue` |
| `Makeup Style` | `text` | `HD Airbrush Makeup, Traditional Bridal Glow` |
| `Products Used` | `text` | `MAC, Charlotte Tilbury, Huda Beauty, Dior` |
| `Trial Included` | `text` | `Yes (1 Paid Trial Available)` |
| `Price` | `currency` | `₹ 25,000 per bridal look` |

---

## 4. Bulk Upload via Excel Spreadsheet (.xlsx)

If you have 20+ vendors or hundreds of attributes, do not type them one by one!

### Step 1: Download Sample Sheet
1. In **Tab 2 (Dynamic Attributes)**, click the blue **Download Sample Sheet** button.
2. An Excel file named `sample_attribute.xlsx` will download.

### Step 2: Format Your Excel File
The sheet requires exactly two columns in the first row:

| Column A | Column B |
| :--- | :--- |
| **Attribute Name** | **Attribute Value** |
| Service Area | Mumbai, Pune |
| Guest Capacity | 1200 |
| Min Budget Lakhs | 5.0 |
| Max Budget Lakhs | 35.0 |
| Food Type | Pure Vegetarian, Jain Available |
| Venue Setting | Grand AC Ballroom |
| Parking | Valet for 300 cars |
| Fresh Flowers | Roses, Orchids, Mogra |

### Step 3: Upload & Done
Click **Upload Attribute Sheet**, choose your `.xlsx` file, and all rows will instantly appear in the form ready to save!

---

## 5. Complete Practical Example: Adding a Luxury Banquet Hall

Here is a 5-minute walk-through to add a venue called **"The Grand Regency Banquets, Mumbai"**:

1. Open `https://shaadisense.com/admin/dynamic-vendors/create`.
2. **Tab 1 (Vendor Details):**
   - Name: `The Grand Regency Banquets`
   - Category: `Venue`
   - Status: `Active`
3. **Tab 2 (Dynamic Attributes):**
   - Click **+ Add Attribute** 3 times:
     - 1. Label: `Service Area` | Type: `Text` | Value: `Mumbai`
     - 2. Label: `Guest Capacity` | Type: `Number` | Value: `1000`
     - 3. Label: `Decoration Type` | Type: `Text` | Value: `Royal Red & Gold Canopy`
4. **Tab 3 (Decor & Hall Offerings):**
   - Hall Name: `Kohinoor Crystal Ballroom & Poolside Lawn`
   - Venue Setting Category: `Grand AC Ballroom`
   - Min Capacity: `200` | Max Capacity: `1200`
   - Min Budget: `4.5` Lakhs | Max Budget: `28.0` Lakhs
   - Locations: `Mumbai, Western Suburbs`
   - Traditions: `Maharashtrian Lagna, Muslim Nikah, Punjabi Wedding`
5. **Tab 4 (Food Packages):**
   - Package Name: `Shahi Dawat Feast`
   - Min Price / Plate: `₹ 850` | Max Price / Plate: `₹ 1,150`
   - Tagline: `Traditional vegetarian banquet with 4 live counters & dessert bar`
6. **Tab 5 (Images):**
   - Upload 4-6 photos of the ballroom, mandap, and entrance.
7. **Tab 6 (SEO):**
   - Short description: `Air-conditioned luxury banquet hall in Mumbai for grand weddings and receptions.`
8. Click **Create Vendor**!

🎉 **Result:** The vendor is now live, and the AI Planner will instantly start recommending them to couples looking for AC ballrooms in Mumbai with ~500–1000 guests!

---

## 6. Common Mistakes to Avoid (Checklist)

| ❌ Common Mistake | Why it hurts | ✅ Correct Action |
| :--- | :--- | :--- |
| **Leaving Status as "Draft"** | The vendor will stay hidden from all couples and the AI engine. | Change Status to **`Active`** when ready to publish. |
| **Missing `Service Area`** | The AI won't know which city the vendor serves and will never recommend them! | Always add `Service Area` (e.g. `Mumbai` or `Pune`). |
| **Omitting `Guest Capacity`** | When couples enter 500 guests, the AI skips vendors with empty capacity. | Always set `Min Capacity` and `Max Capacity`. |
| **Uploading vertical selfie photos** | Distorts layout and looks unprofessional on couple dashboards. | Always upload **landscape photos (4:3 or 16:9 ratio)**. |
| **Files larger than 10 MB** | Server will reject the image upload. | Resize photos to under 10 MB (recommended: 1–3 MB). |
| **Mispelling Category names** | Breaks search filters. | Use standard capitalized names: `Venue`, `Decorator`, `Catering`, `Photographer`, `DJ`, `Makeup`. |

---

*Documentation created for ShadiSense Operations & Vendor Teams.*  
*For questions or bulk database imports, contact the Technical Administration team.*

