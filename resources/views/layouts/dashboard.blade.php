<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'KinerjaApp') }} - @yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --bg-dark: #1a1c23;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f9fa;
        }

        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--bg-dark);
            color: #fff;
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        #sidebar::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari and Opera */
        }

        #content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            background-color: #f0f2f5;
        }

        .main-content {
            flex: 1;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.6);
            padding: 0.8rem 1.2rem;
            display: flex;
            align-items: center;
            border-radius: 10px;
            margin: 0.2rem 1rem;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .nav-link i {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 12px;
            transition: transform 0.2s;
        }

        .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }

        .nav-link:hover i {
            transform: scale(1.1);
        }

        .nav-link.active {
            color: #fff;
            background: var(--primary-color) !important;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .sidebar-header {
            padding: 2.5rem 1.5rem;
            font-weight: 800;
            font-size: 1.4rem;
            color: #fff;
            display: flex;
            align-items: center;
            letter-spacing: -0.5px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            font-weight: 700;
            color: #2b2d42;
        }

        .user-profile-btn {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .user-profile-btn:hover {
            background: #f8f9fa;
            border-color: rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            padding: 1.5rem;
            border-radius: 16px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .bg-green {
            background: linear-gradient(135deg, #2ec4b6, #218380);
        }

        .bg-yellow {
            background: linear-gradient(135deg, #ff9f1c, #f17105);
        }

        .bg-red {
            background: linear-gradient(135deg, #e71d36, #9a031e);
        }

        .bg-blue {
            background: linear-gradient(135deg, #4361ee, #3f37c9);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 0.6rem 1.8rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }

        footer {
            padding: 2rem 0;
            margin-top: auto;
            font-size: 0.85rem;
            color: #adb5bd;
            font-weight: 500;
        }
    </style>
    @yield('styles')
</head>

<body>

    <div id="sidebar" class="d-flex flex-column">
        <div class="sidebar-header">
            <a href="/" class="text-decoration-none color-inherit"><i class="fas fa-chart-line me-2 text-primary"></i>
                Kinerja-App</a>
        </div>

        <div class="flex-grow-1">
            <nav class="mt-4">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('indikator.index') }}"
                        class="nav-link {{ request()->routeIs('indikator.*') ? 'active' : '' }}">
                        <i class="fas fa-list-check"></i> Indikator
                    </a>
                @else
                    <a href="{{ route('indikator.index') }}"
                        class="nav-link {{ request()->routeIs('indikator.*') ? 'active' : '' }}">
                        <i class="fas fa-list-check"></i> Indikator Saya
                    </a>
                @endif

                <li class="nav-item list-unstyled">
                    <a href="{{ route('kegiatan-master.index') }}"
                        class="nav-link {{ request()->routeIs('kegiatan-master.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks me-2"></i>
                        {{ auth()->user()->isAdmin() ? 'Master Kegiatan' : 'Kegiatan Saya' }}
                    </a>
                </li>

                <li class="nav-item list-unstyled">
                    <a href="{{ route('output-master.index') }}"
                        class="nav-link {{ request()->routeIs('output-master.*') ? 'active' : '' }}">
                        <i class="fas fa-box-archive me-2"></i>
                        {{ auth()->user()->isAdmin() ? 'Master Output' : 'Output Saya' }}
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                    <li class="nav-item list-unstyled">
                        <a href="{{ route('pegawai.index') }}"
                            class="nav-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                            <i class="fas fa-user-friends me-2"></i> Master Pegawai
                        </a>
                    </li>
                    <a href="{{ route('target.index') }}"
                        class="nav-link {{ request()->routeIs('target.*') ? 'active' : '' }}">
                        <i class="fas fa-bullseye"></i> Target
                    </a>
                    <a href="{{ route('analisis.index') }}"
                        class="nav-link {{ request()->routeIs('analisis.*') ? 'active' : '' }}">
                        <i class="fas fa-magnifying-glass-chart"></i> Analisis & Kendala
                    </a>
                @endif

                <a href="{{ route('admin.evidence.index') }}"
                    class="nav-link {{ request()->routeIs('admin.evidence.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i> Galeri Bukti Dukung
                </a>

                <a href="{{ route('admin.aktivitas.index') }}"
                    class="nav-link {{ request()->routeIs('admin.aktivitas.*') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    {{ auth()->user()->isAdmin() ? 'Aktivitas Seluruh' : 'Riwayat Aktivitas Saya' }}
                </a>

                <a href="{{ route('rekap.capaian') }}"
                    class="nav-link {{ request()->routeIs('rekap.capaian') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice"></i> Rekap Capaian Kinerja
                </a>

                <!-- <div class="mt-3 px-3">
                    <hr style="opacity: 0.1;">
                    <a href="/explorer-master/public" class="nav-link" style="color: #60a5fa !important;">
                        <i class="fas fa-briefcase"></i> Kelola Tugas (Explorer)
                    </a>
                </div> -->
            </nav>
        </div>
    </div>

    <div id="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">@yield('title')</h4>
            <div class="user-info dropdown">
                <div class="user-profile-btn d-flex align-items-center" data-bs-toggle="dropdown"
                    style="cursor: pointer;">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=4361ee&color=fff"
                        class="rounded-circle me-3 shadow-sm" width="35" alt="avatar">
                    <div class="me-2 d-none d-md-block">
                        <div class="fw-bold text-dark small" style="line-height: 1.2;">{{ Auth::user()->name }}</div>
                        <div class="text-muted extra-small" style="font-size: 0.7rem;">
                            {{ ucfirst(Auth::user()->role ?? 'User') }}
                        </div>
                    </div>
                    <i class="fas fa-chevron-down text-muted ms-1" style="font-size: 0.7rem;"></i>
                </div>

                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 py-2"
                    style="min-width: 200px;">
                    <li class="px-3 py-2 border-bottom mb-2 d-md-none text-center">
                        <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                        <div class="small text-muted">{{ ucfirst(Auth::user()->role ?? 'User') }}</div>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 px-3 rounded-3 mx-2 w-auto" href="#" data-bs-toggle="modal"
                            data-bs-target="#modalProfile">
                            <i class="fas fa-user-circle me-2 text-primary"></i> Profil Saya
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider mx-3 opacity-50">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 px-3 rounded-3 mx-2 w-auto text-danger">
                                <i class="fas fa-right-from-bracket me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        @if(session('success'))
            <div
                class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-3 animate__animated animate__fadeIn">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div
                class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-3 animate__animated animate__fadeIn">
                <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <div class="main-content">
            @yield('content')
        </div>

        <footer class="text-center pt-5">
            <hr class="opacity-10 mb-4">
            &copy; {{ date('Y') }} <span class="text-primary fw-bold">Kinerja-App</span> - Monitoring Kinerja Terpadu
        </footer>
    </div>

    <!-- Modal Profil Saya -->
    <div class="modal fade" id="modalProfile" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Detail Profil Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formProfile">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=4361ee&color=fff&size=128"
                                class="rounded-circle shadow-sm mb-3 border border-4 border-white" width="80"
                                alt="avatar">
                            <h5 class="fw-bold mb-0" id="profile_display_name">{{ Auth::user()->name }}</h5>
                            <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold extra-small text-muted mb-1">NAMA LENGKAP</label>
                                <input type="text" name="name"
                                    class="form-control form-control-sm rounded-3 shadow-none border-light-subtle"
                                    value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold extra-small text-muted mb-1">EMAIL</label>
                                <input type="email" name="email"
                                    class="form-control form-control-sm rounded-3 shadow-none border-light-subtle"
                                    value="{{ Auth::user()->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold extra-small text-muted mb-1">NOMOR HP / WA</label>
                                <input type="text" name="no_hp"
                                    class="form-control form-control-sm rounded-3 shadow-none border-light-subtle"
                                    value="{{ Auth::user()->pegawai->no_hp ?? '' }}" placeholder="08xxxxxxxxxx">
                            </div>

                            <div class="col-12 border-top pt-3 mt-4">
                                <div class="alert alert-light border-0 small py-2 mb-3">
                                    <i class="fas fa-key me-2 text-primary"></i> <span class="fw-bold">Ganti
                                        Password</span> (Kosongkan jika tidak ingin diubah)
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold extra-small text-muted mb-1">PASSWORD BARU</label>
                                <input type="password" name="password"
                                    class="form-control form-control-sm rounded-3 shadow-none border-light-subtle">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold extra-small text-muted mb-1">KONFIRMASI
                                    PASSWORD</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control form-control-sm rounded-3 shadow-none border-light-subtle">
                            </div>

                            <div class="col-12 border-top pt-3 mt-4">
                                <div class="bg-light rounded-4 p-3 border-0">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <span class="text-muted extra-small fw-bold d-block mb-0">Role Akses</span>
                                            <span
                                                class="badge bg-primary rounded-pill extra-small px-3">{{ strtoupper(Auth::user()->role ?? 'USER') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted extra-small fw-bold d-block mb-0">Status Akun</span>
                                            <span class="text-success extra-small fw-bold"><i class="fas fa-circle me-1"
                                                    style="font-size: 0.5rem;"></i> Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"
                            id="btnUpdateProfile">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function () {
            // DataTables Language ID
            window.DATATABLES_ID = {
                "sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "sProcessing": "Sedang memproses...",
                "sLengthMenu": "Tampilkan _MENU_ entri",
                "sZeroRecords": "Tidak ditemukan data yang sesuai",
                "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "sInfoPostFix": "",
                "sSearch": "Cari:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelumnya",
                    "sNext": "Selanjutnya",
                    "sLast": "Terakhir"
                }
            };

            // Toastr Configuration
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Update Profile AJAX
            $('#formProfile').on('submit', function (e) {
                e.preventDefault();
                const btn = $('#btnUpdateProfile');
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

                $.ajax({
                    url: "{{ route('profile.update') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        toastr.success(response.message);
                        $('#modalProfile').modal('hide');
                        btn.prop('disabled', false).text('Simpan Perubahan');

                        // Digital Update UI
                        $('#profile_display_name').text(response.user.name);
                        $('div.fw-bold.text-dark.small').text(response.user.name); // update header

                        // Clear password fields
                        $('input[name="password"]').val('');
                        $('input[name="password_confirmation"]').val('');
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).text('Simpan Perubahan');
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            Object.values(errors).forEach(err => toastr.error(err[0]));
                        } else {
                            toastr.error('Terjadi kesalahan saat memperbarui profil.');
                        }
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>