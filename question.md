Overview
A focused 90-minute challenge to evaluate your technical leadership approach and problem-solving skills. Complete the challenge and create a 10-minute Loom video explaining your solutions and thinking process.
Context
You're the new V1 Sr. Fullstack Engineer at LittleLives. Our platform serves two thousand preschools across SEA using PHP, Node.js, Elixir Phoenix, and mobile apps. We're scaling rapidly into Indonesia while maintaining stability.
Challenge Structure (90 minutes total)
• Part 1: Technical Problem Solving (45 minutes)
• Part 2: Architecture & Leadership Decisions (30 minutes)
• Part 3: Loom Video Explanation (10-15 minutes)
Part 1: Technical Problem Solving (45 minutes)
Scenario: Performance Crisis
It's 8:30 AM Singapore time. Your monitoring shows:
None
ALERTS:

- API response times: 15+ seconds (normal: <2s)
- Database connections: 95/100 pool exhausted
- Error rate: 23% (normal: <1%)
- Indonesian schools reporting "app freezing during check-in"
  RECENT CHANGES:
- Yesterday: Deployed new attendance tracking feature
- Last week: Added real-time parent notifications
- Database grew 40% in past month (Indonesia pilot)
  Your Tasks
  1.1 Immediate Diagnosis (15 minutes)
  Without looking anything up, write your step-by-step investigation approach:
  • What's your first hypothesis about the root cause?
  • What specific logs/metrics would you check first? (List 5)
  • Which team member would you call first and why?
  1.2 Code Analysis (20 minutes)
  Here's simplified code from the new attendance feature:
  PHP|
  // AttendanceController.php
  public function markAttendance(SchildId, §status) {
  Schild = Sthis->Child->findById(SchildId);
  Sschool
  Sthis->School->findById(Schild[ 'Child'][' school_id']);
  // Log attendance
  $this->Attendance->save ([
  'child_id' => $childId,
  'status' => $status,
  'timestamp' => date('Y-m-d H:i:s'),
  'marked_by' => $this->Auth->user ('id')

1. :
   // Send real-time notification to parents
   Sparents = $this->Parent->findByChildId($childId);
   foreach(Sparents as $parent) {
   Sthis-NotificationService->sendRealTime(l
   'user_id' => $parent[ 'Parent' ]['user_id'],
   'message' = Schild['Child']['name']. ' marked'
   Sstatus,
   'type' => 'attendance'
   1);
   }
   // Update school statistics
   StodayAttendance = $this->Attendance->find( 'count', [
   'conditions' => [
   'Attendance school_id' => $school[ 'School']'id'l,
   'DATE (Attendance. timestamp)' => date ('Y-m-d')
   J）；
1. :
   $this->School->updateStats ($school[' School']['id']. [
   'today_attendance_count' => $todayAttendance
   return $this-›redirect('/attendance/success');
   }
   Identify 3 performance problems in this code and write improved versions. Consider:
   • Database queries and N+1 problems
   • Real-time notification bottlenecks
   • Inefficient data processing
   1.3 Quick Fix Strategy (10 minutes)
   You need the platform stable in 30 minutes for a critical Indonesian demo. Write:
   • Immediate hotfix (what you'd deploy right now)
   • Traffic routing strategy (how to minimize impact)
   • Rollback plan (if the fix makes things worse)
   Part 2: Architecture & Leadership Decisions (30 minutes)
   Scenario: Scaling Pressure
   Your CEO just announced: "We're onboarding 500 Indonesian schools in Q3. V1 must handle 10x current load."
   Current stats:
   • 2000 schools, ~15K daily active users
   • Peak: 8-10 AM local time (check-ins)
   • 50-80 concurrent users per school during peak
   • Indonesia target: 500 schools, ~5K DAU
   Your Tasks
   2.1 Technical Risk Assessment (15 minutes)
   Rank these risks (1-5, highest first) and give your reasoning:
   • Database becomes bottleneck with 10x queries
   • Mobile apps crash with increased real-time updates
   • PHP memory limits hit during peak attendance processing
   • AWS costs become unsustainable
   • Cross-region latency affects Indonesian user experience
   For your #1 risk, design a specific mitigation strategy.
   2.2 Team Resource Dilemma (15 minutes)
   You have these competing priorities:
   Option A: Fix performance issues (2 weeks, entire team)
   Option B: Build Indonesian localization features (3 weeks, 4 people)
   Option C: Implement monitoring/alerting system (1 week, 2 people)
   The CEO wants all three done in 2 weeks.
   Write your recommendation including:
   • Which option(s) you'd prioritize and why How you'd communicate this to the CEO
   • What you'd say to team members whose priorities get delayed
   Part 3: Loom Video Explanation (10-15 minutes)
   Create a video covering:
   Technical Deep-Dive (6-8 minutes)
   • Walk through your code analysis and fixes
   • Explain your reasoning for the performance optimizations
   • Describe your risk mitigation strategy
   Leadership Approach (3-4 minutes)
   • How you'd handle the competing priorities situation
   • Your communication style with both technical and business stakeholders
   • How you balance technical debt vs. feature delivery
   LittleLives Fit (2-3 minutes)
   • What excites you about this technical challenge
   • How you'd approach leading a distributed team
   • Your vision for evolving v1 while maintaining stability
   What We're Evaluating
   Technical Excellence (60%)
   • Problem diagnosis: Systematic debugging approach
   • Code quality: Identifying real performance issues
   • Architecture thinking: Practical scaling solutions
   • Trade-off decisions: Business-aware technical choices
   Leadership & Communication (40%)
   • Decision-making: Clear rationale under pressure
   • Stakeholder management: CEO/team communication
   • Video clarity: Explaining complex concepts simply
   • Cultural fit: Collaborative, ownership mindset
   Al Usage Guidelines
   We expect you to use Al tools for:
   V Syntax checking and code formatting
   Looking up specific function parameters
   Generating boilerplate code structures
   But we want to see YOUR thinking on:
   Problem diagnosis and root cause analysis
   Architecture decisions and trade-offs
   • Leadership and communication approaches
   Priority setting and business judgment
   In your video, mention:
   • Which parts you used Al assistance for
   • Where you relied on your own experience/judgment
   • How you validated Al suggestions
   Submission Requirements

1) Document: Your written responses (3-4 pages max)
2) Code: Any improved code samples with comments
3) Loom Video: 10-15 minute explanation
4) Al Usage: Brief note on how you used Al tools
   Success Indicators
   We're looking for:
   ・・・
   ©* Practical problem-solving over perfect solutions
   © Clear communication in both writing and video
   © Business awareness in technical decisions
   •
   •
   ®* Honest assessment of what you know vs. need to learn
   © Leadership mindset even in technical scenarios
   Red Flags
   • Generic, Al-generated responses without personal insight
   • Over-engineering solutions without business justification
   • Avoiding hard decisions or giving non-committal answers
   • Poor video communication or unclear explanations
   This challenge reflects real scenarios you'll face at LittleLives. We value practical thinking, clear communication, and honest self-assessment. Show us how you approach complex problems and lead technical decisions in a fast-growing environment.
