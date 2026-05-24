<script setup>
import { computed, ref, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    perros: Array,
    perfilIncompleto: Boolean
});

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);

// ================= ESTADO Y LÓGICA DEL MODAL DE FICHA =================
const perroSeleccionado = ref(null);

const abrirFicha = (perro) => {
    perroSeleccionado.value = perro;
    document.body.style.overflow = 'hidden';
};

const cerrarFicha = () => {
    perroSeleccionado.value = null;
    document.body.style.overflow = '';
};

onUnmounted(() => {
    document.body.style.overflow = '';
});

const getImageUrl = (perro) => {
    if (!perro) return '/images/default-perro.jpg';
    const img = perro.Imagen || perro.imagen;
    if (!img) return '/images/default-perro.jpg';
    if (img.startsWith('http')) return img;
    return img.includes('storage') ? img : '/storage/' + img;
};

const generarUrlContactoAdopcion = () => {
    if (!perroSeleccionado.value) return '#';
    
    const nombrePerro = perroSeleccionado.value.Nombre || 'el perro';
    const mensaje = `Hola, me gustaría tener más información para adoptar a ${nombrePerro}.`;
    
    return route('contacto', { 
        asunto: 'adopcion', 
        mensaje: mensaje
    });
};
</script>

<template>
    <Header />

    <section id="perros-recomendados">

        <!-- ======= USUARIO NO AUTENTICADO ======= -->
        <div v-if="!user" class="auth-wall">
            <div class="auth-card">
                <div class="auth-icon">🐾</div>
                <h1>Encuentra tu perro ideal</h1>
                <p>
                    Nuestro sistema de recomendación personalizada analiza tu estilo de vida
                    para encontrar el compañero perfecto para ti.
                </p>
                <p class="auth-hint">Necesitas iniciar sesión para acceder a esta funcionalidad.</p>
                <div class="auth-buttons">
                    <Link href="/login" class="btn-primary">Iniciar sesión</Link>
                    <Link href="/register" class="btn-secondary">Crear cuenta gratis</Link>
                </div>
            </div>
        </div>

        <!-- ======= USUARIO AUTENTICADO PERO PERFIL INCOMPLETO ======= -->
        <div v-else-if="perfilIncompleto" class="auth-wall">
            <div class="auth-card">
                <div class="auth-icon">🤖</div>
                <h1>Casi listo, {{ user.Nombre }}</h1>
                <p>
                    Para que nuestro recomendador de IA pueda encontrar a tu compañero ideal, 
                    necesitamos que completes unos datos adicionales en tu perfil.
                </p>
                <div class="auth-hint">
                    Faltan datos como tu tipo de vivienda, nivel de actividad o experiencia.
                </div>
                <div class="auth-buttons">
                    <Link href="/profile" class="btn-primary">Completar mi perfil</Link>
                </div>
            </div>
        </div>

        <!-- ======= USUARIO AUTENTICADO Y COMPLETO ======= -->
        <div v-else class="recomendados-container">
            <div class="header-content">
                <h1>Hola, {{ user.Nombre }} 👋</h1>
                <p class="subtitle">Según tu perfil, estos son los compañeros que mejor encajarían contigo:</p>
            </div>

            <div v-if="perros && perros.length > 0" class="perros-grid">
                <div v-for="(perro, index) in perros" :key="perro.idAnimal" class="perro-card">
                    <div class="perro-badge" v-if="index === 0">Mejor Opción ⭐</div>
                    <div class="perro-img-wrapper">
                        <img 
                            :src="getImageUrl(perro)" 
                            :alt="perro.Nombre"
                        >
                    </div>
                    <div class="perro-info">
                        <h3>{{ perro.Nombre }}</h3>
                        <p class="raza">{{ perro.Raza }}</p>
                        <div class="perro-tags">
                            <span>{{ perro.Tamano }}</span>
                            <span>{{ isNaN(perro.Edad) ? perro.Edad : perro.Edad + ' años' }}</span>
                        </div>
                        <p class="match-reason" v-if="perro.match_reason">
                            {{ perro.match_reason }}
                        </p>
                        <button @click="abrirFicha(perro)" class="ver-mas-btn" style="cursor: pointer;">Conocer a {{ perro.Nombre }}</button>
                    </div>
                </div>
            </div>
            
            <div v-else class="no-perros">
                <p>Aún no tenemos suficientes datos para darte recomendaciones personalizadas.</p>
                <Link href="/profile" class="btn-primary">Completar mi perfil</Link>
            </div>
        </div>

    </section>

    <!-- MODAL DE FICHA DEL PERRO -->
    <div v-if="perroSeleccionado" class="modal-overlay" @click.self="cerrarFicha">
        <div class="modal-content">
            <button class="modal-close" @click="cerrarFicha">×</button>
            <div class="modal-body">
                <div class="modal-img-container">
                    <img :src="getImageUrl(perroSeleccionado)" :alt="perroSeleccionado.Nombre" />
                </div>
                <div class="modal-info">
                    <h2>{{ perroSeleccionado.Nombre }}</h2>
                    <p class="raza">{{ perroSeleccionado.Raza }}</p>
                    
                    <div class="modal-tags">
                        <span>🐶 {{ perroSeleccionado.Tamano }}</span>
                        <span>🎂 {{ isNaN(perroSeleccionado.Edad) ? perroSeleccionado.Edad : perroSeleccionado.Edad + ' años' }}</span>
                        <span>⚧️ {{ perroSeleccionado.Genero }}</span>
                    </div>

                    <p class="modal-desc">{{ perroSeleccionado.Descripcion }}</p>

                    <!-- SECCIÓN DE COMPATIBILIDAD IA -->
                    <div class="compatibilidad-section">
                        <h3>🤖 Análisis de Compatibilidad IA</h3>
                        <div class="compat-box success">
                            <div class="compat-header">
                                <span class="compat-icon">✨</span>
                                <h4>¡Alta Compatibilidad!</h4>
                            </div>
                            <p>{{ perroSeleccionado.match_reason || 'Un compañero equilibrado para ti.' }}</p>
                        </div>
                    </div>

                    <!-- BOTÓN DE ADOPCIÓN -->
                    <div class="modal-actions" style="margin-top: 2rem; text-align: center;">
                        <Link :href="generarUrlContactoAdopcion()" class="btn-adoptar">
                            🐾 ¡Quiero Adoptar a {{ perroSeleccionado.Nombre }}!
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Footer />
</template>

