<?php
if (!empty($res) && !empty($res['RESPONSE'])) {
    $badgeColor = ($res['RESPONSE'] == 'Cukup') ? 'background: #dcfce7; color: #16a34a;' : 'background: #fef3c7; color: #d97706;';
    ?>
    <div style="margin-bottom: 15px;">
        <span style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Status Review</span>
        <div style="margin-top: 5px;">
            <span style="<?= $badgeColor ?> padding: 4px 12px; border-radius: 6px; font-size: 13px; font-weight: 700;">
                <?= htmlspecialchars($res['RESPONSE']) ?>
            </span>
        </div>
    </div>
    <div>
        <span style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Komentar Dosen</span>
        <div style="margin-top: 8px; color: #334155; line-height: 1.6; font-size: 14.5px; background: #fff; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
            <?= ($res['KRITIKSARAN']) ? htmlspecialchars($res['KRITIKSARAN']) : '<i>Tidak ada komentar.</i>' ?>
        </div>
    </div>
    <?php
}
?>
