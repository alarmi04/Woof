<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
</script>

<template>
    <Head title="Panel de Usuario - Woof!" />

    <Header />

    <main class="dashboard-page py-12 bg-gray-50 min-h-screen">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold text-orange-600 mb-6">¡Bienvenido de nuevo, {{ $page.props.auth.user.Nombre }}! 🐾</h1>
            
            <div class="bg-white overflow-hidden shadow-xl rounded-3xl p-8 border border-orange-50 inline-block max-w-md w-full">
                <p class="text-gray-700 text-lg mb-6">Has iniciado sesión correctamente.</p>
                
                <div class="flex flex-col gap-4">
                    <Link href="/home" class="btn-primary">Ir al Inicio</Link>
                    <Link href="/adopta" class="btn-secondary">Ver perros en adopción</Link>
                </div>
            </div>
        </div>
    </main>

    <Footer />
</template>

<style scoped>
.btn-primary {
    display: inline-block;
    padding: 0.85rem 2rem;
    background: #f2790f;
    color: white;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #d97f11;
    transform: translateY(-2px);
}

.btn-secondary {
    display: inline-block;
    padding: 0.85rem 2rem;
    background: transparent;
    color: #f2790f;
    border: 2px solid #f2790f;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #fff4ea;
    transform: translateY(-2px);
}
</style>
