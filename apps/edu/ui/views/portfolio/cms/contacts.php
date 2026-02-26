<?php
/**
 * Portfolio CMS — Contact Messages inbox
 * 
 * WHY: Lets the admin view and delete messages submitted by visitors
 *      through the public portfolio contact form. Read-only + delete.
 */
include __DIR__ . '/../../../../controllers/PortfolioController.php';

$portfolio = new PortfolioController();
$portfolio->check_auth();

$contacts = $portfolio->list_contacts();

require_once __DIR__ . '/../../../layouts/nav.php';
?>

<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/portfolio/cms'); ?>">Portfolio CMS</a></li>
            <li class="breadcrumb-item active">Messages</li>
        </ol>
    </nav>

    <h4>Contact Messages</h4>
    <p class="text-muted">Messages submitted through the public portfolio contact form.</p>

    <table class="table table-striped table-responsive">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($contacts)): ?>
                <tr><td colspan="7" class="text-center text-muted">No messages yet.</td></tr>
            <?php else: ?>
                <?php foreach ($contacts as $i => $msg): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo htmlspecialchars($msg['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><a href="mailto:<?php echo htmlspecialchars($msg['email'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($msg['email'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                        <td><?php echo htmlspecialchars($msg['subject'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars(substr($msg['message'], 0, 100), ENT_QUOTES, 'UTF-8'); ?>...</td>
                        <td><?php echo htmlspecialchars($msg['created_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <form method="POST" action="<?php echo url('/portfolio_api'); ?>" class="d-inline"
                                  onsubmit="return confirm('Delete this message?');">
                                <input type="hidden" name="id" value="<?php echo (int) $msg['id']; ?>">
                                <button type="submit" name="delete_contact" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../../layouts/footer.php'; ?>
