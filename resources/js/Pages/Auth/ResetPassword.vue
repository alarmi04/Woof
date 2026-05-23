<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Restablecer contraseña - Woof!" />

    <div id="registro-container">
        <img
            src="/images/woof-logo-removebg-preview.png"
            alt="Logo Woof"
            class="logo-registro"
        />

        <form @submit.prevent="submit" class="formulario-registro">
            <h2 class="form-titulo">Crear nueva contraseña</h2>

            <label for="email">Correo electrónico</label>
            <input
                id="email"
                type="email"
                v-model="form.email"
                required
                autofocus
                autocomplete="username"
                readonly
                :class="{ 'input-error': form.errors.email }"
                style="background-color: #f9f9f9; color: #777;"
            />
            <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>

            <label for="password">Nueva Contraseña</label>
            <input
                id="password"
                type="password"
                v-model="form.password"
                required
                autocomplete="new-password"
                :class="{ 'input-error': form.errors.password }"
            />
            <span v-if="form.errors.password" class="error-msg">{{ form.errors.password }}</span>

            <label for="password_confirmation">Confirmar Contraseña</label>
            <input
                id="password_confirmation"
                type="password"
                v-model="form.password_confirmation"
                required
                autocomplete="new-password"
                :class="{ 'input-error': form.errors.password_confirmation }"
            />
            <span v-if="form.errors.password_confirmation" class="error-msg">{{ form.errors.password_confirmation }}</span>

            <input
                type="submit"
                value="Restablecer contraseña"
                :disabled="form.processing"
                :style="form.processing ? 'opacity:0.6;cursor:not-allowed' : ''"
            />

            <div class="link-login">
                <Link :href="route('login')">Volver al inicio de sesión</Link>
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
