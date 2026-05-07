<script setup>
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Mi Perfil - Woof!" />

    <Header />

    <main class="profile-page py-12 bg-gray-50 min-height-screen">
        <div class="mx-auto max-w-7xl space-y-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-center text-orange-600 mb-8">Gestión de Perfil</h1>

            <!-- Información de Perfil (Incluye datos IA y Foto) -->
            <div class="bg-white p-6 shadow-xl rounded-3xl border border-orange-50">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="w-full"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Cambio de Contraseña -->
                <div class="bg-white p-6 shadow-xl rounded-3xl border border-orange-50">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <!-- Eliminar Cuenta -->
                <div class="bg-white p-6 shadow-xl rounded-3xl border border-red-50">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </main>

    <Footer />
</template>

<style scoped>
.profile-page {
    min-height: 80vh;
}
.min-height-screen {
    min-height: calc(100vh - 20vh); /* Altura del header es 20vh */
}
</style>
