<?php
require_once 'layout/header.php';

// Delete message
if (isset($_GET['delete'])) {
    $id   = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location='messages';</script>";
}

$messages = $conn->query("SELECT * FROM contact_messages ORDER BY sent_at DESC")->fetchAll();
?>

<style>
.msg-card {
    background: linear-gradient(145deg, var(--surface), var(--surface-2));
    border: 1px solid var(--border-soft);
    border-radius: var(--radius);
    padding: 1.3rem 1.5rem;
    margin-bottom: 0.75rem;
    transition: all 0.22s ease;
    display: flex; gap: 1.2rem; align-items: flex-start;
    position: relative;
}
.msg-card:hover {
    border-color: var(--border);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    transform: translateX(3px);
}
.msg-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), var(--accent-2));
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 1rem; color: white;
    flex-shrink: 0; margin-top: 2px;
}
.msg-body { flex: 1; min-width: 0; }
.msg-meta { display: flex; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.3rem; }
.msg-name { font-weight: 700; font-size: 0.95rem; color: var(--text); }
.msg-service {
    font-size: 0.7rem; font-weight: 600;
    background: rgba(99,102,241,0.18); color: var(--accent-2);
    padding: 2px 9px; border-radius: 20px;
}
.msg-date { font-size: 0.75rem; color: var(--text-muted); margin-left: auto; white-space: nowrap; }
.msg-contact { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; }
.msg-contact a { color: var(--text-soft); text-decoration: none; }
.msg-contact a:hover { color: var(--accent-2); }
.msg-details {
    display: flex; flex-wrap: wrap; gap: 1rem;
    margin-bottom: 0.6rem;
}
.msg-detail { font-size: 0.78rem; color: var(--text-muted); display: flex; align-items: center; gap: 5px; }
.msg-detail i { color: var(--text-muted); width: 12px; }
.msg-text {
    font-size: 0.83rem; color: var(--text-soft);
    line-height: 1.6; padding: 0.7rem 0.9rem;
    background: var(--bg); border-radius: var(--radius-sm);
    border-left: 3px solid var(--border);
    margin-top: 0.5rem; margin-bottom: 0.8rem;
}
.msg-actions { display: flex; justify-content: flex-end; gap: 0.5rem; }
</style>

<!-- Page Header -->
<div class="section-header">
    <div>
        <h3 class="sh-title">Messages</h3>
        <div class="sh-sub"><?= count($messages) ?> client inquiries</div>
    </div>
</div>

<!-- Message List -->
<?php if (count($messages) > 0): ?>
    <?php foreach ($messages as $msg): ?>
    <div class="msg-card">
        <div class="msg-avatar"><?= strtoupper(substr($msg['name'], 0, 1)) ?></div>
        <div class="msg-body">
            <div class="msg-meta">
                <span class="msg-name"><?= htmlspecialchars($msg['name']) ?></span>
                <span class="msg-service"><?= htmlspecialchars($msg['service'] ?? 'General') ?></span>
                <span class="msg-date"><i class="fa-regular fa-clock me-1"></i><?= date('d M Y, h:i A', strtotime($msg['sent_at'])) ?></span>
            </div>
            <div class="msg-contact">
                <i class="fa-solid fa-envelope me-1"></i>
                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a>
                <?php if (!empty($msg['phone'])): ?>
                    &nbsp;&bull;&nbsp;
                    <i class="fa-solid fa-phone me-1"></i>
                    <a href="tel:<?= htmlspecialchars($msg['phone']) ?>"><?= htmlspecialchars($msg['phone']) ?></a>
                <?php endif; ?>
            </div>
            <div class="msg-details">
                <?php if (!empty($msg['event_date'])): ?>
                <div class="msg-detail">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span>Event: <strong style="color:var(--text-soft);"><?= htmlspecialchars($msg['event_date']) ?></strong></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($msg['venue'])): ?>
                <div class="msg-detail">
                    <i class="fa-solid fa-location-dot"></i>
                    <span><?= htmlspecialchars($msg['venue']) ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($msg['message'])): ?>
            <div class="msg-text"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
            <?php endif; ?>
            <div class="msg-actions">
                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-reply me-1"></i>Reply
                </a>
                <a href="messages?delete=<?= $msg['id'] ?>" class="btn btn-outline-danger btn-sm"
                   onclick="return confirm('Delete this message?')">
                    <i class="fa-solid fa-trash me-1"></i>Delete
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
<div class="card">
    <div class="card-body text-center py-5" style="color:var(--text-muted);">
        <i class="fa-solid fa-inbox" style="font-size:3rem; opacity:0.2; display:block; margin-bottom:1rem;"></i>
        <h5 style="color:var(--text-soft);">No messages yet</h5>
        <p style="font-size:0.88rem;">Client inquiries submitted through your website will appear here.</p>
    </div>
</div>
<?php endif; ?>

<?php require_once 'layout/footer.php'; ?>