<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    // Datos adicionales (opcionales, para la IA)
    tipo_vivienda:        null,
    numero_hijos:         null,
    nivel_actividad:      null,
    experiencia_mascotas: null,
    tiempo_disponible:    null,
});

const mostrarExtra = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Registro - Woof!" />

    <div id="registro-container">
        <img
            src="/images/woof-logo-removebg-preview.png"
            alt="Logo Woof"
            class="logo-registro"
        />

        <form @submit.prevent="submit" class="formulario-registro">
            <h2 class="form-titulo">Crear cuenta</h2>

            <!-- ── Campos obligatorios ── -->
            <label for="name">Nombre</label>
            <input id="name" type="text" v-model="form.name" required autofocus autocomplete="name"
                :class="{ 'input-error': form.errors.name }" />
            <span v-if="form.errors.name" class="error-msg">{{ form.errors.name }}</span>

            <label for="email">Correo electrónico</label>
            <input id="email" type="email" v-model="form.email" required autocomplete="username"
                :class="{ 'input-error': form.errors.email }" />
            <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>

            <label for="password">Contraseña</label>
            <input id="password" type="password" v-model="form.password" required autocomplete="new-password"
                :class="{ 'input-error': form.errors.password }" />
            <span v-if="form.errors.password" class="error-msg">{{ form.errors.password }}</span>

            <label for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password"
                :class="{ 'input-error': form.errors.password_confirmation }" />
            <span v-if="form.errors.password_confirmation" class="error-msg">{{ form.errors.password_confirmation }}</span>

            <!-- ── Botón datos adicionales ── -->
            <button type="button" class="btn-extra" @click="mostrarExtra = !mostrarExtra">
                <span class="btn-extra-icon">{{ mostrarExtra ? '▲' : '▼' }}</span>
                Datos adicionales
                <span class="btn-extra-badge">opcional</span>
            </button>

            <!-- ── Sección colapsable ── -->
            <div class="extra-section" :class="{ open: mostrarExtra }">
                <p class="extra-desc">
                    🐾 Esta información nos ayuda a recomendarte el perro ideal. Puedes completarla ahora o más tarde.
                </p>

                <label for="tipo_vivienda">Tipo de vivienda</label>
                <select id="tipo_vivienda" v-model="form.tipo_vivienda">
                    <option :value="null">-- Selecciona --</option>
                    <option value="piso">Piso</option>
                    <option value="casa_sin_jardin">Casa sin jardín</option>
                    <option value="casa_con_jardin">Casa con jardín</option>
                </select>

                <label for="numero_hijos">Número de hijos (0 si no tienes)</label>
                <input id="numero_hijos" type="number" min="0" max="20" v-model.number="form.numero_hijos" placeholder="0" />

                <label for="nivel_actividad">Nivel de actividad física</label>
                <select id="nivel_actividad" v-model.number="form.nivel_actividad">
                    <option :value="null">-- Selecciona --</option>
                    <option :value="1">Bajo (poca actividad)</option>
                    <option :value="2">Medio (actividad moderada)</option>
                    <option :value="3">Alto (muy activo)</option>
                </select>

                <label for="experiencia_mascotas">Experiencia con mascotas</label>
                <select id="experiencia_mascotas" v-model.number="form.experiencia_mascotas">
                    <option :value="null">-- Selecciona --</option>
                    <option :value="0">Ninguna</option>
                    <option :value="1">Algo de experiencia</option>
                    <option :value="2">Mucha experiencia</option>
                </select>

                <label for="tiempo_disponible">Horas disponibles al día para el perro</label>
                <input id="tiempo_disponible" type="number" min="1" max="24" v-model.number="form.tiempo_disponible" placeholder="Ej: 3" />
            </div>

            <!-- ── Submit ── -->
            <input
                type="submit"
                value="Crear cuenta"
                :disabled="form.processing"
                :style="form.processing ? 'opacity:0.6;cursor:not-allowed' : ''"
            />

            <div class="link-login">
                ¿Ya tienes cuenta? <Link :href="route('login')">Inicia sesión aquí</Link>
            </div>
        </form>
    </div>
</template>

<style scoped>
#registro-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #d5e5f2 0%, #f2f2f2 100%);
    padding: 2rem;
    gap: 3rem;
    flex-wrap: wrap;
}

.logo-registro {
    width: 380px;
    height: 380px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
}

.formulario-registro {
    background-color: white;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 450px;
    animation: fadeUp 0.4s ease;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

.form-titulo {
    font-size: 1.8rem;
    font-weight: 700;
    color: #d97f11;
    margin-bottom: 2rem;
    text-align: center;
}

.formulario-registro label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.formulario-registro input[type="text"],
.formulario-registro input[type="email"],
.formulario-registro input[type="password"],
.formulario-registro input[type="number"] {
    width: 100%;
    padding: 0.9rem 1.2rem;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 1rem;
    margin-bottom: 1.2rem;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.formulario-registro select {
    width: 100%;
    padding: 0.9rem 1.2rem;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 1rem;
    margin-bottom: 1.2rem;
    transition: all 0.3s ease;
    box-sizing: border-box;
    background: white;
    color: #333;
    cursor: pointer;
}

.formulario-registro input:focus,
.formulario-registro select:focus {
    outline: none;
    border-color: #f2790f;
    box-shadow: 0 0 0 3px rgba(242, 121, 15, 0.1);
}

.input-error {
    border-color: #e53e3e !important;
}

.error-msg {
    display: block;
    color: #e53e3e;
    font-size: 0.85rem;
    margin-top: -0.9rem;
    margin-bottom: 1rem;
}

/* ── Botón datos adicionales ── */
.btn-extra {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    padding: 0.75rem 1.5rem;
    background: #fff8ee;
    border: 2px dashed #f2790f;
    border-radius: 10px;
    color: #d97f11;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    margin: 0.5rem 0 0;
    transition: all 0.3s ease;
}

.btn-extra:hover {
    background: #fff0d6;
    transform: translateY(-1px);
}

.btn-extra-icon {
    font-size: 0.75rem;
    transition: transform 0.3s ease;
}

.btn-extra-badge {
    background: #f2790f;
    color: white;
    font-size: 0.7rem;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    font-weight: 500;
    margin-left: 0.2rem;
}

/* ── Sección colapsable ── */
.extra-section {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease, opacity 0.3s ease, padding 0.3s ease;
    opacity: 0;
    padding: 0;
}

.extra-section.open {
    max-height: 800px;
    opacity: 1;
    padding-top: 1.2rem;
}

.extra-desc {
    background: #f0f7ff;
    border-left: 4px solid #63b3ed;
    border-radius: 8px;
    padding: 0.8rem 1rem;
    font-size: 0.88rem;
    color: #2b6cb0;
    margin-bottom: 1.2rem;
    line-height: 1.5;
}

/* ── Submit ── */
.formulario-registro input[type="submit"] {
    width: 100%;
    padding: 1rem;
    background-color: #f2790f;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 1.2rem;
}

.formulario-registro input[type="submit"]:hover:not(:disabled) {
    background-color: #d97f11;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(242, 121, 15, 0.3);
}

.link-login {
    text-align: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 2px solid #f2f2f2;
    color: #666;
    font-size: 0.95rem;
}

.link-login a {
    color: #f2790f;
    font-weight: 600;
    text-decoration: none;
}

.link-login a:hover {
    text-decoration: underline;
}
</style>
