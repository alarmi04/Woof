<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import { ref } from 'vue';

const props = defineProps({
    ranking: Array,
    materiales: Array
});

/** 
 * Material seleccionado. Por defecto el primero (Dinero).
 */
const materialSeleccionado = ref(props.materiales?.[0] || { idMaterial: 1, Nombre: 'Dinero' });

/** 
 * Determina la unidad de medida según el material.
 */
const getUnidad = (nombre) => {
    switch (nombre) {
        case 'Dinero': return ' €';
        case 'Comida': return ' Kg';
        default: return ' uds';
    }
};

/** 
 * Cantidad predeterminada seleccionada por el usuario.
 */
const cantidadSeleccionada = ref(10);

/** 
 * Cantidad personalizada introducida manualmente por el usuario.
 */
const cantidadPersonalizada = ref('');

/**
 * Formulario reactivo para procesar la donación.
 */
const formulario = useForm({
    cantidad: 10,
    idMaterial: materialSeleccionado.value.idMaterial,
    observaciones: ''
});

/** 
 * Opciones rápidas de donación disponibles en la interfaz.
 */
const opcionesCantidades = [5, 10, 20, 50];

/**
 * Procesa la intención de donación del usuario.
 */
const procesarDonacion = () => {
    formulario.cantidad = cantidadPersonalizada.value || cantidadSeleccionada.value;
    formulario.idMaterial = materialSeleccionado.value.idMaterial;
    
    formulario.post(route('donaciones.store'), {
        preserveScroll: true,
        onSuccess: () => {
            alert('¡Muchísimas gracias por tu donación! Tu ayuda aparecerá en el ranking en unos instantes.');
            cantidadPersonalizada.value = '';
            formulario.reset('observaciones');
        },
    });
};
</script>

