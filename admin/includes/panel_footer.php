<?php
/**
 * admin/includes/panel_footer.php
 * Closes the panel layout and loads scripts.
 */
?>
        </div><!-- /.panel-content -->
    </div><!-- /.panel-main-wrap -->
</div><!-- /.panel-layout -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= ASSETS_URL ?>/js/script.js"></script>
<script src="<?= ASSETS_URL ?>/js/panel.js"></script>

<?php if (!empty($extraJs)): ?>
    <?php foreach ($extraJs as $js): ?>
        <script src="<?= ASSETS_URL ?>/js/<?= e($js) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
