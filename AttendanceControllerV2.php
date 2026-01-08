<?php

class AttendanceControllerV2 extends Controller
{
  public function __construct(
    private $Child,
    private $School,
    private $Attendance,
    private $Parent,
    private $Auth,
  ) {
  }

  public function markAttendance($childId, $status)
  {
    $child = $this->Child->findById($childId);
    $school = $this->School->findById($child['Child']['school_id']);
    $parents = $this->Parent->findByChildId($childId);

    $this->Attendance->save([
      'child_id' => $childId,
      'school_id' => $school['School']['id'],
      'status' => $status,
      'timestamp' => date('Y-m-d H:i:s'),
      'marked_by' => $this->Auth->user('id')
    ]);

    $this->dispatch(new SendAttendanceNotificationsJob([
      'child' => $child,
      'status' => $status,
      'parents' => $parents
    ]));

    $this->School->increment($school['School']['id'], 'today_attendance_count');

    return $this->redirect('/attendance/success');
  }
}