<template>
    <Head title="Donaciones" />
    <Header />

    <main class="donaciones-page">
        <!-- Hero Section -->
        <section class="hero-donaciones">
            <div class="hero-content">
                <span class="badge">Tu ayuda salva vidas</span>
                <h1>Ayúdanos a seguir <br><span class="highlight">uniendo familias</span></h1>
                <p>Cada donación se destina íntegramente al cuidado, alimentación y servicios veterinarios de nuestros peludos mientras encuentran su hogar definitivo.</p>
            </div>
        </section>

        <!-- Ranking de Donaciones -->
        <section class="ranking-section">
            <div class="ranking-container">
                <div class="ranking-header">
                    <h2>🏆 Ranking de Generosidad</h2>
                    <p>Personas que están marcando la diferencia</p>
                </div>
                <div class="ranking-grid" v-if="ranking && ranking.length > 0">
                    <div v-for="(donante, index) in ranking" :key="index" class="ranking-card" :class="'top-' + (index + 1)">
                        <div class="rank-number">{{ index + 1 }}</div>
                        <div class="donor-avatar">
                            <img :src="donante.Foto ? '/storage/' + donante.Foto : '/images/default-avatar.png'" alt="Donante">
                        </div>
                        <div class="donor-info">
                            <span class="donor-name">{{ donante.donante }}</span>
                            <span class="donor-total">{{ donante.total }}{{ getUnidad(donante.tipo) }}</span>
                        </div>
                    </div>
                </div>
                <div v-else class="ranking-empty">
                    <p>¡Sé el primero en aparecer en nuestro ranking de colaboradores!</p>
                </div>
            </div>
        </section>

        <!-- Donation Cards -->
        <section class="impacto-section">
            <div class="impacto-grid">
                <div class="impacto-card">
                    <div class="impact-icon">🦴</div>
                    <h3>Alimentación</h3>
                    <p>Con 10€ alimentas a un perro durante una semana entera.</p>
                </div>
                <div class="impacto-card">
                    <div class="impact-icon">🏥</div>
                    <h3>Salud</h3>
                    <p>Con 50€ cubres una revisión veterinaria completa y vacunas.</p>
                </div>
                <div class="impacto-card">
                    <div class="impact-icon">🏠</div>
                    <h3>Refugio</h3>
                    <p>Con tu ayuda mantenemos nuestras instalaciones seguras y cálidas.</p>
                </div>
            </div>
        </section>

        <!-- Donation Form -->
        <section class="donar-form-section">
            <div class="donar-container">
                    <div class="donar-box">
                        <h2>Haz tu donación</h2>
                        <p class="donar-desc">Elige qué quieres donar y la cantidad para colaborar con Woof!</p>
                        
                        <!-- Selector de Material -->
                        <div class="material-selector">
                            <label>¿Qué deseas donar?</label>
                            <div class="material-grid">
                                <button 
                                    v-for="mat in materiales" 
                                    :key="mat.idMaterial"
                                    @click="materialSeleccionado = mat"
                                    :class="{ active: materialSeleccionado.idMaterial === mat.idMaterial }"
                                    type="button"
                                >
                                    {{ mat.Nombre }}
                                </button>
                            </div>
                        </div>

                        <div class="amount-selector">
                            <button 
                                v-for="cantidad in opcionesCantidades" 
                                :key="cantidad"
                                @click="cantidadSeleccionada = cantidad; cantidadPersonalizada = ''"
                                :class="{ active: cantidadSeleccionada === cantidad && !cantidadPersonalizada }"
                                type="button"
                            >
                                {{ cantidad }}{{ getUnidad(materialSeleccionado.Nombre) }}
                            </button>
                        </div>

                        <div class="custom-amount">
                            <input 
                                type="number" 
                                v-model="cantidadPersonalizada" 
                                :placeholder="'Otra cantidad (' + getUnidad(materialSeleccionado.Nombre) + ')'"
                                @input="cantidadSeleccionada = null"
                            >
                            <span class="currency">{{ getUnidad(materialSeleccionado.Nombre) }}</span>
                        </div>

                        <div class="observations-field">
                            <textarea 
                                v-model="formulario.observaciones" 
                                placeholder="Escribe un mensaje u observaciones opcionales..."
                                rows="2"
                            ></textarea>
                        </div>

                        <button @click="procesarDonacion" class="donate-submit-btn">
                            Donar {{ cantidadPersonalizada || cantidadSeleccionada }}{{ getUnidad(materialSeleccionado.Nombre) }} ahora
                        </button>

                    <div class="payment-methods">
                        <p>Pagos seguros con:</p>
                        <div class="method-icons">
                            <span title="Visa">💳</span>
                            <span title="Mastercard">🎴</span>
                            <span title="PayPal">🅿️</span>
                            <span title="Bizum">📱</span>
                        </div>
                    </div>
                </div>

                <div class="donar-image">
                    <img src="/images/perrera.jpg" alt="Perro agradecido">
                </div>
            </div>
        </section>
    </main>

    <Footer />
</template>

<style scoped>
.donaciones-page {
    background-color: #fcfcfc;
    min-height: 100vh;
}

/* Hero */
.hero-donaciones {
    background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1548199973-03cce0bbc87b?q=80&w=2069&auto=format&fit=crop');
    background-size: cover;
    background-position: center;
    height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
}

.hero-content {
    max-width: 800px;
    padding: 2rem;
}

.badge {
    background: #f2790f;
    padding: 0.5rem 1.2rem;
    border-radius: 50px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 1px;
    margin-bottom: 1.5rem;
    display: inline-block;
}

.hero-content h1 {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.highlight {
    color: #f2d888;
}

.hero-content p {
    font-size: 1.2rem;
    opacity: 0.9;
}

/* Ranking Section */
.ranking-section {
    padding: 4rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.ranking-header {
    text-align: center;
    margin-bottom: 3rem;
}

.ranking-header h2 {
    font-size: 2.5rem;
    color: #333;
    font-weight: 800;
}

.ranking-header p {
    color: #f2790f;
    font-weight: 600;
    font-size: 1.1rem;
}

.ranking-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.ranking-card {
    background: white;
    padding: 1.5rem 2rem;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.03);
    border: 1px solid #f1f1f1;
    transition: all 0.3s ease;
}

.ranking-card:hover {
    transform: scale(1.03);
    border-color: #f2790f;
}

.rank-number {
    font-size: 1.5rem;
    font-weight: 900;
    color: #ccc;
    width: 40px;
}

.donor-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #eee;
}

