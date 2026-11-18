# 📖 ShadiSense — Complete Admin Panel User Manual
### *A Friendly, Step-by-Step Guide for Non-Technical Team Members & Platform Owners*

> **Document Version:** 2.0 (Non-Technical Edition)  
> **Platform:** ShadiSense Luxury AI Event & Wedding Planner  
> **Target Audience:** Business Owners, Operations Managers, Wedding Coordinators, Support Staff, and Content Teams  
> **No Coding or Technical Knowledge Required!**

---

## 🧭 Welcome to ShadiSense Admin Portal

Welcome to your central command center! As an administrator, you have full control over couples' wedding planning journeys, vendor partnerships, subscription revenue, website content, and the AI engine.

This manual is written in **plain, simple language**. Whenever you see an arrow like `➔`, it simply means **"click the next button or menu"**.

---

## 📑 Table of Contents

1. [How to Log In & Secure Your Account](#1-how-to-log-in--secure-your-account)
2. [Navigating the Admin Interface (Quick Tour)](#2-navigating-the-admin-interface-quick-tour)
3. [Module 1: Dashboard (Your Business at a Glance)](#3-module-1-dashboard-your-business-at-a-glance)
4. [Module 2: Manage Users (Couples & Event Hosts)](#4-module-2-manage-users-couples--event-hosts)
5. [Module 3: User Queries (Customer Support & Inquiries)](#5-module-3-user-queries-customer-support--inquiries)
6. [Module 4: Vendor Enquiries (New Vendor Partnership Leads)](#6-module-4-vendor-enquiries-new-vendor-partnership-leads)
7. [Module 5: Manage Vendors (Venues, Decorators, Caterers, etc.)](#7-module-5-manage-vendors-venues-decorators-caterers-etc)
8. [Module 6: Subscription Plan Manager (Pricing & Packages)](#8-module-6-subscription-plan-manager-pricing--packages)
9. [Module 7: Transactions & Invoices (Payment Ledger)](#9-module-7-transactions--invoices-payment-ledger)
10. [Module 8: Vendor Analytics (Marketplace Intelligence)](#10-module-8-vendor-analytics-marketplace-intelligence)
11. [Module 9: Event Requirement Questions (AI Planner Questions Engine)](#11-module-9-event-requirement-questions-ai-planner-questions-engine)
12. [Module 10: Notification Management (Push Announcements)](#12-module-10-notification-management-push-announcements)
13. [Module 11: Manage Pages & Website Content CMS](#13-module-11-manage-pages--website-content-cms)
14. [Module 12: AI Configuration (OpenRouter & Planning Prompts)](#14-module-12-ai-configuration-openrouter--planning-prompts)
15. [Module 13: Staff & Role Permissions (Team Management)](#15-module-13-staff--role-permissions-team-management)
16. [Module 14: Profile & Password Settings](#16-module-14-profile--password-settings)
17. [Common Daily Workflows (Quick Step-by-Step Recipes)](#17-common-daily-workflows-quick-step-by-step-recipes)
18. [Troubleshooting & Help (FAQs for Non-Technical Users)](#18-troubleshooting--help-faqs-for-non-technical-users)

---

## 1. How to Log In & Secure Your Account

### Logging In
1. Open your browser (Google Chrome, Edge, Safari, or Firefox).
2. Go to your admin URL: `https://your-website.com/admin/login` (or `http://localhost:8000/admin/login` on local setup).
3. Enter your **Email Address** and **Password**.
4. Click the **Log In** button.
5. If your credentials are correct, you will be taken directly to your **Dashboard**.

> 💡 **Tip:** If you forget your password, contact your Senior System Administrator to reset it from the **Staff** module.

### Logging Out
- Look at the bottom of the left sidebar or the top-right corner. Click **Sign Out** or **Logout** when leaving your computer unattended.

---

## 2. Navigating the Admin Interface (Quick Tour)

The screen is divided into two primary areas:

```
┌──────────────────────────────┬────────────────────────────────────────────────────────┐
│  SHADISENSE ADMIN            │  [Header Area] Search, Quick Actions, Profile          │
├──────────────────────────────┼────────────────────────────────────────────────────────┤
│  [DB] Dashboard              │                                                        │
│  [MU] Manage Users           │                                                        │
│  [UQ] User Queries           │                  MAIN WORKSPACE AREA                   │
│  [VE] Vendor Enquiries       │                                                        │
│  [MV] Manage Vendors         │  This is where tables, forms, and charts appear.       │
│  [SM] Subscription Manager   │                                                        │
│  [TX] Transactions           │                                                        │
│  [VA] Vendor Analytics       │                                                        │
│  [EQ] Event Questions        │                                                        │
│  [NM] Notifications          │                                                        │
│  [MP] Manage Pages           │                                                        │
│  [AI] AI Configuration       │                                                        │
│  --------------------------- │                                                        │
│  [ST] Staff Team             │                                                        │
│  [PR] My Profile             │                                                        │
└──────────────────────────────┴────────────────────────────────────────────────────────┘
```

1. **Left Sidebar Navigation:** This list stays visible on desktop. Click any item to jump straight into that module.
2. **Main Workspace:** Shows your tables, action buttons, forms, and analytical charts.
3. **Status Badges Color Code:**
   - 🟢 **Green (Active / Published / Replied):** Everything is live and healthy.
   - 🟡 **Yellow / Amber (Pending / Contacted / Inactive):** Needs review or attention.
   - 🔴 **Red (Failed / Closed / Disabled):** Issue detected, expired, or deactivated.

---

## 3. Module 1: Dashboard (Your Business at a Glance)

**Menu Link:** Click **Dashboard** in the sidebar.

The Dashboard is your morning overview. It shows live statistics:

### What You See on the Dashboard:
1. **Total Users:** How many registered couples/users have accounts on ShadiSense, along with their month-on-month percentage growth.
2. **Generated Plans:** The total count of AI wedding plans created to date, plus how many were created this month.
3. **Active Vendors:** The number of live vendors categorized by services (Venues, Decorators, Caterers, Photographers).
4. **Active Subscriptions & Revenue:** Total active paid members and total gross revenue generated (formatted in Lakhs ₹L or Crores ₹Cr).
5. **Quick Overview Cards:** New signups this month and plans created today.

> 🎯 **What you should do here daily:** Check the new users and plans count to see how popular the AI planner was over the past 24 hours.

---

## 4. Module 2: Manage Users (Couples & Event Hosts)

**Menu Link:** Click **Manage Users** in the sidebar.

This page displays every couple and user who registered on your platform.

### What You Can Do Here:

#### A. Search & Filter Users
- Use the search bar at the top to find any user by **Name**, **Email**, or **Mobile Number**.

#### B. Download User Reports (PDF & Excel)
- Click the red **Download PDF** button at the top-right to generate an instant printable PDF directory of all users.
- Click the green **Download Excel** button to download a spreadsheet for marketing or offline analysis.

#### C. Moderate / Deactivate a User Account
- In the **Status** column, each user has a status toggle button.
- If you notice spam or policy violations, click the toggle to set their status to **Inactive**. This immediately prevents them from logging in.

#### D. View a User's AI Wedding Plans
1. Click the **View Plans** button next to any user.
2. You will see a list of all wedding plans this user has generated with the AI.
3. Click on any plan to see:
   - Selected Venue & Mandap theme.
   - Selected Catering preferences & Per-plate cost.
   - Estimated Guest Count & Total Budget.
   - Matched Vendors list.
4. Click **Download Plan PDF** to download the exact luxury wedding plan brochure generated for that couple!

---

## 5. Module 3: User Queries (Customer Support & Inquiries)

**Menu Link:** Click **User Queries** in the sidebar.

When visitors or registered users fill out the "Contact Us" form on your website, their messages arrive here instantly.

### How to Respond to a User:
1. Locate the query in the table. You will see their **Name**, **Email**, **Phone**, **Subject**, and **Message**.
2. Click the **Reply** button next to the query.
3. A response box will appear. Type your message to the user.
4. Click **Send Reply**.
5. **What happens automatically:**
   - ShadiSense sends an official branded email directly to the user's inbox with your reply.
   - The query status automatically changes from 🟡 **Open** to 🟢 **Replied**.
   - Your reply is logged under the message so other team members can see what was answered.

### Closing or Deleting Spam:
- If a query is resolved, click **Edit** ➔ change status to **Closed** ➔ Save.
- If it is junk spam, click the red **Delete** button.

---

## 6. Module 4: Vendor Enquiries (New Vendor Partnership Leads)

**Menu Link:** Click **Vendor Enquiries** in the sidebar.

When a new wedding vendor (e.g. a decorator in Mumbai or a palace venue in Udaipur) visits your site and clicks *"Register as Vendor"*, their initial application lands here.

### Metrics at the Top:
- **Total Enquiries:** All vendor applications received.
- **Pending Review:** New applications waiting for your call.
- **Contacted:** Vendors your team has already called or emailed.

### Step-by-Step: Reviewing & Onboarding a Vendor:
1. Click on a vendor enquiry to view their submitted details:
   - Business Name, Owner Name, Email, Phone.
   - Primary Service Category (e.g. Catering, Photography, Venue).
   - City / Operating Area.
2. After your team speaks with the vendor and verifies their business:
   - Update their status to **Contacted**.
3. Once approved, click **Add Dynamic Vendor** (top-right button) to officially create their live profile in **Manage Vendors**.

---

## 7. Module 5: Manage Vendors (Venues, Decorators, Caterers, etc.)

**Menu Link:** Click **Manage Vendors** in the sidebar.

This is your **master vendor directory**. The AI Planner searches this database to recommend the best vendors for couples.

### Step-by-Step: Adding a New Vendor:
1. Click the blue **Add Dynamic Vendor** button.
2. **Fill in Business Details:**
   - **Vendor Name:** e.g. `Grand Marigold Decorators & Florists`.
   - **Primary Category:** Select `Decoration`, `Venue`, `Catering`, `Photography`, `Makeup`, or `Music & DJ`.
   - **Short Description:** 2-3 sentences explaining their specialty.
3. **Upload Photos & Portfolio:**
   - Upload high-resolution images of their work (Mandaps, banquet setups, food presentations). These photos appear in the user's generated wedding plans!
4. **Set Up Custom Attributes (The AI Secret Sauce):**
   - Scroll to the **Attributes** section.
   - Add details like:
     - `Service Area` = `Mumbai, Pune`
     - `Decoration Type` = `Royal Red & Gold Canopy`
     - `Guest Capacity` = `800`
     - `Catering Types` = `Pure Veg, Jain Available`
   - *Alternative:* You can upload an Excel sheet (`.xlsx`) with two columns: `Attribute Name` and `Attribute Value` to fill 50+ attributes in 2 seconds!
5. **Set Status:** Choose **Active** to publish them live, or **Draft** if you are still waiting for their contract.
6. Click **Save Vendor**.

---

## 8. Module 6: Subscription Plan Manager (Pricing & Packages)

**Menu Link:** Click **Subscription Manager** in the sidebar.

This module controls the monetization plans available on ShadiSense (for example: *Free Trial*, *Silver Wedding Pass*, *Royal Gold VIP Plan*).

### Creating or Editing a Subscription Plan:
1. In the left panel, fill in:
   - **Plan Name:** e.g. `Royal Wedding Planning Pass`.
   - **Price (₹):** e.g. `2999` (enter `0` for free tier).
   - **Billing Cycle:** Choose `Monthly`, `Quarterly`, `Yearly`, or `One-Time Lifetime`.
   - **Description:** A short marketing pitch of what's included.
   - **Features List:** Type each feature on a new line (e.g., `Unlimited AI Plan Generations`, `Direct Vendor Contact Numbers`, `Downloadable PDF Itineraries`, `Dedicated Wedding Concierge`).
2. Click **Create Plan** (or **Save Changes** when editing).
3. The plan will immediately appear on the user pricing page!

---

## 9. Module 7: Transactions & Invoices (Payment Ledger)

**Menu Link:** Click **Transactions** in the sidebar.

Every time a user pays for a subscription plan via Razorpay or online payment, a permanent record is created here.

### What You See in Transactions:
- **User Details:** Who paid (Name, Email, Phone number).
- **Plan Name:** What package they bought.
- **Amount & Currency:** Total amount paid in INR (₹).
- **Gateway IDs:** Official Razorpay Order ID & Payment ID for reconciliation with your bank account.
- **Validity:** The start date and expiration date of the user's subscription.
- **Status:**
  - 🟢 `active` = Payment succeeded and subscription is currently running.
  - 🟡 `created` = User initiated payment but has not finalized it.
  - 🔴 `failed` = Payment declined by the bank.
  - ⚪ `expired` = Previous subscription that has ended.

### Exporting Accounting Reports:
- Use the **Date & Search Filter** to filter payments for a specific month.
- Click **Download Excel** to give the spreadsheet to your accountant or CA.
- Click **Download PDF** for an executive revenue summary.

---

## 10. Module 8: Vendor Analytics (Marketplace Intelligence)

**Menu Link:** Click **Vendor Analytics** in the sidebar.

This page turns your platform data into colorful, visual charts and intelligence.

### Key Charts Explained:
1. **Reporting Window:** Choose `Today`, `This Week`, `This Month`, `This Year`, or select a custom date range.
2. **Current Vendors by Category (Doughnut Chart):** Shows the balance of your vendor marketplace (e.g. 40% Venues, 25% Decorators, 20% Caterers, 15% Photographers).
3. **Planning Demand by Category (Bar Chart):** Shows what couples are requesting most in the AI planner right now! If you see huge demand for "Heritage Palaces" but you have few vendors in that category, you know which vendors to recruit next!
4. **Active Inventory Rate:** The percentage of your registered vendors currently active and ready to take bookings.
5. **Total Planned Value:** The total wedding budget estimated across all couple plans created during that period (e.g. `₹48.50 L`).

---

## 11. Module 9: Event Requirement Questions (AI Planner Questions Engine)

**Menu Link:** Click **Event Requirement Questions** in the sidebar.

*(For an exhaustive guide on this specific module, see our companion document: `AI_PLANNER_USER_MANUAL_QUESTIONS_AND_ATTRIBUTES.md`)*

### Quick Summary of How to Use This:
1. This is where you configure the questions asked in the **AI Wedding Planner** (`/ai-planner`).
2. **To Add a Question:** Click **Add Event Question**.
   - Enter the question headline (e.g. *"What style of photography do you prefer?"*).
   - Enter a unique Code (e.g. `photography_style`).
   - Choose `radio` for single selection or `checkbox` for multiple selections.
   - Set **Display Order** (e.g. `1`, `2`, `3`... determines step number).
3. **Connect to Vendors:** Select a vendor attribute from the dropdown. The system automatically loads the choices offered by real vendors!
4. **Style the Answer Cards:** Upload high-resolution photos, pick luxury icons (Crown, Ring, Champagne, Music, Star), and write descriptive subtitles.
5. Save, and your new question instantly appears in the AI Planner!

---

## 12. Module 10: Notification Management (Push Announcements)

**Menu Link:** Click **Notification Management** in the sidebar.

Send broadcast announcements, festival discounts, or planning reminders directly to your registered users.

### Sending an Announcement:
1. Click **Create Notification**.
2. **Title:** e.g. *"🎉 Diwali Wedding Bonanza — 20% Off Premium Decorators!"*
3. **Message:** Write your announcement text.
4. **Target Recipients:** Choose whether to send to **All Users**, **Active Subscribers Only**, or a specific user segment.
5. **Schedule:**
   - Choose **Immediate** to send it right away.
   - Or choose **Schedule for Later** and pick a future date and time.
6. Click **Send Notification**.

---

## 13. Module 11: Manage Pages & Website Content CMS

**Menu Link:** Click **Manage Pages** or **Landing Content** in the sidebar.

You can edit your website copy without touching any code!

### Manage Pages (Static Pages)
- Edit official text pages such as **About Us**, **Privacy Policy**, **Terms & Conditions**, and **Cancellation Policy**.
- Click **Edit** ➔ change the text in the editor ➔ Click **Update**.

### Landing Content (Homepage Sections)
Located under `Landing Content`:
- **How It Works (`how-it-works`):** Edit the 3 or 4 simple steps shown on the homepage explaining how ShadiSense works.
- **Comparisons (`comparisons`):** Edit the feature comparison chart (e.g. *"ShadiSense vs. Traditional Wedding Planners"*).
- **Testimonials (`testimonials`):** Add reviews from real brides, grooms, and event hosts. Upload their photo, quote, and wedding city.

---

## 14. Module 12: AI Configuration (OpenRouter & Planning Prompts)

**Menu Link:** Click **AI Configuration** in the sidebar.

ShadiSense is powered by state-of-the-art Large Language Models (LLMs) via OpenRouter.

### What Can You Change Here?
1. **OpenRouter API Key:**
   - To update your API key, type the new key in the box.
   - For security, existing keys are masked as `••••••••••••`. You can click **Show** to verify.
2. **OpenRouter Model Dropdown:**
   - Choose the AI model powering your planner (e.g., Anthropic Claude 3.5 Sonnet, OpenAI GPT-4o, Google Gemini 2.0 Flash, etc.).
   - The dropdown automatically lists all compatible models synchronized with your OpenRouter account.
3. **Event Planner System Prompt:**
   - This is the master set of instructions given to the AI.
   - It tells the AI how to act like a luxury Indian wedding planner, how to calculate budgets, how to recommend cultural traditions, and how to write elegant event summaries.
   - If you want the AI to adopt a warmer tone or focus on specific regional weddings, you can tweak the wording here!
4. **Status:** Keep this set to **Enabled**.

> ⚠️ **Caution:** Do not delete placeholder tags like `{guest_count}` or `{budget}` inside the system prompt, as the AI uses these to insert user numbers.

---

## 15. Module 13: Staff & Role Permissions (Team Management)

**Menu Link:** Click **Staff** in the sidebar.

You don't have to manage ShadiSense alone! You can create sub-accounts for your colleagues, coordinators, and customer support agents.

### Adding a New Team Member:
1. Click **Add Staff Member**.
2. Enter their **Name**, **Email Address**, and temporary **Password**.
3. **Set Granular Permissions:** You will see a list of checkboxes for each module:
   - `[x] User Queries` (Allow them to answer customer emails)
   - `[x] Manage Vendors` (Allow them to add and edit vendor profiles)
   - `[ ] Transactions` (Keep financial revenue private from staff)
   - `[ ] AI Configuration` (Prevent accidental prompt changes)
4. Click **Save Staff**.
5. Give the credentials to your team member. When they log in, they will **only** see the modules you checked!

---

## 16. Module 14: Profile & Password Settings

**Menu Link:** Click **Profile** or your name in the sidebar.

Here you can manage your personal administrator account details:
1. Update your **First Name**, **Last Name**, and **Email Address**.
2. **Change Password:** Enter your current password, type your new secure password, confirm it, and click **Save Changes**.

---

## 17. Common Daily Workflows (Quick Step-by-Step Recipes)

### 📌 Recipe 1: "A couple just submitted a question on the website. How do I reply?"
1. Click **User Queries** in the sidebar.
2. Click **Reply** next to their message.
3. Type your response in the box.
4. Click **Send Reply**. Done! The email is on its way.

---

### 📌 Recipe 2: "We signed a new Banquet Hall in Pune. How do I add them?"
1. Click **Manage Vendors** in the sidebar.
2. Click **Add Dynamic Vendor**.
3. Type Name: `The Grand Orchid Ballroom, Pune`.
4. Select Category: `Venue`.
5. Under Attributes, add `Service Area` = `Pune`, `Guest Capacity` = `1200`.
6. Upload 3-5 beautiful banquet hall photos.
7. Set status to **Active** and click **Save Vendor**.
8. They are now live and eligible for AI plan recommendations!

---

### 📌 Recipe 3: "A couple asked for a copy of their AI wedding plan PDF."
1. Click **Manage Users** in the sidebar.
2. Search for the user by email or name.
3. Click **View Plans** next to their name.
4. Locate their plan and click **Download PDF**.
5. A PDF will download to your computer that you can print, WhatsApp, or email to the client!

---

## 18. Troubleshooting & Help (FAQs for Non-Technical Users)

### Q1: I made a change to a question or vendor, but I don't see it on the website.
> **Fix:** 
> 1. Make sure the **Status** checkbox is set to **Enabled** or **Active** (not Draft or Disabled).
> 2. Hard-refresh your browser page by pressing `Ctrl + F5` (Windows) or `Cmd + Shift + R` (Mac) to clear your browser cache.

### Q2: I tried to upload a photo and received an error saying "The image may not be greater than 5120 kilobytes."
> **Fix:** The image file is larger than 5 MB. Open the photo in an image compressor (like tinypng.com) or resize it, then upload again.

### Q3: A vendor called saying they are not getting recommended by the AI.
> **Fix:** 
> 1. Go to **Manage Vendors** and verify their status is **Active**.
> 2. Check their **Attributes**. If they haven't listed their `Service Area` (e.g. Mumbai) or `Budget Range`, the AI has no way of matching them to couples in that area!

### Q4: How do I export data for our monthly accounting meeting?
> **Fix:** Go to **Transactions** ➔ Pick your date range ➔ Click the green **Download Excel** button. You'll get a clean spreadsheet ready for Excel or Google Sheets.

---

*Manual created with care for the ShadiSense Team.*  
*Keep this document saved for onboarding new team members!*

