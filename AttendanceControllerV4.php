<?php

class AttendanceControllerV4 extends Controller
{
  public function __construct(
    private $School,
    private $Attendance,
    private $Auth,
  ) {
  }

  public function markBulkAttendance($schoolId, array $childIds, $status)
  {
    $school = $this->School->find('first', [
      'conditions' => ['School.id' => $schoolId],
      'contain' => [
        'Child' => [
          'conditions' => ['Child.id' => $childIds],
          'Parent'
        ]
      ]
    ]);

    if (!$school || empty($school['Child'])) {
      throw new NotFoundException('School or children not found');
    }

    $timestamp = date('Y-m-d H:i:s');
    $markedBy = $this->Auth->user('id');

    $attendanceRecords = array_map(fn($child) => [
      'child_id' => $child['id'],
      'school_id' => $schoolId,
      'status' => $status,
      'timestamp' => $timestamp,
      'marked_by' => $markedBy
    ], $school['Child']);

    $this->Attendance->saveMany($attendanceRecords);

    $this->dispatch(new SendBulkAttendanceNotificationsJob([
      'school' => $school,
      'children' => $school['Child'],
      'status' => $status
    ]));

    $this->School->incrementBy($schoolId, 'today_attendance_count', count($childIds));

    return $this->redirect('/attendance/success');
  }
}
