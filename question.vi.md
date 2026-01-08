# Bài Test Phỏng Vấn - LittleLives Sr. Fullstack Engineer

## Tổng Quan

Bài test **90 phút** để đánh giá khả năng **technical leadership** và **problem-solving**. Sau đó quay video Loom 10-15 phút giải thích giải pháp.

---

## Bối Cảnh (Context)

Bạn là **Sr. Fullstack Engineer** mới tại LittleLives:

- Platform phục vụ **2000 trường mầm non** tại Đông Nam Á
- Tech stack: **PHP, Node.js, Elixir Phoenix**, mobile apps
- Đang mở rộng nhanh sang **Indonesia** và cần duy trì ổn định

---

## Phần 1: Xử Lý Vấn Đề Kỹ Thuật (Technical Problem Solving) - 45 phút

### 📍 Kịch Bản: Khủng Hoảng Hiệu Năng (Performance Crisis)

**Thời điểm:** 8:30 sáng giờ Singapore

**Cảnh báo hệ thống (Alerts):**
| Chỉ số | Hiện tại | Bình thường |
|--------|----------|-------------|
| API response time | **15+ giây** | < 2 giây |
| Database connections | **95/100** (gần cạn pool) | Thấp |
| Error rate | **23%** | < 1% |
| Phản hồi từ Indonesia | "App bị đơ khi điểm danh" (check-in) | - |

**Thay đổi gần đây (Recent Changes):**

- **Hôm qua:** Deploy tính năng **attendance tracking** mới
- **Tuần trước:** Thêm **real-time parent notifications**
- **Tháng qua:** Database tăng **40%** (pilot Indonesia)

---

### 📝 Bài Tập 1.1: Chẩn Đoán Ngay Lập Tức (Immediate Diagnosis) - 15 phút

> _Không tra cứu, viết approach của bạn:_

**Câu hỏi cần trả lời:**

1. **Hypothesis đầu tiên** về root cause là gì?
2. **5 logs/metrics** cụ thể bạn sẽ check đầu tiên?
3. **Team member nào** bạn sẽ gọi đầu tiên và tại sao?

---

### 📝 Bài Tập 1.2: Phân Tích Code (Code Analysis) - 20 phút

**Code mẫu từ tính năng attendance (đã đơn giản hóa):**

```php
// AttendanceController.php
public function markAttendance($childId, $status) {
    // Query 1: Lấy thông tin child
    $child = $this->Child->findById($childId);

    // Query 2: Lấy thông tin school
    $school = $this->School->findById($child['Child']['school_id']);

    // Query 3: Lưu attendance
    $this->Attendance->save([
        'child_id' => $childId,
        'status' => $status,
        'timestamp' => date('Y-m-d H:i:s'),
        'marked_by' => $this->Auth->user('id')
    ]);

    // Query 4: Lấy danh sách parents
    $parents = $this->Parent->findByChildId($childId);

    // N+1 Problem: Loop gửi notification cho từng parent
    foreach($parents as $parent) {
        $this->NotificationService->sendRealTime([
            'user_id' => $parent['Parent']['user_id'],
            'message' => $child['Child']['name'] . ' marked ' . $status,
            'type' => 'attendance'
        ]);
    }

    // Query 5: Đếm attendance hôm nay (EXPENSIVE!)
    $todayAttendance = $this->Attendance->find('count', [
        'conditions' => [
            'Attendance.school_id' => $school['School']['id'],
            'DATE(Attendance.timestamp)' => date('Y-m-d')
        ]
    ]);

    // Query 6: Update statistics
    $this->School->updateStats($school['School']['id'], [
        'today_attendance_count' => $todayAttendance
    ]);

    return $this->redirect('/attendance/success');
}
```

**Yêu cầu:**
Tìm **3 performance problems** và viết **code cải tiến**:

- ❌ **N+1 queries problem** - quá nhiều query trong 1 request
- ❌ **Real-time notification bottleneck** - gửi sync trong request
- ❌ **Inefficient data processing** - query COUNT mỗi lần check-in

---

### 📝 Bài Tập 1.3: Chiến Lược Fix Nhanh (Quick Fix Strategy) - 10 phút

**Tình huống:** Cần hệ thống ổn định trong **30 phút** cho demo quan trọng ở Indonesia.

**Viết:**

1. **Immediate hotfix** - deploy ngay bây giờ là gì?
2. **Traffic routing strategy** - giảm thiểu impact như thế nào?
3. **Rollback plan** - nếu fix làm tệ hơn thì làm gì?

---

## Phần 2: Quyết Định Kiến Trúc & Leadership - 30 phút

### 📍 Kịch Bản: Áp Lực Mở Rộng (Scaling Pressure)

