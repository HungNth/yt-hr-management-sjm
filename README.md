# HR Management System

Hệ thống quản lý nhân sự (HRM) được xây dựng trên nền tảng Laravel, cung cấp các tính năng quản lý toàn diện cho doanh
nghiệp.

## Tổng quan

Dự án này là một hệ thống HRM hoàn chỉnh với hai nhóm người dùng chính:

- **HR Admin**: Quản lý toàn bộ hệ thống, phê duyệt đơn từ, quản lý nhân viên
- **Employee**: Nhân viên có thể xem thông tin cá nhân, check-in/out, gửi đơn xin nghỉ

## Tính năng chính

### Quản lý tổ chức

- **Phòng ban (Departments)**: Tạo và quản lý các phòng ban trong công ty
- **Chức vụ (Positions)**: Quản lý chức vụ, cấp bậc nhân viên
- **Người dùng (Users)**: Quản lý tài khoản và thông tin nhân viên

### Chấm công

- **Check-in/Check-out**: Nhân viên check-in và check-out theo ca làm việc
- **Báo cáo công**: Theo dõi và xuất báo cáo thời gian làm việc
- **Lịch sử chấm công**: Xem chi tiết lịch sử chấm công theo ngày/tháng

### Xin nghỉ

- **Loại nghỉ (Leave Types)**: Định nghĩa các loại nghỉ phép (nghỉ phép năm, nghỉ ốm, nghỉ không lương...)
- **Đơn xin nghỉ**: Nhân viên gửi đơn, HR phê duyệt
- **Theo dõi ngày nghỉ**: Tự động tính toán số ngày nghỉ còn lại

### Bảng lương (Payroll)

- **Quản lý lương**: Tạo và cập nhật bảng lương nhân viên
- **Chi tiết lương**: Xem chi tiết các khoản thu nhập và khấu trừ
- **Lịch sử lương**: Theo dõi thay đổi lương theo thời gian

### Đánh giá hiệu suất (Performance Review)

- **Đánh giá nhân viên**: HR tạo và quản lý các đợt đánh giá
- **Nội dung đánh giá**: Đánh giá theo tiêu chí cụ thể
- **Lịch sử đánh giá**: Lưu trữ kết quả đánh giá qua các kỳ

### Phân quyền & Bảo mật

- **Role-based Access Control**: Phân quyền theo vai trò với Filament Shield
- **Two Panel**: Tách biệt HR Admin và Employee panel
- **Widget Dashboard**: Thống kê trực quan theo từng vai trò

## Tech Stack

### Backend

- **PHP 8.2** - Server-side scripting language
- **Laravel 12** - PHP Framework mạnh mẽ và hiện đại
- **Filament 4** - Admin Panel Builder đẹp và nhanh
- **Filament Shield** - Role-based Access Control (RBAC)

### Frontend

- **TailwindCSS 4** - CSS Framework utility-first
- **Vite** - Next generation frontend tooling
- **Alpine.js** - Lightweight JavaScript framework (tích hợp sẵn trong Filament)

### Database

- **MySQL** - Cơ sở dữ liệu chính (production)
- **SQLite** - Cơ sở dữ liệu nhẹ (development)

### Development & Testing

- **Laravel Pint** - Code style fixer theo PSR-12
- **Pest** - Testing framework hiện đại cho Laravel
- **Laravel Sail** - Docker development environment
- **Faker** - Thư viện tạo dữ liệu giả cho testing
- **Mockery** - Mocking library cho unit testing
- **Laravel Pail** - Real-time error tracking

## Cấu trúc dự án

```
app/
├── Filament/
│   ├── Hr/                      # Module dành cho HR Admin
│   │   ├── Resources/           # Quản lý: Attendances, LeaveRequests, Payrolls, PerformanceReviews
│   │   └── Widgets/             # Thống kê dashboard
│   ├── Employee/                # Module dành cho Nhân viên
│   │   ├── Resources/           # Xem thông tin: Attendances, LeaveRequests, Payrolls, PerformanceReviews
│   │   └── Pages/               # Trang check-in/out
│   └── Resources/               # Tài nguyên chung
│       ├── Departments/         # Quản lý phòng ban
│       ├── Positions/           # Quản lý chức vụ
│       └── Users/               # Quản lý người dùng
```

## Database Schema

### Models chính

| Model               | Mô tả                             |
|---------------------|-----------------------------------|
| `User`              | Thông tin người dùng và nhân viên |
| `Department`        | Phòng ban                         |
| `Position`          | Chức vụ                           |
| `Attendance`        | Bản ghi chấm công                 |
| `LeaveRequest`      | Đơn xin nghỉ                      |
| `LeaveType`         | Loại nghỉ phép                    |
| `Payroll`           | Bảng lương                        |
| `PerformanceReview` | Đánh giá hiệu suất                |

### Quan hệ

```
User belongsTo Department
User belongsTo Position
User hasMany Attendance
User hasMany LeaveRequest
User hasMany Payroll
User hasMany PerformanceReview
LeaveRequest belongsTo LeaveType
LeaveRequest belongsTo User (approver)
```

## License

MIT
