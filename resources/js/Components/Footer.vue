<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section links">
                <h4>Enlaces</h4>
                <ul>
                    <li><Link href="/home">Inicio</Link></li>
                    <li><Link href="/adopta">Adopta</Link></li>
                    <li><Link href="/perros-recomendados">Perros recomendados</Link></li>
                    <li><Link href="/contacto">Contacto</Link></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h4>Contáctanos</h4>
                <p>📍 Valencia, España</p>
                <p>📞 +34 640 85 08 31</p>
                <p>✉️ woofadopta@gmail.com</p>
            </div>
            <div class="footer-section social">
                <h4>Síguenos</h4>
                <ul>
                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                </ul>
            </div>
        </div>
    </footer>
</template>

<style>
.footer {
  background-color: #d5e5f2;
  color: #333;
  padding: 3rem 2rem 1rem;
  box-shadow: 0 -0.2rem 0.8rem rgba(0, 0, 0, 0.1);
}

.footer-content {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
  gap: 2rem;
  max-width: 80rem;
  margin: 0 auto;
  text-align: center;
}

.footer-section {
  flex: 1 1 20rem;
  min-width: 18rem;
}

.footer-section h3,
.footer-section h4 {
  margin-bottom: 1rem;
  color: #d97f11;
}

.footer-section p {
  line-height: 1.6;
  color: #555;
}

.footer-section ul {
  list-style: none;
  padding: 0;
}

.footer-section li {
  margin-bottom: 0.5rem;
}

.footer-section a {
  text-decoration: none;
  color: #555;
  transition: color 0.2s;
}

.footer-section a:hover {
  color: #f2790f;
}

.footer-bottom {
  text-align: center;
  border-top: 0.1rem solid #ddd;
  margin-top: 2rem;
  padding-top: 1rem;
  font-size: 0.9rem;
  color: #777;
}

</style>