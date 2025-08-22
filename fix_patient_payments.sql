-- Fix patient_payments table structure

USE laravel;

-- Show current table structure
DESCRIBE patient_payments;

-- Add type column if it doesn't exist
ALTER TABLE patient_payments 
ADD COLUMN IF NOT EXISTS `type` ENUM('deposit', 'withdrawal') DEFAULT 'deposit' AFTER `user_id`;

-- Add razorpay_payout_id column if it doesn't exist
ALTER TABLE patient_payments 
ADD COLUMN IF NOT EXISTS `razorpay_payout_id` VARCHAR(255) NULL;

-- Add bank_details column if it doesn't exist
ALTER TABLE patient_payments 
ADD COLUMN IF NOT EXISTS `bank_details` JSON NULL;

-- Add processed_at column if it doesn't exist
ALTER TABLE patient_payments 
ADD COLUMN IF NOT EXISTS `processed_at` TIMESTAMP NULL;

-- Update status column to include all required values
ALTER TABLE patient_payments 
MODIFY COLUMN `status` ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending';

-- Show final table structure
DESCRIBE patient_payments;

SELECT 'Patient payments table structure updated successfully!' as Result;
