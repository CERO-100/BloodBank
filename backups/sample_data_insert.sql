-- ============================================================================
-- Blood Bank Management System - Sample Random Data
-- ============================================================================
-- This script adds realistic random data for testing and demonstration
-- Run this AFTER importing blood_bank_complete.sql
-- ============================================================================

USE `blood`;

-- ============================================================================
-- Clear existing sample data (optional - comment out if you want to keep existing data)
-- ============================================================================

-- DELETE FROM donations WHERE donation_id > 6;
-- DELETE FROM requests WHERE request_id > 29;
-- DELETE FROM blood_stock WHERE stock_id > 14;
-- DELETE FROM notifications;
-- DELETE FROM users WHERE user_id > 21;

-- ============================================================================
-- Insert Random Users (Donors)
-- ============================================================================

INSERT INTO `users` (`name`, `email`, `location`, `password`, `role`, `phone`, `blood_group`, `status`, `district`, `dob`, `gender`) VALUES
('Rajesh Kumar', 'rajesh.kumar@gmail.com', 'MG Road, Bangalore', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543210', 'A+', 'approved', 'Bangalore Urban', '1990-05-15', 'Male'),
('Priya Sharma', 'priya.sharma@gmail.com', 'Connaught Place, Delhi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543211', 'B+', 'approved', 'New Delhi', '1992-08-22', 'Female'),
('Amit Patel', 'amit.patel@gmail.com', 'Satellite, Ahmedabad', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543212', 'O+', 'approved', 'Ahmedabad', '1988-12-10', 'Male'),
('Sneha Reddy', 'sneha.reddy@gmail.com', 'Banjara Hills, Hyderabad', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543213', 'AB+', 'approved', 'Hyderabad', '1995-03-18', 'Female'),
('Vikram Singh', 'vikram.singh@gmail.com', 'Civil Lines, Jaipur', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543214', 'A-', 'approved', 'Jaipur', '1991-07-25', 'Male'),
('Ananya Iyer', 'ananya.iyer@gmail.com', 'T Nagar, Chennai', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543215', 'B-', 'approved', 'Chennai', '1993-11-30', 'Female'),
('Arjun Mehta', 'arjun.mehta@gmail.com', 'Koramangala, Bangalore', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543216', 'O-', 'approved', 'Bangalore Urban', '1989-04-12', 'Male'),
('Deepika Nair', 'deepika.nair@gmail.com', 'Marine Drive, Kochi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543217', 'AB-', 'approved', 'Ernakulam', '1994-09-08', 'Female'),
('Karan Joshi', 'karan.joshi@gmail.com', 'Park Street, Kolkata', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543218', 'A+', 'approved', 'Kolkata', '1990-01-20', 'Male'),
('Ritu Verma', 'ritu.verma@gmail.com', 'Hazratganj, Lucknow', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543219', 'B+', 'approved', 'Lucknow', '1992-06-14', 'Female'),
('Siddharth Kapoor', 'siddharth.kapoor@gmail.com', 'Bandra, Mumbai', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543220', 'O+', 'approved', 'Mumbai', '1987-02-28', 'Male'),
('Neha Gupta', 'neha.gupta@gmail.com', 'Sector 17, Chandigarh', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543221', 'AB+', 'approved', 'Chandigarh', '1996-10-05', 'Female'),
('Rohit Malhotra', 'rohit.malhotra@gmail.com', 'Model Town, Ludhiana', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543222', 'A-', 'pending', 'Ludhiana', '1991-03-17', 'Male'),
('Kavya Reddy', 'kavya.reddy@gmail.com', 'Jubilee Hills, Hyderabad', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543223', 'B-', 'pending', 'Hyderabad', '1993-08-21', 'Female'),
('Aditya Rao', 'aditya.rao@gmail.com', 'Whitefield, Bangalore', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '9876543224', 'O-', 'approved', 'Bangalore Urban', '1989-12-03', 'Male');

-- ============================================================================
-- Insert Random Hospitals
-- ============================================================================

INSERT INTO `users` (`name`, `email`, `location`, `password`, `role`, `phone`, `status`, `district`, `latitude`, `longitude`) VALUES
('Apollo Hospital', 'apollo.bangalore@hospital.com', 'Bannerghatta Road, Bangalore', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital', '08012345678', 'approved', 'Bangalore Urban', 12.91230000, 77.59940000),
('Fortis Hospital', 'fortis.delhi@hospital.com', 'Vasant Kunj, Delhi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital', '01129876543', 'approved', 'New Delhi', 28.51950000, 77.15830000),
('Max Hospital', 'max.mumbai@hospital.com', 'Andheri West, Mumbai', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital', '02226754321', 'approved', 'Mumbai', 19.13570000, 72.83240000),
('Manipal Hospital', 'manipal.chennai@hospital.com', 'Anna Nagar, Chennai', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital', '04445678901', 'approved', 'Chennai', 13.08680000, 80.20920000),
('KIMS Hospital', 'kims.hyderabad@hospital.com', 'Secunderabad, Hyderabad', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital', '04067890123', 'approved', 'Hyderabad', 17.43840000, 78.49890000),
('AIIMS', 'aiims.delhi@hospital.com', 'Ansari Nagar, Delhi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital', '01126588500', 'approved', 'New Delhi', 28.56750000, 77.20940000),
('Ruby Hall Clinic', 'ruby.pune@hospital.com', 'Pune Station, Pune', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital', '02026163000', 'approved', 'Pune', 18.53200000, 73.85350000),
('Medanta Hospital', 'medanta.gurgaon@hospital.com', 'Sector 38, Gurgaon', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital', '01244141414', 'pending', 'Gurgaon', 28.43700000, 77.04620000),
('Columbia Asia', 'columbia.bangalore@hospital.com', 'Hebbal, Bangalore', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital', '08039898000', 'approved', 'Bangalore Urban', 13.03510000, 77.59760000),
('Narayana Health', 'narayana.kolkata@hospital.com', 'Salt Lake, Kolkata', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hospital', '03366453030', 'approved', 'Kolkata', 22.57260000, 88.36390000);

-- ============================================================================
-- Insert Blood Stock for Hospitals
-- Note: Get hospital IDs first, then add stock
-- ============================================================================

-- For Apollo Hospital Bangalore
INSERT INTO `blood_stock` (`hospital_id`, `blood_group`, `quantity`) VALUES
((SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'A+', 25),
((SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'A-', 12),
((SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'B+', 18),
((SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'B-', 8),
((SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'AB+', 10),
((SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'AB-', 5),
((SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'O+', 30),
((SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'O-', 15);

-- For Fortis Hospital Delhi
INSERT INTO `blood_stock` (`hospital_id`, `blood_group`, `quantity`) VALUES
((SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'A+', 20),
((SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'A-', 10),
((SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'B+', 22),
((SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'B-', 6),
((SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'AB+', 8),
((SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'AB-', 4),
((SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'O+', 28),
((SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'O-', 12);

-- For Max Hospital Mumbai
INSERT INTO `blood_stock` (`hospital_id`, `blood_group`, `quantity`) VALUES
((SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'A+', 15),
((SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'A-', 7),
((SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'B+', 20),
((SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'B-', 5),
((SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'AB+', 12),
((SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'AB-', 3),
((SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'O+', 25),
((SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'O-', 10);

-- For Manipal Hospital Chennai
INSERT INTO `blood_stock` (`hospital_id`, `blood_group`, `quantity`) VALUES
((SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'A+', 18),
((SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'A-', 9),
((SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'B+', 16),
((SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'B-', 7),
((SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'AB+', 11),
((SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'AB-', 4),
((SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'O+', 24),
((SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'O-', 13);

-- For KIMS Hospital Hyderabad
INSERT INTO `blood_stock` (`hospital_id`, `blood_group`, `quantity`) VALUES
((SELECT user_id FROM users WHERE email='kims.hyderabad@hospital.com'), 'A+', 22),
((SELECT user_id FROM users WHERE email='kims.hyderabad@hospital.com'), 'A-', 11),
((SELECT user_id FROM users WHERE email='kims.hyderabad@hospital.com'), 'B+', 19),
((SELECT user_id FROM users WHERE email='kims.hyderabad@hospital.com'), 'B-', 8),
((SELECT user_id FROM users WHERE email='kims.hyderabad@hospital.com'), 'AB+', 9),
((SELECT user_id FROM users WHERE email='kims.hyderabad@hospital.com'), 'AB-', 5),
((SELECT user_id FROM users WHERE email='kims.hyderabad@hospital.com'), 'O+', 27),
((SELECT user_id FROM users WHERE email='kims.hyderabad@hospital.com'), 'O-', 14);

-- For AIIMS Delhi
INSERT INTO `blood_stock` (`hospital_id`, `blood_group`, `quantity`) VALUES
((SELECT user_id FROM users WHERE email='aiims.delhi@hospital.com'), 'A+', 35),
((SELECT user_id FROM users WHERE email='aiims.delhi@hospital.com'), 'A-', 18),
((SELECT user_id FROM users WHERE email='aiims.delhi@hospital.com'), 'B+', 32),
((SELECT user_id FROM users WHERE email='aiims.delhi@hospital.com'), 'B-', 15),
((SELECT user_id FROM users WHERE email='aiims.delhi@hospital.com'), 'AB+', 20),
((SELECT user_id FROM users WHERE email='aiims.delhi@hospital.com'), 'AB-', 10),
((SELECT user_id FROM users WHERE email='aiims.delhi@hospital.com'), 'O+', 40),
((SELECT user_id FROM users WHERE email='aiims.delhi@hospital.com'), 'O-', 22);

-- For Ruby Hall Clinic Pune
INSERT INTO `blood_stock` (`hospital_id`, `blood_group`, `quantity`) VALUES
((SELECT user_id FROM users WHERE email='ruby.pune@hospital.com'), 'A+', 14),
((SELECT user_id FROM users WHERE email='ruby.pune@hospital.com'), 'A-', 6),
((SELECT user_id FROM users WHERE email='ruby.pune@hospital.com'), 'B+', 17),
((SELECT user_id FROM users WHERE email='ruby.pune@hospital.com'), 'B-', 4),
((SELECT user_id FROM users WHERE email='ruby.pune@hospital.com'), 'AB+', 8),
((SELECT user_id FROM users WHERE email='ruby.pune@hospital.com'), 'AB-', 3),
((SELECT user_id FROM users WHERE email='ruby.pune@hospital.com'), 'O+', 21),
((SELECT user_id FROM users WHERE email='ruby.pune@hospital.com'), 'O-', 9);

-- For Columbia Asia Bangalore
INSERT INTO `blood_stock` (`hospital_id`, `blood_group`, `quantity`) VALUES
((SELECT user_id FROM users WHERE email='columbia.bangalore@hospital.com'), 'A+', 16),
((SELECT user_id FROM users WHERE email='columbia.bangalore@hospital.com'), 'A-', 8),
((SELECT user_id FROM users WHERE email='columbia.bangalore@hospital.com'), 'B+', 19),
((SELECT user_id FROM users WHERE email='columbia.bangalore@hospital.com'), 'B-', 6),
((SELECT user_id FROM users WHERE email='columbia.bangalore@hospital.com'), 'AB+', 10),
((SELECT user_id FROM users WHERE email='columbia.bangalore@hospital.com'), 'AB-', 4),
((SELECT user_id FROM users WHERE email='columbia.bangalore@hospital.com'), 'O+', 23),
((SELECT user_id FROM users WHERE email='columbia.bangalore@hospital.com'), 'O-', 11);

-- For Narayana Health Kolkata
INSERT INTO `blood_stock` (`hospital_id`, `blood_group`, `quantity`) VALUES
((SELECT user_id FROM users WHERE email='narayana.kolkata@hospital.com'), 'A+', 21),
((SELECT user_id FROM users WHERE email='narayana.kolkata@hospital.com'), 'A-', 10),
((SELECT user_id FROM users WHERE email='narayana.kolkata@hospital.com'), 'B+', 18),
((SELECT user_id FROM users WHERE email='narayana.kolkata@hospital.com'), 'B-', 7),
((SELECT user_id FROM users WHERE email='narayana.kolkata@hospital.com'), 'AB+', 12),
((SELECT user_id FROM users WHERE email='narayana.kolkata@hospital.com'), 'AB-', 5),
((SELECT user_id FROM users WHERE email='narayana.kolkata@hospital.com'), 'O+', 26),
((SELECT user_id FROM users WHERE email='narayana.kolkata@hospital.com'), 'O-', 13);

-- ============================================================================
-- Insert Random Blood Requests
-- ============================================================================

INSERT INTO `requests` (`user_id`, `hospital_id`, `blood_group`, `quantity`, `status`, `created_at`) VALUES
((SELECT user_id FROM users WHERE email='rajesh.kumar@gmail.com'), (SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'A+', 2, 'approved', '2025-11-01 10:30:00'),
((SELECT user_id FROM users WHERE email='priya.sharma@gmail.com'), (SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'B+', 1, 'approved', '2025-11-02 14:15:00'),
((SELECT user_id FROM users WHERE email='amit.patel@gmail.com'), (SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'O+', 3, 'pending', '2025-11-03 09:45:00'),
((SELECT user_id FROM users WHERE email='sneha.reddy@gmail.com'), (SELECT user_id FROM users WHERE email='kims.hyderabad@hospital.com'), 'AB+', 1, 'approved', '2025-11-04 16:20:00'),
((SELECT user_id FROM users WHERE email='vikram.singh@gmail.com'), (SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'A-', 2, 'pending', '2025-11-05 11:00:00'),
((SELECT user_id FROM users WHERE email='ananya.iyer@gmail.com'), (SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'B-', 1, 'rejected', '2025-11-06 13:30:00'),
((SELECT user_id FROM users WHERE email='arjun.mehta@gmail.com'), (SELECT user_id FROM users WHERE email='columbia.bangalore@hospital.com'), 'O-', 2, 'approved', '2025-11-07 10:15:00'),
((SELECT user_id FROM users WHERE email='deepika.nair@gmail.com'), (SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'AB-', 1, 'pending', '2025-11-08 15:45:00'),
((SELECT user_id FROM users WHERE email='karan.joshi@gmail.com'), (SELECT user_id FROM users WHERE email='narayana.kolkata@hospital.com'), 'A+', 2, 'approved', '2025-11-09 09:00:00'),
((SELECT user_id FROM users WHERE email='ritu.verma@gmail.com'), (SELECT user_id FROM users WHERE email='aiims.delhi@hospital.com'), 'B+', 3, 'pending', '2025-11-10 14:30:00'),
((SELECT user_id FROM users WHERE email='siddharth.kapoor@gmail.com'), (SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'O+', 1, 'approved', '2025-11-11 11:20:00'),
((SELECT user_id FROM users WHERE email='neha.gupta@gmail.com'), (SELECT user_id FROM users WHERE email='ruby.pune@hospital.com'), 'AB+', 2, 'pending', '2025-11-12 10:00:00');

-- ============================================================================
-- Insert Random Blood Donations
-- ============================================================================

INSERT INTO `donations` (`user_id`, `hospital_id`, `blood_group`, `quantity`, `status`, `created_at`) VALUES
((SELECT user_id FROM users WHERE email='rajesh.kumar@gmail.com'), (SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'A+', 1, 'approved', '2025-11-01 09:00:00'),
((SELECT user_id FROM users WHERE email='priya.sharma@gmail.com'), (SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'B+', 1, 'approved', '2025-11-02 10:30:00'),
((SELECT user_id FROM users WHERE email='amit.patel@gmail.com'), (SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'O+', 1, 'pending', '2025-11-03 11:45:00'),
((SELECT user_id FROM users WHERE email='sneha.reddy@gmail.com'), (SELECT user_id FROM users WHERE email='kims.hyderabad@hospital.com'), 'AB+', 1, 'approved', '2025-11-04 14:20:00'),
((SELECT user_id FROM users WHERE email='vikram.singh@gmail.com'), (SELECT user_id FROM users WHERE email='aiims.delhi@hospital.com'), 'A-', 1, 'approved', '2025-11-05 09:15:00'),
((SELECT user_id FROM users WHERE email='ananya.iyer@gmail.com'), (SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'B-', 1, 'rejected', '2025-11-06 15:30:00'),
((SELECT user_id FROM users WHERE email='arjun.mehta@gmail.com'), (SELECT user_id FROM users WHERE email='columbia.bangalore@hospital.com'), 'O-', 1, 'approved', '2025-11-07 10:45:00'),
((SELECT user_id FROM users WHERE email='deepika.nair@gmail.com'), (SELECT user_id FROM users WHERE email='manipal.chennai@hospital.com'), 'AB-', 1, 'pending', '2025-11-08 13:00:00'),
((SELECT user_id FROM users WHERE email='karan.joshi@gmail.com'), (SELECT user_id FROM users WHERE email='narayana.kolkata@hospital.com'), 'A+', 1, 'approved', '2025-11-09 11:30:00'),
((SELECT user_id FROM users WHERE email='ritu.verma@gmail.com'), (SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'B+', 1, 'approved', '2025-11-10 09:45:00'),
((SELECT user_id FROM users WHERE email='siddharth.kapoor@gmail.com'), (SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'O+', 1, 'pending', '2025-11-11 14:15:00'),
((SELECT user_id FROM users WHERE email='neha.gupta@gmail.com'), (SELECT user_id FROM users WHERE email='ruby.pune@hospital.com'), 'AB+', 1, 'approved', '2025-11-12 10:20:00');

-- ============================================================================
-- Insert Random Notifications
-- ============================================================================

-- For Users
INSERT INTO `notifications` (`user_id`, `message`, `type`, `status`, `created_at`) VALUES
((SELECT user_id FROM users WHERE email='rajesh.kumar@gmail.com'), 'Your blood request for A+ (2 units) at Apollo Hospital has been approved.', 'request', 'unread', '2025-11-01 10:35:00'),
((SELECT user_id FROM users WHERE email='rajesh.kumar@gmail.com'), 'Thank you for your blood donation of A+ (1 unit). Your donation has been approved!', 'donation', 'read', '2025-11-01 09:05:00'),
((SELECT user_id FROM users WHERE email='priya.sharma@gmail.com'), 'Your blood request for B+ (1 unit) at Fortis Hospital has been approved.', 'request', 'read', '2025-11-02 14:20:00'),
((SELECT user_id FROM users WHERE email='amit.patel@gmail.com'), 'Your blood request for O+ (3 units) is pending review at Max Hospital.', 'request', 'unread', '2025-11-03 09:50:00'),
((SELECT user_id FROM users WHERE email='sneha.reddy@gmail.com'), 'Your blood donation of AB+ (1 unit) has been approved. Thank you for saving lives!', 'donation', 'unread', '2025-11-04 14:25:00'),
((SELECT user_id FROM users WHERE email='ananya.iyer@gmail.com'), 'Your blood donation request has been rejected. Please contact the hospital for details.', 'donation', 'read', '2025-11-06 15:35:00');

-- For Hospitals
INSERT INTO `notifications` (`user_id`, `message`, `type`, `status`, `created_at`) VALUES
((SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'New blood request from Rajesh Kumar for A+ (2 units). Contact: 9876543210', 'request', 'read', '2025-11-01 10:30:00'),
((SELECT user_id FROM users WHERE email='apollo.bangalore@hospital.com'), 'New blood donation from Rajesh Kumar (A+, 1 unit). Contact: 9876543210', 'donation', 'read', '2025-11-01 09:00:00'),
((SELECT user_id FROM users WHERE email='fortis.delhi@hospital.com'), 'New blood request from Priya Sharma for B+ (1 unit). Contact: 9876543211', 'request', 'read', '2025-11-02 14:15:00'),
((SELECT user_id FROM users WHERE email='max.mumbai@hospital.com'), 'New blood request from Amit Patel for O+ (3 units). Contact: 9876543212', 'request', 'unread', '2025-11-03 09:45:00'),
((SELECT user_id FROM users WHERE email='kims.hyderabad@hospital.com'), 'New blood donation from Sneha Reddy (AB+, 1 unit). Contact: 9876543213', 'donation', 'unread', '2025-11-04 14:20:00'),
((SELECT user_id FROM users WHERE email='aiims.delhi@hospital.com'), 'New blood donation from Vikram Singh (A-, 1 unit). Contact: 9876543214', 'donation', 'unread', '2025-11-05 09:15:00');

-- ============================================================================
-- Summary Report
-- ============================================================================

SELECT 'SAMPLE DATA INSERTION COMPLETE!' AS status;

SELECT 'Total Users (Donors):' AS metric, COUNT(*) AS count FROM users WHERE role='user';
SELECT 'Total Hospitals:' AS metric, COUNT(*) AS count FROM users WHERE role='hospital';
SELECT 'Total Blood Stock Entries:' AS metric, COUNT(*) AS count FROM blood_stock;
SELECT 'Total Blood Requests:' AS metric, COUNT(*) AS count FROM requests;
SELECT 'Total Blood Donations:' AS metric, COUNT(*) AS count FROM donations;
SELECT 'Total Notifications:' AS metric, COUNT(*) AS count FROM notifications;

SELECT '================================================' AS separator;
SELECT 'Blood Stock Summary by Blood Group:' AS info;
SELECT blood_group, SUM(quantity) AS total_units FROM blood_stock GROUP BY blood_group ORDER BY blood_group;

SELECT '================================================' AS separator;
SELECT 'Request Status Summary:' AS info;
SELECT status, COUNT(*) AS count FROM requests GROUP BY status;

SELECT '================================================' AS separator;
SELECT 'Donation Status Summary:' AS info;
SELECT status, COUNT(*) AS count FROM donations GROUP BY status;

-- ============================================================================
-- END OF SCRIPT
-- ============================================================================
