<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar</title>
    <!-- Bootstrap 5.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="<?= base_url(); ?>fontawesome/css/all.css" rel="stylesheet">
</head>

<body style="background-color: #2A2A2A;" class="text-light">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
            <div class="container">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Pricing</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown link
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <h5>Menu</h5>
        <form method="post" action="<?= base_url("menu/add"); ?>">
            <div class="mb-3">
                <small>Role</small>
                <select class="form-select bg-dark text-light" name="role">
                    <option selected value="">Pilih Role</option>
                    <option value="1">Root</option>
                    <option value="2">Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <small>Menu</small>
                <input type="text" class="form-control bg-dark text-light border-secondary" placeholder="Menu" name="menu">
            </div>
            <div class="mb-3">
                <small>Tabel</small>
                <input type="text" class="form-control bg-dark text-light border-secondary" placeholder="Tabel" name="tabel">
            </div>
            <div class="mb-3">
                <small>Controller</small>
                <input type="text" class="form-control bg-dark text-light border-secondary" placeholder="Controller" name="controller">
            </div>
            <div class="mb-3">
                <small>Icon</small>
                <input type="text" class="form-control bg-dark text-light border-secondary" placeholder="Icon" name="icon">
            </div>
            <div class="mb-3">
                <small>Urutan</small>
                <input type="text" class="form-control bg-dark text-light border-secondary" placeholder="Urutan" name="urutan">
            </div>
            <div class="mb-3">
                <small>Grup</small>
                <input type="text" class="form-control bg-dark text-light border-secondary" placeholder="Grup" name="grup">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-outline-light">Simpan</button>
            </div>
    </div>
    </form>
    <!-- </div> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>