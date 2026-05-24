<script setup>
import Header from '@/Components/Header.vue';
import { useForm, usePage, Head } from '@inertiajs/vue3';
import { computed, onMounted, watch } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const form = useForm({
    nombre: user.value ? user.value.Nombre : '',
    email: user.value ? user.value.Correo : '',
    asunto: '',
    mensaje: ''
});

// Rellenar el formulario cuando se cargan los parámetros de query
onMounted(() => {
    // Intentar obtener de los query parameters
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('asunto')) {
        const asunto = urlParams.get('asunto');
        form.asunto = asunto;
    }
    
    if (urlParams.has('mensaje')) {
        const mensaje = urlParams.get('mensaje');
        form.mensaje = mensaje;
    }
});

const submit = () => {
    form.post(route('contacto.send'), {
        preserveScroll: true,
        onSuccess: () => {
            alert('¡Gracias por contactarnos! Te responderemos lo antes posible.');
            form.reset('asunto', 'mensaje');
        }
    });
};
</script>

<template>
    <Head title="Contacto" />
    <Header></Header>
    <main class="contacto-container">
        <div class="contacto-hero">
            <h1>Contáctanos</h1>
            <p>Estamos aquí para ayudarte en todo lo que necesites sobre adopciones, voluntariado o consultas generales.</p>
        </div>

        <div class="contacto-grid">
            <div class="contacto-card">
                <div class="card-icon">📞</div>
                <h2>Teléfono</h2>
                <p>+34 900 123 456</p>
            </div>

            <div class="contacto-card">
                <div class="card-icon">✉️</div>
                <h2>Email</h2>
                <p>woofadopta@gmail.com</p>
            </div>

            <div class="contacto-card">
                <div class="card-icon">📍</div>
                <h2>Dirección</h2>
                <p>Valencia, Comunidad Valenciana</p>
            </div>
        </div>

        <div class="contacto-form-section">
            <div class="form-container">
                <h2>Envíanos un Mensaje</h2>
                <p>Si tienes dudas o necesitas ayuda, completa el formulario y te responderemos pronto.</p>

                <form @submit.prevent="submit" class="contacto-form">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" v-model="form.nombre" placeholder="Introduce tu nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" v-model="form.email" placeholder="tu@email.com" required :readonly="!!user">
                    </div>

                    <div class="form-group">
                        <label for="asunto">Asunto</label>
                        <select id="asunto" v-model="form.asunto" required>
                            <option value="">Selecciona un asunto</option>
                            <option value="adopcion">Consulta sobre adopción</option>
                            <option value="voluntariado">Información sobre voluntariado</option>
                            <option value="donaciones">Dudas sobre donaciones</option>
                            <option value="general">Consulta general</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" v-model="form.mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
                    </div>

                    <button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Enviando...' : 'Enviar Mensaje' }}
                    </button>
                </form>
            </div>
        </div>
    </main>
</template>

<style scoped>
.contacto-container {
    font-family: 'Figtree', sans-serif;
}

.contacto-hero {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(to bottom, #fef3c7, #fde68a);
}

.contacto-hero h1 {
    font-size: 3rem;
    font-weight: 800;
    color: #9a3412;
    margin-bottom: 1rem;
}

.contacto-hero p {
    font-size: 1.25rem;
    color: #78350f;
}

.contacto-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    padding: 3rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.contacto-card {
    background: white;
    padding: 2.5rem 2rem;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: all 0.3s ease;
}

.contacto-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.card-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
}

.contacto-card h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #9a3412;
    margin-bottom: 1rem;
}

.contacto-card p {
    font-size: 1.1rem;
    color: #555;
    line-height: 1.6;
}

.contacto-form-section {
    padding: 4rem 2rem;
    background-color: #fdf2e9;
}

.form-container {
    max-width: 700px;
    margin: 0 auto;
    background: white;
    padding: 3rem;
    border-radius: 16px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.form-container h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #9a3412;
    text-align: center;
    margin-bottom: 0.5rem;
}

.form-container p {
    text-align: center;
    color: #555;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #78350f;
    margin-bottom: 0.5rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #fca5a5;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.2s ease;
    color: #333;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #ea580c;
    box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1);
}

button[type="submit"] {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(to right, #f97316, #ea580c);
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 1.25rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
}

button[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(234, 88, 12, 0.4);
}

@media (max-width: 768px) {
    .contacto-hero h1 {
        font-size: 2rem;
    }

    .contacto-hero p {
        font-size: 1rem;
    }
}
</style>