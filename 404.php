<?php
/**
 * The template for displaying 404 pages (Not Found)
 * Animated & Clean 404 Page for Sailor Theme
 */

get_header();
?>

<!-- Page Title -->
<div class="page-title light-background">
  <div class="container d-lg-flex justify-content-between align-items-center">
    <h1 class="mb-2 mb-lg-0">404 Error</h1>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li class="current">Page Not Found</li>
      </ol>
    </nav>
  </div>
</div>

<style>
/* 404 Animation & Layout Styles */
.error-404-section {
  padding: 90px 0 120px 0;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.error-404-hero {
  position: relative;
  display: inline-block;
  margin-bottom: 30px;
}

.error-404-number {
  font-size: clamp(110px, 20vw, 180px);
  font-weight: 900;
  font-family: var(--heading-font, 'Raleway', sans-serif);
  line-height: 1;
  background: linear-gradient(135deg, #d9232d 0%, #ff6b6b 50%, #1e293b 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  display: inline-block;
  letter-spacing: -4px;
  animation: float404 4s ease-in-out infinite;
  text-shadow: 0 15px 30px rgba(217, 35, 45, 0.15);
}

@keyframes float404 {
  0%, 100% {
    transform: translateY(0) scale(1);
  }
  50% {
    transform: translateY(-16px) scale(1.02);
  }
}

.error-404-icon-orbit {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 90px;
  height: 90px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.95);
  box-shadow: 0 10px 25px rgba(217, 35, 45, 0.25);
  display: flex;
  align-items: center;
  justify-content: center;
  animation: pulseGlow 3s ease-in-out infinite;
}

.error-404-icon-orbit i {
  font-size: 42px;
  color: #d9232d;
  animation: spinCompass 12s linear infinite;
}

@keyframes spinCompass {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes pulseGlow {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(217, 35, 45, 0.4);
    transform: translate(-50%, -50%) scale(1);
  }
  50% {
    box-shadow: 0 0 0 18px rgba(217, 35, 45, 0);
    transform: translate(-50%, -50%) scale(1.08);
  }
}

.error-404-title {
  font-size: clamp(26px, 4vw, 38px);
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 15px;
  font-family: var(--heading-font, 'Raleway', sans-serif);
}

.error-404-text {
  font-size: 18px;
  color: #64748b;
  max-width: 540px;
  margin: 0 auto 40px auto;
  line-height: 1.6;
}

/* 404 Action Button */
.error-404-actions {
  display: flex;
  justify-content: center;
}

.error-btn-primary {
  background: #d9232d;
  color: #ffffff !important;
  padding: 14px 38px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 16px;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  box-shadow: 0 6px 20px rgba(217, 35, 45, 0.3);
  transition: all 0.3s ease;
}

.error-btn-primary:hover {
  background: #b51a22;
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(217, 35, 45, 0.4);
}
</style>

<section class="error-404-section section">
  <div class="container" data-aos="fade-up">

    <!-- Animated 404 Graphic -->
    <div class="error-404-hero">
      <div class="error-404-number">404</div>
      <div class="error-404-icon-orbit">
        <i class="bi bi-compass"></i>
      </div>
    </div>

    <!-- Titles & Description -->
    <h2 class="error-404-title">Oops! Page Not Found</h2>
    <p class="error-404-text">
      The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
    </p>

    <!-- Action Button -->
    <div class="error-404-actions">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="error-btn-primary">
        <i class="bi bi-house-door-fill"></i> Back to Home
      </a>
    </div>

  </div>
</section>

<?php get_footer(); ?>