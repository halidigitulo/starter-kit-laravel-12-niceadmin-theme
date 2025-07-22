<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        $motivationalQuotes = [
            "Kegagalan adalah kesuksesan yang tertunda.",
            "Setiap langkah kecil membawa kita lebih dekat pada tujuan.",
            "Jangan takut untuk bermimpi besar.",
            "Semua mimpi bisa jadi kenyataan jika kita berani mengejarnya.",
            "Kerja keras mengalahkan bakat ketika bakat tidak bekerja keras.",
            "Keberhasilan dimulai dari keberanian untuk mencoba.",
            "Perubahan dimulai dari diri sendiri.",
            "Jika kamu bisa memimpikannya, kamu bisa melakukannya.",
            "Kemenangan terbesar adalah bangkit setelah jatuh.",
            "Hanya mereka yang berani gagal yang akan meraih keberhasilan.",
            "Setiap hari adalah kesempatan baru untuk menjadi lebih baik.",
            "Semakin keras kamu bekerja, semakin beruntung kamu.",
            "Keberanian adalah kunci kesuksesan.",
            "Jangan pernah menyerah pada impianmu.",
            "Usaha tidak akan pernah mengkhianati hasil.",
            "Kesuksesan adalah perjalanan, bukan tujuan akhir.",
            "Tidak ada yang mustahil bagi mereka yang terus berusaha.",
            "Kamu adalah arsitek dari masa depanmu.",
            "Hidup ini singkat, lakukanlah yang terbaik setiap hari.",
            "Jadilah pribadi yang lebih baik dari kemarin.",
            "Kesuksesan dimulai dari satu langkah kecil.",
            "Kesabaran adalah kunci dari segala pencapaian.",
            "Orang yang sukses tidak pernah berhenti belajar.",
            "Impian tanpa aksi hanyalah khayalan.",
            "Jangan takut gagal, takutlah jika tidak mencoba.",
            "Kunci kebahagiaan adalah bersyukur atas apa yang kita miliki.",
            "Setiap tantangan adalah kesempatan untuk tumbuh.",
            "Keberhasilan membutuhkan pengorbanan dan kerja keras.",
            "Percayalah pada proses, meski hasilnya belum terlihat.",
            "Kamu lebih kuat dari apa yang kamu kira.",
            "Mulailah dari mana kamu berada, gunakan apa yang kamu punya, lakukan apa yang kamu bisa.",
            "Kegigihan adalah rahasia dari kesuksesan.",
            "Setiap usaha yang kita lakukan hari ini adalah investasi untuk masa depan.",
            "Tetaplah fokus pada tujuanmu, jangan biarkan gangguan menghalangi.",
            "Setiap masalah pasti ada solusinya, jangan pernah menyerah.",
            "Kesuksesan tidak datang dari zona nyaman.",
            "Hari ini mungkin sulit, tapi besok akan lebih baik.",
            "Setiap langkah kecil memiliki dampak besar.",
            "Bersyukur adalah kunci kebahagiaan.",
            "Jadilah orang yang tidak takut untuk bermimpi besar.",
            "Kita tidak bisa mengubah arah angin, tapi kita bisa menyesuaikan layar.",
            "Kamu bisa mencapai apa pun yang kamu pikirkan dan yakini.",
            "Setiap detik adalah peluang baru untuk berjuang.",
            "Peluang tidak datang dua kali, manfaatkan yang ada sekarang.",
            "Tidak ada jalan pintas menuju kesuksesan.",
            "Jika tidak sekarang, kapan lagi?",
            "Jangan menunggu waktu yang tepat, ciptakan waktu yang tepat.",
            "Ketika kamu berhenti mencoba, kamu berhenti berkembang.",
            "Mimpi adalah peta, usaha adalah jalannya.",
            "Kamu adalah satu-satunya yang bisa membatasi dirimu.",
            "Kesuksesan terbesar adalah memenangkan pertarungan dengan diri sendiri.",
            "Jangan biarkan ketakutan menghentikan langkahmu.",
            "Apa yang kamu tanam hari ini, akan kamu panen di masa depan.",
            "Jangan fokus pada kesalahan, tapi fokuslah pada pembelajaran.",
            "Kegagalan adalah batu loncatan menuju kesuksesan.",
            "Setiap kesulitan adalah kesempatan tersembunyi.",
            "Tidak ada kata terlambat untuk memulai.",
            "Kesuksesan bukanlah kunci kebahagiaan, kebahagiaan adalah kunci kesuksesan.",
            "Setiap hari adalah kesempatan untuk menjadi lebih baik.",
            "Jangan pernah meremehkan kekuatan dari langkah kecil.",
            "Setiap orang memiliki waktunya sendiri, jangan bandingkan dirimu dengan orang lain.",
            "Kamu adalah hasil dari keputusan yang kamu buat hari ini.",
            "Keyakinan adalah separuh dari kemenangan.",
            "Jangan takut untuk bermimpi setinggi-tingginya.",
            "Waktu terbaik untuk memulai adalah sekarang.",
            "Kerja keras akan selalu membuahkan hasil.",
            "Jangan berhenti sampai kamu bangga.",
            "Hidup adalah tentang bagaimana kamu bangkit dari keterpurukan.",
            "Semakin besar tantangannya, semakin besar pula kesempatannya.",
            "Masa depanmu dimulai dari keputusanmu hari ini.",
            "Percayalah bahwa hal-hal besar sedang menantimu.",
            "Setiap perjuangan yang kamu hadapi, menjadikanmu lebih kuat.",
            "Kesuksesan adalah hasil dari kerja keras, ketekunan, dan keberanian.",
            "Hidup ini adalah pilihan, pilih untuk sukses.",
            "Langkah pertama selalu yang paling sulit, tapi juga yang paling penting.",
            "Orang yang berani gagal adalah orang yang berani sukses.",
            "Rintangan adalah bagian dari jalan menuju kesuksesan.",
            "Kamu tidak akan pernah tahu seberapa kuat dirimu sampai kamu menghadapi tantangan.",
            "Setiap pencapaian dimulai dari keputusan untuk mencoba.",
            "Kegagalan adalah guru terbaik.",
            "Apa yang kamu lakukan hari ini menentukan masa depanmu.",
            "Semakin keras perjuanganmu, semakin manis kemenanganmu.",
            "Kesuksesan tidak datang kepada mereka yang menunggu, tapi kepada mereka yang bekerja untuk itu.",
            "Jadilah perubahan yang kamu inginkan di dunia ini.",
            "Tidak ada mimpi yang terlalu besar, hanya usaha yang kurang maksimal.",
            "Fokus pada solusi, bukan masalah.",
            "Semua orang bisa sukses, asalkan mereka mau bekerja keras.",
            "Kamu lebih kuat dari apa yang kamu pikirkan.",
            "Jangan biarkan kegagalan membuatmu menyerah.",
            "Setiap langkah menuju tujuan adalah pencapaian.",
            "Keberanian adalah kunci dari setiap kesuksesan.",
            "Ketika satu pintu tertutup, pintu lain akan terbuka.",
            "Mimpi adalah kompas, tindakan adalah perjalanan.",
            "Setiap orang punya waktu, kamu hanya perlu memanfaatkan waktumu dengan baik.",
            "Ketika kamu bekerja dengan hati, hasilnya akan luar biasa.",
            "Hidup adalah tentang menciptakan dirimu, bukan menemukan dirimu.",
            "Ketika kamu menyerah, itulah saat kamu gagal.",
            "Semua hal besar dimulai dari mimpi kecil.",
            "Kesuksesan tidak diukur dari seberapa cepat kamu mencapainya, tapi dari seberapa keras usahamu.",
            "Setiap hari adalah kesempatan baru untuk membuat hidup lebih baik.",
        ];

        // Pick a random quote
        $quote = $motivationalQuotes[array_rand($motivationalQuotes)];
        // Check if the user is already authenticated, if so redirect to dashboard
        if (Auth::guard('admin')->check()) {
            return redirect('dashboard')->with('info', 'You are already logged in.');
        }

        return view('auth.login', compact('quote'));
    }

    public function auth_login(Request $request)
    {
        // Define validation rules for the request
        $rules = [
            'username' => 'required|string|exists:users,username',  // Required, must be a string, and must exist in 'users' table
            'password' => 'required|string',                 // Required, must be a string
        ];

        // Custom error messages
        $messages = [
            'username.required' => 'Username wajib diisi.',
            'username.exists' => 'username tidak terdaftar.',
            'password.required' => 'Password wajib diisi.',
        ];

        // Validate the request with custom messages
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Return validation errors
            if ($request->ajax()) {
                return response()->json(['success' => 0, 'message' => $validator->errors()->first()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Attempt to log in using the 'admin' guard
        if (Auth::guard('admin')->attempt($request->only('username', 'password'))) {
            $user = Auth::guard('admin')->user();

            // Check if the user is active
            if (!$user->is_active) {
                Auth::guard('admin')->logout(); // Log out inactive user

                if ($request->ajax()) {
                    return response()->json(['success' => 0, 'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
                }
                return redirect()->route('login')->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }
            // Check if the request is an AJAX request, return a JSON response if it is
            if ($request->ajax()) {
                return response()->json(['success' => 1, 'message' => 'Selamat! Anda berhasil Login.']);
            }

            // If authentication is successful, redirect to the intended route
            return redirect()->intended(route('dashboard'))->with('success', 'Selamat! Anda berhasil Login.');

            // Check if the user is already logged in on another device
            if ($user->session_id) {
                // Destroy the previous session
                \Illuminate\Support\Facades\Session::getHandler()->destroy($user->session_id);
                // return response()->json([
                //     'success' => 0,
                //     'message' => 'Anda sudah login di perangkat lain.'
                // ], 403);
            }

            // Generate a new session ID and save it to the user
            $user->session_id = session()->getId();
            $user->save();

            // Handle AJAX response for successful login
            if ($request->ajax()) {
                return response()->json(['success' => 1, 'message' => 'Selamat! Anda berhasil Login.']);
            }

            // Redirect to the intended route for non-AJAX requests
            return redirect()->intended(route('dashboard'))->with('success', 'Selamat! Anda berhasil Login.');
        }

        // If authentication fails
        $errorMessage = 'Username atau Password salah';

        if ($request->ajax()) {
            return response()->json(['success' => 0, 'message' => $errorMessage], 401);
        }
        return redirect()->back()->with('error', $errorMessage)->withInput($request->only('username'));
    }


    public function logout(Request $request)
    {
        $user = Auth::user(); // Get the currently authenticated user

        // Log out the user from the 'admin' guard if authenticated
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        // Invalidate the session and regenerate the token to prevent session fixation attacks
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the login page with a logout success message
        return redirect('/')->withErrors([
            'session_expired' => 'You have been logged out because your account was logged in from another device.',
        ]);
    }
}
