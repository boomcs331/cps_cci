-- =========================================================
-- Users / Roles / User_Role schema (MySQL / InnoDB / utf8mb4)
-- =========================================================
-- Safety first
SET
    NAMES utf8mb4;

SET
    FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS user_role;

DROP TABLE IF EXISTS roles;

DROP TABLE IF EXISTS users;

SET
    FOREIGN_KEY_CHECKS = 1;

-- -----------------------
-- Table: users
-- -----------------------
CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id VARCHAR(50) NOT NULL COMMENT 'รหัสพนักงาน/รหัสผู้ใช้ (ใช้ล็อกอิน/อ้างอิง)',
    name VARCHAR(100) NOT NULL COMMENT 'ชื่อ-สกุล',
    active TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=ใช้งาน,0=ปิดการใช้งาน',
    create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_user_id (user_id),
    KEY ix_users_active (active)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- -----------------------
-- Table: roles
-- -----------------------
CREATE TABLE roles (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL COMMENT 'ชื่อสิทธิ์ เช่น admin, user, pc',
    description VARCHAR(255) NULL,
    create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_roles_role_name (role_name)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- -----------------------
-- Table: user_role  (junction)
-- -----------------------
CREATE TABLE user_roles (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL COMMENT 'FK -> users.id',
    role_id INT UNSIGNED NOT NULL COMMENT 'FK -> roles.id',
    assigned_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_user_role_userid_roleid (user_id, role_id),
    -- กันซ้ำ
    KEY ix_user_role_user_id (user_id),
    KEY ix_user_role_role_id (role_id),
    CONSTRAINT fk_user_role_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_user_role_role FOREIGN KEY (role_id) REFERENCES roles(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- =========================================================
-- EXTRAS: ตัวอย่างใช้งาน (เอาออกได้หากไม่ต้องการ)
-- =========================================================
-- ตัวอย่างเพิ่มข้อมูล
-- INSERT INTO users (user_id, name, active) VALUES ('EMP001', 'สมชาย ใจดี', 1);
-- INSERT INTO roles (role_name, description) VALUES ('admin','ผู้ดูแลระบบ'), ('user','ผู้ใช้งานทั่วไป');
-- ผูกสิทธิ์
-- INSERT INTO user_role (user_id, role_id) VALUES (1, 1), (1, 2);
-- ตัวอย่างดึงข้อมูล User พร้อมรายชื่อ Role
-- SELECT u.id, u.user_id, u.name, u.active, GROUP_CONCAT(r.role_name ORDER BY r.role_name) AS roles
-- FROM users u
-- LEFT JOIN user_role ur ON ur.user_id = u.id
-- LEFT JOIN roles r ON r.id = ur.role_id
-- GROUP BY u.id;