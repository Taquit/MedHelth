    </main>
</div>
<script>
document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') {
        return;
    }
    var cancelTarget = document.querySelector('[data-modal-cancel]');
    if (cancelTarget && !cancelTarget.closest('[hidden]')) {
        cancelTarget.click();
    }
});
</script>
