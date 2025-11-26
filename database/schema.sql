-- Community Donation Database Schema
-- Drop existing tables if they exist
DROP TABLE IF EXISTS DISTRIBUTION;
DROP TABLE IF EXISTS AID_REQUEST;
DROP TABLE IF EXISTS DONATION;
DROP TABLE IF EXISTS Aid_Seeker;
DROP TABLE IF EXISTS VOLUNTEERS;
DROP TABLE IF EXISTS WAREHOUSE;
DROP TABLE IF EXISTS DONOR;

-- Create DONOR table
CREATE TABLE DONOR (
    Donor_ID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE,
    Donor_Type ENUM('Individual', 'Organization', 'Anonymous') DEFAULT 'Individual',
    Phone VARCHAR(50),
    Address TEXT,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create WAREHOUSE table
CREATE TABLE WAREHOUSE (
    Item_ID INT PRIMARY KEY AUTO_INCREMENT,
    Item_Name VARCHAR(255) UNIQUE NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    CHECK (quantity >= 0)
);

-- Create VOLUNTEERS table
CREATE TABLE VOLUNTEERS (
    Volunteer_ID INT PRIMARY KEY AUTO_INCREMENT,
    Vol_Name VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE,
    Phone VARCHAR(50),
    Gender ENUM('Male', 'Female'),
    Area_Assigned VARCHAR(100),
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Aid_Seeker table
CREATE TABLE Aid_Seeker (
    Seeker_ID INT(11) PRIMARY KEY AUTO_INCREMENT,
    Seeker_Name VARCHAR(255) NOT NULL,
    National_ID VARCHAR(20) NOT NULL,
    Monthly_Income INT(11) NOT NULL,
    Family_Members INT(11) NOT NULL,
    Phone VARCHAR(20) NOT NULL,
    Email VARCHAR(50),
    Reason TEXT,
    District VARCHAR(20) NOT NULL,
    Thana VARCHAR(20) NOT NULL,
    Post_No VARCHAR(20) NOT NULL,
    Village VARCHAR(20),
    Street VARCHAR(20),
    House VARCHAR(20),
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create DONATION table
CREATE TABLE DONATION (
    Donation_ID INT PRIMARY KEY AUTO_INCREMENT,
    Donor_ID INT NOT NULL,
    Item_ID INT NOT NULL,
    Date_Donated DATE NOT NULL,
    Quantity_Donated INT NOT NULL,
    Payment_Method ENUM('Test Donation', 'Offline Donation') DEFAULT 'Offline Donation',
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (Donor_ID) REFERENCES DONOR(Donor_ID) ON DELETE CASCADE,
    FOREIGN KEY (Item_ID) REFERENCES WAREHOUSE(Item_ID) ON DELETE CASCADE
);

-- Create AID_REQUEST table
CREATE TABLE AID_REQUEST (
    Request_ID INT NOT NULL AUTO_INCREMENT,
    Item_ID INT NOT NULL,
    Seeker_ID INT NOT NULL,
    Date DATE NOT NULL,
    Reason TEXT,
    Quantity_Requested INT NOT NULL,
    Status ENUM('Pending', 'Approved', 'Rejected', 'Fulfilled') DEFAULT 'Pending',
    
    PRIMARY KEY (Request_ID, Item_ID),
    
    FOREIGN KEY (Seeker_ID) REFERENCES Aid_Seeker(Seeker_ID) ON DELETE CASCADE,
    FOREIGN KEY (Item_ID) REFERENCES WAREHOUSE(Item_ID) ON DELETE CASCADE
);

-- Create DISTRIBUTION table
CREATE TABLE DISTRIBUTION (
    Distribution_ID INT PRIMARY KEY AUTO_INCREMENT,
    Volunteer_ID INT NOT NULL,
    Item_ID INT NOT NULL,
    Seeker_ID INT NOT NULL,
    Dis_Date DATE NOT NULL,
    Dis_Area VARCHAR(100),
    Quantity INT NOT NULL,
    
    FOREIGN KEY (Volunteer_ID) REFERENCES VOLUNTEERS(Volunteer_ID) ON DELETE CASCADE,
    FOREIGN KEY (Item_ID) REFERENCES WAREHOUSE(Item_ID) ON DELETE CASCADE,
    FOREIGN KEY (Seeker_ID) REFERENCES Aid_Seeker(Seeker_ID) ON DELETE CASCADE
);

-- Insert sample data for WAREHOUSE items
INSERT INTO WAREHOUSE (Item_Name, quantity) VALUES
('Food Items', 1000),
('Clothing', 500),
('Medicine', 300),
('Books', 200),
('Water', 800);

-- Insert sample volunteer
INSERT INTO VOLUNTEERS (Vol_Name, Email, Phone, Gender, Area_Assigned) VALUES
('Sarah J. Anderson', 'sarah@warmhands.org', '555-0100', 'Female', 'Downtown District');

-- ==========================================
-- VIEWS FOR USER DATA ISOLATION (DBMS Project Requirement)
-- ==========================================

-- View 1: User Donation History
-- Users can only see their own donations
CREATE OR REPLACE VIEW user_donation_history AS
SELECT 
    d.Donation_ID,
    d.Date_Donated,
    d.Quantity_Donated,
    d.Payment_Method,
    w.Item_Name,
    don.Donor_ID,
    u.id as user_id
FROM DONATION d
JOIN WAREHOUSE w ON d.Item_ID = w.Item_ID
JOIN DONOR don ON d.Donor_ID = don.Donor_ID
JOIN users u ON don.Email = u.email
ORDER BY d.Date_Donated DESC;

-- View 2: User Volunteer Activities
-- Users can only see their own volunteer assignments
CREATE OR REPLACE VIEW user_volunteer_activities AS
SELECT 
    va.Assignment_ID,
    va.Donation_ID,
    va.Status,
    va.Assigned_Date,
    va.Completed_Date,
    va.Notes,
    d.Date_Donated,
    d.Quantity_Donated,
    w.Item_Name,
    don.Name as Donor_Name,
    don.Phone as Donor_Phone,
    don.Address as Pickup_Address,
    v.Volunteer_ID,
    u.id as user_id
FROM volunteer_assignments va
JOIN DONATION d ON va.Donation_ID = d.Donation_ID
JOIN WAREHOUSE w ON d.Item_ID = w.Item_ID
JOIN DONOR don ON d.Donor_ID = don.Donor_ID
JOIN VOLUNTEERS v ON va.Volunteer_ID = v.Volunteer_ID
JOIN users u ON v.Email = u.email
ORDER BY va.Assigned_Date DESC;

-- View 3: User Aid Requests
-- Users can only see their own aid seeker applications
CREATE OR REPLACE VIEW user_aid_requests AS
SELECT 
    a.Seeker_ID,
    a.Seeker_Name,
    a.National_ID,
    a.Phone,
    a.Monthly_Income,
    a.Family_Members,
    a.Reason,
    a.House,
    a.Street,
    a.Village,
    a.Thana,
    a.District,
    a.Status,
    a.Created_At,
    u.id as user_id
FROM Aid_Seeker a
JOIN users u ON a.Email = u.email
ORDER BY a.Created_At DESC;

-- View 4: Available Pickup Tasks
-- Users see unassigned pickup donations with limited donor information
CREATE OR REPLACE VIEW available_pickup_tasks AS
SELECT 
    d.Donation_ID,
    d.Date_Donated,
    d.Quantity_Donated,
    w.Item_Name,
    don.Phone as Contact_Phone,
    don.Address as Pickup_Address,
    COALESCE(don.Address, 'Not specified') as Location_Info
FROM DONATION d
JOIN WAREHOUSE w ON d.Item_ID = w.Item_ID
JOIN DONOR don ON d.Donor_ID = don.Donor_ID
LEFT JOIN volunteer_assignments va ON d.Donation_ID = va.Donation_ID
WHERE d.Payment_Method = 'Pickup from Home'
AND va.Assignment_ID IS NULL
ORDER BY d.Date_Donated ASC;
