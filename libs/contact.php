<?php
function showAlerting($status, $heading, $message)
{
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title:'$heading',
            text:'$message',
            icon:'$status'
        });
    });
    </script>";
}
