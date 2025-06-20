# ExpenseMate 💰

**A comprehensive personal finance management application built with Laravel**

ExpenseMate is a modern, intuitive web application designed to help individuals and businesses track expenses, manage income, and gain valuable insights into their financial habits. Built with Laravel 11 and styled with Tailwind CSS, it offers a clean, responsive interface for effective financial management.

## ✨ Features

### 📊 Financial Tracking

-   **Income & Expense Management**: Track all your financial transactions with detailed categorization
-   **Category System**: Pre-defined categories with the ability to create custom categories
-   **Real-time Analytics**: Comprehensive dashboard with charts and insights
-   **Date Range Filtering**: Analyze your finances over custom time periods

### 📈 Analytics & Reporting

-   **Visual Charts**: Interactive pie charts, bar charts, and trend analysis
-   **Category Breakdown**: Detailed analysis of spending patterns by category
-   **Daily/Monthly Trends**: Track your financial patterns over time
-   **Export Capabilities**: Export data in CSV, Excel, and PDF formats

### 🔐 Security & User Management

-   **User Authentication**: Secure login and registration system
-   **Personal Data**: Each user's financial data is completely private
-   **Profile Management**: Update personal information and account settings
-   **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices

## 🚀 Technology Stack

-   **Backend**: Laravel 12.x
-   **Frontend**: Blade Templates + Tailwind CSS
-   **Database**: SQLite (configurable for MySQL/PostgreSQL)
-   **Charts**: Chart.js for data visualization
-   **Authentication**: Laravel Breeze
-   **Build Tools**: Vite for asset compilation

## 📋 Requirements

-   PHP 8.2 or higher
-   Composer
-   Node.js & NPM
-   SQLite/MySQL/PostgreSQL

## 🛠️ Installation

1. **Clone the repository**

    ```bash
    git clone https://github.com/Tertho1/expensemate.git
    cd expensemate
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install JavaScript dependencies**

    ```bash
    npm install
    ```

4. **Environment setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Configure your database**

    Edit your `.env` file with your database credentials:

    ```env
    DB_CONNECTION=sqlite
    DB_DATABASE=/path/to/database/database.sqlite
    ```
    You can use mysql also

6. **Run database migrations and seeders**

    ```bash
    php artisan migrate:fresh --seed
    ```

7. **Build assets**

    ```bash
    npm run build
    ```

8. **Start the development server**
    ```bash
    php artisan serve
    ```

Visit `http://localhost:8000` to access the application.

## 📖 Usage

### Getting Started

1. **Register** a new account or **login** if you already have one
2. **Add Categories** (optional) - The system comes with pre-defined categories
3. **Record Transactions** - Add your income and expenses
4. **View Analytics** - Check your financial insights on the dashboard

### Key Pages

-   **Dashboard**: Overview of your financial status with quick stats
-   **Transactions**: List, add, edit, and delete financial transactions
-   **Analytics**: Detailed charts and financial insights
-   **Categories**: Manage your transaction categories
-   **Export**: Generate reports in various formats

## 🗂️ Project Structure

```
expensemate/
├── app/
│   ├── Http/Controllers/
│   │   ├── AnalyticsController.php
│   │   ├── CategoryController.php
│   │   ├── DashboardController.php
│   │   └── TransactionController.php
│   └── Models/
│       ├── Category.php
│       ├── Transaction.php
│       └── User.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── analytics.blade.php
│   │   ├── dashboard.blade.php
│   │   └── transactions/
│   └── css/
└── routes/
    └── web.php
```

## 🎨 Features Overview

### Dashboard

-   Quick financial overview
-   Recent transactions
-   Monthly summaries
-   Balance tracking

### Transaction Management

-   Add income and expenses
-   Categorize transactions
-   Edit and delete entries
-   Search and filter capabilities

### Analytics

-   Income vs Expenses charts
-   Category-wise breakdowns
-   Daily and monthly trends
-   Custom date range analysis

### Data Export

-   **CSV**: For spreadsheet applications
-   **Excel**: Professional formatted reports
-   **PDF**: Print-ready financial statements

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 🐛 Bug Reports & Feature Requests

If you encounter any bugs or have feature requests, please create an issue on GitHub with:

-   Clear description of the problem/feature
-   Steps to reproduce (for bugs)
-   Expected vs actual behavior
-   Screenshots (if applicable)

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🙏 Acknowledgments

-   Laravel Framework team for the excellent PHP framework
-   Tailwind CSS for the utility-first CSS framework
-   Chart.js for beautiful data visualizations
-   The open-source community for inspiration and support

## 📞 Support

-   **Email**: support@expensemate.com
-   **Phone**: +880 123 456 789
-   **Documentation**: [Link to detailed docs]
-   **GitHub Issues**: [Repository Issues Page]

---

**Built with ❤️ using Laravel & Tailwind CSS**

_Take control of your financial future with ExpenseMate!_
