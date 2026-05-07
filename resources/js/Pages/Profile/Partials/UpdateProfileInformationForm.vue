<script setup>
import { computed, ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

/**
 * Propiedades del componente.
 */
defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

/**
 * Acceso reactivo a los datos del usuario autenticado.
 */
const pagina = usePage();
const usuario = computed(() => pagina.props.auth.user);

/**
 * Formulario reactivo para la actualización del perfil.
 */
const form = useForm({
    _method: 'patch',
    name: usuario.value?.Nombre || '',
    email: usuario.value?.Correo || '',
    telefono: usuario.value?.Telefono || '',
    direccion: usuario.value?.Direccion || '',
    tipo_vivienda: usuario.value?.Tipo_vivienda || '',
    numero_hijos: usuario.value?.Numero_hijos || 0,
    nivel_actividad: usuario.value?.Nivel_actividad || null,
    experiencia_mascotas: usuario.value?.Experiencia_mascotas || null,
    tiempo_disponible: usuario.value?.Tiempo_disponible || null,
    foto: null,
});

const fotoPreview = ref(null);

const onFileChange = (e) => {
    const file = e.target.files[0];
    form.foto = file;
    if (file) {
        fotoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.foto = null;
            fotoPreview.value = null;
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-xl font-bold text-orange-600">
                Información del Perfil
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Actualiza tu información personal y los datos para tu perfil de adoptante.
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6" enctype="multipart/form-data">
            <!-- Foto de Perfil -->
            <div class="flex flex-col items-center sm:flex-row sm:items-start gap-6">
                <div class="relative">
                    <img
                        :src="fotoPreview || (usuario.Foto ? '/storage/' + usuario.Foto : '/images/default-avatar.png')"
                        alt="Foto de perfil"
                        class="w-32 h-32 rounded-full object-cover border-4 border-orange-200"
                    />
                    <div class="mt-2 text-center">
                        <InputLabel for="foto" value="Foto de Perfil" class="cursor-pointer text-orange-600 hover:underline" />
                        <input
                            id="foto"
                            type="file"
                            class="hidden"
                            @change="onFileChange"
                            accept="image/*"
                        />
                    </div>
                </div>
                <div class="flex-1 space-y-6 w-full">
                    <!-- Nombre -->
                    <div>
                        <InputLabel for="name" value="Nombre" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <!-- Correo -->
                    <div>
                        <InputLabel for="email" value="Correo Electrónico" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                </div>
            </div>

            <!-- Contacto -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel for="telefono" value="Teléfono" />
                    <TextInput
                        id="telefono"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.telefono"
                    />
                    <InputError class="mt-2" :message="form.errors.telefono" />
                </div>
                <div>
                    <InputLabel for="direccion" value="Dirección" />
                    <TextInput
                        id="direccion"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.direccion"
                    />
                    <InputError class="mt-2" :message="form.errors.direccion" />
                </div>
            </div>

            <hr class="border-orange-100" />

            <!-- Datos Adicionales para IA -->
            <div class="bg-orange-50 p-6 rounded-2xl space-y-6">
                <h3 class="text-lg font-semibold text-orange-700 flex items-center gap-2">
                    <span>🤖</span> Perfil de Adoptante (IA)
                </h3>
                <p class="text-sm text-orange-600">
                    Completa estos datos para que nuestro sistema te recomiende el compañero ideal.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="tipo_vivienda" value="Tipo de Vivienda" />
                        <select
                            id="tipo_vivienda"
                            v-model="form.tipo_vivienda"
                            class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                        >
                            <option value="">-- Selecciona --</option>
                            <option value="piso">Piso</option>
                            <option value="casa_sin_jardin">Casa sin jardín</option>
                            <option value="casa_con_jardin">Casa con jardín</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.tipo_vivienda" />
                    </div>

                    <div>
                        <InputLabel for="numero_hijos" value="Número de Hijos" />
                        <TextInput
                            id="numero_hijos"
                            type="number"
                            min="0"
                            class="mt-1 block w-full"
                            v-model="form.numero_hijos"
                        />
                        <InputError class="mt-2" :message="form.errors.numero_hijos" />
                    </div>

                    <div>
                        <InputLabel for="nivel_actividad" value="Nivel de Actividad" />
                        <select
                            id="nivel_actividad"
                            v-model="form.nivel_actividad"
                            class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                        >
                            <option :value="null">-- Selecciona --</option>
                            <option :value="1">Bajo (Tranquilo)</option>
                            <option :value="2">Medio (Normal)</option>
                            <option :value="3">Alto (Muy activo)</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.nivel_actividad" />
                    </div>

                    <div>
                        <InputLabel for="experiencia_mascotas" value="Experiencia con Mascotas" />
                        <select
                            id="experiencia_mascotas"
                            v-model="form.experiencia_mascotas"
                            class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                        >
                            <option :value="null">-- Selecciona --</option>
                            <option :value="0">Ninguna</option>
                            <option :value="1">Algo de experiencia</option>
                            <option :value="2">Mucha experiencia</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.experiencia_mascotas" />
                    </div>

                    <div>
                        <InputLabel for="tiempo_disponible" value="Horas Disponibles al Día" />
                        <TextInput
                            id="tiempo_disponible"
                            type="number"
                            min="0"
                            max="24"
                            class="mt-1 block w-full"
                            v-model="form.tiempo_disponible"
                        />
                        <InputError class="mt-2" :message="form.errors.tiempo_disponible" />
                    </div>
                </div>
            </div>

            <div v-if="mustVerifyEmail && usuario.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing" class="bg-orange-600 hover:bg-orange-700">
                    Guardar Cambios
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Guardado correctamente.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
