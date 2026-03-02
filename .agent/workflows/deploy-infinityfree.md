---
description: How to host your Zeoraz Platform on InfinityFree
---

To host your premium marketplace on InfinityFree, follow these exact steps to ensure the high-performance aesthetics and AI functionality remain operational.

# Step 1: Export Your Database
1. Open your local XAMPP/MySQL and go to `phpMyAdmin`.
2. Select the `multi_vendor_market` database.
3. Click the **Export** tab and then **Export** to download the `.sql` file.

# Step 2: Set Up InfinityFree
1. Log in to your InfinityFree account and create a new **Hosting Account**.
2. Go to **MySQL Databases** in the Control Panel (cPanel).
3. Create a NEW database (named something like `epiz_XXX_zeoraz`).
4. Note down your **MySQL Hostname, Username, and Password** from the cPanel (InfinityFree sidebar).

# Step 3: Configure Database Connection
1. In your local folder, update your `.env` file (or create one on the server) with your new InfinityFree credentials:
   ```env
   DB_HOST=[Your InfinityFree MySQL Hostname]
   DB_NAME=[Your InfinityFree DB Name]
   DB_USER=[Your InfinityFree DB Username]
   DB_PASS=[Your InfinityFree DB Password]
   DB_PORT=3306
   OPENROUTER_API_KEY=[Your AI API Key]
   ```
2. **CRITICAL**: The `.env` file must be uploaded to the `htdocs` directory on InfinityFree.

# Step 4: Upload Your Files
1. Use an FTP client like **FileZilla** (recommended) or the **Online File Manager**.
2. Upload EVERYTHING from your local folder into the `htdocs` folder on InfinityFree.
3. Keep the same directory structure (e.g., `api/`, `core/`, `pages/`).

# Step 5: Import Database on Server
1. In your InfinityFree cPanel, go to **phpMyAdmin**.
2. Select the database you just created.
3. Click the **Import** tab, select your `.sql` file from Step 1, and click **Go**.

# Step 6: Verify Deployment
1. Visit your InfinityFree URL (e.g., `zeoraz.epizy.com`).
2. Test the AI Chatbot to ensure its "Neural Fallback" is working or that your OpenRouter key is properly connected!

**PRO TIP**: InfinityFree has some performance limitations. For the best experience, consider moving to a VPS later, but this setup will handle your early launch perfectly.
