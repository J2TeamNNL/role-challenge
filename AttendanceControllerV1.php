<?php

class AttendanceControllerV1 extends Controller
{
  public function __construct(
    private $Child,
    private $School,
    private $Attendance,
    private $Parent,
    private $NotificationService,
    private $Auth,
  ) {
  }

  public function markAttendance($childId, $status)
  {
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
    foreach ($parents as $parent) {
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
}
