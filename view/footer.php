          </div>
        </div>
      </div>
    </main>

    <!-- Scroll to Top -->
    <button class="btn scroll-to-top" data-scroll-top="data-scroll-top">
      <span class="uil uil-angle-up text-white"></span>
    </button>

    <!-- Footer -->
    <footer class="Footer" style="background-image: url('../assets/img/illustrations/BOTTOM.png')">
      <div class="pb-x1 px-3 px-lg-0">
        <div class="container">
          <div class="row align-items-end g-4 g-sm-6">
            <div class="col-6 col-md-4">
              <div class="mb-6 mb-md-8">
                <a class="cursor-pointer" href="../index.php">
                  <img class="img-fluid" src="../assets/img/logos/Footer_logo.png" alt="GreenMind" />
                </a>
              </div>
              <div>
                <div class="mb-2"><a class="links" href="../index.php">Accueil</a></div>
                <div class="mb-2"><a class="links" href="evenements">Événements</a></div>
                <div class="mb-2"><a class="links" href="../produits">Produits</a></div>
                <div class="mb-2"><a class="links" href="../contact">Contact</a></div>
                <div class="mb-2"><a class="links" href="../dashboard">Dashboard</a></div>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="mb-3 mb-md-5">
                <h3 class="fs-9 fs-md-8 fw-bold mb-0" style="color: #FFF0D3;">Mon Compte</h3>
              </div>
              <div>
                <div class="mb-2"><a class="links" href="#!">Inscription</a></div>
                <div class="mb-2"><a class="links" href="#!">Connexion</a></div>
                <div class="mb-2"><a class="links" href="#!">Mon profil</a></div>
                <div class="mb-2"><a class="links" href="#!">Historique</a></div>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="row g-4 g-sm-6 g-md-0">
                <div class="col-6 col-md-12 mb-md-7">
                  <h3 class="fs-9 fs-md-8 fw-bold mb-3" style="color: #FFF0D3;">Suivez-nous</h3>
                  <div class="d-flex align-items-center">
                    <a class="social-icon me-2" href="#!"><span class="uil uil-facebook fs-8"></span></a>
                    <a class="social-icon me-2" href="#!"><span class="uil uil-linkedin fs-8"></span></a>
                    <a class="social-icon me-2" href="#!"><span class="uil uil-youtube fs-8"></span></a>
                    <a class="social-icon" href="#!"><span class="uil uil-twitter fs-8"></span></a>
                  </div>
                </div>
                <div class="col-6 col-md-12">
                  <div class="mb-3">
                    <p class="fs-9 fs-md-8 fw-bold lh-nomal mb-0" style="color: #FFF0D3;">Contact</p>
                  </div>
                  <div class="fs-10 fs-md-9">
                    <p class="mb-0 text-white opacity-70">GreenMind</p>
                    <p class="mb-0 text-white opacity-70">123 Rue des Plantes, 75000 Paris</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-success py-2 py-md-3 position-relative terms-condition">
        <div class="overley-background"></div>
        <div class="container py-12 text-div text-md-end">
          <a class="links ms-md-4" href="#!">F.A.Q</a>
          <a class="links ms-3 ms-md-4" href="#!">Mentions légales</a>
          <a class="links ms-3 ms-md-4" href="#!">CGV</a>
          <a class="links ms-3 ms-md-4" href="#!">Politique de confidentialité</a>
        </div>
      </div>
    </footer>

    <!-- JavaScripts -->
    <script src="../vendors/popper/popper.min.js"></script>
    <script src="../vendors/bootstrap/bootstrap.min.js"></script>
    <script src="../vendors/is/is.min.js"></script>
    <script src="../vendors/swiper/swiper-bundle.min.js"></script>
    <script src="../vendors/lodash/lodash.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="../vendors/list.js/list.min.js"></script>
    <script src="../assets/js/theme.js"></script>

    <script>
      // Loading animation
      window.addEventListener('load', function() {
        document.querySelector('.loading').style.display = 'none';
      });

      // Active nav item
      document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        if(link.getAttribute('href') === window.location.pathname.split('/').pop()) {
          link.classList.add('active');
        }
      });
    </script>
  </body>
</html> 