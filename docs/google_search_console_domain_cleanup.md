# Google Search Console & Domain History Cleanup Guide

This guide details the exact steps for the site owner of **Breezekings.com** to resolve legacy domain history issues, protect against spammy legacy backlinks, and accelerate Google indexing for new content and AdSense readiness.

---

## 1. Verify Security & Manual Actions Status

1. Open [Google Search Console](https://search.google.com/search-console).
2. Select your property: `https://breezekings.com` (or the Domain property `breezekings.com`).
3. In the left-hand navigation sidebar, click **Security & Manual Actions**:
   - Click **Manual actions**: Verify that it says **"No issues detected"** with a green checkmark.
   - Click **Security issues**: Verify that it says **"No issues detected"** (no hacked content, malware, or phishing detected).
4. *If any manual action is listed, review the reason provided by Google and submit a reconsideration request after cleaning the links/content.*

---

## 2. Check the Links Report & Prepare Backlink Disavow

Because the domain was previously used around 2017 as a WordPress content-mill site (publishing generic articles about Discord, Zelle, video loading, etc.), low-quality or spammy backlinks may still point to it and drag down your new domain authority.

### Steps to Audit:
1. In Google Search Console, click **Links** in the left sidebar (under *Legacy tools & reports* or near the bottom).
2. Under **External links**, review:
   - **Top linking sites** (click "MORE" to view all linking domains).
   - **Top linking text** (anchor text used by incoming links).
3. Export the list to CSV/Excel:
   - Look for link-farm directories, scraper sites, spam blogs, or irrelevant foreign language sites that linked to the 2017 articles.
4. Add any spammy domains to `disavow.txt` using domain-level formatting:
   ```txt
   # Breezekings Spam Backlink Disavow File
   domain:spammy-site-one.com
   domain:lowquality-directory.xyz
   domain:content-farm-network.net
   ```
5. Visit the official [Google Disavow Tool](https://search.google.com/search-console/disavow-links).
6. Select your property (`https://breezekings.com`) and upload your `disavow.txt` file.

---

## 3. Remove Stale Indexed URLs from the 2017 Era

Google may still have old WordPress URLs indexed from the domain's previous owner (e.g., `/2017/...`, old WordPress tag archives, or stale posts about Zelle/Discord).

### Steps to Request URL Removals:
1. In Search Console, click **Removals** in the left sidebar.
2. Click **NEW REQUEST**.
3. Select **Clear cached URL** (or **Temporarily remove URL**):
   - Choose **Remove all URLs with this prefix** if the old URLs share a prefix (e.g. `https://breezekings.com/wp-content/` or `https://breezekings.com/tag/`).
   - Or enter specific old URLs that show up in Google search results for `site:breezekings.com`.
4. Submit the request. Google typically approves temporary removals within 24 to 48 hours.

---

## 4. Request Fast Indexing for Real, Live Pages

1. In the top search bar in Google Search Console ("*Inspect any URL in breezekings.com*"):
   - Enter your homepage: `https://breezekings.com/`
   - Click **Test Live URL**.
   - Once verified, click **REQUEST INDEXING**.
2. Repeat for your core legal and content pages:
   - `https://breezekings.com/about`
   - `https://breezekings.com/contact`
   - `https://breezekings.com/privacy-policy`
   - `https://breezekings.com/sitemap.xml`
   - Each live post permalink (e.g., `https://breezekings.com/post/...`)
3. Go to **Sitemaps** in the left menu and ensure `https://breezekings.com/sitemap.xml` is submitted with status **"Success"**.

---

## 5. AdSense Application Readiness Checklist

Before applying (or reapplying) for Google AdSense:

- [x] **Zero Placeholder Text**: All editor default text ("Start writing your amazing...") has been completely eliminated from all excerpts, homepages, and single posts.
- [x] **Real Author Identification**: Author avatars and real names (Khizar Ahmad / Waseem Azam) are visible sitewide, replacing generic "Admin".
- [x] **No Broken/Fake Navigation**: No fake pagination links (`#`) or broken `blog.php` links.
- [x] **Functional Footer Links**: Real email, working RSS feed, active legal policy pages (Privacy Policy, Terms, Cookie Policy).
- [ ] **Content Volume across Categories**: Publish at least 2–3 substantive articles (700+ words each) in every category (Business, Health, Entertainment, Lifestyle, News, Technology) before submitting for AdSense review.
