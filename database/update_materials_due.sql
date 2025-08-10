-- Add 'due' field to existing materials table
-- Run this script to update existing database

-- Add due column with default value 2
ALTER TABLE materials ADD COLUMN due INT DEFAULT 2 AFTER min_qty;

-- Update existing records to have due = 2 (if you want to change the default)
-- UPDATE materials SET due = 2 WHERE due IS NULL;

-- Verify the change
-- DESCRIBE materials;
-- SELECT mat_id, mat_name, min_qty, due FROM materials LIMIT 5;
