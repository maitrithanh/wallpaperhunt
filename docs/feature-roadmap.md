# 🗺️ Danh Sách Tính Năng & Lộ Trình Phát Triển (Feature Roadmap)
## Dự án: WallpaperHunt

Tài liệu này tổng hợp toàn bộ các tính năng cần có cho nền tảng cộng đồng chia sẻ hình nền WallpaperHunt. Hãy sử dụng tài liệu này như một checklist để theo dõi tiến độ và tránh bỏ sót tính năng.

---

## 🏠 1. Trang Chủ (Homepage) - Điểm Chạm Đầu Tiên
Mục tiêu: Thu hút người dùng ngay từ cái nhìn đầu tiên với giao diện mượt mà và tốc độ tải trang nhanh.

- [ ] **Hero Section:**
  - [ ] Thanh tìm kiếm thông minh (Auto-suggest).
  - [ ] Hiệu ứng nền mờ (Glassmorphism) và chữ chào mừng ấn tượng.
- [ ] **Danh Mục Nổi Bật (Featured Categories):**
  - [ ] Dạng Grid hoặc Slider ngang (Anime, Gaming, Nature, Minimalist,...).
- [ ] **Masonry Grid (Lưới hình nền):**
  - [ ] Hiển thị hình nền không bị cắt (Duy trì tỷ lệ ảnh gốc).
  - [ ] Infinite Scrolling (Cuộn vô hạn) tải thêm ảnh qua AJAX.
  - [ ] Hiệu ứng Hover: Hiện nút Tải nhanh, nút Yêu thích và tên Tác giả.

---

## 🖼️ 2. Trang Chi Tiết Hình Nền (Wallpaper Details)
Mục tiêu: Cung cấp trải nghiệm xem và tải ảnh tốt nhất.

- [ ] **Trình xem ảnh cao cấp:**
  - [ ] Ảnh chất lượng cao (Sử dụng `loading="lazy"`).
  - [ ] Chế độ xem toàn màn hình (Lightbox) hoặc Zoom ảnh.
- [ ] **Hành động người dùng (Interactions):**
  - [ ] Nút **Tải về (Download)**: Tự động đếm lượt tải.
  - [ ] Nút **Yêu thích (Like/Favorite)**: Sử dụng AJAX (Không load lại trang).
- [ ] **Thông tin chi tiết (Metadata):**
  - [ ] Độ phân giải (VD: 3840x2160 - 4K), Dung lượng, Định dạng (WEBP/PNG).
  - [ ] Bảng màu chủ đạo (Color Palette) trích xuất từ ảnh.
  - [ ] Danh sách Thẻ Tag liên quan.
- [ ] **Mục liên quan:**
  - [ ] Gợi ý hình nền cùng chủ đề hoặc cùng tác giả.

---

## 📂 3. Khám Phá & Bộ Lọc (Explore & Filters)
Mục tiêu: Giúp người dùng dễ dàng tìm thấy hình nền mong muốn.

- [ ] **Trang danh sách theo Danh mục/Thẻ.**
- [ ] **Bộ lọc nâng cao (Advanced Filters):**
  - [ ] Lọc theo **Màu sắc** (Dựa trên mã màu).
  - [ ] Lọc theo **Độ phân giải** (Full HD, 2K, 4K).
  - [ ] Lọc theo **Hướng ảnh** (Ngang - Desktop, Dọc - Mobile).
  - [ ] Sắp xếp theo: Mới nhất, Tải nhiều nhất, Xem nhiều nhất.

---

## 🧑‍🎨 4. Hồ Sơ Tác Giả & Bộ Sưu Tập (Artist & Albums)
Mục tiêu: Xây dựng cộng đồng sáng tạo.

- [ ] **Trang cá nhân của Tác giả (Artist Profile):**
  - [ ] Avatar, Banner, Tiểu sử, Liên kết mạng xã hội.
  - [ ] Danh sách hình nền đã đăng (Được chia theo Tab).
- [ ] **Bộ sưu tập/Albums:**
  - [ ] Cho phép tạo Album cá nhân (VD: "Góc Gaming", "Chill Vibe").
  - [ ] Nút "Thêm vào Album" nhanh khi đang xem ảnh.

---

## 🔐 5. Tài Khoản & Xác Thực (Authentication)

- [ ] **Đăng ký/Đăng nhập:**
  - [ ] Qua Email/Mật khẩu truyền thống.
  - [ ] Đăng nhập nhanh bằng Google (OAuth2).
- [ ] **Quản lý tài khoản:**
  - [ ] Đổi mật khẩu, cập nhật thông tin cá nhân.
  - [ ] Xem lại danh sách hình nền đã Yêu thích/Đã tải.

---

## 📤 6. Đăng Tải & Quy Trình Duyệt Ảnh (Upload & Moderation)

- [ ] **Trình Upload cho Người dùng (User Upload):**
  - [ ] Giao diện kéo thả (Drag & Drop) tải lên nhiều ảnh.
  - [ ] Nhập thông tin ảnh: Tiêu đề, Danh mục, Thẻ tag.
  - [ ] Trạng thái ảnh sau khi tải lên: **Chờ duyệt (Pending)**.
- [ ] **Hệ thống Quản trị & Duyệt Ảnh (Admin CMS & Moderation):**
  - [ ] Trang danh sách ảnh Chờ duyệt dành riêng cho Admin.
  - [ ] Tính năng: **Duyệt (Approve)** hoặc **Từ chối (Reject)** kèm lý do.
  - [ ] Gửi thông báo (Notification/Email) cho User khi trạng thái ảnh thay đổi.
- [ ] **Xử lý ảnh & Tính ổn định (Backend & Image Quality):**
  - [ ] Chuyển đổi ảnh sang `WEBP`/`AVIF`, giữ chất lượng (85-92%) để ảnh cực kỳ **rõ nét**.
  - [ ] Tạo Thumbnails sắc nét (Sharp resizing) và trích xuất thông số (Độ phân giải, Dung lượng).
  - [ ] Sử dụng hàng đợi (Queue/Jobs) để xử lý ảnh ngầm, đảm bảo server hoạt động ổn định không bị nghẽn.

---

## 📈 7. SEO & Hiệu Suất (SEO & Performance)

> [!IMPORTANT]
> SEO là yếu tố sống còn để kéo traffic tự nhiên.

- [ ] **SEO Meta Tags:**
  - [ ] Tiêu đề, Mô tả thân thiện cho từng trang.
  - [ ] Thẻ OpenGraph (`og:image`) hiển thị ảnh đẹp khi chia sẻ link lên Facebook/Zalo.
- [ ] **Sitemap động:** Tự động cập nhật khi có ảnh mới.

---
*Mẹo: Hãy tích `[x]` vào các ô trống khi tính năng đã hoàn thành.*
