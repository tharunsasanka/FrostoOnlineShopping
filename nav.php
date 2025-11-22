<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>FROSTO - Your Online Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Segoe UI',sans-serif;color:#111}

    .navbar{
      position:sticky;top:0;z-index:1000;
      display:flex;align-items:center;justify-content:space-between;
      padding:16px 10%;
      background:#fff;box-shadow:0 2px 6px rgba(0,0,0,.06);gap:16px
    }
    .nav-left{display:flex;align-items:center;gap:12px}
    .logo{font-weight:800;letter-spacing:.5px;color:#111;text-decoration:none;font-size:22px}
    .highlight{color:#1fc2ff}

    .nav-links{list-style:none;display:flex;align-items:center;gap:20px}
    .nav-links a{color:#333;text-decoration:none;font-weight:500;padding:8px 6px;border-radius:8px;transition:color .2s,background .2s}
    .nav-links a:hover{color:#1e90ff;background:rgba(30,144,255,.08)}
    .nav-links a.active{color:#1fc2ff}
    .only-mobile{display:none}

    .menu-toggle{
      display:none;background:transparent;border:none;cursor:pointer;
      font-size:26px;line-height:1;padding:6px 8px;border-radius:8px
    }

    .backdrop{
      position:fixed;inset:0;background:rgba(0,0,0,.35);
      opacity:0;visibility:hidden;transition:opacity .25s ease;z-index:900
    }
    body.nav-open .backdrop{opacity:1;visibility:visible}

    @media (max-width: 768px){
      .menu-toggle{display:inline-block}
      .nav-links{
        position:fixed;left:0;right:0;top:64px; 
        background:#fff;border-top:1px solid #eee;box-shadow:0 10px 20px rgba(0,0,0,.08);
        display:flex;flex-direction:column;gap:0;padding:8px 0;
        transform:translateY(-16px);opacity:0;visibility:hidden;
        transition:transform .25s ease, opacity .25s ease, visibility 0s .25s;
        z-index:1001
      }
      .nav-links li{width:100%}
      .nav-links a{display:block;width:100%;padding:14px 16px;border-radius:0}

      body.nav-open .nav-links{
        transform:translateY(0);opacity:1;visibility:visible;transition-delay:0s
      }

      .only-mobile{display:block}
    }

    @media (max-width: 420px){
      .logo{font-size:20px}
    }

    body.dark-mode .navbar{background:#1e1e1e;box-shadow:0 2px 6px rgba(0,0,0,.4)}
    body.dark-mode .nav-links{background:#1e1e1e;border-top-color:#2a2a2a}
    body.dark-mode .nav-links a{color:#eaeaea}
    body.dark-mode .nav-links a:hover{background:rgba(77,205,255,.12);color:#4dcdff}
  </style>
</head>
<body>

  <nav class="navbar">
    <div class="nav-left">
      <button class="menu-toggle" aria-label="Toggle menu" aria-expanded="false">☰</button>
      <a href="index.php" class="logo">FR<span class="highlight">O</span>ST<span class="highlight">O</span></a>
    </div>

    <ul class="nav-links" id="primaryNav">
      <li><a href="index.php" class="active">Home</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li><a href="cartmain.php">Cart 🛒</a></li>
      <li class=""><a href="profile.php">Profile</a></li>
      <li class="">
        <a href="login.php">Login</a> · <a href="register.php">Sign Up</a>
      </li>
    </ul>
  </nav>

  <div class="backdrop" id="menuBackdrop"></div>

  <script>
    const toggleBtn = document.querySelector('.menu-toggle');
    const backdrop = document.getElementById('menuBackdrop');
    const navLinks = document.getElementById('primaryNav');

    function closeMenu() {
      document.body.classList.remove('nav-open');
      toggleBtn.setAttribute('aria-expanded', 'false');
    }
    function toggleMenu() {
      const isOpen = document.body.classList.toggle('nav-open');
      toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

      document.documentElement.style.overflow = isOpen ? 'hidden' : '';
      document.body.style.overflow = isOpen ? 'hidden' : '';
    }

    toggleBtn.addEventListener('click', toggleMenu);
    backdrop.addEventListener('click', closeMenu);

    navLinks.addEventListener('click', (e) => {
      if (e.target.tagName === 'A') closeMenu();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeMenu();
    });
  </script>
</body>
</html>
