# Administrator Guide - Hiking Management System

Complete guide for administrators to manage the Hiking Management System.

## Table of Contents

1. [Admin Dashboard](#admin-dashboard)
2. [Trip Management](#trip-management)
3. [Bus Fleet Management](#bus-fleet-management)
4. [Participation Management](#participation-management)
5. [User Management](#user-management)
6. [Analytics & Reports](#analytics--reports)
7. [Settings & Configuration](#settings--configuration)
8. [Maintenance Tasks](#maintenance-tasks)
9. [Troubleshooting](#troubleshooting)
10. [Security Best Practices](#security-best-practices)

---

## Admin Dashboard

### Dashboard Overview

The admin dashboard provides at-a-glance metrics and management access.

**Key Metrics Displayed:**
- Total registered users
- Total hiking trips
- Total participation requests
- Revenue generated
- Pending approvals
- Recent activity feed

### Quick Statistics

| Metric | What it shows |
|--------|---------------|
| Total Users | Number of registered accounts |
| Active Trips | Trips in "Planned" or "In Progress" status |
| Pending Requests | Awaiting admin approval |
| Total Revenue | Earnings from trip prices |
| Capacity Utilization | % of spots filled across all trips |

### Recent Activity Log

Shows recent system activity:
- New user registrations
- Participation requests
- Approved/rejected requests
- Trip creations/modifications
- New messages/support tickets

---

## Trip Management

### Creating a New Trip

1. Click **"Manage Trips"** → **"Add New Trip"**
2. Fill in the trip form:

**Required Fields:**
- **Title:** Trip name (e.g., "Djebel Zaghouan Adventure")
- **Category:** Type of hike (Mountain, Desert, Forest, Water)
- **Description:** Detailed description of the trip
- **Region:** Geographic location
- **Difficulty Level:** 
  - Facile (Easy)
  - Moyen (Medium)
  - Difficile (Hard)
- **Start Date & Time:** When the trip begins
- **End Date & Time:** When the trip ends
- **Distance:** Trail distance in kilometers
- **Price:** Cost per participant
- **Capacity:** Maximum number of participants
- **Bus Assignment:** Which bus to use (optional)

**Optional Fields:**
- **Trip Image:** Upload cover photo
- **Special Requirements:** Any requirements for participants
- **Meeting Point:** Specific location details

3. Click **"Create Trip"**

### Editing Trips

1. Go to **"Manage Trips"** → **"View All"**
2. Find the trip to edit
3. Click **"Edit"** button
4. Modify desired fields
5. Click **"Save Changes"**

**Note:** Be careful editing active trips with confirmed participants.

### Trip Status Management

Each trip has a status:

**Prévue (Planned)**
- Trip is announced but not started
- Users can request participation
- Most common status for future trips

**En cours (In Progress)**
- Trip is currently happening
- No new requests accepted
- Document ongoing activity

**Terminée (Completed)**
- Trip has finished
- Users can still review and rate
- Archived from active listings

**Annulée (Cancelled)**
- Trip is cancelled
- Participants are notified
- Refunds processed if applicable

**Change Status:**
1. Edit trip
2. Click "Status" dropdown
3. Select new status
4. Save changes
5. Users are notified of status change

### Deleting Trips

1. Go to trip management
2. Click **"Delete"** button
3. Confirm deletion
4. Trip is permanently removed

⚠️ **Warning:** Deleting trips with participants may cause issues. Set status to "Cancelled" instead.

### Trip Templates

To speed up trip creation, common trips can be templated:

1. Create a fully configured trip
2. Click "Save as Template"
3. In new trip creation, select "Use Template"
4. Template pre-fills all common fields
5. Modify specific details
6. Save as new trip

---

## Bus Fleet Management

### Viewing Bus Fleet

1. Click **"Manage Buses"** → **"View Fleet"**
2. See all buses with:
   - License plate number
   - Passenger capacity
   - Driver name
   - Driver contact
   - Assignment status

### Adding a Bus

1. Click **"Manage Buses"** → **"Add Bus"**
2. Fill in bus details:
   - **License Plate:** Unique identifier (e.g., "TUN 123 456")
   - **Capacity:** Number of seats
   - **Driver Name:** Full name of driver
   - **Driver Phone:** Contact number
   - **Notes:** Any special features or restrictions

3. Click **"Add Bus"**

### Editing Bus Information

1. Select bus from fleet list
2. Click **"Edit"**
3. Update information:
   - Change capacity if adjusted
   - Update driver contact
   - Add maintenance notes
4. Click **"Save"**

### Assigning Buses to Trips

1. Go to trip management
2. Edit the trip
3. In bus assignment dropdown, select a bus
4. Click **"Save"**
5. Bus is now assigned and unavailable for conflicting dates

### Bus Maintenance Tracking

Keep notes on bus maintenance:
1. Edit bus record
2. In notes field, add maintenance info
3. Track service dates
4. Alert drivers of upcoming maintenance

### Deactivating/Archiving Buses

For buses no longer in service:
1. Edit bus
2. Click **"Deactivate"** or set status to inactive
3. Bus won't appear in new trip assignments
4. Existing trips keep current assignments

---

## Participation Management

### Reviewing Participation Requests

1. Click **"Manage Participations"**
2. See all pending requests with:
   - User name and email
   - Trip name and date
   - Request date
   - User background/reviews (if available)

### Filtering Requests

Use filters to organize:
- **Status:** Pending, Accepted, Rejected
- **Trip:** Filter by specific trip
- **Date Range:** Requests from specific period
- **User:** Search by user name/email

### Approving Requests

1. Review user and trip details
2. Verify:
   - Trip still has available capacity
   - No date conflicts with already-approved participants
   - User meets requirements
3. Click **"Approve"** or **"Accept"**
4. Status changes to "Acceptée"
5. User receives notification

### Rejecting Requests

1. Click **"Reject"** or **"Decline"**
2. Enter reason (optional):
   - "Trip at capacity"
   - "Date conflict detected"
   - "User restriction"
   - "Custom reason"
3. Click **"Confirm Rejection"**
4. User receives notification with reason

### Batch Operations

For efficiency with many requests:

1. Select multiple requests (checkboxes)
2. Choose bulk action:
   - "Approve All"
   - "Reject All"
   - "Change Status"
3. Confirm action
4. All selected updated at once

### Viewing Participant Lists

1. Select a trip
2. Click **"View Participants"**
3. See full list of approved participants:
   - Names and contact info
   - Check-in status
   - Any special requirements
4. Export as CSV if needed

---

## User Management

### Viewing Users

1. Click **"Manage Users"** or **"User List"**
2. See all registered users with:
   - User ID and name
   - Email address
   - Phone number
   - Registration date
   - Account status
   - Number of participations

### Searching Users

1. Use search bar to find by:
   - Email address
   - Full name
   - Phone number

2. Use filters:
   - Account status (Active, Inactive, Suspended)
   - Registration date range
   - Number of participations

### User Account Management

**View User Profile:**
1. Click user name or ID
2. See complete user information:
   - Personal details
   - Profile picture
   - Participation history
   - Ratings/reviews
   - Account creation date

**Edit User Information:**
1. Click **"Edit"** on user profile
2. Modify:
   - Name
   - Email
   - Phone
   - Role (upgrade to admin)
3. Click **"Save"**

**Reset User Password:**
1. Go to user profile
2. Click **"Reset Password"**
3. Generate temporary password
4. Send to user via email
5. User must change password on first login

**Suspend/Deactivate User:**
1. Click **"Deactivate Account"** or **"Suspend"**
2. Reason for suspension
3. User loses access to system
4. Can be reactivated later

### User Analytics

Track user behavior:
- Most active users
- Users with most approved trips
- Average participation rate
- User retention metrics
- Rating distribution

---

## Analytics & Reports

### Dashboard Metrics

**Revenue Tracking:**
- Total revenue from trip prices
- Revenue by trip/category
- Average revenue per trip
- Seasonal trends
- Revenue forecast

**Participation Metrics:**
- Total participation requests
- Approval rate (%)
- Average time to approval
- Most popular trips
- Participation trends

**User Metrics:**
- Total users
- New users (by period)
- User retention rate
- Active users
- User engagement level

### Generating Reports

1. Click **"Reports"** or **"Analytics"**
2. Select report type:
   - Revenue Report
   - Participation Report
   - User Statistics
   - Trip Performance
   - Capacity Analysis

3. Set date range
4. Choose format (HTML, PDF, CSV)
5. Click **"Generate"**

### Exporting Data

**Export to CSV:**
1. Select data (users, trips, participations)
2. Click **"Export"**
3. Choose format and fields
4. File downloads to computer

**CSV Fields Include:**
- User data (ID, name, email, phone, registration date)
- Trip data (ID, title, dates, price, capacity)
- Participation data (IDs, status, dates, timestamps)

### Tracking Trends

Monitor over time:
- Monthly new user registrations
- Participation rate trends
- Revenue growth
- Seasonal hiking patterns
- Most popular difficulty levels
- Most visited regions

---

## Settings & Configuration

### General Settings

1. Click **"Settings"** or **"Admin Settings"**
2. Configure:

**Site Information:**
- Site name
- Site URL
- Contact email
- Support email

**General Configuration:**
- Default currency
- Timezone
- Language
- Date format

### Email Configuration

Set up email notifications:

1. **SMTP Settings:**
   - SMTP host
   - SMTP port
   - SMTP username
   - SMTP password

2. **Email Templates:**
   - Participation approval emails
   - Rejection notifications
   - Password reset emails
   - New user welcome emails

3. **Email Triggers:**
   - Send confirmation when request accepted
   - Notify when capacity fills
   - Trip reminder emails
   - Admin notification for new requests

### Payment Settings

Configure payment processing (if applicable):

1. **Payment Gateway:**
   - Select provider (Stripe, PayPal, etc.)
   - API keys
   - Webhook URLs

2. **Payment Options:**
   - Enable/disable online payments
   - Payment methods accepted
   - Refund policies
   - Transaction fees

### Access Control

Manage admin permissions:

1. Create admin accounts
2. Set permission levels:
   - Full admin (all features)
   - Trip manager (manage trips only)
   - User manager (manage users)
   - Support staff (view only access)

3. Assign roles to admin users

---

## Maintenance Tasks

### Database Backups

Regular backups protect your data:

**Manual Backup:**
1. Use phpMyAdmin or MySQL command line
2. Export database to SQL file
3. Store in secure location
4. Download frequency: Weekly minimum

**Automated Backups:**
Set up cron job to backup automatically:
```bash
0 2 * * * mysqldump -u root -p database_name > /backups/backup.sql
```

### Clearing Upload Cache

If storage becomes an issue:

1. Go to **Settings** → **Storage**
2. View upload directory usage
3. Review old/unused images
4. Delete unnecessary files
5. Clear browser cache

### Checking System Health

Regularly verify:

**Database:**
- Run database integrity check
- Monitor database size
- Optimize tables periodically

**Files:**
- Check file permissions are correct
- Verify upload directories are writable
- Monitor disk space

**Logs:**
- Check error logs for issues
- Review access logs for suspicious activity
- Archive old logs

### Updating the System

When updates available:

1. **Backup everything** first
2. Download update files
3. Stop accepting requests (set maintenance mode if available)
4. Apply update
5. Test all functionality
6. Enable system again

### User & Data Cleanup

Periodically clean up:

1. **Delete inactive users:**
   - Users with no activity for 1+ years
   - Create archived record first
   - Retain data for compliance

2. **Archive old trips:**
   - Completed trips older than 1 year
   - Export data before archiving
   - Free up database space

3. **Remove orphaned data:**
   - Participation requests for deleted users
   - Incomplete or abandoned sessions
   - Temporary upload files

---

## Troubleshooting

### Common Admin Issues

**"Database connection failed"**
- Check db.php credentials
- Verify MySQL is running
- Confirm database exists
- Check user permissions

**"Permission denied" on file uploads**
- Check public/uploads directory permissions
- Ensure chmod 755 or 777
- Verify web server user owns directory

**"Email not sending"**
- Verify SMTP settings
- Check email address is valid
- Review email logs
- Test with admin email first

**"Trip showing wrong participants"**
- Clear browser cache
- Refresh database query
- Check for pending database updates
- Verify participation records

### Performance Issues

**Slow dashboard loading:**
- Check database size
- Archive old completed trips
- Optimize database tables
- Reduce query load

**Image uploads timing out:**
- Increase PHP upload limits (php.ini)
- Check file sizes
- Verify connection speed
- Try different browser

### Data Issues

**Missing participation records:**
- Check database backups
- Verify referential integrity
- Run database repair
- Contact support with details

**Duplicate user accounts:**
- Merge accounts manually
- Consolidate participation records
- Delete duplicate account
- Notify user of change

---

## Security Best Practices

### Admin Account Security

**Strong Passwords:**
- Minimum 12 characters
- Mix uppercase/lowercase
- Include numbers and special characters
- Don't use personal information
- Change every 60 days

**Login Security:**
- Use unique admin password
- Don't share credentials
- Logout when done
- Use HTTPS only
- Enable two-factor authentication if available

### Data Protection

**Backup Strategy:**
- Daily database backups
- Weekly file backups
- Store backups off-site
- Test restore procedure
- Document backup location

**Access Control:**
- Limit admin privileges by role
- Audit admin activities
- Disable unused admin accounts
- Monitor for suspicious activity
- Enforce strong passwords for all admins

### System Security

**PHP Configuration:**
```php
// Add to config/db.php or php.ini
error_reporting(E_ALL);
display_errors = 0;  // Don't display to users
log_errors = 1;      // Log to file
session.cookie_httponly = 1;
session.use_only_cookies = 1;
```

**File Permissions:**
```bash
# Directories: read, write, execute
chmod 755 /var/www/html/projet
chmod 755 public/
chmod 777 public/uploads/

# Sensitive files: read only for web server
chmod 644 config/db.php
```

**SQL Injection Prevention:**
- Always use prepared statements (done by default)
- Never concatenate user input into queries
- Validate all inputs server-side
- Use parameterized queries

### Regular Security Audits

Monthly tasks:
- [ ] Review admin access logs
- [ ] Check for unauthorized account creation
- [ ] Verify all passwords are strong
- [ ] Update PHP/MySQL if updates available
- [ ] Check file permissions are correct
- [ ] Review error logs for issues
- [ ] Test backup/restore process
- [ ] Scan for malware/unauthorized files

---

## Admin Checklist

### Daily
- [ ] Check pending participation requests
- [ ] Review new user registrations
- [ ] Monitor system health (database, disk space)
- [ ] Check for errors in logs
- [ ] Approve/reject pending requests

### Weekly
- [ ] Generate reports
- [ ] Archive completed trips
- [ ] Backup database
- [ ] Review user activity
- [ ] Update featured trips

### Monthly
- [ ] Review analytics and trends
- [ ] Update content/trip descriptions
- [ ] Clean up old files/uploads
- [ ] Security audit
- [ ] Performance optimization
- [ ] Plan marketing/promotions

### Quarterly
- [ ] Database optimization
- [ ] Major backup verification
- [ ] Policy review
- [ ] Feature updates
- [ ] User feedback review

### Annually
- [ ] Complete system audit
- [ ] Security penetration testing
- [ ] Database migration/upgrade
- [ ] Staff training/updates
- [ ] Strategic planning

---

## Additional Resources

### Useful Admin Commands

**Clear database cache:**
```sql
TRUNCATE TABLE sessions;
```

**Reset admin password:**
```sql
UPDATE user SET password = SHA1('newpassword') 
WHERE email = 'admin@randonnee.tn';
```

**Count statistics:**
```sql
SELECT 'Users' as Type, COUNT(*) as Count FROM user
UNION ALL
SELECT 'Trips', COUNT(*) FROM randonnee
UNION ALL
SELECT 'Participations', COUNT(*) FROM participation;
```

---

## Support & Escalation

When issues arise:

1. **Check documentation first**
2. **Review error logs** for specific errors
3. **Contact technical support** with:
   - Detailed error messages
   - Steps to reproduce
   - System information
   - Recent changes made
   - Screenshots if helpful

---

**You're all set to administer the system! Monitor regularly and keep it secure.** 🔐

For technical support: support@hiking-management.com