<style scoped>
#perros-recomendados {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
}

/* ── Auth wall ── */
.auth-wall {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.auth-card {
    background: white;
    border-radius: 24px;
    padding: 3.5rem 3rem;
    max-width: 520px;
    width: 100%;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
    animation: fadeUp 0.5s ease;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}

.auth-icon {
    font-size: 4rem;
    margin-bottom: 1.2rem;
}

.auth-card h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #d97f11;
    margin-bottom: 1rem;
}

.auth-card p {
    color: #555;
    font-size: 1.05rem;
    line-height: 1.7;
    margin-bottom: 0.8rem;
}

.auth-hint {
    background: #fff8ee;
    border-left: 4px solid #f2790f;
    border-radius: 8px;
    padding: 0.8rem 1rem;
    color: #b86000 !important;
    font-weight: 500;
    font-size: 0.95rem !important;
    margin: 1.5rem 0 2rem !important;
}

.auth-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-primary {
    padding: 0.85rem 2rem;
    background: #f2790f;
    color: white;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(242, 121, 15, 0.35);
}

.btn-primary:hover {
    background: #d97f11;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(242, 121, 15, 0.45);
}

.btn-secondary {
    padding: 0.85rem 2rem;
    background: transparent;
    color: #f2790f;
    border: 2px solid #f2790f;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #fff4ea;
    transform: translateY(-2px);
}

/* ── Contenido autenticado ── */
.recomendados-container {
    max-width: 1200px;
    width: 100%;
    align-self: flex-start;
}

