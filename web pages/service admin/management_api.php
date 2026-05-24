<?php
  require_once __DIR__ . '/../../config/session_check.php';
  require_once __DIR__ . '/../../config/database.php';

  header('Content-Type: application/json');

  $db     = getDB();
  $action = $_GET['action'] ?? '';

  $stmt = $db->prepare("SELECT id FROM companies WHERE user_id = ?");
  $stmt->execute([$currentUser['id']]);
  $row  = $stmt->fetch(PDO::FETCH_ASSOC);
  
  if (!$row) { 
      echo json_encode(['error' => 'Company not found']); 
      exit; 
  }
  $company_id = $row['id'];

  switch ($action) {

    case 'get_clients':
      $stmt = $db->prepare(
        "SELECT id, client_name, email, status
        FROM clients WHERE company_id = ? ORDER BY client_name ASC"
      );
      $stmt->execute([$company_id]);
      echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
      exit;

    case 'get_employees':
      $stmt = $db->prepare(
        "SELECT id, first_name, last_name, position, department, email
        FROM employees WHERE company_id = ? ORDER BY first_name ASC"
      );
      $stmt->execute([$company_id]);
      echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
      exit;

    case 'get_meeting_employees':
      $id = intval($_GET['id'] ?? 0);
      if (!$id) { echo json_encode([]); exit; }
      
      $own = $db->prepare("SELECT id FROM meetings WHERE id=? AND company_id=?");
      $own->execute([$id, $company_id]);
      if (!$own->fetch()) { echo json_encode([]); exit; }
      
      $stmt = $db->prepare(
        "SELECT e.first_name, e.last_name, e.position, e.department, e.email
        FROM meeting_employees me
        JOIN employees e ON e.id = me.employee_id
        WHERE me.meeting_id = ?
        ORDER BY e.first_name ASC"
      );
      $stmt->execute([$id]);
      echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
      exit;

    case 'get_meetings':
      $stmt = $db->prepare(
        "SELECT id, title, meeting_date, meeting_time, meet_link, notes, status
        FROM meetings
        WHERE company_id = ?
        ORDER BY meeting_date DESC, meeting_time DESC"
      );
      $stmt->execute([$company_id]);
      echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
      exit;

    case 'add_meeting':
      $body  = json_decode(file_get_contents('php://input'), true);
      $title  = trim($body['title']        ?? '');
      $date   = trim($body['meeting_date'] ?? '');
      $time   = trim($body['meeting_time'] ?? '');
      $link   = trim($body['meet_link']    ?? '');
      $notes  = trim($body['notes']        ?? '');
      $status = in_array($body['status'] ?? '', ['scheduled','done','cancelled'])
                  ? $body['status'] : 'scheduled';
      $empIds = array_filter(array_map('intval', $body['employee_ids'] ?? []));

      if (!$title || !$date || !$time) {
        echo json_encode(['success' => false, 'error' => 'Title, date and time are required.']);
        exit;
      }

      $db->beginTransaction();
      try {
        $stmt = $db->prepare(
          "INSERT INTO meetings (company_id, title, meeting_date, meeting_time, meet_link, notes, status)
          VALUES (?,?,?,?,?,?,?)"
        );
        $stmt->execute([$company_id, $title, $date, $time, $link, $notes, $status]);
        $meetId = $db->lastInsertId();

        if ($empIds) {
          $ins = $db->prepare("INSERT INTO meeting_employees (meeting_id, employee_id) VALUES (?,?)");
          foreach ($empIds as $eid) $ins->execute([$meetId, $eid]);
        }

        $db->commit();

        echo json_encode(['success' => true, 'id' => $meetId]);

        if ($empIds) {
          $placeholders = implode(',', array_fill(0, count($empIds), '?'));
          $eStmt = $db->prepare(
            "SELECT first_name, last_name, email FROM employees
            WHERE id IN ($placeholders) AND email IS NOT NULL AND email != ''"
          );
          $eStmt->execute(array_values($empIds));
          foreach ($eStmt->fetchAll(PDO::FETCH_ASSOC) as $emp) {
            $subject = "📅 New Meeting: $title";
            $message = "Hello {$emp['first_name']},\n\n"
                    . "A new meeting has been scheduled:\n\n"
                    . "📌 Title: $title\n"
                    . "📅 Date:  $date\n"
                    . "🕐 Time:  $time\n"
                    . ($link ? "🔗 Link:  $link\n" : "")
                    . ($notes ? "\n📝 Notes: $notes\n" : "")
                    . "\nYou will receive a reminder 1 hour before the meeting.\n\nRegards,\nManagement";
            sendEmail($emp['email'], $subject, $message, 'meeting');
          }
        }
        exit;

      } catch (Exception $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
      }

    case 'update_meeting':
      $body   = json_decode(file_get_contents('php://input'), true);
      $id     = intval($body['id']           ?? 0);
      $date   = trim($body['meeting_date']   ?? '');
      $time   = trim($body['meeting_time']   ?? '');
      $status = in_array($body['status'] ?? '', ['scheduled','done','cancelled'])
                  ? $body['status'] : 'scheduled';

      if (!$id || !$date || !$time) {
        echo json_encode(['success' => false, 'error' => 'Invalid data.']);
        exit;
      }

      $check = $db->prepare("SELECT id, title, meet_link FROM meetings WHERE id = ? AND company_id = ?");
      $check->execute([$id, $company_id]);
      $meeting = $check->fetch(PDO::FETCH_ASSOC);
      if (!$meeting) { echo json_encode(['success' => false, 'error' => 'Meeting not found.']); exit; }

      $db->prepare("UPDATE meetings SET meeting_date=?, meeting_time=?, status=? WHERE id=?")
        ->execute([$date, $time, $status, $id]);

      echo json_encode(['success' => true]);

      // Notifications emails
      $eStmt = $db->prepare(
        "SELECT e.first_name, e.email
        FROM meeting_employees me
        JOIN employees e ON e.id = me.employee_id
        WHERE me.meeting_id = ? AND e.email IS NOT NULL AND e.email != ''"
      );
      $eStmt->execute([$id]);
      foreach ($eStmt->fetchAll(PDO::FETCH_ASSOC) as $emp) {
        if ($status === 'cancelled') {
          $subject = "❌ Meeting Cancelled: {$meeting['title']}";
          $message = "Hello {$emp['first_name']},\n\n"
                  . "The meeting \"{$meeting['title']}\" scheduled for "
                  . "$date at $time has been cancelled.\n\n"
                  . "Regards,\nManagement";
          sendEmail($emp['email'], $subject, $message, 'cancelled');
        } else if ($status === 'rescheduled') {
          $subject = "📅 Meeting Rescheduled: {$meeting['title']}";
          $message = "Hello {$emp['first_name']},\n\n"
                  . "The meeting \"{$meeting['title']}\" has been rescheduled.\n\n"
                  . "📅 New Date: $date\n"
                  . "🕐 New Time: $time\n"
                  . "📋 Status:   $status\n"
                  . ($meeting['meet_link'] ? "🔗 Link:   {$meeting['meet_link']}\n" : "")
                  . "\nRegards,\nManagement";
          sendEmail($emp['email'], $subject, $message, 'reschedule');
        }
      }
      exit;

    case 'delete_meeting':
      $body = json_decode(file_get_contents('php://input'), true);
      $id   = intval($body['id'] ?? 0);

      if (!$id) { echo json_encode(['success' => false, 'error' => 'Invalid id.']); exit; }

      $check = $db->prepare("SELECT id, title, meeting_date, meeting_time FROM meetings WHERE id=? AND company_id=?");
      $check->execute([$id, $company_id]);
      $meeting = $check->fetch(PDO::FETCH_ASSOC);
      if (!$meeting) { echo json_encode(['success' => false, 'error' => 'Not found.']); exit; }

      echo json_encode(['success' => true]);

      // Only notify if meeting hasn't happened yet
      $meetingDT = new DateTime($meeting['meeting_date'] . ' ' . $meeting['meeting_time']);
      if ($meetingDT > new DateTime()) {
        $eStmt = $db->prepare(
          "SELECT e.first_name, e.email
          FROM meeting_employees me
          JOIN employees e ON e.id = me.employee_id
          WHERE me.meeting_id = ? AND e.email IS NOT NULL AND e.email != ''"
        );
        $eStmt->execute([$id]);
        foreach ($eStmt->fetchAll(PDO::FETCH_ASSOC) as $emp) {
          $subject = "❌ Meeting Cancelled: {$meeting['title']}";
          $message = "Hello {$emp['first_name']},\n\n"
                  . "The meeting \"{$meeting['title']}\" scheduled for "
                  . "{$meeting['meeting_date']} at {$meeting['meeting_time']} has been cancelled.\n\n"
                  . "Regards,\nManagement";
          sendEmail($emp['email'], $subject, $message, 'cancelled');
        }
      }

      $db->prepare("DELETE FROM meetings WHERE id=?")->execute([$id]);
      exit;

    default:
      echo json_encode(['error' => 'Unknown action: ' . htmlspecialchars($action)]);
      exit;
  }

  function sendEmail(string $to, string $subject, string $message, string $type = 'meeting'): void {
    if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) return;
  
    $MAKE_WEBHOOK = $_ENV['ZAPIER_URL'] ?? getenv('ZAPIER_URL');

    if (!$MAKE_WEBHOOK) {
        error_log("Erreur : MAKE_WEBHOOK_URL non défini dans le fichier .env");
        return;
    }
  
    $payload = json_encode([
      'to'      => $to,
      'subject' => $subject,
      'body'    => $message,
      'type'    => $type,   
    ]);
  
    $ch = curl_init($MAKE_WEBHOOK);
    curl_setopt_array($ch, [
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => $payload,
      CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT        => 5,
    ]);
    curl_exec($ch);
    curl_close($ch);
  }