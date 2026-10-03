    </main>

    <?php 
    global $page;
    $page = $page ?? ($_GET['page'] ?? 'home');
    if (!in_array($page, ['auth', 'admin', 'user'])): 
    ?>
    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-grid">
            <div>
                <div class="footer-brand-name">L'Échappée Sauvage</div>
                <p class="footer-brand-desc">
                    Votre partenaire d'aventure en Tunisie. Nous créons des expériences uniques pour connecter l'homme à la nature sauvage.
                </p>
                <div class="footer-social">
                    <i data-lucide="globe" style="color:rgba(1,38,31,0.5);width:1.25rem;height:1.25rem;"></i>
                    <i data-lucide="share-2" style="color:rgba(1,38,31,0.5);width:1.25rem;height:1.25rem;"></i>
                    <i data-lucide="heart" style="color:rgba(1,38,31,0.5);width:1.25rem;height:1.25rem;"></i>
                </div>
            </div>
            <div class="footer-col">
                <h4 class="footer-col-title">Navigation</h4>
                <ul>
                    <li><a href="index.php?page=randonnee&action=index">Destinations</a></li>
                    <li><a href="#">Équipement</a></li>
                    <li><a href="#">Sécurité</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4 class="footer-col-title">Légal</h4>
                <ul>
                    <li><a href="#">CGU</a></li>
                    <li><a href="#">Confidentialité</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4 class="footer-col-title">Contact</h4>
                <p class="footer-contact">
                    Avenue Habib Bourguiba, Tunis<br/>
                    contact@echappeesauvage.tn<br/>
                    +216 71 000 000
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="footer-copy">© 2026 L'Échappée Sauvage. Passionnément Tunisien.</p>
            <div class="footer-status">
                <span class="footer-status-dot"></span>
                <span class="footer-status-text">Opérationnel 24/7</span>
            </div>
        </div>
    </footer>
    <?php endif; ?>

    <script>lucide.createIcons();</script>
</body>
</html>
