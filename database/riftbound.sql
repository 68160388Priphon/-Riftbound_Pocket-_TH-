-- Riftbound Pocket TH
-- ตารางผู้ดูแลระบบ
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'user') NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตารางการ์ด Riftbound
CREATE TABLE IF NOT EXISTS `cards` (
  `card_id` INT AUTO_INCREMENT PRIMARY KEY,
  `card_code` VARCHAR(20) DEFAULT NULL,
  `name_en` VARCHAR(100) NOT NULL,
  `name_th` VARCHAR(100) NOT NULL,
  `card_type` VARCHAR(50) NOT NULL,
  `cost` INT DEFAULT 0,
  `effect_en` TEXT,
  `effect_th` TEXT,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin เริ่มต้น: admin / admin123  (hash สร้างจาก password_hash() จริง)
-- ควรเปลี่ยนรหัสผ่านหลังล็อกอินครั้งแรก
INSERT INTO `users` (`username`, `password`, `role`)
VALUES ('admin', '$2y$10$s5eWAtXbkgltDuNPVkMzdO0WUGVkzr9xx0fwH7DLqG/aat4NQuOOG', 'admin')
ON DUPLICATE KEY UPDATE `password` = VALUES(`password`), `role` = VALUES(`role`);

-- ข้อมูลการ์ดตัวอย่าง (สำหรับทดสอบหน้าเว็บ แก้ไข/ลบได้)
INSERT INTO `cards` (`card_code`, `name_en`, `name_th`, `card_type`, `cost`, `effect_en`, `effect_th`)
VALUES
('SAMPLE-001', 'Sample Unit', 'ยูนิตตัวอย่าง', 'Unit', 3, 'Sample effect text.', 'ข้อความเอฟเฟกต์ตัวอย่าง'),
('SAMPLE-002', 'Sample Spell', 'สเปลตัวอย่าง', 'Spell', 2, 'Sample spell effect.', 'ข้อความเอฟเฟกต์สเปลตัวอย่าง');