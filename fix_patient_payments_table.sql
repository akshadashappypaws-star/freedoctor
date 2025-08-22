-- SQL script to add missing columns to patient_payments table

-- First check if table exists
SELECT * FROM information_schema.tables 
WHERE table_schema = DATABASE() AND table_name = 'patient_payments';

-- Check current table structure
DESCRIBE patient_payments;

-- Add type column if it doesn't exist
ALTER TABLE patient_payments 
ADD COLUMN IF NOT EXISTS `type` ENUM('deposit', 'withdrawal') DEFAULT 'deposit' AFTER `user_id`;

-- Add status column if it doesn't exist (modify if exists)
ALTER TABLE patient_payments 
MODIFY COLUMN `status` ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending';

-- Add razorpay_payout_id column if it doesn't exist
ALTER TABLE patient_payments 
ADD COLUMN IF NOT EXISTS `razorpay_payout_id` VARCHAR(255) NULL;

-- Add bank_details column if it doesn't exist
ALTER TABLE patient_payments 
ADD COLUMN IF NOT EXISTS `bank_details` JSON NULL;

-- Add processed_at column if it doesn't exist
ALTER TABLE patient_payments 
ADD COLUMN IF NOT EXISTS `processed_at` TIMESTAMP NULL;

-- Show final structure
DESCRIBE patient_payments;
