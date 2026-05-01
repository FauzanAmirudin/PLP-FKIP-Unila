<?php
if (isset($error)) {
    echo '<div class="info info-danger" style="margin-bottom: 10px; padding: 10px; border-radius: 6px; background: #fee2e2; color: #dc2626; border: 1px solid #f87171;"><a>' . htmlspecialchars($error) . '</a></div>';
} elseif (isset($success)) {
    echo '<div class="info info-success" style="margin-bottom: 10px; padding: 10px; border-radius: 6px; background: #dcfce7; color: #16a34a; border: 1px solid #4ade80;"><a>' . (is_string($success) ? htmlspecialchars($success) : 'Respons berhasil disimpan.') . '</a></div>';
}
?>
