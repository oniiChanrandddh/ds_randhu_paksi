<?php
function addNotif($user_id, $message) {
    global $conn;
    $conn->query("INSERT INTO notifications (user_id, message, status) VALUES ('$user_id', '$message', 'unread')");
}

function getNotifCount($user_id) {
    global $conn;
    $q = $conn->query("SELECT COUNT(*) AS total FROM notifications WHERE user_id='$user_id' AND status='unread'");
    $d = $q->fetch_assoc();
    return $d['total'];
}
