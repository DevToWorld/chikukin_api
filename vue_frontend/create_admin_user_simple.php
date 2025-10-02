<?php
/**
 * Simple Admin User Creation Script
 * Run this script to create an admin user in your empty database
 * 
 * Usage: php create_admin_user_simple.php
 */

require_once 'laravel-backend/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Hash;

// Load Laravel configuration
$app = require_once 'laravel-backend/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Creating admin user...\n";
    
    // Create admin user in users table
    $user = \App\Models\User::updateOrCreate(
        ['email' => 'admin@yourdomain.com'],
        [
            'name' => 'Administrator',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'membership_type' => 'premium',
            'membership_expires_at' => now()->addYear(),
            'membership_features' => ['unlimited_access', 'priority_support', 'advanced_features'],
            'is_active' => true,
            'is_admin' => true,
        ]
    );
    
    echo "✅ User created in users table: {$user->email}\n";
    
    // Create admin user in admins table (if exists)
    if (class_exists('\App\Models\Admin')) {
        $admin = \App\Models\Admin::updateOrCreate(
            ['email' => 'admin@yourdomain.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('password'),
                'full_name' => 'System Administrator',
                'role' => 'super_admin',
                'is_active' => true,
                'failed_attempts' => 0,
                'mfa_enabled' => false,
            ]
        );
        
        echo "✅ Admin created in admins table: {$admin->email}\n";
    }
    
    echo "\n🎉 Admin user created successfully!\n";
    echo "================================\n";
    echo "Email: admin@yourdomain.com\n";
    echo "Password: password\n";
    echo "Role: Administrator\n";
    echo "================================\n";
    
} catch (Exception $e) {
    echo "❌ Error creating admin user: " . $e->getMessage() . "\n";
    echo "Make sure your database is properly configured in .env file\n";
    exit(1);
}
