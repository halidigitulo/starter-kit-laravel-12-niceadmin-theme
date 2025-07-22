<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- JAVASCRIPT -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script src="{{ asset('') }}libs/jquery/jquery.min.js"></script>
<script src="{{ asset('') }}libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('') }}libs/metismenu/metisMenu.min.js"></script>
<script src="{{ asset('') }}libs/simplebar/simplebar.min.js"></script>
<script src="{{ asset('') }}libs/node-waves/waves.min.js"></script>

<script src="{{ asset('') }}js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

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
        document.getElementById('day').innerHTML = day;
        document.getElementById('date').innerHTML = currentDate;
        document.getElementById('time').innerHTML = time;

    }

    setInterval(updateDateTime, 1000); // Update every second
    updateDateTime(); // Initial call to display date, day, and time immediately
</script>
{{-- <script>
    // Define routes to exclude from auto logout
    const excludedRoutes = [
        "{{ route('displayantrian.index') }}", // Replace with your actual route name for "Display Antrian"
        "{{ route('ambilantrian.index') }}" // Replace with your actual route name for "Ambil Antrian"
    ];

    // Check if the current URL matches any of the excluded routes
    const currentRoute = window.location.href;
    const isExcluded = excludedRoutes.some(route => currentRoute.includes(route));

    if (!isExcluded) {
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

        // Detect user activity (mouse movement, keypress, scroll, etc.)
        window.onload = resetTimer;
        window.onmousemove = resetTimer;
        window.onkeypress = resetTimer;
        window.onscroll = resetTimer;
        window.onclick = resetTimer;
    }
</script> --}}


</body>

</html>
