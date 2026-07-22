## LO-Wallet (Learning Outcome Management System)

**Status:** Archived

*Disclaimer: This repository contains the source code for my university project. It was developed to meet specific academic requirements and is currently no longer actively maintained.*

## เกี่ยวกับโปรเจกต์ (About The Project)
LO-Wallet เป็นระบบสารสนเทศในรูปแบบเว็บแอปพลิเคชันที่พัฒนาขึ้นสำหรับสำนักวิทยบริการและเทคโนโลยีสารสนเทศ มหาวิทยาลัยราชภัฏภูเก็ต เพื่อใช้ในการบันทึกผลลัพธ์การเรียนรู้ (Learning Outcomes) ของนักศึกษาและบุคลากร
เป้าหมายหลักคือเพื่อให้นักศึกษามีระบบสำหรับติดตามประวัติการอบรมของตนเอง และสามารถนำใบรับรอง (e-Certificate) ที่ได้จากระบบไปใช้ประกอบการสมัครงานในอนาคต

## ฟีเจอร์หลัก (Key Features)
ระบบรองรับผู้ใช้งาน 3 ระดับ (Role-based Access Control):
*   **User (ผู้ใช้งานทั่วไป/นักศึกษา):** 
    *   ดูรายการคอร์ส และรายละเอียดคอร์สอบรมต่างๆ
    *   ลงทะเบียนเข้าร่วมคอร์ส
    *   ตรวจสอบประวัติคอร์สที่ลงทะเบียนแล้ว
    *   ดาวน์โหลดและจัดการ "Certificate ของฉัน"
    *   ตรวจสอบผลลัพธ์การเรียนรู้ผ่านเมนู "LO-Wallet"

*   **Instructor (ผู้จัดอบรม/วิทยากร):** 
    *   เพิ่มและแก้ไขข้อมูลคอร์สอบรม
    *   ดูรายชื่อผู้ลงทะเบียนทั้งหมด
    *   ดูรายชื่อผู้ผ่านการอบรม
    *   เพิ่มรายชื่อผู้ลงทะเบียน (ในกรณีลงทะเบียนล่าช้า)

*   **Officer (เจ้าหน้าที่สำนักวิทยบริการฯ):** 
    *   ดูรายชื่อผู้ลงทะเบียน และผู้ผ่านการอบรม
    *   ออกใบรับรองการอบรม (e-Certificate) ให้กับผู้ผ่านเกณฑ์
    *   จัดการลายเซ็นดิจิทัลสำหรับประทับลงในใบรับรอง
    *   เพิ่มรายชื่อผู้ลงทะเบียน (ในกรณีลงทะเบียนล่าช้า)

## เครื่องมือที่ใช้พัฒนา (Tech Stack)
*   **Backend:** PHP, Laravel Framework, Livewire
*   **Database:** MySQL
*   **Frontend:** HTML, CSS, JavaScript (Tailwind CSS)

## โครงสร้างฐานข้อมูล (Database)
ระบบมีการออกแบบ Database Schema ที่ซับซ้อนเพื่อรองรับเงื่อนไขของหลักสูตร เช่น:
*   การจัดการคณะ (Faculties), สาขาวิชา (Majors) และหลักสูตร (Programs)
*   การผูกความสัมพันธ์ระหว่าง หลักสูตร (Courses) และ ผลลัพธ์การเรียนรู้ (Learning Outcomes)
*   ระบบติดตามสถานะการเข้าร่วม (Enrollments) และการสำเร็จหลักสูตร (Course Completions)
