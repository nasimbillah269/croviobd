<script>
    // show / hide password
    $(document).on('click', '.au-eye', function () {
        var input = document.getElementById($(this).data('target'));
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        $(this).attr('aria-label', show ? 'Hide password' : 'Show password')
               .find('i').toggleClass('fa-eye', !show).toggleClass('fa-eye-slash', show);
    });

    // prevent double submits
    $('.au-submit-guard').on('submit', function () {
        $(this).find('.au-submit').prop('disabled', true);
    });
</script>
