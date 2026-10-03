<!-- Hero Section -->
<section class="hero">
    <div class="hero-bg">
        <img alt="Paysage de montagne tunisien" src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80"/>
        <div class="hero-bg-overlay"></div>
    </div>
    <div class="hero-content">
        <div>
            <span class="hero-badge">L'aventure vous attend</span>
            <h1 class="hero-title">
                Explore Tunisia <br/><span>Like Never Before</span>
            </h1>
            <p class="hero-subtitle">
                Découvrez les sommets du Djebel, les oasis cachées et les sentiers sauvages de la Tunisie avec des guides locaux passionnés.
            </p>
        </div>
        <div class="hero-actions">
            <a href="index.php?page=randonnee&action=index" class="btn-hero-primary">
                Parcourir les Randonnées
            </a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="index.php?page=auth&action=register" class="btn-hero-secondary">
                    Commencer l'Aventure
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Categories Bento Grid -->
<section class="section section--surface">
    <div class="bento-header">
        <h2 class="section-title">Explorez par Écosystème</h2>
        <span class="section-label"></span>
    </div>
    <div class="bento-grid">
        <div class="bento-item bento-item--wide">
            <img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" alt="Forêts"/>
            <div class="bento-overlay">
                <div>
                    <h3 class="bento-title">Forêts du Nord</h3>
                    <p class="bento-sub">Kroumirie et Mogods</p>
                </div>
            </div>
        </div>
        <div class="bento-item">
            <img src="https://images.unsplash.com/photo-1473580044384-7ba9967e16a0?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Désert"/>
            <div class="bento-overlay">
                <div>
                    <h3 class="bento-title bento-title--sm">Désert</h3>
                    <p class="bento-sub">Grand Erg Oriental</p>
                </div>
            </div>
        </div>
        <div class="bento-item">
            <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Montagnes"/>
            <div class="bento-overlay">
                <div>
                    <h3 class="bento-title bento-title--sm">Montagnes</h3>
                    <p class="bento-sub">Djebel Chambi</p>
                </div>
            </div>
        </div>
        <div class="bento-item">
            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Mer"/>
            <div class="bento-overlay">
                <div>
                    <h3 class="bento-title bento-title--sm">Mer &amp; Criques</h3>
                    <p class="bento-sub">Cap Bon &amp; Raf Raf</p>
                </div>
            </div>
        </div>
        <div class="bento-item bento-item--tall3">
            <img src="https://images.unsplash.com/photo-1549417229-aa67d3263c09?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" alt="Circuits"/>
            <div class="bento-overlay bento-overlay--wide">
                <div>
                    <h3 class="bento-title">Circuits Panoramiques</h3>
                    <p class="bento-sub">Patrimoine &amp; Nature</p>
                </div>
                <i data-lucide="arrow-right" style="color:white;width:2rem;height:2rem;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Featured Hikes -->
<section class="section section--low">
    <div class="section-header-flex">
        <div style="max-width:40rem;">
            <h2 class="section-title">Randonnées à la une</h2>
            <p class="section-subtitle">
                Chaque itinéraire est soigneusement sélectionné par nos experts pour sa beauté, son authenticité et sa sécurité.
            </p>
        </div>
        <a href="index.php?page=randonnee&action=index" class="section-link-all">
            Toutes les aventures <i data-lucide="arrow-right" style="width:1rem;height:1rem;"></i>
        </a>
    </div>
    <div class="hikes-grid">
        <?php foreach (array_slice($randonnees, 0, 3) as $r): ?>
            <div class="hike-card">
                <div class="hike-card-image">
                    <img src="<?php echo $r->getImage() ?: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'; ?>" alt="<?php echo htmlspecialchars($r->getTitre()); ?>"/>
                    <div class="hike-card-badges">
                        <span class="hike-badge hike-badge--primary"><?php echo htmlspecialchars($r->getCategorie()); ?></span>
                        <span class="hike-badge hike-badge--secondary">1 Jour</span>
                    </div>
                </div>
                <div class="hike-card-body">
                    <div class="hike-card-header">
                        <h3 class="hike-card-title"><?php echo htmlspecialchars($r->getTitre()); ?></h3>
                        <span class="hike-card-price"><?php echo number_format($r->getPrix(), 2); ?> DT</span>
                    </div>
                    <p class="hike-card-desc"><?php echo mb_strimwidth(htmlspecialchars($r->getDescription()), 0, 100, "..."); ?></p>
                    <div class="hike-card-footer">
                        <div class="hike-card-location">
                            <i data-lucide="map-pin"></i>
                            <span><?php echo htmlspecialchars($r->getRegion()); ?>, Tunisie</span>
                        </div>
                        <a href="index.php?page=randonnee&action=details&id=<?php echo $r->getId(); ?>" class="hike-card-cta">
                            <i data-lucide="plus" style="width:1rem;height:1rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Testimonials -->
<section class="section section--surface">
    <div class="testimonials-center">
        <h2 class="section-title">Ils ont franchi le pas</h2>
        <p class="testimonials-subtitle">Plus de 2,000 aventuriers nous font confiance chaque année.</p>
    </div>
    <div class="testimonials-grid">
        <?php
        $testimonials = [
            ['text' => '"Une expérience incroyable. L\'organisation était parfaite et notre guide connaissait chaque plante, chaque pierre du chemin."', 'name' => 'Sarah Ben Amar', 'role' => 'Randonneuse Passionnée', 'img' => 'https://i.pravatar.cc/150?u=sarah'],
            ['text' => '"J\'ai découvert des paysages en Tunisie que je ne soupçonnais même pas. C\'est plus qu\'une marche, c\'était une reconnexion avec notre terre."', 'name' => 'Mehdi Trabelsi', 'role' => 'Photographe de Nature', 'img' => 'https://i.pravatar.cc/150?u=mehdi'],
            ['text' => '"L\'Echappée Sauvage porte bien son nom. Un service premium, des équipements au top et une sécurité rassurante pour les familles."', 'name' => 'Inès Karoui', 'role' => 'Exploratrice Locale', 'img' => 'https://i.pravatar.cc/150?u=linda'],
        ];
        foreach ($testimonials as $t):
        ?>
        <div class="testimonial-card">
            <i data-lucide="quote" class="testimonial-quote-icon"></i>
            <div class="testimonial-stars">
                <?php for($i=0;$i<5;$i++): ?><i data-lucide="star"></i><?php endfor; ?>
            </div>
            <p class="testimonial-text"><?php echo $t['text']; ?></p>
            <div class="testimonial-author">
                <div class="testimonial-avatar">
                    <img alt="Portrait" src="<?php echo $t['img']; ?>"/>
                </div>
                <div>
                    <h4 class="testimonial-name"><?php echo $t['name']; ?></h4>
                    <p class="testimonial-role"><?php echo $t['role']; ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
