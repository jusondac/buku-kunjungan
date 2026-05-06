# Buku Kunjungan v2.0 - User Guide

## 🎯 Quick Start

### 1. Start the Server
```bash
cd /home/rejka/Desktop/code/laravel/buku-kunjungan
php artisan serve
```

**Access**: http://localhost:8000

### 2. Login
- **Email**: petugas@example.com
- **Password**: password123

---

## 📊 Navigation Guide

### Sidebar Menu (Left Side)
After login, you'll see three main options:

#### 1. 📊 Dashboard
- **What**: Overview of all guest statistics
- **Shows**:
  - Total guests (all-time and this month)
  - Menunggu count
  - Dilayani count
  - Selesai count
  - Status distribution with progress bars
  - Quick action buttons

#### 2. 📋 Data Kunjungan
- **What**: Complete guest list management
- **Features**:
  - View all guests in table format
  - Search by nama or telepon
  - Filter by status
  - Update status (dropdown)
  - Delete guests
  - Pagination (15 per page)

#### 3. 📥 Export Data
- **What**: Generate reports and export data
- **Features**:
  - Filter by date range
  - Filter by keperluan (purpose)
  - Preview data before export
  - Export to Excel (.xlsx)
  - Export to PDF

---

## 🔍 Using Data Kunjungan

### Search
1. Click "📋 Data Kunjungan"
2. Enter name or phone number in search box
3. Click "Cari" button
4. Click "Reset" to clear search

### Filter by Status
1. Open "📋 Data Kunjungan"
2. Select status from dropdown (Menunggu/Dilayani/Selesai)
3. Click "Cari" button
4. To remove filter, select "Semua Status"

### Update Guest Status
1. In the table, find the guest
2. Click on their status dropdown
3. Select new status (auto-saves)
4. Success message appears

### Delete Guest
1. In the table, click "Hapus" button
2. Confirm the deletion
3. Guest record is removed

---

## 📥 Using Export Data

### Export with Filters

#### Step 1: Set Filters
- **Tanggal Mulai**: Select start date (optional)
- **Tanggal Selesai**: Select end date (optional)
- **Keperluan**: Select from list (optional):
  - Rehabilitas
  - SKHPN
  - Bagian Umum
  - Pemberantasan
  - Lainnya

#### Step 2: View Preview
- Click "Tampilkan" button
- See statistics (Total, Menunggu, Dilayani, Selesai)
- View first 20 records in preview table

#### Step 3: Export
- Click "📊 Export Excel" to download .xlsx file
- Click "📄 Export PDF" to download PDF

### Export All Data
- Leave all filters empty
- Click "Tampilkan"
- All 150+ guest records available for export

---

## 📊 Dashboard Breakdown

### Statistics Cards
Show both all-time and monthly data:
- **Total Tamu**: Total guests
- **Menunggu**: Waiting for service
- **Dilayani**: Currently being served
- **Selesai**: Service completed

### Status Distribution
Progress bars showing percentage of each status:
- Yellow bar: % Menunggu
- Blue bar: % Dilayani
- Green bar: % Selesai

### Quick Actions
Fast links to other sections:
- Lihat Data Kunjungan
- Tambah Tamu Baru
- Export Data

---

## 📋 Data Fields

### Guest Information (Tamu)
| Field | Description | Example |
|-------|-------------|---------|
| Nama | Full name | Budi Santoso |
| Telepon | Phone number | 081234567890 |
| Alamat | Address | Jl. Merdeka No. 123 |
| Keperluan | Purpose of visit | Rehabilitas |
| Status | Current status | Menunggu |
| Tanggal | Registration date | 06 May 2026 |

### Keperluan Options
1. **Rehabilitas** - Rehabilitation
2. **SKHPN** - Specific program code
3. **Bagian Umum** - General affairs
4. **Pemberantasan** - Eradication programs
5. **Lainnya** - Other (custom value)

### Status Options
1. **Menunggu** (Yellow) - Waiting
2. **Dilayani** (Blue) - Being served
3. **Selesai** (Green) - Completed

---

## 🎨 UI Color Guide

| Color | Meaning | Example |
|-------|---------|---------|
| Blue (#0066cc) | Primary action | Buttons, links |
| Yellow (#fcd34d) | Status Menunggu | Status badges |
| Light Blue (#87ceeb) | Status Dilayani | Status badges |
| Green (#90ee90) | Status Selesai | Status badges |
| Red | Delete/Danger | Delete button |
| Gray | Neutral/Disabled | Inactive buttons |

---

## 💡 Common Tasks

### Task: View Today's Guests
1. Go to "📋 Data Kunjungan"
2. Guests are sorted by newest first
3. Scroll to see today's registrations

### Task: Find Rehabilitation Guests
1. Go to "📋 Data Kunjungan"
2. In search, clear any existing filter
3. Go to "📥 Export Data"
4. Select Keperluan = "Rehabilitas"
5. Click "Tampilkan"

### Task: Generate Monthly Report
1. Go to "📥 Export Data"
2. Set:
   - Tanggal Mulai: 1st of month
   - Tanggal Selesai: Last day of month
3. Click "Tampilkan"
4. Click "Export PDF" or "Export Excel"

### Task: See Pending Tasks
1. Go to "📊 Dashboard"
2. Look at "Menunggu" card
3. See count of waiting guests
4. Click "📋 Data Kunjungan" and filter by "Menunggu"

---

## 🔐 Security Notes

- Always logout when done (button in sidebar)
- Passwords are hashed and secure
- All data operations require login
- Deletion is permanent (use with caution)

---

## ⌨️ Keyboard Shortcuts

- `Ctrl+F` - Search in browser (works in table)
- `Tab` - Navigate between form fields
- `Enter` - Submit forms
- `Esc` - Close modals/dialogs

---

## 🐛 Troubleshooting

### Can't login
- Check email: petugas@example.com
- Check password: password123
- Browser cookies enabled?

### Export not working
- Select at least Tanggal Mulai OR Tanggal Selesai
- Or add Keperluan filter
- Click "Tampilkan" first

### Can't see new data
- Refresh page (F5)
- Check if still logged in
- Data may need to be created first

### Slow performance
- 150+ guest records is normal size
- Pagination helps (15 per page)
- Filter data to speed up exports

---

## 📱 Responsive Design

The application works on:
- ✅ Desktop (1920px+)
- ✅ Tablet (768px - 1024px)
- ✅ Mobile (320px - 480px)

Sidebar collapses on mobile - tap menu to expand

---

## 📞 Support

For issues, check:
1. Are you logged in?
2. Is the server running?
3. Have you refreshed the page?
4. Check browser console (F12) for errors

---

**Version**: 2.0.0
**Last Updated**: May 6, 2026
**Database Records**: 150 test guests
**Status**: ✅ Production Ready
