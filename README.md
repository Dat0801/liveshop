# LiveShop - E-commerce Platform

A modern e-commerce platform built with Laravel 10+, Livewire, AlpineJS, and TailwindCSS.

## Features

### Customer Features
- **Product Listing** with search, filter, sort, and pagination
- **Product Detail Page** with variants (size, color) and real-time price updates
- **Shopping Cart** with add/update/remove items without page reload
- **Checkout Form** with real-time validation
- **Order Management**

### Admin Features
- **Dashboard** with statistics and insights
- **Product Management** - Create, edit, delete products
- **Order Management** - View and update order status

## Technology Stack

- **Backend**: Laravel 10+
- **Frontend**: Livewire (for SPA-like experience)
- **UI Interactions**: AlpineJS
- **Styling**: TailwindCSS
- **Database**: MySQL

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL

### Setup Instructions

1. **Install PHP dependencies**
```bash
composer install
```

2. **Install Node dependencies**
```bash
npm install
```

3. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Database**
Edit `.env` file and set your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=liveshop
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Create Database**
```bash
mysql -u root -p
CREATE DATABASE liveshop;
exit;
```

6. **Run Migrations**
```bash
php artisan migrate
```

7. **Seed Database** (Optional - adds sample data)
```bash
php artisan db:seed
```

8. **Build Assets**
```bash
npm run build
# Or for development with hot reload:
npm run dev
```

9. **Start Development Server**
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Project Structure

```
liveshop/
├── app/
│   ├── Livewire/              # Livewire components
│   │   ├── ProductList.php
│   │   ├── ProductDetail.php
│   │   ├── ShoppingCart.php
│   │   ├── Checkout.php
│   │   └── Admin/
│   │       ├── Dashboard.php
│   │       ├── ProductManagement.php
│   │       └── OrderManagement.php
│   └── Models/                # Eloquent models
│       ├── Product.php
│       ├── Category.php
│       ├── ProductVariant.php
│       ├── Cart.php
│       ├── CartItem.php
│       ├── Order.php
│       └── OrderItem.php
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   ├── views/
│   │   ├── components/
│   │   │   └── layouts/      # Layout components
│   │   └── livewire/         # Livewire views
│   ├── css/
│   │   └── app.css           # TailwindCSS
│   └── js/
│       └── app.js            # AlpineJS
└── routes/
    └── web.php               # Application routes
```

## Database Schema

### Categories
- id, name, slug, description, image, is_active, timestamps

### Products
- id, category_id, name, slug, description, short_description
- base_price, discount_price, sku, stock_quantity
- images (JSON), is_active, is_featured, timestamps, soft_deletes

### Product Variants
- id, product_id, type, value, price_adjustment, stock_quantity, sku, timestamps

### Carts & Cart Items
- Carts: id, user_id, session_id, timestamps
- Cart Items: id, cart_id, product_id, variants (JSON), quantity, price, timestamps

### Orders & Order Items
- Orders: id, user_id, order_number, status, totals, billing/shipping info, timestamps
- Order Items: id, order_id, product details, variants (JSON), quantity, price, timestamps

## Usage

### Customer Flow
1. Browse products on the homepage
2. Filter/search/sort products
3. View product details and select variants
4. Add products to cart
5. Review cart and proceed to checkout
6. Fill in billing/shipping information
7. Place order and receive confirmation

### Admin Flow
1. Access admin dashboard at `/admin/dashboard`
2. Manage products at `/admin/products`
3. Manage orders at `/admin/orders`
4. View statistics and insights

## Routes

### Public Routes
- `/` - Homepage (redirects to products)
- `/products` - Product listing
- `/products/{slug}` - Product detail
- `/checkout` - Checkout form
- `/order/success/{id}` - Order confirmation

### Admin Routes
- `/admin/dashboard` - Admin dashboard
- `/admin/products` - Product management
- `/admin/orders` - Order management

## Key Features

### Livewire Components
- SPA-like experience without page reloads
- Real-time cart updates
- Live search and filtering
- Real-time form validation

### AlpineJS Usage
- Modal dialogs
- Dropdown menus
- Cart sidebar
- UI animations

### TailwindCSS
- Responsive design
- Custom component classes
- Consistent styling

## License

Open-sourced software.