**CEO thông báo:** "Chúng ta sẽ onboard **500 trường Indonesia trong Q3**. V1 phải xử lý được **10x load hiện tại**."

**Thống kê hiện tại:**
| Metric | Hiện tại | Mục tiêu Indonesia |
|--------|----------|-------------------|
| Số trường | 2000 | +500 |
| DAU (Daily Active Users) | ~15K | +5K |
| Peak time | 8-10 AM local | 8-10 AM local |
| Concurrent users/school | 50-80 | 50-80 |

---

### 📝 Bài Tập 2.1: Đánh Giá Rủi Ro Kỹ Thuật (Technical Risk Assessment) - 15 phút

**Xếp hạng các rủi ro (1-5, cao nhất trước) và giải thích:**

| Rủi ro | Mô tả                                                     |
| ------ | --------------------------------------------------------- |
| A      | **Database bottleneck** - 10x queries có thể làm nghẽn    |
| B      | **Mobile app crash** - tăng real-time updates             |
| C      | **PHP memory limits** - peak attendance processing        |
| D      | **AWS costs** - chi phí không bền vững                    |
| E      | **Cross-region latency** - user Indonesia trải nghiệm kém |

**Với rủi ro #1:** Thiết kế **mitigation strategy** cụ thể.

---

### 📝 Bài Tập 2.2: Tình Huống Phân Bổ Nguồn Lực (Team Resource Dilemma) - 15 phút

**3 priorities cạnh tranh:**

| Option | Công việc                        | Thời gian | Nhân lực |
| ------ | -------------------------------- | --------- | -------- |
| A      | Fix performance issues           | 2 tuần    | Cả team  |
| B      | Indonesian localization features | 3 tuần    | 4 người  |
| C      | Implement monitoring/alerting    | 1 tuần    | 2 người  |

**CEO muốn cả 3 xong trong 2 tuần!** 😅

**Viết recommendation:**

1. Bạn **prioritize option nào** và tại sao?
2. **Communicate với CEO** như thế nào?
3. **Nói gì với team members** bị delay priority?

---

## Phần 3: Video Loom (10-15 phút)

### 🎬 Nội dung video:

**1. Technical Deep-Dive (6-8 phút)**

- Walk through code analysis và fixes
- Giải thích reasoning cho performance optimizations
- Mô tả risk mitigation strategy

**2. Leadership Approach (3-4 phút)**

- Xử lý competing priorities như thế nào
- Communication style với technical và business stakeholders
- Balance technical debt vs. feature delivery

**3. LittleLives Fit (2-3 phút)**

- Điều gì khiến bạn hứng thú với technical challenge này
- Approach để lead distributed team
- Vision cho evolving V1 và duy trì stability

---

## Tiêu Chí Đánh Giá (Evaluation Criteria)

### Technical Excellence (60%)

| Tiêu chí              | Mô tả                            |
| --------------------- | -------------------------------- |
| Problem diagnosis     | Systematic debugging approach    |
| Code quality          | Identify real performance issues |
| Architecture thinking | Practical scaling solutions      |
| Trade-off decisions   | Business-aware technical choices |

### Leadership & Communication (40%)

| Tiêu chí               | Mô tả                            |
| ---------------------- | -------------------------------- |
| Decision-making        | Clear rationale under pressure   |
| Stakeholder management | CEO/team communication           |
| Video clarity          | Explain complex concepts simply  |
| Cultural fit           | Collaborative, ownership mindset |

---

## Hướng Dẫn Sử Dụng AI

### ✅ Được phép dùng AI cho:

- Syntax checking, code formatting
- Tra cứu function parameters
- Generate boilerplate code

### ❌ Họ muốn thấy TƯ DUY CỦA BẠN về:

- Problem diagnosis & root cause analysis
- Architecture decisions & trade-offs
- Leadership & communication approaches
- Priority setting & business judgment

### 🎥 Trong video, đề cập:

- Phần nào dùng AI hỗ trợ
- Phần nào dựa vào kinh nghiệm cá nhân
- Cách validate AI suggestions

---

## Yêu Cầu Nộp Bài (Submission)

1. **Document:** Câu trả lời viết (tối đa 3-4 trang)
2. **Code:** Code samples cải tiến kèm comments
3. **Loom Video:** 10-15 phút giải thích
4. **AI Usage:** Ghi chú ngắn về cách sử dụng AI

---

## 🌟 Điểm Cộng (Success Indicators)

- Practical problem-solving > perfect solutions
- Clear communication (viết + video)
- Business awareness trong technical decisions
- Honest về những gì biết vs. cần học thêm
- Leadership mindset trong technical scenarios

## 🚩 Red Flags (Tránh)

- Generic, AI-generated responses không có insight cá nhân
- Over-engineering không có business justification
- Tránh hard decisions, trả lời non-committal
- Video communication kém, giải thích không rõ