.donor-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.top-1 .rank-number { color: #FFD700; font-size: 2rem; }
.top-2 .rank-number { color: #C0C0C0; font-size: 1.8rem; }
.top-3 .rank-number { color: #CD7F32; font-size: 1.6rem; }

.top-1 { border: 2px solid #FFD700; background: #fffdf0; }

.donor-info {
    display: flex;
    flex-direction: column;
}

.donor-name {
    font-weight: 700;
    color: #333;
    font-size: 1.1rem;
}

.donor-total {
    color: #f2790f;
    font-weight: 800;
    font-size: 1.2rem;
}

.ranking-empty {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 20px;
    color: #999;
    border: 2px dashed #eee;
}

/* Impacto */
.impacto-section {
    padding: 5rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.impacto-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: -100px;
}

.impacto-card {
    background: white;
    padding: 3rem 2rem;
    border-radius: 24px;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
}

.impacto-card:hover {
    transform: translateY(-10px);
}

.impact-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
}

.impacto-card h3 {
    color: #d97f11;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.impacto-card p {
    color: #666;
    line-height: 1.6;
}

/* Form Section */
.donar-form-section {
    padding: 5rem 2rem;
    background-color: #fdf2e9;
}

.donar-container {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.donar-box {
    background: white;
    padding: 3.5rem;
    border-radius: 32px;
    box-shadow: 0 30px 60px rgba(217, 127, 17, 0.1);
}

.donar-box h2 {
    font-size: 2.2rem;
    color: #333;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.donar-desc {
    color: #666;
    margin-bottom: 2.5rem;
}

.amount-selector {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.amount-selector button {
    padding: 1rem;
    border: 2px solid #eee;
    background: white;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.0rem;
    color: #555;
    cursor: pointer;
    transition: all 0.2s ease;
}

.amount-selector button:hover {
    border-color: #f2790f;
    color: #f2790f;
}

.amount-selector button.active {
    background: #f2790f;
    border-color: #f2790f;
    color: white;
}

.custom-amount {
    position: relative;
    margin-bottom: 2rem;
}

.custom-amount input {
    width: 100%;
    padding: 1rem 3rem 1rem 1.5rem;
    border: 2px solid #eee;
    border-radius: 12px;
    font-size: 1.1rem;
    outline: none;
    transition: border-color 0.2s;
}

.custom-amount input:focus {
    border-color: #f2790f;
}

.currency {
    position: absolute;
    right: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 700;
    color: #999;
}

.material-selector {
    margin-bottom: 2rem;
}

.material-selector label {
    display: block;
    font-weight: 700;
    color: #555;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    text-transform: uppercase;
}

.material-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 0.8rem;
}

.material-grid button {
    padding: 0.8rem;
    border: 2px solid #eee;
    background: #f9f9f9;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #666;
    cursor: pointer;
    transition: all 0.2s ease;
}

.material-grid button:hover {
    border-color: #f2790f;
    color: #f2790f;
}

.material-grid button.active {
    background: #f2790f;
    border-color: #f2790f;
    color: white;
}

.observations-field {
    margin-bottom: 2rem;
}

.observations-field textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #eee;
    border-radius: 12px;
    font-size: 1rem;
    outline: none;
    resize: none;
    transition: border-color 0.2s;
}

.observations-field textarea:focus {
    border-color: #f2790f;
}

.donate-submit-btn {
    width: 100%;
    padding: 1.2rem;
    background: #f2790f;
    color: white;
    border: none;
    border-radius: 15px;
    font-size: 1.2rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(242, 121, 15, 0.3);
}

.donate-submit-btn:hover {
    background: #d97f11;
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(242, 121, 15, 0.4);
}

.payment-methods {
    margin-top: 2.5rem;
    text-align: center;
}

.payment-methods p {
    font-size: 0.85rem;
    color: #999;
    margin-bottom: 0.8rem;
}

.method-icons {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    font-size: 1.8rem;
    opacity: 0.7;
}

.donar-image img {
    width: 100%;
    border-radius: 40px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

@media (max-width: 900px) {
    .donar-container {
        grid-template-columns: 1fr;
    }
    .donar-image {
        order: -1;
    }
    .hero-content h1 {
        font-size: 2.5rem;
    }
}
</style>
