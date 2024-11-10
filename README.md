
# Laravel E-Commerce API

This project is a Laravel-based API for an e-commerce platform. It supports user authentication, product management, role-based access control, and includes features for optimizing query performance.

## Table of Contents
1. [Installation](#installation)
2. [Authentication](#authentication)
3. [Product Management](#product-management)
4. [Role-Based Access Control](#role-based-access-control)
5. [Database Optimization](#database-optimization)

## 1. Installation

### Prerequisites
- PHP >= 8.1
- Composer
- Laravel 10.x
- MySQL

### Setup
1. Clone the repository:
   ```bash
   git clone https://github.com/sakir-console/ecommerce-api.git
   cd ecommerce-api
   php artisan serve
   ```



## 2. Authentication

The API uses **Laravel Sanctum** for token-based authentication. Users must register (sign up) and log in (sign in) to obtain a token for accessing protected routes.

### User Authentication
Deployed: [https://test.foodcity.com.bd](https://test.foodcity.com.bd)
#### 1. Sign Up
- **Endpoint:** `POST /api/v1/signup`
- **Request Payload:**
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }
  ```
- **Response:**
  ```json
  {
    "result": true,
    "message": "Registration successful",
    "data": {
      "token": "6|KyGVaxF1tyHETiGlONQ9mPielR9mLDsEaByXUlTC47d42bd9"
    }
  }
  ```

#### 2. Sign In
- **Endpoint:** `POST /api/v1/signin`
- **Request Payload:**
  ```json
  {
    "email": "sakirislam7@gmail.com",
    "password": "112233"
  }
  ```
- **Response:**
  ```json
  {
    "result": true,
    "message": "Admin Login successful",
    "data": {
      "token": "7|WW4DBTM3lDW8vcIoo30N4nC4QRF4T4Ye3LXpz1h5dfdd4d2a"
    }
  }
  ```

## 3. Product Management

All product management endpoints require a Bearer token obtained from the sign-in endpoint.

### Headers:
```http
Authorization: Bearer your-api-token
```

### 1. Get All Products
- **Endpoint:** `GET /api/v1/products`
- **Response:**
  ```json
  [
    {
      "id": 1,
      "name": "Orange fruit",
      "description": "Healthy fruit",
      "price": "340.00",
      "sales_count": 2,
      "created_at": "2024-11-10T16:05:13.000000Z",
      "updated_at": "2024-11-10T16:12:00.000000Z"
    }
  ]
  ```

### 2. Add a New Product (Admin Only)
- **Endpoint:** `POST /api/v1/product/add`
- **Request Payload:**
  ```json
  {
    "name": "New Product",
    "description": "Description of the new product",
    "price": 99.99,
    "stock": 50
  }
  ```
- **Response:**
  ```json
  {
    "result": true,
    "message": "Product added successfully",
    "data": []
  }
  ```

### 3. Show Product Details
- **Endpoint:** `GET /api/v1/product-show/{id}`
- **Response:**
  ```json
  {
    "result": true,
    "message": "Product",
    "data": {
      "id": 3,
      "name": "Banana",
      "description": "Very Healthy fruit",
      "price": "240.00",
      "sales_count": 4
    }
  }
  ```

### 4. Get Top 5 Products
- **Endpoint:** `GET /api/v1/product-top`
- **Response:**
  ```json
  {
    "result": true,
    "message": "Top sold 5 products",
    "data": [
      {
        "id": 3,
        "name": "Banana",
        "description": "Very Healthy fruit",
        "price": "240.00",
        "sales_count": 4
      }
    ]
  }
  ```

### 5. Update Product (Admin & Editor Only)
- **Endpoint:** `POST /api/v1/product-update/{id}`
- **Request Payload:**
  ```json
  {
    "name": "Orange fruit",
    "description": "Healthy fruit",
    "price": 340,
    "stock": 100
  }
  ```
- **Response:**
  ```json
  {
    "result": true,
    "message": "Product updated successfully",
    "data": []
  }
  ```

### 6. Delete a Product (Admin Only)
- **Endpoint:** `POST /api/v1/product-delete/{id}`
- **Response:**
  ```json
  {
    "result": true,
    "message": "Product deleted successfully"
  }
  ```

## 4. Role-Based Access Control

The application defines two roles:
- **Admin:** Full access to all product CRUD operations. (`role_id` = 2)
- **Editor:** Can only update product details. (`role_id` = 3)

## 5. Database Optimization

Indexes are added to frequently queried columns to improve performance.

```php
Schema::table('products', function (Blueprint $table) {
    $table->index('sales_count');
});
```

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Thank You
For any queries or contributions, feel free to open an issue or submit a pull request.
