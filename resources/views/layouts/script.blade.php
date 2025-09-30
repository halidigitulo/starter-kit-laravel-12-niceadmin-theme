<!-- Vendor JS Files -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script src="{{ asset('') }}vendor/apexcharts/apexcharts.min.js"></script>
<script src="{{ asset('') }}vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('') }}vendor/chart.js/chart.umd.js"></script>
<script src="{{ asset('') }}vendor/echarts/echarts.min.js"></script>
<script src="{{ asset('') }}vendor/quill/quill.js"></script>
<script src="{{ asset('') }}vendor/simple-datatables/simple-datatables.js"></script>
<script src="{{ asset('') }}vendor/tinymce/tinymce.min.js"></script>
<script src="{{ asset('') }}vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="{{ asset('') }}js/main.js"></script>

@stack('scripts')
{{-- Tampilkan tanggal  --}}
<script>
    function updateDateTime() {
        const date = new Date();
        const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        const day = days[date.getDay()];

        const currentDate = date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });


        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        const seconds = date.getSeconds().toString().padStart(2, '0');
        const time = `${hours}:${minutes}:${seconds}`;

        const dateDayTime = `${day}, ${currentDate} - ${time}`;

        document.getElementById('date-day-time').innerHTML = dateDayTime;

    }

    setInterval(updateDateTime, 1000); // Update every second
    updateDateTime(); // Initial call to display date, day, and time immediately
</script>
<script>
    // Idle timeout duration (1 minute = 60,000 ms)
    const idleTimeout = 60 * 60 * 1000;

    let idleTimer;

    // Reset timer on user activity
    function resetTimer() {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(logoutUser, idleTimeout);
    }

    function logoutUser() {
        // Clear any ongoing intervals
        clearTimeout(idleTimer);

        Swal.fire({
            title: "Session Timeout",
            text: "You have been idle for too long. Logging you out now.",
            icon: "warning",
            timer: 5000, // Optional: Wait 5 seconds before logout
            timerProgressBar: true,
            showConfirmButton: false
        }).then(() => {
            // Perform logout using POST request
            fetch("{{ route('logout') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}", // Include CSRF token
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({}) // Optional: empty body for logout
                })
                .then(() => {
                    // Redirect to the login page after logout
                    window.location.href = "{{ route('login') }}";
                })
                .catch((error) => console.error("Error logging out:", error));
        });
    }

    $(document).ready(function() {
        $('#fullscreen-btn').on('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                $(this).html('<i class="bi bi-fullscreen-exit"></i>');
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                    $(this).html('<i class="bi bi-arrows-fullscreen"></i>');
                }
            }
        });

        // Update button text and icon on exiting fullscreen mode
        $(document).on('fullscreenchange', function() {
            if (!document.fullscreenElement) {
                $('#fullscreen-btn').html('<i class="bi bi-arrows-fullscreen"></i> ');
            }
        });
    });

    // Detect user activity (mouse movement, keypress, scroll, etc.)
    window.onload = resetTimer;
    window.onmousemove = resetTimer;
    window.onkeypress = resetTimer;
    window.onscroll = resetTimer;
    window.onclick = resetTimer;
</script>


</body>

</html>
