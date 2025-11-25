# Community Donation System

A complete PHP-based community donation management system with MySQL database backend.

## Features

- **Donor Management**: Register and manage donor information
- **Donation Processing**: Accept and track donations with different payment methods
- **Warehouse Inventory**: Track available items and quantities
- **Volunteer Management**: Manage volunteers and their assigned areas
- **Aid Seeker Registration**: Register individuals/families seeking assistance
- **Distribution Tracking**: Track distribution of items to aid seekers
- **Responsive UI**: Clean, modern interface matching the provided design

## Database Tables

- **DONOR**: Stores donor information
- **WAREHOUSE**: Manages inventory of donation items
- **VOLUNTEERS**: Tracks volunteer information
- **Aid_Seeker**: Stores information about people seeking assistance
- **DONATION**: Records all donations received
- **AID_REQUEST**: Tracks aid requests from seekers
- **DISTRIBUTION**: Logs distribution of items to aid seekers

## Installation

1. **Setup Database**:
   - Create a MySQL database named `community_donation`
   - Import the schema: `database/schema.sql`

2. **Configure Database Connection**:
   - Edit `config/config.php`
   - Update database credentials if needed:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'community_donation');
     ```

3. **Update Site URL**:
   - In `config/config.php`, update the `SITE_URL` to match your local setup:
     ```php
     define('SITE_URL', 'http://localhost/Community Donation');
     ```

4. **Start Your Server**:
   - Using XAMPP/WAMP: Place the project in `htdocs` folder
   - Using PHP built-in server: `php -S localhost:8000 -t .`

5. **Access the Application**:
   - Open browser and navigate to: `http://localhost/Community Donation/`

## Project Structure

```
Community Donation/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   └── images/
├── config/
│   └── config.php
├── database/
│   └── schema.sql
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── process_donation.php
│   ├── process_contact.php
│   └── subscribe.php
├── pages/
│   ├── about.php
│   ├── cart.php
│   ├── contact.php
│   ├── donate.php
│   ├── donation.php
│   ├── donation_success.php
│   ├── events.php
│   ├── news.php
│   ├── pages.php
│   ├── shop.php
│   ├── volunteers.php
│   └── warehouse.php
├── index.php
└── README.md
```

## Main Pages

- **Home** (`index.php`): Dashboard with statistics and recent donations
- **Donate Now** (`pages/donate.php`): Main donation form matching the UI design
- **Donation History** (`pages/donation.php`): View all donations
- **About Us** (`pages/about.php`): Information about the organization
- **Contact** (`pages/contact.php`): Contact form
- **Volunteers** (`pages/volunteers.php`): List of volunteers
- **Warehouse** (`pages/warehouse.php`): Current inventory
- **Events** (`pages/events.php`): Upcoming events

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Icons**: Font Awesome 6.4.0

## Key Features

### Donation Form
- Preset donation amounts ($25, $50, $75, $99, $200)
- Custom amount option
- Item selection from warehouse inventory
- Payment method selection (Test/Offline)
- Donor information collection
- Real-time inventory updates

### Database Integration
- Transaction-based donation processing
- Automatic inventory updates
- Donor deduplication by email
- Foreign key relationships maintained

### UI Design
- Purple and gold color scheme matching provided design
- Responsive layout
- Clean, modern interface
- Interactive form elements
- Progress bars and statistics

## Usage

### Making a Donation:
1. Navigate to "Donate Now"
2. Select donation amount or enter custom amount
3. Choose item to donate from dropdown
4. Select payment method
5. Fill in personal information
6. Submit donation

### Viewing Donations:
1. Go to "Donation History" page
2. View all donations in a table format
3. See donor details, items, quantities, and dates

### Managing Inventory:
1. Visit "Warehouse" page
2. View current stock levels for all items
3. Quantities automatically update with donations

## License

© 2023 Clean Hearts - All Rights Reserved

## Support

For questions or support, contact: info.cleanhearts@gmail.com
