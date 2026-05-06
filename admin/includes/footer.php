            </div>
        </main>
    </div>
    
    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content confirm-modal">
            <div class="confirm-icon-wrapper">
                <i id="confirmIcon" class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 id="confirmTitle" class="confirm-title">Confirm Delete</h3>
            <p id="confirmMessage" class="confirm-message">Are you sure you want to delete this item?</p>
            <div class="confirm-actions">
                <button type="button" class="btn btn-secondary" onclick="closeConfirm()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmActionBtn" onclick="confirmYes()">
                    <i class="fas fa-check"></i> <span id="confirmBtnText">Confirm</span>
                </button>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Jodit Editor CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@4.0.0-beta.24/build/jodit.min.css">
    <!-- Jodit Editor JS -->
    <script src="https://cdn.jsdelivr.net/npm/jodit@4.0.0-beta.24/build/jodit.min.js"></script>
    <script src="assets/js/admin.js"></script>
    <?php if (isset($extra_scripts)): ?>
        <?php echo $extra_scripts; ?>
    <?php endif; ?>
</body>
</html>

