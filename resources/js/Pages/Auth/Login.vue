<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    console.log('Intentando iniciar sesión...', form.email);
    form.post(route('login'), {
        onSuccess: () => {
            console.log('¡Login exitoso en el cliente!');
            window.location.href = route('profile.edit');
        },
        onError: (errors) => console.error('Errores de login:', errors),
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Iniciar sesión - Woof!" />

    <div id="registro-container">
        <img
            src="/images/woof-logo-removebg-preview.png"
            alt="Logo Woof"
            class="logo-registro"
        />

        <form @submit.prevent="submit" class="formulario-registro">
            <h2 class="form-titulo">Iniciar sesión</h2>

            <div v-if="status" class="status-msg">{{ status }}</div>

            <label for="email">Correo electrónico</label>
            <input
                id="email"
                type="email"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
                :class="{ 'input-error': form.errors.email }"
            />
            <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>

            <label for="password">Contraseña</label>
            <input
                id="password"
                type="password"
                v-model="form.password"
                required
                autocomplete="current-password"
                :class="{ 'input-error': form.errors.password }"
            />
            <span v-if="form.errors.password" class="error-msg">{{ form.errors.password }}</span>

            <div class="recuerdame">
                <input id="remember" type="checkbox" v-model="form.remember" />
                <label for="remember" class="label-check">Recuérdame</label>
            </div>

            <input
                type="submit"
                value="Iniciar sesión"
                :disabled="form.processing"
                :style="form.processing ? 'opacity:0.6;cursor:not-allowed' : ''"
            />

            <div class="link-login">
                ¿No tienes cuenta? <Link :href="route('register')">Regístrate aquí</Link>
            </div>
            <div v-if="canResetPassword" class="link-login" style="margin-top:0.5rem; border-top:none; padding-top:0;">
                <Link :href="route('password.request')">¿Olvidaste tu contraseña?</Link>
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

.formulario-registro input[type="email"],
.formulario-registro input[type="password"] {
    width: 100%;
    padding: 0.9rem 1.2rem;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 1rem;
    margin-bottom: 1.2rem;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.formulario-registro input:focus {
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

.status-msg {
    background: #f0fff4;
    border: 1px solid #68d391;
    color: #276749;
    padding: 0.8rem 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.recuerdame {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.recuerdame input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #f2790f;
    cursor: pointer;
}

.label-check {
    font-weight: 500 !important;
    color: #555 !important;
    font-size: 0.9rem !important;
    margin: 0 !important;
    cursor: pointer;
}

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
    margin-top: 0.5rem;
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
