<?php if (!empty($avis)): ?>
    <?php foreach ($avis as $a): ?>
        <tr>
            <td><?= $a['avis_id'] ?></td>
            <td><?= htmlspecialchars($a['nom']) ?></td>
            <td><?= htmlspecialchars($a['prenom']) ?></td>
            <td><?= htmlspecialchars($a['email']) ?></td>
            <td><?= htmlspecialchars($a['contenu']) ?></td>
            <td><?= $a['note'] ?>/5</td>
            <td><?= $a['date_creation'] ?></td>
            <td>
                <span class="status-badge <?= $a['is_visible'] ? 'visible' : 'hidden' ?>">
                    <?= $a['is_visible'] ? 'Visible' : 'Masqué' ?>
                </span>
            </td>
            <td class="actions">
                <button onclick="toggleVisibility(<?= $a['avis_id'] ?>, <?= $a['is_visible'] ?>)" class="btn-visibility">
                    <?= $a['is_visible'] ? 'Masquer' : 'Afficher' ?>
                </button>
                <button onclick="modifierAvis(<?= $a['avis_id'] ?>)" class="btn-edit">Modifier</button>
                <button onclick="supprimerAvis(<?= $a['avis_id'] ?>)" class="btn-delete">Supprimer</button>
                <button onclick="signalerAvis(<?= $a['avis_id'] ?>)" class="btn-report">Signaler</button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="9" class="text-center">Aucun avis trouvé</td>
    </tr>
<?php endif; ?>