.header-content {
    margin-bottom: 3rem;
}

.recomendados-container h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #d97f11;
    margin-bottom: 0.8rem;
}

.subtitle {
    color: #666;
    font-size: 1.15rem;
}

.perros-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2.5rem;
}

.perro-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
}

.perro-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(242, 121, 15, 0.15);
}

.perro-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: #f2790f;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 700;
    z-index: 10;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.perro-img-wrapper {
    height: 250px;
    overflow: hidden;
}

.perro-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.perro-card:hover .perro-img-wrapper img {
    transform: scale(1.1);
}

.perro-info {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.perro-info h3 {
    font-size: 1.4rem;
    color: #333;
    margin-bottom: 0.3rem;
}

.raza {
    color: #f2790f;
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 1rem;
}

.match-reason {
    background: #fdf2e9;
    color: #af601a;
    padding: 0.8rem;
    border-radius: 10px;
    font-size: 0.85rem;
    line-height: 1.4;
    margin-bottom: 1.5rem;
    border-left: 3px solid #f2790f;
}

.perro-tags {
    display: flex;
    gap: 0.8rem;
    margin-bottom: 1.5rem;
}

.perro-tags span {
    background: #f8f9fa;
    color: #666;
    padding: 0.3rem 0.8rem;
    border-radius: 8px;
    font-size: 0.85rem;
    border: 1px solid #eee;
}

.ver-mas-btn {
    margin-top: auto;
    text-align: center;
    background: #fff;
    color: #f2790f;
    border: 2px solid #f2790f;
    padding: 0.7rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.ver-mas-btn:hover {
    background: #f2790f;
    color: white;
}

.no-perros {
    text-align: center;
    padding: 4rem;
    background: white;
    border-radius: 24px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.no-perros p {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 2rem;
}

/* Modal Ficha Perro */
.modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 1rem;
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background: white;
    border-radius: 20px;
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    animation: slideUp 0.3s ease;
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 2rem;
    background: none;
    border: none;
    color: #333;
    cursor: pointer;
    z-index: 10;
    transition: color 0.2s;
}

.modal-close:hover {
    color: #f2790f;
}

.modal-body {
    display: flex;
    flex-direction: column;
}

@media (min-width: 768px) {
    .modal-body {
        flex-direction: row;
    }
}

.modal-img-container {
    flex: 1;
    min-height: 300px;
}

.modal-img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 20px 20px 0 0;
}

@media (min-width: 768px) {
    .modal-img-container img {
        border-radius: 20px 0 0 20px;
    }
}

.modal-info {
    flex: 1.5;
    padding: 2.5rem;
    text-align: left;
}

.modal-info h2 {
    font-size: 2.2rem;
    color: #d97f11;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.modal-info .raza {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.modal-tags {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}

.modal-tags span {
    background: #fef3c7;
    color: #b45309;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
}

.modal-desc {
    line-height: 1.6;
    color: #555;
    margin-bottom: 2rem;
    font-size: 1.05rem;
}

.compatibilidad-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 15px;
    border: 1px solid #eee;
}

.compatibilidad-section h3 {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.compat-box {
    padding: 1rem;
    border-radius: 12px;
}

.compat-box.success {
    background: #f0fdf4;
    border-left: 4px solid #22c55e;
}

.compat-header {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-bottom: 0.5rem;
}

.compat-header h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    color: #1f2937;
}

.compat-icon {
    font-size: 1.5rem;
}

.compat-box p {
    margin-bottom: 0;
    color: #4b5563;
    font-size: 0.95rem;
    line-height: 1.5;
}

.btn-adoptar {
    display: inline-block;
    width: 100%;
    padding: 1.2rem;
    background: linear-gradient(135deg, #f2790f, #d97f11);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.2rem;
    box-shadow: 0 10px 20px rgba(242, 121, 15, 0.3);
    transition: all 0.3s ease;
}

.btn-adoptar:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 25px rgba(242, 121, 15, 0.4);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>