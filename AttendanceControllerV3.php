<?php

class AttendanceControllerV3 extends Controller
{
  public function __construct(
    private $Child,
    private $School,
    private $Attendance,
    private $Auth,
  ) {
  }

  public function markAttendance($childId, $status)
  {
    $child = $this->Child->find('first', [
      'conditions' => ['Child.id' => $childId],
      'contain' => ['School', 'Parent']
    ]);

    if (!$child) {
      throw new NotFoundException('Child not found');
    }

    $this->Attendance->save([
      'child_id' => $childId,
      'school_id' => $child['School']['id'],
      'status' => $status,
      'timestamp' => date('Y-m-d H:i:s'),
      'marked_by' => $this->Auth->user('id')
    ]);

    $this->dispatch(new SendAttendanceNotificationsJob([
      'child' => $child,
      'status' => $status,
      'parents' => $child['Parent']
    ]));

    $this->School->increment($child['School']['id'], 'today_attendance_count');

    return $this->redirect('/attendance/success');
  }
}
