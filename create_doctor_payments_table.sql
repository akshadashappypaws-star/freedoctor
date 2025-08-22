-- Create doctor_payments table if it doesn't exist

USE laravel;

-- Create doctor_payments table
CREATE TABLE IF NOT EXISTS `doctor_payments` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `doctor_id` BIGINT UNSIGNED NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    `payment_id` VARCHAR(255) NULL,
    `order_id` VARCHAR(255) NULL,
    `payment_status` ENUM('pending', 'processing', 'completed', 'success', 'failed', 'cancelled') DEFAULT 'pending',
    `payment_details` JSON NULL,
    `payment_date` TIMESTAMP NULL,
    `receipt_number` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_doctor_id` (`doctor_id`),
    INDEX `idx_payment_status` (`payment_status`)
);

-- Show the table structure
DESCRIBE doctor_payments;

SELECT 'Doctor payments table created/updated successfully!' as Result;
